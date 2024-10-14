<?php
namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Config;

class LocationHelper
{
    public static function getLocationInfo()
    {
        $ip = static::getMyIPAddress();

        // $ip = '106.78.44.18';//india ip
        // $ip = '209.142.68.29';  // US IP address
        // $ip = '116.12.49.132';//singapore ip
        // $ip = '100.42.23.255';//canada ip
        // $ip = '100.42.108.255';//england ip

        try {
            $response = Http::get("http://ipinfo.io/$ip/json");

            if ($response->successful()) {
                $locationData = $response->json();

                // Retrieve the country code from the response
                $countryCode = $locationData['country'] ?? null;

                // Get the full country name from config/countries.php
                $countryName = Config::get("countries.$countryCode", $countryCode);

                // Replace the country code with the full country name in the response
                $locationData['country'] = $countryName;

                return [
                    'status' => 'success',
                    'data' => $locationData
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Unable to retrieve location data.',
                ];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    protected static function getMyIPAddress()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            return $_SERVER['REMOTE_ADDR'];
        }
    }
}
