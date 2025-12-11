@extends('layouts.admin')
@section('content')
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --ink-900: #0f1524;
            --ink-700: #3b4150;
            --ink-500: #7b8191;
            --border: #e5e7eb;
            --shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
        }

        body {
            background: var(--bg);
        }

        .pay-shell {
            padding: 10px 0 26px;
        }

        .pay-header h5 {
            color: #101828;
            font-size: 24px;
            font-style: normal;
            font-weight: 400;
            line-height: 36px;
            /* 150% */
        }

        .pay-header p {
            margin: 4px 0 18px;
            color: var(--ink-500);
            font-size: 13px;
        }

        .summary-row {
            margin: 0 -6px 18px;
        }

        .summary-col {
            padding: 0 6px;
            margin-bottom: 12px;
        }

        .summary-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 12px;
            box-shadow: var(--shadow);
        }

        .summary-card .card-body {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 18px;
        }

        .summary-title {
            font-size: 12px;
            text-transform: none;
            color: var(--ink-500);
            margin-bottom: 6px;
        }

        .summary-amount {
            font-size: 24px;
            font-weight: 800;
            color: var(--ink-900);
            margin: 0;
        }

        .summary-icon {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            background: #eef1f5;
            display: grid;
            place-items: center;
            color: #7b8191;
            font-size: 14px;
        }

        .recent-card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .recent-head {
            padding: 14px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--border);
        }

        .recent-title {
            margin: 0;
            font-weight: 700;
            color: var(--ink-900);
        }

        .manage-btn {
            background: #0f1524;
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 8px 12px;
            font-size: 13px;
            text-decoration: none;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
        }

        .manage-btn:hover {
            color: #fff;
            opacity: 0.92;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .pay-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .pay-table thead th {
            background: #f9fafb;
            color: var(--ink-700);
            text-transform: uppercase;
            letter-spacing: 0.4px;
            font-size: 11px;
            font-weight: 700;
            padding: 12px 14px;
            border-bottom: 1px solid var(--border);
            white-space: nowrap;
        }

        .pay-table tbody td {
            padding: 12px 14px;
            border-bottom: 1px solid #f0f2f5;
            color: var(--ink-700);
            vertical-align: middle;
        }

        .pay-table tbody tr:last-child td {
            border-bottom: none;
        }

        .talent-name {
            color: var(--ink-900);
            font-weight: 600;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 10px;
            border-radius: 999px;
            font-weight: 700;
            font-size: 11px;
            border: 1px solid transparent;
        }

        .status-requested {
            background: #e6f2ff;
            color: #2d8cff;
            border-color: #c7e0ff;
        }

        .status-approved {
            background: #e8f8f0;
            color: #16a34a;
            border-color: #c5ecd7;
        }

        .status-released {
            background: #f2e9ff;
            color: #a855f7;
            border-color: #e1d2fb;
        }

        .status-pending {
            background: #fff7df;
            color: #d97706;
            border-color: #f2e4b5;
        }

        .status-default {
            background: #f1f2f4;
            color: #6b7280;
            border-color: #e1e3e6;
        }

        .action-ellipsis {
            color: #c0c4cc;
            font-size: 14px;
            text-align: right;
        }

        @media (max-width: 960px) {
            .summary-row {
                margin: 0 -4px 12px;
            }

            .summary-card .card-body {
                padding: 14px;
            }
        }
    </style>

    <div class="pay-shell">
        <div class="pay-header">
            <h5>Payments Overview</h5>
            <p>Manage all payments from here.</p>
        </div>

        <div class="row summary-row">
            <div class="col-md-4 summary-col">
                <div class="card summary-card">
                    <div class="card-body">
                        <div>
                            <div class="summary-title">Total Requested Amount</div>
                            <p class="summary-amount">${{ number_format($financials['total_requested'] ?? 0, 2) }}</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 summary-col">
                <div class="card summary-card">
                    <div class="card-body">
                        <div>
                            <div class="summary-title">Pending Amount</div>
                            <p class="summary-amount">${{ number_format($financials['total_pending'] ?? 0, 2) }}</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 summary-col">
                <div class="card summary-card">
                    <div class="card-body">
                        <div>
                            <div class="summary-title">Released Amount</div>
                            <p class="summary-amount">${{ number_format($financials['total_released'] ?? 0, 2) }}</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card recent-card">
            <div class="recent-head">
                <h6 class="recent-title">Recent Payments</h6>
                <a href="{{ route('admin.payment-requests.index') }}" class="manage-btn">Manage Payments</a>
            </div>
            <div class="table-wrap">
                <table class="pay-table">
                    <thead>
                        <tr>
                            <th>Talent</th>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Payment Status</th>
                            <th>Requested At</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $application)
                            @php
                                $status = $application->payment_status;
                                $statusLabel =
                                    \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$status] ?? ucfirst($status);
                                $statusClass =
                                    [
                                        'requested' => 'status-requested',
                                        'approved' => 'status-approved',
                                        'released' => 'status-released',
                                        'pending' => 'status-pending',
                                    ][$status] ?? 'status-default';
                            @endphp
                            <tr>
                                <td class="talent-name">
                                    {{ optional($application->talent_profile)->display_name ?? (optional($application->talent_profile)->legal_name ?? 'N/A') }}
                                </td>
                                <td>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</td>
                                <td>${{ number_format($application->getPaymentAmount(), 2) }}</td>
                                <td><span class="status-pill {{ $statusClass }}">{{ $statusLabel }}</span></td>
                                <td>
                                    @if ($application->payment_requested_at)
                                        {{ $application->payment_requested_at->format('M d, Y') }}
                                        <div class="text-muted" style="font-size:11px;">
                                            {{ $application->payment_requested_at->diffForHumans() }}</div>
                                    @else
                                        <span class="text-muted">Not requested</span>
                                    @endif
                                </td>
                                <td class="action-ellipsis"><i class="fas fa-ellipsis-v"></i></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" style="text-align:center; padding:24px; color: var(--ink-500);">No recent
                                    payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
