<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogOnboardingActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // Only log for talent users
        $user = Auth::guard('talent')->user();
        
        if ($user && $request->is('talent/onboarding/*')) {
            // Check if this is a store action (step completion)
            if ($request->isMethod('POST') && $request->route('step')) {
                $profile = $user->talentProfile;
                
                if ($profile) {
                    $this->logOnboardingStep($user, $profile, $request);
                }
            }
        }
        
        return $response;
    }
    
    /**
     * Log onboarding step activity
     */
    private function logOnboardingStep($user, $profile, Request $request)
    {
        $step = $request->route('step');
        $stepsCompleted = $profile->onboarding_steps_completed ?? 0;
        $isCompleted = $profile->hasCompletedOnboarding();
        
        // Determine action description
        $action = "Completed onboarding step: {$step}";
        if ($isCompleted && $stepsCompleted >= 5) {
            $action = "Completed full onboarding process";
        }
        
        AuditLog::create([
            'event_type' => $isCompleted ? 'onboarding_completed' : 'onboarding_step',
            'user_type' => 'talent',
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'onboarding_step' => $step,
            'onboarding_steps_completed' => $stepsCompleted,
            'onboarding_completed' => $isCompleted,
            'onboarding_action' => $action,
            'created_at' => now(),
        ]);
    }
}
