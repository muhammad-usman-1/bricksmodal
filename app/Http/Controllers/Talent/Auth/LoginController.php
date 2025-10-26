<?php

namespace App\Http\Controllers\Talent\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('talent.auth.login');
    }

    public function login(Request $request)
    {
        // Phase 1: user submits phone -> generate OTP and show OTP entry form
        $data = $request->validate([
            'phone_country_code' => ['required', 'string', 'max:8'],
            'phone_number'       => ['required', 'string', 'max:30'],
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

        // TODO: integrate with SMS provider. For now log and flash the OTP for development.
        Log::info('Talent OTP for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);

        $request->session()->put('talent_phone', [
            'phone_country_code' => $user->phone_country_code,
            'phone_number'       => $user->phone_number,
        ]);

        return redirect()->route('talent.otp.form')->with('status', trans('An OTP has been sent to your phone.'));
    }

    public function showOtpForm(Request $request)
    {
        $phone = $request->session()->get('talent_phone');
        if (! $phone) {
            return redirect()->route('talent.login');
        }

        return view('talent.auth.otp', ['phone' => $phone]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'string', 'size:6'],
        ]);

        $phone = $request->session()->get('talent_phone');
        if (! $phone) {
            return redirect()->route('talent.login')->withErrors(['phone' => 'Phone not provided.']);
        }

        $user = User::where('phone_country_code', $phone['phone_country_code'])
            ->where('phone_number', $phone['phone_number'])
            ->where('type', User::TYPE_TALENT)
            ->first();

        if (! $user) {
            return redirect()->route('talent.login')->withErrors(['phone' => 'User not found.']);
        }

        // Check expiry
        if (! $user->otp || Carbon::now()->greaterThan(Carbon::parse($user->otp_expires_at))) {
            return redirect()->route('talent.login')->withErrors(['otp' => 'OTP expired. Please request a new one.']);
        }

        if ($user->otp_consumed) {
            return redirect()->route('talent.login')->withErrors(['otp' => 'OTP already used. Please request a new one.']);
        }

        if (! hash_equals($user->otp, $data['otp'])) {
            // increment attempts
            $user->increment('otp_attempts');
            return back()->withErrors(['otp' => 'Invalid OTP.']);
        }

        // Mark consumed and clear otp for safety
        $user->otp_consumed = true;
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Log the user in
        Auth::guard('talent')->login($user);
        $request->session()->forget('talent_phone');
        $request->session()->regenerate();

        // Redirect depending on onboarding state. OnboardingController will create a profile if missing.
        $profile = $user->talentProfile;
        if (! $profile || ! $profile->hasCompletedOnboarding()) {
            return redirect()->route('talent.onboarding.start');
        }

        if ($profile->verification_status !== 'approved') {
            return redirect()->route('talent.pending');
        }

        return redirect()->intended(route('talent.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('talent')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('talent.login');
    }
}
