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

        $user = User::with('emailOtp', 'phoneOtp')->where('id', $request->user_id)->firstOrFail();

        if ($user->emailOtp->otp != $request->emailotp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        if (Carbon::now() >= $user->emailOtp->expires_at) {
            return response()->json(['status' => false, 'message' => 'OTP has expired']);
        }

        try {
            $user->emailOtp->update(['status' => '1']);
            $user->update(['email_verified_at' => carbon::now()]);

            if ($user->email_verified_at && $user->otp_is_verified) {
                auth()->login($user);
                return response()->json(['login' => 1]);
            }

            return response()->json(['status' => true, 'message' => 'Email OTP verified successfully']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
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

        $user = User::with('phoneOtp', 'emailOtp')->where('id', $request->user_id)->firstOrFail();

        if ($user->phoneOtp->otp != $request->phoneotp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        if (Carbon::now() >= $user->phoneOtp->expires_at) {
            return response()->json(['status' => false, 'message' => 'OTP has expired']);
        }

        try {
            $user->phoneOtp->update(['status' => '1']);
            $user->update(['otp_is_verified' => '1']);

            if ($user->email_verified_at && $user->otp_is_verified) {
                auth()->login($user);
                return response()->json(['login' => 1]);
            }

            return response()->json(['status' => true, 'message' => 'Phone OTP verified successfully']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }




    public function resendOtp(Request $request, $type)
    {
        $userId = $request->query('user_id');
        $user = User::findOrFail($userId);

        if ($type === 'email') {
            $otp = $this->otpService->generateOtp($user);

            // Uncomment the following line to actually send the email OTP
            // $this->otpService->sendEmailOtp($user, $otp);

            EmailOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(15),
                    'status' => '0',
                ]
            );
        } elseif ($type === 'phone_number') {
            $otp = $this->otpService->generateOtp($user);

            // Uncomment the following line to actually send the phone OTP
            // $this->otpService->sendPhoneOtp($user, $otp);

            PhoneOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(15),
                    'status' => '0',
                ]
            );
        } else {
            return redirect()->route('auth.verify_otp_form', ['user_id' => $user->id])->with('error', 'Invalid OTP type.');
        }

        return redirect()->route('auth.verify_otp_form', ['user_id' => $user->id])
            ->with('status', ucfirst($type) . ' OTP resent successfully.');
    }
}
