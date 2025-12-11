<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class KwtSmsService
{
    protected $username;
    protected $password;
    protected $sender;
    protected $apiUrl = 'https://www.kwtsms.com/API/send/';

    public function __construct()
    {
        $this->username = config('services.kwt_sms.username');
        $this->password = config('services.kwt_sms.password');
        $this->sender = config('services.kwt_sms.sender');
    }

    /**
     * Send OTP SMS via KWT SMS API
     *
     * @param string $mobile Mobile number with country code (e.g., 96551557699)
     * @param string $otp The OTP code to send
     * @return array ['success' => bool, 'message' => string, 'response' => mixed]
     */
    public function sendOtp(string $mobile, string $otp): array
    {
        // Clean mobile number - remove spaces, dashes, plus signs
        $mobile = preg_replace('/[^0-9]/', '', $mobile);
        $timestamp = now()->format('Y-m-d H:i:s');
        // Build the message
        $message = "Dear Bricks Community User, Here is your OTP: {$otp}. DO NOT DISCLOSE THIS OTP to anyone!  {$timestamp}";
        try {
            // Send GET request with query parameters
            $response = Http::timeout(10)->get($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'sender' => $this->sender,
                'mobile' => $mobile,
                'lang' => '1', // 1 for English, 2 for Arabic
                'message' => $message,
            ]);

            $responseBody = $response->body();
            $statusCode = $response->status();

            Log::info('KWT SMS API Response', [
                'mobile' => $mobile,
                'status' => $statusCode,
                'response' => $responseBody,
            ]);

            // Check if request was successful
            if ($response->successful()) {
                // KWT SMS typically returns success messages
                // You may need to adjust this based on actual API response format
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'response' => $responseBody,
                ];
            } else {
                Log::error('KWT SMS API Error', [
                    'mobile' => $mobile,
                    'status' => $statusCode,
                    'response' => $responseBody,
                ]);

                return [
                    'success' => false,
                    'message' => 'Failed to send OTP',
                    'response' => $responseBody,
                ];
            }
        } catch (\Exception $e) {
            Log::error('KWT SMS Exception', [
                'mobile' => $mobile,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Error sending OTP: ' . $e->getMessage(),
                'response' => null,
            ];
        }
    }

    /**
     * Format phone number for KWT SMS (country code + number without plus or spaces)
     *
     * @param string $countryCode e.g., "+965"
     * @param string $phoneNumber e.g., "51557699"
     * @return string e.g., "96551557699"
     */
    public static function formatMobileNumber(string $countryCode, string $phoneNumber): string
    {
        // Remove all non-numeric characters
        $countryCode = preg_replace('/[^0-9]/', '', $countryCode);
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        return $countryCode . $phoneNumber;
    }
}







