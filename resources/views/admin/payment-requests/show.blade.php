@extends('layouts.admin')

@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card: #ffffff;
        --ink-900: #101828;
        --ink-700: #344054;
        --ink-500: #667085;
        --border: #eaecf0;
        --shadow: 0 1px 2px rgba(16, 24, 40, 0.04), 0 8px 18px rgba(16, 24, 40, 0.06);
    }

    .pr-shell { padding: 22px 0; font-family: 'Arimo', sans-serif; }
    .pr-header {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 18px;
    }
    .pr-title { margin: 0 0 4px 0; color: var(--ink-900); font-size: 24px; font-weight: 400; line-height: 32px; }
    .pr-sub { margin: 0; color: var(--ink-500); font-size: 14px; }
    .pr-back {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-700);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
    }
    .pr-back:hover { background: #f9fafb; color: var(--ink-900); text-decoration: none; }

    .pr-grid { display: grid; grid-template-columns: 2fr 1fr; gap: 16px; align-items: start; }
    @media (max-width: 992px) { .pr-grid { grid-template-columns: 1fr; } }

    .pr-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 16px;
        margin-bottom: 16px;
    }
    .pr-card-head { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .pr-card-title { margin: 0; font-size: 16px; font-weight: 700; color: var(--ink-900); }
    .pr-divider { border-top: 1px solid var(--border); margin: 14px 0; }

    .pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        border: 1px solid transparent;
        white-space: nowrap;
    }
    .pill-requested { background: #e0eaff; color: #444ce7; border-color: #c7d2fe; }
    .pill-approved { background: #ecfdf3; color: #027a48; border-color: #abefc6; }
    .pill-released { background: #f4ebff; color: #6941c6; border-color: #e9d7fe; }
    .pill-received { background: #ecfdf3; color: #027a48; border-color: #abefc6; }
    .pill-rejected { background: #fee4e2; color: #b42318; border-color: #fecdca; }
    .pill-pending { background: #fff8e6; color: #b54708; border-color: #fedf89; }
    .pill-default { background: #f2f4f7; color: #344054; border-color: #eaecf0; }

    .kv-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 16px; }
    @media (max-width: 576px) { .kv-grid { grid-template-columns: 1fr; } }
    .kv-label { color: var(--ink-500); font-size: 12px; font-weight: 600; margin-bottom: 4px; }
    .kv-value { color: var(--ink-900); font-size: 14px; font-weight: 600; }
    .kv-sub { color: var(--ink-500); font-size: 12px; margin-top: 2px; }
    .kv-link { color: var(--ink-900); text-decoration: none; }
    .kv-link:hover { text-decoration: underline; color: var(--ink-900); }

    .amount { color: #12b76a; font-weight: 800; font-size: 18px; }

    .stars { display: inline-flex; align-items: center; gap: 2px; color: #f59e0b; font-size: 14px; }
    .stars .empty { color: #d1d5db; }
    .rating-text { color: var(--ink-500); font-size: 12px; font-weight: 600; margin-left: 8px; }
    .review-box {
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 14px;
        color: var(--ink-700);
        font-size: 14px;
        line-height: 1.6;
        margin-top: 10px;
    }
    .review-meta { margin-top: 10px; color: var(--ink-500); font-size: 12px; }

    .pr-sticky { position: sticky; top: 18px; }
    @media (max-width: 992px) { .pr-sticky { position: static; } }
    .action-btn {
        width: 100%;
        border: none;
        border-radius: 10px;
        padding: 12px 14px;
        font-size: 14px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none !important;
    }
    .action-approve { background: #12b76a; color: #fff; }
    .action-approve:hover { background: #0ea463; color: #fff; }
    .action-reject { background: #fff; color: #b42318; border: 1px solid #fecdca; }
    .action-reject:hover { background: #fff5f4; color: #b42318; }
    .action-primary { background: #000; color: #fff; }
    .action-primary:hover { background: #111; color: #fff; }

    .note-box {
        border: 1px solid var(--border);
        background: #f9fafb;
        border-radius: 12px;
        padding: 12px 14px;
        color: var(--ink-700);
        font-size: 13px;
        line-height: 1.5;
    }
</style>

@php
    $projectName = optional($castingApplication->casting_requirement)->project_name ?? 'N/A';
    $talentName = optional(optional($castingApplication->talent_profile)->user)->name ?? 'N/A';
    $talentProfileId = optional($castingApplication->talent_profile)->id;
    $requestedBy = $castingApplication->requestedByAdmin;
    $statusKey = (string) ($castingApplication->payment_status ?? 'pending');
    $statusLabel = \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$statusKey] ?? ucfirst($statusKey);
    $pillClass = match($statusKey) {
        'requested' => 'pill-requested',
        'approved' => 'pill-approved',
        'released' => 'pill-released',
        'received' => 'pill-received',
        'rejected' => 'pill-rejected',
        'pending' => 'pill-pending',
        default => 'pill-default',
    };
    $amount = $castingApplication->getPaymentAmount();
    $rating = $castingApplication->rating;
    $review = $castingApplication->reviews;
@endphp

<div class="pr-shell">
    <div class="pr-header">
        <div>
            <h1 class="pr-title">{{ __('Payment Request Details') }}</h1>
            <p class="pr-sub">{{ __('Review the request, rating, and review before acting.') }}</p>
        </div>
        <a href="{{ route('admin.payment-requests.index') }}" class="pr-back">
            <i class="fas fa-arrow-left"></i>{{ __('Back to list') }}
        </a>
    </div>

    <div class="pr-grid">
        <div>
            <div class="pr-card">
                <div class="pr-card-head">
                    <h3 class="pr-card-title">{{ __('Overview') }}</h3>
                    <span class="pill {{ $pillClass }}">{{ $statusLabel }}</span>
                </div>

                <div class="kv-grid">
                    <div>
                        <div class="kv-label">{{ __('Project') }}</div>
                        <div class="kv-value">{{ $projectName }}</div>
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Amount') }}</div>
                        <div class="kv-value amount">${{ number_format($amount, 2) }}</div>
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Talent') }}</div>
                        <div class="kv-value">
                            @if($talentProfileId)
                                <a class="kv-link" href="{{ route('admin.talent-profiles.show', $talentProfileId) }}">{{ $talentName }}</a>
                            @else
                                {{ $talentName }}
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Requested By') }}</div>
                        <div class="kv-value">
                            @if($requestedBy)
                                {{ $requestedBy->name }}
                                <div class="kv-sub">{{ $requestedBy->email }}</div>
                            @else
                                <span class="kv-sub">{{ __('Direct Request') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="pr-divider"></div>

                <div class="kv-grid">
                    <div>
                        <div class="kv-label">{{ __('Requested At') }}</div>
                        <div class="kv-value">{{ optional($castingApplication->payment_requested_at)->format('M d, Y H:i') ?? '—' }}</div>
                        @if($castingApplication->payment_requested_at)
                            <div class="kv-sub">{{ $castingApplication->payment_requested_at->diffForHumans() }}</div>
                        @endif
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Approval Date') }}</div>
                        <div class="kv-value">{{ optional($castingApplication->payment_approved_at)->format('M d, Y H:i') ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Release Date') }}</div>
                        <div class="kv-value">{{ optional($castingApplication->payment_released_at)->format('M d, Y H:i') ?? '—' }}</div>
                    </div>
                    <div>
                        <div class="kv-label">{{ __('Received Date') }}</div>
                        <div class="kv-value">{{ optional($castingApplication->payment_received_at)->format('M d, Y H:i') ?? '—' }}</div>
                    </div>
                </div>
            </div>

            <div class="pr-card">
                <div class="pr-card-head">
                    <h3 class="pr-card-title">{{ __('Client Feedback') }}</h3>
                </div>

                @if($rating)
                    <div style="display:flex; align-items:center; gap:10px; margin-bottom: 6px;">
                        <div class="stars" aria-label="Rating">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="{{ $i <= $rating ? 'fas' : 'far' }} fa-star {{ $i <= $rating ? '' : 'empty' }}"></i>
                            @endfor
                        </div>
                        <span class="rating-text">{{ number_format((float) $rating, 1) }}/5</span>
                    </div>
                @else
                    <div class="note-box">{{ __('No rating submitted with this request.') }}</div>
                @endif

                @if($review)
                    <div class="review-box">{{ $review }}</div>
                    <div class="review-meta">
                        {{ __('Submitted by') }}
                        <strong>{{ optional($castingApplication->requestedByAdmin)->name ?? __('Unknown Admin') }}</strong>
                    </div>
                @endif
            </div>
        </div>

        <div>
            <div class="pr-card pr-sticky">
                <div class="pr-card-head">
                    <h3 class="pr-card-title">{{ __('Next Actions') }}</h3>
                </div>

                @if($castingApplication->payment_status === 'requested')
                    <form action="{{ route('admin.payment-requests.approve', $castingApplication) }}" method="POST" class="mb-2">
                        @csrf
                        <button type="submit" class="action-btn action-approve" onclick="return confirm('{{ __('Approve this payment request?') }}')">
                            <i class="fas fa-check"></i>{{ __('Approve Request') }}
                        </button>
                    </form>
                    <button class="action-btn action-reject" type="button" data-toggle="modal" data-target="#showRejectModal">
                        <i class="fas fa-times"></i>{{ __('Reject Request') }}
                    </button>
                @elseif($castingApplication->payment_status === 'approved')
                    <a href="{{ route('admin.payment-requests.release-form', $castingApplication) }}" class="action-btn action-primary">
                        <i class="fab fa-stripe"></i>{{ __('Release via Stripe') }}
                    </a>
                @elseif($castingApplication->payment_status === 'released')
                    <div class="note-box">
                        <strong>{{ __('Payment released') }}.</strong>
                        {{ __('Awaiting confirmation from the talent.') }}
                    </div>
                @elseif($castingApplication->payment_status === 'received')
                    <div class="note-box" style="background:#ecfdf3; border-color:#abefc6; color:#027a48;">
                        <strong>{{ __('Payment completed successfully.') }}</strong>
                    </div>
                @elseif($castingApplication->payment_status === 'rejected')
                    <div class="note-box" style="background:#fff5f4; border-color:#fecdca; color:#b42318;">
                        <strong>{{ __('Payment request rejected.') }}</strong>
                        @if($castingApplication->payment_rejection_reason)
                            <div style="margin-top:8px; font-weight:500;">{{ $castingApplication->payment_rejection_reason }}</div>
                        @endif
                    </div>
                @else
                    <div class="note-box">{{ __('No further actions needed at this time.') }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="modal fade" id="showRejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.payment-requests.reject', $castingApplication) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Reject Payment Request') }}</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="rejection_reason">{{ __('Rejection Reason') }}</label>
                            <textarea name="rejection_reason" id="rejection_reason" class="form-control" rows="4" required placeholder="{{ __('Explain why you are rejecting this payment.') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-danger">{{ __('Reject Payment') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

