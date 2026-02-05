<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * KwtSmsService
 * 
 * IMPORTANT: This service is restricted to OTP (One-Time Password) functionality ONLY.
 * Generic SMS notifications are disabled and should not be used.
 * 
 * @package App\Services
 */
class KwtSmsService
{
    protected $username;
    protected $password;
    protected $sender;
    protected $apiUrl = 'https://kwtsms.com/API/send/';

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

        // If it's a local 8-digit number, prepend the Kuwait country code (965)
        if (strlen($mobile) === 8) {
            $mobile = '965' . $mobile;
        }

        // Strict Kuwaiti format validation: 965 + 8 digits = 11 digits total
        if (strlen($mobile) !== 11 || !str_starts_with($mobile, '965')) {
            Log::error('KWT SMS Invalid Format - Only Kuwait numbers (965 + 8 digits) are allowed.', [
                'mobile' => $mobile,
            ]);
            return [
                'success' => false,
                'message' => 'Invalid mobile number. Only Kuwait numbers (e.g., 96565560520) are allowed.',
                'response' => null,
            ];
        }

        $timestamp = now()->format('Y-m-d H:i:s');
        $message = "Dear Bricks Community User, Here is your OTP {$otp}. DO NOT DISCLOSE THIS OTP to anyone! {$timestamp}";

        try {
            // Send GET request with query parameters
            $response = Http::timeout(30)->withoutVerifying()->get($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'sender'   => $this->sender,
                'mobile'   => $mobile,
                'lang'     => '1', // 1 for English, 2 for Arabic
                'message'  => $message,
            ]);

            $responseBody = trim($response->body());
            $statusCode = $response->status();

            Log::info('KWT SMS API Response', [
                'mobile' => $mobile,
                'status' => $statusCode,
                'response' => $responseBody,
            ]);

            // Check response body for error messages
            $isError = (
                stripos($responseBody, 'ERR') !== false ||
                stripos($responseBody, 'error') !== false ||
                stripos($responseBody, 'invalid') !== false ||
                stripos($responseBody, 'failed') !== false ||
                stripos($responseBody, 'No valid numbers') !== false
            );

            if ($response->successful() && !$isError) {
                Log::info('KWT SMS Success', [
                    'mobile' => $mobile,
                    'response' => $responseBody,
                ]);
                return [
                    'success' => true,
                    'message' => 'OTP sent successfully',
                    'response' => $responseBody,
                ];
            }

            Log::error('KWT SMS API Error', [
                'mobile' => $mobile,
                'status' => $statusCode,
                'response' => $responseBody,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send OTP: ' . $responseBody,
                'response' => $responseBody,
            ];

        } catch (\Exception $e) {
            Log::error('KWT SMS Exception', [
                'mobile' => $mobile,
                'error' => $e->getMessage(),
            ]);
            return [
                'success' => false,
                'message' => 'SMS Service Exception: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Send generic SMS via KWT SMS API
     *
     * @param string $mobile Mobile number (e.g., 96551557699)
     * @param string $message The message content
     * @param int $lang 1 for English, 2 for Arabic
     * @return array ['success' => bool, 'message' => string, 'response' => mixed]
     */
    public function sendSms(string $mobile, string $message, int $lang = 1): array
    {
        // KWT SMS service disabled for generic notifications as per user request.
        // It should only be used for OTP.
        Log::info('KWT SMS Generic Service is disabled. Skipping SMS sending.', [
            'mobile' => $mobile,
            'message' => $message,
            'lang' => $lang
        ]);

        return [
            'success' => true, 
            'message' => 'SMS service is disabled for notifications. Skipping sending.'
        ];
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









