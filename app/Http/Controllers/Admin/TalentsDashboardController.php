<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use Gate;
use Symfony\Component\HttpFoundation\Response;

class TalentsDashboardController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talents = TalentProfile::with(['languages', 'user'])
            ->latest()
            ->get();

        $stats = [
            'total'    => TalentProfile::count(),
            'approved' => TalentProfile::where('verification_status', 'approved')->count(),
            'pending'  => TalentProfile::where('verification_status', 'pending')->count(),
            'rejected' => TalentProfile::where('verification_status', 'rejected')->count(),
        ];

        return view('admin.dashboards.talents', compact('talents', 'stats'));
    }
}
