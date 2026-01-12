@extends('layouts.admin')
@section('content')
<style>
    .notifications-shell {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }
    .notif-hero {
        background: #111827;
        color: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 12px;
        box-shadow: 0 12px 32px rgba(0,0,0,0.18);
    }
    .notif-hero h2 {
        margin: 0;
        font-size: 20px;
        font-weight: 700;
    }
    .notif-hero p {
        margin: 4px 0 0;
        color: #d1d5db;
        font-size: 13px;
    }
    .hero-actions {
        display: inline-flex;
        gap: 10px;
    }
    .btn-ghost,
    .btn-solid {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 9px 13px;
        border-radius: 10px;
        font-weight: 600;
        font-size: 13px;
        border: 1px solid rgba(255,255,255,0.2);
        color: #fff;
        background: transparent;
        text-decoration: none;
        transition: all 0.18s ease;
    }
    .btn-solid {
        background: #10b981;
        border-color: #10b981;
        color: #ffffff;
    }
    .btn-ghost:hover { background: inherit; color: #fff; border-color: rgba(255,255,255,0.2); text-decoration: none; }
    .btn-solid:hover { background: #0f9a6a; border-color: #0f9a6a; text-decoration: none; }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 12px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 6px 18px rgba(15,23,42,0.07);
    }
    .stat-icon {
        width: 38px;
        height: 38px;
        border-radius: 10px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 16px;
    }
    .stat-text { display: flex; flex-direction: column; gap: 2px; }
    .stat-label { font-size: 12px; color: #6b7280; font-weight: 600; }
    .stat-value { font-size: 18px; font-weight: 800; color: #111827; }

    .filter-pills {
        display: inline-flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    .pill {
        padding: 7px 12px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        font-weight: 600;
        font-size: 12px;
        text-decoration: none;
        transition: all 0.15s ease;
    }
    .pill:hover { background: #f9fafb; text-decoration: none; }
    .pill.active {
        background: #111827;
        color: #fff;
        border-color: #111827;
    }

    .notif-list {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .notif-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 14px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 12px;
        align-items: center;
        box-shadow: 0 8px 20px rgba(15,23,42,0.08);
    }
    .notif-card.unread { border-color: #c7d2fe; box-shadow: 0 8px 24px rgba(59,130,246,0.18); }
    .notif-icon {
        width: 46px;
        height: 46px;
        border-radius: 12px;
        display: grid;
        place-items: center;
        color: #fff;
        font-size: 18px;
        font-weight: 700;
    }
    .notif-body { display: flex; flex-direction: column; gap: 6px; }
    .notif-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }
    .badge-soft {
        padding: 4px 8px;
        border-radius: 999px;
        background: #f3f4f6;
        color: #374151;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.02em;
    }
    .notif-message { margin: 0; color: #4b5563; font-size: 13px; }
    .notif-meta { font-size: 12px; color: #6b7280; display: flex; gap: 12px; align-items: center; flex-wrap: wrap; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; }

    .notif-actions { display: inline-flex; gap: 8px; }
    .btn-line,
    .btn-plain {
        padding: 8px 12px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        color: #111827;
        background: #fff;
        transition: all 0.15s ease;
    }
    .btn-line:hover { background: #111827; color: #fff; border-color: #111827; text-decoration: none; }
    .btn-plain { border-style: dashed; color: #6b7280; }
    .btn-plain:hover { background: #f3f4f6; color: #111827; text-decoration: none; }
    .notif-card a:hover { text-decoration: none; }

    /* Dark theme */
    html[data-theme="dark"] .notif-hero { background: #0f172a; box-shadow: none; }
    html[data-theme="dark"] .notif-hero p { color: #cbd5e1; }
    html[data-theme="dark"] .stat-card,
    html[data-theme="dark"] .notif-card {
        background: #1f2937;
        border-color: #2d3138;
        box-shadow: none;
    }
    html[data-theme="dark"] .stat-label,
    html[data-theme="dark"] .notif-message,
    html[data-theme="dark"] .notif-meta { color: #cbd5e1; }
    html[data-theme="dark"] .stat-value,
    html[data-theme="dark"] .notif-title { color: #f9fafb; }
    html[data-theme="dark"] .pill { background: #111827; border-color: #2d3138; color: #e5e7eb; }
    html[data-theme="dark"] .pill.active { background: #3b82f6; border-color: #3b82f6; }
    html[data-theme="dark"] .btn-line,
    html[data-theme="dark"] .btn-plain {
        background: #111827;
        border-color: #2d3138;
        color: #e5e7eb;
    }
    html[data-theme="dark"] .btn-line:hover { background: #3b82f6; border-color: #3b82f6; color: #fff; }
    html[data-theme="dark"] .btn-plain:hover { background: #252932; }
</style>

<div class="notifications-shell">
    <div class="notif-hero">
        <div>
            <h2>Notifications</h2>
            <p>Stay on top of talent profiles, casting activity, and payments.</p>
        </div>
        <div class="hero-actions">
            <a class="btn-ghost" href="{{ route('admin.notifications.index') }}">
                <i class="fas fa-sync-alt"></i> Refresh
            </a>
            @if(auth()->user()->unreadNotifications->count() > 0)
                <a class="btn-solid" href="{{ route('admin.notifications.mark-all-read') }}">
                    <i class="fas fa-check-double"></i> Mark all as read
            </a>
        @endif
        </div>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon" style="background:#111827;"><i class="fas fa-bell"></i></div>
            <div class="stat-text">
                <span class="stat-label">Total</span>
                <span class="stat-value">{{ $stats['total'] ?? 0 }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#2563eb;"><i class="fas fa-inbox"></i></div>
            <div class="stat-text">
                <span class="stat-label">Unread</span>
                <span class="stat-value">{{ $stats['unread'] ?? 0 }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#10b981;"><i class="fas fa-check-circle"></i></div>
            <div class="stat-text">
                <span class="stat-label">Read</span>
                <span class="stat-value">{{ $stats['read'] ?? 0 }}</span>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background:#f59e0b;"><i class="fas fa-layer-group"></i></div>
            <div class="stat-text">
                <span class="stat-label">By Type</span>
                <span class="stat-value">
                    TP {{ $typeCounts['talent_profile'] ?? 0 }} · CA {{ $typeCounts['casting_application'] ?? 0 }} · PR {{ $typeCounts['payment_requested'] ?? 0 }}
                </span>
            </div>
        </div>
    </div>

    <div style="display:flex; align-items:center; justify-content: space-between; flex-wrap: wrap; gap:10px;">
        <div class="filter-pills">
            <a class="pill {{ $filter === 'all' ? 'active' : '' }}" href="{{ route('admin.notifications.index', ['filter' => 'all']) }}">All ({{ $stats['total'] ?? 0 }})</a>
            <a class="pill {{ $filter === 'unread' ? 'active' : '' }}" href="{{ route('admin.notifications.index', ['filter' => 'unread']) }}">Unread ({{ $stats['unread'] ?? 0 }})</a>
            <a class="pill {{ $filter === 'read' ? 'active' : '' }}" href="{{ route('admin.notifications.index', ['filter' => 'read']) }}">Read ({{ $stats['read'] ?? 0 }})</a>
        </div>
        <div class="filter-pills" style="gap:6px;">
            <span class="badge-soft">Talent Profiles: {{ $typeCounts['talent_profile'] ?? 0 }}</span>
            <span class="badge-soft">Casting Apps: {{ $typeCounts['casting_application'] ?? 0 }}</span>
            <span class="badge-soft">Payments: {{ $typeCounts['payment_requested'] ?? 0 }}</span>
        </div>
    </div>

    @php
        $typeMeta = [
            'talent_profile' => ['icon' => 'fas fa-user', 'bg' => '#2563eb', 'label' => 'Talent Profile'],
            'casting_application' => ['icon' => 'fas fa-video', 'bg' => '#f59e0b', 'label' => 'Casting Application'],
            'payment_requested' => ['icon' => 'fas fa-dollar-sign', 'bg' => '#10b981', 'label' => 'Payment Request'],
            'default' => ['icon' => 'fas fa-bell', 'bg' => '#6b7280', 'label' => 'Notification'],
        ];
    @endphp

    <div class="notif-list">
                    @forelse($notifications as $notification)
            @php
                $type = $notification->data['type'] ?? 'default';
                $meta = $typeMeta[$type] ?? $typeMeta['default'];
                $title = $notification->data['title'] ?? 'Notification';
                $message = $notification->data['message'] ?? 'No additional details provided.';
            @endphp
            <div class="notif-card {{ $notification->read_at ? '' : 'unread' }}">
                <div class="notif-icon" style="background: {{ $meta['bg'] }}">
                    <i class="{{ $meta['icon'] }}"></i>
                </div>
                <div class="notif-body">
                    <div class="notif-title">
                        <span>{{ $title }}</span>
                        <span class="badge-soft" style="background: #f3f4f6; color:#111827;">{{ $meta['label'] }}</span>
                        @if(!$notification->read_at)
                            <span class="badge-soft" style="background:#c7d2fe; color:#1d4ed8;">Unread</span>
                                @endif
                    </div>
                    <p class="notif-message">{{ $message }}</p>
                    <div class="notif-meta">
                        <span class="status-dot" style="background: {{ $notification->read_at ? '#10b981' : '#f59e0b' }};"></span>
                        <span>{{ $notification->created_at->format('M d, Y g:i A') }}</span>
                        <span>({{ $notification->created_at->diffForHumans() }})</span>
                    </div>
                </div>
                <div class="notif-actions">
                    <a class="btn-line" href="{{ route('admin.notifications.show', $notification->id) }}">
                                    View
                                </a>
                    @if(!$notification->read_at)
                        <a class="btn-plain" href="{{ route('admin.notifications.show', $notification->id) }}">
                            Mark as read
                        </a>
                    @endif
                </div>
            </div>
                    @empty
            <div class="card">
                <div class="card-body text-center text-muted">
                    No notifications found.
                </div>
            </div>
                    @endforelse
        </div>

    <div style="margin-top: 8px;">
        {{ $notifications->links() }}
    </div>
</div>
@endsection
