@extends('layouts.admin')
@section('content')
<link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #f8f9fc;
            --card: #ffffff;
            --ink-900: #101828;
            --ink-700: #344054;
            --ink-500: #667085;
            --border: #eaecf0;
            --shadow: none;
        }

        .pay-shell {
            padding: 22px 0;
              font-family: 'Arimo', sans-serif;
        }

        .pay-header h5 {
            color: #101828;
            font-size: 24px;
            font-weight: 400;
            margin-bottom: 4px;
        }

        .pay-header p {
            color: #667085;
            font-size: 14px;
            margin: 0 0 32px 0;
        }

        .summary-row {
            margin: 0 -12px 32px;
        }

        .summary-col {
            padding: 0 12px;
        }

        .summary-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }

        .summary-card-body {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
        }

        .summary-title {
            font-size: 14px;
            color: #667085;
            margin-bottom: 8px;
            font-weight: 400;
        }

        .summary-amount {
            font-size: 28px;
            font-weight: 400;
            color: #101828;
            margin: 0;
        }

        .summary-icon {
            width: 44px;
            height: 44px;
            border-radius: 8px;
            background: #f2f4f7;
            display: grid;
            place-items: center;
            color: #344054;
            font-size: 20px;
        }

        .recent-card {
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 12px;
            overflow: hidden;
        }

        .recent-head {
            padding: 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .recent-title {
            margin: 0;
            font-size: 18px;
            font-weight: 400;
            color: #101828;
        }

        .manage-btn {
            background: #1a1a1a;
            color: #fff;
            border: none;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none !important;
            display: inline-block;
        }

        .manage-btn:hover {
            background: #000;
            color: #fff;
            text-decoration: none;
        }

        .table-wrap {
            overflow-x: auto;
        }

        .pay-table {
            width: 100%;
            border-collapse: collapse;
        }

        .pay-table thead th {
            color: #4A5565
            text-transform: uppercase;
            font-size: 14px;
            font-weight: 700;
            padding: 16px 24px;
            border-bottom: 1px solid #f2f4f7;
            text-align: left;
               font-family: 'Arimo', sans-serif;
        }


        .pay-table tbody td {
            padding: 16px 24px;
            border-bottom: 1px solid #f2f4f7;
            color: #475467;
            font-size: 14px;
            vertical-align: middle;
               font-family: 'Arimo', sans-serif;
        }

        .talent-name {
            color: #101828;
            font-weight: 500;
        }
        .pay-row-clickable { cursor: pointer; }
        .pay-row-clickable:hover { background: #f9fafb; }

        .status-pill {
            display: inline-flex;
            padding: 4px 12px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        .status-requested { background: #e0eaff; color: #444ce7; }
        .status-approved { background: #ecfdf3; color: #027a48; }
        .status-released { background: #f4ebff; color: #6941c6; }
        .status-pending { background: #fff8e6; color: #b54708; }
        .status-default { background: #f2f4f7; color: #344054; }

        .action-ellipsis {
            color: #98a2b3;
            font-size: 16px;
            text-align: right;
            cursor: pointer;
        }

        @media (max-width: 768px) {
            .summary-col { margin-bottom: 16px; }
        }
    </style>

    <div class="pay-shell">
        <div class="pay-header">
            <h5>Payments Overview</h5>
            <p>Manage all payments from here.</p>
        </div>

        <div class="row summary-row">
            <div class="col-md-4 summary-col">
                <div class="summary-card">
                    <div class="summary-card-body">
                        <div>
                            <div class="summary-title">Total Requested Amount</div>
                            <p class="summary-amount">{{ number_format($financials['total_requested'] ?? 0, 2) }} KWD</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 summary-col">
                <div class="summary-card">
                    <div class="summary-card-body">
                        <div>
                            <div class="summary-title">Pending Amount</div>
                            <p class="summary-amount">{{ number_format($financials['total_pending'] ?? 0, 2) }} KWD</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 summary-col">
                <div class="summary-card">
                    <div class="summary-card-body">
                        <div>
                            <div class="summary-title">Released Amount</div>
                            <p class="summary-amount">{{ number_format($financials['total_released'] ?? 0, 2) }} KWD</p>
                        </div>
                        <div class="summary-icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card recent-card">
            <div class="recent-head">
                <h2 class="recent-title">Recent Payments</h2>
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
                           
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $application)
                            @php
                                $status = strtolower($application->payment_status);
                                $isPaid = in_array($status, ['released', 'approved', 'paid', 'completed', 'settled']);
                                $isRequested = $status === 'requested';
                                $statusLabel = $isPaid ? 'Paid' : ($isRequested ? 'Requested' : 'Pending');
                                $statusClass = $isPaid ? 'status-approved' : ($isRequested ? 'status-requested' : 'status-pending');
                            @endphp
                            <tr class="pay-row-clickable" data-detail-url="{{ route('admin.payment-requests.show', $application) }}">
                                <td class="talent-name">
                                    {{ optional($application->talent_profile)->display_name ?? (optional($application->talent_profile)->legal_name ?? 'N/A') }}
                                </td>
                                <td>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</td>
                                <td>{{ number_format($application->getPaymentAmount(), 2) }} KWD</td>
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

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('tr.pay-row-clickable[data-detail-url]').forEach(row => {
            row.addEventListener('click', function (e) {
                // Don't navigate if clicking on interactive elements
                if (e.target.closest('a, button, input, select, textarea, label')) return;
                const url = this.dataset.detailUrl;
                if (url) window.location.href = url;
            });
        });
    });
</script>
@endsection
