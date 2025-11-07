@extends('layouts.talent')
@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-credit-card mr-2"></i>
                        {{ $profile->hasCardDetails() ? 'Update' : 'Add' }} Card Details
                    </h5>
                </div>
                <div class="card-body">
                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('message') }}
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                        </div>
                    @endif

                    @if($profile->hasCardDetails())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Current Card:</strong> {{ $profile->getMaskedCardNumber() }}
                            <br>
                            <small>You can update your card details below.</small>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            <strong>Important:</strong> You need to provide your card details to receive payments.
                        </div>
                    @endif

                    <form action="{{ route('talent.payments.store-card-details') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label for="card_holder_name" class="required">Card Holder Name</label>
                            <input type="text"
                                   name="card_holder_name"
                                   id="card_holder_name"
                                   class="form-control @error('card_holder_name') is-invalid @enderror"
                                   value="{{ old('card_holder_name', $profile->card_holder_name) }}"
                                   placeholder="Enter name as it appears on card"
                                   required>
                            @error('card_holder_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="card_number" class="required">Card Number</label>
                            <input type="text"
                                   name="card_number"
                                   id="card_number"
                                   class="form-control @error('card_number') is-invalid @enderror"
                                   value="{{ old('card_number') }}"
                                   placeholder="Enter 13-19 digit card number"
                                   maxlength="19"
                                   required>
                            @error('card_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-lock mr-1"></i>
                                Your card information is securely stored and will only be used for payment transfers.
                            </small>
                        </div>

                        <div class="alert alert-light border">
                            <h6 class="font-weight-bold mb-2">
                                <i class="fas fa-shield-alt text-success mr-2"></i>
                                Security Notice
                            </h6>
                            <ul class="mb-0 small">
                                <li>Your card details are stored securely</li>
                                <li>Only masked card number will be visible to you</li>
                                <li>Full card details are never displayed after saving</li>
                                <li>Payments will be sent directly to this card</li>
                            </ul>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('talent.payments.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left mr-1"></i> Back to Payments
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save mr-1"></i>
                                {{ $profile->hasCardDetails() ? 'Update' : 'Save' }} Card Details
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm mt-4">
                <div class="card-body">
                    <h6 class="font-weight-bold mb-3">
                        <i class="fas fa-question-circle text-info mr-2"></i>
                        Frequently Asked Questions
                    </h6>
                    <div class="accordion" id="faqAccordion">
                        <div class="card mb-2">
                            <div class="card-header p-2" id="faq1">
                                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse1">
                                    Why do I need to provide card details?
                                </button>
                            </div>
                            <div id="collapse1" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body small">
                                    We need your card details to directly transfer your earnings. Once approved by the super admin, payments will be sent to this card.
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header p-2" id="faq2">
                                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse2">
                                    Is my card information secure?
                                </button>
                            </div>
                            <div id="collapse2" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body small">
                                    Yes! Your card information is encrypted and stored securely. Only you and authorized payment processors can access this information.
                                </div>
                            </div>
                        </div>
                        <div class="card mb-2">
                            <div class="card-header p-2" id="faq3">
                                <button class="btn btn-link btn-sm" type="button" data-toggle="collapse" data-target="#collapse3">
                                    Can I update my card later?
                                </button>
                            </div>
                            <div id="collapse3" class="collapse" data-parent="#faqAccordion">
                                <div class="card-body small">
                                    Yes, you can update your card details anytime from this page.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('card_number').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '');
    e.target.value = value;
});
</script>
@endsection
