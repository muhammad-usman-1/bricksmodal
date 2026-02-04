<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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

        // Validate mobile number format
        if (empty($mobile) || strlen($mobile) < 8) {
            Log::error('KWT SMS Invalid Mobile Number', [
                'mobile' => $mobile,
            ]);
            return [
                'success' => false,
                'message' => 'Invalid mobile number format',
                'response' => null,
            ];
        }

        $timestamp = now()->format('Y-m-d H:i:s');
        // Build the message
        $message = "Dear Bricks Community User, Here is your OTP {$otp}. DO NOT DISCLOSE THIS OTP to anyone!  {$timestamp}";

        // For Kuwait numbers (starting with 965), try full format first (works for KWT SMS API)
        $formatsToTry = [];
        if (strlen($mobile) >= 11 && substr($mobile, 0, 3) === '965') {
            // Kuwait number: try full format first (with country code) - this works for KWT SMS API
            $formatsToTry[] = ['format' => $mobile, 'type' => 'full (with country code)'];
            // Fallback: try local format (without country code) if full format fails
            $localNumber = substr($mobile, 3);
            // Remove leading zero if present (Kuwait numbers sometimes have leading 0)
            if (substr($localNumber, 0, 1) === '0') {
                $localNumber = substr($localNumber, 1);
            }
            $formatsToTry[] = ['format' => $localNumber, 'type' => 'local (without country code)'];
        } else {
            // For other numbers, try as-is first
            $formatsToTry[] = ['format' => $mobile, 'type' => 'original'];
        }

        $lastError = null;
        foreach ($formatsToTry as $formatInfo) {
            $mobileToTry = $formatInfo['format'];

            try {
                // Send GET request with query parameters
                $response = Http::timeout(30)->withoutVerifying()->get($this->apiUrl, [
                    'username' => $this->username,
                    'password' => $this->password,
                    'sender' => $this->sender,
                    'mobile' => $mobileToTry,
                    'lang' => '1', // 1 for English, 2 for Arabic
                    'message' => $message,
                ]);

                $responseBody = trim($response->body());
                $statusCode = $response->status();

                Log::info('KWT SMS API Response', [
                    'mobile' => $mobileToTry,
                    'format_type' => $formatInfo['type'],
                    'status' => $statusCode,
                    'response' => $responseBody,
                ]);

                // Check response body for error messages (API may return 200 with error in body)
                $isError = false;
                $isAccountError = false; // Account/balance errors that won't be fixed by trying different formats

                if (stripos($responseBody, 'ERR') !== false ||
                    stripos($responseBody, 'error') !== false ||
                    stripos($responseBody, 'invalid') !== false ||
                    stripos($responseBody, 'failed') !== false ||
                    stripos($responseBody, 'No valid numbers') !== false) {
                    $isError = true;
                }

                // Check for account/balance errors - these won't be fixed by trying different formats
                if (stripos($responseBody, 'ERR010') !== false ||
                    stripos($responseBody, 'no balance') !== false ||
                    stripos($responseBody, 'insufficient') !== false ||
                    stripos($responseBody, 'account') !== false && stripos($responseBody, 'balance') !== false) {
                    $isAccountError = true;
                    $isError = true;
                }

                // Check if request was successful (HTTP 200-299 and no error in body)
                if ($response->successful() && !$isError) {
                    Log::info('KWT SMS Success', [
                        'mobile' => $mobileToTry,
                        'format_type' => $formatInfo['type'],
                        'response' => $responseBody,
                    ]);
                    return [
                        'success' => true,
                        'message' => 'OTP sent successfully',
                        'response' => $responseBody,
                    ];
                } else {
                    // Store error
                    $lastError = [
                        'mobile' => $mobileToTry,
                        'format_type' => $formatInfo['type'],
                        'status' => $statusCode,
                        'response' => $responseBody,
                    ];

                    // If it's an account/balance error, stop trying other formats (won't help)
                    if ($isAccountError) {
                        Log::error('KWT SMS Account/Balance Error - Stopping format attempts', $lastError);
                        break; // Exit the loop, no point trying other formats
                    } else {
                        // Format error - continue to next format
                        Log::warning('KWT SMS Format failed, trying next format', $lastError);
                    }
                }
            } catch (\Exception $e) {
                $lastError = [
                    'mobile' => $mobileToTry,
                    'format_type' => $formatInfo['type'],
                    'error' => $e->getMessage(),
                ];
                Log::warning('KWT SMS Exception, trying next format', $lastError);
            }
        }

        // All formats failed or account error occurred
        $errorResponse = $lastError['response'] ?? $lastError['error'] ?? 'Unknown error';
        $isAccountError = isset($lastError['response']) && (
            stripos($lastError['response'], 'ERR010') !== false ||
            stripos($lastError['response'], 'no balance') !== false ||
            stripos($lastError['response'], 'insufficient') !== false
        );

        if ($isAccountError) {
            Log::error('KWT SMS API Error - Account/Balance Issue', [
                'original_mobile' => $mobile,
                'last_error' => $lastError,
            ]);
            $errorMessage = 'SMS service temporarily unavailable. Please contact support or try again later.';
        } else {
            Log::error('KWT SMS API Error - All formats failed', [
                'original_mobile' => $mobile,
                'last_error' => $lastError,
            ]);
            $errorMessage = 'Failed to send OTP: ' . $errorResponse;
        }

        return [
            'success' => false,
            'message' => $errorMessage,
            'response' => $errorResponse,
        ];
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
        // Clean mobile number - remove spaces, dashes, plus signs
        $mobile = preg_replace('/[^0-9]/', '', $mobile);

        // Ensure Kuwait country code if it looks like a local number
        if (strlen($mobile) === 8) {
            $mobile = '965' . $mobile;
        }

        if (empty($mobile) || strlen($mobile) < 8) {
            return ['success' => false, 'message' => 'Invalid mobile number format'];
        }

        try {
            $response = Http::timeout(30)->withoutVerifying()->get($this->apiUrl, [
                'username' => $this->username,
                'password' => $this->password,
                'sender'   => $this->sender,
                'mobile'   => $mobile,
                'lang'     => $lang,
                'message'  => $message,
            ]);

            $responseBody = trim($response->body());
            
            if ($response->successful() && stripos($responseBody, 'ERR') === false) {
                return ['success' => true, 'message' => 'SMS sent successfully', 'response' => $responseBody];
            }

            return ['success' => false, 'message' => 'Failed to send SMS: ' . $responseBody, 'response' => $responseBody];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => 'SMS Service Exception: ' . $e->getMessage()];
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









