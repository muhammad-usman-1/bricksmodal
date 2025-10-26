@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        Notifications
        @if(auth()->user()->unreadNotifications->count() > 0)
            <a href="{{ route('admin.notifications.mark-all-read') }}" class="btn btn-sm btn-info float-right">
                Mark all as read
            </a>
        @endif
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notifications as $notification)
                        <tr class="{{ $notification->read_at ? '' : 'table-info' }}">
                            <td>
                                @if($notification->data['type'] === 'talent_profile')
                                    <i class="fas fa-user text-info"></i> Talent Profile
                                @else
                                    <i class="fas fa-video text-warning"></i> Casting Application
                                @endif
                            </td>
                            <td>{{ $notification->data['title'] }}</td>
                            <td>{{ $notification->data['message'] }}</td>
                            <td>{{ $notification->created_at->diffForHumans() }}</td>
                            <td>
                                @if($notification->read_at)
                                    <span class="badge badge-success">Read</span>
                                @else
                                    <span class="badge badge-warning">Unread</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn btn-sm btn-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">No notifications found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $notifications->links() }}
    </div>
</div>
@endsection