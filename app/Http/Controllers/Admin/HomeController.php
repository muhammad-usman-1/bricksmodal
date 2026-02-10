<?php

namespace App\Http\Controllers\Admin;

use App\Models\TalentProfile;
use App\Models\CastingRequirement;
use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        // Base query to filter out admin users and only show talents who completed onboarding
        $baseQuery = TalentProfile::with(['user', 'media'])
            ->whereHas('user', function ($query) {
                // Exclude users with admin, superadmin, or creative roles
                $query->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->whereIn('title', ['admin', 'superadmin', 'creative']);
                });
            })
            ->where('onboarding_steps_completed', '>=', 5); // Only show talents who completed onboarding (step 5)

        // Calculate stats with the same filters
        $total = (clone $baseQuery)->count();
        $pending = (clone $baseQuery)->where('verification_status', 'pending')->count();
        $recent = (clone $baseQuery)->where('created_at', '>=', now()->subDays(30))->count();
        $activeCampaigns = CastingRequirement::count();

        $stats = [
            'total' => $total,
            'pending_verification' => $pending,
            'recent_signups' => $recent,
            'active_campaigns' => $activeCampaigns,
        ];

        // Get all talents (will be hidden client-side, showing 7 by default)
        $talents = (clone $baseQuery)->latest()->get();

        return view('home', compact('stats', 'talents'));
    }
}
