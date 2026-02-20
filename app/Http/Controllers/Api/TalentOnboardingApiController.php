<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\TalentProfile;
use App\Services\KwtSmsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use App\Services\MuxService;

class TalentOnboardingApiController extends Controller
{
    /**
     * Send OTP to user's phone number
     */
    public function sendOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone_country_code' => ['required', 'string', 'in:965,+965'],
            'phone_number'       => ['required', 'string', 'regex:/^[0-9]{8}$/'],
        ], [
            'phone_country_code.in' => 'Only Kuwait numbers (965) are allowed for OTP.',
            'phone_number.regex'    => 'The phone number must be exactly 8 digits.',
        ]);

        // Find or create talent user by phone
        $user = User::where('phone_number', $data['phone_number'])->first();

        if ($user) {
            // User exists - update country code and type, but preserve name/email
            $user->phone_country_code = $data['phone_country_code'];
            $user->type = User::TYPE_TALENT;
            $user->save();
        } else {
            // User doesn't exist - create new talent user
            $user = User::create([
                'phone_country_code' => $data['phone_country_code'],
                'phone_number'       => $data['phone_number'],
                'type'               => User::TYPE_TALENT,
                'name'               => null,
                'email'              => null,
            ]);
        }

        // Generate a random 4-digit OTP
        $otp = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
        $otpExpiresAt = Carbon::now()->addMinutes(5);
        $user->otp = $otp;
        $user->otp_expires_at = $otpExpiresAt;
        $user->otp_consumed = false;
        $user->otp_attempts = 0;
        $user->save();

        // Check if phone number starts with 123
        $phoneStartsWith123 = substr($user->phone_number, 0, 3) === '123';

        if ($phoneStartsWith123) {
            // For phone numbers starting with 123, skip SMS service and just log OTP
            Log::info('Talent OTP (Test number - no SMS sent) for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);
        } else {
            // Send OTP via KWT SMS for regular phone numbers
            $smsService = new KwtSmsService();
            $mobile = KwtSmsService::formatMobileNumber($user->phone_country_code, $user->phone_number);
            $smsResult = $smsService->sendOtp($mobile, $otp);

            if (!$smsResult['success']) {
                Log::error('Failed to send OTP SMS', [
                    'user_id' => $user->id,
                    'mobile' => $mobile,
                    'error' => $smsResult['message'],
                ]);

                // Log the OTP for development/testing purposes
                Log::info('Talent OTP (SMS failed) for phone ' . $user->phone_country_code . $user->phone_number . ': ' . $otp);
            } else {
                Log::info('OTP sent successfully via SMS', [
                    'user_id' => $user->id,
                    'mobile' => $mobile,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP has been sent to your phone.',
            'otp' => $phoneStartsWith123 ? $otp : null, // Only return OTP for test numbers
            'expires_at' => $otpExpiresAt->toIso8601String(),
        ], 200);
    }

    /**
     * Verify OTP and return authentication token with onboarding status
     */
    public function verifyOtp(Request $request): JsonResponse
    {
        $data = $request->validate([
            'phone_country_code' => ['required', 'string'],
            'phone_number'       => ['required', 'string'],
            'otp'                => ['required', 'string', 'size:4'],
        ]);

        $user = User::where('phone_country_code', $data['phone_country_code'])
            ->where('phone_number', $data['phone_number'])
            ->where('type', User::TYPE_TALENT)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }

        // Check expiry
        if (!$user->otp || Carbon::now()->greaterThan(Carbon::parse($user->otp_expires_at))) {
            return response()->json([
                'success' => false,
                'message' => 'OTP expired. Please request a new one.',
            ], 422);
        }

        if ($user->otp_consumed) {
            return response()->json([
                'success' => false,
                'message' => 'OTP already used. Please request a new one.',
            ], 422);
        }

        if (!hash_equals($user->otp, $data['otp'])) {
            // Increment attempts
            $user->increment('otp_attempts');
            
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP.',
            ], 422);
        }

        // Mark consumed and clear OTP
        $user->otp_consumed = true;
        $user->otp = null;
        $user->otp_expires_at = null;
        $user->save();

        // Create Sanctum token
        $token = $user->createToken('talent-api-token')->plainTextToken;

        // Get or create profile
        $profile = $user->talentProfile;
        if (!$profile) {
            $profile = TalentProfile::create([
                'user_id' => $user->id,
                'legal_name' => $user->name ?? '',
                'display_name' => $user->name ?? '',
                'daily_rate' => 0,
                'rate' => 0,
                'verification_status' => 'pending',
                'whatsapp_number' => null,
                'onboarding_step' => 'step-1',
                'onboarding_steps_completed' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully.',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'phone_number' => $user->phone_number,
                'phone_country_code' => $user->phone_country_code,
            ],
            'onboarding' => [
                'completed' => $profile->hasCompletedOnboarding(),
                'current_step' => $profile->onboarding_step ?? 'step-1',
                'steps_completed' => $profile->onboarding_steps_completed ?? 0,
                'completed_at' => $profile->onboarding_completed_at?->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Save onboarding step 1 data
     */
    public function saveStep1(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
        $data = $request->validate([
            'first_name'        => ['required', 'string', 'max:120', 'regex:/^[a-zA-Z\s]+$/'],
            'last_name'         => ['required', 'string', 'max:120', 'regex:/^[a-zA-Z\s]+$/'],
            'date_of_birth'     => ['required', 'date', "before_or_equal:$eighteenYearsAgo"],
            'nationality'       => ['nullable', 'string', 'max:120'],
            'country_code'      => ['required', 'string', 'max:10'],
            'mobile_number'     => ['required', 'string', 'max:30'],
            'whatsapp_number'   => ['nullable', 'required_if:whatsapp_choice,alt', 'string', 'max:30'],
            'whatsapp_choice'   => ['required', 'in:same,alt'],
        ], [
            'date_of_birth.before_or_equal' => 'You must be at least 18 years old to join.',
            'first_name.regex' => 'First name must contain only English letters.',
            'last_name.regex' => 'Last name must contain only English letters.',
        ]);

        // Auto-capitalize names
        $firstName = ucwords(strtolower(trim($data['first_name'])));
        $lastName = ucwords(strtolower(trim($data['last_name'])));
        $fullName = trim($firstName . ' ' . $lastName);
        
        $user->update(['name' => $fullName]);

        $whatsappNumber = ($data['whatsapp_choice'] === 'same')
            ? $data['mobile_number']
            : ($data['whatsapp_number'] ?? $data['mobile_number']);

        $profile->update([
            'first_name'        => $firstName,
            'last_name'         => $lastName,
            'legal_name'        => $fullName,
            'display_name'      => $fullName,
            'nationality'       => Arr::get($data, 'nationality'),
            'country_code'      => $data['country_code'],
            'mobile_number'     => $this->sanitizePhoneNumber($data['mobile_number']),
            'whatsapp_number'   => $this->sanitizePhoneNumber($whatsappNumber),
            'date_of_birth'     => $data['date_of_birth'],
            'onboarding_step'   => 'step-2',
            'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 1),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Step 1 data saved successfully.',
            'data' => $this->getStep1Data($profile),
            'onboarding' => [
                'current_step' => $profile->onboarding_step,
                'steps_completed' => $profile->onboarding_steps_completed,
            ],
        ], 200);
    }

    /**
     * Save onboarding step 2 data
     */
    public function saveStep2(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        $data = $request->validate([
            'height'            => ['nullable', 'numeric', 'between:50,300'],
            'weight'            => ['nullable', 'numeric', 'between:40,200'],
            'gender'            => ['required', 'string', 'max:20'],
            'hijab_preference'  => ['nullable', 'string', 'max:50'],
            'hair_color'        => ['nullable', 'string', 'max:120'],
            'eye_color'         => ['nullable', 'string', 'max:120'],
            'skin_tone'         => ['nullable', 'string', 'max:60'],
            'has_visible_tattoos' => ['required', 'in:0,1'],
            'has_piercings'       => ['required', 'in:0,1'],
        ]);

        $profile->update([
            'gender'            => $data['gender'],
            'hijab_preference'  => $data['gender'] === 'female' ? Arr::get($data, 'hijab_preference') : null,
            'height'            => Arr::get($data, 'height'),
            'weight'            => Arr::get($data, 'weight'),
            'hair_color'        => Arr::get($data, 'hair_color'),
            'eye_color'         => Arr::get($data, 'eye_color'),
            'skin_tone'         => Arr::get($data, 'skin_tone'),
            'has_visible_tattoos' => (bool) $data['has_visible_tattoos'],
            'has_piercings'       => (bool) $data['has_piercings'],
            'onboarding_step'   => 'step-3',
            'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 2),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Step 2 data saved successfully.',
            'data' => $this->getStep2Data($profile),
            'onboarding' => [
                'current_step' => $profile->onboarding_step,
                'steps_completed' => $profile->onboarding_steps_completed,
            ],
        ], 200);
    }

    /**
     * Save onboarding step 3 data
     */
    public function saveStep3(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        $isFemale = strtolower($profile->gender ?? '') === 'female';
        
        $validationRules = [
            't_shirt_size'      => ['required', 'string', 'max:20'],
            'shoe_size'         => ['required', 'numeric', 'between:0,100'],
        ];
        
        if ($isFemale) {
            $validationRules['dress_size'] = ['required', 'string', 'max:20'];
        } else {
            $validationRules['dress_size'] = ['nullable', 'string', 'max:20'];
        }
        
        $data = $request->validate($validationRules);

        $updateData = [
            't_shirt_size'      => Arr::get($data, 't_shirt_size'),
            'shoe_size'         => Arr::get($data, 'shoe_size'),
            'onboarding_step'   => 'step-4',
            'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 3),
        ];
        
        if ($isFemale) {
            $updateData['dress_size'] = Arr::get($data, 'dress_size');
        } else {
            $updateData['dress_size'] = null;
        }

        $profile->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Step 3 data saved successfully.',
            'data' => $this->getStep3Data($profile),
            'onboarding' => [
                'current_step' => $profile->onboarding_step,
                'steps_completed' => $profile->onboarding_steps_completed,
            ],
        ], 200);
    }

    /**
     * Save onboarding step 4 data
     */
    public function saveStep4(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        $requireDoc = empty($profile->id_document_front);
        $hasIdKey = $request->filled('id_document_key');
        $idDocRequired = $requireDoc && !$hasIdKey;

        $data = $request->validate([
            'id_document_front' => [$idDocRequired ? 'required' : 'nullable', 'file', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg,heic,heif'],
            'id_document_key' => ['nullable', 'string'],
        ]);

        $updateData = [
            'onboarding_step'   => 'step-5',
            'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 4),
        ];

        $idKey = $request->input('id_document_key');
        if (is_string($idKey) && $idKey !== '') {
            $expectedPrefix = "talent/{$profile->id}/id/front/";
            if (str_starts_with($idKey, $expectedPrefix)) {
                $s3Disk = config('filesystems.cloud', 's3');
                $storageDisk = Storage::disk($s3Disk);
                try {
                    $updateData['id_document_front'] = $storageDisk->url($idKey);
                } catch (\Exception $e) {
                    $updateData['id_document_front'] = $idKey;
                }
            }
        } elseif ($request->hasFile('id_document_front')) {
            $s3Disk = config('filesystems.cloud', 's3');
            $updateData['id_document_front'] = $this->storeTalentFile(
                $profile,
                $request->file('id_document_front'),
                'id/front',
                $s3Disk,
                true
            );
        }

        $profile->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'Step 4 data saved successfully.',
            'data' => $this->getStep4Data($profile),
            'onboarding' => [
                'current_step' => $profile->onboarding_step,
                'steps_completed' => $profile->onboarding_steps_completed,
            ],
        ], 200);
    }

    /**
     * Save onboarding step 5 data
     */
    public function saveStep5(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        $data = $request->validate([
            'video'             => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm', 'max:512000'],
            'additional_photos' => ['nullable', 'array'],
            'additional_photos.*' => ['file', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg,heic,heif'],
            'additional_photo_keys' => ['nullable', 'array'],
            'additional_photo_keys.*' => ['string'],
        ]);

        $muxVideoAssetId = $profile->mux_video_asset_id;
        if ($request->hasFile('video')) {
            $videoFile = $request->file('video');
            if ($videoFile->isValid()) {
                try {
                    $muxService = new MuxService();
                    $muxVideoAssetId = $muxService->uploadVideo($videoFile);
                } catch (\Exception $e) {
                    Log::error('Failed to upload video to MUX: ' . $e->getMessage());
                    return response()->json([
                        'success' => false,
                        'message' => 'Upload failed: ' . $e->getMessage(),
                    ], 422);
                }
            }
        }

        // Handle additional photos
        $uploadedKeys = $request->input('additional_photo_keys', []);
        if (is_array($uploadedKeys) && count($uploadedKeys) > 0) {
            $profile->media()->where('type', 'profile')->delete();
            $s3Disk = config('filesystems.cloud', 's3');
            $storageDisk = Storage::disk($s3Disk);
            $expectedPrefix = "talent/{$profile->id}/photos/profile/";

            foreach ($uploadedKeys as $key) {
                if (is_string($key) && $key !== '' && str_starts_with($key, $expectedPrefix)) {
                    try {
                        $filePathToStore = $key;
                        try {
                            $filePathToStore = $storageDisk->url($key);
                        } catch (\Exception $e) {
                            // Keep key as fallback
                        }

                        $profile->media()->create([
                            'file_path' => $filePathToStore,
                            'type' => 'profile',
                        ]);
                    } catch (\Exception $e) {
                        Log::error("Failed to save photo key", ['error' => $e->getMessage(), 'key' => $key]);
                    }
                }
            }
        } elseif ($request->hasFile('additional_photos')) {
            $profile->media()->where('type', 'profile')->delete();
            $s3Disk = config('filesystems.cloud', 's3');

            foreach ($request->file('additional_photos') as $photo) {
                try {
                    $path = $this->storeTalentFile($profile, $photo, 'photos/profile', $s3Disk, true);
                    $profile->media()->create([
                        'file_path' => $path,
                        'type' => 'profile',
                    ]);
                } catch (\Exception $e) {
                    Log::error("Failed to upload photo", ['error' => $e->getMessage()]);
                }
            }
        }

        $profile->update([
            'mux_video_asset_id' => $muxVideoAssetId,
            'onboarding_step'   => 'step-5',
            'onboarding_steps_completed' => 5,
            'onboarding_completed_at' => now(),
            'verification_status'     => 'pending',
        ]);

        // Refresh profile to get updated data
        $profile->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Step 5 data saved successfully. Onboarding completed!',
            'data' => $this->getStep5Data($profile),
            'onboarding' => [
                'completed' => true,
                'current_step' => $profile->onboarding_step,
                'steps_completed' => $profile->onboarding_steps_completed,
                'completed_at' => $profile->onboarding_completed_at ? Carbon::parse($profile->onboarding_completed_at)->toIso8601String() : null,
            ],
        ], 200);
    }

    /**
     * Get saved data for all onboarding steps
     */
    public function getOnboardingData(Request $request): JsonResponse
    {
        $user = $request->user();
        $profile = $this->getOrCreateProfile($user);

        return response()->json([
            'success' => true,
            'data' => [
                'step_1' => $this->getStep1Data($profile),
                'step_2' => $this->getStep2Data($profile),
                'step_3' => $this->getStep3Data($profile),
                'step_4' => $this->getStep4Data($profile),
                'step_5' => $this->getStep5Data($profile),
            ],
            'onboarding' => [
                'completed' => $profile->hasCompletedOnboarding(),
                'current_step' => $profile->onboarding_step ?? 'step-1',
                'steps_completed' => $profile->onboarding_steps_completed ?? 0,
                'completed_at' => $profile->onboarding_completed_at?->toIso8601String(),
            ],
        ], 200);
    }

    /**
     * Get step 1 data
     */
    private function getStep1Data(TalentProfile $profile): array
    {
        $whatsappChoice = 'same';
        if ($profile->whatsapp_number && $profile->whatsapp_number !== $profile->mobile_number) {
            $whatsappChoice = 'alt';
        }

        return [
            'first_name' => $profile->first_name,
            'last_name' => $profile->last_name,
            'date_of_birth' => $profile->date_of_birth?->format('Y-m-d'),
            'nationality' => $profile->nationality,
            'country_code' => $profile->country_code,
            'mobile_number' => $profile->mobile_number,
            'whatsapp_number' => $profile->whatsapp_number,
            'whatsapp_choice' => $whatsappChoice,
        ];
    }

    /**
     * Get step 2 data
     */
    private function getStep2Data(TalentProfile $profile): array
    {
        return [
            'height' => $profile->height,
            'weight' => $profile->weight,
            'gender' => $profile->gender,
            'hijab_preference' => $profile->hijab_preference,
            'hair_color' => $profile->hair_color,
            'eye_color' => $profile->eye_color,
            'skin_tone' => $profile->skin_tone,
            'has_visible_tattoos' => $profile->has_visible_tattoos ? 1 : 0,
            'has_piercings' => $profile->has_piercings ? 1 : 0,
        ];
    }

    /**
     * Get step 3 data
     */
    private function getStep3Data(TalentProfile $profile): array
    {
        return [
            't_shirt_size' => $profile->t_shirt_size,
            'dress_size' => $profile->dress_size,
            'shoe_size' => $profile->shoe_size,
        ];
    }

    /**
     * Get step 4 data
     */
    private function getStep4Data(TalentProfile $profile): array
    {
        return [
            'id_document_front' => $profile->id_document_front,
        ];
    }

    /**
     * Get step 5 data
     */
    private function getStep5Data(TalentProfile $profile): array
    {
        return [
            'mux_video_asset_id' => $profile->mux_video_asset_id,
            'additional_photos' => $profile->media()->where('type', 'profile')->get()->map(function ($media) {
                return $media->file_path;
            })->toArray(),
        ];
    }

    /**
     * Get or create profile for user
     */
    private function getOrCreateProfile(User $user): TalentProfile
    {
        $profile = $user->talentProfile;

        if (!$profile) {
            $profile = TalentProfile::create([
                'user_id' => $user->id,
                'legal_name' => $user->name ?? '',
                'display_name' => $user->name ?? '',
                'daily_rate' => 0,
                'rate' => 0,
                'verification_status' => 'pending',
                'whatsapp_number' => null,
                'onboarding_step' => 'step-1',
                'onboarding_steps_completed' => 0,
            ]);
        }

        return $profile;
    }

    /**
     * Sanitize phone number
     */
    private function sanitizePhoneNumber(?string $number): ?string
    {
        if (!$number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);
        return $digits ?: null;
    }

    /**
     * Store talent file
     */
    private function storeTalentFile(TalentProfile $profile, $file, string $folder, ?string $disk = null, bool $compress = false): string
    {
        $disk = $disk ?: config('filesystems.default', 'public');

        if ($compress && strpos($file->getMimeType(), 'image/') !== false && $file->getSize() > 10 * 1024 * 1024) {
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
                } elseif ($mime === 'image/gif') {
                    $image = imagecreatefromgif($path);
                }

                if ($image) {
                    $tempPath = tempnam(sys_get_temp_dir(), 'compressed_');
                    imagejpeg($image, $tempPath, 75);
                    imagedestroy($image);
                    $file = new \Illuminate\Http\File($tempPath);
                }
            } catch (\Exception $e) {
                Log::warning("Backend image compression failed: " . $e->getMessage());
            }
        }

        $path = $file->store("talent/{$profile->id}/{$folder}", $disk);

        $cloudDisk = config('filesystems.cloud', 's3');
        if ($disk === $cloudDisk) {
            try {
                return Storage::disk($disk)->url($path);
            } catch (\Exception $e) {
                return $path;
            }
        }

        return $path;
    }
}
