<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificationTemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notificationTemplates = [
            'talent'   => \App\Models\NotificationTemplate::where('role', 'talent')->get(),
            'admin'    => \App\Models\NotificationTemplate::where('role', 'admin')->get(),
            'creative' => \App\Models\NotificationTemplate::where('role', 'creative')->get(),
        ];

        return view('admin.notification-templates.index', compact('notificationTemplates'));
    }

    public function create()
    {
        return view('admin.notification-templates.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|unique:notification_templates,key',
            'name' => 'required',
            'role' => 'required|in:talent,admin,creative',
            'language_preference' => 'required|in:en,ar,both',
        ]);

        \App\Models\NotificationTemplate::create($request->all());

        return redirect()->route('admin.notification-templates.index')->with('message', 'Notification template created successfully.');
    }

    public function edit(\App\Models\NotificationTemplate $notificationTemplate)
    {
        return view('admin.notification-templates.edit', compact('notificationTemplate'));
    }

    public function update(Request $request, \App\Models\NotificationTemplate $notificationTemplate)
    {
        $request->validate([
            'key' => 'required|unique:notification_templates,key,' . $notificationTemplate->id,
            'name' => 'required',
            'role' => 'required|in:talent,admin,creative',
            'language_preference' => 'required|in:en,ar,both',
        ]);

        $notificationTemplate->update($request->all());

        return redirect()->route('admin.notification-templates.index')->with('message', 'Notification template updated successfully.');
    }

    public function destroy(\App\Models\NotificationTemplate $notificationTemplate)
    {
        $notificationTemplate->delete();
        return redirect()->route('admin.notification-templates.index')->with('message', 'Notification template deleted successfully.');
    }

    public function toggleActive(\App\Models\NotificationTemplate $notificationTemplate)
    {
        $notificationTemplate->update([
            'is_active' => !$notificationTemplate->is_active
        ]);

        return response()->json([
            'success'   => true,
            'is_active' => $notificationTemplate->is_active
        ]);
    }

}
