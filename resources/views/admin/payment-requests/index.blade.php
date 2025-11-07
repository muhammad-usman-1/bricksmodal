@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">Payment Requests Management</h2>
            <p class="text-muted mb-0">Approve and release payments to talents</p>
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

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Pending Approval</div>
                    <h4 class="mb-0 text-warning">{{ $stats['total_requested'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Approved</div>
                    <h4 class="mb-0 text-primary">{{ $stats['total_approved'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Released</div>
                    <h4 class="mb-0 text-info">{{ $stats['total_released'] }}</h4>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="text-muted small mb-1">Total Pending Amount</div>
                    <h4 class="mb-0 text-success">${{ number_format($stats['total_amount_pending'], 2) }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Payment Requests</h5>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('admin.payment-requests.index') }}" class="btn btn-outline-secondary {{ !request('status') ? 'active' : '' }}">
                    Pending Actions
                </a>
                <a href="{{ route('admin.payment-requests.index', ['status' => 'requested']) }}" class="btn btn-outline-warning {{ request('status') === 'requested' ? 'active' : '' }}">
                    Awaiting Approval
                </a>
                <a href="{{ route('admin.payment-requests.index', ['status' => 'approved']) }}" class="btn btn-outline-primary {{ request('status') === 'approved' ? 'active' : '' }}">
                    Ready to Release
                </a>
                <a href="{{ route('admin.payment-requests.index', ['status' => 'released']) }}" class="btn btn-outline-info {{ request('status') === 'released' ? 'active' : '' }}">
                    Released
                </a>
                <a href="{{ route('admin.payment-requests.index', ['status' => 'received']) }}" class="btn btn-outline-success {{ request('status') === 'received' ? 'active' : '' }}">
                    Completed
                </a>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-dark">
                        <tr>
                            <th>Project</th>
                            <th>Talent</th>
                            <th>Requested By Admin</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Requested Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($paymentRequests as $application)
                            <tr>
                                <td>
                                    <strong>{{ optional($application->casting_requirement)->project_name ?? 'N/A' }}</strong>
                                </td>
                                <td>
                                    {{ optional(optional($application->talent_profile)->user)->name ?? 'N/A' }}
                                    <br>
                                    <small class="text-muted">
                                        Card: {{ optional($application->talent_profile)->getMaskedCardNumber() }}
                                    </small>
                                </td>
                                <td>
                                    @if($application->requestedByAdmin)
                                        {{ $application->requestedByAdmin->name }}
                                        <br><small class="text-muted">{{ $application->requestedByAdmin->email }}</small>
                                    @else
                                        <span class="text-muted">Direct Request</span>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-success">${{ number_format($application->getPaymentAmount(), 2) }}</strong>
                                </td>
                                <td>
                                    <span class="badge {{ $application->getPaymentStatusBadgeClass() }}">
                                        {{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ucfirst($application->payment_status) }}
                                    </span>
                                    @if($application->payment_approved_at)
                                        <br><small class="text-muted">Approved: {{ $application->payment_approved_at->format('M d, Y') }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($application->payment_requested_at)
                                        {{ $application->payment_requested_at->format('M d, Y H:i') }}
                                        <br><small class="text-muted">{{ $application->payment_requested_at->diffForHumans() }}</small>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if($application->payment_status === 'requested')
                                        <div class="btn-group btn-group-sm">
                                            <form action="{{ route('admin.payment-requests.approve', $application) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-success" onclick="return confirm('Approve this payment request?')">
                                                    <i class="fas fa-check"></i> Approve
                                                </button>
                                            </form>
                                            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#rejectModal{{ $application->id }}">
                                                <i class="fas fa-times"></i> Reject
                                            </button>
                                        </div>

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
                                    @elseif($application->payment_status === 'approved')
                                        <a href="{{ route('admin.payment-requests.release-form', $application) }}" class="btn btn-sm btn-primary">
                                            <i class="fab fa-stripe"></i> Release via Stripe
                                        </a>
                                    @elseif($application->payment_status === 'released')
                                        <div class="text-info">
                                            <i class="fas fa-check-circle"></i> Payment Released
                                            @if($application->stripe_payment_intent)
                                                <br><small class="text-muted">
                                                    <i class="fab fa-stripe"></i> {{ substr($application->stripe_payment_intent, 0, 20) }}...
                                                </small>
                                            @endif
                                        </div>
                                        <span class="text-muted">
                                            <i class="fas fa-clock"></i> Awaiting Confirmation
                                        </span>
                                        @if($application->payment_released_at)
                                            <br><small class="text-muted">{{ $application->payment_released_at->diffForHumans() }}</small>
                                        @endif
                                    @elseif($application->payment_status === 'received')
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i> Completed
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-5">
                                    <i class="fas fa-inbox fa-3x mb-3 d-block"></i>
                                    <p>No payment requests found</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($paymentRequests->hasPages())
            <div class="card-footer bg-white">
                {{ $paymentRequests->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
