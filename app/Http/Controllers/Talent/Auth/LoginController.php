<?php

namespace App\Http\Controllers\Talent\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Services\KwtSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            'phone_country_code' => ['required', 'string', 'in:965,+965'],
            'phone_number'       => ['required', 'string', 'regex:/^[0-9]{8}$/'],
        ], [
            'phone_country_code.in' => 'Only Kuwait numbers (965) are allowed for OTP.',
            'phone_number.regex'    => 'The phone number must be exactly 8 digits.',
        ]);

        // Find or create talent user by phone
        // Since phone_number has a unique constraint, we check by phone_number first
        $user = User::where('phone_number', $data['phone_number'])->first();

        if ($user) {
            // User exists - update country code and type, but preserve name/email
            $user->phone_country_code = $data['phone_country_code'];
            $user->type = User::TYPE_TALENT;
            $user->save();
        } else {
            // User doesn't exist - create new talent user
            $user = User::create([
                'phone_country_code' => $data['phone_country_code'],
                'phone_number'       => $data['phone_number'],
                'type'               => User::TYPE_TALENT,
                'name'               => null,
                'email'              => null,
            ]);
        }

        // Generate a random 4-digit OTP
        $otp = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(5);
        $user->otp_consumed = false;
        $user->otp_attempts = 0;
        $user->save();

        // Check if phone number starts with 123
        $phoneStartsWith123 = substr($user->phone_number, 0, 3) === '123';

        if ($phoneStartsWith123) {
            // For phone numbers starting with 123, skip SMS service and just log OTP
            Log::info('Talent OTP (Test number - no SMS sent) for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);
        } else {
            // Send OTP via KWT SMS for regular phone numbers
            $smsService = new KwtSmsService();
            $mobile = KwtSmsService::formatMobileNumber($user->phone_country_code, $user->phone_number);
            $smsResult = $smsService->sendOtp($mobile, $otp);

            if (!$smsResult['success']) {
                Log::error('Failed to send OTP SMS', [
                    'user_id' => $user->id,
                    'mobile' => $mobile,
                    'error' => $smsResult['message'],
                ]);

                // Log the OTP for development/testing purposes
                Log::info('Talent OTP (SMS failed) for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);

                // Still proceed to OTP screen for development
                // In production, you might want to return error instead
            } else {
                Log::info('OTP sent successfully via SMS', [
                    'user_id' => $user->id,
                    'mobile' => $mobile,
                ]);
            }
        }

        $request->session()->put('talent_phone', [
            'phone_country_code' => $user->phone_country_code,
            'phone_number'       => $user->phone_number,
        ]);

        // Store OTP in session for testing purposes (in case database lookup fails)
        $request->session()->put('talent_otp', $otp);

        return redirect()->route('talent.otp.form')->with('status', trans('An OTP has been sent to your phone.'));
    }

    public function showOtpForm(Request $request)
    {
        $phone = $request->session()->get('talent_phone');
        if (! $phone) {
            return redirect()->route('talent.login');
        }

        // Get the OTP from database for testing purposes
        $user = User::where('phone_country_code', $phone['phone_country_code'])
            ->where('phone_number', $phone['phone_number'])
            ->where('type', User::TYPE_TALENT)
            ->first();

        $otp = null;

        // First try to get from database
        if ($user && $user->otp) {
            // For testing: show OTP if it exists (even if expired or consumed)
            // This makes it easier to test without worrying about expiration
            $otp = $user->otp;
        }

        // Fallback: get from session (in case database lookup fails)
        if (!$otp) {
            $otp = $request->session()->get('talent_otp');
        }

        return view('talent.auth.otp', [
            'phone' => $phone,
            'otp' => $otp, // Display OTP for testing
        ]);
    }

    public function verifyOtp(Request $request)
    {
        $data = $request->validate([
            'otp' => ['required', 'string', 'size:4'],
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
            
            // Log failed login attempt
            $this->logLoginFailure($user->phone_number, 'Invalid OTP', $request, 'talent', $user->id);
            
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
        
        // Determine redirect destination for logging
        $redirectTo = 'talent_dashboard';
        $onboardingStep = null;
        
        // Check if profile is rejected - redirect to rejection page
        if ($profile && $profile->verification_status === 'rejected') {
            $redirectTo = 'rejected_page';
        } elseif (! $profile || ! $profile->hasCompletedOnboarding()) {
            $step = $profile?->onboarding_step;
            $redirectTo = 'onboarding';
            $onboardingStep = $step ?? 'intro';
            
            if (! $profile || ! $step || $step === 'profile') {
                $onboardingStep = 'intro';
            }
        } elseif ($profile->verification_status !== 'approved') {
            $redirectTo = 'pending_status';
        }
        
        // Log successful login with redirect information
        $this->logLoginSuccess($user, $request, $redirectTo, $onboardingStep);

        // Perform redirects
        if ($profile && $profile->verification_status === 'rejected') {
            return redirect()->route('talent.rejected');
        }

        // IMPORTANT: If onboarding is completed, skip Terms & Conditions entirely
        // and go directly to dashboard or pending status
        if ($profile && $profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                return redirect()->route('talent.pending_status');
            }
            return redirect()->intended(route('talent.dashboard'));
        }

        // Only for users who haven't completed onboarding:
        // New flow: OTP -> Terms (first time) -> Intro -> Onboarding step-1
        if (! $profile || ! $profile->terms_accepted_at) {
            return redirect()->route('talent.onboarding.terms');
        }

        return redirect()->route('talent.onboarding.intro');
    }

    public function logout(Request $request)
    {
        $user = Auth::guard('talent')->user();
        
        Auth::guard('talent')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Log logout event with onboarding details
        if ($user) {
            $this->logLogout($user, $request);
        }

        return redirect()->route('talent.login');
    }
    
    /**
     * Log successful login event
     */
    private function logLoginSuccess($user, Request $request, $redirectTo, $onboardingStep = null)
    {
        $profile = $user->talentProfile;
        $stepsCompleted = $profile?->onboarding_steps_completed ?? 0;
        $isCompleted = $profile?->hasCompletedOnboarding() ?? false;
        
        AuditLog::create([
            'event_type' => 'login_success',
            'user_type' => 'talent',
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => true,
            'login_redirect_to' => $redirectTo,
            'onboarding_step' => $onboardingStep,
            'onboarding_steps_completed' => $stepsCompleted,
            'onboarding_completed' => $isCompleted,
            'onboarding_action' => "Logged in successfully. Redirected to: {$redirectTo}" . ($onboardingStep ? " (Current step: {$onboardingStep})" : ''),
            'metadata' => [
                'redirect_to' => $redirectTo,
                'onboarding_step' => $onboardingStep,
                'steps_completed' => $stepsCompleted,
            ],
            'created_at' => now(),
        ]);
    }
    
    /**
     * Log failed login event
     */
    private function logLoginFailure($phoneNumber, $reason, Request $request, $userType = 'talent', $userId = null)
    {
        AuditLog::create([
            'event_type' => 'login_failed',
            'user_type' => $userType,
            'user_id' => $userId,
            'user_phone' => $phoneNumber,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => false,
            'login_failure_reason' => $reason,
            'created_at' => now(),
        ]);
    }
    
    /**
     * Log logout event
     */
    private function logLogout($user, Request $request)
    {
        $profile = $user->talentProfile;
        $currentStep = $profile?->onboarding_step;
        $stepsCompleted = $profile?->onboarding_steps_completed ?? 0;
        $isCompleted = $profile?->hasCompletedOnboarding() ?? false;
        
        // Determine where they logged out from
        $logoutFrom = 'unknown';
        if ($request->is('talent/onboarding/*')) {
            $logoutFrom = 'onboarding';
        } elseif ($request->is('talent/dashboard*')) {
            $logoutFrom = 'dashboard';
        } elseif ($request->is('talent/profile*')) {
            $logoutFrom = 'profile';
        } elseif ($request->is('talent/pending*')) {
            $logoutFrom = 'pending_status';
        }
        
        AuditLog::create([
            'event_type' => 'logout',
            'user_type' => 'talent',
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => true,
            'onboarding_step' => $currentStep,
            'onboarding_steps_completed' => $stepsCompleted,
            'onboarding_completed' => $isCompleted,
            'onboarding_action' => "Logged out from: {$logoutFrom}" . ($currentStep ? " (Was on step: {$currentStep})" : ''),
            'metadata' => [
                'logout_from' => $logoutFrom,
                'onboarding_step' => $currentStep,
            ],
            'created_at' => now(),
        ]);
    }
}
