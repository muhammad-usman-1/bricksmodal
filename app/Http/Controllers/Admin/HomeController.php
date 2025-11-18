<?php

namespace App\Http\Controllers\Admin;

use App\Models\TalentProfile;
use App\Models\CastingRequirement;
use Illuminate\Http\Request;

class HomeController
{
    public function index(Request $request)
    {
        $total_models = TalentProfile::count();
        $pending = TalentProfile::where('verification_status', 'pending')->count();
        $recent = TalentProfile::where('created_at', '>=', now()->subDays(30))->count();
        $activeCampaigns = CastingRequirement::count();
        $pendingPayments = CastingRequirement::count();
        $total = $pendingPayments + $activeCampaigns;

        $stats = [
            'total_models' => $total_models,
            'pending_verification' => $pending,
            'recent_signups' => $recent,
            'active_campaigns' => $activeCampaigns,
            'pending_payments' => $pendingPayments,
            'total' => $total,

        ];

        $talents = TalentProfile::with('user')->latest()->take(7)->get();

        return view('home', compact('stats', 'talents'));
    }
}
