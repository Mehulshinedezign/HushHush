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
        // dd("here",$request->all());

        $full =  $request->input('phone_number.full');
        $main = $request->input('phone_number.main');
        // dd($full);
        if ($request->has('phone_number') && $request->input('phone_number.main') && $request->input('phone_number.full') ) {
            return $this->sendResetLinkToPhoneNumber($full,$main,$request);
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


    protected function sendResetLinkToPhoneNumber($full_number,$main_number,Request $request)
    {
        // dd($full_number,$main_number);
        // dd("WWWWWWWWWW$",User::where('phone_number', $main_number)->first());
        
        // if (!$this->otpService) {
        //     return response()->json(['message' => 'OTP service not available'], 500);
        // }

        $user = User::where('phone_number', $main_number)->first();

        // dd("DONE",$user);
        $phone = $request->input('phone_number.main');
        if (!$user) {
            session()->flash('status', 'User not found');
            return redirect()->back();
        }
        
        $otp = $this->otpService->generateOtp($user);
        $this->otpService->sendOtp($otp, $full_number);
        
        $request->session()->put('phone_number', $main_number);
        session()->forget('error');
        session()->flash('status', 'OTP send successfully!');
        return view('auth.passwords.otp_verification');
         
     
    }
}