<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCastingRequirementRequest;
use App\Http\Requests\StoreCastingRequirementRequest;
use App\Http\Requests\UpdateCastingRequirementRequest;
use App\Models\CastingRequirement;
use App\Models\User;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CastingRequirementController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('casting_requirement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirements = CastingRequirement::with(['user', 'media'])->get();

        return view('admin.castingRequirements.index', compact('castingRequirements'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_requirement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.castingRequirements.create', compact('users'));
    }

    public function store(StoreCastingRequirementRequest $request)
    {
        $castingRequirement = CastingRequirement::create($request->all());

        foreach ($request->input('reference', []) as $file) {
            $castingRequirement->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('reference');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $castingRequirement->id]);
        }

        return redirect()->route('admin.casting-requirements.index');
    }

    public function edit(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $castingRequirement->load('user');

        return view('admin.castingRequirements.edit', compact('castingRequirement', 'users'));
    }

    public function update(UpdateCastingRequirementRequest $request, CastingRequirement $castingRequirement)
    {
        $castingRequirement->update($request->all());

        if (count($castingRequirement->reference) > 0) {
            foreach ($castingRequirement->reference as $media) {
                if (! in_array($media->file_name, $request->input('reference', []))) {
                    $media->delete();
                }
            }
        }
        $media = $castingRequirement->reference->pluck('file_name')->toArray();
        foreach ($request->input('reference', []) as $file) {
            if (count($media) === 0 || ! in_array($file, $media)) {
                $castingRequirement->addMedia(storage_path('tmp/uploads/' . basename($file)))->toMediaCollection('reference');
            }
        }

        return redirect()->route('admin.casting-requirements.index');
    }

    public function show(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->load('user');

        return view('admin.castingRequirements.show', compact('castingRequirement'));
    }

    public function destroy(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->delete();

        return back();
    }

    public function massDestroy(MassDestroyCastingRequirementRequest $request)
    {
        $castingRequirements = CastingRequirement::find(request('ids'));

        foreach ($castingRequirements as $castingRequirement) {
            $castingRequirement->delete();
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function storeCKEditorImages(Request $request)
    {
        abort_if(Gate::denies('casting_requirement_create') && Gate::denies('casting_requirement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $model         = new CastingRequirement();
        $model->id     = $request->input('crud_id', 0);
        $model->exists = true;
        $media         = $model->addMediaFromRequest('upload')->toMediaCollection('ck-media');

        return response()->json(['id' => $media->id, 'url' => $media->getUrl()], Response::HTTP_CREATED);
    }
}
