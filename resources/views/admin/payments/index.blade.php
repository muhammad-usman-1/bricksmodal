@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Payment History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>User</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Description</th>
                            <th>Card</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($payments as $payment)
                            <tr>
                                <td>{{ $payment->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $payment->user->name }}</td>
                                <td>${{ number_format($payment->amount, 2) }}</td>
                                <td>
                                    <span class="badge bg-{{ $payment->status === 'succeeded' ? 'success' : 'warning' }}">
                                        {{ ucfirst($payment->status) }}
                                    </span>
                                </td>
                                <td>{{ $payment->description ?? '-' }}</td>
                                <td>
                                    @if($payment->stripe_payment_method_id)
                                        {{ $payment->paymentMethod?->card_brand ?? 'Card' }}
                                        ending in {{ $payment->paymentMethod?->card_last_four ?? 'xxxx' }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center">No payments found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $payments->links() }}
        </div>
    </div>
</div>
@endsection
