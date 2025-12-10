<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Label;
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
            'startRoute'  => route('talent.onboarding.show', 'profile'),
        ]);
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

        $viewData = [
            'profile'      => $profile,
            'currentStep'  => $step,
            'progress'     => $this->progress($step),
            'nextStep'     => $this->nextStep($step),
            'previousStep' => $this->previousStep($step),
        ];

        if ($step === 'profile') {
            $profile->loadMissing('labels');
            $viewData['labels'] = Label::orderBy('name')->get();
        }

        return view("talent.onboarding.{$step}", $viewData);
    }

    public function store(Request $request, string $step): RedirectResponse
    {
        $profile = $this->profile($request);

        abort_unless($this->isValidStep($step), 404);

        if ($profile->hasCompletedOnboarding()) {
            return redirect()->route('talent.dashboard');
        }

        $data = $request->validate([
            'first_name'        => ['required', 'string', 'max:120'],
            'last_name'         => ['required', 'string', 'max:120'],
            'date_of_birth'     => ['required', 'date'],
            'nationality'       => ['nullable', 'string', 'max:120'],
            'country_code'      => ['required', 'string', 'max:10'],
            'mobile_number'     => ['required', 'string', 'max:30'],
            'whatsapp_number'   => ['nullable', 'string', 'max:30'],
            'gender'            => ['required', 'string', 'max:20'],
            'hijab_preference'  => ['nullable', 'string', 'max:50'],
            'height'            => ['nullable', 'numeric', 'between:0,300'],
            'weight'            => ['nullable', 'numeric', 'between:0,500'],
            'hair_color'        => ['nullable', 'string', 'max:120'],
            'eye_color'         => ['nullable', 'string', 'max:120'],
            'skin_tone'         => ['nullable', 'string', 'max:60'],
            'has_visible_tattoos' => ['required', 'in:0,1'],
            'has_piercings'       => ['required', 'in:0,1'],
            'chest'             => ['nullable', 'numeric', 'between:0,300'],
            'waist'             => ['nullable', 'numeric', 'between:0,300'],
            'hips'              => ['nullable', 'numeric', 'between:0,300'],
            'shoe_size'         => ['nullable', 'numeric', 'between:0,100'],
            'id_front'          => [$profile->id_front_path ? 'nullable' : 'required', 'image', 'max:4096'],
            'id_back'           => [$profile->id_back_path ? 'nullable' : 'required', 'image', 'max:4096'],
        ]);

        $user = $request->user('talent');
        $fullName = trim($data['first_name'] . ' ' . $data['last_name']);

        $user->update([
            'name' => $fullName,
        ]);

        $profile->update([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'legal_name'        => $fullName,
            'display_name'      => $fullName,
            'nationality'       => Arr::get($data, 'nationality'),
            'country_code'      => $data['country_code'],
            'mobile_number'     => $this->sanitizePhoneNumber($data['mobile_number']),
            'whatsapp_number'   => $this->sanitizePhoneNumber(Arr::get($data, 'whatsapp_number')),
            'date_of_birth'     => $data['date_of_birth'],
            'gender'            => $data['gender'],
            'hijab_preference'  => Arr::get($data, 'hijab_preference'),
            'height'            => Arr::get($data, 'height'),
            'weight'            => Arr::get($data, 'weight'),
            'hair_color'        => Arr::get($data, 'hair_color'),
            'eye_color'         => Arr::get($data, 'eye_color'),
            'skin_tone'         => Arr::get($data, 'skin_tone'),
            'has_visible_tattoos' => (bool) $data['has_visible_tattoos'],
            'has_piercings'       => (bool) $data['has_piercings'],
            'chest'             => Arr::get($data, 'chest'),
            'waist'             => Arr::get($data, 'waist'),
            'hips'              => Arr::get($data, 'hips'),
            'shoe_size'         => Arr::get($data, 'shoe_size'),
            'id_front_path'     => Arr::get($data, 'id_front') ? $this->storeTalentFile($profile, $data['id_front'], 'id/front') : $profile->id_front_path,
            'id_back_path'      => Arr::get($data, 'id_back') ? $this->storeTalentFile($profile, $data['id_back'], 'id/back') : $profile->id_back_path,
            'onboarding_step'   => 'pending-approval',
            'onboarding_completed_at' => now(),
            'verification_status'     => 'pending',
        ]);

        return redirect()->route('talent.pending')->with('message', trans('global.onboarding_submitted'));
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
