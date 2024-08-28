<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Notifications\EmailOtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class ResetPasswordController extends Controller
{

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }
    public function resetPassword(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'password' => 'required|min:8',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $userId = $request->user_id;
            $user = User::find($userId);

            if (Hash::check($request->password, $user->password)) {

                $apiResponse = 'success';
                $statusCode = 422;
                $message = 'The new password must be different from the current password.';
                $response = [
                    'token' => $user->createToken('login')->plainTextToken,
                    'user_id' => $user->id,
                ];
                return $this->apiResponse($apiResponse, $statusCode, $message, $response, Null);
                // return response()->json([
                //     'status' => false,
                //     'errors' => ['password' => ['The new password must be different from the current password.']],
                // ], 422);
            }


            $user->password = Hash::make($request->password);
            $user->save();

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'Password reset successfully!';
            $response = [
                'token' => $user->createToken('login')->plainTextToken,
                'user_id' => $user->id,
            ];
            return $this->apiResponse($apiResponse, $statusCode, $message, $response, Null);

            // return response()->json([
            //     'status' => true,
            //     'message' => 'Password reset successfully!',
            // ], 200);
        } catch (\Throwable $e) {

            $isVerified = false;
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()], $isVerified);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while reset password.'
            ], 500);
            // return response()->json([
            //     'status' => 'error',
            //     'errors' => ['exception' => [$e->getMessage()]],
            // ], 500);
        }
    }


    public function resentOtp(Request $request, $type)
    {
        try {
            // Validate the input
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            // Retrieve the user
            $user = User::findOrFail($request->user_id);

            // Initialize variables for response message
            $otpSentMessage = 'Otp sent successfully';

            if ($type === 'phone_number') {
                // Prepare full phone number
                $full_number = $user->country_code . $user->phone_number;

                // Generate and save OTP
                $phoneOtp = '777777'; // This should be replaced with actual OTP generation logic
                PhoneOtp::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'otp' => $phoneOtp,
                        'expires_at' => now()->addMinutes(15),
                        'status' => '0',
                    ]
                );

                // Send OTP (this line is commented out, you should uncomment in actual use)
                // $this->otpService->sendOtp($phoneOtp, $full_number);

                $otpSentMessage .= ' on phone number.';
            } elseif ($type === 'email') {
                // Generate and save OTP
                $emailOtp = $this->otpService->generateOtp($user);
                EmailOtp::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'otp' => $emailOtp,
                        'expires_at' => now()->addMinutes(15),
                        'status' => '0',
                    ]
                );

                // Notify user via email
                $user->notify(new EmailOtpVerification($user, $emailOtp));

                $otpSentMessage .= ' on email.';
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid OTP type specified.',
                ], 400);
            }

            return response()->json([
                'status' => true,
                'message' => $otpSentMessage,
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
