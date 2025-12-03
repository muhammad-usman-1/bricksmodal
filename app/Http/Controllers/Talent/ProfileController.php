<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\Label;
use App\Models\Language;
use App\Models\TalentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function show(Request $request): View
    {
        $user = $request->user('talent');
        $profile = $this->resolveProfile($user);

        return view('talent.profile.index', [
            'profile'          => $profile->load('languages', 'labels'),
            'languages'        => Language::orderBy('title')->get(),
            'availableLabels'  => Label::orderBy('name')->get(),
            'skinToneOptions'  => TalentProfile::SKIN_TONE_SELECT,
            'statusOptions'    => TalentProfile::VERIFICATION_STATUS_SELECT,
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
            'daily_rate'       => ['required', 'numeric', 'min:0'],
            'hourly_rate'      => ['nullable', 'numeric', 'min:0'],
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
            'languages'        => ['nullable', 'array'],
            'languages.*'      => ['integer', 'exists:languages,id'],
            'labels'           => ['required', 'array', 'min:1'],
            'labels.*'         => ['integer', 'exists:labels,id'],
            'id_front'         => ['nullable', 'image', 'max:4096'],
            'id_back'          => ['nullable', 'image', 'max:4096'],
            'headshot_center'  => ['nullable', 'image', 'max:6144'],
            'headshot_left'    => ['nullable', 'image', 'max:6144'],
            'headshot_right'   => ['nullable', 'image', 'max:6144'],
            'full_body_front'  => ['nullable', 'image', 'max:6144'],
            'full_body_right'  => ['nullable', 'image', 'max:6144'],
            'full_body_back'   => ['nullable', 'image', 'max:6144'],
        ]);

        $user->update([
            'name'  => $data['legal_name'],
            'email' => $data['email'],
        ]);

        $profile->update([
            'legal_name'        => $data['legal_name'],
            'display_name'      => $data['display_name'] ?: $data['legal_name'],
            'daily_rate'        => Arr::get($data, 'daily_rate'),
            'hourly_rate'       => Arr::get($data, 'hourly_rate'),
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

        if (array_key_exists('languages', $data)) {
            $profile->languages()->sync($data['languages'] ?? []);
        }

        if (array_key_exists('labels', $data)) {
            $profile->labels()->sync($data['labels'] ?? []);
        }

        $uploadMap = [
            'id_front'        => ['column' => 'id_front_path', 'folder' => 'id/front'],
            'id_back'         => ['column' => 'id_back_path', 'folder' => 'id/back'],
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

        return redirect()
            ->route('talent.profile.show')
            ->with('message', __('Profile updated successfully.'));
    }

    private function storeTalentFile(TalentProfile $profile, $file, string $folder): string
    {
        $path = $file->store("talent/{$profile->id}/{$folder}", 'public');

        return Storage::url($path);
    }

    private function sanitizePhoneNumber(?string $number): ?string
    {
        if (! $number) {
            return null;
        }

        $digits = preg_replace('/[^0-9]/', '', $number);

        return $digits ?: null;
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
            'hourly_rate'       => 0,
            'onboarding_step'   => 'profile',
        ]);
    }
}

