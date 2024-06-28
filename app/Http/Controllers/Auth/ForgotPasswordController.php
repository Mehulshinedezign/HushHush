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
use App\Http\Controllers\Auth\ForgotPasswordController as MainForgotPassword;
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
        if ($request->has('phone_number') && $request->input('phone_number.main') && $request->input('phone_number.full') ) {
            return $this->sendResetLinkToPhoneNumber($request);
        } else {
            $forgot = new MainforgotPassword();
            $forgot->sendResetPasswordLinkEmail($request);
            return redirect()->back();
        }
    }

    protected function sendResetLinkToEmail(Request $request)
    {
        $mainForgotPassword = new MainForgotPassword();
        return $mainForgotPassword->sendResetLinkEmail($request);
    }

    protected function sendResetLinkToPhoneNumber(Request $request)
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

        $phone = $request->input('phone_number.main');
        if (!$user) {
            session()->flash('status', 'User not found');
            return redirect()->back();
        }
        
        $otp = $this->otpService->generateOtp($user);
        $this->otpService->sendOtp($otp, $request->input('phone_number.full'));
        
        $request->session()->put('phone_number', $phone);

        session()->flash('status', 'OTP send successfully!');
        return view('auth.passwords.otp_verification');
         
     
    }
}