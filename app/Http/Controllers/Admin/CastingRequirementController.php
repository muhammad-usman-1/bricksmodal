<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\MediaUploadingTrait;
use App\Http\Requests\MassDestroyCastingRequirementRequest;
use App\Http\Requests\StoreCastingRequirementRequest;
use App\Http\Requests\UpdateCastingRequirementRequest;
use App\Models\CastingRequirement;
use App\Models\CastingRequirementModel;
use App\Models\CastingApplication;
use App\Models\TalentProfile;
use App\Models\User;
use App\Models\Outfit;
use App\Models\Label;
use App\Support\EmailTemplateManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Symfony\Component\HttpFoundation\Response;

class CastingRequirementController extends Controller
{
    use MediaUploadingTrait;

    public function index()
    {
        abort_if(Gate::denies('project_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirements = CastingRequirement::with(['user', 'media'])->orderBy('id', 'desc')->get();

        return view('admin.castingRequirements.index', compact('castingRequirements'));
    }

    public function progress()
    {
        abort_if(Gate::denies('project_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Find strictly today's shoot
        $project = CastingRequirement::where('status', '!=', 'completed')
            ->whereDate('shoot_date_time', now()->toDateString())
            ->orderBy('shoot_date_time', 'asc')
            ->with(['user', 'media', 'castingApplications.talent_profile'])
            ->first();

        $castingRequirements = CastingRequirement::with(['user', 'media'])->get();

        return view('admin.castingRequirements.progress', compact('castingRequirements', 'project'));
    }

    public function create()
    {
        abort_if(Gate::denies('casting_requirement_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $outfits = Outfit::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        $labels = Label::orderBy('name')->get();
        $ageRanges = CastingRequirementModel::AGE_RANGE_OPTIONS;

        return view('admin.castingRequirements.create', compact('users', 'outfits', 'labels', 'ageRanges'));
    }

    public function store(StoreCastingRequirementRequest $request)
    {
        $data = $request->validated();
        $models = $data['models'] ?? [];
        unset($data['shoot_date'], $data['shoot_time'], $data['models']);
        $data['status'] = 'advertised';
        $data['user_id'] = auth('admin')->id(); // Set the authenticated admin as the user

        $totalQuantity = collect($models)->sum(function ($model) {
            return (int) ($model['quantity'] ?? 0);
        });
        $data['count'] = max($totalQuantity, 1);

        $castingRequirement = CastingRequirement::create($data);

        foreach ($models as $index => $modelPayload) {
            $ageOption = CastingRequirementModel::AGE_RANGE_OPTIONS[$modelPayload['age_range_key']] ?? ['min' => null, 'max' => null];
            $rateDecision = $modelPayload['rate_decision'] ?? 'talent_decide';
            $rateValue = $rateDecision === 'admin_decide'
                ? (isset($modelPayload['rate']) ? (float) $modelPayload['rate'] : null)
                : 0;

            $model = $castingRequirement->modelRequirements()->create([
                'title' => $modelPayload['title'] ?? __('Model :number', ['number' => $index + 1]),
                'quantity' => $modelPayload['quantity'],
                'rate' => $rateValue,
                'rate_decision' => $rateDecision,
                'model_hours' => $modelPayload['model_hours'] ?? null,
                'gender' => $modelPayload['gender'] ?? null,
                'hair_color' => $modelPayload['hair_color'] ?? null,
                'age_range_key' => $modelPayload['age_range_key'] ?? null,
                'min_age' => $ageOption['min'] ?? null,
                'max_age' => $ageOption['max'] ?? null,
                'height_range' => $modelPayload['height_range'] ?? null,
                'weight_range' => $modelPayload['weight_range'] ?? null,
                'skin_color' => $modelPayload['skin_color'] ?? null,
                'eye_color' => $modelPayload['eye_color'] ?? null,
                'time_slot' => $modelPayload['time_slot'] ?? null,
                'model_hours' => $modelPayload['model_hours'] ?? null,
                'male_top_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? null : ($modelPayload['male_top_id'] ?? null),
                'male_bottom_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? null : ($modelPayload['male_bottom_id'] ?? null),
                'male_traditional_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? ($modelPayload['male_traditional_id'] ?? null) : null,
                'female_top_id' => $modelPayload['female_top_id'] ?? null,
                'female_bottom_id' => $modelPayload['female_bottom_id'] ?? null,
                'child_top_id' => $modelPayload['child_top_id'] ?? null,
                'child_bottom_id' => $modelPayload['child_bottom_id'] ?? null,
            ]);

            $model->labels()->sync($modelPayload['labels'] ?? []);

            // Handle model-specific reference photos (direct file upload)
            if ($request->hasFile("models.{$index}.reference_photo")) {
                foreach ($request->file("models.{$index}.reference_photo") as $file) {
                    $model->addMedia($file)->toMediaCollection('reference_photo');
                }
            }
        }

        foreach ($request->input('reference', []) as $file) {
            $path = storage_path('tmp/uploads/' . basename($file));
            if (! file_exists($path)) {
                Log::warning('Temporary upload missing for casting requirement reference', ['path' => $path]);
                continue;
            }
            $castingRequirement->addMedia($path)->toMediaCollection('reference');
        }

        if ($media = $request->input('ck-media', false)) {
            Media::whereIn('id', $media)->update(['model_id' => $castingRequirement->id]);
        }

        // Attempt to scrape Instagram image logic
        $this->fetchAndSaveInstagramImage($castingRequirement);

        $this->notifyApprovedTalents($castingRequirement);

        return redirect()->route('admin.casting-requirements.index')->with('success', 'Shoot Requirement created successfully.');
    }

    public function edit(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $outfits = Outfit::active()->orderBy('category')->orderBy('sort_order')->get()->groupBy('category');
        $labels = Label::orderBy('name')->get();
        $ageRanges = CastingRequirementModel::AGE_RANGE_OPTIONS;

        $castingRequirement->load(['user', 'modelRequirements.labels']);

        return view('admin.castingRequirements.edit', compact('castingRequirement', 'users', 'outfits', 'labels', 'ageRanges'));
    }

    public function update(UpdateCastingRequirementRequest $request, CastingRequirement $castingRequirement)
    {
        $data = $request->validated();
        $models = $data['models'] ?? [];
        unset($data['shoot_date'], $data['shoot_time'], $data['models']);
        $data['user_id'] = $castingRequirement->user_id ?? auth('admin')->id(); // Keep existing user_id or set current admin

        $totalQuantity = collect($models)->sum(function ($model) {
            return (int) ($model['quantity'] ?? 0);
        });
        $data['count'] = max($totalQuantity, 1);

        $castingRequirement->update($data);

        $existingModelIds = $castingRequirement->modelRequirements->pluck('id')->toArray();
        $payloadModelIds = collect($models)->pluck('id')->filter()->toArray();

        // Delete models that are not in the payload
        $modelsToDelete = array_diff($existingModelIds, $payloadModelIds);
        if (!empty($modelsToDelete)) {
            CastingRequirementModel::whereIn('id', $modelsToDelete)->delete();
        }

        foreach ($models as $index => $modelPayload) {
            $ageOption = CastingRequirementModel::AGE_RANGE_OPTIONS[$modelPayload['age_range_key']] ?? ['min' => null, 'max' => null];
            $rateDecision = $modelPayload['rate_decision'] ?? 'talent_decide';
            $rateValue = $rateDecision === 'admin_decide'
                ? (isset($modelPayload['rate']) ? (float) $modelPayload['rate'] : null)
                : 0;

            $modelData = [
                'title' => $modelPayload['title'] ?? __('Model :number', ['number' => $index + 1]),
                'quantity' => $modelPayload['quantity'],
                'rate' => $rateValue,
                'rate_decision' => $rateDecision,
                'model_hours' => $modelPayload['model_hours'] ?? null,
                'gender' => $modelPayload['gender'] ?? null,
                'hair_color' => $modelPayload['hair_color'] ?? null,
                'age_range_key' => $modelPayload['age_range_key'] ?? null,
                'min_age' => $ageOption['min'] ?? null,
                'max_age' => $ageOption['max'] ?? null,
                'height_range' => $modelPayload['height_range'] ?? null,
                'weight_range' => $modelPayload['weight_range'] ?? null,
                'skin_color' => $modelPayload['skin_color'] ?? null,
                'eye_color' => $modelPayload['eye_color'] ?? null,
                'time_slot' => $modelPayload['time_slot'] ?? null,
                'model_hours' => $modelPayload['model_hours'] ?? null,
                'male_top_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? null : ($modelPayload['male_top_id'] ?? null),
                'male_bottom_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? null : ($modelPayload['male_bottom_id'] ?? null),
                'male_traditional_id' => ($modelPayload['traditional_mode'] ?? 'false') === 'true' ? ($modelPayload['male_traditional_id'] ?? null) : null,
                'female_top_id' => $modelPayload['female_top_id'] ?? null,
                'female_bottom_id' => $modelPayload['female_bottom_id'] ?? null,
                'child_top_id' => $modelPayload['child_top_id'] ?? null,
                'child_bottom_id' => $modelPayload['child_bottom_id'] ?? null,
            ];

            if (!empty($modelPayload['id'])) {
                $model = CastingRequirementModel::find($modelPayload['id']);
                if ($model) {
                    $model->update($modelData);
                }
            } else {
                $model = $castingRequirement->modelRequirements()->create($modelData);
            }

            if ($model) {
                $model->labels()->sync($modelPayload['labels'] ?? []);

                // Handle model-specific reference photos (direct file upload)
                if ($request->hasFile("models.{$index}.reference_photo")) {
                    foreach ($request->file("models.{$index}.reference_photo") as $file) {
                        $model->addMedia($file)->toMediaCollection('reference_photo');
                    }
                }
            }
        }

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

        // Attempt to scrape Instagram image logic
        $this->fetchAndSaveInstagramImage($castingRequirement);

        return redirect()->route('admin.casting-requirements.index')->with('success', 'Shoot Requirement updated successfully.');
    }

    public function show(CastingRequirement $castingRequirement)
    {
        abort_if(Gate::denies('casting_requirement_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $castingRequirement->load([
            'user',
            'modelRequirements.labels',
            'modelRequirements.media'
        ]);

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
            ->with(['user', 'labels'])
            ->chunk(200, function ($profiles) use ($castingRequirement, $templateKey) {
                foreach ($profiles as $profile) {
                    $user = $profile->user;

                    if (! $user) {
                        continue;
                    }

                    if (! $castingRequirement->matchesTalentProfile($profile)) {
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

    private function fetchAndSaveInstagramImage(CastingRequirement $castingRequirement)
    {
        if (empty($castingRequirement->instagram_url)) {
            return;
        }

        // Try to fetch og:image from the URL
        try {
            $response = Http::timeout(5)
                ->withUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36')
                ->get($castingRequirement->instagram_url);

            if ($response->successful()) {
                $html = $response->body();
                // Look for og:image
                if (preg_match('/meta property="og:image" content="([^"]+)"/', $html, $matches)) {
                    $imageUrl = html_entity_decode($matches[1]);

                    // Clear existing shoot logo
                    $castingRequirement->clearMediaCollection('shoot_logo');

                    // Add new one
                    $castingRequirement->addMediaFromUrl($imageUrl)
                        ->toMediaCollection('shoot_logo');
                }
            }
        } catch (\Exception $e) {
            // Silently fail if scraping fails, fallback to initials will handle it
            Log::error('Failed to scrape Instagram image: ' . $e->getMessage());
        }
    }
}
