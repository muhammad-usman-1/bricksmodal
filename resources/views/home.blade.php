@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e6e9f0;
        --shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
        --pill: #eff2f7;
        --green: #00b87c;
        --amber: #f9cf55;
    }

    body {
        background: var(--bg);
        font-family: 'Inter', 'Arial', sans-serif;
    }



    .topbar {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
    }

    .search-box {
        flex: 1;
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 10px 14px;
        box-shadow: inset 0 1px 0 rgba(255,255,255,0.6);
    }

    .search-box input {
        border: none;
        outline: none;
        width: 100%;
        font-size: 14px;
        color: var(--ink-700);
        background: transparent;
    }

    .icon-row {
        display: inline-flex;
        align-items: center;
        gap: 10px;
    }

    .icon-btn {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        border: 1px solid var(--border);
        background: #fff;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-700);
        cursor: pointer;
        transition: background 0.12s ease, transform 0.12s ease;
    }

    .icon-btn:hover {
        background: #f3f5f9;
        transform: translateY(-1px);
    }

    .overview {
        margin-bottom: 12px;
    }

    .overview h5 {
     
        color: #101828;
 
font-size: 24px;
font-style: normal;
font-weight: 400;
line-height: 36px;
    }

    .overview .sub {
        margin: 2px 0 0;
        color: var(--ink-500);
        font-size: 13px;
    }

    .stat-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 18px;
    }

    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 14px;
        padding: 14px;
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 10px;
        align-items: center;
        box-shadow: var(--shadow);
    }

    .stat-title {
        color: var(--ink-500);
        font-size: 12px;
        margin: 0 0 6px;
        letter-spacing: 0.1px;
    }

    .stat-value {
        color: var(--ink-900);
        font-size: 18px;
        font-weight: 800;
        margin: 0;
    }

    .stat-pill {
        color: #10b981;
        font-size: 11px;
        background: #e9f7f1;
        border-radius: 999px;
        padding: 4px 8px;
        display: inline-block;
        margin-top: 2px;
    }

    .stat-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #f1f4f8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--ink-700);
    }

    .panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .panel-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px 10px;
    }

    .panel-title {
        margin: 0;
        color: var(--ink-900);
        font-weight: 800;
        font-size: 14px;
    }

    .panel-body {
        padding: 0 0 6px;
    }

    .panel-link {
        color: #7b8191;
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
    }

    .panel-link:hover {
        color: #0f1524;
        text-decoration: none;
    }

    .table-wrap {
        overflow-x: auto;
    }

    table.talent-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
    }

    table.talent-table th,
    table.talent-table td {
        padding: 12px 16px;
        border-bottom: 1px solid #eef1f5;
        color: var(--ink-700);
    }

    table.talent-table th {
        text-transform: uppercase;
        letter-spacing: 0.05em;
        font-size: 11px;
        color: var(--ink-500);
        font-weight: 700;
    }

    .talent-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .avatar {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        background: #e5e7eb;
        flex-shrink: 0;
    }

    .talent-name {
        font-weight: 700;
        color: var(--ink-900);
        margin: 0;
    }

    .talent-email {
        margin: 0;
        color: var(--ink-500);
        font-size: 12px;
    }

    .stat-pill-small {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 10px;
        background: #f3f5f9;
        border-radius: 10px;
        font-weight: 700;
        font-size: 12px;
        color: var(--ink-700);
    }

    .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 70px;
        padding: 6px 10px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
    }

    .badge-pending { background: #fff7e0; color: #ad7a00; border: 1px solid #f5df9b; }
    .badge-verified { background: #e9f7f1; color: #0f9d75; border: 1px solid #ccefe0; }
    .badge-rejected { background: #fde8e8; color: #b91c1c; border: 1px solid #f8cccc; }

    .actions-btn {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-700);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    @media (max-width: 720px) {
        .topbar { flex-wrap: wrap; }
        .icon-row { width: 100%; justify-content: flex-end; }
        table.talent-table th, table.talent-table td { white-space: nowrap; }
    }
</style>

<div class="dash-shell">
    {{--  <div class="topbar">
        <div class="search-box">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
            <input type="text" placeholder="Search talents, shoots, or campaigns..." aria-label="Search" />
        </div>
        <div class="icon-row">
            <button class="icon-btn" aria-label="Notifications">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
            </button>
            <button class="icon-btn" aria-label="Settings">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 8 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 8 4.6a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09A1.65 1.65 0 0 0 15 4.6a1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9c.14.53.51.97 1 .97H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
            </button>
            <button class="icon-btn" aria-label="Profile">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg>
            </button>
        </div>
    </div>  --}}

    <div class="overview">
        <h5>Dashboard Overview</h5>
        <div class="sub">Welcome back, here’s what’s happening today.</div>
    </div>

    <div class="stat-grid">
        @php
            $total = $stats['total'] ?? 0;
            $pending = $stats['pending_verification'] ?? 0;
            $active = $stats['active_shoots'] ?? ($stats['active_campaigns'] ?? 0);
            $recent = $stats['recent_signups'] ?? 0;
        @endphp
        <div class="stat-card">
            <div>
                <p class="stat-title">Total Talents</p>
                <p class="stat-value">{{ $total }}</p>
                <span class="stat-pill">+12% vs last month</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="4"/><path d="M5.5 21a6.5 6.5 0 0 1 13 0"/></svg>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Pending Verification</p>
                <p class="stat-value">{{ $pending }}</p>
                <span class="stat-pill">+12% new requests</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Active Shoots</p>
                <p class="stat-value">{{ $active }}</p>
                <span class="stat-pill">+32% ongoing now</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path d="M21 15l-5-5L5 21"/></svg>
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Recent Sign-ups</p>
                <p class="stat-value">{{ $recent }}</p>
                <span class="stat-pill">+12% this week</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 21l-6-6"/><circle cx="10" cy="10" r="7"/></svg>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h6 class="panel-title">Recent Talent Sign-ups</h6>
            <a class="panel-link" href="{{ route('admin.talents.dashboard') }}">View All</a>
        </div>
        <div class="panel-body">
            <div class="table-wrap">
                <table class="talent-table">
                    <thead>
                        <tr>
                            <th>Talent</th>
                            <th>Gender</th>
                            <th>Stats (H / W)</th>
                            <th>Date Joined</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $talentRows = $talents instanceof \Illuminate\Support\Collection ? $talents->take(4) : collect($talents)->take(4);
                        @endphp
                        @forelse($talentRows as $talent)
                            @php
                                $name = optional($talent->user)->name ?? ($talent->display_name ?? $talent->legal_name ?? '—');
                                $email = optional($talent->user)->email ?? '';
                                $avatar = null;
                                if (!empty($talent->headshot_center_path)) {
                                    $publicPath = public_path('storage/' . ltrim($talent->headshot_center_path, '/'));
                                    if (file_exists($publicPath)) {
                                        $avatar = asset('storage/' . ltrim($talent->headshot_center_path, '/'));
                                    } else {
                                        $avatar = $talent->headshot_center_path;
                                    }
                                }
                                if (empty($avatar)) {
                                    $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=eff2f7&color=0f1524&rounded=true&size=64';
                                }
                                $status = $talent->verification_status ?? 'pending';
                            @endphp
                            <tr>
                                <td>
                                    <div class="talent-cell">
                                        <img class="avatar" src="{{ $avatar }}" alt="{{ $name }}" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=eff2f7&color=0f1524&rounded=true&size=64'">
                                        <div>
                                            <p class="talent-name">{{ $name }}</p>
                                            <p class="talent-email">{{ $email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst($talent->gender ?? '—') }}</td>
                                <td>
                                    <span class="stat-pill-small">{{ $talent->height ? $talent->height . 'cm' : '—' }} / {{ $talent->weight ? $talent->weight . 'kg' : '—' }}</span>
                                </td>
                                <td>{{ $talent->created_at ? $talent->created_at->format('Y-m-d') : '—' }}</td>
                                <td>
                                    @if(in_array($status, ['approved','verified']))
                                        <span class="badge-status badge-verified">Verified</span>
                                    @elseif($status === 'pending')
                                        <span class="badge-status badge-pending">Pending</span>
                                    @else
                                        <span class="badge-status badge-rejected">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td style="text-align:right;">
                                    <button class="actions-btn" aria-label="More actions">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/></svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:22px; color: var(--ink-500);">No recent talents found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
