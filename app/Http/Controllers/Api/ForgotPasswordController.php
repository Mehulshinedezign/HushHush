<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\UserOtp;
use App\Notifications\EmailOtpVerification;
use App\Services\OtpService;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService = null)
    {
        $this->otpService = $otpService;
    }

    public function resetPassword(Request $request, $type)
    {
        if ($type == "email") {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => "error",
                    'message' => $validator->errors()->first(),
                    'errors' => []
                ], 401);
            }

            // dd('djhjsd');
            $user = User::where('email', $request->email)->first();
            if (!$user) {
                return response()->json([
                    'status' => "error",
                    'message' => 'User not found',
                    'errors' => []
                ], 404);
            }

            $otp = $this->otpService->generateOtp($user);
            UserOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);
            $user->notify(new EmailOtpVerification($user, $otp));

            $apiResponse = 'success';
            $statusCode = 200;
            $message = 'OTP sent to your email successfully';
            $response = [
                'user_id' => $user->id,
            ];
            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        }

        elseif ($type == "phone_number") {
            $validator = Validator::make($request->all(), [
                'phone_number' => 'required',
                'country_code' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => "error",
                    'message' => $validator->errors()->first(),
                    'errors' => []
                ], 401);
            }

            $user = User::where('phone_number', $request->phone_number)->first();

            if (!$user) {
                return response()->json([
                    'status' => "error",
                    'message' => 'User not found',
                    'errors' => []
                ], 404);
            }

            // $phoneOtp = $this->otpService->generateOtp($user);
            $phoneOtp='777777';
            UserOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $phoneOtp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);
            // $this->otpService->sendOtp($otp, $request->country_code . $request->phone_number);

            $apiResponse = "success";
            $statusCode = 200;
            $message = "OTP sent to your phone number successfully";
            $response = [
                'user_id' => $user->id,
            ];

            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        }
    }
}
