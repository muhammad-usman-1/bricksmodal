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

    .log-detail-shell { padding: 8px 0 80px; }
    .log-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .log-header h2 {
        color: black;
        font-family: 'Arimo', sans-serif;
        font-size: 24px;
        font-weight: 400;
        line-height: 36px;
        margin: 0;
    }
    .back-link {
        color: var(--ink-700);
        text-decoration: none;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .back-link:hover {
        color: var(--ink-900);
    }

    .detail-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        box-shadow: var(--shadow);
        margin-bottom: 20px;
    }
    .detail-row {
        display: grid;
        grid-template-columns: 200px 1fr;
        gap: 16px;
        padding: 16px 0;
        border-bottom: 1px solid var(--border);
    }
    .detail-row:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: var(--ink-700);
        font-size: 14px;
    }
    .detail-value {
        color: var(--ink-900);
        font-size: 14px;
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
</style>

<div class="log-detail-shell">
    <div class="log-header">
        <h2>Audit Log Details</h2>
        <a href="{{ route('admin.audit-logs.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Logs
        </a>
    </div>

    <div class="detail-card">
        <div class="detail-row">
            <div class="detail-label">Event Type</div>
            <div class="detail-value">
                @if($auditLog->event_type == 'login_success')
                    <span class="badge badge-success">Login Success</span>
                @elseif($auditLog->event_type == 'login_failed')
                    <span class="badge badge-danger">Login Failed</span>
                @elseif($auditLog->event_type == 'logout')
                    <span class="badge badge-info">Logout</span>
                @elseif($auditLog->event_type == 'onboarding_step')
                    <span class="badge badge-warning">Onboarding Step</span>
                @elseif($auditLog->event_type == 'onboarding_completed')
                    <span class="badge badge-success">Onboarding Completed</span>
                @elseif($auditLog->event_type == 'signup')
                    <span class="badge badge-info">Signup</span>
                @elseif($auditLog->event_type == 'talent_accepted')
                    <span class="badge badge-success">Talent Accepted</span>
                @elseif($auditLog->event_type == 'profile_updated')
                    <span class="badge badge-warning">Profile Updated</span>
                @else
                    <span class="badge">{{ $auditLog->event_type }}</span>
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">User Type</div>
            <div class="detail-value">
                <span style="text-transform: capitalize; font-weight: 600;">{{ $auditLog->user_type }}</span>
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">User ID</div>
            <div class="detail-value">{{ $auditLog->user_id ?? 'N/A' }}</div>
        </div>
        @if($auditLog->user && $auditLog->user->name)
        <div class="detail-row">
            <div class="detail-label">Name</div>
            <div class="detail-value">{{ $auditLog->user->name }}</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">Email</div>
            <div class="detail-value">{{ $auditLog->user_email ?? 'N/A' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Phone</div>
            <div class="detail-value">
                @if($auditLog->user_phone)
                    +965 {{ $auditLog->user_phone }}
                @else
                    N/A
                @endif
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">Timestamp</div>
            <div class="detail-value">
                {{ $auditLog->created_at->setTimezone('Asia/Kuwait')->format('F d, Y h:i:s A') }} GMT+3
            </div>
        </div>
        <div class="detail-row">
            <div class="detail-label">IP Address</div>
            <div class="detail-value" style="word-break: break-all;">{{ $auditLog->ip_address ?? 'N/A' }}</div>
        </div>
        <div class="detail-row">
            <div class="detail-label">User Agent</div>
            <div class="detail-value" style="word-break: break-all; white-space: normal;">{{ $auditLog->user_agent ?? 'N/A' }}</div>
        </div>
        @if($auditLog->onboarding_step)
        <div class="detail-row">
            <div class="detail-label">Onboarding Step</div>
            <div class="detail-value">{{ $auditLog->onboarding_step }}</div>
        </div>
        @if($auditLog->event_type == 'signup')
        <div class="detail-row">
            <div class="detail-label">Steps Completed</div>
            <div class="detail-value">{{ $auditLog->onboarding_steps_completed ?? 0 }}/5</div>
        </div>
        @endif
        <div class="detail-row">
            <div class="detail-label">Onboarding Completed</div>
            <div class="detail-value">
                @if($auditLog->onboarding_completed)
                    <span class="badge badge-success">Yes</span>
                @else
                    <span class="badge badge-warning">No</span>
                @endif
            </div>
        </div>
        @if($auditLog->onboarding_action)
        <div class="detail-row">
            <div class="detail-label">Action</div>
            <div class="detail-value">{{ $auditLog->onboarding_action }}</div>
        </div>
        @endif
        @endif
        @if($auditLog->login_failure_reason)
        <div class="detail-row">
            <div class="detail-label">Failure Reason</div>
            <div class="detail-value" style="color: #dc2626;">{{ $auditLog->login_failure_reason }}</div>
        </div>
        @endif
        @if($auditLog->metadata)
        <div class="detail-row">
            <div class="detail-label">Additional Data</div>
            <div class="detail-value">
                <pre style="background: #f9fafb; padding: 12px; border-radius: 8px; font-size: 12px; overflow-x: auto;">{{ json_encode($auditLog->metadata, JSON_PRETTY_PRINT) }}</pre>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection