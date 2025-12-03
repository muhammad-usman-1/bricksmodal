@extends('layouts.admin')

@section('content')
<div class="content">
    <div class="row mb-3">
        <div class="col-md-8">
            <h2 class="mb-1">{{ __('Payment Request Details') }}</h2>
            <p class="text-muted mb-0">{{ __('Review the request, rating, and review before acting.') }}</p>
        </div>
        <div class="col-md-4 text-md-right mt-3 mt-md-0">
            <a href="{{ route('admin.payment-requests.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i>{{ __('Back to list') }}
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('Overview') }}</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>{{ __('Project') }}:</strong><br>
                                {{ optional($castingApplication->casting_requirement)->project_name ?? 'N/A' }}
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Talent') }}:</strong><br>
                                {{ optional(optional($castingApplication->talent_profile)->user)->name ?? 'N/A' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>{{ __('Requested By') }}:</strong><br>
                                @if($castingApplication->requestedByAdmin)
                                    {{ $castingApplication->requestedByAdmin->name }}<br>
                                    <small class="text-muted">{{ $castingApplication->requestedByAdmin->email }}</small>
                                @else
                                    <span class="text-muted">{{ __('Direct Request') }}</span>
                                @endif
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Amount') }}:</strong><br>
                                <span class="text-success h5">${{ number_format($castingApplication->getPaymentAmount(), 2) }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>{{ __('Status') }}:</strong><br>
                                <span class="badge {{ $castingApplication->getPaymentStatusBadgeClass() }}">
                                    {{ \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$castingApplication->payment_status] ?? ucfirst($castingApplication->payment_status) }}
                                </span>
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Requested At') }}:</strong><br>
                                {{ optional($castingApplication->payment_requested_at)->format('M d, Y H:i') ?? '—' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2">
                                <strong>{{ __('Approval Date') }}:</strong><br>
                                {{ optional($castingApplication->payment_approved_at)->format('M d, Y H:i') ?? '—' }}
                            </p>
                            <p class="mb-2">
                                <strong>{{ __('Release Date') }}:</strong><br>
                                {{ optional($castingApplication->payment_released_at)->format('M d, Y H:i') ?? '—' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('Client Feedback') }}</h5>
                </div>
                <div class="card-body">
                    @php
                        $rating = $castingApplication->rating;
                        $review = $castingApplication->reviews;
                    @endphp
                    @if($rating)
                        <div class="mb-3 text-warning h4">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star{{ $i <= $rating ? '' : '-o' }}"></i>
                            @endfor
                            <span class="text-muted h6 ml-2">{{ number_format($rating, 1) }}/5</span>
                        </div>
                    @else
                        <p class="text-muted mb-0">{{ __('No rating submitted with this request.') }}</p>
                    @endif
                    @if($review)
                        <blockquote class="blockquote">
                            <p class="mb-0">{{ $review }}</p>
                            <footer class="blockquote-footer mt-2">
                                {{ __('Submitted by') }}
                                <cite title="Reviewer">{{ optional($castingApplication->requestedByAdmin)->name ?? __('Unknown Admin') }}</cite>
                            </footer>
                        </blockquote>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">{{ __('Next Actions') }}</h5>
                </div>
                <div class="card-body">
                    @if($castingApplication->payment_status === 'requested')
                        <form action="{{ route('admin.payment-requests.approve', $castingApplication) }}" method="POST" class="mb-3">
                            @csrf
                            <button type="submit" class="btn btn-success btn-block" onclick="return confirm('{{ __('Approve this payment request?') }}')">
                                <i class="fas fa-check mr-1"></i>{{ __('Approve Request') }}
                            </button>
                        </form>
                        <button class="btn btn-outline-danger btn-block" data-toggle="modal" data-target="#showRejectModal">
                            <i class="fas fa-times mr-1"></i>{{ __('Reject Request') }}
                        </button>
                    @elseif($castingApplication->payment_status === 'approved')
                        <a href="{{ route('admin.payment-requests.release-form', $castingApplication) }}" class="btn btn-primary btn-block">
                            <i class="fab fa-stripe mr-1"></i>{{ __('Release via Stripe') }}
                        </a>
                    @elseif($castingApplication->payment_status === 'released')
                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle mr-1"></i>
                            {{ __('Payment released. Awaiting confirmation from the talent.') }}
                        </div>
                    @elseif($castingApplication->payment_status === 'received')
                        <div class="alert alert-success mb-0">
                            <i class="fas fa-check-circle mr-1"></i>
                            {{ __('Payment completed successfully.') }}
                        </div>
                    @else
                        <div class="alert alert-secondary mb-0">
                            {{ __('No further actions needed at this time.') }}
                        </div>
                    @endif
                </div>
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

