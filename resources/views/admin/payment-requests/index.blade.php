@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f7f8fb;
        --card: #fff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e5e7eb;
        --shadow: 0 16px 36px rgba(15,23,42,0.08);
        --head: #1f2228;
    }

    body { background: var(--bg); }

    .pay-shell { padding: 8px 0 20px; }
    .pay-title {
color: #101828;
font-size: 24px;
font-style: normal;
font-weight: 400;
line-height: 36px; /* 150% */}
    .pay-sub { margin: 2px 0 18px; color: var(--ink-500); font-size: 13px; }

    /* Stat cards styled to match reference mock */
    .stat-row .card { border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow); }
    .stat-card { display: flex; flex-direction: column; gap: 8px; }
    .stat-top { display: flex; align-items: center; justify-content: space-between; }
    .stat-label { color: var(--ink-500); font-size: 13px; margin: 0; }
    .stat-value { font-weight: 800; color: var(--ink-900); margin: 0; }
    .stat-trend { font-size: 12px; color: #16a34a; margin: 0; display: flex; align-items: center; gap: 6px; }
    .stat-icon { width: 34px; height: 34px; border-radius: 50%; display: grid; place-items: center; background: #eef1f5; color: #7b8191; font-size: 14px; }
    .stat-icon.success { color: #16a34a; background: #e9f7ee; }
    .stat-icon.info { color: #1e88e5; background: #e7f3ff; }

    .panel { background: var(--card); border: 1px solid var(--border); border-radius: 14px; box-shadow: var(--shadow); overflow: hidden; }
    .panel-head { padding: 14px 16px; display: flex; align-items: center; justify-content: space-between; border-bottom: 1px solid var(--border); gap: 10px; flex-wrap: wrap; }
    .panel-title { margin: 0; color: var(--ink-900); font-weight: 700; }
    .filter-dropdown { position: relative; }
    .filter-toggle { border: 1px solid var(--border); background: #fff; border-radius: 8px; padding: 8px 12px; font-size: 13px; color: var(--ink-700); min-width: 150px; display: inline-flex; align-items: center; justify-content: space-between; gap: 8px; }
    .filter-menu { position: absolute; right: 0; top: 110%; min-width: 180px; background: #fff; border: 1px solid var(--border); border-radius: 10px; box-shadow: var(--shadow); padding: 6px 0; z-index: 10; display: none; }
    .filter-menu a { display: block; padding: 8px 12px; font-size: 13px; color: var(--ink-700); text-decoration: none; }
    .filter-menu a:hover { background: #f4f5f7; }
    .filter-menu a.active { background: #e7f3ff; color: #1e88e5; font-weight: 700; }

    .table-wrap { overflow-x: auto; }
    .pay-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .pay-table thead th { background: var(--head); color: #fff; padding: 12px 14px; font-weight: 600; border: none; white-space: nowrap; }
    .pay-table tbody td { padding: 14px; border-bottom: 1px solid #eef1f5; color: var(--ink-700); vertical-align: middle; }

    .talent-cell { display: flex; flex-direction: column; gap: 4px; }
    .talent-name { color: var(--ink-900); font-weight: 700; margin: 0; }
    .talent-sub { color: var(--ink-500); font-size: 12px; margin: 0; }
    .feedback { color: var(--ink-700); font-size: 12px; }
    .stars { color: #111; font-size: 12px; letter-spacing: 1px; }
    .amount { color: #17a864; font-weight: 700; }

    .pill { display: inline-flex; align-items: center; gap: 6px; padding: 6px 10px; border-radius: 12px; font-weight: 700; font-size: 12px; border: 1px solid transparent; }
    .pill-approve { background: #e3f7ed; color: #16a34a; border-color: #c6e8d7; }
    .pill-reject { background: #fde8e8; color: #b91c1c; border-color: #f3c3c3; }
    .pill-release { background: #e5f4ff; color: #1e88e5; border-color: #c7e4ff; }
    .pill-paid { background: #e7f2eb; color: #2e7d32; border-color: #c8e0ce; }
    .pill-muted { background: #f1f2f4; color: #6b7280; border-color: #e1e3e6; }

    .action-row { display: inline-flex; gap: 8px; flex-wrap: wrap; }
    .btn-mini { border: none; border-radius: 10px; padding: 6px 10px; font-size: 12px; font-weight: 700; color: #fff; cursor: pointer; box-shadow: 0 6px 14px rgba(0,0,0,0.1); }
    .btn-approve { background: #16c784; }
    .btn-reject { background: #f05d5e; }
    .btn-release { background: #2f80ed; }
    .btn-view { background: #f1f2f4; color: #0f1524; box-shadow: none; }

    .table-foot { padding: 12px 14px; color: var(--ink-500); font-size: 12px; display: flex; justify-content: space-between; align-items: center; }
    .pager { display: inline-flex; gap: 6px; align-items: center; }
    .pager .page-dot { width: 26px; height: 26px; border-radius: 6px; border: 1px solid var(--border); display: grid; place-items: center; color: var(--ink-700); background: #fff; }
    .pager .active { background: #000; color: #fff; border-color: #000; }

    @media (max-width: 900px) {
        .pay-table thead { display: none; }
        .pay-table tbody tr { display: block; margin-bottom: 14px; border: 1px solid var(--border); border-radius: 10px; padding: 10px; }
        .pay-table tbody td { display: flex; justify-content: space-between; border: none; padding: 8px 0; }
        .pay-table tbody td::before { content: attr(data-label); font-weight: 700; color: var(--ink-900); padding-right: 10px; }
    }
</style>

<div class="pay-shell">
    <h5 class="pay-title">Payment Requests Management</h5>
    <div class="pay-sub">Approve and release payments to talents.</div>

    @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    @endif

    <div class="row mb-4 stat-row">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Pending Approval</p>
                        <div class="stat-icon"><i class="far fa-file-alt"></i></div>
                    </div>
                    <p class="stat-value text-warning mb-0">{{ $stats['total_requested'] }}</p>
                    <p class="stat-trend"><span style="font-size:14px;">&#8593;</span> +12% this week</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Approved</p>
                        <div class="stat-icon success"><i class="far fa-check-circle"></i></div>
                    </div>
                    <p class="stat-value text-primary mb-0">{{ $stats['total_approved'] }}</p>
                    <p class="stat-trend"><span style="font-size:14px;">&#8593;</span> +6% vs last week</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Released</p>
                        <div class="stat-icon info"><i class="far fa-check-circle"></i></div>
                    </div>
                    <p class="stat-value text-info mb-0">{{ $stats['total_released'] }}</p>
                    <p class="stat-trend"><span style="font-size:14px;">&#8593;</span> +15% this month</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body stat-card">
                    <div class="stat-top">
                        <p class="stat-label">Total Pending Amount</p>
                        <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                    <p class="stat-value text-success mb-0">${{ number_format($stats['total_amount_pending'], 2) }}</p>
                    <p class="stat-trend"><span style="font-size:14px;">&#8593;</span> +23% vs last month</p>
                </div>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h6 class="panel-title">Payment Requests</h6>
            @php
                $statusOptions = collect($paymentRequests)->pluck('payment_status')->filter()->unique();
                $currentStatus = request('status');
            @endphp
            <div class="filter-dropdown" id="statusDropdown">
                <button type="button" class="filter-toggle">
                    <span>Status</span>
                    <i class="fas fa-chevron-down" style="font-size:11px;"></i>
                </button>
                <div class="filter-menu">
                    <a href="{{ route('admin.payment-requests.index') }}" class="{{ !$currentStatus ? 'active' : '' }}">All</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'requested']) }}" class="{{ $currentStatus === 'requested' ? 'active' : '' }}">Awaiting Approval</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'approved']) }}" class="{{ $currentStatus === 'approved' ? 'active' : '' }}">Ready to Release</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'released']) }}" class="{{ $currentStatus === 'released' ? 'active' : '' }}">Released</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'received']) }}" class="{{ $currentStatus === 'received' ? 'active' : '' }}">Completed</a>
                    @foreach($statusOptions as $statusOption)
                        @if(!in_array($statusOption, ['requested','approved','released','received']))
                            <a href="{{ route('admin.payment-requests.index', ['status' => $statusOption]) }}" class="{{ $currentStatus === $statusOption ? 'active' : '' }}">{{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$statusOption] ?? ucfirst($statusOption) }}</a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="table-wrap">
            <table class="pay-table">
                <thead>
                    <tr>
                        <th>Talent</th>
                        <th>Project</th>
                        <th>Requested By Admin</th>
                        <th>Feedback</th>
                        <th>Amount</th>
                        <th>Requested Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($paymentRequests as $application)
                        @php
                            $talentName = optional(optional($application->talent_profile)->user)->name ?? '—';
                            $talentEmail = optional(optional($application->talent_profile)->user)->email ?? optional($application->talent_profile)->email ?? '';
                            $projectName = optional($application->casting_requirement)->project_name ?? '—';
                            $requestedBy = $application->requestedByAdmin;
                            $rating = $application->rating;
                            $review = $application->reviews;
                            $amount = $application->getPaymentAmount();
                            $requestedAt = $application->payment_requested_at;
                            $statusKey = $application->payment_status;
                        @endphp
                        <tr data-status="{{ $statusKey }}">
                            <td data-label="Talent">
                                <div class="talent-cell">
                                    <span class="talent-name">{{ $talentName }}</span>
                                    @if($talentEmail)
                                        <span class="talent-sub">{{ $talentEmail }}</span>
                                    @endif
                                    @if(optional($application->talent_profile)->getMaskedCardNumber())
                                        <span class="talent-sub">Card: {{ optional($application->talent_profile)->getMaskedCardNumber() }}</span>
                                    @endif
                                </div>
                            </td>
                            <td data-label="Project">{{ $projectName }}</td>
                            <td data-label="Requested By Admin">
                                @if($requestedBy)
                                    <div class="talent-name" style="font-size:13px; font-weight:600;">{{ $requestedBy->name }}</div>
                                    <div class="talent-sub">{{ $requestedBy->email }}</div>
                                @else
                                    <span class="talent-sub">Direct Request</span>
                                @endif
                            </td>
                            <td data-label="Feedback">
                                @if($review)
                                    <div class="feedback">{{ \Illuminate\Support\Str::limit($review, 40) }}</div>
                                @endif
                                @if($rating)
                                    <div class="stars">
                                        @for($i=1;$i<=5;$i++)
                                            {!! $i <= $rating ? '&#9733;' : '&#9734;' !!}
                                        @endfor
                                    </div>
                                @endif
                                @if(!$rating && !$review)
                                    <span class="talent-sub">No feedback</span>
                                @endif
                            </td>
                            <td data-label="Amount" class="amount">${{ number_format($amount, 2) }}</td>
                            <td data-label="Requested Date">
                                @if($requestedAt)
                                    {{ $requestedAt->format('M d, Y') }}<br>
                                    <span class="talent-sub">{{ $requestedAt->diffForHumans() }}</span>
                                @else
                                    <span class="talent-sub">—</span>
                                @endif
                            </td>
                            <td data-label="Status">
                                @if($statusKey === 'requested')
                                    <div class="action-row">
                                        <form action="{{ route('admin.payment-requests.approve', $application) }}" method="POST">
                                            @csrf
                                            <button class="btn-mini btn-approve" type="submit">Approve</button>
                                        </form>
                                        <button class="btn-mini btn-reject" type="button" data-toggle="modal" data-target="#rejectModal{{ $application->id }}">Reject</button>
                                    </div>
                                @elseif($statusKey === 'approved')
                                    <div class="action-row">
                                        <a class="btn-mini btn-release" href="{{ route('admin.payment-requests.release-form', $application) }}">Release Payment</a>
                                    </div>
                                @elseif($statusKey === 'released')
                                    <span class="pill pill-paid">Paid</span>
                                @elseif($statusKey === 'received')
                                    <span class="pill pill-approve">Completed</span>
                                @else
                                    <span class="pill pill-muted">No action needed</span>
                                @endif
                                <div style="margin-top:6px;">
                                    <a class="btn-mini btn-view" href="{{ route('admin.payment-requests.show', $application) }}" style="color: var(--ink-700);">View</a>
                                </div>
                            </td>
                        </tr>

                        <!-- Reject Modal -->
                        <div class="modal fade" id="rejectModal{{ $application->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <form action="{{ route('admin.payment-requests.reject', $application) }}" method="POST">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title">Reject Payment Request</h5>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="form-group">
                                                <label for="rejection_reason">Rejection Reason</label>
                                                <textarea name="rejection_reason" class="form-control" rows="3" required placeholder="Explain why this payment is being rejected..."></textarea>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-danger">Reject Payment</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align:center; padding:22px; color: var(--ink-500);">No payment requests found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="table-foot">
            <div>Showing {{ $paymentRequests->count() }} of {{ $paymentRequests->total() ?? $paymentRequests->count() }} entries</div>
            @if($paymentRequests->hasPages())
                <div class="pager">
                    {{ $paymentRequests->links() }}
                </div>
            @endif
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdown = document.getElementById('statusDropdown');
        const toggle = dropdown?.querySelector('.filter-toggle');
        const menu = dropdown?.querySelector('.filter-menu');

        toggle?.addEventListener('click', function () {
            const isOpen = menu?.style.display === 'block';
            if (menu) menu.style.display = isOpen ? 'none' : 'block';
        });

        document.addEventListener('click', function (e) {
            if (!dropdown?.contains(e.target)) {
                if (menu) menu.style.display = 'none';
            }
        });
    });
</script>
@endsection
