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
use App\Traits\LogsAuditEvents;
use Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TalentProfileController extends Controller
{
    use LogsAuditEvents;
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
            'id_document_front', 'deleted_media_ids', 'uploaded_keys', 'uploaded_fields'
        ]);

        $data['whatsapp_number'] = $this->sanitizePhoneNumber($data['whatsapp_number'] ?? null);

        // Handle file removals for specific columns
        $fileFields = [
            'headshot_center_path',
            'headshot_left_path',
            'headshot_right_path',
            'full_body_front_path',
            'full_body_right_path',
            'full_body_back_path',
            'id_document_front',
            'id_front_path'
        ];

        foreach ($fileFields as $field) {
            if ($request->input("remove_{$field}") == '1') {
                $this->deletePhysicalFile($talentProfile->{$field});
                $data[$field] = null;
            }
        }

        // Handle file uploads
        $uploadFolders = [
            'headshot_center_path' => 'headshot-center',
            'headshot_left_path'   => 'headshot-left',
            'headshot_right_path'  => 'headshot-right',
            'full_body_front_path' => 'full-body-front',
            'full_body_right_path' => 'full-body-right',
            'full_body_back_path'  => 'full-body-back',
            'id_document_front'    => 'id/document',
        ];

        foreach ($uploadFolders as $field => $folder) {
            if ($request->hasFile($field)) {
                $this->deletePhysicalFile($talentProfile->{$field});
                $data[$field] = $this->storeTalentFile($talentProfile, $request->file($field), $folder);
            }
        }

        // Handle TalentMedia deletions
        if ($request->has('deleted_media_ids')) {
            $deletedIds = $request->input('deleted_media_ids');
            $mediaItems = \App\Models\TalentMedia::whereIn('id', $deletedIds)
                ->where('talent_profile_id', $talentProfile->id)
                ->get();

            foreach ($mediaItems as $media) {
                $this->deletePhysicalFile($media->file_path);
                $media->delete();
            }
        }

        // Handle uploaded S3 keys (from AJAX uploads)
        if ($request->has('uploaded_keys')) {
            $uploadedKeys = $request->input('uploaded_keys', []);
            $uploadedFields = $request->input('uploaded_fields', []);
            
            $disk = config('filesystems.cloud', 's3');
            $storage = \Storage::disk($disk);
            
            foreach ($uploadedKeys as $index => $key) {
                // Get the field name if provided
                $field = $uploadedFields[$index] ?? null;
                
                // Generate the full URL for the S3 key
                $url = $storage->url($key);
                
                if ($field && in_array($field, [
                    'headshot_center_path', 'headshot_left_path', 'headshot_right_path',
                    'full_body_front_path', 'full_body_right_path', 'full_body_back_path',
                    'id_front_path', 'id_document_front'
                ])) {
                    // Update the specific field
                    $data[$field] = $url;
                } else {
                    // Create a TalentMedia record for additional photos
                    TalentMedia::create([
                        'talent_profile_id' => $talentProfile->id,
                        'file_path' => $url,
                        'type' => 'profile',
                    ]);
                }
            }
        }

        // Get changed fields for logging
        $changedFields = [];
        $originalData = $talentProfile->getOriginal();
        foreach ($data as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] != $value) {
                $changedFields[] = $key;
            }
        }

        $talentProfile->update($data);
        $talentProfile->labels()->sync($request->input('labels', []));
        
        // Reload to get updated data
        $talentProfile->load('user');

        // Log profile update by admin
        if (!empty($changedFields)) {
            $adminUser = auth()->user();
            $this->logAuditEvent(
                'profile_updated',
                $adminUser->isSuperAdmin() ? 'superadmin' : ($adminUser->isCreative() ? 'creative' : 'admin'),
                $adminUser->id,
                $adminUser->email,
                null,
                $request,
                "Updated talent profile: {$talentProfile->display_name} (ID: {$talentProfile->id})",
                [
                    'talent_profile_id' => $talentProfile->id,
                    'talent_name' => $talentProfile->display_name ?? ($talentProfile->first_name . ' ' . $talentProfile->last_name),
                    'talent_email' => $talentProfile->user->email ?? null,
                    'talent_phone' => $talentProfile->user->phone_number ?? null,
                    'admin_name' => $adminUser->name,
                    'changed_fields' => $changedFields,
                ]
            );
        }

        return redirect()->route('admin.talent-profiles.show', $talentProfile)->with('message', trans('global.update_success'));
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $disk = config('filesystems.cloud', 's3');
        
        // Resize and crop images to 9:16 aspect ratio for profile images (NOT for ID documents)
        $isProfileImage = in_array($folder, ['headshots', 'full-body', 'profile-photos']);
        $isIdDocument = in_array($folder, ['id-documents', 'id']);
        
        if ($isProfileImage && !$isIdDocument && strpos($file->getMimeType(), 'image/') !== false) {
            try {
                $path = $file->getRealPath();
                $mime = $file->getMimeType();
                $sourceImage = null;

                // Load source image
                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $sourceImage = imagecreatefromjpeg($path);
                } elseif ($mime === 'image/png') {
                    $sourceImage = imagecreatefrompng($path);
                } elseif ($mime === 'image/webp') {
                    $sourceImage = imagecreatefromwebp($path);
                } elseif ($mime === 'image/gif') {
                    $sourceImage = imagecreatefromgif($path);
                }

                if ($sourceImage) {
                    $sourceWidth = imagesx($sourceImage);
                    $sourceHeight = imagesy($sourceImage);
                    
                    // Target aspect ratio: 9:16
                    $targetAspect = 9 / 16;
                    $sourceAspect = $sourceWidth / $sourceHeight;
                    
                    // Calculate dimensions to crop and resize to 9:16
                    $targetWidth = 1080; // Base width (can be adjusted)
                    $targetHeight = 1920; // Base height (9:16 ratio)
                    
                    $newWidth = $sourceWidth;
                    $newHeight = $sourceHeight;
                    $x = 0;
                    $y = 0;
                    
                    // Crop to 9:16 aspect ratio
                    if ($sourceAspect > $targetAspect) {
                        // Source is wider - crop width
                        $newWidth = (int)($sourceHeight * $targetAspect);
                        $x = (int)(($sourceWidth - $newWidth) / 2);
                    } else {
                        // Source is taller - crop height
                        $newHeight = (int)($sourceWidth / $targetAspect);
                        $y = (int)(($sourceHeight - $newHeight) / 2);
                    }
                    
                    // Create cropped image
                    $croppedImage = imagecreatetruecolor($newWidth, $newHeight);
                    
                    // Preserve transparency for PNG
                    if ($mime === 'image/png') {
                        imagealphablending($croppedImage, false);
                        imagesavealpha($croppedImage, true);
                        $transparent = imagecolorallocatealpha($croppedImage, 0, 0, 0, 127);
                        imagefill($croppedImage, 0, 0, $transparent);
                    }
                    
                    imagecopyresampled($croppedImage, $sourceImage, 0, 0, $x, $y, $newWidth, $newHeight, $newWidth, $newHeight);
                    
                    // Resize to target dimensions
                    $resizedImage = imagecreatetruecolor($targetWidth, $targetHeight);
                    
                    // Preserve transparency for PNG
                    if ($mime === 'image/png') {
                        imagealphablending($resizedImage, false);
                        imagesavealpha($resizedImage, true);
                        $transparent = imagecolorallocatealpha($resizedImage, 0, 0, 0, 127);
                        imagefill($resizedImage, 0, 0, $transparent);
                    }
                    
                    imagecopyresampled($resizedImage, $croppedImage, 0, 0, 0, 0, $targetWidth, $targetHeight, $newWidth, $newHeight);
                    
                    // Save resized image
                    $tempPath = tempnam(sys_get_temp_dir(), 'resized_9_16_');
                    if ($mime === 'image/png') {
                        imagepng($resizedImage, $tempPath, 9);
                    } else {
                        imagejpeg($resizedImage, $tempPath, 90);
                    }
                    
                    imagedestroy($sourceImage);
                    imagedestroy($croppedImage);
                    imagedestroy($resizedImage);
                    
                    $file = new \Illuminate\Http\File($tempPath);
                }
            } catch (\Exception $e) {
                \Log::warning("Image resize to 9:16 failed: " . $e->getMessage());
                // Continue with original file if resize fails
            }
        } elseif (strpos($file->getMimeType(), 'image/') !== false && $file->getSize() > 10 * 1024 * 1024) {
            // Backend compression fallback for large images that aren't profile images
            try {
                $path = $file->getRealPath();
                $mime = $file->getMimeType();
                $image = null;

                if ($mime === 'image/jpeg' || $mime === 'image/jpg') {
                    $image = imagecreatefromjpeg($path);
                } elseif ($mime === 'image/png') {
                    $image = imagecreatefrompng($path);
                } elseif ($mime === 'image/webp') {
                    $image = imagecreatefromwebp($path);
                }

                if ($image) {
                    $tempPath = tempnam(sys_get_temp_dir(), 'compressed_');
                    imagejpeg($image, $tempPath, 80);
                    imagedestroy($image);
                    $file = new \Illuminate\Http\File($tempPath);
                }
            } catch (\Exception $e) {
                \Log::warning("Backend compression failed: " . $e->getMessage());
            }
        }

        $path = $file->store("talent/{$profile->id}/{$folder}", $disk);

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
        $adminUser = auth()->user();
        
        // Load user relationship before logging
        $talentProfile->load('user');

        $talentProfile->update([
            'verification_status' => 'approved',
            'verification_notes'  => $notes,
            'onboarding_step'     => 'completed',
            'onboarding_completed_at' => $talentProfile->onboarding_completed_at ?? now(),
        ]);

        // Log talent acceptance
        $this->logAuditEvent(
            'talent_accepted',
            $adminUser->isSuperAdmin() ? 'superadmin' : ($adminUser->isCreative() ? 'creative' : 'admin'),
            $adminUser->id,
            $adminUser->email,
            null,
            $request,
            "Accepted talent: {$talentProfile->display_name} (ID: {$talentProfile->id})",
            [
                'talent_profile_id' => $talentProfile->id,
                'talent_name' => $talentProfile->display_name ?? ($talentProfile->first_name . ' ' . $talentProfile->last_name),
                'talent_email' => $talentProfile->user->email ?? null,
                'talent_phone' => $talentProfile->user->phone_number ?? null,
                'admin_name' => $adminUser->name,
                'notes' => $notes,
            ]
        );

        $this->triggerNotification($talentProfile, 'talent_profile_approval', $notes);

        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyAdmins('admin_profile_status_update', [
            'name'       => $talentProfile->display_name,
            'status'     => 'approved',
            'admin_name' => optional(auth()->user())->name ?? 'Admin',
        ], [
            'talent_profile_id' => $talentProfile->id,
            'type'              => 'admin_approval_action',
        ]);

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

        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyAdmins('admin_profile_status_update', [
            'name'       => $talentProfile->display_name,
            'status'     => 'rejected',
            'admin_name' => optional(auth()->user())->name ?? 'Admin',
        ], [
            'talent_profile_id' => $talentProfile->id,
            'type'              => 'admin_rejection_action',
        ]);

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

        $notificationService = app(\App\Services\NotificationService::class);
        $notificationService->notifyAdmins('admin_profile_status_update', [
            'name'       => $talentProfile->display_name,
            'status'     => 'reactivated',
            'admin_name' => optional(auth()->user())->name ?? 'Admin',
        ], [
            'talent_profile_id' => $talentProfile->id,
            'type'              => 'admin_reactivation_action',
        ]);

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
                    // It's a dedicated talent user - delete profile but keep user so they can re-register
                    $talentProfile->forceDelete();

                    // Remove any other talent profiles for this user (DB allows multiple; FK would block user DB)
                    $otherProfiles = TalentProfile::where('user_id', $user->id)->get();
                    foreach ($otherProfiles as $profile) {
                        $profile->languages()->detach();
                        $profile->labels()->detach();
                        CastingApplication::where('talent_profile_id', $profile->id)->forceDelete();
                        BankDetail::where('talent_profile_id', $profile->id)->forceDelete();
                        TalentMedia::where('talent_profile_id', $profile->id)->forceDelete();
                        TalentSetting::where('talent_profile_id', $profile->id)->delete();
                        $profile->forceDelete();
                    }

                    // Nullify references in casting_requirements before deleting the user
                    DB::table('casting_requirements')->where('user_id', $user->id)->update(['user_id' => null]);

                    // Don't delete the user - allow them to re-register and complete onboarding again
                    // Just remove roles if any
                    $user->roles()->detach();
                    // Keep user record so they can login and create a new profile
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

    public function uploadProfileImage(TalentProfile $talentProfile, Request $request): JsonResponse
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:6144'],
        ]);

        try {
            // Delete old image if exists
            $this->deletePhysicalFile($talentProfile->headshot_center_path);
            
            $imagePath = $this->storeTalentFile($talentProfile, $request->file('profile_image'), 'headshot-center');
            
            $talentProfile->update([
                'headshot_center_path' => $imagePath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image uploaded successfully.',
                'image_url' => $imagePath,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to upload profile image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload image. Please try again.',
            ], 500);
        }
    }

    public function removeProfileImage(TalentProfile $talentProfile): JsonResponse
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            // Delete the file from storage
            $this->deletePhysicalFile($talentProfile->headshot_center_path);

            $talentProfile->update([
                'headshot_center_path' => null,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile image removed successfully.',
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to remove profile image: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove image. Please try again.',
            ], 500);
        }
    }

    public function destroyMedia(TalentMedia $talentMedia)
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $this->deletePhysicalFile($talentMedia->file_path);
        $talentMedia->delete();

        return response()->json(['success' => true]);
    }

    private function deletePhysicalFile(?string $url): void
    {
        if (!$url) return;

        try {
            $disk = config('filesystems.cloud', 's3');
            $storage = \Storage::disk($disk);
            
            // Extract path from URL. URLs look like: https://bucket.s3.region.amazonaws.com/talent/ID/folder/file.jpg
            // Or they might be relative paths if config is different.
            $baseUrl = $storage->url('/');
            $path = $url;
            
            if (strpos($url, 'http') === 0) {
                // It's a full URL. Try to find the part after the bucket name or custom domain.
                // A robust way is to look for the "talent/" prefix which we know we use.
                if (strpos($url, '/talent/') !== false) {
                    $path = substr($url, strpos($url, 'talent/'));
                } else {
                    // Fallback to simple replacement if possible
                    $path = str_replace(rtrim($baseUrl, '/'), '', $url);
                    $path = ltrim($path, '/');
                }
            }

            if ($storage->exists($path)) {
                $storage->delete($path);
            }
        } catch (\Exception $e) {
            \Log::warning("Could not delete file from S3: " . $url . " Error: " . $e->getMessage());
        }
    }

    protected function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
    }

    /**
     * Generate a presigned PUT URL for uploading a profile image directly to S3.
     */
    public function presignProfileImage(TalentProfile $talentProfile, Request $request): JsonResponse
    {
        abort_if(Gate::denies('talent_profile_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = $request->validate([
            'file_name' => ['required', 'string', 'max:255'],
            'file_type' => ['required', 'string', 'max:100'],
            'field' => ['nullable', 'string', 'max:255'], // Optional: field name like 'headshot_center_path'
        ]);

        $fileName = (string) $data['file_name'];
        $fileType = (string) $data['file_type'];
        $field = $data['field'] ?? null;

        // Basic allowlist (client-side also restricts to images).
        $allowed = [
            'image/jpeg' => 'jpg',
            'image/jpg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            'image/heic' => 'heic',
            'image/heif' => 'heif',
            'image/bmp' => 'bmp',
            'image/svg+xml' => 'svg',
        ];

        if (!array_key_exists($fileType, $allowed)) {
            return response()->json(['message' => 'Invalid file type.'], 422);
        }

        $ext = $allowed[$fileType] ?? (pathinfo($fileName, PATHINFO_EXTENSION) ?: 'jpg');
        $uuid = (string) Str::uuid();
        
        // Determine the S3 key based on field type
        if ($field && in_array($field, ['headshot_center_path', 'headshot_left_path', 'headshot_right_path', 'full_body_front_path', 'full_body_right_path', 'full_body_back_path', 'id_front_path', 'id_document_front'])) {
            // Standard profile field images
            $folder = match($field) {
                'headshot_center_path', 'headshot_left_path', 'headshot_right_path' => 'headshots',
                'full_body_front_path', 'full_body_right_path', 'full_body_back_path' => 'full-body',
                'id_front_path', 'id_document_front' => 'id-documents',
                default => 'profile-photos',
            };
            $key = "talent/{$talentProfile->id}/{$folder}/{$uuid}.{$ext}";
        } else {
            // Additional media files
            $key = "talent/{$talentProfile->id}/photos/profile/{$uuid}.{$ext}";
        }

        $diskName = config('filesystems.cloud', 's3');
        $diskConfig = config("filesystems.disks.{$diskName}");
        
        if (!$diskConfig || $diskConfig['driver'] !== 's3') {
            return response()->json(['message' => 'S3 disk not configured.'], 500);
        }

        $bucket = $diskConfig['bucket'] ?? null;
        if (!$bucket) {
            return response()->json(['message' => 'S3 bucket not configured.'], 500);
        }

        // Create S3 client directly from config
        $s3Config = [
            'version' => 'latest',
            'region' => $diskConfig['region'] ?? 'us-east-1',
            'credentials' => [
                'key' => $diskConfig['key'] ?? null,
                'secret' => $diskConfig['secret'] ?? null,
            ],
        ];

        // Add endpoint if configured (for S3-compatible services)
        if (!empty($diskConfig['endpoint'])) {
            $s3Config['endpoint'] = $diskConfig['endpoint'];
            if (!empty($diskConfig['use_path_style_endpoint'])) {
                $s3Config['use_path_style_endpoint'] = true;
            }
        }

        try {
            $client = new \Aws\S3\S3Client($s3Config);
        } catch (\Exception $e) {
            Log::error('Failed to create S3 client for presigning', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to initialize S3 client.'], 500);
        }

        // Create presigned PUT URL - don't include ACL as it may be disabled on bucket
        $command = $client->getCommand('PutObject', [
            'Bucket' => $bucket,
            'Key' => $key,
            'ContentType' => $fileType,
        ]);

        try {
            $presignedRequest = $client->createPresignedRequest($command, '+20 minutes');
            $presignedUrl = (string) $presignedRequest->getUri();
        } catch (\Exception $e) {
            Log::error('Failed to create presigned URL', ['error' => $e->getMessage(), 'key' => $key]);
            return response()->json(['message' => 'Failed to generate presigned URL.'], 500);
        }

        return response()->json([
            'key' => $key,
            'url' => $presignedUrl,
            'headers' => [
                'Content-Type' => $fileType,
            ],
        ]);
    }
}
