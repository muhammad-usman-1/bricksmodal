@extends('layouts.talent')

@section('content')
@php
    // Calculate summary stats
    $availableBalance = $applications->where('payment_status', 'received')->sum(function($app) {
        return $app->rate_offered ?? $app->rate ?? 0;
    });

    $pendingAmount = $applications->whereIn('payment_status', ['pending', 'requested', 'approved', 'released'])->sum(function($app) {
        return $app->rate_offered ?? $app->rate ?? 0;
    });

    $totalEarned = $applications->sum(function($app) {
        return $app->rate_offered ?? $app->rate ?? 0;
    });

    // Get status filter
    $statusFilter = request('status', 'all');

    // Filter applications by status
    $filteredApplications = $applications;
    if ($statusFilter !== 'all') {
        $filteredApplications = $applications->where('payment_status', $statusFilter);
    }

    // Get payment method - for now using placeholder logic
    function getPaymentMethod($application) {
        // This would come from actual payment data
        if ($application->payment_status === 'received') {
            return 'Bank Transfer'; // or PayPal based on actual data
        } elseif ($application->payment_status === 'pending') {
            return 'Pending';
        }
        return 'Bank Transfer';
    }
@endphp

<style>
    .payments-page {



    }

    .payments-container {

        margin: 0 auto;
        
    }

    .payments-header {
        margin-bottom: 32px;
    }

    .payments-title {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .payments-subtitle {
        font-size: 15px;
        color: #6b7280;
        margin: 0;
    }

    .summary-cards {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .summary-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 24px;
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .summary-card-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 20px;
        flex-shrink: 0;
    }

    .summary-card-content {
        flex: 1;
    }

    .summary-card-label {
        font-size: 13px;
        color: #6b7280;
        margin: 0 0 4px 0;
        font-weight: 500;
    }

    .summary-card-value {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
        margin: 0;
    }

    .section-controls {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .status-filter {
        padding: 8px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        background: #fff;
        color: #111827;
        font-size: 14px;
        cursor: pointer;
        outline: none;
    }

    .manage-payments-btn {
        padding: 10px 20px;
        background: #111827;
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        text-decoration: none;
        display: inline-block;
    }

    .manage-payments-btn:hover {
        background: #374151;
        color: #fff;
        text-decoration: none;
    }

    .payments-table {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        overflow: hidden;
    }

    .table {
        width: 100%;
        border-collapse: collapse;
        margin: 0;
    }

    .table thead {
        background: #f9fafb;
    }

    .table th {
        padding: 16px;
        text-align: left;
        font-size: 12px;
        font-weight: 700;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid #e5e7eb;
    }

    .table td {
        padding: 16px;
        border-bottom: 1px solid #e5e7eb;
        font-size: 14px;
        color: #111827;
    }

    .table tbody tr:last-child td {
        border-bottom: none;
    }

    .table tbody tr:hover {
        background: #f9fafb;
    }

    .project-name {
        font-weight: 700;
        color: #111827;
    }

    .amount-value {
        font-weight: 700;
        color: #111827;
    }

    .status-tag {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-paid {
        background: #d1fae5;
        color: #065f46;
    }

    .status-pending {
        background: #fef3c7;
        color: #92400e;
    }

    .status-requested {
        background: #dbeafe;
        color: #1e40af;
    }

    .status-approved {
        background: #d1fae5;
        color: #065f46;
    }

    .date-text {
        color: #6b7280;
    }

    .payment-method {
        color: #6b7280;
    }

    .earnings-section {
        margin-top: 40px;
    }

    .earnings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 24px;
    }

    .earning-card {
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        padding: 20px;
    }

    .earning-card-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .earning-card-role {
        font-size: 14px;
        color: #6b7280;
        margin: 0 0 16px 0;
    }

    .earning-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .earning-amount {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    .empty-state {
        text-align: center;
        padding: 48px 24px;
        color: #6b7280;
    }

    @media (max-width: 768px) {
        .summary-cards {
            grid-template-columns: 1fr;
        }

        .section-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }

        .earnings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="payments-page">
    <div class="payments-container">
        <div class="payments-header">
            <h1 class="payments-title">Payments Overview</h1>
            <p class="payments-subtitle">Manage all payments from here.</p>
        </div>

        <!-- Summary Cards -->
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="summary-card-content">
                    <p class="summary-card-label">Available Balance</p>
                    <p class="summary-card-value">${{ number_format($availableBalance, 2) }}</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="summary-card-content">
                    <p class="summary-card-label">Pending Amount</p>
                    <p class="summary-card-value">${{ number_format($pendingAmount, 2) }}</p>
                </div>
            </div>
            <div class="summary-card">
                <div class="summary-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="summary-card-content">
                    <p class="summary-card-label">Total Earned</p>
                    <p class="summary-card-value">${{ number_format($totalEarned, 2) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent Payments Section -->
        <div class="recent-payments-section">
            <div class="section-header">
                <h2 class="section-title">Recent Payments</h2>
                <div class="section-controls">
                    <select class="status-filter" id="statusFilter" onchange="window.location.href='{{ route('talent.payments.index') }}?status=' + this.value">
                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="received" {{ $statusFilter === 'received' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ $statusFilter === 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="requested" {{ $statusFilter === 'requested' ? 'selected' : '' }}>Requested</option>
                        <option value="approved" {{ $statusFilter === 'approved' ? 'selected' : '' }}>Approved</option>
                    </select>
                    <a href="{{ route('talent.payments.card-details') }}" class="manage-payments-btn">Manage Payments</a>
                </div>
            </div>

            <div class="payments-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th>PROJECT</th>
                            <th>AMOUNT</th>
                            <th>STATUS</th>
                            <th>DATE</th>
                            <th>PAYMENT METHOD</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($filteredApplications as $application)
                            @php
                                $project = $application->casting_requirement;
                                $amount = $application->rate_offered ?? $application->rate ?? 0;
                                $paymentStatus = $application->payment_status ?? 'pending';
                                $statusLabel = \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$paymentStatus] ?? ucfirst($paymentStatus);

                                // Format date
                                $paymentDate = null;
                                if ($application->payment_received_at) {
                                    $paymentDate = $application->payment_received_at;
                                } elseif ($application->payment_released_at) {
                                    $paymentDate = $application->payment_released_at;
                                } elseif ($application->payment_requested_at) {
                                    $paymentDate = $application->payment_requested_at;
                                } else {
                                    $paymentDate = $application->updated_at;
                                }

                                $dateFormatted = $paymentDate ? $paymentDate->format('M d, Y') : 'N/A';
                                $dateAgo = $paymentDate ? $paymentDate->diffForHumans() : '';

                                $paymentMethod = getPaymentMethod($application);

                                // Status tag class
                                $statusClass = 'status-pending';
                                if ($paymentStatus === 'received') {
                                    $statusClass = 'status-paid';
                                } elseif ($paymentStatus === 'approved') {
                                    $statusClass = 'status-approved';
                                } elseif ($paymentStatus === 'requested') {
                                    $statusClass = 'status-requested';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <span class="project-name">{{ $project->project_name ?? 'N/A' }}</span>
                                </td>
                                <td>
                                    <span class="amount-value">${{ number_format($amount, 2) }}</span>
                                </td>
                                <td>
                                    <span class="status-tag {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td>
                                    <span class="date-text">{{ $dateFormatted }}</span>
                                    @if($dateAgo)
                                        <br><small style="color: #9ca3af;">{{ $dateAgo }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="payment-method">{{ $paymentMethod }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="empty-state">
                                    <p>No payments found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Earnings by Shoot Section -->
        <div class="earnings-section">
            <h2 class="section-title">Earnings by Shoot</h2>
            <div class="earnings-grid">
                @forelse($applications as $application)
                    @php
                        $project = $application->casting_requirement;
                        $amount = $application->rate_offered ?? $application->rate ?? 0;
                        $paymentStatus = $application->payment_status ?? 'pending';
                        $statusLabel = \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$paymentStatus] ?? ucfirst($paymentStatus);

                        // Get role from model requirement or default
                        $role = 'Model';
                        if ($project && $project->modelRequirements->isNotEmpty()) {
                            $firstReq = $project->modelRequirements->first();
                            $role = $firstReq->title ?? 'Model';
                        }

                        $statusClass = 'status-pending';
                        if ($paymentStatus === 'received' || $paymentStatus === 'approved') {
                            $statusClass = 'status-paid';
                        }
                    @endphp
                    <div class="earning-card">
                        <h3 class="earning-card-title">{{ $project->project_name ?? 'N/A' }}</h3>
                        <p class="earning-card-role">Role: {{ $role }}</p>
                        <div class="earning-card-footer">
                            <span class="earning-amount">${{ number_format($amount, 2) }}</span>
                            <span class="status-tag {{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>
                    </div>
                @empty
                    <div class="empty-state" style="grid-column: 1 / -1;">
                        <p>No earnings found</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
