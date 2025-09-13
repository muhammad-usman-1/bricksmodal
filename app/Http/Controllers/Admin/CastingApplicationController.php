<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyCastingApplicationRequest;
use App\Http\Requests\StoreCastingApplicationRequest;
use App\Http\Requests\UpdateCastingApplicationRequest;
use App\Models\CastingApplication;
use App\Models\CastingRequirement;
use App\Models\TalentProfile;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CastingApplicationController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('casting_application_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplications = CastingApplication::with(['casting_requirement', 'talent_profile'])->get();

        return view('admin.castingApplications.index', compact('castingApplications'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_application_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $casting_requirements = CastingRequirement::pluck('project_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.castingApplications.create', compact('casting_requirements', 'talent_profiles'));
    }

    public function store(StoreCastingApplicationRequest $request)
    {
        $castingApplication = CastingApplication::create($request->all());

        return redirect()->route('admin.casting-applications.index');
    }

    public function edit(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $casting_requirements = CastingRequirement::pluck('project_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talent_profiles = TalentProfile::pluck('legal_name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castingApplication->load('casting_requirement', 'talent_profile');

        return view('admin.castingApplications.edit', compact('castingApplication', 'casting_requirements', 'talent_profiles'));
    }

    public function update(UpdateCastingApplicationRequest $request, CastingApplication $castingApplication)
    {
        $castingApplication->update($request->all());

        return redirect()->route('admin.casting-applications.index');
    }

    public function show(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplication->load('casting_requirement', 'talent_profile');

        return view('admin.castingApplications.show', compact('castingApplication'));
    }

    public function destroy(CastingApplication $castingApplication)
    {
        abort_if(Gate::denies('casting_application_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingApplication->delete();

        return back();
    }

    public function massDestroy(MassDestroyCastingApplicationRequest $request)
    {
        $castingApplications = CastingApplication::find(request('ids'));

        foreach ($castingApplications as $castingApplication) {
            $castingApplication->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
