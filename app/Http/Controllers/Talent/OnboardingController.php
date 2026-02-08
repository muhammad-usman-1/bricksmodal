<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\TalentProfile;
use App\Models\TalentMedia;
use App\Services\MuxService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Str;
use App\Models\User;
use App\Notifications\TalentSignupCompleted;

class OnboardingController extends Controller
{
    // Logical steps; view remains a single blade (profile)
    private const STEPS = [
        'step-1',
        'step-2',
        'step-3',
        'step-4',
        'step-5',
    ];

    public function start(Request $request): RedirectResponse
    {
        $profile = $this->profile($request);

        if ($profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                // If onboarding is completed but not approved, check if it was just completed
                if (session('onboarding_just_completed')) {
                    return redirect()->route('talent.pending');
                }
                // Otherwise, redirect to pending-status for subsequent visits
                return redirect()->route('talent.pending_status');
            }

            return redirect()->route('talent.dashboard');
        }

        return $this->redirectToCurrentStep($profile);
    }

    public function intro(Request $request): View|RedirectResponse
    {
        $profile = $this->profile($request);

        if ($profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                // If onboarding is completed but not approved, check if it was just completed
                if (session('onboarding_just_completed')) {
                    return redirect()->route('talent.pending');
                }
                // Otherwise, redirect to pending-status for subsequent visits
                return redirect()->route('talent.pending_status');
            }

            return redirect()->route('talent.dashboard');
        }

        if ($profile->onboarding_step && $profile->onboarding_step !== 'profile') {
            return $this->redirectToCurrentStep($profile);
        }

        return view('talent.onboarding.intro', [
            'profile'     => $profile,
            'startRoute'  => route('talent.onboarding.show', 'step-1'),
        ]);
    }

    public function show(Request $request, string $step): View|RedirectResponse
    {
        $profile = $this->profile($request);

        // We render a single view (profile.blade.php) for all steps; validate step slug but ignore for view selection
        if (! $this->isValidStep($step)) {
            return $this->redirectToCurrentStep($profile);
        }

        if ($profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                // If onboarding is completed but not approved, check if it was just completed
                if (session('onboarding_just_completed')) {
                    return redirect()->route('talent.pending');
                }
                // Otherwise, redirect to pending-status for subsequent visits
                return redirect()->route('talent.pending_status');
            }

            return redirect()->route('talent.dashboard');
        }

        // Use the step from URL if valid, otherwise fallback to profile progress
        $currentStep = $this->isValidStep($step) ? $step : $this->currentStep($profile);

        $viewData = [
            'profile'      => $profile,
            'currentStep'  => $currentStep,
            'initialStep'  => $this->stepIndex($currentStep) + 1,
            'progress'     => $this->progress($currentStep),
            'nextStep'     => $this->nextStep($currentStep),
            'previousStep' => $this->previousStep($currentStep),
        ];
        $profile->loadMissing('labels');
        $viewData['labels'] = Label::orderBy('name')->get();

        // Always use the unified onboarding view
        return view("talent.onboarding.profile", array_merge($viewData, [
            'countries' => $this->getCountries()
        ]));
    }

    private function getCountries()
    {
        return [
            'af' => 'Afghanistan', 'al' => 'Albania', 'dz' => 'Algeria', 'as' => 'American Samoa', 'ad' => 'Andorra', 'ao' => 'Angola', 'ai' => 'Anguilla', 'aq' => 'Antarctica', 'ag' => 'Antigua and Barbuda', 'ar' => 'Argentina', 'am' => 'Armenia', 'aw' => 'Aruba', 'au' => 'Australia', 'at' => 'Austria', 'az' => 'Azerbaijan',
            'bs' => 'Bahamas', 'bh' => 'Bahrain', 'bd' => 'Bangladesh', 'bb' => 'Barbados', 'by' => 'Belarus', 'be' => 'Belgium', 'bz' => 'Belize', 'bj' => 'Benin', 'bm' => 'Bermuda', 'bt' => 'Bhutan', 'bo' => 'Bolivia', 'ba' => 'Bosnia and Herzegovina', 'bw' => 'Botswana', 'br' => 'Brazil', 'io' => 'British Indian Ocean Territory', 'bn' => 'Brunei', 'bg' => 'Bulgaria', 'bf' => 'Burkina Faso', 'bi' => 'Burundi',
            'cv' => 'Cabo Verde', 'kh' => 'Cambodia', 'cm' => 'Cameroon', 'ca' => 'Canada', 'ky' => 'Cayman Islands', 'cf' => 'Central African Republic', 'td' => 'Chad', 'cl' => 'Chile', 'cn' => 'China', 'cx' => 'Christmas Island', 'cc' => 'Cocos Islands', 'co' => 'Colombia', 'km' => 'Comoros', 'cg' => 'Congo', 'cd' => 'Congo (DRC)', 'ck' => 'Cook Islands', 'cr' => 'Costa Rica', 'ci' => 'Côte d\'Ivoire', 'hr' => 'Croatia', 'cu' => 'Cuba', 'cw' => 'Curaçao', 'cy' => 'Cyprus', 'cz' => 'Czech Republic',
            'dk' => 'Denmark', 'dj' => 'Djibouti', 'dm' => 'Dominica', 'do' => 'Dominican Republic',
            'ec' => 'Ecuador', 'eg' => 'Egypt', 'sv' => 'El Salvador', 'gq' => 'Equatorial Guinea', 'er' => 'Eritrea', 'ee' => 'Estonia', 'sz' => 'Eswatini', 'et' => 'Ethiopia',
            'fk' => 'Falkland Islands', 'fo' => 'Faroe Islands', 'fj' => 'Fiji', 'fi' => 'Finland', 'fr' => 'France', 'gf' => 'French Guiana', 'pf' => 'French Polynesia', 'tf' => 'French Southern Territories',
            'ga' => 'Gabon', 'gm' => 'Gambia', 'ge' => 'Georgia', 'de' => 'Germany', 'gh' => 'Ghana', 'gi' => 'Gibraltar', 'gr' => 'Greece', 'gl' => 'Greenland', 'gd' => 'Grenada', 'gp' => 'Guadeloupe', 'gu' => 'Guam', 'gt' => 'Guatemala', 'gg' => 'Guernsey', 'gn' => 'Guinea', 'gw' => 'Guinea-Bissau', 'gy' => 'Guyana',
            'ht' => 'Haiti', 'hm' => 'Heard Island', 'hn' => 'Honduras', 'hk' => 'Hong Kong', 'hu' => 'Hungary',
            'is' => 'Iceland', 'in' => 'India', 'id' => 'Indonesia', 'ir' => 'Iran', 'iq' => 'Iraq', 'ie' => 'Ireland', 'im' => 'Isle of Man', 'il' => 'Israel', 'it' => 'Italy',
            'jm' => 'Jamaica', 'jp' => 'Japan', 'je' => 'Jersey', 'jo' => 'Jordan',
            'kz' => 'Kazakhstan', 'ke' => 'Kenya', 'ki' => 'Kiribati', 'kp' => 'Korea (North)', 'kr' => 'Korea (South)', 'kw' => 'Kuwait', 'kg' => 'Kyrgyzstan',
            'la' => 'Laos', 'lv' => 'Latvia', 'lb' => 'Lebanon', 'ls' => 'Lesotho', 'lr' => 'Liberia', 'ly' => 'Libya', 'li' => 'Liechtenstein', 'lt' => 'Lithuania', 'lu' => 'Luxembourg',
            'mo' => 'Macao', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mv' => 'Maldives', 'ml' => 'Mali', 'mt' => 'Malta', 'mh' => 'Marshall Islands', 'mq' => 'Martinique', 'mr' => 'Mauritania', 'mu' => 'Mauritius', 'yt' => 'Mayotte', 'mx' => 'Mexico', 'fm' => 'Micronesia', 'md' => 'Moldova', 'mc' => 'Monaco', 'mn' => 'Mongolia', 'me' => 'Montenegro', 'ms' => 'Montserrat', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'mm' => 'Myanmar',
            'na' => 'Namibia', 'nr' => 'Nauru', 'np' => 'Nepal', 'nl' => 'Netherlands', 'nc' => 'New Caledonia', 'nz' => 'New Zealand', 'ni' => 'Nicaragua', 'ne' => 'Niger', 'ng' => 'Nigeria', 'nu' => 'Niue', 'nf' => 'Norfolk Island', 'mk' => 'North Macedonia', 'mp' => 'Northern Mariana Islands', 'no' => 'Norway',
            'om' => 'Oman',
            'pk' => 'Pakistan', 'pw' => 'Palau', 'ps' => 'Palestine', 'pa' => 'Panama', 'pg' => 'Papua New Guinea', 'py' => 'Paraguay', 'pe' => 'Peru', 'ph' => 'Philippines', 'pn' => 'Pitcairn', 'pl' => 'Poland', 'pt' => 'Portugal', 'pr' => 'Puerto Rico',
            'qa' => 'Qatar',
            're' => 'Réunion', 'ro' => 'Romania', 'ru' => 'Russia', 'rw' => 'Rwanda',
            'bl' => 'Saint Barthélemy', 'sh' => 'Saint Helena', 'kn' => 'Saint Kitts and Nevis', 'lc' => 'Saint Lucia', 'mf' => 'Saint Martin', 'pm' => 'Saint Pierre and Miquelon', 'vc' => 'Saint Vincent and the Grenadines', 'ws' => 'Samoa', 'sm' => 'San Marino', 'st' => 'São Tomé and Príncipe', 'sa' => 'Saudi Arabia', 'sn' => 'Senegal', 'rs' => 'Serbia', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sg' => 'Singapore', 'sx' => 'Sint Maarten', 'sk' => 'Slovakia', 'si' => 'Slovenia', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa', 'gs' => 'South Georgia', 'ss' => 'South Sudan', 'es' => 'Spain', 'lk' => 'Sri Lanka', 'sd' => 'Sudan', 'sr' => 'Suriname', 'sj' => 'Svalbard and Jan Mayen', 'se' => 'Sweden', 'ch' => 'Switzerland', 'sy' => 'Syria',
            'tw' => 'Taiwan', 'tj' => 'Tajikistan', 'tz' => 'Tanzania', 'th' => 'Thailand', 'tl' => 'Timor-Leste', 'tg' => 'Togo', 'tk' => 'Tokelau', 'to' => 'Tonga', 'tt' => 'Trinidad and Tobago', 'tn' => 'Tunisia', 'tr' => 'Turkey', 'tm' => 'Turkmenistan', 'tc' => 'Turks and Caicos Islands', 'tv' => 'Tuvalu',
            'ug' => 'Uganda', 'ua' => 'Ukraine', 'ae' => 'United Arab Emirates', 'gb' => 'United Kingdom', 'um' => 'United States Minor Outlying Islands', 'us' => 'United States', 'uy' => 'Uruguay', 'uz' => 'Uzbekistan',
            'vu' => 'Vanuatu', 've' => 'Venezuela', 'vn' => 'Vietnam', 'vg' => 'Virgin Islands (British)', 'vi' => 'Virgin Islands (U.S.)',
            'wf' => 'Wallis and Futuna', 'eh' => 'Western Sahara',
            'ye' => 'Yemen',
            'zm' => 'Zambia', 'zw' => 'Zimbabwe'
        ];
    }



    public function store(Request $request, string $step, \App\Services\NotificationService $notificationService): RedirectResponse
    {
        Log::info("OnboardingController::store called for step: $step");
        if ($step === 'step-4') {
             // Check for file upload errors at the PHP level
             if (isset($_FILES['video'])) {
                 Log::info('PHP $_FILES["video"]:', $_FILES['video']);
             } else {
                 Log::info('PHP $_FILES["video"] is not set.');
             }

             // Check if request has the file via Laravel
             if ($request->hasFile('video')) {
                 $f = $request->file('video');
                 Log::info('Laravel sees video file:', [
                     'isValid' => $f->isValid(),
                     'error' => $f->getError(),
                     'size' => $f->getSize(),
                     'mime' => $f->getMimeType(),
                     'original' => $f->getClientOriginalName(),
                 ]);
             } else {
                 Log::info('Laravel $request->hasFile("video") returned false.');
             }
        }

        $profile = $this->profile($request);

        abort_unless($this->isValidStep($step), 404);

        if ($profile->hasCompletedOnboarding()) {
            return redirect()->route('talent.dashboard');
        }

        switch ($step) {
            case 'step-1':
                $eighteenYearsAgo = now()->subYears(18)->format('Y-m-d');
                $data = $request->validate([
                    'first_name'        => ['required', 'string', 'max:120'],
                    'last_name'         => ['required', 'string', 'max:120'],
                    'date_of_birth'     => ['required', 'date', "before_or_equal:$eighteenYearsAgo"],
                    'nationality'       => ['nullable', 'string', 'max:120'],
                    'country_code'      => ['required', 'string', 'max:10'],
                    'mobile_number'     => ['required', 'string', 'max:30'],
                    'whatsapp_number'   => ['nullable', 'required_if:whatsapp_choice,alt', 'string', 'max:30'],
                    'whatsapp_choice'   => ['required', 'in:same,alt'],
                ], [
                    'date_of_birth.before_or_equal' => 'You must be at least 18 years old to join.',
                ]);

                $fullName = trim($data['first_name'] . ' ' . $data['last_name']);
                $request->user('talent')->update(['name' => $fullName]);

                $whatsappNumber = ($data['whatsapp_choice'] === 'same')
                    ? $data['mobile_number']
                    : ($data['whatsapp_number'] ?? $data['mobile_number']);

                $profile->update([
                    'first_name'        => $data['first_name'],
                    'last_name'         => $data['last_name'],
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

                $notificationService->send($request->user('talent'), 'talent_profile_edit', [
                    'step' => 'Basic Info',
                ]);

                return redirect()->route('talent.onboarding.show', 'step-2');

            case 'step-2':
                $data = $request->validate([
                    'height'            => ['nullable', 'numeric', 'between:50,300'],
                    'weight'            => ['nullable', 'numeric', 'between:50,200'],
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

                return redirect()->route('talent.onboarding.show', 'step-3');

            case 'step-3':
                $data = $request->validate([
                    't_shirt_size'      => ['required', 'string', 'max:20'],
                    'dress_size'        => ['required', 'string', 'max:20'],
                    'shoe_size'         => ['required', 'numeric', 'between:0,100'],
                ]);

                $profile->update([
                    't_shirt_size'      => Arr::get($data, 't_shirt_size'),
                    'dress_size'        => Arr::get($data, 'dress_size'),
                    'shoe_size'         => Arr::get($data, 'shoe_size'),
                    'onboarding_step'   => 'step-4',
                    'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 3),
                ]);

                return redirect()->route('talent.onboarding.show', 'step-4');

            case 'step-4':
                // Only require document if it hasn't been uploaded yet
                $requireDoc = empty($profile->id_document_front);
                
                // Check if id_document_key is provided (S3 upload path)
                $hasIdKey = $request->filled('id_document_key');
                
                // If id_document_key is provided, id_document_front is not required
                // Otherwise, require it only if profile doesn't have one yet
                $idDocRequired = $requireDoc && !$hasIdKey;

                $data = $request->validate([
                    'id_document_front' => [$idDocRequired ? 'required' : 'nullable', 'file', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg,heic,heif'],
                    'id_document_key' => ['nullable', 'string'],
                ]);

                $updateData = [
                    'onboarding_step'   => 'step-5',
                    'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 4),
                ];

                // Preferred path: ID document is uploaded directly to S3 via AJAX (presigned PUT),
                // then Step 4 submits only `id_document_key`.
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
                    } else {
                        Log::warning('Invalid id_document_key prefix for Step 4', ['profile_id' => $profile->id, 'key' => $idKey]);
                        // If id_document_key is invalid and no existing document, require id_document_front
                        if ($requireDoc && !$request->hasFile('id_document_front')) {
                            return back()->withErrors(['id_document_key' => 'Invalid document key. Please upload the document again.'])->withInput();
                        }
                    }
                } elseif ($request->hasFile('id_document_front')) {
                    $s3Disk = config('filesystems.cloud', 's3');
                    $updateData['id_document_front'] = $this->storeTalentFile(
                        $profile,
                        $request->file('id_document_front'),
                        'id/front',
                        $s3Disk,
                        true // Compress if > 10MB
                    );
                }

                $profile->update($updateData);

                return redirect()->route('talent.onboarding.show', 'step-5');

            case 'step-5':
                set_time_limit(600); // 10 minutes
                ini_set('memory_limit', '1024M'); // 1GB

                Log::info('Starting validation for Step 5 (Photos & Video)...');
                try {
                    $data = $request->validate([
                        'video'             => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm', 'max:512000'],
                        'additional_photos' => ['nullable', 'array'],
                        'additional_photos.*' => ['file', 'mimes:jpeg,jpg,png,gif,webp,bmp,svg,heic,heif'],
                        'additional_photo_keys' => ['nullable', 'array'],
                        'additional_photo_keys.*' => ['string'],
                    ]);
                    Log::info('Validation passed for Step 5.');
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Validation failed for Step 5:', $e->errors());
                    throw $e;
                }

                $muxVideoAssetId = $profile->mux_video_asset_id;
                if ($request->hasFile('video')) {
                    $videoFile = $request->file('video');
                    Log::info('Video upload started.', [
                        'filename' => $videoFile->getClientOriginalName(),
                        'size' => $videoFile->getSize(),
                        'mime' => $videoFile->getMimeType(),
                        'error' => $videoFile->getError(),
                        'is_valid' => $videoFile->isValid(),
                        'real_path' => $videoFile->getRealPath(),
                    ]);

                    if (!$videoFile->isValid()) {
                         Log::error('Video file is invalid.', ['error' => $videoFile->getErrorMessage()]);
                         return back()->withErrors(['video' => 'File error: ' . $videoFile->getErrorMessage()])->withInput();
                    }

                    try {
                        Log::info('Initializing MuxService...');
                        $muxService = new MuxService();
                        Log::info('Calling MuxService::uploadVideo...');
                        $muxVideoAssetId = $muxService->uploadVideo($videoFile);
                        Log::info('Mux upload successful. Asset ID: ' . $muxVideoAssetId);
                    } catch (\Exception $e) {
                         Log::error('Failed to upload video to MUX: ' . $e->getMessage());
                         Log::error($e->getTraceAsString());
                         return back()->withErrors(['video' => 'Upload failed: ' . $e->getMessage()])->withInput();
                    }
                } else {
                    Log::info('No video file present in request.');
                }

                // Preferred path: photos are uploaded directly to S3 via AJAX (presigned PUT),
                // then the form submits only the uploaded object keys in `additional_photo_keys`.
                $uploadedKeys = $request->input('additional_photo_keys', []);
                if (is_array($uploadedKeys) && count($uploadedKeys) > 0) {
                    Log::info('Processing additional_photo_keys in Step 5...', [
                        'photo_count' => count($uploadedKeys),
                        'profile_id' => $profile->id,
                    ]);

                    // Delete existing profile photos
                    $deletedCount = $profile->media()->where('type', 'profile')->delete();
                    Log::info('Deleted existing profile photos', ['count' => $deletedCount]);

                    $s3Disk = config('filesystems.cloud', 's3');
                    $storageDisk = Storage::disk($s3Disk);
                    $expectedPrefix = "talent/{$profile->id}/photos/profile/";

                    foreach ($uploadedKeys as $index => $key) {
                        try {
                            if (!is_string($key) || $key === '' || !str_starts_with($key, $expectedPrefix)) {
                                Log::warning("Skipping invalid photo key #{$index}", ['key' => $key]);
                                continue;
                            }

                            // Store URL if possible, otherwise store the key/path.
                            $filePathToStore = $key;
                            try {
                                $filePathToStore = $storageDisk->url($key);
                            } catch (\Exception $e) {
                                // Keep key as fallback (e.g., private bucket without URL)
                            }

                            $media = $profile->media()->create([
                                'file_path' => $filePathToStore,
                                'type' => 'profile',
                            ]);

                            Log::info("Media record created from key", [
                                'media_id' => $media->id,
                                'key' => $key,
                                'stored_file_path' => $filePathToStore,
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Failed to save photo key #{$index}", [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                                'key' => $key,
                            ]);
                        }
                    }
                } elseif ($request->hasFile('additional_photos')) {
                    Log::info('Processing additional_photos in Step 5...', [
                        'photo_count' => count($request->file('additional_photos')),
                        'profile_id' => $profile->id,
                    ]);

                    // Delete existing profile photos
                    $deletedCount = $profile->media()->where('type', 'profile')->delete();
                    Log::info('Deleted existing profile photos', ['count' => $deletedCount]);

                    $s3Disk = config('filesystems.cloud', 's3');
                    Log::info('Using S3 disk for uploads', ['disk' => $s3Disk]);

                    foreach ($request->file('additional_photos') as $index => $photo) {
                        try {
                            Log::info("Processing photo #{$index}", [
                                'original_name' => $photo->getClientOriginalName(),
                                'size' => $photo->getSize(),
                                'mime' => $photo->getClientMimeType(),
                            ]);

                            $path = $this->storeTalentFile($profile, $photo, 'photos/profile', $s3Disk, true);
                            Log::info("Photo uploaded to S3", ['path' => $path]);

                            $media = $profile->media()->create([
                                'file_path' => $path,
                                'type' => 'profile',
                            ]);

                            Log::info("Media record created in database", [
                                'media_id' => $media->id,
                                'file_path' => $media->file_path,
                                'type' => $media->type,
                            ]);
                        } catch (\Exception $e) {
                            Log::error("Failed to upload photo #{$index}", [
                                'error' => $e->getMessage(),
                                'trace' => $e->getTraceAsString(),
                            ]);
                            // Continue with other photos even if one fails
                        }
                    }

                    // Verify photos were saved
                    $savedPhotos = $profile->media()->where('type', 'profile')->get();
                    Log::info('Profile photos saved successfully', [
                        'count' => $savedPhotos->count(),
                        'photos' => $savedPhotos->pluck('file_path')->toArray(),
                    ]);
                } else {
                    Log::info('No additional photos in request for Step 5');
                }

                $profile->update([
                    'mux_video_asset_id' => $muxVideoAssetId,
                    'onboarding_step'   => 'step-5',
                    'onboarding_steps_completed' => 5,
                    'onboarding_completed_at' => now(),
                    'verification_status'     => 'pending',
                ]);

                $notificationService->send($request->user('talent'), 'talent_profile_submission', [
                    'name' => $profile->display_name,
                ]);

                $admins = User::whereHas('roles', function($q) {
                    $q->whereIn('title', ['admin', 'superadmin', 'creative']);
                })->get();

                // Email notifications disabled as per user request
                // Notification::send($admins, new TalentSignupCompleted($profile));

                session()->flash('onboarding_just_completed', true);
                return redirect()->route('talent.pending')->with('message', trans('global.onboarding_submitted'));

            default:
                abort(404);
        }
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder, ?string $disk = null, bool $compress = false): string
    {
        $disk = $disk ?: config('filesystems.default', 'public');

        // Automatic compression for images > 10MB
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
                    imagejpeg($image, $tempPath, 75); // 75% quality - convert all to JPEG for compression
                    imagedestroy($image);

                    // Create a new File instance from the temporary compressed file
                    $file = new \Illuminate\Http\File($tempPath);
                }
             } catch (\Exception $e) {
                Log::warning("Backend image compression failed: " . $e->getMessage());
                // Fallback to original file if compression fails (e.g., for HEIC, HEIF, BMP, SVG)
             }
        }

        $path = $file->store("talent/{$profile->id}/{$folder}", $disk);

        // If storing to cloud disk, return full URL (e.g., CloudFront/S3) for DB storage
        $cloudDisk = config('filesystems.cloud', 's3');
        if ($disk === $cloudDisk) {
            try {
                return Storage::disk($disk)->url($path);
            } catch (\Exception $e) {
                // Fallback to stored path if URL generation fails
                return $path;
            }
        }

        // Return relative path for non-cloud disks
        return $path;
    }

    /**
     * Step 4: Generate a presigned PUT URL for uploading the ID document image directly to S3.
     */
    public function presignIdDocument(Request $request): JsonResponse
    {
        $profile = $this->profile($request);

        $data = $request->validate([
            'file_name' => ['required', 'string', 'max:255'],
            'file_type' => ['required', 'string', 'max:100'],
        ]);

        $fileName = (string) $data['file_name'];
        $fileType = (string) $data['file_type'];

        // Allowlist images. Client compression may convert to JPEG.
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
        $key = "talent/{$profile->id}/id/front/{$uuid}.{$ext}";

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

    /**
     * Step 5: Generate a presigned PUT URL for uploading one additional profile photo directly to S3.
     * Each photo should be uploaded with an individual AJAX request.
     */
    public function presignAdditionalPhoto(Request $request): JsonResponse
    {
        $profile = $this->profile($request);

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

    private function currentStep(TalentProfile $profile): string
    {
        $step = $profile->onboarding_step ?? self::STEPS[0];
        // Map legacy value
        if ($step === 'profile') {
            $step = 'step-1';
        }
        return in_array($step, self::STEPS, true) ? $step : self::STEPS[0];
    }

    private function nextStep(string $step): string
    {
        $index = $this->stepIndex($step);
        return isset(self::STEPS[$index + 1]) ? self::STEPS[$index + 1] : 'review';
    }

    private function previousStep(string $step): ?string
    {
        $index = $this->stepIndex($step);
        return $index > 0 ? self::STEPS[$index - 1] : null;
    }

    private function progress(string $step): array
    {
        $total = count(self::STEPS);
        $currentIndex = $this->stepIndex($step) + 1;

        return [
            'current' => $currentIndex,
            'total'   => $total,
            'percent' => round(($currentIndex / $total) * 100),
        ];
    }

    private function stepIndex(string $step): int
    {
        return array_search($step, self::STEPS, true) ?? 0;
    }

    private function isValidStep(string $step): bool
    {
        return in_array($step, self::STEPS, true);
    }

    private function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
    }

    private function profile(Request $request): TalentProfile
    {
        /** @var \App\Models\User $user */
        $user = $request->user('talent');

        // Check for existing active (non-deleted) profile
        $profile = $user->talentProfile;

        // If no active profile exists, check if there's a deleted/rejected one
        if (! $profile) {
            $deletedProfile = $user->talentProfile()->withTrashed()->latest()->first();
            
            // If there's a deleted profile, we can create a new one
            // The old profile will remain in database but won't interfere
            return $user->talentProfile()->create([
                'legal_name'       => $user->name ?? '',
                'display_name'     => $user->name ?? '',
                'daily_rate'       => 0,
                'rate'             => 0,
                'verification_status' => 'pending',
                'whatsapp_number'  => null,
                'onboarding_step'  => 'step-1',
                'onboarding_steps_completed' => 0,
            ]);
        }

        // If profile exists but is rejected, soft delete it and create a new one
        if ($profile->verification_status === 'rejected') {
            // Soft delete the rejected profile to allow creating a new one
            $profile->delete();
            
            // Create a new profile for re-application
            return $user->talentProfile()->create([
                'legal_name'       => $user->name ?? '',
                'display_name'     => $user->name ?? '',
                'daily_rate'       => 0,
                'rate'             => 0,
                'verification_status' => 'pending',
                'whatsapp_number'  => null,
                'onboarding_step'  => 'step-1',
                'onboarding_steps_completed' => 0,
            ]);
        }

        return $profile;
    }

    public function pending(Request $request)
    {
        $profile = $this->profile($request);

        if (! $profile->hasCompletedOnboarding()) {
            return $this->redirectToCurrentStep($profile);
        }

        if ($profile->verification_status === 'approved') {
            return redirect()->route('talent.dashboard');
        }

        if (!session('onboarding_just_completed')) {
            return redirect()->route('talent.pending_status');
        }

        return view('talent.onboarding.pending', compact('profile'));
    }

    public function pendingStatus(Request $request)
    {
        $profile = $this->profile($request);

        if (!$profile->hasCompletedOnboarding()) {
            return $this->redirectToCurrentStep($profile);
        }

        if ($profile->verification_status === 'approved') {
            return redirect()->route('talent.dashboard');
        }

        // If rejected, redirect to rejected page
        if ($profile->verification_status === 'rejected') {
            return redirect()->route('talent.rejected');
        }

        return view('talent.onboarding.pending-status', compact('profile'));
    }

    public function rejected(Request $request)
    {
        $user = $request->user('talent');
        
        // Check for active rejected profile first
        $profile = $user->talentProfile;
        
        // If no active profile or it's not rejected, check deleted profiles
        if (!$profile || $profile->verification_status !== 'rejected') {
            $profile = \App\Models\TalentProfile::where('user_id', $user->id)
                ->where('verification_status', 'rejected')
                ->withTrashed()
                ->latest()
                ->first();
        }

        // If no rejected profile exists, redirect to onboarding
        if (!$profile || $profile->verification_status !== 'rejected') {
            return redirect()->route('talent.onboarding.intro');
        }

        return view('talent.onboarding.rejected', compact('profile'));
    }

    private function redirectToCurrentStep(TalentProfile $profile): RedirectResponse
    {
        $step = $this->currentStep($profile);
        return redirect()->route('talent.onboarding.show', $step);
    }
}
