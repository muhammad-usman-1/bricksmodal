@extends('layouts.admin')
@section('content')
<div class="content">
     

    <div class="row">
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Requested Amount</h5>
                    <p class="card-text display-4">
                        ${{ number_format($financials['total_requested'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Pending Amount</h5>
                    <p class="card-text display-4">
                        ${{ number_format($financials['total_pending'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Released Amount</h5>
                    <p class="card-text display-4">
                        ${{ number_format($financials['total_released'] ?? 0, 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>



    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-history mr-2"></i>
                Recent Payments
            </span>
            @if($isSuperAdmin ?? false)
                <a href="{{ route('admin.payment-requests.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-cog mr-1"></i> Manage Payments
                </a>
            @else
                <a href="{{ route('admin.casting-applications.index') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-list mr-1"></i> View Applications
                </a>
            @endif
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
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
                            <tr>
                                <td>{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name ?? 'N/A' }}</td>
                                <td>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</td>
                                <td>
                                    <strong>${{ number_format($application->getPaymentAmount(), 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $application->getPaymentStatusBadgeClass() }}">
                                        {{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ucfirst($application->payment_status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($application->payment_requested_at)
                                        {{ $application->payment_requested_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $application->payment_requested_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not requested</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2"></i>
                                    <br>No recent payments found
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.badge-lg {
    font-size: 1.5rem;
    padding: 0.5rem 1rem;
}
</style>
@endsection
