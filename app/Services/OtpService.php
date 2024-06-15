<?php

namespace App\Services;

use App\Models\Otp;
use App\Models\UserOtp;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;

class OtpService
{
    public function generateOtp($user)
    {
        $otp = mt_rand(100000, 999999);

        UserOtp::create([
            'user_id' => $user->id,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes(1),
            'status' =>'0',
        ]);

        return $otp;
    }


    public function sendOtp($user, $otp)
    {
        $accountSid = config('services.twilio.sid');
        $authToken = config('services.twilio.token');
        $twilioNumber = config('services.twilio.phone_number');
        $messagingServiceSid = config('services.twilio.messaging_service_sid');

        $client = new Client($accountSid, $authToken);

        $toNumber = $this->formatPhoneNumber($user->phone_number);

        $params = [
            'body' => "Your OTP is: $otp",
            'to' => $toNumber
        ];

        if ($messagingServiceSid) {
            $params['messagingServiceSid'] = $messagingServiceSid;
        } else {
            $params['from'] = $twilioNumber;
        }

        try {
            $message = $client->messages->create(
                $toNumber,
                $params
            );
            Log::info("OTP sent successfully to {$toNumber}. Message SID: {$message->sid}");

        } catch (\Exception $e) {
            Log::error('OTP send FAIL: ' . $e->getMessage());
            throw $e;
        }

        // return redirect()->route('verify.otp');
    }

    private function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);

        if (strpos($phoneNumber, '+') !== 0) {
            $phoneNumber = '+91' . $phoneNumber;
        }

        return $phoneNumber;
    }
}
