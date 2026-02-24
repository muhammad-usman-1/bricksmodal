@extends('layouts.admin')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    .btn-filter:hover {
        text-decoration: none;
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
    .btn-refresh {
        background: #7e8e95;
        color: white;
        border: none;
        border-radius: 8px;
        padding: 9px 20px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-refresh:hover {
        background: #7e8e95;
        text-decoration: none;
        color: white;
    }
    .btn-export-csv {
        background: #10b981;
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

    .see-more-container {
        padding: 16px;
        text-align: center;
        border-top: 1px solid var(--border);
    }

    .see-more-btn {
        color: #7b8191;
        font-weight: 700;
        font-size: 12px;
        text-decoration: none;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px 16px;
        transition: color 0.2s ease;
        outline: none;
    }

    .see-more-btn:hover {
        color: #0f1524;
        text-decoration: none;
    }

    .see-more-btn:focus,
    .see-more-btn:active {
        outline: none;
        border: none;
        box-shadow: none;
    }

    .see-more-btn:disabled {
        opacity: 0.6;
        cursor: wait;
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
                        <option value="account_created" {{ request('event_type') == 'account_created' ? 'selected' : '' }}>Account Created</option>
                        <option value="account_deletion" {{ request('event_type') == 'account_deletion' ? 'selected' : '' }}>Account Deletion</option>
                        <option value="account_suspension" {{ request('event_type') == 'account_suspension' ? 'selected' : '' }}>Account Suspension</option>
                        <option value="account_rejection" {{ request('event_type') == 'account_rejection' ? 'selected' : '' }}>Account Rejection</option>
                        <option value="talent_accepted" {{ request('event_type') == 'talent_accepted' ? 'selected' : '' }}>Talent Accepted</option>
                        <option value="profile_updated" {{ request('event_type') == 'profile_updated' ? 'selected' : '' }}>Profile Updated</option>
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
                <button type="button" class="btn-refresh" id="refreshBtn">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="23 4 23 10 17 10"></polyline>
                        <polyline points="1 20 1 14 7 14"></polyline>
                        <path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path>
                    </svg>
                    Refresh
                </button>
                <button type="button" class="btn-export-csv" id="exportCsvBtn">Export CSV</button>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="logs-table-card" id="logsTableContainer">
        @if($logs->count() > 0)
        <table class="logs-table" id="logsTable">
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
                @foreach($logs as $index => $log)
                <tr class="log-row" style="{{ $index >= 50 ? 'display: none;' : '' }}">
                    <td>
                        <div>{{ $log->created_at->setTimezone('Asia/Kuwait')->format('M d, Y') }}</div>
                        <div style="color: var(--ink-500); font-size: 12px;">{{ $log->created_at->setTimezone('Asia/Kuwait')->format('h:i:s A') }} GMT+3</div>
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
                        @elseif($log->event_type == 'account_created')
                            <span class="badge badge-success">Account Created</span>
                        @elseif($log->event_type == 'account_deletion')
                            <span class="badge badge-danger">Account Deletion</span>
                        @elseif($log->event_type == 'account_suspension')
                            <span class="badge badge-warning">Account Suspension</span>
                        @elseif($log->event_type == 'account_rejection')
                            <span class="badge badge-danger">Account Rejection</span>
                        @elseif($log->event_type == 'talent_accepted')
                            <span class="badge badge-success">Talent Accepted</span>
                        @elseif($log->event_type == 'profile_updated')
                            <span class="badge badge-warning">Profile Updated</span>
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
                            @if($log->user && $log->user->name)
                                <strong>{{ $log->user->name }}</strong>
                                @if($log->user_email)
                                    <div style="color: var(--ink-500); font-size: 12px; margin-top: 2px;">{{ $log->user_email }}</div>
                                @elseif($log->user_phone)
                                    <div style="color: var(--ink-500); font-size: 12px; margin-top: 2px;">+965 {{ $log->user_phone }}</div>
                                @endif
                            @elseif($log->user_email)
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
                        @if($log->event_type == 'talent_accepted')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['admin_name']))
                                <div><strong>Accepted by:</strong> {{ $log->metadata['admin_name'] }}</div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->event_type == 'account_created')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->event_type == 'account_deletion')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['admin_name']))
                                <div><strong>Deleted by:</strong> {{ $log->metadata['admin_name'] }}</div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->event_type == 'account_suspension')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['admin_name']))
                                <div><strong>Suspended by:</strong> {{ $log->metadata['admin_name'] }}</div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->event_type == 'account_rejection')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['admin_name']))
                                <div><strong>Rejected by:</strong> {{ $log->metadata['admin_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['rejection_notes']) && $log->metadata['rejection_notes'])
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;"><strong>Notes:</strong> {{ $log->metadata['rejection_notes'] }}</div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->event_type == 'profile_updated')
                            @if($log->metadata && isset($log->metadata['talent_name']))
                                <div><strong>Talent:</strong> {{ $log->metadata['talent_name'] }}</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['admin_name']))
                                <div><strong>Updated by:</strong> {{ $log->metadata['admin_name'] }}</div>
                            @elseif($log->user_type == 'talent')
                                <div><strong>Updated by:</strong> Talent</div>
                            @endif
                            @if($log->metadata && isset($log->metadata['changed_fields']) && is_array($log->metadata['changed_fields']))
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">
                                    <strong>Fields:</strong> {{ implode(', ', array_slice($log->metadata['changed_fields'], 0, 5)) }}
                                    @if(count($log->metadata['changed_fields']) > 5)
                                        <span>...</span>
                                    @endif
                                </div>
                            @endif
                            @if($log->onboarding_action)
                                <div style="color: var(--ink-500); font-size: 12px; margin-top: 4px;">{{ $log->onboarding_action }}</div>
                            @endif
                        @elseif($log->onboarding_step)
                            <div><strong>Step:</strong> {{ $log->onboarding_step }}</div>
                            @if($log->event_type == 'signup')
                                <div><strong>Completed:</strong> {{ $log->onboarding_steps_completed ?? 0 }}/5</div>
                            @endif
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
                        <div style="word-break: break-all;">{{ $log->ip_address ?? 'N/A' }}</div>
                        @if($log->user_agent)
                            <div style="color: var(--ink-500); font-size: 11px; margin-top: 4px; word-break: break-all; white-space: normal;">
                                {{ $log->user_agent }}
                            </div>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div class="see-more-container">
            <button type="button" class="see-more-btn" id="seeMoreBtn">See More</button>
        </div>


        @else
        <div class="no-logs">
            <p>No audit logs found.</p>
        </div>
        @endif
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle See More button click - client-side expansion
        const seeMoreBtn = document.getElementById('seeMoreBtn');
        const logRows = document.querySelectorAll('.log-row');
        let visibleCount = 50; // Start with 50 visible rows

        if (seeMoreBtn && logRows.length > 0) {
            // Hide button if all rows are already visible
            if (logRows.length <= visibleCount) {
                seeMoreBtn.style.display = 'none';
            }

            seeMoreBtn.addEventListener('click', function() {
                // Show next 50 rows
                const nextBatch = Math.min(visibleCount + 50, logRows.length);

                for (let i = visibleCount; i < nextBatch; i++) {
                    if (logRows[i]) {
                        logRows[i].style.display = 'table-row';
                    }
                }

                visibleCount = nextBatch;

                // Hide button if all rows are now visible
                if (visibleCount >= logRows.length) {
                    seeMoreBtn.style.display = 'none';
                }
            });
        } else if (seeMoreBtn) {
            // No rows to show, hide button
            seeMoreBtn.style.display = 'none';
        }

        // Handle Refresh button - reload only the table
        const refreshBtn = document.getElementById('refreshBtn');
        if (refreshBtn) {
            refreshBtn.addEventListener('click', function() {
                const btn = this;
                const originalHtml = btn.innerHTML;
                btn.disabled = true;
                btn.innerHTML = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="animation: spin 1s linear infinite;"><polyline points="23 4 23 10 17 10"></polyline><polyline points="1 20 1 14 7 14"></polyline><path d="M3.51 9a9 9 0 0 1 14.85-3.36L23 10M1 14l4.64 4.36A9 9 0 0 0 20.49 15"></path></svg> Refreshing...';

                // Get current filter parameters
                const params = new URLSearchParams(window.location.search);

                // Fetch updated table content
                fetch('{{ route("admin.audit-logs.index") }}?' + params.toString(), {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html',
                    }
                })
                .then(response => response.text())
                .then(html => {
                    // Create a temporary container to parse the HTML
                    const tempDiv = document.createElement('div');
                    tempDiv.innerHTML = html;

                    // Extract the table container content
                    const newTableContainer = tempDiv.querySelector('#logsTableContainer');
                    if (newTableContainer) {
                        const currentContainer = document.getElementById('logsTableContainer');
                        const currentHtml = currentContainer.innerHTML;
                        currentContainer.innerHTML = newTableContainer.innerHTML;

                        // Reinitialize See More functionality
                        setTimeout(() => {
                            initializeSeeMore();
                        }, 100);
                    }

                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                })
                .catch(error => {
                    console.error('Error refreshing table:', error);
                    btn.disabled = false;
                    btn.innerHTML = originalHtml;
                    if (typeof Swal !== 'undefined') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to refresh the table. Please try again.',
                        });
                    } else {
                        alert('Failed to refresh the table. Please try again.');
                    }
                });
            });
        }

        // Handle Export CSV button with SweetAlert
        const exportCsvBtn = document.getElementById('exportCsvBtn');
        if (exportCsvBtn) {
            exportCsvBtn.addEventListener('click', function() {
                Swal.fire({
                    title: 'Export Audit Logs',
                    html: `
                        <div style="text-align: left; margin-top: 20px;">
                            <label style="display: block; margin-bottom: 10px; font-weight: 600;">Select number of records to export:</label>
                            <select id="exportLimit" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px;">
                                <option value="50">50 records</option>
                                <option value="100">100 records</option>
                                <option value="250">250 records</option>
                                <option value="500">500 records</option>
                                <option value="1000">1,000 records</option>
                                <option value="full">Full table (All records)</option>
                            </select>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: 'Export',
                    cancelButtonText: 'Cancel',
                    confirmButtonColor: '#10b981',
                    cancelButtonColor: '#6b7280',
                    didOpen: () => {
                        const select = document.getElementById('exportLimit');
                        select.focus();
                    },
                    preConfirm: () => {
                        const select = document.getElementById('exportLimit');
                        return select.value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const limit = result.value;
                        const params = new URLSearchParams(window.location.search);
                        params.set('limit', limit);

                        // Create and trigger download
                        window.location.href = '{{ route("admin.audit-logs.export") }}?' + params.toString();
                    }
                });
            });
        }

        function initializeSeeMore() {
            const seeMoreBtn = document.getElementById('seeMoreBtn');
            const logRows = document.querySelectorAll('.log-row');
            let visibleCount = 50;

            if (seeMoreBtn && logRows.length > 0) {
                if (logRows.length <= visibleCount) {
                    seeMoreBtn.style.display = 'none';
                } else {
                    seeMoreBtn.style.display = 'block';
                }

                seeMoreBtn.addEventListener('click', function() {
                    const nextBatch = Math.min(visibleCount + 50, logRows.length);

                    for (let i = visibleCount; i < nextBatch; i++) {
                        if (logRows[i]) {
                            logRows[i].style.display = 'table-row';
                        }
                    }

                    visibleCount = nextBatch;

                    if (visibleCount >= logRows.length) {
                        seeMoreBtn.style.display = 'none';
                    }
                });
            } else if (seeMoreBtn) {
                seeMoreBtn.style.display = 'none';
            }
        }
    });
</script>
<style>
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endsection
