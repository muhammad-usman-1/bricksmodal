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
        $query = CastingRequirement::query()->whereIn('status', ['advertised', 'processing'])->latest();

        $projects = $query->paginate(12);

        $profile = $request->user('talent')->talentProfile;
        $appliedIds = $profile?->castingApplications()->pluck('casting_requirement_id')->toArray() ?? [];

        return view('talent.projects.index', compact('projects', 'appliedIds'));
    }

    public function show(Request $request, CastingRequirement $castingRequirement)
    {
        abort_if(! in_array($castingRequirement->status, ['advertised', 'processing']), 404);

        $profile = $request->user('talent')->talentProfile;
        $existingApplication = CastingApplication::where('casting_requirement_id', $castingRequirement->id)
            ->where('talent_profile_id', $profile->id)
            ->first();

        return view('talent.projects.show', compact('castingRequirement', 'existingApplication'));
    }

    public function apply(Request $request, CastingRequirement $castingRequirement)
    {
        abort_if(! in_array($castingRequirement->status, ['advertised', 'processing']), 404);

        $profile = $request->user('talent')->talentProfile;

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
