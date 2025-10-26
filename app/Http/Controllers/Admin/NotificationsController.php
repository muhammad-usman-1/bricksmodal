<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);
        return view('admin.notifications.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Auth::user()->notifications()->findOrFail($id);
        $notification->markAsRead();

        if ($notification->data['type'] === 'talent_profile') {
            return redirect()->route('admin.talent-profiles.show', $notification->data['talent_profile_id']);
        } else {
            return redirect()->route('admin.casting-applications.show', $notification->data['application_id']);
        }
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('message', 'All notifications marked as read');
    }
}