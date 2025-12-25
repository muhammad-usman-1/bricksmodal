<?php

namespace App\Http\Controllers\Admin;

use App\Models\TalentProfile;
use App\Models\CastingRequirement;
use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        // Base query to filter out admin users and only show complete talent profiles
        $baseQuery = TalentProfile::with('user')
            ->whereHas('user', function ($query) {
                // Exclude users with admin, superadmin, or creative roles
                $query->whereDoesntHave('roles', function ($roleQuery) {
                    $roleQuery->whereIn('title', ['admin', 'superadmin', 'creative']);
                });
            })
            ->whereNotNull('date_of_birth') // Age is required
            ->whereNotNull('height') // Height is required
            ->whereNotNull('id_front_path') // ID front document is required
            ->whereNotNull('id_back_path'); // ID back document is required

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

        // Get recent talents with the same filters
        $talents = (clone $baseQuery)->latest()->take(7)->get();

        return view('home', compact('stats', 'talents'));
    }
}
