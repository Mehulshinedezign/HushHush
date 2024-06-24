<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\VerificationEmail;
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

    public function register(Request $request)
    {
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
                'zipcode' => $request->zipcode,
                'email_verification_token' => Str::random(50),
            ]);

            $user->notify(new VerificationEmail($user));

            $apiResponse = 'success';
            $statusCode = '200';
            $message = "User Registration successful. Please verify your email address.";

            return $this->apiResponse($apiResponse, $statusCode, $message);

        } catch (\Throwable $e) {
            return $this->apiResponse('error', '500', $e->getMessage(), ['errors' => $e->getMessage()]);
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
            'username' => 'required|min:3|max:50|unique:users',
            'name' => 'required|string|min:3|max:50',
            'email' => 'required|string|email|max:255|unique:users|regex:' . $emailRegex,
            'phone_number' => 'required|digits:' . config('validation.phone_minlength') . '|min:' . config('validation.phone_minlength') . '|max:' . config('validation.phone_maxlength'),
            'zipcode' => 'required',
            'password' => 'required|string|min:8|max:32|confirmed',
        ];

        $message = [
            'username.required' => __('customvalidation.user.username.required'),
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
            'zipcode.required' => __('customvalidation.user.zipcode.required'),
        ];

        return Validator::make($data, $validation, $message);
    }
}
