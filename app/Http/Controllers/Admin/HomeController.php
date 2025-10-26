<?php

namespace App\Http\Controllers\Admin;

use App\Models\TalentProfile;
use App\Models\CastingRequirement;
use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        $total = TalentProfile::count();
        $pending = TalentProfile::where('verification_status', 'pending')->count();
        $recent = TalentProfile::where('created_at', '>=', now()->subDays(30))->count();
        $activeCampaigns = CastingRequirement::count();

        $stats = [
            'total' => $total,
            'pending_verification' => $pending,
            'recent_signups' => $recent,
            'active_campaigns' => $activeCampaigns,
        ];

        $talents = TalentProfile::with('user')->latest()->take(7)->get();

        return view('home', compact('stats', 'talents'));
    }
}
