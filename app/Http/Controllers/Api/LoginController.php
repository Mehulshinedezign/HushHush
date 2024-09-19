<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\Product;
use App\Models\PushToken;
use App\Notifications\EmailOtpVerification;
use Illuminate\Http\Request;
use App\Notifications\VerificationEmail;
use App\Services\OtpService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\PersonalAccessToken;

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

                if ($user->role->name == 'admin') {
                    Auth::logout();
                    $isVerified = false;
                    return $this->apiResponse('error', '500', 'Invalid Credentials', ['errors' => 'Invalid Credentials'], $isVerified);
                }

                $isVerified = !is_null($user->email_verified_at) && $user->otp_is_verified == 1;
                $isActive = $user->status == 1;
                PushToken::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'fcm_token' => $request->fcm_token,
                        'device_id' => $request->device_id,
                        'device_type' => $request->device_type,
                    ]
                );

                $product_ids = Product::where('user_id', $user->id)->pluck('id');
                $product = (explode(",", implode(",", $product_ids->toArray())));


                $hasCompleteAddress = !empty($user->userDetail->complete_address) &&
                    !empty($user->userDetail->country);
                $addresAdded = false;

                if ($hasCompleteAddress) {
                    $addresAdded = true;
                } else {
                    $addresAdded = false;
                }

                $identity = $user->identity_verified_at;
                if (is_null($identity)) {
                    $identity = false;
                } else {
                    $identity = true;
                }


                $is_bankdetail = $user->bankAccount;
                if (is_null($is_bankdetail)) {
                    $is_added = false;
                } else {
                    $is_added = true;
                }

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
                        'profile_pic' => $user->frontend_profile_url,
                        'name' => $user->name,

                    ];

                    return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
                }

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
                return response()->json([
                    'status' => true,
                    'message' => 'Login Success!',
                    'data' => $response = [
                        'token' => $user->createToken('login')->plainTextToken,
                        'user_id' => $user->id,
                        'email_verified_at' => $user->email_verified_at,
                        'otp_is_verified' => $user->otp_is_verified,
                        'email' => $user->email,
                        'phone' => $user->country_code . $user->phone_number,
                        'profile_pic' => $user->frontend_profile_url,
                        'name' => $user->name,
                        'fcm_token' => $user->pushToken->fcm_token,
                        'device_type' => $user->pushToken->device_type,
                        'device_id' => $user->pushToken->device_id,
                        'is_added' => $is_added,
                        'identity' =>$identity,
                        // 'product' => $product,
                    ],
                    'product' => $product,
                    'verify' => $isVerified,
                    'addres_added'=>$addresAdded,
                ], 200);

                // $apiResponse = 'success';
                // $statusCode = 200;
                // $message = 'Login Success';
                // $response = [
                //     'token' => $user->createToken('login')->plainTextToken,
                //     'user_id' => $user->id,
                //     'email_verified_at' => $user->email_verified_at,
                //     'otp_is_verified' => $user->otp_is_verified,
                //     'email' => $user->email,
                //     'phone' => $user->country_code . $user->phone_number,
                //     'profile_pic' => $user->frontend_profile_url,
                //     'name' => $user->name,
                //     'fcm_token' => $user->pushToken->fcm_token,
                //     'device_type' => $user->pushToken->device_type,
                //     'device_id' => $user->pushToken->device_id,
                //     'is_added' => $is_added,
                //     'product' => $product,
                // ];
                // return $this->apiResponse($apiResponse, $statusCode, $message, $response, $isVerified);
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

            $token = $request->bearerToken();

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token not provided',
                ], 401);
            }

            $personalAccessToken = PersonalAccessToken::findToken($token);

            if (!$personalAccessToken) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid token',
                ], 401);
            }


            $user = $personalAccessToken->tokenable;

            if ($user) {

                if ($user->pushToken) {
                    $user->pushToken->delete();
                }

                $user->tokens()->delete();

                return response()->json([
                    'status' => true,
                    'message' => 'User logged out successfully!',
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthorized',
                ], 401);
            }
        } catch (\Throwable $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
