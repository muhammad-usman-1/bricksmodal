@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-8 offset-lg-2">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fab fa-stripe mr-2"></i>
                        Release Payment via Stripe
                    </h5>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                Please fix the following errors:
                            </h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    <!-- Payment Summary -->
                    <div class="card bg-light border mb-4">
                        <div class="card-body">
                            <h6 class="font-weight-bold mb-3">
                                <i class="fas fa-file-invoice-dollar mr-2"></i>
                                Payment Details
                            </h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Project:</strong><br>
                                        {{ $application->casting_requirement->project_name ?? 'N/A' }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Talent:</strong><br>
                                        {{ optional($application->talent_profile->user)->name ?? 'N/A' }}
                                    </p>
                                </div>
                                <div class="col-md-6">
                                    <p class="mb-2">
                                        <strong>Talent's Card:</strong><br>
                                        {{ $application->talent_profile->getMaskedCardNumber() }}
                                    </p>
                                    <p class="mb-2">
                                        <strong>Amount Due:</strong><br>
                                        <span class="text-success h5">${{ number_format($application->getPaymentAmount(), 2) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Stripe Payment Form -->
                    <form action="{{ route('admin.payment-requests.release', $application) }}" method="POST" id="payment-form">
                        @csrf

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Super Admin Payment Processing</strong><br>
                            Please enter your Stripe payment details to process this payment transfer.
                        </div>

                        <!-- Payment Amount -->
                        <div class="form-group">
                            <label for="payment_amount" class="required">Payment Amount (USD)</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                <input type="number"
                                       name="payment_amount"
                                       id="payment_amount"
                                       class="form-control @error('payment_amount') is-invalid @enderror"
                                       value="{{ old('payment_amount', $application->getPaymentAmount()) }}"
                                       step="0.01"
                                       min="0.01"
                                       required>
                            </div>
                            @error('payment_amount')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                Default amount is based on the approved rate: ${{ number_format($application->getPaymentAmount(), 2) }}
                            </small>
                        </div>

                        <!-- Card Holder Name -->
                        <div class="form-group">
                            <label for="card_holder_name" class="required">Card Holder Name</label>
                            <input type="text"
                                   name="card_holder_name"
                                   id="card_holder_name"
                                   class="form-control @error('card_holder_name') is-invalid @enderror"
                                   value="{{ old('card_holder_name') }}"
                                   placeholder="Name as it appears on card"
                                   required>
                            @error('card_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Card Number -->
                        <div class="form-group">
                            <label for="card_number" class="required">Card Number</label>
                            <div id="card-number-element" class="form-control"></div>
                            <div id="card-number-errors" class="invalid-feedback d-block"></div>
                            <small class="form-text text-muted">
                                <i class="fas fa-lock mr-1"></i>
                                Use test card: 4242 4242 4242 4242 for sandbox testing
                            </small>
                        </div>

                        <!-- Card Expiry and CVC -->
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card_expiry" class="required">Expiry Date</label>
                                    <div id="card-expiry-element" class="form-control"></div>
                                    <div id="card-expiry-errors" class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="card_cvc" class="required">CVC</label>
                                    <div id="card-cvc-element" class="form-control"></div>
                                    <div id="card-cvc-errors" class="invalid-feedback d-block"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Description -->
                        <div class="form-group">
                            <label for="payment_description">Payment Description (Optional)</label>
                            <textarea name="payment_description"
                                      id="payment_description"
                                      class="form-control @error('payment_description') is-invalid @enderror"
                                      rows="3"
                                      placeholder="Add any notes about this payment...">{{ old('payment_description') }}</textarea>
                            @error('payment_description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror>
                        </div>

                        <!-- Processing Fee Notice -->
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <strong>Processing Fee:</strong> Stripe charges approximately 2.9% + $0.30 per transaction.
                            <br>
                            <small>For ${{ number_format($application->getPaymentAmount(), 2) }}, the fee will be approximately ${{ number_format(($application->getPaymentAmount() * 0.029) + 0.30, 2) }}</small>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox"
                                       class="custom-control-input"
                                       id="confirm_payment"
                                       name="confirm_payment"
                                       required>
                                <label class="custom-control-label" for="confirm_payment">
                                    I confirm that I want to process this payment of <strong>${{ number_format($application->getPaymentAmount(), 2) }}</strong>
                                    to {{ optional($application->talent_profile->user)->name ?? 'the talent' }}'s card ending in
                                    {{ substr($application->talent_profile->card_number ?? '', -4) }}
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('admin.payment-requests.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Cancel
                            </a>
                            <button type="submit" id="submit-button" class="btn btn-primary btn-lg">
                                <i class="fab fa-stripe mr-2"></i>
                                <span id="button-text">Process Payment via Stripe</span>
                                <span id="spinner" class="spinner-border spinner-border-sm d-none ml-2"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Help Section -->
            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">
                        <i class="fas fa-question-circle text-info mr-2"></i>
                        Stripe Test Cards
                    </h6>
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Card Number</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><code>4242 4242 4242 4242</code></td>
                                    <td><span class="badge badge-success">Success</span></td>
                                </tr>
                                <tr>
                                    <td><code>4000 0000 0000 0002</code></td>
                                    <td><span class="badge badge-danger">Card Declined</span></td>
                                </tr>
                                <tr>
                                    <td><code>4000 0000 0000 9995</code></td>
                                    <td><span class="badge badge-warning">Insufficient Funds</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <small class="text-muted">
                        Use any future expiry date and any 3-digit CVC for testing.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Stripe.js -->
<script src="https://js.stripe.com/v3/"></script>
<script>
    // Initialize Stripe
    const stripe = Stripe('{{ config('services.stripe.key') }}');
    const elements = stripe.elements();

    // Custom styling
    const style = {
        base: {
            fontSize: '16px',
            color: '#32325d',
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
            '::placeholder': {
                color: '#aab7c4'
            }
        },
        invalid: {
            color: '#dc3545',
            iconColor: '#dc3545'
        }
    };

    // Create card elements
    const cardNumber = elements.create('cardNumber', { style });
    cardNumber.mount('#card-number-element');

    const cardExpiry = elements.create('cardExpiry', { style });
    cardExpiry.mount('#card-expiry-element');

    const cardCvc = elements.create('cardCvc', { style });
    cardCvc.mount('#card-cvc-element');

    // Handle real-time validation errors
    cardNumber.on('change', function(event) {
        displayError('card-number-errors', event);
    });

    cardExpiry.on('change', function(event) {
        displayError('card-expiry-errors', event);
    });

    cardCvc.on('change', function(event) {
        displayError('card-cvc-errors', event);
    });

    function displayError(elementId, event) {
        const displayError = document.getElementById(elementId);
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    }

    // Handle form submission
    const form = document.getElementById('payment-form');
    const submitButton = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('spinner');

    form.addEventListener('submit', async function(event) {
        event.preventDefault();

        // Disable submit button
        submitButton.disabled = true;
        buttonText.textContent = 'Processing...';
        spinner.classList.remove('d-none');

        // Create payment method
        const { error, paymentMethod } = await stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: document.getElementById('card_holder_name').value,
            }
        });

        if (error) {
            // Show error
            document.getElementById('card-number-errors').textContent = error.message;

            // Re-enable submit button
            submitButton.disabled = false;
            buttonText.textContent = 'Process Payment via Stripe';
            spinner.classList.add('d-none');
        } else {
            // Add payment method ID to form
            const hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'payment_method_id');
            hiddenInput.setAttribute('value', paymentMethod.id);
            form.appendChild(hiddenInput);

            // Submit form
            form.submit();
        }
    });
</script>
@endsection
