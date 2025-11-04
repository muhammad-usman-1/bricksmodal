@extends('layouts.admin')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Process Payment</h5>
        </div>
        <div class="card-body">
            <h6>Processing payment for: {{ $user->name }}</h6>
            
            @if($paymentMethods->isEmpty())
                <div class="alert alert-warning">
                    This user has no saved payment methods.
                </div>
            @else
                <form action="{{ route('admin.payments.store', $user) }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="amount">Amount (USD)</label>
                        <input type="number" step="0.01" min="0.01" 
                               class="form-control @error('amount') is-invalid @enderror" 
                               id="amount" name="amount" required>
                        @error('amount')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="payment_method_id">Payment Method</label>
                        <select class="form-control @error('payment_method_id') is-invalid @enderror" 
                                id="payment_method_id" name="payment_method_id" required>
                            @foreach($paymentMethods as $method)
                                <option value="{{ $method->stripe_payment_method_id }}">
                                    {{ ucfirst($method->card_brand) }} ending in {{ $method->card_last_four }} 
                                    (Expires: {{ $method->card_exp_month }}/{{ $method->card_exp_year }})
                                    {{ $method->is_default ? '(Default)' : '' }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group mb-3">
                        <label for="description">Description (Optional)</label>
                        <input type="text" class="form-control @error('description') is-invalid @enderror" 
                               id="description" name="description">
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary">Process Payment</button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection