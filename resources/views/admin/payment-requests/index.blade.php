@extends('layouts.admin')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --bg: #f8f9fc;
        --card: #fff;
        --ink-900: #101828;
        --ink-700: #344054;
        --ink-500: #667085;
        --border: #eaecf0;
        --shadow: 0 1px 3px rgba(16, 24, 40, 0.1), 0 1px 2px rgba(16, 24, 40, 0.06);
        --head: #2d2d2d;
    }

    .pay-shell { 
        
    }
    
    .pay-title {
        color: #101828;
        font-size: 24px;
        font-weight: 400;
        line-height: 32px;
        margin-bottom: 4px;
    }
    .pay-sub { 
        color: #667085; 
        font-size: 14px; 
        margin-bottom: 32px; 
    }

    /* Stat cards redesigned */
    .stat-row {
        margin: 0 -12px 32px;
    }
    .stat-col {
        padding: 0 12px;
    }
    .stat-card {
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 24px;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }
    .stat-top { 
        display: flex; 
        align-items: flex-start; 
        justify-content: space-between;
        margin-bottom: 8px;
    }
    .stat-label { 
        color: #667085; 
        font-size: 14px; 
        font-weight: 400;
        margin: 0; 
    }
    .stat-value { 
        font-size: 24px;
        font-weight:400; 
        color: #101828; 
        margin: 0 0 12px 0; 
    }
    .stat-trend { 
        font-size: 14px; 
        color: #12b76a; 
        font-weight: 500;
        margin: 0; 
        display: flex; 
        align-items: center; 
        gap: 4px; 
    }
    .stat-icon { 
        width: 40px; 
        height: 40px; 
        border-radius: 8px; 
        display: grid; 
        place-items: center; 
        background: #f2f4f7; 
        color: #344054; 
        font-size: 18px; 
    }
    .stat-icon i { color: #344054; }

    .panel { 
        background: #fff; 
        border: 1px solid var(--border); 
        border-radius: 12px; 
        box-shadow: var(--shadow); 
        overflow: hidden; 
    }
    .panel-header-section {
        padding: 24px;
        border-bottom: 1px solid var(--border);
    }
    .panel-title { 
        margin: 0 0 16px 0; 
        color: #101828; 
        font-weight: 600; 
        font-size: 18px;
    }
    
    .filter-dropdown { position: relative; }
    .filter-toggle { 
        border: 1px solid var(--border); 
        background: #fff; 
        border-radius: 8px; 
        padding: 10px 16px; 
        font-size: 14px; 
        color: #344054; 
        display: inline-flex; 
        align-items: center; 
        gap: 8px;
        font-weight: 500;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.05);
    }
    .filter-menu { 
        position: absolute; 
        left: 0; 
        top: 110%; 
        min-width: 200px; 
        background: #fff; 
        border: 1px solid var(--border); 
        border-radius: 8px; 
        box-shadow: 0 12px 16px -4px rgba(16, 24, 40, 0.08), 0 4px 6px -2px rgba(16, 24, 40, 0.03); 
        padding: 4px 0; 
        z-index: 100; 
        display: none; 
    }
    .filter-menu a { display: block; padding: 10px 16px; font-size: 14px; color: #344054; text-decoration: none; }
    .filter-menu a:hover { background: #f9fafb; }
    .filter-menu a.active { background: #f9fafb; color: #101828; font-weight: 600; }

    .table-wrap { overflow-x: auto; }
    .pay-table { width: 100%; border-collapse: collapse; font-size: 14px; }
    .pay-table thead th { 
        background: #323232; 
        color: #fff; 
        padding: 16px; 
        font-weight: 500; 
        border: none; 
        white-space: nowrap; 
        text-align: left;
    }
    .pay-table tbody td { 
        padding: 16px; 
        border-bottom: 1px solid var(--border); 
        color: #475467; 
        vertical-align: middle; 
    }

    .talent-cell { display: flex; flex-direction: column; gap: 2px; }
    .talent-name { color: #101828; font-weight: 500; margin: 0; font-size: 14px; }
    .talent-sub { color: #667085; font-size: 13px; margin: 0; }
    .project-name { color: #475467; font-weight: 400; }
    
    .rating-stars { color: #101828; font-size: 10px; margin-top: 2px; }
    
    .amount { color: #12b76a; font-weight: 600; }

    .action-row { display: flex; gap: 8px; align-items: center; }
    .btn-pill { 
        border: none; 
        border-radius: 8px; 
        padding: 8px 14px; 
        font-size: 13px; 
        font-weight: 600; 
        color: #fff; 
        cursor: pointer; 
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
        text-decoration: none !important;
    }
    .btn-pill:hover { 
        color: #fff !important; 
        text-decoration: none;
        opacity: 0.9;
    }
    .btn-approve { background: #12b76a; }
    .btn-approve:hover { background: #12b76a; }
    .btn-release { background: #12b76a; }
    .btn-release:hover { background: #12b76a; }
    .btn-reject { background: #f04438; }
    .btn-reject:hover { background: #f04438; }
    .btn-view { background: #f2f4f7; color: #344054; }
    .btn-view:hover { background: #e5e7eb; color: #344054 !important; }

    .table-foot { padding: 16px 24px; color: #667085; font-size: 14px; display: flex; justify-content: space-between; align-items: center; border-top: 1px solid var(--border); }
    
    /* Responsive tweaks */
    @media (max-width: 1024px) {
        .stat-col { margin-bottom: 16px; }
    }
</style>

<div class="pay-shell">
    <h1 class="pay-title">Payment Requests Management</h1>
    <p class="pay-sub">Approve and release payments to talents.</p>

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

    <div class="row stat-row">
        <div class="col-md-3 stat-col">
            <div class="stat-card">
                <div class="stat-top">
                    <p class="stat-label">Pending Approval</p>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <p class="stat-value">{{ $stats['total_requested'] }}</p>
                <p class="stat-trend"><i class="fas fa-arrow-up"></i> +12% this week</p>
            </div>
        </div>
        <div class="col-md-3 stat-col">
            <div class="stat-card">
                <div class="stat-top">
                    <p class="stat-label">Approved</p>
                    <div class="stat-icon"><i class="fas fa-check"></i></div>
                </div>
                <p class="stat-value">{{ $stats['total_approved'] }}</p>
                <p class="stat-trend"><i class="fas fa-arrow-up"></i> +8% vs last week</p>
            </div>
        </div>
        <div class="col-md-3 stat-col">
            <div class="stat-card">
                <div class="stat-top">
                    <p class="stat-label">Released</p>
                    <div class="stat-icon"><i class="fas fa-check"></i></div>
                </div>
                <p class="stat-value">{{ $stats['total_released'] }}</p>
                <p class="stat-trend"><i class="fas fa-arrow-up"></i> +15% this month</p>
            </div>
        </div>
        <div class="col-md-3 stat-col">
            <div class="stat-card">
                <div class="stat-top">
                    <p class="stat-label">Total Pending Amount</p>
                    <div class="stat-icon"><i class="fas fa-dollar-sign"></i></div>
                </div>
                <p class="stat-value">${{ number_format($stats['total_amount_pending'], 2) }}</p>
                <p class="stat-trend"><i class="fas fa-arrow-up"></i> +23% vs last month</p>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header-section">
            <h2 class="panel-title">Payment Requests</h2>
            @php
                $statusOptions = collect($paymentRequests)->pluck('payment_status')->filter()->unique();
                $currentStatus = request('status');
            @endphp
            <div class="filter-dropdown" id="statusDropdown">
                <button type="button" class="filter-toggle">
                    <i class="fas fa-filter" style="font-size:12px; color:#667085;"></i>
                    <span>Status</span>
                    <i class="fas fa-chevron-down" style="font-size:10px; color:#667085;"></i>
                </button>
                <div class="filter-menu">
                    <a href="{{ route('admin.payment-requests.index') }}" class="{{ !$currentStatus ? 'active' : '' }}">All Statuses</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'requested']) }}" class="{{ $currentStatus === 'requested' ? 'active' : '' }}">Awaiting Approval</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'approved']) }}" class="{{ $currentStatus === 'approved' ? 'active' : '' }}">Ready to Release</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'released']) }}" class="{{ $currentStatus === 'released' ? 'active' : '' }}">Released</a>
                    <a href="{{ route('admin.payment-requests.index', ['status' => 'received']) }}" class="{{ $currentStatus === 'received' ? 'active' : '' }}">Completed</a>
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
                                            <button class="btn-pill btn-approve" type="submit"><i class="fas fa-check"></i> Approve</button>
                                        </form>
                                        <button class="btn-pill btn-reject" type="button" data-toggle="modal" data-target="#rejectModal{{ $application->id }}"><i class="fas fa-times"></i> Reject</button>
                                    </div>
                                @elseif($statusKey === 'approved')
                                    <div class="action-row">
                                        <a class="btn-pill btn-release" href="{{ route('admin.payment-requests.release-form', $application) }}"><i class="fas fa-check"></i> Release Payment</a>
                                        <button class="btn-pill btn-reject" type="button" data-toggle="modal" data-target="#rejectModal{{ $application->id }}"><i class="fas fa-times"></i> Reject</button>
                                    </div>
                                @elseif($statusKey === 'released')
                                    <span class="btn-pill btn-approve" style="background: #ecfdf3; color: #12b76a;"><i class="fas fa-check"></i> Paid</span>
                                @elseif($statusKey === 'received')
                                    <span class="btn-pill btn-approve" style="background: #ecfdf3; color: #12b76a;"><i class="fas fa-check"></i> Completed</span>
                                @else
                                    <span class="btn-pill btn-view">No action needed</span>
                                @endif
                                <div style="margin-top:8px;">
                                    <a class="btn-pill btn-view" href="{{ route('admin.payment-requests.show', $application) }}">View Details</a>
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
