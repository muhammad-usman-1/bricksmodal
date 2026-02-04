@extends('layouts.admin')
@section('content')
<style>
    :root {
        --notif-primary: #000000;
        --notif-accent: #000000;
        --notif-success: #000000;
        --notif-warning: #4b5563;
        --notif-gray: #6b7280;
        --notif-bg: #f9fafb;
        --notif-card-bg: #ffffff;
        --notif-border: #e5e7eb;
        --notif-hover: #f3f4f6;
    }

    [data-theme="dark"] {
        --notif-primary: #ffffff;
        --notif-accent: #ffffff;
        --notif-success: #ffffff;
        --notif-warning: #9ca3af;
        --notif-gray: #9ca3af;
        --notif-bg: #000000;
        --notif-card-bg: #111111;
        --notif-border: #333333;
        --notif-hover: #222222;
    }

    .dashboard-container {
        margin: 0 auto;
        padding: 20px 0;
        font-family: 'Inter', -apple-system, sans-serif;
    }

    /* --- Sleek Header --- */
    .dashboard-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 32px;
        flex-wrap: wrap;
        gap: 20px;
    }



    .header-info p {
        color: var(--notif-gray);
        margin: 4px 0 0;
        font-size: 14px;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-premium {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        border: 1px solid var(--notif-primary);
        background: var(--notif-card-bg);
        color: var(--notif-primary);
    }

    .btn-premium:hover {
        background: var(--notif-primary);
        color: var(--notif-card-bg);
        text-decoration: none;
    }

    .btn-premium.primary {
        background: var(--notif-primary);
        color: var(--notif-card-bg);
        border: 1px solid var(--notif-primary);
    }

    .btn-premium.primary:hover {
        background: transparent;
        color: var(--notif-primary);
    }

    /* --- Minimal Stats --- */
    .stat-bar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-item {
        background: var(--notif-card-bg);
        padding: 20px;
        border-radius: 12px;
        border: 1px solid var(--notif-border);
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        transition: all 0.2s ease;
    }

    .stat-item:hover {
        border-color: var(--notif-primary);
    }

    .stat-content .label {
        font-size: 11px;
        color: var(--notif-gray);
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        display: block;
        margin-bottom: 4px;
    }

    .stat-content .value {
        font-size: 24px;
        font-weight: 800;
        color: var(--notif-primary);
    }

    .stat-chart-mini {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #000;
        font-size: 16px;
    }

    [data-theme="dark"] .stat-chart-mini {
        background: #222;
        color: #fff;
    }

    /* --- Activity Feed Layout --- */
    .feed-section {
        background: var(--notif-card-bg);
        border-radius: 12px;
        border: 1px solid var(--notif-border);
        overflow: hidden;
    }

    .feed-toolbar {
        padding: 16px 24px;
        border-bottom: 1px solid var(--notif-border);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--notif-card-bg);
    }

    .nav-tabs-premium {
        display: flex;
        gap: 32px;
    }

    .tab-item {
        font-size: 13px;
        font-weight: 700;
        color: var(--notif-gray);
        position: relative;
        padding: 12px 0;
        cursor: pointer;
        text-decoration: none !important;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .tab-item:hover { color: var(--notif-primary); }
    .tab-item.active { color: var(--notif-primary); }
    .tab-item.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: -4px;
        right: -4px;
        height: 3px;
        background: var(--notif-primary);
        border-radius: 0;
    }

    .feed-list {
        display: flex;
        flex-direction: column;
    }

    .feed-item {
        display: grid;
        grid-template-columns: 1fr auto;
        padding: 24px;
        gap: 16px;
        align-items: center;
        border-bottom: 1px solid var(--notif-border);
        transition: all 0.2s ease;
        position: relative;
    }

    .feed-item:last-child { border-bottom: none; }
    .feed-item:hover { background: var(--notif-hover); }

    .feed-item.unread {
        background: #fcfcfc;
    }

    [data-theme="dark"] .feed-item.unread {
        background: #151515;
    }

    .feed-item.unread::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        background: #000;
    }

    [data-theme="dark"] .feed-item.unread::before {
        background: #fff;
    }

    .icon-box {
        width: 44px;
        height: 44px;
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
        border: 1px solid var(--notif-border);
    }

    .feed-content {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .feed-title {
        font-size: 16px;
        font-weight: 800;
        color: var(--notif-primary);
        line-height: 1.2;
    }

    .feed-desc {
        font-size: 13px;
        color: var(--notif-gray);
        line-height: 1.5;
        margin: 0;
    }

    .feed-meta {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
        font-size: 10px;
        font-weight: 800;
        color: var(--notif-gray);
        text-transform: uppercase;
        letter-spacing: 0.1em;
    }

    .type-chip {
        padding: 2px 8px;
        background: var(--notif-primary);
        color: var(--notif-card-bg);
        font-weight: 900;
    }

    .feed-right {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .time-badge {
        font-size: 12px;
        font-weight: 600;
        color: var(--notif-gray);
        white-space: nowrap;
    }

    .action-group {
        display: flex;
        gap: 8px;
    }

    .btn-icon {
        width: 36px;
        height: 36px;
        border-radius: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: var(--notif-card-bg);
        border: 1px solid var(--notif-border);
        color: var(--notif-primary);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-icon:hover {
        background: var(--notif-primary);
        color: var(--notif-card-bg);
        border-color: var(--notif-primary);
    }

    /* Mobile Adaptivity */
    @media (max-width: 768px) {
        .dashboard-header { flex-direction: column; align-items: flex-start; }
        .feed-item { grid-template-columns: 48px 1fr; gap: 12px; }
        .feed-right { grid-column: 2; margin-top: 8px; justify-content: space-between; }
        .stat-bar { grid-template-columns: 1fr; }
    }
</style>

<div class="dashboard-container">
    <!-- Header Area -->
    <header class="dashboard-header">
        <div class="header-info">
            <h1 style="font-family: 'Arimo', sans-serif; font-size: 24px; font-weight: 700; color: #000;">Activity Center</h1>
            <p>Monitor applications, profiles, and financial movements in real-time.</p>
        </div>
        <div class="header-actions">
            <a href="{{ route('admin.notifications.index') }}" class="btn-premium">
                <i class="fas fa-redo-alt"></i> Refresh
            </a>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <button onclick="markAllAdminNotificationsAsRead(); location.reload();" class="btn-premium primary">
                    <i class="fas fa-check-double"></i> Mark All Done
                </button>
            @endif
        </div>
    </header>

    <!-- Stats Section -->
    <section class="stat-bar">
        <div class="stat-item">
            <div class="stat-content">
                <span class="label">Total Activity</span>
                <span class="value">{{ $stats['total'] ?? 0 }}</span>
            </div>
            <div class="stat-chart-mini"><i class="fas fa-bolt"></i></div>
        </div>
        <div class="stat-item">
            <div class="stat-content">
                <span class="label">Attention Needed</span>
                <span class="value">{{ $stats['unread'] ?? 0 }}</span>
            </div>
            <div class="stat-chart-mini"><i class="fas fa-eye"></i></div>
        </div>
        <div class="stat-item">
            <div class="stat-content">
                <span class="label">Completed</span>
                <span class="value">{{ $stats['read'] ?? 0 }}</span>
            </div>
            <div class="stat-chart-mini"><i class="fas fa-check"></i></div>
        </div>
    </section>

    <!-- Activity Feed -->
    <section class="feed-section">
        <div class="feed-toolbar">
            <nav class="nav-tabs-premium">
                <a href="{{ route('admin.notifications.index', ['filter' => 'all']) }}" class="tab-item {{ $filter === 'all' ? 'active' : '' }}">Inbox</a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}" class="tab-item {{ $filter === 'unread' ? 'active' : '' }}">Unread</a>
                <a href="{{ route('admin.notifications.index', ['filter' => 'read']) }}" class="tab-item {{ $filter === 'read' ? 'active' : '' }}">Archived</a>
            </nav>
            <div style="display: flex; gap: 20px;">
                <span style="font-size: 10px; font-weight: 800; color: var(--notif-gray); text-transform: uppercase; letter-spacing: 0.05em;">Profiles: {{ $typeCounts['talent_profile'] }}</span>
                <span style="font-size: 10px; font-weight: 800; color: var(--notif-gray); text-transform: uppercase; letter-spacing: 0.05em;">Apps: {{ $typeCounts['casting_application'] }}</span>
                <span style="font-size: 10px; font-weight: 800; color: var(--notif-gray); text-transform: uppercase; letter-spacing: 0.05em;">Cash: {{ $typeCounts['payment_requested'] }}</span>
            </div>
        </div>

        @php
            $typeMeta = [
                'talent_profile'                  => ['icon' => 'fas fa-id-badge', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Profile'],
                'admin_talent_profile_submission' => ['icon' => 'fas fa-user-plus', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Verification'],
                'admin_profile_status_update'     => ['icon' => 'fas fa-user-shield', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Governance'],

                'casting_application'             => ['icon' => 'fas fa-clapperboard', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Application'],
                'admin_shoot_application'         => ['icon' => 'fas fa-paper-plane', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Submission'],
                'admin_application_status_update' => ['icon' => 'fas fa-rotate', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Decision'],

                'payment_requested'               => ['icon' => 'fas fa-coins', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Finance'],
                'payment_request'                 => ['icon' => 'fas fa-wallet', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Transfer'],
                'admin_payment_received'          => ['icon' => 'fas fa-piggy-bank', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'Revenue'],

                'default'                         => ['icon' => 'fas fa-wave-square', 'bg' => 'transparent', 'color' => 'var(--notif-primary)', 'label' => 'System'],
            ];
        @endphp

        <div class="feed-list">
            @forelse($notifications as $notification)
                @php
                    $type = $notification->data['type'] ?? 'default';
                    $meta = $typeMeta[$type] ?? $typeMeta['default'];
                    $title = $notification->data['title'] ?? 'New Notification';
                    $message = $notification->data['message'] ?? 'Check dashboard for details.';
                @endphp
                <div id="notification-{{ $notification->id }}" class="feed-item {{ $notification->read_at ? '' : 'unread' }}">
                    <div class="feed-content">
                        <div class="feed-title">{{ $title }}</div>
                        <p class="feed-desc">{{ $message }}</p>
                    </div>
                    <div class="feed-right">
                        <span class="time-badge">{{ $notification->created_at->diffForHumans() }}</span>
                        <div class="action-group">
                            <a href="{{ route('admin.notifications.show', $notification->id) }}" class="btn-icon" title="View Details">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                            @if(!$notification->read_at)
                                <button onclick="markAdminNotificationAsRead('{{ $notification->id }}')" class="btn-icon" title="Archive">
                                    <i class="fas fa-archive"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-5 text-center">
                    <p style="color: var(--notif-gray); font-weight: 800; text-transform: uppercase; letter-spacing: .2em;">Inbox empty</p>
                </div>
            @endforelse
        </div>
    </section>

    <!-- Pagination -->
    <div style="margin-top: 24px; display: flex; justify-content: flex-end;">
        {{ $notifications->links() }}
    </div>
</div>

<script>
    // These functions are already in the admin layout, but we ensure they behave nicely with our new UI
    function markAdminNotificationAsRead(id) {
        $.ajax({
            url: '/admin/notifications/' + id + '/mark-as-read',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if (response.success) {
                    $('#notification-' + id).removeClass('unread').addClass('archived');
                    $('#notification-' + id + ' .btn-icon[title="Archive"]').fadeOut();
                }
            }
        });
    }
</script>
@endsection
