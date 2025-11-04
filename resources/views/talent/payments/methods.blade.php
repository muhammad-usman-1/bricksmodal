@extends('layouts.talent')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Payment Methods</h5>
                    <a href="{{ route('talent.payment-methods.create') }}" class="btn btn-primary">Add New Card</a>
                </div>
                <div class="card-body">
                    @if($paymentMethods->isEmpty())
                        <p class="text-center">No payment methods added yet.</p>
                    @else
                        <div class="list-group">
                            @foreach($paymentMethods as $method)
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-0">
                                                {{ ucfirst($method->card_brand) }} ending in {{ $method->card_last_four }}
                                            </h6>
                                            <small class="text-muted">
                                                Expires {{ $method->card_exp_month }}/{{ $method->card_exp_year }}
                                            </small>
                                        </div>
                                        @if($method->is_default)
                                            <span class="badge bg-success">Default</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
