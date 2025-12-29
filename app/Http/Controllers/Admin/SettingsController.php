<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('system_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = AdminSetting::singleton();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('system_settings_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $validated = $request->validate([
            'email_notifications' => ['sometimes', 'boolean'],
            'push_notifications' => ['sometimes', 'boolean'],
            'talent_updates' => ['sometimes', 'boolean'],
            'shoot_reminders' => ['sometimes', 'boolean'],
            'payment_alerts' => ['sometimes', 'boolean'],
            'system_updates' => ['sometimes', 'boolean'],
            'language' => ['nullable', 'string', 'max:120'],
            'timezone' => ['nullable', 'string', 'max:120'],
            'date_format' => ['nullable', 'string', 'max:50'],
            'time_format' => ['nullable', 'string', 'max:50'],
            'appearance' => ['nullable', 'string', 'max:50'],
            'background_image' => ['nullable', 'image', 'mimes:jpeg,jpg,png,gif,webp', 'max:5120'],
            'remove_background_image' => ['sometimes', 'boolean'],
        ]);

        $settings = AdminSetting::singleton();

        // Handle background image upload
        if ($request->has('remove_background_image') && $request->boolean('remove_background_image')) {
            // Remove background image
            if ($settings->background_image_path) {
                Storage::disk('public')->delete($settings->background_image_path);
                $validated['background_image_path'] = null;
            }
        } elseif ($request->hasFile('background_image')) {
            // Delete old background image if exists
            if ($settings->background_image_path) {
                Storage::disk('public')->delete($settings->background_image_path);
            }
            
            // Store new background image
            $path = $request->file('background_image')->store('backgrounds', 'public');
            $validated['background_image_path'] = $path;
        }

        // Ensure missing checkboxes are treated as false
        foreach ([
            'email_notifications',
            'push_notifications',
            'talent_updates',
            'shoot_reminders',
            'payment_alerts',
            'system_updates',
        ] as $flag) {
            $validated[$flag] = $request->boolean($flag);
        }

        // Remove file input from validated data before saving
        unset($validated['background_image'], $validated['remove_background_image']);

        $settings->fill($validated)->save();

        return back()->with('message', __('Settings updated successfully.'));
    }
}
