<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    private const STEPS = [
        'profile',
        'id-documents',
        'headshot-center',
        'headshot-left',
        'headshot-right',
        'full-body-front',
        'full-body-right',
        'full-body-back',
        'review',
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

    public function show(Request $request, string $step): View|RedirectResponse
    {
        $profile = $this->profile($request);

        if (! $this->isValidStep($step)) {
            return $this->redirectToCurrentStep($profile);
        }

        if ($profile->hasCompletedOnboarding()) {
            if ($profile->verification_status !== 'approved') {
                return redirect()->route('talent.pending');
            }

            return redirect()->route('talent.dashboard');
        }

        $currentStep = $this->currentStep($profile);
        if ($this->stepIndex($step) > $this->stepIndex($currentStep)) {
            return $this->redirectToCurrentStep($profile);
        }

        return view("talent.onboarding.{$step}", [
            'profile'      => $profile,
            'currentStep'  => $step,
            'progress'     => $this->progress($step),
            'nextStep'     => $this->nextStep($step),
            'previousStep' => $this->previousStep($step),
        ]);
    }

    public function store(Request $request, string $step): RedirectResponse
    {
        $profile = $this->profile($request);

        abort_unless($this->isValidStep($step), 404);

        if ($profile->hasCompletedOnboarding()) {
            return redirect()->route('talent.dashboard');
        }

        switch ($step) {
            case 'profile':
                $data = $request->validate([
                    'full_name'       => ['required', 'string', 'max:150'],
                    'email'           => ['required', 'email', 'max:255'],
                    'height'          => ['nullable', 'numeric', 'between:0,300'],
                    'weight'          => ['nullable', 'numeric', 'between:0,500'],
                    'daily_rate'      => ['required', 'numeric', 'min:0'],
                    'hourly_rate'     => ['nullable', 'numeric', 'min:0'],
                    'chest'           => ['nullable', 'integer', 'between:0,300'],
                    'waist'           => ['nullable', 'integer', 'between:0,300'],
                    'hips'            => ['nullable', 'integer', 'between:0,300'],
                    'date_of_birth'   => ['required', 'date'],
                    'gender'          => ['required', 'string', 'max:20'],
                    'whatsapp_number' => ['required', 'regex:/^\+?[0-9\s\-()]{7,20}$/'],
                    'skin_tone'       => ['nullable', 'in:' . implode(',', array_keys(TalentProfile::SKIN_TONE_SELECT))],
                    'hair_color'      => ['nullable', 'string', 'max:120'],
                    'eye_color'       => ['nullable', 'string', 'max:120'],
                    'shoe_size'       => ['nullable', 'integer', 'between:0,100'],
                ]);

                $user = $request->user('talent');
                $user->update([
                    'name'  => $data['full_name'],
                    'email' => $data['email'],
                ]);

                $profile->update([
                    'legal_name'  => $data['full_name'],
                    'display_name'=> $data['full_name'],
                    'height'      => Arr::get($data, 'height'),
                    'weight'      => Arr::get($data, 'weight'),
                    'daily_rate'  => Arr::get($data, 'daily_rate'),
                    'hourly_rate' => Arr::get($data, 'hourly_rate'),
                    'chest'       => Arr::get($data, 'chest'),
                    'waist'       => Arr::get($data, 'waist'),
                    'hips'        => Arr::get($data, 'hips'),
                    'date_of_birth' => $data['date_of_birth'],
                    'gender'        => $data['gender'],
                    'skin_tone'     => Arr::get($data, 'skin_tone'),
                    'hair_color'    => Arr::get($data, 'hair_color'),
                    'eye_color'     => Arr::get($data, 'eye_color'),
                    'shoe_size'     => Arr::get($data, 'shoe_size'),
                    'whatsapp_number' => $this->sanitizePhoneNumber($data['whatsapp_number']),
                    'onboarding_step' => $this->nextStep($step),
                ]);
                break;

            case 'id-documents':
                $data = $request->validate([
                    'id_front' => ['required', 'image', 'max:4096'],
                    'id_back'  => ['required', 'image', 'max:4096'],
                ]);

                $profile->update([
                    'id_front_path' => $this->storeTalentFile($profile, $data['id_front'], 'id/front'),
                    'id_back_path'  => $this->storeTalentFile($profile, $data['id_back'], 'id/back'),
                    'onboarding_step' => $this->nextStep($step),
                ]);
                break;

            case 'headshot-center':
            case 'headshot-left':
            case 'headshot-right':
            case 'full-body-front':
            case 'full-body-right':
            case 'full-body-back':
                $data = $request->validate([
                    'photo' => ['required', 'image', 'max:6144'],
                ]);

                $column = match ($step) {
                    'headshot-center'   => 'headshot_center_path',
                    'headshot-left'     => 'headshot_left_path',
                    'headshot-right'    => 'headshot_right_path',
                    'full-body-front'   => 'full_body_front_path',
                    'full-body-right'   => 'full_body_right_path',
                    'full-body-back'    => 'full_body_back_path',
                };

                $profile->update([
                    $column => $this->storeTalentFile($profile, $data['photo'], $step),
                    'onboarding_step' => $this->nextStep($step),
                ]);
                break;

            case 'review':
                $request->validate([
                    'confirm' => ['accepted'],
                ]);

                $profile->update([
                    'onboarding_step'         => 'pending-approval',
                    'onboarding_completed_at' => now(),
                    'verification_status'     => 'pending',
                ]);

                return redirect()->route('talent.pending')->with('message', trans('global.onboarding_submitted'));
        }

        return $this->redirectToCurrentStep($profile);
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $path = $file->store("talent/{$profile->id}/{$folder}", 'public');
        return Storage::url($path);
    }

    private function currentStep(TalentProfile $profile): string
    {
        $step = $profile->onboarding_step ?? self::STEPS[0];
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
