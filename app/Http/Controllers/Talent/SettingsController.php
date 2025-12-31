<?php

namespace App\Http\Controllers\Talent;

use App\Http\Controllers\Controller;
use App\Models\TalentProfile;
use App\Models\TalentSetting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index(Request $request)
    {
        $talent = $request->user('talent');
        $talentProfile = TalentProfile::where('user_id', $talent->id)->first();
        
        if (!$talentProfile) {
            return redirect()->route('talent.dashboard')
                ->with('error', 'Talent profile not found. Please complete your profile first.');
        }

        // Get or create settings for this talent profile
        $settings = TalentSetting::getOrCreateForProfile($talentProfile->id);

        return view('talent.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $talent = $request->user('talent');
        $talentProfile = TalentProfile::where('user_id', $talent->id)->first();
        
        if (!$talentProfile) {
            return redirect()->route('talent.dashboard')
                ->with('error', 'Talent profile not found. Please complete your profile first.');
        }

        $validated = $request->validate([
            'email_notifications' => ['sometimes', 'boolean'],
            'push_notifications' => ['sometimes', 'boolean'],
            'shows_updates' => ['sometimes', 'boolean'],
            'show_reminders' => ['sometimes', 'boolean'],
            'payment_alerts' => ['sometimes', 'boolean'],
            'system_updates' => ['sometimes', 'boolean'],
            'language' => ['nullable', 'string', 'max:120'],
            'timezone' => ['nullable', 'string', 'max:120'],
            'date_format' => ['nullable', 'string', 'max:50'],
            'time_format' => ['nullable', 'string', 'max:50'],
            'appearance' => ['nullable', 'string', 'max:50'],
        ]);

        // Ensure missing checkboxes are treated as false
        foreach ([
            'email_notifications',
            'push_notifications',
            'shows_updates',
            'show_reminders',
            'payment_alerts',
            'system_updates',
        ] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        // Get or create settings and update
        $settings = TalentSetting::getOrCreateForProfile($talentProfile->id);
        $settings->fill($validated);
        $settings->save();

        return back()->with('message', 'Settings updated successfully.');
    }
}

