<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\PushToken;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserDocuments;
use App\Notifications\EmailOtpVerification;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'status' => '0',
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                'email_verification_token' => Str::random(50),
                'country_code' => $request->country_code,
            ]);

            UserDetail::create([
                'user_id' => $user->id,
                // 'address1' =>$data['complete_address'],
                // 'about' =>$data['about'],
            ]);

            // $path = $data['gov_id']->store('user_documents');
            // $filePath = str_replace("public/", "", $path);
            // $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            // UserDocuments::create([
            //     'user_id' => $request->id,
            //     'file' => $fileExtension,
            //     'url' => $filePath,
            // ]);
    

            $country_code = $request->country_code;
            $number = $request->phone_number;
            $full_number = $country_code . $number;

            // Generate and save OTPs
            $phoneOtp = '777777';
            PhoneOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $phoneOtp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);
            // $this->otpService->sendOtp($phoneOtp, $full_number);

            $emailOtp = $this->otpService->generateOtp($user);
            EmailOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $emailOtp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);

            $user->notify(new EmailOtpVerification($user, $emailOtp));

            // Save push token
            PushToken::create([
                'user_id' => $user->id,
                'fcm_token' => $request->fcm_token,
                'device_id' => $request->device_id,
                'device_type' => $request->device_type,
            ]);
            // dd($request->all(),$user->pushToken);

            DB::commit();

            $apiResponse = 'success';
            $statusCode = '200';
            $message = "User registration successful. Please verify your email address or phone number.";

            $response = [
                'token' => $user->createToken('login')->plainTextToken,
                'user_id' => $user->id,
                'profile_pic' => $user->frontend_profile_url,
                'name' => $user->name,
                'device_type' =>$user->pushToken->device_type,
                'device_id'=> $user->pushToken->device_id,
                'fcm_token'=> $user->pushToken->fcm_token,
            ];

            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        } catch (\Throwable $e) {
            DB::rollBack();
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()], null);
        }
    }

    protected function validator(array $data)
    {
        $emailRegex = "/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/";
        $validation = [
            'name' => 'required|string|min:2|max:50',
            'email' => 'required|string|email|max:255|unique:users|regex:' . $emailRegex,
            'phone_number' => 'required|digits:' . config('validation.phone_minlength') . '|min:' . config('validation.phone_minlength') . '|max:' . config('validation.phone_maxlength'),
            'password' => 'required|string|min:8|max:32|confirmed',
            'country_code' => 'required',
            'fcm_token' => 'required|string',
            'device_id' => 'required|string',
            'device_type' => 'required|string',
        ];

        $message = [
            'name.required' => __('user.validations.nameRequired'),
            'name.string' => __('user.validations.nameString'),
            'name.min' => __('user.validations.nameMin'),
            'name.max' => __('user.validations.nameMax'),
            'email.required' => __('user.validations.emailRequired'),
            'email.string' => __('user.validations.emailString'),
            'email.email' => __('user.validations.emailType'),
            'email.regex' => __('user.validations.emailType'),
            'email.max' => __('user.validations.emailMax'),
            'email.unique' => __('user.validations.emailUnique'),
            'phone_number.required' => __('customvalidation.user.phone_number.required'),
            'phone_number.digits' => __('customvalidation.user.phone_number.digits'),
            'phone_number.min' => __('customvalidation.user.phone_number.min', ['min' => config('validation.phone_minlength'), 'max' => config('validation.phone_maxlength')]),
            'phone_number.max' => __('customvalidation.user.phone_number.max', ['min' => config('validation.phone_minlength'), 'max' => config('validation.phone_maxlength')]),
            'password.required' => __('user.validations.passwordRequired'),
            'password.string' => __('user.validations.passwordString'),
            'password.min' => 'Password must be 8-32 characters long',
            'password.max' => 'Password must be 8-32 characters long',
            'password.confirmed' => __('user.validations.passwordConfirmed'),
            'fcm_token.required' => 'FCM token is required',
            'fcm_token.string' => 'FCM token must be a string',
            'device_id.required' => 'Device ID is required',
            'device_id.string' => 'Device ID must be a string',
            'device_type.required' => 'Device type is required',
            'device_type.string' => 'Device type must be a string',
        ];

        return Validator::make($data, $validation, $message);
    }

    public function verifyOtp(Request $request, $type)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id',
                'otp' => 'required|digits:6',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validator->errors()->first(),
                    'errors' => []
                ], 422);
            }

            $user = User::find($request->input('user_id'));
            $otp = $request->input('otp');

            if ($this->otpService->verifyOtpByType($user, $otp, $type)) {
                $apiResponse = 'success';
                $statusCode = 200;
                $message = 'OTP verified successfully.';

                $response = [
                    'token' => $user->createToken('login')->plainTextToken,
                    'user_id' => $user->id,
                ];
            } else {
                $apiResponse = 'error';
                $statusCode = 404;
                $message = "Invalid OTP";
                $response = [];
            }

            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        } catch (\Throwable $e) {
            $apiResponse = 'error';
            $statusCode = 500;
            $message = $e->getMessage();
            $response = [];

            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        }
    }
}
