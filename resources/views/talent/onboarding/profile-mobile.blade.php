@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff;
            font-family: 'Arimo', sans-serif;
            color: #1f1f1f;
            padding: 0;
            margin: 0;
        }

        .mobile-onboarding-container {
            min-height: 100vh;
            background: #ffffff;
            padding: 0;
            margin: 0;
        }

        .mobile-card {
            background: #ffffff;
            border-radius: 0;
            padding: 24px 20px;
            max-width: 100%;
            margin: 0 auto;
        }

        .mobile-header {
            text-align: center;
            margin-bottom: 32px;
        }

        .mobile-logo {
            width: 140px;
            height: auto;
            margin: 0 auto 16px;
            display: block;
        }

        .mobile-title {
            font-size: 24px;
            font-weight: 700;
            color: #1c2435;
            margin-bottom: 8px;
            line-height: 1.3;
        }

        .mobile-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 24px;
        }

        .progress-indicator {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 32px;
        }

        .progress-bar {
            height: 4px;
            width: 60px;
            background: #d1d5db;
            border-radius: 2px;
            transition: background 0.3s ease;
        }

        .progress-bar.active {
            background: #000000;
        }

        .mobile-form {
            width: 100%;
        }

        .form-field {
            margin-bottom: 20px;
        }

        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: #1c2435;
            margin-bottom: 8px;
        }

        .required-asterisk {
            color: #dc3545;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            background: #ffffff;
            color: #1c2435;
            transition: border-color 0.2s ease;
        }

        .form-input:focus {
            outline: none;
            border-color: #000000;
        }

        .form-input.is-invalid {
            border-color: #dc3545;
        }

        .form-input::placeholder {
            color: #9ca3af;
        }

        .error-text {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            visibility: hidden;
        }

        .error-text.show {
            visibility: visible;
        }

        .date-input-wrapper {
            position: relative;
        }

        .date-input-wrapper::after {
            content: '📅';
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            font-size: 18px;
        }

        .country-select-wrapper {
            position: relative;
        }

        .country-select-wrapper select {
            padding-right: 40px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%236b7280' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 12px;
        }

        .phone-input-group {
            display: flex;
            gap: 8px;
        }

        .country-code-selector {
            flex: 0 0 auto;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            color: #1c2435;
            min-width: 100px;
        }

        .country-code-selector .fi {
            font-size: 20px;
        }

        .phone-input {
            flex: 1;
            padding: 12px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            background: #e9ecef;
            color: #6b7280;
            cursor: not-allowed;
        }

        .whatsapp-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
        }

        .whatsapp-checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #e5e7eb;
            border-radius: 50%;
            cursor: pointer;
            position: relative;
            flex-shrink: 0;
        }

        .whatsapp-checkbox input {
            opacity: 0;
            position: absolute;
            width: 100%;
            height: 100%;
            cursor: pointer;
        }

        .whatsapp-checkbox input:checked + .checkmark {
            display: block;
        }

        .checkmark {
            display: none;
            width: 12px;
            height: 12px;
            background: #000000;
            border-radius: 50%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .whatsapp-label {
            font-size: 14px;
            color: #1c2435;
            cursor: pointer;
        }

        .whatsapp-number-section {
            display: none;
            margin-top: 16px;
        }

        .whatsapp-number-section.show {
            display: block;
        }

        .next-button {
            width: 100%;
            padding: 16px 24px;
            background: #000000;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            margin-top: 32px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .next-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        }

        .next-button:active {
            transform: translateY(0);
        }

        .next-button:disabled {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .chevron-icon {
            width: 18px;
            height: 18px;
        }

        @media (min-width: 768px) {
            .mobile-onboarding-container {
                display: none;
            }
        }
    </style>
@endsection

@section('content')
<div class="mobile-onboarding-container">
    <div class="mobile-card">
        <div class="mobile-header">
            <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS" class="mobile-logo">
            <h1 class="mobile-title">JOIN THE COMMUNITY</h1>
            <p class="mobile-subtitle">Create your account to get started</p>
            
            <!-- Progress Indicator (5 steps) -->
            <div class="progress-indicator">
                <div class="progress-bar {{ $initialStep >= 1 ? 'active' : '' }}"></div>
                <div class="progress-bar {{ $initialStep >= 2 ? 'active' : '' }}"></div>
                <div class="progress-bar {{ $initialStep >= 3 ? 'active' : '' }}"></div>
                <div class="progress-bar {{ $initialStep >= 4 ? 'active' : '' }}"></div>
                <div class="progress-bar {{ $initialStep >= 5 ? 'active' : '' }}"></div>
            </div>
        </div>

        @if($currentStep == 'step-1')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-1') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step1-form">
            @csrf
            @php
                $whatsappChoice = old('whatsapp_choice');
                if (!$whatsappChoice && isset($profile)) {
                    $whatsappChoice = ($profile->whatsapp_number && $profile->whatsapp_number != $profile->mobile_number) ? 'alt' : 'same';
                }
                $whatsappChoice = $whatsappChoice ?: 'same';
            @endphp

            <!-- First Name -->
            <div class="form-field">
                <label for="mobile_first_name" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.first_name') }} <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_first_name" 
                    name="first_name" 
                    class="form-input" 
                    type="text" 
                    placeholder="John" 
                    value="{{ old('first_name', $profile->first_name) }}" 
                    required 
                    pattern="[a-zA-Z\s]+" 
                    autocomplete="given-name"
                >
                <span class="error-text" id="error-mobile_first_name">First name is required (English letters only)</span>
            </div>

            <!-- Last Name -->
            <div class="form-field">
                <label for="mobile_last_name" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.last_name') }} <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_last_name" 
                    name="last_name" 
                    class="form-input" 
                    type="text" 
                    placeholder="Doe" 
                    value="{{ old('last_name', $profile->last_name) }}" 
                    required 
                    pattern="[a-zA-Z\s]+" 
                    autocomplete="family-name"
                >
                <span class="error-text" id="error-mobile_last_name">Last name is required (English letters only)</span>
            </div>

            <!-- Date of Birth -->
            <div class="form-field">
                <label for="mobile_date_of_birth" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.date_of_birth') }} <span class="required-asterisk">*</span>
                </label>
                <div class="date-input-wrapper">
                    <input 
                        id="mobile_date_of_birth" 
                        name="date_of_birth" 
                        class="form-input" 
                        type="date" 
                        value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" 
                        max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}" 
                        required
                    >
                </div>
                @error('date_of_birth')
                    <span class="error-text show">{{ $message }}</span>
                @enderror
                <span class="error-text" id="error-mobile_date_of_birth">Date of birth is required</span>
            </div>

            <!-- Where are you from? (Nationality) -->
            <div class="form-field">
                <label for="mobile_nationality" class="form-label">
                    Where are you from? <span class="required-asterisk">*</span>
                </label>
                <div class="country-select-wrapper" style="display: flex; align-items: center; gap: 8px;">
                    <span id="mobile_nationality_flag" class="fi" style="font-size: 20px; display: none;"></span>
                    <select id="mobile_nationality" name="nationality" class="form-input" required style="flex: 1;">
                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_nationality') }}</option>
                        <option value="Unspecified" {{ old('nationality', $profile->nationality) == 'Unspecified' ? 'selected' : '' }}>Unspecified</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" {{ old('nationality', $profile->nationality) == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_nationality">Nationality is required</span>
            </div>

            <!-- Mobile Number -->
            <div class="form-field">
                <label for="mobile_number" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.mobile_number') }} <span class="required-asterisk">*</span>
                </label>
                <div class="phone-input-group">
                    <div class="country-code-selector">
                        <span class="fi fi-kw country-flag" title="Kuwait"></span>
                        <span>KW +965</span>
                    </div>
                    <input type="hidden" name="country_code" value="{{ old('country_code', $profile->country_code ?? auth('talent')->user()->phone_country_code ?? 'kw') }}">
                    <input 
                        class="phone-input" 
                        id="mobile_number" 
                        name="mobile_number" 
                        type="tel" 
                        value="{{ old('mobile_number', $profile->mobile_number ?? auth('talent')->user()->phone_number) }}" 
                        readonly 
                        required
                    >
                </div>
            </div>

            <!-- WhatsApp Option -->
            <div class="form-field">
                <div class="whatsapp-checkbox-wrapper">
                    <div class="whatsapp-checkbox">
                        <input 
                            type="checkbox" 
                            id="mobile_whatsapp_checkbox" 
                            name="whatsapp_choice_checkbox"
                            {{ $whatsappChoice == 'alt' ? 'checked' : '' }}
                        >
                        <input type="hidden" id="mobile_whatsapp_choice" name="whatsapp_choice" value="{{ $whatsappChoice }}">
                        <span class="checkmark"></span>
                    </div>
                    <label for="mobile_whatsapp_checkbox" class="whatsapp-label">
                        I have another number for WhatsApp
                    </label>
                </div>
                
                <!-- WhatsApp Number Input (shown when checkbox is checked) -->
                <div class="whatsapp-number-section {{ $whatsappChoice == 'alt' ? 'show' : '' }}" id="mobile_whatsapp_section">
                    <label for="mobile_whatsapp_number" class="form-label" style="margin-top: 16px;">
                        {{ \App\Helpers\Bilingual::get('onboarding.whatsapp_number') }} <span class="required-asterisk">*</span>
                    </label>
                    <div class="phone-input-group">
                        <div class="country-code-selector">
                            <span class="fi fi-kw country-flag" title="Kuwait"></span>
                            <span>+965</span>
                        </div>
                        <input type="hidden" name="whatsapp_country_code" value="kw">
                        <input 
                            class="form-input" 
                            id="mobile_whatsapp_number" 
                            name="whatsapp_number" 
                            type="tel" 
                            value="{{ old('whatsapp_number', $profile->whatsapp_number) }}" 
                            maxlength="8" 
                            pattern="\d*"
                            placeholder="XXXX XXXX"
                        >
                    </div>
                    <span id="mobile-whatsapp-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: 4px;">WhatsApp number must be 8 digits</span>
                </div>
            </div>

            <!-- Next Button -->
            <button type="submit" class="next-button">
                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </form>
        @endif

        @if($currentStep != 'step-1')
        <div style="text-align: center; padding: 40px 20px;">
            <p style="color: #6b7280;">Step {{ $currentStep }} will be implemented next.</p>
        </div>
        @endif
    </div>
</div>

<script>
(function() {
    'use strict';
    
    const form = document.getElementById('mobile-step1-form');
    if (!form) return;

    const whatsappCheckbox = document.getElementById('mobile_whatsapp_checkbox');
    const whatsappChoiceInput = document.getElementById('mobile_whatsapp_choice');
    const whatsappSection = document.getElementById('mobile_whatsapp_section');
    const whatsappNumberInput = document.getElementById('mobile_whatsapp_number');
    const whatsappError = document.getElementById('mobile-whatsapp-error');

    // WhatsApp checkbox toggle
    if (whatsappCheckbox) {
        whatsappCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            whatsappChoiceInput.value = isChecked ? 'alt' : 'same';
            
            if (whatsappSection) {
                if (isChecked) {
                    whatsappSection.classList.add('show');
                    if (whatsappNumberInput) {
                        whatsappNumberInput.setAttribute('required', 'required');
                    }
                } else {
                    whatsappSection.classList.remove('show');
                    if (whatsappNumberInput) {
                        whatsappNumberInput.removeAttribute('required');
                        whatsappNumberInput.classList.remove('is-invalid');
                        if (whatsappError) whatsappError.style.display = 'none';
                    }
                }
            }
        });
    }

    // Restrict WhatsApp input to numbers only
    if (whatsappNumberInput) {
        whatsappNumberInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/\D/g, '');
            
            if (this.classList.contains('is-invalid')) {
                this.classList.remove('is-invalid');
                if (whatsappError) whatsappError.style.display = 'none';
            }
        });
    }

    // Name inputs - English only, auto-capitalize
    ['mobile_first_name', 'mobile_last_name'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            const capitalizeName = (text) => {
                text = text.replace(/[^a-zA-Z\s]/g, '');
                return text.toLowerCase().replace(/\b\w/g, function(char) {
                    return char.toUpperCase();
                });
            };
            
            el.addEventListener('input', function(e) {
                const cursorPos = this.selectionStart;
                const oldValue = this.value;
                const newValue = capitalizeName(this.value);
                
                this.value = newValue;
                
                const diff = newValue.length - oldValue.length;
                this.setSelectionRange(cursorPos + diff, cursorPos + diff);
            });
            
            el.addEventListener('paste', function(e) {
                e.preventDefault();
                let pastedText = (e.clipboardData || window.clipboardData).getData('text');
                pastedText = capitalizeName(pastedText);
                this.value = pastedText;
            });
            
            el.addEventListener('keydown', function(e) {
                if ([8, 9, 27, 13, 46, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
                    (e.keyCode === 65 && e.ctrlKey === true) ||
                    (e.keyCode === 67 && e.ctrlKey === true) ||
                    (e.keyCode === 86 && e.ctrlKey === true) ||
                    (e.keyCode === 88 && e.ctrlKey === true)) {
                    return;
                }
                if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 90)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode !== 32) {
                    e.preventDefault();
                }
            });
        }
    });

    // Form validation
    form.addEventListener('submit', function(e) {
        let isValid = true;
        const requiredFields = [
            { id: 'mobile_first_name', msg: 'First name is required' },
            { id: 'mobile_last_name', msg: 'Last name is required' },
            { id: 'mobile_date_of_birth', msg: 'Date of birth is required' },
            { id: 'mobile_nationality', msg: 'Nationality is required' }
        ];

        // Validate core fields
        requiredFields.forEach(field => {
            const el = document.getElementById(field.id);
            const errEl = document.getElementById('error-' + field.id);

            if (el && !el.value.trim()) {
                isValid = false;
                el.classList.add('is-invalid');
                if (errEl) errEl.classList.add('show');

                el.addEventListener('input', function() {
                    this.classList.remove('is-invalid');
                    if (errEl) errEl.classList.remove('show');
                }, { once: true });

                if (el.tagName === 'SELECT') {
                    el.addEventListener('change', function() {
                        this.classList.remove('is-invalid');
                        if (errEl) errEl.classList.remove('show');
                    }, { once: true });
                }
            }
        });

        // Validate WhatsApp if alternate is selected
        if (whatsappCheckbox && whatsappCheckbox.checked && whatsappNumberInput) {
            const val = whatsappNumberInput.value.replace(/\D/g, '');
            if (val.length !== 8) {
                isValid = false;
                whatsappNumberInput.classList.add('is-invalid');
                if (whatsappError) whatsappError.style.display = 'block';
            }
        }

        if (!isValid) {
            e.preventDefault();
        }
    });

    // Initialize WhatsApp section visibility
    if (whatsappCheckbox && whatsappCheckbox.checked && whatsappSection) {
        whatsappSection.classList.add('show');
        if (whatsappNumberInput) {
            whatsappNumberInput.setAttribute('required', 'required');
        }
    }

    // Nationality Flag Logic
    const mobileNatSelect = document.getElementById('mobile_nationality');
    const mobileNatFlag = document.getElementById('mobile_nationality_flag');

    function toggleMobileNationalityFlag() {
        if (mobileNatSelect && mobileNatFlag) {
            const code = mobileNatSelect.value ? mobileNatSelect.value.toLowerCase() : '';
            if (code && code !== 'unspecified') {
                mobileNatFlag.className = `fi fi-${code}`;
                mobileNatFlag.style.display = 'inline-block';
            } else {
                mobileNatFlag.style.display = 'none';
            }
        }
    }
    
    if (mobileNatSelect) {
        mobileNatSelect.addEventListener('change', toggleMobileNationalityFlag);
        toggleMobileNationalityFlag(); // Init
    }
})();
</script>
@endsection
