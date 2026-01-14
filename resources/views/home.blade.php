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

        font-size: 11px;

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
        margin-bottom: 8px;
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
        font-weight: 400;
        font-size: 20px;
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
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .table-wrap::-webkit-scrollbar {
        display: none;
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
        display: inline-block;
        transition: color 0.2s ease;
    }
    .talent-name:hover {
        color: #3b82f6;
        text-decoration: underline;
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
        text-decoration: none;
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
        transition: all 0.2s ease;
    }
    .actions-btn:hover { background: #f3f5f9; border-color: #cbd5e1;text-decoration: none; }

    .actions-dropdown-container { position: relative; display: inline-block; }
    .actions-dropdown-menu {
        text-decoration: none;
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 8px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        min-width: 140px;
        display: none;
        overflow: hidden;
    }
    .actions-dropdown-menu.active { display: block; animation: dropdownFade 0.2s ease; }

    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .actions-dropdown-item {
      text-decoration: none;
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--ink-700);
        text-decoration: none;
        transition: background 0.12s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .actions-dropdown-item:hover { background: #f3f5f9; color: var(--ink-900); text-decoration: none;}
    .actions-dropdown-item.text-danger { color: #dc2626; }
    .actions-dropdown-item.text-danger:hover { background: #fef2f2; }

    @media (max-width: 720px) {
        .topbar { flex-wrap: wrap; }
        .icon-row { width: 100%; justify-content: flex-end; }
        table.talent-table th, table.talent-table td { white-space: nowrap; }
    }

    /* Dark theme support */
    html[data-theme="dark"] .talent-name {
        color: #f3f4f6;
    }
    html[data-theme="dark"] .talent-name:hover {
        color: #60a5fa;
    }
</style>

<div class="dash-shell" style="padding-top: 20px;">
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
                <img src="{{ asset('images/talent.png') }}" alt="Total Talents" style="width: 20px; height: 20px;">
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Pending Verification</p>
                <p class="stat-value">{{ $pending }}</p>
                <span class="stat-pill">+12% new requests</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <img src="{{ asset('images/pending.png') }}" alt="Pending Verification" style="width: 20px; height: 20px;">
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Active Shoots</p>
                <p class="stat-value">{{ $active }}</p>
                <span class="stat-pill">+32% ongoing now</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <img src="{{ asset('images/camera.png') }}" alt="Active Shoots" style="width: 20px; height: 20px;">
            </div>
        </div>
        <div class="stat-card">
            <div>
                <p class="stat-title">Recent Sign-ups</p>
                <p class="stat-value">{{ $recent }}</p>
                <span class="stat-pill">+12% this week</span>
            </div>
            <div class="stat-icon" aria-hidden="true">
                <img src="{{ asset('images/recent.png') }}" alt="Recent Sign-ups" style="width: 20px; height: 20px;">
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
                            $talentRows = $talents instanceof \Illuminate\Support\Collection ? $talents : collect($talents);
                        @endphp
                        @forelse($talentRows as $talent)
                            @php
                                $name = optional($talent->user)->name ?? ($talent->display_name ?? $talent->legal_name ?? '—');
                                $user = $talent->user ?? null;
                                $phoneNumber = '';
                                if ($user) {
                                    $countryCode = $user->phone_country_code ?? '';
                                    $phone = $user->phone_number ?? '';
                                    if ($countryCode && $phone) {
                                        $phoneNumber = $countryCode . ' ' . $phone;
                                    } elseif ($phone) {
                                        $phoneNumber = $phone;
                                    }
                                }

                                // Image loading logic using Storage for S3 support
                                $storageDisk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'));
                                $avatar = null;

                                if (!empty($talent->headshot_center_path)) {
                                    $path = $talent->headshot_center_path;
                                    // Check if it's already a full URL
                                    if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', 'data:'])) {
                                        $avatar = $path;
                                    } else {
                                        // Use Storage to get the URL (works for both S3 and local)
                                        $cleanPath = ltrim($path, '/');
                                        try {
                                            $avatar = $storageDisk->url($cleanPath);
                                        } catch (\Exception $e) {
                                            $avatar = asset('storage/' . $cleanPath);
                                        }
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
                                            <a href="{{ route('admin.talent-profiles.show', $talent) }}" class="talent-name" style="text-decoration: none; color: var(--ink-900); font-weight: 700;">{{ $name }}</a>
                                            <p class="talent-email">{{ $phoneNumber ?: '—' }}</p>
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
                                    <div class="actions-dropdown-container">
                                        <button type="button" class="actions-btn dropdown-toggle-btn" aria-label="More actions">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="1"/><circle cx="19" cy="12" r="1"/><circle cx="5" cy="12" r="1"/>
                                            </svg>
                                        </button>
                                        <div class="actions-dropdown-menu">
                                            <a href="{{ route('admin.talent-profiles.show', $talent) }}" class="actions-dropdown-item">
                                                <i class="far fa-eye"></i> View Profile
                                            </a>
                                            <form action="{{ route('admin.talent-profiles.destroy', $talent) }}" method="POST" data-swal-confirm="Are you sure you want to delete this talent? All the data will be deleted.">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="actions-dropdown-item text-danger">
                                                    <i class="far fa-trash-alt"></i> Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
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
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');

            dropdownBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const menu = this.nextElementSibling;

                    // Close other menus
                    document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                        if (m !== menu) m.classList.remove('active');
                    });

                    menu.classList.toggle('active');
                });
            });

            document.addEventListener('click', function() {
                document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
                    menu.classList.remove('active');
                });
            });
        });
    </script>
@endsection
