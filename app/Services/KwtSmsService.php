<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KwtSmsService
{
    private $apiUrl = 'https://www.kwtsms.com/API/send/';
    private $username;
    private $password;
    private $sender;

    public function __construct()
    {
        // Read credentials from env for safety and configurability
        $this->username = env('KWT_SMS_USERNAME', 'brickskw');
        $this->password = env('KWT_SMS_PASSWORD', 'sNdBfF@g988');
        $this->sender = env('KWT_SMS_SENDER', 'KWT-SMS');
    }

    public function sendOtp(string $phoneCountryCode, string $phoneNumber, string $otp): bool
    {
        // This method will format the number according to KWT SMS expectations
        // KWT expects numeric country code + number (no + sign). Example for Kuwait: 965XXXXXXXX
        try {
            // normalize input
            $country = preg_replace('/[^0-9]/', '', (string)$phoneCountryCode);
            $number = preg_replace('/[^0-9]/', '', (string)$phoneNumber);

            // if local number starts with a leading 0, drop it
            if (strlen($number) > 1 && $number[0] === '0') {
                $number = ltrim($number, '0');
            }

            // if the number already contains the country prefix, don't duplicate
            if ($country !== '' && strpos($number, $country) === 0) {
                $mobile = $number;
            } elseif ($country !== '') {
                $mobile = $country . $number;
            } else {
                // fallback: use number as-is
                $mobile = $number;
            }

            // Prepare the message
            $message = "{$otp} is your BRICKS verification code. Do not share this code with anyone.";

            // Build request params
            $params = [
                'username' => $this->username,
                'password' => $this->password,
                'sender'   => $this->sender,
                'mobile'   => $mobile,
                'lang'     => '1', // 1 for English
                'message'  => $message,
            ];

            // Log the outgoing request but do not include the password
            Log::info('KWT SMS API Request', [
                'username' => $this->username,
                'sender' => $this->sender,
                'mobile' => $mobile,
                'message' => $message,
            ]);

            // Make the API request as GET with query params and a timeout
            $response = Http::timeout(10)->get($this->apiUrl, $params);

            $status = $response->status();
            $body = trim($response->body());

            // Log status and body for debugging
            Log::info('KWT SMS API Response', [
                'status' => $status,
                'body'   => $body,
            ]);

            // KWT returns textual error codes like "ERR025" or a success id/string.
            // Treat any response containing 'ERR' as failure.
            if ($response->successful() && stripos($body, 'ERR') === false) {
                return true;
            }

            // if not successful or contains ERR, log and return false
            Log::error('Failed to send OTP via SMS', ['phone' => $mobile, 'response' => $body, 'status' => $status]);
            return false;
        } catch (\Exception $e) {
            Log::error('KWT SMS API Error', [
                'mobile' => $mobile ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }
}
