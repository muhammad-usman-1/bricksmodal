<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminSetting;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SettingsController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $settings = AdminSetting::singleton();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        abort_if(Gate::denies('user_management_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

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
        ]);

        $settings = AdminSetting::singleton();

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

        $settings->fill($validated)->save();

        return back()->with('message', __('Settings updated successfully.'));
    }
}
