<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use App\Models\CastingRequirement;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $talent = auth('talent')->user();
        $profile = $talent?->talentProfile;

        $stats = [
            'total_applications' => 0,
            'active_applications' => 0,
            'awarded_roles' => 0,
            'pending_payments' => 0,
        ];

        $recentApplications = collect();
        $recentPayments = collect();
        $openProjects = CastingRequirement::query()
            ->whereIn('status', ['advertised', 'processing'])
            ->latest()
            ->limit(4)
            ->get();

        if ($profile) {
            $baseApplicationsQuery = CastingApplication::query()
                ->with('casting_requirement')
                ->where('talent_profile_id', $profile->id);

            $stats = [
                'total_applications' => (clone $baseApplicationsQuery)->count(),
                'active_applications' => (clone $baseApplicationsQuery)
                    ->whereIn('status', ['applied', 'selected'])
                    ->count(),
                'awarded_roles' => (clone $baseApplicationsQuery)
                    ->where('status', 'selected')
                    ->count(),
                'pending_payments' => (clone $baseApplicationsQuery)
                    ->whereIn('payment_status', ['pending', 'requested', 'approved'])
                    ->count(),
            ];

            $recentApplications = (clone $baseApplicationsQuery)
                ->latest()
                ->limit(4)
                ->get();

            $recentPayments = (clone $baseApplicationsQuery)
                ->where('status', 'selected')
                ->orderByDesc('payment_requested_at')
                ->orderByDesc('updated_at')
                ->limit(5)
                ->get();

            $openProjects = CastingRequirement::query()
                ->whereIn('status', ['advertised', 'processing'])
                ->whereDoesntHave('castingApplications', function ($query) use ($profile) {
                    $query->where('talent_profile_id', $profile->id);
                })
                ->latest()
                ->limit(4)
                ->get();
        }

        return view('talent.dashboard.index', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'recentPayments' => $recentPayments,
            'openProjects' => $openProjects,
            'profile' => $profile,
        ]);
    }
}
