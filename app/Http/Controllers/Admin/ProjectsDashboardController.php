<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CastingRequirement;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectsDashboardController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $stateStatusMap = [
            'open'   => ['advertised', 'processing'],
            'closed' => ['completed'],
        ];

        $state  = $request->input('state', 'all');
        $search = $request->input('search');

        $query = CastingRequirement::with(['user'])
            ->withCount('castingApplications');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', "%{$search}%")
                    ->orWhere('client_name', 'like', "%{$search}%")
                    ->orWhere('location', 'like', "%{$search}%");
            });
        }

        if (array_key_exists($state, $stateStatusMap)) {
            $query->whereIn('status', $stateStatusMap[$state]);
        }

        $projects = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        $stats = [
            'all'    => CastingRequirement::count(),
            'open'   => CastingRequirement::whereIn('status', $stateStatusMap['open'])->count(),
            'closed' => CastingRequirement::whereIn('status', $stateStatusMap['closed'])->count(),
        ];

        $statusDisplay = [
            'advertised' => 'open',
            'processing' => 'open',
            'completed'  => 'close',
        ];

        return view('admin.dashboards.projects', [
            'projects'       => $projects,
            'stats'          => $stats,
            'state'          => $state,
            'search'         => $search,
            'statusDisplay'  => $statusDisplay,
        ]);
    }
}
