@extends('layouts.admin')

@section('content')
<style>
    :root {
        --bg: #f7f8fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e5e7eb;
        --shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .audit-logs-shell { padding: 8px 0 80px; }
    .audit-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 10px 0 12px;
        margin-bottom: 20px;
    }
    .audit-head h2 {
        color: black;
        font-family: 'Arimo', sans-serif;
        font-size: 24px;
        font-weight: 400;
        line-height: 36px;
        margin: 0;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--shadow);
    }
    .stat-label {
        font-size: 13px;
        color: var(--ink-500);
        margin-bottom: 8px;
    }
    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: var(--ink-900);
    }

    .filters-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: var(--shadow);
    }
    .filters-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 16px;
        margin-bottom: 16px;
    }
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .filter-group label {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-700);
    }
    .filter-group select,
    .filter-group input {
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 13px;
        color: var(--ink-700);
        background: #fff;
    }
    .filter-actions {
        display: flex;
        gap: 12px;
        margin-top: 8px;
    }
    .btn-filter {
        background: black;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }
    .btn-reset {
        background: #f3f4f6;
        color: var(--ink-700);
        border: 1px solid var(--border);
        text-decoration: none;
    }
    .btn-reset:hover {
        background: #f3f4f6;
        color: var(--ink-700);
        text-decoration: none;
        border-color: var(--border);
    }
    .btn-reset:focus,
    .btn-reset:active {
        background: #f3f4f6;
        color: var(--ink-700);
        text-decoration: none;
        border-color: var(--border);
        outline: none;
    }

    .logs-table-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 20px;
        box-shadow: var(--shadow);
        overflow-x: auto;
    }
    .logs-table {
        width: 100%;
        border-collapse: collapse;
    }
    .logs-table thead {
        background: #f9fafb;
        border-bottom: 2px solid var(--border);
    }
    .logs-table th {
        padding: 12px 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-700);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .logs-table td {
        padding: 16px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        color: var(--ink-700);
    }
    .logs-table tbody tr:hover {
        background: #f9fafb;
    }

    .badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-success { background: #e6f7ed; color: #15803d; }
    .badge-danger { background: #fee2e2; color: #dc2626; }
    .badge-info { background: #dbeafe; color: #1e40af; }
    .badge-warning { background: #fffbeb; color: #f59e0b; }

    .user-type-badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: capitalize;
    }
    .user-type-admin { background: #e0e7ff; color: #3730a3; }
    .user-type-creative { background: #fce7f3; color: #9f1239; }
    .user-type-talent { background: #dcfce7; color: #166534; }

    .no-logs {
        text-align: center;
        padding: 60px 20px;
        color: var(--ink-500);
    }
</style>

<div class="audit-logs-shell">
    <div class="audit-head">
        <div>
            <h2>Audit Logs</h2>
            <p style="color: var(--ink-500); font-size: 14px; margin: 4px 0 0;">System activity and authentication logs</p>
        </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-label">Total Logs</div>
            <div class="stat-value">{{ number_format($stats['total_logs']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Successful Logins</div>
            <div class="stat-value">{{ number_format($stats['login_success']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Failed Logins</div>
            <div class="stat-value">{{ number_format($stats['login_failed']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Onboarding Events</div>
            <div class="stat-value">{{ number_format($stats['onboarding_events']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Admin Logins</div>
            <div class="stat-value">{{ number_format($stats['admin_logins']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Creative Logins</div>
            <div class="stat-value">{{ number_format($stats['creative_logins']) }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Talent Logins</div>
            <div class="stat-value">{{ number_format($stats['talent_logins']) }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="filters-card">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" id="filterForm">
            <div class="filters-row">
                <div class="filter-group">
                    <label>Event Type</label>
                    <select name="event_type">
                        <option value="">All Events</option>
                        <option value="login_success" {{ request('event_type') == 'login_success' ? 'selected' : '' }}>Login Success</option>
                        <option value="login_failed" {{ request('event_type') == 'login_failed' ? 'selected' : '' }}>Login Failed</option>
                        <option value="logout" {{ request('event_type') == 'logout' ? 'selected' : '' }}>Logout</option>
                        <option value="onboarding_step" {{ request('event_type') == 'onboarding_step' ? 'selected' : '' }}>Onboarding Step</option>
                        <option value="onboarding_completed" {{ request('event_type') == 'onboarding_completed' ? 'selected' : '' }}>Onboarding Completed</option>
                        <option value="signup" {{ request('event_type') == 'signup' ? 'selected' : '' }}>Signup</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>User Type</label>
                    <select name="user_type">
                        <option value="">All Users</option>
                        <option value="admin" {{ request('user_type') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="creative" {{ request('user_type') == 'creative' ? 'selected' : '' }}>Creative</option>
                        <option value="talent" {{ request('user_type') == 'talent' ? 'selected' : '' }}>Talent</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Date From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}">
                </div>
                <div class="filter-group">
                    <label>Date To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}">
                </div>
                <div class="filter-group">
                    <label>Search (Email/Phone)</label>
                    <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}">
                </div>
            </div>
            <div class="filter-actions">
                <button type="submit" class="btn-filter">Apply Filters</button>
                <a href="{{ route('admin.audit-logs.index') }}" class="btn-filter btn-reset">Reset</a>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="logs-table-card">
        @if($logs->count() > 0)
        <table class="logs-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Event</th>
                    <th>User Type</th>
                    <th>User</th>
                    <th>Details</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @foreach($logs as $log)
                <tr>
                    <td>
                        <div>{{ $log->created_at->format('M d, Y') }}</div>
                        <div style="color: var(--ink-500); font-size: 12px;">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        @if($log->event_type == 'login_success')
                            <span class="badge badge-success">Login Success</span>
                        @elseif($log->event_type == 'login_failed')
                            <span class="badge badge-danger">Login Failed</span>
                        @elseif($log->event_type == 'logout')
                            <span class="badge badge-info">Logout</span>
                        @elseif($log->event_type == 'onboarding_step')
                            <span class="badge badge-warning">Onboarding Step</span>
                        @elseif($log->event_type == 'onboarding_completed')
                            <span class="badge badge-success">Onboarding Completed</span>
                        @elseif($log->event_type == 'signup')
                            <span class="badge badge-info">Signup</span>
                        @else
                            <span class="badge">{{ $log->event_type }}</span>
                        @endif
                    </td>
                    <td>
                        <span class="user-type-badge user-type-{{ $log->user_type }}">
                            {{ ucfirst($log->user_type) }}
                        </span>
                    </td>
                    <td>
                        <div>
                            @if($log->user_email)
                                {{ $log->user_email }}
                            @elseif($log->user_phone)
                                +965 {{ $log->user_phone }}
                            @else
                                N/A
                            @endif
                        </div>
                        @if($log->user_id)
                            <div style="color: var(--ink-500); font-size: 12px;">ID: {{ $log->user_id }}</div>
                        @endif
                    </td>
                    <td>
                        @if($log->onboarding_step)
                            <div><strong>Step:</strong> {{ $log->onboarding_step }}</div>
                            <div><strong>Completed:</strong> {{ $log->onboarding_steps_completed ?? 0 }}/5</div>
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->login_failure_reason)
                            <div style="color: #dc2626;">{{ $log->login_failure_reason }}</div>
                        @else
                            <span style="color: var(--ink-500);">—</span>
                        @endif
                    </td>
                    <td>
                        <div>{{ $log->ip_address ?? 'N/A' }}</div>
                        @if($log->user_agent)
                            <div style="color: var(--ink-500); font-size: 11px; margin-top: 4px; max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" title="{{ $log->user_agent }}">
                                {{ Str::limit($log->user_agent, 40) }}
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div style="margin-top: 20px;">
            {{ $logs->links() }}
        </div>
        @else
        <div class="no-logs">
            <p>No audit logs found.</p>
        </div>
        @endif
    </div>
</div>
@endsection