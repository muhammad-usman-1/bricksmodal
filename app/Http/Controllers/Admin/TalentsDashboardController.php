<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use App\Models\AuditLog;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class TalentsDashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('talent_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Get all talents for filtering (approved, pending, rejected, suspended)
        // The JavaScript will filter them, but we need all of them for the filter buttons to work
        $talents = TalentProfile::with(['languages', 'user', 'media'])
            ->where('onboarding_steps_completed', '>=', 3)
            ->latest()
            ->get();

        // Get last login dates from audit logs for all talent users
        $userIds = $talents->pluck('user_id')->filter()->toArray();
        $lastLogins = AuditLog::where('event_type', 'login_success')
            ->where('user_type', 'talent')
            ->whereIn('user_id', $userIds)
            ->selectRaw('user_id, MAX(created_at) as last_login')
            ->groupBy('user_id')
            ->pluck('last_login', 'user_id')
            ->toArray();

        // Attach last login to each talent
        $talents->each(function ($talent) use ($lastLogins) {
            $talent->last_login = $lastLogins[$talent->user_id] ?? null;
        });

        $stats = [
            'total'    => TalentProfile::count(),
            'approved' => TalentProfile::where('verification_status', 'approved')->count(),
            'pending'  => TalentProfile::where('verification_status', 'pending')->count(),
            'rejected' => TalentProfile::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.dashboards.talents', compact('talents', 'stats'));
    }
}
