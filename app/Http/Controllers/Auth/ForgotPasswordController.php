<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\OtpService;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Contracts\Session\Session;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    protected $otpService;

    public function __construct(OtpService $otpService = null)
    {
        $this->otpService = $otpService;
    }

    public function sendResetLinkEmail(Request $request)
    {
        $full = $request->input('phone_number.full');
        $main = $request->input('phone_number.main');

        if ($request->has('phone_number') && $main && $full) {
            return $this->sendResetLinkToPhoneNumber($full, $main, $request);
        } else {
            $this->validateEmail($request);

            $response = $this->broker()->sendResetLink(
                $this->credentials($request)
            );

            return $response == Password::RESET_LINK_SENT
                ? $this->sendResetLinkResponse($request, $response)
                : $this->sendResetLinkFailedResponse($request, $response);
        }
    }

    //     $forgot = new MainforgotPassword();
    //     $forgot->sendResetPasswordLinkEmail($request);
    //     return redirect()->back();
    // }


    // protected function sendResetPasswordLinkEmail(Request $request)
    // {
    //     $mainForgotPassword = new MainForgotPassword();
    //     return $mainForgotPassword->sendResetLinkEmail($request);
    // }

    protected function sendResetLinkToPhoneNumber($full_number, $main_number, Request $request)
    {
        if (!$this->otpService) {
            return response()->json(['message' => 'OTP service not available'], 500);
        }

        $user = User::where('phone_number', $main_number)->first();

        if (!$user) {
            session()->flash('status', 'User not found');
            return redirect()->back();
        }

        $otp = $this->otpService->generateOtp($user);
        // $this->otpService->sendOtp($otp, $full_number);

        $request->session()->put('phone_number', $main_number);
        session()->forget('error');
        session()->flash('status', 'OTP sent successfully!');
        return view('auth.passwords.otp_verification');
    }

    public function sendResetLink(Request $request)
    {
        if ($request->has('phone_number') && $request->input('phone_number.main') && $request->input('phone_number.full')) {
            return $this->resetLinkToPhoneNumber($request);
        } else {
            return $this->resetLinkToEmail($request);
        }
    }

    public function resetLinkToEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $response = $this->broker()->sendResetLink($request->only('email'));

        return $response;
    }

    public function resetLinkToPhoneNumber(Request $request)
    {
        if (!$this->otpService) {
            return response()->json(['message' => 'OTP service not available'], 500);
        }

        $validator = Validator::make($request->all(), [
            'phone_number.full' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::where('phone_number', $request->input('phone_number.main'))->first();

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $otp = $this->otpService->generateOtp($user);
        // $this->otpService->sendOtp($otp, $request->input('phone_number.full'));

        return response()->json(['message' => 'OTP sent successfully'], 200);
    }

    protected function broker()
    {
        return Password::broker();
    }
}
