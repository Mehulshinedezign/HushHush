<?php

namespace App\Http\Controllers;

use App\Models\EmailOtp;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Notifications\VerificationEmail;
use App\Services\OtpService;
use App\Traits\SmsTrait;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class VerifyOtpController extends Controller
{
    use SmsTrait;
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
        $user = User::findOrFail(auth()->user()->id);

        $otp = $this->otpService->generateOtp($user);

        if ($type === 'email') {
            $user->notify(new VerificationEmail($user, $otp));

            EmailOtp::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'otp' => $otp,
                    'expires_at' => now()->addMinutes(15), // OTP expires after 15 minutes
                    'status' => 'pending', // Status is 'pending' until verified
                ]
            );
            $status = 'OTP resent successfully to your email.';
        } elseif ($type === 'phone_number') {
            if (empty($user->phone_number)) {
                return redirect()->route('auth.verify_otp_form', ['user' => $user->id])
                    ->with('error', 'No phone number available for this user.');
            }

            $message = "Your login OTP is " . $otp;
            $account_sid = env("TWILIO_ACCOUNT_SID");
            $auth_token = env("TWILIO_AUTH_TOKEN");
            $twilio_number = env("TWILIO_PHONE_NUMBER");

            if (empty($account_sid) || empty($auth_token) || empty($twilio_number)) {
                return redirect()->route('auth.verify_otp_form', ['user' => $user->id])
                    ->with('error', 'Twilio configuration is missing or invalid.');
            }

            try {
                $client = new Client($account_sid, $auth_token);
                $client->messages->create('+918210331846', [
                    'from' => $twilio_number, // Use the Twilio number from env
                    'body' => $message,
                ]);

                info('SMS sent successfully.');

                PhoneOtp::updateOrCreate(
                    ['user_id' => $user->id],
                    [
                        'otp' => $otp,
                        'expires_at' => now()->addMinutes(15), // OTP expires after 15 minutes
                        'status' => 'pending', // Status is 'pending' until verified
                    ]
                );
                $status = 'OTP resent successfully to your phone number.';
            } catch (\Exception $e) {
                info('SMS sending failed: ' . $e->getMessage());
                return redirect()->route('auth.verify_otp_form', ['user' => $user->id])
                    ->with('error', 'Failed to send OTP to phone number.');
            }
        } else {
            return redirect()->route('auth.verify_otp_form', ['user' => $user->id])
                ->with('error', 'Invalid OTP type.');
        }

        return redirect()->route('auth.verify_otp_form', ['user' => $user->id])
            ->with('status', $status);
    }
}
