<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\User;
use App\Models\UserOtp;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp($user)
    {
        // $otp = mt_rand(100000, 999999);
        $otp = '123456';

        // UserOtp::create([
        //     'user_id' => $user->id,
        //     'otp' => $otp,
        //     'expires_at' => Carbon::now()->addMinutes(50),
        //     'status' => '0',
        // ]);

        return $otp;
    }


    public function sendOtp($otp, $full_phone_number)
    {
        // try {
        $otp = rand(100000, 999999);
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

    //     $user = User::Where('id', $user->id);
    //     $user->update(['otp_is_verified' => true]);

    //     return true;
    // }
}
