@extends('layouts.talent')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <h4 class="mb-0 font-weight-bold">Notifications</h4>
                @php
                    $unreadCount = auth('talent')->user()->unreadNotifications->count();
                @endphp
                @if($unreadCount > 0)
                    <button onclick="markAllNotificationsAsRead()" class="btn btn-dark btn-sm">
                        Mark all as read
                    </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item list-group-item-action d-flex flex-column p-4 {{ $notification->read_at ? '' : 'bg-light border-left-dark' }}" id="notification-page-{{ $notification->id }}" style="{{ $notification->read_at ? '' : 'border-left: 4px solid #000 !important;' }}">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-dark text-white d-flex align-items-center justify-content-center mr-3" style="width: 40px; height: 40px;">
                                        @if(isset($notification->data['type']) && $notification->data['type'] === 'talent_profile')
                                            <i class="fas fa-user"></i>
                                        @elseif(isset($notification->data['type']) && $notification->data['type'] === 'casting_application')
                                            <i class="fas fa-video"></i>
                                        @else
                                            <i class="fas fa-bell"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-0 font-weight-bold">{{ $notification->data['title'] ?? 'Notification' }}</h6>
                                        <small class="text-secondary">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if(!$notification->read_at)
                                    <button onclick="markNotificationAsReadPage('{{ $notification->id }}')" class="btn btn-outline-dark btn-sm mark-read-btn-page">
                                        Mark as read
                                    </button>
                                @endif
                            </div>
                            <p class="mb-0 text-muted" style="margin-left: 53px;">
                                {{ $notification->data['message'] ?? '' }}
                            </p>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <img src="{{ asset('images/noti.png') }}" alt="No Notifications" style="opacity: 0.2; width: 60px;" class="mb-3">
                            <p class="text-muted">You have no notifications at this time.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer bg-white py-3">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .border-left-dark {
        border-left: 4px solid #000 !important;
    }
</style>
@endsection

@section('scripts')
<script>
    function markNotificationAsReadPage(id) {
        $.ajax({
            url: '/talent/notifications/' + id + '/mark-as-read',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    $('#notification-page-' + id).removeClass('bg-light border-left-dark').css('border-left', 'none');
                    $('#notification-page-' + id + ' .mark-read-btn-page').remove();
                    // Also update the header badge if possible (function is in layout)
                    if (typeof updateBadgeCount === 'function') {
                        updateBadgeCount();
                    }
                }
            }
        });
    }
</script>
@endsection
