<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Notifications\VerificationEmail;
use App\Services\OtpService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class VerifyOtpController extends Controller
{
    protected $otpService;

    public function __construct(OtpService $otpService = null)
    {
        $this->otpService = $otpService;
    }

    public function showVerifyOtpForm(Request $request)
    {

        $user = User::find(auth()->user()->id);
        return view('auth.verify_otp', compact('user'));
    }

    public function verifyEmailOtp(Request $request)
    {
        // dd($request);
        // $request->validate([
        //     'emailotp' => 'required|digits:6',
        // ], [
        //     'emailotp.required' => 'OTP is required',
        //     'emailotp.digits' => 'OTP must be 6 digits',
        // ]);

        $user = User::with('emailOtp', 'phoneOtp')->where('id', auth()->user()->id)->firstOrFail();

        if ($user->emailOtp->otp != $request->emailotp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        // if (Carbon::now() >= $user->emailOtp->expires_at) {
        //     return response()->json(['status' => false, 'message' => 'OTP has expired']);
        // }

        try {
            $user->emailOtp->update(['status' => '1']);
            // dd($request, $user);

            if ($user->phoneOtp->status == 1) {
                User::where("id", $user->id)->update(["status" => "1", "email_verified_at" => date('Y-m-d H:i:s'), 'email_verification_token' => null]);

                auth()->login($user);
                $url = route('index');
                return response()->json(['login' => 1, 'url' => $url]);
            }

            return response()->json(['status' => true, 'message' => 'Email OTP verified successfully']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }

    public function verifyPhoneOtp(Request $request)
    {
        // dd($request);
        // $request->validate([
        //     'phoneotp' => 'required|digits:6',
        // ], [
        //     'phoneotp.required' => 'OTP is required',
        //     'phoneotp.digits' => 'OTP must be 6 digits',
        // ]);

        $user = User::with('phoneOtp', 'emailOtp')->where('id', auth()->user()->id)->firstOrFail();
        if ($user->phoneOtp->otp != $request->phoneotp) {
            return response()->json(['status' => false, 'message' => 'Invalid OTP']);
        }

        // if (Carbon::now() >= $user->phoneOtp->expires_at) {
        //     return response()->json(['status' => false, 'message' => 'OTP has expired']);
        // }

        try {
            $user->phoneOtp->update(['status' => '1']);
            $user->update(['otp_is_verified' => '1']);

            if ($user->emailOtp->status == 1) {
                User::where("id", $user->id)->update(["status" => "1", "email_verified_at" => date('Y-m-d H:i:s'), 'email_verification_token' => null]);

                auth()->login($user);
                $url = route('index');
                return response()->json(['login' => 1, 'url' => $url]);
            }

            return response()->json(['status' => true, 'message' => 'Phone OTP verified successfully']);
        } catch (Exception $ex) {
            return response()->json(['status' => false, 'message' => $ex->getMessage()]);
        }
    }





    public function resendOtp(Request $request, $type)
    {
        // $userId = $request->query('user_id');
        $user = User::findOrFail(auth()->user()->id);

        if ($type === 'email') {
            $otp = $this->otpService->generateOtp($user);

            // Uncomment the following line to actually send the email OTP
            // $this->otpService->sendEmailOtp($user, $otp);
            $user->notify(new VerificationEmail($user, $otp));

            EmailOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => date('Y-m-d H:i:s'),
                    'status' => '0',
                ]
            );
        } elseif ($type === 'phone_number') {
            $otp = $this->otpService->generateOtp($user);
            // $message = "Login OTP is " . $otp;
            // $account_sid = env("TWILIO_SID");
            // $auth_token = env("TWILIO_TOKEN");
            // $twilio_number = env("TWILIO_FROM");
            // $client = new Client($account_sid, $auth_token);
            // $client->messages->create("+919463833241", [
            //     'from' => $twilio_number,
            //     'body' => $message
            // ]);
            // info('SMS Sent Successfully.');
            // }
            // return "Otp sent successfully";
            // Uncomment the following line to actually send the phone OTP
            // $this->otpService->sendPhoneOtp($user, $otp);

            PhoneOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => date('Y-m-d H:i:s'),
                    'status' => '0',
                ]
            );
        } else {
            return redirect()->route('auth.verify_otp_form', ['user' => $user->id])->with('error', 'Invalid OTP type.');
        }

        return redirect()->route('auth.verify_otp_form',  ['user' => $user->id])
            ->with('status', ' OTP resent successfully on your email/phone number.');
    }
}
