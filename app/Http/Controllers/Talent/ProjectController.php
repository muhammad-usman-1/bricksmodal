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
        if ($profile) {
            $profile->loadMissing('labels');
        }

        $statusFilter = $request->get('status', 'all');
        $allowedFilters = ['all', 'applied', 'shortlisted', 'selected', 'rejected'];
        if (! in_array($statusFilter, $allowedFilters, true)) {
            $statusFilter = 'all';
        }

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

        if ($statusFilter === 'all') {
            $query->whereIn('status', ['advertised', 'processing']);
        } else {
            if ($profile) {
                $query->whereHas('castingApplications', function ($q) use ($profile, $statusFilter) {
                    $q->where('talent_profile_id', $profile->id)
                        ->where('status', $statusFilter);
                });
            } else {
                $query->whereRaw('0 = 1');
            }
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('project_name', 'like', '%' . $search . '%')
                    ->orWhere('client_name', 'like', '%' . $search . '%')
                    ->orWhere('location', 'like', '%' . $search . '%');
            });
        }

        $talentLabelIds = $profile ? $profile->labels->pluck('id')->all() : [];
        $talentAge = $profile && $profile->date_of_birth ? $profile->date_of_birth->age : null;

        if ($profile) {
            $query->where(function ($builder) use ($profile, $talentLabelIds, $talentAge) {
                $builder->whereDoesntHave('modelRequirements')
                    ->orWhereHas('modelRequirements', function ($requirements) use ($profile, $talentLabelIds, $talentAge) {
                        $requirements->matchesTalent($profile, $talentLabelIds, $talentAge);
                    });
            });
        } else {
            $query->whereDoesntHave('modelRequirements');
        }

        $projects = $query->latest()->paginate(12);
        $projects->appends($request->query());

        return view('talent.projects.index', compact(
            'projects',
            'appliedIds',
            'statusFilter',
            'applicationsByProject',
            'search'
        ));
    }

    public function show(Request $request, CastingRequirement $castingRequirement)
    {
        $profile = $request->user('talent')->talentProfile;
        if ($profile) {
            $profile->loadMissing('labels');
        }

        abort_if(! in_array($castingRequirement->status, ['advertised', 'processing']), 404);
        abort_if(! $castingRequirement->matchesTalentProfile($profile), 404);

        $castingRequirement->load(['modelRequirements.labels']);
        $existingApplication = CastingApplication::where('casting_requirement_id', $castingRequirement->id)
            ->where('talent_profile_id', $profile->id)
            ->first();

        return view('talent.projects.show', compact('castingRequirement', 'existingApplication'));
    }

    public function apply(Request $request, CastingRequirement $castingRequirement)
    {
        abort_if(! in_array($castingRequirement->status, ['advertised', 'processing']), 404);

        $profile = $request->user('talent')->talentProfile;
        if ($profile) {
            $profile->loadMissing('labels');
        }

        abort_if(! $castingRequirement->matchesTalentProfile($profile), 404);

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

        return redirect()->route('talent.projects.show', $castingRequirement)->with('message', trans('global.application_submitted'));
    }
}
