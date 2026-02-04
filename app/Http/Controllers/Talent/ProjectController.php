<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user('talent');
        $profile = $talent?->talentProfile;

        $search = $request->get('q');

        $applicationsByProject = collect();
        $appliedIds = [];

        if ($profile) {
            $applicationsByProject = $profile->castingApplications()
                ->select('id', 'casting_requirement_id', 'status')
                ->get()
                ->keyBy('casting_requirement_id');

            $appliedIds = $applicationsByProject->keys()->toArray();
        }

        $query = CastingRequirement::with(['modelRequirements.labels']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', '%' . $search . '%')
                    ->orWhere('client_name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $projects = $query->latest()->paginate(12);
        $projects->appends($request->query());

        return view('talent.projects.index', compact(
            'projects',
            'appliedIds',
            'applicationsByProject',
            'search'
        ));
    }

    public function show(Request $request, CastingRequirement $castingRequirement)
    {
        $profile = $request->user('talent')->talentProfile;
        $castingRequirement->load(['modelRequirements.labels']);
        $existingApplication = null;
        if ($profile) {
            $existingApplication = CastingApplication::where('casting_requirement_id', $castingRequirement->id)
                ->where('talent_profile_id', $profile->id)
                ->first();
        }

        return view('talent.projects.show', compact('castingRequirement', 'existingApplication'));
    }

    public function apply(Request $request, CastingRequirement $castingRequirement, \App\Services\NotificationService $notificationService)
    {
        $profile = $request->user('talent')->talentProfile;
        if (! $profile) {
            return redirect()->route('talent.login');
        }

        if ($profile->verification_status === 'suspended') {
            return back()->with('message', 'Your account is suspended. You cannot apply for projects.');
        }

        if (CastingApplication::where('casting_requirement_id', $castingRequirement->id)
            ->where('talent_profile_id', $profile->id)
            ->exists()) {
            return redirect()->route('talent.projects.show', $castingRequirement)->with('message', trans('global.application_already_submitted'));
        }

        $data = $request->validate([
            'rate'         => ['nullable', 'numeric', 'min:0'],
            'talent_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        CastingApplication::create([
            'casting_requirement_id' => $castingRequirement->id,
            'talent_profile_id'      => $profile->id,
            'rate'                   => $data['rate'] ?? null,
            'talent_notes'           => $data['talent_notes'] ?? null,
            'status'                 => 'applied',
            'payment_processed'      => 'n/a',
        ]);

        $notificationService->send($request->user('talent'), 'shoot_application', [
            'project_name' => $castingRequirement->project_name,
        ]);

        $notificationService->notifyAdmins('admin_shoot_application', [
            'name'         => $profile->display_name,
            'project_name' => $castingRequirement->project_name,
        ], [
            'casting_requirement_id' => $castingRequirement->id,
            'talent_profile_id'      => $profile->id,
        ]);

        return redirect()->route('talent.projects.show', $castingRequirement)->with('message', trans('global.application_submitted'));
    }
}
