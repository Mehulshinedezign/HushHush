<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\PhoneOtp;
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

                $isVerified = !is_null($user->email_verified_at) && $user->otp_is_verified == 1;
                $isActive = $user->status == 1;

                // Check verification and send OTP if needed
                if (!$isVerified) {
                    if (is_null($user->email_verified_at) && $user->otp_is_verified != 1) {
                        // Generate OTP for both email and phone
                        $emailOtp = $this->otpService->generateOtp($user);
                        EmailOtp::updateOrCreate(['user_id' => $user->id], [
                            'otp' => $emailOtp,
                            'expires_at' => now()->addMinutes(15),
                            'status' => '0',
                        ]);
                        $user->notify(new EmailOtpVerification($user, $emailOtp));

                        $country_code = $request->country_code;
                        $number = $request->phone_number;
                        $full_number = $country_code . $number;

                        // $phoneOtp = $this->otpService->generateOtp($user);
                        $phoneOtp = '777777';
                        PhoneOtp::updateOrCreate(['user_id' => $user->id], [
                            'otp' => $phoneOtp,
                            'expires_at' => now()->addMinutes(15),
                            'status' => '0',
                        ]);
                        // $this->otpService->sendOtp($phoneOtp, $full_number);
                    } else {
                        if (is_null($user->email_verified_at)) {
                            $emailOtp = $this->otpService->generateOtp($user);
                            EmailOtp::updateOrCreate(['user_id' => $user->id], [
                                'otp' => $emailOtp,
                                'expires_at' => now()->addMinutes(15),
                                'status' => '0',
                            ]);
                            $user->notify(new EmailOtpVerification($user, $emailOtp));
                        }

                        if ($user->otp_is_verified != 1) {
                            $country_code = $request->country_code;
                            $number = $request->phone_number;
                            $full_number = $country_code . $number;

                            // $phoneOtp = $this->otpService->generateOtp($user);
                            $phoneOtp = '777777';
                            PhoneOtp::updateOrCreate(['user_id' => $user->id], [
                                'otp' => $phoneOtp,
                                'expires_at' => now()->addMinutes(15),
                                'status' => '0',
                            ]);
                            // $this->otpService->sendOtp($phoneOtp, $full_number);
                        }
                    }

                    $message = 'Please verify your email or phone';
                    $apiResponse = 'success';
                    $statusCode = 200;
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                        'email_verified_at' => $user->email_verified_at,
                        'otp_is_verified' => $user->otp_is_verified,
                        'email' => $user->email,
                        'phone' => $user->country_code . $user->phone_number,
                        'name' => $user->frontend_profile_url,
                        'profile_pic' => $user->name,
                    ];

                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
                }

                // Check if user status is active
                if (!$isActive) {
                    $message = 'Your account status is inactive';
                    $apiResponse = 'success';
                    $statusCode = 200;
                    $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                        'email_verified_at' => $user->email_verified_at,
                        'otp_is_verified' => $user->otp_is_verified,
                        'email' => $user->email,
                        'phone' => $user->country_code . $user->phone_number,
                        'name' => $user->frontend_profile_url,
                        'profile_pic' => $user->name,
                    ];

                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
                }

                // If verified and active, login successfully
                $apiResponse = 'success';
                $statusCode = 200;
                $message = 'Login Success';
                $response = [
                    'token' => $user->createToken('login')->plainTextToken,
                    'user_id' => $user->id,
                    'email_verified_at' => $user->email_verified_at,
                    'otp_is_verified' => $user->otp_is_verified,
                    'email' => $user->email,
                    'phone' => $user->country_code . $user->phone_number,
                    'profile_pc' => $user->frontend_profile_url,
                    'name' => $user->name,
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
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()], $isVerified);
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
