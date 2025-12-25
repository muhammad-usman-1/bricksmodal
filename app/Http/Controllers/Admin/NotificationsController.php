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

        $notificationType = $notification->data['type'] ?? null;

        if ($notificationType === 'talent_profile' && isset($notification->data['talent_profile_id'])) {
            return redirect()->route('admin.talent-profiles.show', $notification->data['talent_profile_id']);
        } elseif ($notificationType === 'casting_application' && isset($notification->data['application_id'])) {
            return redirect()->route('admin.casting-applications.show', $notification->data['application_id']);
        } elseif ($notificationType === 'payment_requested' && isset($notification->data['casting_application_id'])) {
            return redirect()->route('admin.payment-requests.index');
        } else {
            return redirect()->route('admin.notifications.index')->with('message', 'Notification details not available.');
        }
    }

    public function markAllRead()
    {
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back()->with('message', 'All notifications marked as read');
    }
}
