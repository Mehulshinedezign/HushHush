<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Notifications\EmailOtpVerification;
use Illuminate\Http\Request;
use App\Notifications\VerificationEmail;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function login(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                $type => 'required',
                'password' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => false, 'errors' => $validator->errors()], 422);
            }

            $credentials = [$type => $request->$type, 'password' => $request->password];
            if (Auth::attempt($credentials)) {
                $user = Auth::user();

                $isVerified = !is_null($user->email_verified_at) || $user->otp_is_verified == 1;

                if (!$user->status) {
                    if ($type == 'email') {
                        $otp = $this->otpService->generateOtp($user);
                        $user->notify(new EmailOtpVerification($user,$otp));
                    } elseif ($type == 'phone_number') {
                        $country_code = $request->country_code;
                        $number = $request->phone_number;
                        // dd($country_code);
                        $full_number = $country_code . $number;

                        $otp = $this->otpService->generateOtp($user);
                        // $this->otpService->sendOtp($otp, $full_number);
                    }
                    $apiResponse = 'success';
                    $statusCode = 200;
                    $message = 'Your account status is inactive';
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                    ];
                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
                }

                if (!$isVerified) {

                    if ($type == 'email') {
                        $user->notify(new VerificationEmail($user));
                    } elseif ($type == 'phone_number') {
                        $country_code = $request->country_code;
                        $number = $request->phone_number;
                        // dd($country_code);
                        $full_number = $country_code . $number;

                        $otp = $this->otpService->generateOtp($user);
                        // $this->otpService->sendOtp($otp, $full_number);
                    }
                    $apiResponse = 'success';
                    $statusCode = 200;
                    $message = 'Please verify your email or phone';
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                    ];
                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
                }

                $apiResponse = 'success';
                $statusCode = 200;
                $message = 'Login Success';
                $response = [
                    'token' => $user->createToken('login')->plainTextToken,
                    'user_id' => $user->id,
                ];
                return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
            } else {
                $apiResponse = 'error';
                $statusCode = 401;
                $message = 'Invalid credentials';
                $response = [];
                $isVerified = false;
                return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
            }
        } catch (\Throwable $e) {
            $isVerified = false;
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()],$isVerified);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while login.'
            ], 500);
        }
    }




    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($user) {
                $user->tokens()->delete();
                $apiResponse = 'success';
                $statusCode = '200';
                $message = 'User logged out successfully!';
            } else {
                $apiResponse = 'error';
                $statusCode = '401';
                $message = 'Unauthorized';
            }

            return $this->apiResponse($apiResponse, $statusCode, $message);
        } catch (\Throwable $e) {
            return $this->apiResponse('error', '404', $e->getMessage(), ['errors' => $e->getMessage()]);
        }
    }
}
