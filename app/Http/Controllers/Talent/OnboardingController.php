<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\TalentProfile;
use App\Models\TalentMedia;
use App\Services\MuxService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    // Logical steps; view remains a single blade (profile)
    private const STEPS = [
        'step-1',
        'step-2',
        'step-3',
        'step-4',
    ];

    public function start(Request $request): RedirectResponse
    {
        $profile = $this->profile($request);

        if ($profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                return redirect()->route('talent.pending');
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
                return redirect()->route('talent.pending');
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
                return redirect()->route('talent.pending');
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



    public function store(Request $request, string $step): RedirectResponse
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
                $data = $request->validate([
                    'first_name'        => ['required', 'string', 'max:120'],
                    'last_name'         => ['required', 'string', 'max:120'],
                    'date_of_birth'     => ['required', 'date'],
                    'nationality'       => ['nullable', 'string', 'max:120'],
                    'country_code'      => ['required', 'string', 'max:10'],
                    'mobile_number'     => ['required', 'string', 'max:30'],
                    'whatsapp_number'   => ['nullable', 'required_if:whatsapp_choice,alt', 'string', 'max:30'],
                    'whatsapp_choice'   => ['required', 'in:same,alt'],
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

                return redirect()->route('talent.onboarding.show', 'step-2');

            case 'step-2':
                $data = $request->validate([
                    'height'            => ['nullable', 'numeric', 'between:0,300'],
                    'weight'            => ['nullable', 'numeric', 'between:0,500'],
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
                    'chest'             => ['required', 'numeric', 'between:0,300'],
                    'waist'             => ['required', 'numeric', 'between:0,300'],
                    'hips'              => ['required', 'numeric', 'between:0,300'],
                    'shoe_size'         => ['required', 'numeric', 'between:0,100'],
                ]);

                $profile->update([
                    'chest'             => Arr::get($data, 'chest'),
                    'waist'             => Arr::get($data, 'waist'),
                    'hips'              => Arr::get($data, 'hips'),
                    'shoe_size'         => Arr::get($data, 'shoe_size'),
                    'onboarding_step'   => 'step-4',
                    'onboarding_steps_completed' => max($profile->onboarding_steps_completed ?? 0, 3),
                ]);

                return redirect()->route('talent.onboarding.show', 'step-4');

            case 'step-4':
                set_time_limit(600); // 10 minutes
                ini_set('memory_limit', '1024M'); // 1GB

                Log::info('Starting validation for Step 4...');
                try {
                    $data = $request->validate([
                        'civil_id_number'   => ['required', 'string', 'max:50'],
                        'id_front'          => [$profile->id_front_path ? 'nullable' : 'required', 'image', 'max:4096'],
                        'id_back'           => [$profile->id_back_path ? 'nullable' : 'required', 'image', 'max:4096'],
                        'headshot'          => [$profile->headshot_center_path ? 'nullable' : 'required', 'image', 'max:4096'],
                        'fullbody'          => [$profile->full_body_front_path ? 'nullable' : 'required', 'image', 'max:4096'],
                        'video'             => ['nullable', 'file', 'mimes:mp4,mpeg,mov,avi,webm', 'max:512000'],
                        'additional_photos' => ['nullable', 'array'],
                        'additional_photos.*' => ['image', 'max:4096'],
                    ]);
                    Log::info('Validation passed.');
                } catch (\Illuminate\Validation\ValidationException $e) {
                    Log::error('Validation failed:', $e->errors());
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

                if ($request->hasFile('additional_photos')) {
                    foreach ($request->file('additional_photos') as $photo) {
                        $path = $this->storeTalentFile($profile, $photo, 'photos/additional');
                        $profile->media()->create([
                            'file_path' => $path,
                            'type' => 'photo',
                        ]);
                    }
                }

                $profile->update([
                    'civil_id_number'   => Arr::get($data, 'civil_id_number'),
                    'id_front_path'     => Arr::get($data, 'id_front') ? $this->storeTalentFile($profile, $data['id_front'], 'id/front') : $profile->id_front_path,
                    'id_back_path'      => Arr::get($data, 'id_back') ? $this->storeTalentFile($profile, $data['id_back'], 'id/back') : $profile->id_back_path,
                    'headshot_center_path' => Arr::get($data, 'headshot') ? $this->storeTalentFile($profile, $data['headshot'], 'photos/headshot') : $profile->headshot_center_path,
                    'full_body_front_path' => Arr::get($data, 'fullbody') ? $this->storeTalentFile($profile, $data['fullbody'], 'photos/fullbody') : $profile->full_body_front_path,
                    'mux_video_asset_id' => $muxVideoAssetId,
                    'onboarding_step'   => 'step-4',
                    'onboarding_steps_completed' => 4,
                    'onboarding_completed_at' => now(),
                    'verification_status'     => 'pending',
                ]);

                return redirect()->route('talent.pending')->with('message', trans('global.onboarding_submitted'));

            default:
                abort(404);
        }
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $disk = config('filesystems.default', 'public');
        // Store and return the relative path so we can build URLs consistently
        return $file->store("talent/{$profile->id}/{$folder}", $disk);
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

        if (! $user->talentProfile) {
            return $user->talentProfile()->create([
                'legal_name'       => $user->name ?? '',
                'display_name'     => $user->name ?? '',
                'daily_rate'       => 0,
                'hourly_rate'      => 0,
                'verification_status' => 'pending',
                'whatsapp_number'  => null,
                'onboarding_step'  => 'profile',
            ]);
        }

        return $user->talentProfile;
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

        return view('talent.onboarding.pending', compact('profile'));
    }

    private function redirectToCurrentStep(TalentProfile $profile): RedirectResponse
    {
        $step = $this->currentStep($profile);
        return redirect()->route('talent.onboarding.show', $step);
    }
}
