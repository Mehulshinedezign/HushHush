<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserDetail;
use App\Models\UserDocuments;
use App\Notifications\EmailOtpVerification;
use App\Notifications\VerificationEmail;
use App\Services\OtpService;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService)
    {
        $this->otpService = $otpService;
    }

    public function register(Request $request)
    {
        // dd('hello');
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::create([
                'username' => $request->username,
                'name' => $request->name,
                'status' => '0',
                'email' => $request->email,
                'phone_number' => $request->phone_number,
                'password' => Hash::make($request->password),
                // 'zipcode' => $request->zipcode,
                'email_verification_token' => Str::random(50),
                'country_code' => $request->country_code,
            ]);

            // UserDetail::create([
            //     'user_id' => $user->id,
            //     'address1' => $request->complete_address ?? NULL,
            //     'about' => $request->about ?? NULL,
            // ]);


            // $path = $request->gov_id->store('user_documents');
            // $filePath = str_replace("public/", "", $path);
            // $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);

            // UserDocuments::create([
            //     'user_id' => $user->id,
            //     'file' => $fileExtension,
            //     'url' => $filePath,
            // ]);

            $country_code = $request->country_code;
            $number = $request->phone_number;
            // dd($country_code);
            $full_number = $country_code . $number;

            $otp = $this->otpService->generateOtp($user);
            // $this->otpService->sendOtp($otp, $full_number);

            // $otp = $this->otpService->generateOtp($user);
            $user->notify(new EmailOtpVerification($user, $otp));

            $apiResponse = 'success';
            $statusCode = '200';
            $message = "User Registration successful. Please verify your email address or phone number .";

            $response = [
                'token' => $user->createToken('login')->plainTextToken,
                'user_id' => $user->id,

            ];
            return $this->apiResponse($apiResponse, $statusCode, $message, $response, null);
        } catch (\Throwable $e) {
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()], null);
            return response()->json([
                'status' => 'error',
                'message' => 'Something went wrong while sending the email verification. Please try again later.'
            ], 500);
        }
    }

    protected function validator(array $data)
    {

        $emailRegex = "/^[a-zA-Z]+[a-zA-Z0-9_\.\-]*@[a-zA-Z]+(\.[a-zA-Z]+)*[\.]{1}[a-zA-Z]{2,10}$/";
        $validation = [
            // 'username' => 'required|min:3|max:50|unique:users',
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:255|unique:users|regex:' . $emailRegex,
            'phone_number' => 'required|digits:' . config('validation.phone_minlength') . '|min:' . config('validation.phone_minlength') . '|max:' . config('validation.phone_maxlength'),
            'password' => 'required|string|min:8|max:32|confirmed',
            // 'complete_address' => 'required',
            // 'gov_id' => 'required|file|mimes:jpg,png,jpeg,pdf|max:2048',
            'country_code' => 'required'
        ];

        $message = [
            // 'username.required' => __('customvalidation.user.username.required'),
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

            // 'complete_address' => __('customvalidation.user.complete_address.required'),
            // 'complete_address.min' => __('user.validations.completeAddressMin'),
            // 'complete_address.max' => __('user.validations.completeAddressMax'),

            // 'gov_id' => __('customvalidation.user.gov_id.required'),
            // 'gov_id.file' => __('customvalidation.user.gov_id.file'),
            // 'gov_id.max' => __('customvalidation.user.gov_id.max_size'),

        ];

        return Validator::make($data, $validation, $message);
    }

    public function verifyOtp(Request $request)
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

            if ($this->otpService->verifyOtp($user, $otp)) {
                $user->update(['status' => '1']);

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
