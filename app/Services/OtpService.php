<?php

namespace App\Services;

use App\Models\EmailOtp;
use App\Models\Otp;
use App\Models\PhoneOtp;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp($user)
    {
        $otp = mt_rand(100000, 999999);
        // $otp = '123456';



        return $otp;
    }


    public function sendOtp($otp, $full_phone_number)
    {
        // try {
        // $otp = rand(100000, 999999);
        // $otp = LoginOtp::updateOrCreate(["user_id" => $user->id], ["otp" => $otp, "expire_at" => now()->addMinutes(2)]);

        // if ($method == "email") {

        //     $user->notify(new SendLoginOtp($otp->otp));
        // }
        // else {
        $message = "Login OTP is " . $otp;
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_TOKEN");
        $twilio_number = env("TWILIO_FROM");
        $client = new Client($account_sid, $auth_token);
        $client->messages->create("+919463833241", [
            'from' => $twilio_number,
            'body' => $message
        ]);
        info('SMS Sent Successfully.');
        // }
        return "Otp sent successfully";
        // } catch (Exception $e) {

        //     throw ValidationException::withMessages([
        //         $method => [$e->getMessage()],
        //     ]);

        // }
    }

    // public function verifyOtp($user, $otp)
    // {

    //     if($type)
    //     $userOtp = UserOtp::where('user_id', $user->id)
    //         ->where('otp', $otp)
    //         ->where('expires_at', '>', Carbon::now())
    //         ->where('status', '0')
    //         ->first();

    //     if (!$userOtp) {
    //         // dd('here',$userOtp);
    //         return false;
    //     }

    //     $userOtp->update(['status' => '1']);

    //     $user=User::Where('id',$user->id);
    //     $user->update(['otp_is_verified'=>true,'email_verified_at'=>carbon::now()]);

    //     return true;
    // }



    // public function verifyOtpByType($user, $otp, $type)
    // {
    //     $otpModel = $type === 'phone_number' ? PhoneOtp::class : EmailOtp::class;
    //     $userOtp = $otpModel::where('user_id', $user->id)
    //         ->where('otp', $otp)
    //         ->where('expires_at', '>', now())
    //         ->where('status', '0')
    //         ->first();

    //     if (!$userOtp) {
    //         return false;
    //     }

    //     $userOtp->update(['status' => '1']);

    //     if ($type === 'phone_number') {
    //         $user->update(['otp_is_verified' => true]);
    //     } else if ($type === 'email') {
    //         $user->update(['email_verified_at' => Carbon::now()]);
    //     }

    //     if ($user->email_verified_at && $user->otp_is_verified) {
    //         $user->update(['status' => '1']);
    //     }

    //     return true;
    // }

    public function verifyOtpByType($user, $otp, $type)
    {
        if ($type === 'phone_number') {
            $otpModel = PhoneOtp::class;
        } elseif ($type === 'email') {
            $otpModel = EmailOtp::class;
        } elseif ($type === 'reset') {
            $otpModel = UserOtp::class;
        } else {
            return false;
        }

        $userOtp = $otpModel::where('user_id', $user->id)
            ->where('otp', $otp)
            ->where('expires_at', '>', now())
            ->where('status', '0')
            ->first();

        if (!$userOtp) {
            return false;
        }

        $userOtp->update(['status' => '1']);

        if ($type === 'phone_number') {
            $user->update(['otp_is_verified' => true]);
        } elseif ($type === 'email') {
            $user->update(['email_verified_at' => Carbon::now()]);
        } elseif ($type === 'reset') {
            // Custom handling for reset type
            $user->update(['status' => '1']); // Update this as needed
        }

        if ($user->email_verified_at && $user->otp_is_verified) {
            $user->update(['status' => '1']);
        }

        return true;
    }

}
