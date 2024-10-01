<?php
namespace App\Traits;

use Twilio\Rest\Client;
use Exception;
use Illuminate\Support\Facades\Log;

trait SmsTrait
{
    public function sendSms(string $phoneNumber, array $message)
    {
        // dd($phoneNumber);
        $account_sid = env("TWILIO_ACCOUNT_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $twilio_number = env("TWILIO_PHONE_NUMBER");

        // Ensure that the environment variables are correctly set
        if (empty($account_sid) || empty($auth_token) || empty($twilio_number)) {
            throw new Exception("Twilio configuration is missing or invalid.");
        }

        try {
            $client = new Client($account_sid, $auth_token);

            // The message part is always included
            $body = $message['message'];

            // If a 'route' is provided, append it to the message
            if (isset($message['route']) && !empty($message['route'])) {
                $body .= "\nClick here: " . $message['route'];
            }

            $client->messages->create('+918210331846', [
                'from' => $twilio_number,
                'body' => $body,
            ]);

            Log::info('SMS sent successfully to ' . $phoneNumber);
            return true;
        } catch (Exception $e) {
            Log::info('SMS sending failed: ' . $e->getMessage());
            return $e->getMessage();
        }
    }
}
