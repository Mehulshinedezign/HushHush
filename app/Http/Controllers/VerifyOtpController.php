<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Services\OtpService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class VerifyOtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService = null)
    {
        $this->otpService = $otpService;
    }

    public function showVerifyOtpForm(Request $request)
    {
        $user = User::find($request->user()->id); // Use request to get the authenticated user ID
        return view('auth.verify_otp', compact('user'));
    }

    public function verifyEmailOtp(Request $request)
    {
        $request->validate([
            'emailotp' => 'required|digits:6',
        ], [
            'emailotp.required' => 'OTP is required',
            'emailotp.digits' => 'OTP must be 6 digits',
        ]);

        $user = User::with('phoneOtp', 'emailOtp')->where('id', $request->user_id)->first();

        if ($user->emailOtp->otp != $request->emailotp) {
            return redirect()->back()->with('error', 'Email verification token is invalid.');
        }

        $expiry = Carbon::now()->subMinutes(15);

        if ($user->emailOtp->created_at <= $expiry) {
            return redirect()->route('login')->with('error', 'Email verification token has expired.');
        }

        try {
            EmailOtp::where('user_id', $user->id)->update(['email_verified_at' => now()]);
            if (isset($user->phoneOtp->status) && $user->phoneOtp->status == '1') {
                auth()->login($user);
                return response()->json(['login' => 1]);
            }
            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }
    }

    public function verifyPhoneOtp(Request $request)
    {
        $request->validate([
            'phoneotp' => 'required|digits:6',
        ], [
            'phoneotp.required' => 'OTP is required',
            'phoneotp.digits' => 'OTP must be 6 digits',
        ]);

        $user = User::with('phoneOtp', 'emailOtp')->where('id', $request->user_id)->first();

        if ($user->phoneOtp->otp != $request->phoneotp) {
            return redirect()->back()->with('error', 'Phone verification token is invalid.');
        }

        $expiry = Carbon::now()->subMinutes(15);

        if ($user->phoneOtp->created_at <= $expiry) {
            return redirect()->route('login')->with('error', 'Phone verification token has expired.');
        }

        try {
            PhoneOtp::where('user_id', $user->id)->update(['otp_is_verified' => "1"]);
            if (isset($user->emailOtp->status) && $user->emailOtp->status == '1') {
                auth()->login($user);
                return response()->json(['login' => 1]);
            }
            return response()->json(['status' => true]);
        } catch (Exception $ex) {
            return redirect()->route('login')->with('error', $ex->getMessage());
        }
    }

    public function resendOtp(Request $request, $type)
    {
        $userId = $request->query('user_id');
        $user = User::findOrFail($userId);

        if ($type === 'email') {
            $otp = $this->otpService->generateOtp($user);
            // $this->otpService->sendEmailOtp($user, $otp);

            EmailOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);
        } elseif ($type === 'phone_number') {
            $otp = $this->otpService->generateOtp($user);
            // $this->otpService->sendPhoneOtp($user, $otp);

            PhoneOtp::updateOrCreate(['user_id' => $user->id], [
                'otp' => $otp,
                'expires_at' => now()->addMinutes(15),
                'status' => '0',
            ]);
        } else {
            return redirect()->route('auth.verify_otp_form', ['user_id' => $user->id])->with('error', 'Invalid OTP type.');
        }

        return redirect()->route('auth.verify_otp_form', ['user_id' => $user->id])
            ->with('status', ucfirst($type) . ' OTP resent successfully.');
    }


}
