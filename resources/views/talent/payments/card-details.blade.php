@extends('layouts.talent')
@section('content')
<style>
    :root {
        --text-dark: #111827;
        --text-gray: #6b7280;
        --bg-light: #f9fafb;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }
    .page-shell { padding: 24px 0; font-family: 'Inter', sans-serif; color: var(--text-dark); }
    .page-container { margin: 0 auto; max-width: 900px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .page-title { font-size: 22px; font-weight: 700; margin: 0; }
    .page-subtitle { font-size: 14px; color: var(--text-gray); margin: 4px 0 0 0; }
    .card-shell { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 20px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
    .form-grid { display: grid; grid-template-columns: 1fr; gap: 16px; }
    .field-block { display: flex; flex-direction: column; gap: 6px; }
    .field-label { font-size: 13px; font-weight: 600; color: var(--text-dark); margin: 0; }
    .field-label .required { color: #ef4444; margin-left: 4px; }
    .input-control { border: 1px solid var(--border-color); border-radius: 10px; padding: 12px 14px; font-size: 14px; color: var(--text-dark); background: #fff; transition: border-color 0.15s, box-shadow 0.15s; }
    .input-control:focus { outline: none; border-color: #111827; box-shadow: 0 0 0 3px rgba(17,24,39,0.1); }
    .help-text { font-size: 12px; color: var(--text-gray); display: flex; align-items: center; gap: 6px; }
    .alert-box { background: #f9fafb; border: 1px solid var(--border-color); border-radius: 10px; padding: 12px 14px; font-size: 13px; color: var(--text-dark); }
    .actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 16px; }
    .btn-outline { border: 1px solid var(--border-color); background: #fff; color: var(--text-dark); border-radius: 10px; padding: 10px 16px; font-weight: 600; font-size: 14px; }
    .btn-primary { border: none; background: #111827; color: #fff; border-radius: 10px; padding: 10px 16px; font-weight: 600; font-size: 14px; }
    .btn-outline:hover { background: #f3f4f6; text-decoration: none; color: var(--text-dark); }
    .btn-primary:hover { background: #0b1020; color: #fff; text-decoration: none; }
    .error-list { margin: 0 0 12px 0; padding: 10px 12px; background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; border-radius: 10px; font-size: 13px; }
    .success-box { margin: 0 0 12px 0; padding: 10px 12px; background: #ecfdf3; border: 1px solid #bbf7d0; color: #065f46; border-radius: 10px; font-size: 13px; }
</style>

<div class="page-shell">
    <div class="page-container">
        <div class="page-header">
            <div>
                <p class="page-title">{{ $profile->hasCardDetails() ? 'Update Card Details' : 'Add Card Details' }}</p>
                <p class="page-subtitle">Securely store your card to receive payments.</p>
            </div>
        </div>

        <div class="card-shell">
            @if(session('message'))
                <div class="success-box">{{ session('message') }}</div>
            @endif

            @if($errors->any())
                <div class="error-list">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if($profile->hasCardDetails())
                <div class="alert-box" style="background:#ecfdf3; border-color:#bbf7d0; color:#065f46;">
                    <strong>Current Card:</strong> {{ $profile->getMaskedCardNumber() }}<br>
                    <small>You can update your card details below.</small>
                </div>
            @else
                <div class="alert-box" style="background:#fffbeb; border-color:#fcd34d; color:#b45309;">
                    <strong>Important:</strong> You need to provide your card details to receive payments.
                </div>
            @endif

            <form action="{{ route('talent.payments.store-card-details') }}" method="POST">
                @csrf
                <div class="form-grid">
                    <div class="field-block">
                        <label for="card_holder_name" class="field-label">Card Holder Name <span class="required">*</span></label>
                        <input type="text"
                               name="card_holder_name"
                               id="card_holder_name"
                               class="input-control @error('card_holder_name') is-invalid @enderror"
                               value="{{ old('card_holder_name', $profile->card_holder_name) }}"
                               placeholder="Enter name as it appears on card"
                               required>
                        @error('card_holder_name')
                            <div class="text-danger" style="font-size:12px;">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="field-block">
                        <label for="card_number" class="field-label">Card Number <span class="required">*</span></label>
                        <input type="text"
                               name="card_number"
                               id="card_number"
                               class="input-control @error('card_number') is-invalid @enderror"
                               value="{{ old('card_number') }}"
                               placeholder="Enter 13-19 digit card number"
                               maxlength="19"
                               required>
                        @error('card_number')
                            <div class="text-danger" style="font-size:12px;">{{ $message }}</div>
                        @enderror
                        <div class="help-text">
                            <i class="fas fa-lock"></i> Your card information is securely stored and will only be used for payment transfers.
                        </div>
                    </div>
                </div>

                <div class="actions">
                    <a href="{{ route('talent.payments.index') }}" class="btn-outline">Back to Payments</a>
                    <button type="submit" class="btn-primary">
                        {{ $profile->hasCardDetails() ? 'Update' : 'Save' }} Card Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('card_number')?.addEventListener('input', function(e) {
    e.target.value = e.target.value.replace(/\s/g, '');
});
</script>
@endsection
