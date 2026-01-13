@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card: #ffffff;
        --ink-900: #101828;
        --ink-700: #344054;
        --ink-500: #667085;
        --border: #eaecf0;
        --shadow: 0 1px 2px rgba(16, 24, 40, 0.04), 0 8px 18px rgba(16, 24, 40, 0.06);
    }

    .rl-shell { padding: 22px 0; font-family: 'Arimo', sans-serif; }
    .rl-header { display:flex; align-items:flex-start; justify-content:space-between; gap:16px; margin-bottom: 18px; }
    .rl-title { margin:0 0 4px 0; color: var(--ink-900); font-size:24px; font-weight:400; line-height:32px; }
    .rl-sub { margin:0; color: var(--ink-500); font-size:14px; }
    .rl-back {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-700);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        text-decoration: none !important;
        white-space: nowrap;
        box-shadow: 0 1px 2px rgba(16, 24, 40, 0.04);
    }
    .rl-back:hover { background:#f9fafb; color: var(--ink-900); text-decoration:none; }

    .rl-wrap { margin: 0 auto; }
    .rl-card {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 16px;
        margin-bottom: 16px;
    }
    .rl-card-head { display:flex; align-items:center; justify-content:space-between; gap:12px; margin-bottom: 12px; }
    .rl-card-title { margin:0; font-size:16px; font-weight:700; color: var(--ink-900); display:flex; align-items:center; gap:10px; }
    .rl-divider { border-top: 1px solid var(--border); margin: 14px 0; }

    .kv-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px 16px; }
    @media (max-width: 768px) { .kv-grid { grid-template-columns: 1fr; } }
    .kv-label { color: var(--ink-500); font-size: 12px; font-weight: 600; margin-bottom: 4px; }
    .kv-value { color: var(--ink-900); font-size: 14px; font-weight: 600; }
    .kv-sub { color: var(--ink-500); font-size: 12px; margin-top: 2px; }
    .amount { color: #12b76a; font-weight: 800; font-size: 18px; }

    .note-box {
        border: 1px solid var(--border);
        background: #f9fafb;
        border-radius: 12px;
        padding: 12px 14px;
        color: var(--ink-700);
        font-size: 13px;
        line-height: 1.5;
    }

    .field-grid { display:grid; grid-template-columns: 1fr 1fr; gap: 12px 16px; }
    @media (max-width: 768px) { .field-grid { grid-template-columns: 1fr; } }
    .field { margin-bottom: 14px; }
    .field label { font-size: 12px; font-weight: 700; color: var(--ink-700); margin-bottom: 6px; display:block; }
    .field .hint { color: var(--ink-500); font-size: 12px; margin-top: 6px; }

    .control {
        width: 100%;
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 12px;
        font-size: 14px;
        color: var(--ink-900);
        outline: none;
        background: #fff;
    }
    .control:focus { border-color:#000; box-shadow: 0 0 0 2px rgba(0,0,0,0.08); }

    /* Stripe Elements containers */
    .stripe-control {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 10px 12px;
        background: #fff;
    }

    .actions-row { display:flex; justify-content: space-between; align-items:center; gap: 12px; margin-top: 16px; }
    .btn-ghost {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-700);
        border-radius: 10px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 700;
        text-decoration: none !important;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }
    .btn-ghost:hover { background:#f9fafb; color: var(--ink-900); }
    .btn-primary {
        border: none;
        background: #000;
        color: #fff;
        border-radius: 10px;
        padding: 12px 16px;
        font-size: 14px;
        font-weight: 800;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        cursor: pointer;
        white-space: nowrap;
    }
    .btn-primary:hover { background:#111; }
    .btn-primary:disabled { opacity: 0.7; cursor:not-allowed; }
    .inline-error { color: #b42318; font-size: 12px; margin-top: 6px; }

    .confirm-row {
        border: 1px solid var(--border);
        border-radius: 12px;
        padding: 12px 14px;
        background: #fff;
        display:flex;
        gap: 10px;
        align-items:flex-start;
    }
    .confirm-row input { margin-top: 2px; }

    .helper-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .helper-table th, .helper-table td { border-top: 1px solid var(--border); padding: 10px 10px; text-align:left; }
    .helper-table th { color: var(--ink-700); font-weight: 700; background:#fafafa; }
    code { background:#f2f4f7; padding: 2px 6px; border-radius: 8px; }
</style>

@php
    $projectName = optional($application->casting_requirement)->project_name ?? 'N/A';
    $talentName = optional(optional($application->talent_profile)->user)->name ?? 'N/A';
    $talentCard = optional($application->talent_profile)->getMaskedCardNumber() ?? '—';
    $amount = $application->getPaymentAmount();
    $last4 = substr((string) ($application->talent_profile->card_number ?? ''), -4);
@endphp

<div class="rl-shell">
    <div class="rl-wrap">
        <div class="rl-header">
            <div>
                <h1 class="rl-title">Release Payment via Stripe</h1>
                <p class="rl-sub">Enter payment details to process the transfer.</p>
            </div>
            <a href="{{ route('admin.payment-requests.index') }}" class="rl-back">
                <i class="fas fa-arrow-left"></i> Back to list
            </a>
        </div>

        @if($errors->any())
            <div class="rl-card" style="border-color:#fecdca; background:#fff5f4;">
                <div style="font-weight:800; color:#b42318; margin-bottom:8px;">
                    <i class="fas fa-exclamation-triangle" style="margin-right:8px;"></i>
                    Please fix the following errors:
                </div>
                <ul style="margin:0; padding-left: 18px; color:#b42318;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="rl-card">
            <div class="rl-card-head">
                <h3 class="rl-card-title"><i class="fas fa-file-invoice-dollar"></i> Payment Details</h3>
            </div>
            <div class="kv-grid">
                <div>
                    <div class="kv-label">Project</div>
                    <div class="kv-value">{{ $projectName }}</div>
                </div>
                <div>
                    <div class="kv-label">Amount Due</div>
                    <div class="kv-value amount">{{ number_format($amount, 2) }} KWD</div>
                </div>
                <div>
                    <div class="kv-label">Talent</div>
                    <div class="kv-value">{{ $talentName }}</div>
                </div>
                <div>
                    <div class="kv-label">Talent's Card</div>
                    <div class="kv-value">{{ $talentCard }}</div>
                </div>
            </div>

            @if($application->rating || $application->reviews)
                <div class="rl-divider"></div>
                <div class="kv-label" style="margin-bottom:6px;">Feedback from Admin</div>
                @if($application->rating)
                    <div style="color:#f59e0b; font-size: 14px; font-weight:700; margin-bottom:6px;">
                        @for($i = 1; $i <= 5; $i++)
                            <i class="{{ $i <= $application->rating ? 'fas' : 'far' }} fa-star"></i>
                        @endfor
                        <span class="kv-sub" style="margin-left:8px;">{{ number_format($application->rating, 1) }}/5</span>
                    </div>
                @endif
                @if($application->reviews)
                    <div class="note-box">“{{ $application->reviews }}”</div>
                @endif
            @endif
        </div>

        <div class="rl-card">
            <div class="rl-card-head">
                <h3 class="rl-card-title"><i class="fab fa-stripe"></i> Super Admin Payment Processing</h3>
            </div>
            <div class="note-box" style="background:#eff8ff; border-color:#b2ddff; color:#175cd3;">
                Please enter your Stripe payment details to process this payment transfer.
            </div>

            <form action="{{ route('admin.payment-requests.release', $application) }}" method="POST" id="payment-form" style="margin-top:14px;">
                @csrf

                <div class="field">
                    <label for="payment_amount">Payment Amount (KWD) *</label>
                    <div style="display:flex; gap:10px; align-items:center;">
                        <div class="stripe-control" style="width:64px; text-align:center; padding: 10px 0; font-weight:800; color: var(--ink-700);">KWD</div>
                        <input
                            type="number"
                            name="payment_amount"
                            id="payment_amount"
                            class="control @error('payment_amount') is-invalid @enderror"
                            value="{{ old('payment_amount', $application->getPaymentAmount()) }}"
                            step="0.01"
                            min="0.01"
                            required
                        >
                    </div>
                    @error('payment_amount')
                        <div class="inline-error">{{ $message }}</div>
                    @enderror
                    <div class="hint">Default amount is based on the approved rate: {{ number_format($application->getPaymentAmount(), 2) }} KWD</div>
                </div>

                <div class="field">
                    <label for="card_holder_name">Card Holder Name *</label>
                    <input
                        type="text"
                        name="card_holder_name"
                        id="card_holder_name"
                        class="control @error('card_holder_name') is-invalid @enderror"
                        value="{{ old('card_holder_name') }}"
                        placeholder="Name as it appears on card"
                        required
                    >
                    @error('card_holder_name')
                        <div class="inline-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="field">
                    <label class="required">Card Number *</label>
                    <div id="card-number-element" class="stripe-control"></div>
                    <div id="card-number-errors" class="inline-error"></div>
                    <div class="hint"><i class="fas fa-lock" style="margin-right:6px;"></i>Use test card: <code>4242 4242 4242 4242</code></div>
                </div>

                <div class="field-grid">
                    <div class="field">
                        <label class="required">Expiry Date *</label>
                        <div id="card-expiry-element" class="stripe-control"></div>
                        <div id="card-expiry-errors" class="inline-error"></div>
                    </div>
                    <div class="field">
                        <label class="required">CVC *</label>
                        <div id="card-cvc-element" class="stripe-control"></div>
                        <div id="card-cvc-errors" class="inline-error"></div>
                    </div>
                </div>

                <div class="field">
                    <label for="payment_description">Payment Description (Optional)</label>
                    <textarea
                        name="payment_description"
                        id="payment_description"
                        class="control @error('payment_description') is-invalid @enderror"
                        rows="3"
                        placeholder="Add any notes about this payment..."
                    >{{ old('payment_description') }}</textarea>
                    @error('payment_description')
                        <div class="inline-error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="note-box" style="background:#fffaeb; border-color:#fedf89; color:#b54708;">
                    <strong>Processing Fee:</strong> Stripe charges approximately 2.9% + 0.30 per transaction.
                    <div class="kv-sub" style="margin-top:6px;">
                        For {{ number_format($application->getPaymentAmount(), 2) }} KWD, the fee will be approximately
                        {{ number_format(($application->getPaymentAmount() * 0.029) + 0.30, 2) }} KWD.
                    </div>
                </div>

                <div class="field" style="margin-top: 14px;">
                    <div class="confirm-row">
                        <input type="checkbox" id="confirm_payment" name="confirm_payment" required>
                        <label for="confirm_payment" style="margin:0; font-size:13px; color: var(--ink-700); font-weight:600;">
                            I confirm that I want to process this payment of <strong>{{ number_format($application->getPaymentAmount(), 2) }} KWD</strong>
                            to <strong>{{ $talentName }}</strong>'s card ending in <strong>{{ $last4 ?: '—' }}</strong>.
                        </label>
                    </div>
                </div>

                <div class="actions-row">
                    <a href="{{ route('admin.payment-requests.index') }}" class="btn-ghost">
                        <i class="fas fa-arrow-left"></i> Cancel
                    </a>
                    <button type="submit" id="submit-button" class="btn-primary">
                        <i class="fab fa-stripe"></i>
                        <span id="button-text">Process Payment via Stripe</span>
                        <span id="spinner" class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </form>
        </div>

        <div class="rl-card">
            <div class="rl-card-head">
                <h3 class="rl-card-title"><i class="fas fa-question-circle" style="color:#175cd3;"></i> Stripe Test Cards</h3>
            </div>
            <table class="helper-table">
                <thead>
                    <tr>
                        <th>Card Number</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><code>4242 4242 4242 4242</code></td>
                        <td style="color:#027a48; font-weight:700;">Success</td>
                    </tr>
                    <tr>
                        <td><code>4000 0000 0000 0002</code></td>
                        <td style="color:#b42318; font-weight:700;">Card Declined</td>
                    </tr>
                    <tr>
                        <td><code>4000 0000 0000 9995</code></td>
                        <td style="color:#b54708; font-weight:700;">Insufficient Funds</td>
                    </tr>
                </tbody>
            </table>
            <div class="kv-sub" style="margin-top:10px;">Use any future expiry date and any 3-digit CVC for testing.</div>
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
            fontFamily: 'Arimo, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif',
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
