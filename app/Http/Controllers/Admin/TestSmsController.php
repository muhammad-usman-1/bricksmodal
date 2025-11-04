<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\KwtSmsService;
use Illuminate\Http\Request;

class TestSmsController extends Controller
{
    public function testSms(Request $request, KwtSmsService $smsService)
    {
        $phoneCountryCode = $request->input('country_code', '965');  // Remove the + for Kuwait numbers
        $phoneNumber = $request->input('phone');
        if (!$phoneNumber) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide a phone number'
            ], 400);
        }
        $testOtp = '123456';

        try {
            $result = $smsService->sendOtp($phoneCountryCode, $phoneNumber, $testOtp);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'SMS sent successfully' : 'Failed to send SMS',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
