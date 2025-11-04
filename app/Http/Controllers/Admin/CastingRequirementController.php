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
use App\Support\EmailTemplateManager;
use App\Support\OutfitOptions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CastingRequirementController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('casting_requirement_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirements = CastingRequirement::with([
            'user',
            'media',
            'castingApplications.talent_profile.user'
        ])->get();

        return view('admin.castingRequirements.index', compact('castingRequirements'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_requirement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.castingRequirements.create');
    }

    public function store(StoreCastingRequirementRequest $request)
    {
        try {
            $data = $request->validated();
            $data['user_id'] = $request->user()->id;

            // Handle outfit data
            if (isset($data['outfit'])) {
                $normalizedOutfit = OutfitOptions::validateAndNormalize($data['outfit']);

                if ($normalizedOutfit === null) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['outfit' => 'Please select at least one valid outfit option.']);
                }

                $data['outfit'] = $normalizedOutfit;
            }

            // Handle shoot date time format
            if (!empty($data['shoot_date_time'])) {
                try {
                    $data['shoot_date_time'] = Carbon::createFromFormat(
                        config('panel.date_format') . ' ' . config('panel.time_format'),
                        $data['shoot_date_time']
                    )->format('Y-m-d H:i:s');
                } catch (\Exception $e) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors(['shoot_date_time' => 'Invalid date time format.']);
                }
            }

            // Create the casting requirement
            $castingRequirement = CastingRequirement::create($data);

            // Handle reference files
            foreach ($request->input('reference', []) as $file) {
                $path = storage_path('tmp/uploads/' . basename($file));
                if (!file_exists($path)) {
                    Log::warning('Temporary upload missing for casting requirement reference', ['path' => $path]);
                    continue;
                }
                $castingRequirement->addMedia($path)->toMediaCollection('reference');
            }

            // Handle CKEditor uploaded media
            if ($media = $request->input('ck-media', false)) {
                Media::whereIn('id', $media)->update(['model_id' => $castingRequirement->id]);
            }

            // Notify approved talents
            $this->notifyApprovedTalents($castingRequirement);

            return redirect()
                ->route('admin.casting-requirements.index')
                ->with('message', 'Casting requirement created successfully.');

        } catch (\Exception $e) {
            Log::error('Error creating casting requirement: ' . $e->getMessage());
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'An error occurred while creating the casting requirement.');
        }
    }

    public function edit(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->load('user');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.castingRequirements.edit', compact('castingRequirement', 'users'));
    }

    public function update(UpdateCastingRequirementRequest $request, CastingRequirement $castingRequirement)
    {
        $data = $request->all();

        if (isset($data['outfit'])) {
            $decoded = json_decode($data['outfit'], true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $data['outfit'] = OutfitOptions::normalize($decoded);
            } elseif (is_array($data['outfit'])) {
                $data['outfit'] = OutfitOptions::normalize($data['outfit']);
            }
        }

        unset($data['user_id']);

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
                Log::warning('Temporary upload missing for casting requirement reference (update)', ['path' => $path]);
            }
        }

        return redirect()
            ->route('admin.casting-requirements.index')
            ->with('message', 'Casting requirement updated successfully.');
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

        return redirect()
            ->route('admin.casting-requirements.index')
            ->with('message', 'Casting requirement deleted successfully.');
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
                        'project_date'     => $castingRequirement->shoot_date_time ?? '',
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
        abort_if(Gate::denies('casting_requirement_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->load(['castingApplications.talent_profile.user']);

        return view('admin.castingRequirements.applicants', [
            'castingRequirement' => $castingRequirement,
            'applications'       => $castingRequirement->castingApplications,
        ]);
    }
}
