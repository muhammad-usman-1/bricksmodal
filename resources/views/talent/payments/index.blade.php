@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">{{ trans('global.payment_dashboard') }}</h2>
            <p class="text-muted mb-0">Track your payment requests and earnings</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('talent.payments.card-details') }}" class="btn btn-outline-primary">
                <i class="fas fa-credit-card mr-1"></i>
                {{ $profile->hasCardDetails() ? 'Update' : 'Add' }} Card Details
            </a>
        </div>
    </div>

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

    @if(!$profile->hasCardDetails())
        <div class="alert alert-warning">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Action Required:</strong> Please add your card details to receive payments.
            <a href="{{ route('talent.payments.card-details') }}" class="alert-link">Add Card Details</a>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Earnings</div>
                    <h4 class="mb-0">${{ number_format($applications->sum(function($app) { return $app->rate_offered ?? $app->rate ?? 0; }), 2) }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pending</div>
                    <h4 class="mb-0">{{ $applications->where('payment_status', 'pending')->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Requested</div>
                    <h4 class="mb-0">{{ $applications->whereIn('payment_status', ['requested', 'approved'])->count() }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Received</div>
                    <h4 class="mb-0">{{ $applications->where('payment_status', 'received')->count() }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white">
            <h5 class="mb-0">Payment History</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>
                                    <strong>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</strong>
                                    @if($application->admin_notes)
                                        <br><small class="text-muted">{{ Str::limit($application->admin_notes, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <strong>${{ number_format($application->rate_offered ?? $application->rate ?? 0, 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $application->getPaymentStatusBadgeClass() }}">
                                        {{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ucfirst($application->payment_status) }}
                                    </span>
                                    @if($application->payment_status === 'pending')
                                        <br><small class="text-muted">Ready to request</small>
                                    @elseif($application->payment_status === 'requested')
                                        <br><small class="text-muted">Awaiting approval</small>
                                    @elseif($application->payment_status === 'approved')
                                        <br><small class="text-muted">Payment being processed</small>
                                    @elseif($application->payment_status === 'released')
                                        <br><small class="text-muted">Sent to your card</small>
                                    @elseif($application->payment_status === 'rejected')
                                        <br><small class="text-danger">{{ $application->payment_rejection_reason }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($application->payment_requested_at)
                                        {{ $application->payment_requested_at->format('M d, Y') }}
                                        <br><small class="text-muted">{{ $application->payment_requested_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">Not requested</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->canRequestPayment() && $profile->hasCardDetails())
                                        <form action="{{ route('talent.payments.request', $application) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-primary" onclick="return confirm('Request payment for this project?')">
                                                <i class="fas fa-paper-plane mr-1"></i> Request Payment
                                            </button>
                                        </form>
                                    @elseif($application->payment_status === 'released')
                                        <form action="{{ route('talent.payments.confirm-received', $application) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Confirm that you have received this payment?')">
                                                <i class="fas fa-check mr-1"></i> Confirm Received
                                            </button>
                                        </form>
                                    @elseif($application->payment_status === 'received')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Completed
                                        </span>
                                    @elseif(!$profile->hasCardDetails() && $application->payment_status === 'pending')
                                        <a href="{{ route('talent.payments.card-details') }}" class="btn btn-sm btn-outline-warning">
                                            <i class="fas fa-credit-card"></i> Add Card First
                                        </a>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p>No payment records found</p>
                                    <small>Payments will appear here once you're selected for projects</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
