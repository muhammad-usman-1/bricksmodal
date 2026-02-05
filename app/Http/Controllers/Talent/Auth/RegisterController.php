<?php

namespace App\Http\Controllers\Talent\Auth;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use App\Models\User;
use App\Services\KwtSmsService;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{

    public function showRegistrationForm()
    {
        return view('talent.auth.register');
    }

    public function register(Request $request, \App\Services\NotificationService $notificationService)
    {
        // Validate phone number input
        $data = $request->validate([
            'phone_country_code' => ['required', 'string', 'in:965,+965'],
            'phone_number'       => ['required', 'string', 'regex:/^[0-9]{8}$/'],
        ], [
            'phone_country_code.in' => 'Only Kuwait numbers (965) are allowed for OTP.',
            'phone_number.regex'    => 'The phone number must be exactly 8 digits.',
        ]);

        // Find or create talent user by phone
        $user = User::firstOrCreate([
            'phone_country_code' => $data['phone_country_code'],
            'phone_number'       => $data['phone_number'],
            'type'               => User::TYPE_TALENT,
        ], [
            'name'  => null,
            'email' => null,
        ]);

        // Generate a random 6-digit OTP
        $otp = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->otp_consumed = false;
        $user->otp_attempts = 0;
        $user->save();

        // Trigger template-based notification
        $notificationService->send($user, 'talent_signup', [
            'phone' => $user->phone_country_code . $user->phone_number,
        ]);

        // Send OTP via KWT SMS
        $smsService = new KwtSmsService();
        $mobile = KwtSmsService::formatMobileNumber($user->phone_country_code, $user->phone_number);
        $smsResult = $smsService->sendOtp($mobile, $otp);

        if (!$smsResult['success']) {
            Log::error('Failed to send OTP SMS', [
                'user_id' => $user->id,
                'mobile' => $mobile,
                'error' => $smsResult['message'],
            ]);

            // Still log the OTP for development/testing purposes
            Log::info('Talent OTP (SMS failed) for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);

            return back()->withErrors(['phone' => 'Failed to send OTP. Please try again.']);
        }

        Log::info('OTP sent successfully via SMS', [
            'user_id' => $user->id,
            'mobile' => $mobile,
        ]);

        $request->session()->put('talent_phone', [
            'phone_country_code' => $user->phone_country_code,
            'phone_number'       => $user->phone_number,
        ]);

        return redirect()->route('talent.otp.form')->with('status', trans('An OTP has been sent to your phone.'));
    }
}
