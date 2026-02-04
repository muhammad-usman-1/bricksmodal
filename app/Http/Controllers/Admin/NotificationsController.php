<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationsController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->get('filter', 'all');

        $query = $user->notifications()->latest();

        if ($filter === 'unread') {
            $query->whereNull('read_at');
        } elseif ($filter === 'read') {
            $query->whereNotNull('read_at');
        }

        $notifications = $query->paginate(15)->withQueryString();

        $stats = [
            'total'  => $user->notifications()->count(),
            'unread' => $user->unreadNotifications()->count(),
            'read'   => $user->notifications()->whereNotNull('read_at')->count(),
        ];

        $typeCounts = [
            'talent_profile'     => $user->notifications()->whereIn('data->type', [
                'talent_profile', 
                'admin_talent_profile_submission', 
                'admin_profile_status_update'
            ])->count(),
            'casting_application'=> $user->notifications()->whereIn('data->type', [
                'casting_application', 
                'admin_shoot_application', 
                'admin_application_status_update'
            ])->count(),
            'payment_requested'  => $user->notifications()->whereIn('data->type', [
                'payment_requested', 
                'payment_request', 
                'admin_payment_received'
            ])->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'filter', 'stats', 'typeCounts'));
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

    public function markAsRead(Request $request, $id)
    {
        $notification = Auth::user()->unreadNotifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return back();
    }

    public function markAllRead(Request $request)
    {
        Auth::user()->unreadNotifications->markAsRead();

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('message', 'All notifications marked as read');
    }
}
