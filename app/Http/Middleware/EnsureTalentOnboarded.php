<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTalentOnboarded
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user('talent');

        if (! $user) {
            return $next($request);
        }

        $profile = $user->talentProfile;

        if (! $profile) {
            return redirect()->route('talent.onboarding.start');
        }

        if (! $profile->hasCompletedOnboarding()) {
            return redirect()->route('talent.onboarding.start');
        }

        if ($profile->verification_status !== 'approved') {
            return redirect()->route('talent.pending');
        }

        return $next($request);
    }
}
