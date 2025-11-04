@extends('layouts.talent')

@section('styles')
<style>
    .StripeElement {
        background-color: white;
        padding: 10px;
        border-radius: 4px;
        border: 1px solid #ced4da;
    }
    .StripeElement--focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .StripeElement--invalid {
        border-color: #dc3545;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Add Payment Method</div>
                <div class="card-body">
                    <form id="payment-form">
                        <div class="form-group">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element" class="form-control">
                                <!-- A Stripe Element will be inserted here. -->
                            </div>
                            <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                        </div>
                        <button type="submit" class="btn btn-primary mt-3" id="submit-button">
                            Add Card
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://js.stripe.com/v3/"></script>
<script>
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();
    const card = elements.create('card');
    card.mount('#card-element');

    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');

    card.addEventListener('change', ({error}) => {
        const displayError = document.getElementById('card-errors');
        if (error) {
            displayError.textContent = error.message;
        } else {
            displayError.textContent = '';
        }
    });

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        submitButton.disabled = true;

        const {setupIntent, error} = await stripe.confirmCardSetup(
            '{{ $clientSecret }}',
            {
                payment_method: {
                    card: card,
                    billing_details: {}
                }
            }
        );

        if (error) {
            const errorElement = document.getElementById('card-errors');
            errorElement.textContent = error.message;
            submitButton.disabled = false;
        } else {
            // Send the payment method ID to your server
            const response = await fetch('{{ route('talent.payment-methods.store') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_method_id: setupIntent.payment_method
                })
            });

            const result = await response.json();

            if (result.success) {
                window.location.href = '{{ route('talent.payment-methods.index') }}';
            } else {
                const errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.message;
                submitButton.disabled = false;
            }
        }
    });
</script>
@endsection
