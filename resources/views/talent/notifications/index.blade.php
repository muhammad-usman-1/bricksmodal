@extends('layouts.talent')

@section('content')
<div class="row" style="margin-top:20px;">
    <div class="col-md-10 offset-md-1">
        <div class="card border-0 shadow-sm" >
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-4 px-4" style="border-bottom: 1px solid #f1f1f1; border-top-left-radius: 16px; border-top-right-radius: 16px;">
                <h4 class="mb-0 font-weight-bold" style="font-size: 22px; color: #000;">Notifications</h4>
                @php
                    $unreadCount = auth('talent')->user()->unreadNotifications->count();
                @endphp
                @if($unreadCount > 0)
                    <button onclick="markAllNotificationsAsRead()" class="btn btn-dark px-4" style="border-radius: 10px; font-weight: 600; font-size: 14px;">
                        Mark all as read
                    </button>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item d-flex flex-column py-4 px-4 {{ $notification->read_at ? '' : 'unread-item' }}" id="notification-page-{{ $notification->id }}" style="border-bottom: 1px solid #f8f9fa;">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="rounded-circle bg-light text-dark d-flex align-items-center justify-content-center mr-3" style="width: 44px; height: 44px; background-color: #f8f9fa !important;">
                                        @php
                                            $type = $notification->data['type'] ?? '';
                                        @endphp
                                        @if(str_contains($type, 'talent_profile') || str_contains($type, 'talent_signup'))
                                            <i class="fas fa-user" style="font-size: 16px;"></i>
                                        @elseif(str_contains($type, 'shoot'))
                                            <i class="fas fa-video" style="font-size: 16px;"></i>
                                        @elseif(str_contains($type, 'payment'))
                                            <i class="fas fa-credit-card" style="font-size: 16px;"></i>
                                        @elseif(str_contains($type, 'feedback'))
                                            <i class="fas fa-star" style="font-size: 16px;"></i>
                                        @else
                                            <i class="fas fa-bell" style="font-size: 16px;"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <h6 class="mb-1 font-weight-bold" style="font-size: 16px; color: #000;">{{ $notification->data['title'] ?? ($notification->data['subject'] ?? 'Notification') }}</h6>
                                        <div class="text-secondary" style="font-size: 12px; color: #adb5bd !important;">{{ $notification->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                                @if(!$notification->read_at)
                                    <button onclick="markNotificationAsReadPage('{{ $notification->id }}')" class="btn btn-outline-dark btn-sm mark-read-btn-page" style="border-radius: 6px; font-size: 12px; padding: 4px 12px;">
                                        Mark as read
                                    </button>
                                @endif
                            </div>
                            <div class="mt-1" style="margin-left: 58px; font-size: 14px; color: #495057; line-height: 1.6;">
                                {{ $notification->data['message'] ?? ($notification->data['body'] ?? '') }}
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fas fa-bell mb-3" style="font-size: 48px; color: #e9ecef;"></i>
                            <h5 class="text-muted">No notifications yet</h5>
                            <p class="text-secondary small">We'll alert you when there's something new.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            @if($notifications->hasPages())
                <div class="card-footer bg-white py-4 px-4" style="border-top: 1px solid #f1f1f1; border-bottom-left-radius: 16px; border-bottom-right-radius: 16px;">
                    {{ $notifications->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .unread-item {
        background-color: #fff !important;
        border-left: 4px solid #000 !important;
    }
    .pagination .page-link {
        color: #000 !important;
        border-radius: 6px !important;
        margin: 0 3px !important;
    }
    .pagination .active .page-link {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
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
