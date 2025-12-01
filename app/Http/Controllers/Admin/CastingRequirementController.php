<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCastingRequirementRequest;
use App\Http\Requests\StoreCastingRequirementRequest;
use App\Http\Requests\UpdateCastingRequirementRequest;
use App\Models\CastingRequirement;
use App\Models\CastingApplication;
use App\Models\TalentProfile;
use App\Models\User;
use App\Models\Outfit;
use App\Support\EmailTemplateManager;
use Gate;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CastingRequirementController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('project_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirements = CastingRequirement::with(['user', 'media'])->get();

        return view('admin.castingRequirements.index', compact('castingRequirements'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_requirement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $outfits = Outfit::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');

        return view('admin.castingRequirements.create', compact('users', 'outfits'));
    }

    public function store(StoreCastingRequirementRequest $request)
    {
        $data = $request->validated();
        unset($data['shoot_date'], $data['shoot_time']);
        $data['status'] = 'advertised';
        $data['user_id'] = auth('admin')->id(); // Set the authenticated admin as the user

        $castingRequirement = CastingRequirement::create($data);

        foreach ($request->input('reference', []) as $file) {
            $path = storage_path('tmp/uploads/' . basename($file));
            if (! file_exists($path)) {
                \Log::warning('Temporary upload missing for casting requirement reference', ['path' => $path]);
                continue;
            }
            $castingRequirement->addMedia($path)->toMediaCollection('reference');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $castingRequirement->id]);
        }

        $this->notifyApprovedTalents($castingRequirement);

        return redirect()->route('admin.casting-requirements.index');
    }

    public function edit(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $outfits = Outfit::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');

        $castingRequirement->load('user');

        return view('admin.castingRequirements.edit', compact('castingRequirement', 'users', 'outfits'));
    }

    public function update(UpdateCastingRequirementRequest $request, CastingRequirement $castingRequirement)
    {
        $data = $request->validated();
        unset($data['shoot_date'], $data['shoot_time']);
        $data['user_id'] = $castingRequirement->user_id ?? auth('admin')->id(); // Keep existing user_id or set current admin

        $castingRequirement->update($data);

        if (count($castingRequirement->reference) > 0) {
            foreach ($castingRequirement->reference as $media) {
                if (! in_array($media->file_name, $request->input('reference', []))) {
                    $media->delete();
                }
            }
        }
        $media = $castingRequirement->reference->pluck('file_name')->toArray();
        foreach ($request->input('reference', []) as $file) {
            $path = storage_path('tmp/uploads/' . basename($file));
            if ((count($media) === 0 || ! in_array($file, $media)) && file_exists($path)) {
                $castingRequirement->addMedia($path)->toMediaCollection('reference');
            }
            if (! file_exists($path)) {
                \Log::warning('Temporary upload missing for casting requirement reference (update)', ['path' => $path]);
            }
        }

        return redirect()->route('admin.casting-requirements.index');
    }

    public function show(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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

    protected function notifyApprovedTalents(CastingRequirement $castingRequirement): void
    {
        $templateKey = 'project_created_notification';

        TalentProfile::where('verification_status', 'approved')
            ->with('user')
            ->chunk(200, function ($profiles) use ($castingRequirement, $templateKey) {
                foreach ($profiles as $profile) {
                    $user = $profile->user;

                    if (! $user) {
                        continue;
                    }

                    EmailTemplateManager::sendToUser($user, $templateKey, [
                        'project_name'     => $castingRequirement->project_name,
                        'project_location' => $castingRequirement->location ?? trans('global.not_set'),
                        'project_notes'    => $castingRequirement->notes ?? '',
                        'project_url'      => route('talent.projects.show', $castingRequirement),
                        'project_date'     => $castingRequirement->shoot_date_display ?? ($castingRequirement->shoot_date_time ?? ''),
                    ], [
                        'casting_requirement_id' => $castingRequirement->id,
                        'type'                    => 'project_created',
                        'fallback_subject'        => trans('notifications.project_created_subject', ['project' => $castingRequirement->project_name]),
                        'fallback_body'           => trans('notifications.project_created_fallback', ['project' => $castingRequirement->project_name, 'url' => route('talent.projects.show', $castingRequirement)]),
                    ]);
                }
            });
    }

    public function applicants(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->load(['castingApplications.talent_profile.user']);

        return view('admin.castingRequirements.applicants', [
            'castingRequirement' => $castingRequirement,
            'applications'       => $castingRequirement->castingApplications,
        ]);
    }
}
