<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\MassDestroyTalentProfileRequest;
use App\Http\Requests\StoreTalentProfileRequest;
use App\Http\Requests\UpdateTalentProfileRequest;
use App\Models\Language;
use App\Models\TalentProfile;
use App\Models\User;
use App\Models\CastingApplication;
use App\Models\BankDetail;
use App\Models\TalentMedia;
use App\Models\TalentSetting;
use App\Support\EmailTemplateManager;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TalentProfileController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('talent_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfiles = TalentProfile::with(['languages', 'user'])->get();

        return view('admin.talentProfiles.index', compact('talentProfiles'));
    }

    public function suspended()
    {
        abort_if(Gate::denies('talent_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfiles = TalentProfile::with(['user', 'media'])
            ->where('verification_status', 'suspended')
            ->get();

        return view('admin.talentProfiles.suspended', compact('talentProfiles'));
    }

    public function rejected()
    {
        abort_if(Gate::denies('talent_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfiles = TalentProfile::with(['user', 'media'])
            ->where('verification_status', 'rejected')
            ->get();

        return view('admin.talentProfiles.rejected', compact('talentProfiles'));
    }

    public function create()
    {
        abort_if(Gate::denies('talent_profile_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::pluck('title', 'id');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.talentProfiles.create', compact('languages', 'users'));
    }

    public function store(StoreTalentProfileRequest $request)
    {
        $data = $request->all();
        $data['whatsapp_number'] = $this->sanitizePhoneNumber($data['whatsapp_number'] ?? null);

        // Safeguard: Ensure daily_rate travels with a value if missing from form
        if (!isset($data['daily_rate']) || is_null($data['daily_rate'])) {
            $data['daily_rate'] = 0;
        }

        if ($request->boolean('skip_setup')) {
            $data['verification_status'] = 'approved';
            $data['onboarding_step'] = 'completed';
            $data['onboarding_completed_at'] = now();
        }

        $talentProfile = TalentProfile::create($data);
        $talentProfile->languages()->sync($request->input('languages', []));

        return redirect()->route('admin.talents.dashboard');
    }

    public function edit(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $languages = Language::pluck('title', 'id');

        $users = User::pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $talentProfile->load('languages', 'user');

        return view('admin.talentProfiles.edit', compact('languages', 'talentProfile', 'users'));
    }

    public function update(UpdateTalentProfileRequest $request, TalentProfile $talentProfile)
    {
        $data = $request->except([
            'headshot_center_path', 'headshot_left_path', 'headshot_right_path',
            'full_body_front_path', 'full_body_right_path', 'full_body_back_path',
            'id_document_front'
        ]);

        $data['whatsapp_number'] = $this->sanitizePhoneNumber($data['whatsapp_number'] ?? null);

        // Handle file uploads
        $fileFields = [
            'headshot_center_path' => 'headshot-center',
            'headshot_left_path'   => 'headshot-left',
            'headshot_right_path'  => 'headshot-right',
            'full_body_front_path' => 'full-body-front',
            'full_body_right_path' => 'full-body-right',
            'full_body_back_path'  => 'full-body-back',
            'id_document_front'    => 'id/document',
        ];

        foreach ($fileFields as $field => $folder) {
            if ($request->hasFile($field)) {
                $data[$field] = $this->storeTalentFile($talentProfile, $request->file($field), $folder);
            }
        }

        $talentProfile->update($data);
        $talentProfile->labels()->sync($request->input('labels', []));

        return redirect()->route('admin.talent-profiles.show', $talentProfile)->with('message', trans('global.update_success'));
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $disk = config('filesystems.cloud', 's3');
        $path = $file->store("talent/{$profile->id}/{$folder}", $disk);

        // Return full URL for cloud storage
        return \Storage::disk($disk)->url($path);
    }

    public function show(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfile->load('languages', 'user', 'labels');

        $reviews = CastingApplication::with('casting_requirement')
            ->where('talent_profile_id', $talentProfile->id)
            ->latest()
            ->get();

        $languages = \App\Models\Language::all();
        $labels = \App\Models\Label::all();

        // Get MUX playback ID if video exists
        $muxPlaybackId = null;
        if ($talentProfile->mux_video_asset_id) {
            try {
                $muxService = new \App\Services\MuxService();
                $muxPlaybackId = $muxService->getPlaybackId($talentProfile->mux_video_asset_id);
            } catch (\Exception $e) {
                // Log error but don't break the page
                \Log::error('Failed to get MUX playback ID for admin: ' . $e->getMessage());
            }
        }

        return view('admin.talentProfiles.show', compact('talentProfile', 'reviews', 'languages', 'labels', 'muxPlaybackId'));
    }

    public function destroy(TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->removeTalentProfile($talentProfile, false);

        return redirect()
            ->route('admin.talents.dashboard')
            ->with('success', 'Talent Profile deleted successfully.');
    }

    public function massDestroy(MassDestroyTalentProfileRequest $request)
    {
        $talentProfiles = TalentProfile::whereIn('id', (array) $request->input('ids'))->get();

        foreach ($talentProfiles as $talentProfile) {
            $this->removeTalentProfile($talentProfile, true);
        }

        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function approve(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notes = $request->input('notes');

        $talentProfile->update([
            'verification_status' => 'approved',
            'verification_notes'  => $notes,
            'onboarding_step'     => 'completed',
            'onboarding_completed_at' => $talentProfile->onboarding_completed_at ?? now(),
        ]);

        $this->triggerNotification($talentProfile, 'talent_profile_approval', $notes);

        if (request()->is('*home*') || request()->is('admin') || url()->previous() === route('admin.home')) {
            return redirect()->route('admin.home')->with('sweetalert_success', 'Talent approved successfully!');
        }

        return back()->with('message', trans('notifications.status_updated'));
    }

    public function reject(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'notes' => ['nullable', 'string', 'max:500'],
        ]);

        $talentProfile->update([
            'verification_status' => 'rejected',
            'verification_notes'  => $data['notes'] ?? null,
            'onboarding_step'     => 'pending-approval',
        ]);

        $this->triggerNotification($talentProfile, 'talent_profile_rejection', $data['notes'] ?? null);

        if (request()->is('*home*') || request()->is('admin') || url()->previous() === route('admin.home')) {
            return redirect()->route('admin.home')->with('sweetalert_success', 'Talent rejected successfully!');
        }

        return back()->with('message', trans('notifications.status_updated'));
    }

    public function unsuspend(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        // Check if there were previous notes about why it was suspended
        $talentProfile->update([
            'verification_status' => 'approved',
        ]);

        return back()->with('success', 'Talent unsuspended successfully.');
    }

    public function suspend(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $talentProfile->update([
            'verification_status' => 'suspended',
        ]);

        return back()->with('success', 'Talent suspended successfully.');
    }

    public function reactivate(Request $request, TalentProfile $talentProfile)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $notes = $request->input('notes');

        $talentProfile->update([
            'verification_status' => 'pending',
            'verification_notes'  => $notes,
            'onboarding_step'     => 'pending-approval',
        ]);

        $this->triggerNotification($talentProfile, 'talent_profile_reactivated', $notes);

        return back()->with('message', trans('notifications.status_updated'));
    }

    protected function triggerNotification(TalentProfile $talentProfile, string $key, ?string $notes = null): void
    {
        $user = $talentProfile->user;

        if (! $user) {
            return;
        }

        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->send($user, $key, [
            'name'   => $user->name,
            'status' => str_replace('talent_profile_', '', $key),
            'notes'  => $notes ?? '',
        ], [
            'talent_profile_id' => $talentProfile->id,
            'acted_by'          => optional(auth()->user())->name,
        ]);
    }


    protected function removeTalentProfile(TalentProfile $talentProfile, bool $notify = false): void
    {
        DB::transaction(function () use ($talentProfile, $notify) {
            $user = $talentProfile->user;

            if ($notify && $user) {
                $this->notifyTalent($talentProfile, 'deleted', trans('notifications.talent_profile_deleted'));
            }

            // Group detachments/deletions for clarity
            $talentProfile->languages()->detach();
            $talentProfile->labels()->detach();

            CastingApplication::where('talent_profile_id', $talentProfile->id)->forceDelete();
            BankDetail::where('talent_profile_id', $talentProfile->id)->forceDelete();
            TalentMedia::where('talent_profile_id', $talentProfile->id)->forceDelete();
            TalentSetting::where('talent_profile_id', $talentProfile->id)->delete();

            // Handle User record with care
            if ($user) {
                // If it's an admin/superadmin, keep the user record and just remove the talent link
                // This prevents breaking dependencies (like casting_requirements owned by this admin)
                if ($user->isAdmin() || $user->is_super_admin) {
                     $talentProfile->forceDelete();
                } else {
                    // It's a dedicated talent user - safe to remove after clearing dependencies
                    $talentProfile->forceDelete();
                    
                    // Nullify references in casting_requirements before deleting the user
                    \DB::table('casting_requirements')->where('user_id', $user->id)->update(['user_id' => null]);
                    
                    $user->roles()->detach();
                    $user->forceDelete();
                }
            } else {
                $talentProfile->forceDelete();
            }
        });
    }


    public function uploadMedia(TalentProfile $talentProfile, Request $request)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'media_files' => 'array',
            'media_files.*' => 'file|image|max:10240', // 10MB max per file
        ]);

        $uploadedMedia = [];

        if ($request->hasFile('media_files')) {
            foreach ($request->file('media_files') as $file) {
                $filePath = $this->storeTalentFile($talentProfile, $file, 'profile-photos');

                // Create TalentMedia record
                $media = TalentMedia::create([
                    'talent_profile_id' => $talentProfile->id,
                    'file_path' => $filePath,
                    'type' => 'profile',
                ]);

                $uploadedMedia[] = [
                    'id' => $media->id,
                    'file_path' => $filePath,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Media files uploaded successfully',
            'media' => $uploadedMedia,
        ]);
    }

    protected function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
    }
}
