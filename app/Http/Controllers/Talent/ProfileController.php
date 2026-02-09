<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Language;
use App\Models\TalentProfile;
use App\Services\MuxService;
use App\Traits\LogsAuditEvents;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProfileController extends Controller
{
    use LogsAuditEvents;
    public function show(Request $request): View
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        // Get MUX playback ID if video exists
        $muxPlaybackId = null;
        if ($profile->mux_video_asset_id) {
            try {
                $muxService = new MuxService();
                $muxPlaybackId = $muxService->getPlaybackId($profile->mux_video_asset_id);
            } catch (\Exception $e) {
                // Log error but don't break the page
                \Log::error('Failed to get MUX playback ID: ' . $e->getMessage());
            }
        }

        // Stats: shoots completed from selected applications
        $shootsCompleted = 0;
        if ($profile) {
            $shootsCompleted = \App\Models\CastingApplication::where('talent_profile_id', $profile->id)
                ->where('status', 'selected')
                ->count();
        }

        return view('talent.profile.index', [
            'profile'          => $profile,
            'skinToneOptions'  => TalentProfile::SKIN_TONE_SELECT,
            'statusOptions'    => TalentProfile::VERIFICATION_STATUS_SELECT,
            'muxPlaybackId'    => $muxPlaybackId,
            'shootsCompleted'  => $shootsCompleted,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        $data = $request->validate([
            'legal_name'       => ['required', 'string', 'max:150'],
            'display_name'     => ['nullable', 'string', 'max:150'],
            'email'            => ['required', 'email', 'max:255'],
            'date_of_birth'    => ['nullable', 'date'],
            'gender'           => ['nullable', 'string', 'max:20'],
            'daily_rate'       => ['nullable', 'numeric', 'min:0'],
            'rate'             => ['nullable', 'numeric', 'min:0'],
            'height'           => ['nullable', 'numeric', 'between:0,300'],
            'weight'           => ['nullable', 'numeric', 'between:0,500'],
            'chest'            => ['nullable', 'numeric', 'between:0,300'],
            'waist'            => ['nullable', 'numeric', 'between:0,300'],
            'hips'             => ['nullable', 'numeric', 'between:0,300'],
            'skin_tone'        => ['nullable', 'in:' . implode(',', array_keys(TalentProfile::SKIN_TONE_SELECT))],
            'hair_color'       => ['nullable', 'string', 'max:120'],
            'eye_color'        => ['nullable', 'string', 'max:120'],
            'shoe_size'        => ['nullable', 'numeric', 'between:0,100'],
            'whatsapp_number'  => ['nullable', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
            'bio'              => ['nullable', 'string', 'max:1000'],
            // ID documents are not editable by talent - removed from validation
            'headshot_center'  => ['nullable', 'image', 'max:6144'],
            'headshot_left'    => ['nullable', 'image', 'max:6144'],
            'headshot_right'   => ['nullable', 'image', 'max:6144'],
            'full_body_front'  => ['nullable', 'image', 'max:6144'],
            'full_body_right'  => ['nullable', 'image', 'max:6144'],
            'full_body_back'   => ['nullable', 'image', 'max:6144'],
            'uploaded_keys' => ['nullable', 'array'],
            'uploaded_keys.*' => ['string'],
            'uploaded_fields' => ['nullable', 'array'],
            'uploaded_fields.*' => ['string'],
            'deleted_fields' => ['nullable', 'array'],
            'deleted_fields.*' => ['string'],
            'additional_photo_keys' => ['nullable', 'array'],
            'additional_photo_keys.*' => ['string'],
            'deleted_media_ids' => ['nullable', 'array'],
            'deleted_media_ids.*' => ['integer'],
            'video' => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm', 'max:512000'],
        ]);

        $user->update([
            'name'  => $data['legal_name'],
            'email' => $data['email'],
        ]);

        $profile->update([
            'legal_name'        => $data['legal_name'],
            'display_name'      => $data['display_name'] ?: $data['legal_name'],
            'daily_rate'        => Arr::get($data, 'daily_rate'),
            'rate'              => Arr::get($data, 'rate'),
            'date_of_birth'     => Arr::get($data, 'date_of_birth'),
            'gender'            => Arr::get($data, 'gender'),
            'height'            => Arr::get($data, 'height'),
            'weight'            => Arr::get($data, 'weight'),
            'chest'             => Arr::get($data, 'chest'),
            'waist'             => Arr::get($data, 'waist'),
            'hips'              => Arr::get($data, 'hips'),
            'skin_tone'         => Arr::get($data, 'skin_tone'),
            'hair_color'        => Arr::get($data, 'hair_color'),
            'eye_color'         => Arr::get($data, 'eye_color'),
            'shoe_size'         => Arr::get($data, 'shoe_size'),
            'bio'               => Arr::get($data, 'bio'),
            'whatsapp_number'   => $this->sanitizePhoneNumber(Arr::get($data, 'whatsapp_number')),
        ]);


        $uploadMap = [
            // ID documents are not editable by talent - removed from upload map
            'headshot_center' => ['column' => 'headshot_center_path', 'folder' => 'headshot-center'],
            'headshot_left'   => ['column' => 'headshot_left_path', 'folder' => 'headshot-left'],
            'headshot_right'  => ['column' => 'headshot_right_path', 'folder' => 'headshot-right'],
            'full_body_front' => ['column' => 'full_body_front_path', 'folder' => 'full-body-front'],
            'full_body_right' => ['column' => 'full_body_right_path', 'folder' => 'full-body-right'],
            'full_body_back'  => ['column' => 'full_body_back_path', 'folder' => 'full-body-back'],
        ];

        foreach ($uploadMap as $field => $meta) {
            if ($request->hasFile($field)) {
                $profile->update([
                    $meta['column'] => $this->storeTalentFile($profile, $request->file($field), $meta['folder']),
                ]);
            }
        }

        // Handle uploaded S3 keys for profile images (headshots, full body)
        if ($request->has('uploaded_keys') && $request->has('uploaded_fields')) {
            $uploadedKeys = $request->input('uploaded_keys', []);
            $uploadedFields = $request->input('uploaded_fields', []);
            $disk = config('filesystems.cloud', 's3');
            $storage = Storage::disk($disk);
            
            // Map field names to database columns
            $fieldMap = [
                'headshot_center_path' => 'headshot_center_path',
                'headshot_left_path' => 'headshot_left_path',
                'headshot_right_path' => 'headshot_right_path',
                'full_body_front_path' => 'full_body_front_path',
                'full_body_right_path' => 'full_body_right_path',
                'full_body_back_path' => 'full_body_back_path',
            ];
            
            foreach ($uploadedKeys as $index => $key) {
                $field = $uploadedFields[$index] ?? null;
                if ($field && isset($fieldMap[$field])) {
                    $url = $storage->url($key);
                    $profile->update([$fieldMap[$field] => $url]);
                }
            }
        }

        // Handle deleted fields (when user removes an image)
        if ($request->has('deleted_fields')) {
            $deletedFields = $request->input('deleted_fields', []);
            $fieldMap = [
                'headshot_center_path' => 'headshot_center_path',
                'headshot_left_path' => 'headshot_left_path',
                'headshot_right_path' => 'headshot_right_path',
                'full_body_front_path' => 'full_body_front_path',
                'full_body_right_path' => 'full_body_right_path',
                'full_body_back_path' => 'full_body_back_path',
            ];
            
            $updates = [];
            foreach ($deletedFields as $field) {
                if (isset($fieldMap[$field])) {
                    $updates[$fieldMap[$field]] = null;
                }
            }
            if (!empty($updates)) {
                $profile->update($updates);
            }
        }

        // Handle uploaded S3 keys (from AJAX uploads of additional photos)
        if ($request->has('additional_photo_keys')) {
            $uploadedKeys = $request->input('additional_photo_keys', []);
            $disk = config('filesystems.cloud', 's3');
            $storage = Storage::disk($disk);
            
            foreach ($uploadedKeys as $key) {
                // Generate the full URL for the S3 key
                $url = $storage->url($key);
                
                // Create a TalentMedia record for additional photos
                \App\Models\TalentMedia::create([
                    'talent_profile_id' => $profile->id,
                    'file_path' => $url,
                    'type' => 'profile',
                ]);
            }
        }

        // Handle TalentMedia deletions
        if ($request->has('deleted_media_ids')) {
            $deletedIds = $request->input('deleted_media_ids');
            $mediaItems = \App\Models\TalentMedia::whereIn('id', $deletedIds)
                ->where('talent_profile_id', $profile->id)
                ->get();

            foreach ($mediaItems as $media) {
                // Delete the file from storage
                try {
                    $disk = config('filesystems.cloud', 's3');
                    $path = $media->file_path;
                    
                    // Extract relative path if it's a full URL
                    if (str_starts_with($path, ['http://', 'https://'])) {
                        $awsUrl = rtrim((string) env('AWS_URL'), '/');
                        if ($awsUrl && str_starts_with($path, $awsUrl)) {
                            $path = ltrim(str_replace($awsUrl, '', $path), '/');
                        } else {
                            $path = null;
                        }
                    }
                    
                    if ($path) {
                        Storage::disk($disk)->delete($path);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete media file: ' . $e->getMessage());
                }
                $media->delete();
            }
        }

        // Handle video upload with Mux
        $muxVideoAssetId = $profile->mux_video_asset_id;
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            Log::info('Video upload started in profile update.', [
                'filename' => $videoFile->getClientOriginalName(),
                'size' => $videoFile->getSize(),
                'mime' => $videoFile->getMimeType(),
            ]);

            if (!$videoFile->isValid()) {
                Log::error('Video file is invalid.', ['error' => $videoFile->getErrorMessage()]);
                return back()->withErrors(['video' => 'File error: ' . $videoFile->getErrorMessage()])->withInput();
            }

            try {
                Log::info('Initializing MuxService for profile video upload...');
                $muxService = new MuxService();
                Log::info('Calling MuxService::uploadVideo...');
                $muxVideoAssetId = $muxService->uploadVideo($videoFile);
                Log::info('Mux upload successful. Asset ID: ' . $muxVideoAssetId);
                
                $profile->update([
                    'mux_video_asset_id' => $muxVideoAssetId,
                ]);
            } catch (\Exception $e) {
                Log::error('Failed to upload video to MUX: ' . $e->getMessage());
                Log::error($e->getTraceAsString());
                return back()->withErrors(['video' => 'Upload failed: ' . $e->getMessage()])->withInput();
            }
        }

        // Handle video removal (check for remove_video flag)
        if ($request->has('remove_video') && $request->input('remove_video') === '1') {
            $profile->update([
                'mux_video_asset_id' => null,
            ]);
        }

        // Log profile update by talent
        $talentUser = $request->user('talent');
        $changedFields = [];
        $originalData = $profile->getOriginal();
        $currentData = $profile->getAttributes();
        
        // Check which fields changed
        foreach ($data as $key => $value) {
            if (isset($originalData[$key]) && $originalData[$key] != $value) {
                $changedFields[] = $key;
            }
        }
        
        if (!empty($changedFields) || $request->hasFile('video') || $request->has('remove_video') || 
            $request->has('uploaded_keys') || $request->has('deleted_media_ids') || $request->has('additional_photo_keys')) {
            $this->logAuditEvent(
                'profile_updated',
                'talent',
                $talentUser->id,
                $talentUser->email,
                $talentUser->phone_number,
                $request,
                "Updated own profile",
                [
                    'talent_profile_id' => $profile->id,
                    'talent_name' => $profile->display_name ?? ($profile->first_name . ' ' . $profile->last_name),
                    'changed_fields' => $changedFields,
                    'has_video_change' => $request->hasFile('video') || $request->has('remove_video'),
                    'has_media_changes' => $request->has('uploaded_keys') || $request->has('deleted_media_ids') || $request->has('additional_photo_keys'),
                ]
            );
        }

        return redirect()
            ->route('talent.profile.show')
            ->with('message', __('Profile updated successfully.'));
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $disk = config('filesystems.cloud', 's3');
        $path = $file->store("talent/{$profile->id}/{$folder}", $disk);

        // Return full URL for cloud storage
        return Storage::disk($disk)->url($path);
    }

    private function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
    }

    public function uploadImage(Request $request): JsonResponse
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        $request->validate([
            'profile_image' => ['required', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:6144'],
        ]);

        try {
            $imagePath = $this->storeTalentFile($profile, $request->file('profile_image'), 'headshot-center');
            
            $profile->update([
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

    public function removeImage(Request $request): JsonResponse
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        try {
            // Optionally delete the file from storage
            if ($profile->headshot_center_path) {
                try {
                    $disk = config('filesystems.cloud', 's3');
                    $path = $profile->headshot_center_path;
                    
                    // Extract relative path if it's a full URL
                    if (str_starts_with($path, ['http://', 'https://'])) {
                        $awsUrl = rtrim((string) env('AWS_URL'), '/');
                        if ($awsUrl && str_starts_with($path, $awsUrl)) {
                            $path = ltrim(str_replace($awsUrl, '', $path), '/');
                        } else {
                            // If we can't extract the path, just clear the database field
                            $path = null;
                        }
                    }
                    
                    if ($path) {
                        Storage::disk($disk)->delete($path);
                    }
                } catch (\Exception $e) {
                    \Log::warning('Failed to delete profile image file: ' . $e->getMessage());
                }
            }

            $profile->update([
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

    /**
     * Generate a presigned PUT URL for uploading an additional profile photo directly to S3.
     */
    public function presignAdditionalPhoto(Request $request): JsonResponse
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        $data = $request->validate([
            'file_name' => ['required', 'string', 'max:255'],
            'file_type' => ['required', 'string', 'max:100'],
        ]);

        $fileName = (string) $data['file_name'];
        $fileType = (string) $data['file_type'];

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
        $key = "talent/{$profile->id}/photos/profile/{$uuid}.{$ext}";

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

    private function resolveProfile($user): TalentProfile
    {
        if ($user->talentProfile) {
            return $user->talentProfile;
        }

        return $user->talentProfile()->create([
            'legal_name'        => $user->name ?? '',
            'display_name'      => $user->name ?? '',
            'verification_status' => 'pending',
            'daily_rate'        => 0,
            'rate'              => 0,
            'onboarding_step'   => 'profile',
        ]);
    }
}

