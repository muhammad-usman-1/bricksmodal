@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @php
        $adminSettings = $adminSettings ?? \App\Models\AdminSetting::singleton();
    @endphp
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Arimo', sans-serif;
            color: #1f1f1f;
            padding: 0;
            margin: 0;
        }

        .mobile-onboarding-container {
            min-height: 100vh;
            padding: 20px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-card {
            background: #ffffff;
            border-radius: 20px;
            padding: 24px 20px;
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
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

        /* Gender Buttons */
        .gender-buttons {
            display: flex;
            gap: 12px;
            margin-top: 8px;
        }

        .gender-btn {
            flex: 1;
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            background: #ffffff;
            color: #1c2435;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.2s ease;
        }

        .gender-btn:hover {
            border-color: #000000;
        }

        .gender-btn.active {
            background: #1c2435;
            color: #ffffff;
            border-color: #1c2435;
        }

        .gender-icon {
            font-size: 18px;
        }

        /* Size Select with Icon */
        .size-select-wrapper {
            position: relative;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .size-icon {
            font-size: 24px;
            flex-shrink: 0;
        }

        .size-select-wrapper .form-input {
            flex: 1;
            padding-right: 40px;
        }

        /* Chip/Button Groups */
        .chip-group {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .chip {
            padding: 10px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            background: #ffffff;
            color: #1c2435;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .chip:hover {
            border-color: #000000;
        }

        .chip.active {
            background: #000000;
            color: #ffffff;
            border-color: #000000;
        }

        /* Toggle Section */
        .toggle-section {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 20px;
            margin-top: 24px;
        }

        .toggle-section-title {
            font-size: 16px;
            font-weight: 600;
            color: #1c2435;
            margin-bottom: 16px;
        }

        .toggle-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }

        .toggle-item:last-child {
            border-bottom: none;
        }

        .toggle-label {
            font-size: 14px;
            color: #1c2435;
            font-weight: 500;
        }

        /* Toggle Switch */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }

        .toggle-switch input[type="radio"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #d1d5db;
            transition: 0.3s;
            border-radius: 28px;
        }

        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        .toggle-switch input[type="radio"]:first-of-type:checked ~ .toggle-slider {
            background-color: #d1d5db;
        }

        .toggle-switch input[type="radio"]:first-of-type:checked ~ .toggle-slider:before {
            transform: translateX(0);
        }

        .toggle-switch input[type="radio"]:last-of-type:checked ~ .toggle-slider {
            background-color: #000000;
        }

        .toggle-switch input[type="radio"]:last-of-type:checked ~ .toggle-slider:before {
            transform: translateX(22px);
        }

        /* Step 4 - ID Document Upload Styles */
        .id-info-box {
            background: #f3f4f6;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
        }

        .id-examples {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .id-example-card {
            flex: 1;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            background: #ffffff;
        }

        .id-example-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 12px;
            font-weight: 500;
        }

        .id-example-placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 120px;
            background: #f9fafb;
            border-radius: 8px;
        }

        .upload-card-mobile {
            display: block;
            border: 2px dashed #e5e7eb;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .upload-card-mobile:hover {
            border-color: #000000;
            background: #f9fafb;
        }

        .upload-card-mobile.uploaded {
            border-color: #10b981;
            background: #f0fdf9;
        }

        .upload-inner-mobile {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }

        .upload-icon-mobile {
            color: #6b7280;
            transition: color 0.2s ease;
        }

        .upload-card-mobile:hover .upload-icon-mobile {
            color: #000000;
        }

        .upload-label-mobile {
            font-size: 16px;
            font-weight: 600;
            color: #1c2435;
        }

        .progress-bar-container-mobile {
            width: 100%;
            height: 6px;
            background: #e5e7eb;
            border-radius: 3px;
            overflow: hidden;
            margin-top: 12px;
        }

        .progress-bar-fill-mobile {
            height: 100%;
            background: #10b981;
            transition: width 0.3s ease;
            border-radius: 3px;
        }

        .security-message {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            padding: 16px;
            background: #fef2f2;
            border: 1px solid #fecaca;
            border-radius: 12px;
            margin-top: 24px;
            margin-bottom: 32px;
        }

        .security-message svg {
            color: #dc2626;
            margin-top: 2px;
        }

        .security-message span {
            font-size: 13px;
            color: #991b1b;
            line-height: 1.5;
        }

        /* Step 5 - Profile Photo Styles */
        .profile-photo-upload {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .profile-photo-circle {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f9fafb;
            overflow: hidden;
            position: relative;
        }

        .profile-photo-circle img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
        }

        .profile-photo-btn {
            padding: 12px 24px;
            background: #1c2435;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .profile-photo-btn:hover {
            background: #000000;
            transform: translateY(-1px);
        }

        .photo-previews-mobile {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-top: 16px;
        }

        .photo-preview-item-mobile {
            position: relative;
            aspect-ratio: 1;
            border-radius: 8px;
            overflow: hidden;
            border: 2px solid #e5e7eb;
        }

        .photo-preview-item-mobile img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-preview-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: rgba(0, 0, 0, 0.2);
        }

        .photo-preview-progress-fill {
            height: 100%;
            background: #10b981;
            transition: width 0.3s ease;
        }

        .photo-preview-error {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(239, 68, 68, 0.9);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            padding: 4px;
            text-align: center;
        }

        .photo-preview-remove {
            position: absolute;
            top: 4px;
            right: 4px;
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            line-height: 1;
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

        @if($currentStep == 'step-2')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-2') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step2-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <h1 class="mobile-title">BODY DETAILS</h1>
                <p class="mobile-subtitle">Enter your measurements, sizing, and physical details</p>
                
                <!-- Progress Indicator (5 steps) -->
                <div class="progress-indicator">
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar"></div>
                    <div class="progress-bar"></div>
                    <div class="progress-bar"></div>
                </div>
            </div>

            <!-- Height -->
            <div class="form-field">
                <label for="mobile_height" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.height') }} <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_height" 
                    name="height" 
                    class="form-input" 
                    type="number" 
                    step="0.1" 
                    placeholder="175 cm" 
                    value="{{ old('height', $profile->height) }}" 
                    required
                >
                <span class="error-text" id="error-mobile_height">Height is required</span>
            </div>

            <!-- Weight -->
            <div class="form-field">
                <label for="mobile_weight" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.weight') }} <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_weight" 
                    name="weight" 
                    class="form-input" 
                    type="number" 
                    step="0.1" 
                    placeholder="70 kg" 
                    value="{{ old('weight', $profile->weight) }}" 
                    required
                >
                <span class="error-text" id="error-mobile_weight">Weight is required</span>
            </div>

            <!-- Gender Selection -->
            <div class="form-field">
                <label class="form-label">{{ \App\Helpers\Bilingual::get('onboarding.select_gender') }} <span class="required-asterisk">*</span></label>
                <div class="gender-buttons">
                    <button type="button" class="gender-btn {{ old('gender', $profile->gender ?? 'male') == 'male' ? 'active' : '' }}" data-gender="male">
                        <span class="gender-icon">♂</span>
                        {{ \App\Helpers\Bilingual::get('onboarding.male') }}
                    </button>
                    <button type="button" class="gender-btn {{ old('gender', $profile->gender ?? 'male') == 'female' ? 'active' : '' }}" data-gender="female">
                        <span class="gender-icon">♀</span>
                        {{ \App\Helpers\Bilingual::get('onboarding.female') }}
                    </button>
                </div>
                <input type="hidden" name="gender" id="mobile_gender" value="{{ old('gender', $profile->gender ?? 'male') }}" required>
            </div>

            <!-- Skin Color Selection -->
            <div class="form-field">
                <label class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.skin_tone') }} <span class="required-asterisk">*</span>
                </label>
                <div class="chip-group">
                    @php
                        $skinOptions = ['Fair', 'Light', 'Medium', 'Tan', 'Rich', 'Deep', 'Dark Brown', 'Brown'];
                        $skinSelected = old('skin_tone', $profile->skin_tone);
                    @endphp
                    @foreach($skinOptions as $option)
                        <button type="button" class="chip {{ $skinSelected == $option ? 'active' : '' }}" data-value="{{ $option }}">
                            {{ $option }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="skin_tone" id="mobile_skin_tone" value="{{ $skinSelected }}" required>
                <span class="error-text" id="error-mobile_skin_tone">Skin color is required</span>
            </div>

            <!-- Hair Color Selection -->
            <div class="form-field">
                <label class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.hair_color') }} <span class="required-asterisk">*</span>
                </label>
                <div class="chip-group">
                    @php
                        $hairOptions = ['Black', 'Brown', 'Chocolate', 'Auburn', 'Blonde', 'Red', 'Grey', 'White', 'Bald', 'Dyed / Colored'];
                        $hairSelected = old('hair_color', $profile->hair_color);
                    @endphp
                    @foreach($hairOptions as $option)
                        <button type="button" class="chip {{ $hairSelected == $option ? 'active' : '' }}" data-value="{{ $option }}">
                            {{ $option }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="hair_color" id="mobile_hair_color" value="{{ $hairSelected }}" required>
                <span class="error-text" id="error-mobile_hair_color">Hair color is required</span>
            </div>

            <!-- Eye Color Selection -->
            <div class="form-field">
                <label class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.eye_color') }} <span class="required-asterisk">*</span>
                </label>
                <div class="chip-group">
                    @php
                        $eyeOptions = ['Black', 'Brown', 'Blue', 'Green', 'Hazel', 'Gray', 'Amber'];
                        $eyeSelected = old('eye_color', $profile->eye_color);
                    @endphp
                    @foreach($eyeOptions as $option)
                        <button type="button" class="chip {{ $eyeSelected == $option ? 'active' : '' }}" data-value="{{ $option }}">
                            {{ $option }}
                        </button>
                    @endforeach
                </div>
                <input type="hidden" name="eye_color" id="mobile_eye_color" value="{{ $eyeSelected }}" required>
                <span class="error-text" id="error-mobile_eye_color">Eye color is required</span>
            </div>

            <!-- Toggle Switches Section -->
            <div class="toggle-section">
                <h3 class="toggle-section-title">Do you have any of the following?</h3>
                
                <!-- Visible Tattoos -->
                <div class="toggle-item">
                    <label class="toggle-label">{{ \App\Helpers\Bilingual::get('onboarding.visible_tattoos') }}</label>
                    <label class="toggle-switch">
                        <input type="radio" name="has_visible_tattoos" value="0" {{ old('has_visible_tattoos', $profile->has_visible_tattoos ?? 0) == 0 ? 'checked' : '' }} required>
                        <input type="radio" name="has_visible_tattoos" value="1" {{ old('has_visible_tattoos', $profile->has_visible_tattoos ?? 0) == 1 ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <!-- Visible Piercings -->
                <div class="toggle-item">
                    <label class="toggle-label">{{ \App\Helpers\Bilingual::get('onboarding.piercings') }}</label>
                    <label class="toggle-switch">
                        <input type="radio" name="has_piercings" value="0" {{ old('has_piercings', $profile->has_piercings ?? 0) == 0 ? 'checked' : '' }} required>
                        <input type="radio" name="has_piercings" value="1" {{ old('has_piercings', $profile->has_piercings ?? 0) == 1 ? 'checked' : '' }}>
                        <span class="toggle-slider"></span>
                    </label>
                </div>

                <!-- Visible Scars (Optional - not saved in backend yet) -->
                <div class="toggle-item">
                    <label class="toggle-label">Visible Scars</label>
                    <label class="toggle-switch">
                        <input type="radio" name="has_visible_scars" value="0" checked>
                        <input type="radio" name="has_visible_scars" value="1">
                        <span class="toggle-slider"></span>
                    </label>
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

        @if($currentStep == 'step-3')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step3-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <h1 class="mobile-title">SIZING</h1>
                <p class="mobile-subtitle">Select your clothing and shoe sizes</p>
                
                <!-- Progress Indicator (5 steps) -->
                <div class="progress-indicator">
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar"></div>
                    <div class="progress-bar"></div>
                </div>
            </div>

            <!-- T-Shirt Size -->
            <div class="form-field">
                <label for="mobile_t_shirt_size" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.t_shirt_size') }} <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <span class="size-icon">👕</span>
                    <select id="mobile_t_shirt_size" name="t_shirt_size" class="form-input" required>
                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_size') }}</option>
                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <option value="{{ $size }}" {{ old('t_shirt_size', $profile->t_shirt_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_t_shirt_size">T-shirt size is required</span>
            </div>

            <!-- Dress Size (only for females) -->
            @php
                $isFemale = strtolower($profile->gender ?? 'male') === 'female';
            @endphp
            @if($isFemale)
            <div class="form-field" id="mobile_dress_size_field">
                <label for="mobile_dress_size" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.dress_size') }} <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <span class="size-icon">👗</span>
                    <select id="mobile_dress_size" name="dress_size" class="form-input" required>
                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_size') }}</option>
                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <option value="{{ $size }}" {{ old('dress_size', $profile->dress_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_dress_size">Dress size is required</span>
            </div>
            @else
            <input type="hidden" name="dress_size" value="">
            @endif

            <!-- Shoe Size -->
            <div class="form-field">
                <label for="mobile_shoe_size" class="form-label">
                    {{ \App\Helpers\Bilingual::get('onboarding.shoe_size') }} <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <span class="size-icon">👟</span>
                    <input 
                        id="mobile_shoe_size" 
                        name="shoe_size" 
                        class="form-input" 
                        type="number" 
                        step="0.1" 
                        placeholder="Select Size" 
                        value="{{ old('shoe_size', $profile->shoe_size) }}" 
                        required
                    >
                </div>
                <span class="error-text" id="error-mobile_shoe_size">Shoe size is required</span>
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

        @if($currentStep == 'step-4')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step4-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <h1 class="mobile-title">VERIFY YOURSELF</h1>
                <p class="mobile-subtitle">Upload your government-issued Civil ID</p>
                
                <!-- Progress Indicator (5 steps) -->
                <div class="progress-indicator">
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar"></div>
                </div>
            </div>

            <!-- ID Document Info -->
            <div class="id-info-box">
                <p style="font-size: 14px; color: #1c2435; line-height: 1.5; margin: 0;">
                    {!! \Illuminate\Support\Facades\Lang::get('onboarding.id_document_info', [], 'en') !!}
                </p>
            </div>

            <!-- ID Card Examples (Visual Reference) -->
            <div class="id-examples">
                <div class="id-example-card">
                    <div class="id-example-label">Front</div>
                    <div class="id-example-placeholder">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <path d="M9 9h6v6H9z"/>
                        </svg>
                    </div>
                </div>
                <div class="id-example-card">
                    <div class="id-example-label">Back</div>
                    <div class="id-example-placeholder">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <path d="M9 9h6v6H9z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Upload Button -->
            <div class="form-field">
                <label class="upload-card-mobile" for="mobile_upload_id_document" id="mobile_upload_label">
                    <input id="mobile_upload_id_document" name="id_document_front" type="file" accept="image/*" style="display:none;">
                    <div class="upload-inner-mobile">
                        <div class="upload-icon-mobile">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <div class="upload-label-mobile" id="mobile_upload_label_text">Upload ID document</div>
                        <div class="progress-bar-container-mobile" id="mobile_upload_progress" style="display:none;">
                            <div class="progress-bar-fill-mobile" id="mobile_upload_progress_fill" style="width: 0%;"></div>
                        </div>
                    </div>
                </label>
                <input type="hidden" id="mobile_id_document_key" name="id_document_key" value="">
                @error('id_document_front')
                    <span class="error-text show" style="margin-top: 8px;">{{ $message }}</span>
                @enderror
                <div id="mobile_upload_success" style="display: none; color: #10b981; font-size: 14px; margin-top: 8px; font-weight: 500;">
                    ✓ Document uploaded successfully
                </div>
            </div>

            <!-- Security Message -->
            <div class="security-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                <span>Your information is securely verified and protected with industry-leading encryption</span>
            </div>

            <!-- Next Button -->
            <button type="submit" class="next-button" id="mobile_step4_submit">
                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 18l6-6-6-6"/>
                </svg>
            </button>
        </form>
        @endif

        @if($currentStep == 'step-5')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-5') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step5-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <h1 class="mobile-title">PROFILE PHOTO</h1>
                <p class="mobile-subtitle">Upload a clear headshot</p>
                
                <!-- Progress Indicator (5 steps) -->
                <div class="progress-indicator">
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                    <div class="progress-bar active"></div>
                </div>
            </div>

            <!-- Profile Photo Upload -->
            <div class="form-field">
                <label class="profile-photo-upload" for="mobile_profile_photo">
                    <input id="mobile_profile_photo" name="profile_photo" type="file" accept="image/*" style="display:none;">
                    <div class="profile-photo-circle" id="mobile_profile_photo_preview">
                        <svg width="60" height="60" viewBox="0 0 24 24" fill="none" stroke="#9ca3af" stroke-width="1.5">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                    </div>
                    <button type="button" class="profile-photo-btn" onclick="document.getElementById('mobile_profile_photo').click()">
                        Upload profile photo
                    </button>
                    <div class="progress-bar-container-mobile" id="mobile_profile_photo_progress" style="display:none; margin-top: 12px;">
                        <div class="progress-bar-fill-mobile" id="mobile_profile_photo_progress_fill" style="width: 0%;"></div>
                    </div>
                    <input type="hidden" id="mobile_profile_photo_key" name="profile_photo_key" value="">
                </label>
            </div>

            <!-- Additional Photos Upload -->
            <div class="form-field">
                <label class="form-label">{{ \App\Helpers\Bilingual::get('onboarding.add_photos') }}</label>
                <label class="upload-card-mobile" for="mobile_additional_photos">
                    <input type="file" id="mobile_additional_photos" name="additional_photos[]" multiple accept="image/*" style="display:none;">
                    <div class="upload-inner-mobile">
                        <div class="upload-icon-mobile">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <div class="upload-label-mobile" id="mobile_additional_photos_label">
                            Upload up to 10 clear, natural photos (no filters) to improve your chances of being selected.
                        </div>
                    </div>
                </label>
                <div id="mobile_additional_photos_previews" class="photo-previews-mobile"></div>
                <div id="mobile_step5_photo_keys" style="display:none;"></div>
            </div>

            <!-- Video Upload -->
            <div class="form-field">
                <label class="form-label">{{ \App\Helpers\Bilingual::get('onboarding.profile_video') }}</label>
                <label class="upload-card-mobile" for="mobile_video_upload">
                    <input id="mobile_video_upload" name="video" type="file" accept="video/*" style="display:none;">
                    <div class="upload-inner-mobile">
                        <div class="upload-icon-mobile">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                                <polyline points="17 8 12 3 7 8"/>
                                <line x1="12" y1="3" x2="12" y2="15"/>
                            </svg>
                        </div>
                        <div class="upload-label-mobile" id="mobile_video_label">Add Video Optional (0)</div>
                        <div class="progress-bar-container-mobile" id="mobile_video_progress" style="display:none;">
                            <div class="progress-bar-fill-mobile" id="mobile_video_progress_fill" style="width: 0%;"></div>
                        </div>
                    </div>
                </label>
                <input type="hidden" id="mobile_video_key" name="video_key" value="">
                @error('video')
                    <span class="error-text show" style="margin-top: 8px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="next-button" id="mobile_step5_submit">
                {{ \App\Helpers\Bilingual::get('onboarding.submit_application') }}
            </button>
        </form>
        @endif

        @if($currentStep != 'step-1' && $currentStep != 'step-2' && $currentStep != 'step-3' && $currentStep != 'step-4' && $currentStep != 'step-5')
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

    // Step 2 Form Logic
    const step2Form = document.getElementById('mobile-step2-form');
    if (step2Form) {
        // Gender Selection
        const genderButtons = document.querySelectorAll('.gender-btn');
        const genderInput = document.getElementById('mobile_gender');

        genderButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const gender = this.dataset.gender;
                genderInput.value = gender;
                
                genderButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Chip Selection (Skin, Hair, Eye Color)
        const chipGroups = document.querySelectorAll('.chip-group');
        chipGroups.forEach(group => {
            const chips = group.querySelectorAll('.chip');
            chips.forEach(chip => {
                chip.addEventListener('click', function() {
                    const value = this.dataset.value;
                    const hiddenInput = group.parentElement.querySelector('input[type="hidden"]');
                    
                    chips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (hiddenInput) {
                        hiddenInput.value = value;
                    }
                });
            });
        });

        // Toggle Switch Logic - handled by CSS, but ensure proper state on load
        const toggleSwitches = document.querySelectorAll('.toggle-switch');
        toggleSwitches.forEach(toggle => {
            const radios = toggle.querySelectorAll('input[type="radio"]');
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // CSS handles the visual state
                });
            });
        });

        // Form Validation
        step2Form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = [
                { id: 'mobile_height', msg: 'Height is required', min: 50, max: 300, errorMsg: 'Height must be between 50 and 300 cm' },
                { id: 'mobile_weight', msg: 'Weight is required', min: 40, max: 200, errorMsg: 'Weight must be between 40 and 200 kg' },
                { id: 'mobile_skin_tone', msg: 'Skin color is required' },
                { id: 'mobile_hair_color', msg: 'Hair color is required' },
                { id: 'mobile_eye_color', msg: 'Eye color is required' }
            ];

            // Validate height and weight ranges
            const heightEl = document.getElementById('mobile_height');
            if (heightEl && heightEl.value) {
                const hVal = parseFloat(heightEl.value);
                if (hVal < 50 || hVal > 300) {
                    isValid = false;
                    heightEl.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_height');
                    if (errEl) {
                        errEl.textContent = 'Height must be between 50 and 300 cm';
                        errEl.classList.add('show');
                    }
                }
            }

            const weightEl = document.getElementById('mobile_weight');
            if (weightEl && weightEl.value) {
                const wVal = parseFloat(weightEl.value);
                if (wVal < 40 || wVal > 200) {
                    isValid = false;
                    weightEl.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_weight');
                    if (errEl) {
                        errEl.textContent = 'Weight must be between 40 and 200 kg';
                        errEl.classList.add('show');
                    }
                }
            }

            // Validate other required fields
            requiredFields.forEach(field => {
                const el = document.getElementById(field.id);
                const errEl = document.getElementById('error-' + field.id);

                if (el && !el.value.trim()) {
                    isValid = false;
                    el.classList.add('is-invalid');
                    if (errEl) {
                        errEl.textContent = field.msg;
                        errEl.classList.add('show');
                    }

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

            if (!isValid) {
                e.preventDefault();
            }
        });

    }

    // Step 3 Form Logic
    const step3Form = document.getElementById('mobile-step3-form');
    if (step3Form) {
        // Form Validation
        step3Form.addEventListener('submit', function(e) {
            let isValid = true;
            const requiredFields = [
                { id: 'mobile_t_shirt_size', msg: 'T-shirt size is required' },
                { id: 'mobile_shoe_size', msg: 'Shoe size is required' }
            ];

            // Validate dress size if female
            const dressSizeField = document.getElementById('mobile_dress_size_field');
            const dressSizeSelect = document.getElementById('mobile_dress_size');
            if (dressSizeField && dressSizeField.style.display !== 'none' && dressSizeSelect) {
                if (!dressSizeSelect.value) {
                    isValid = false;
                    dressSizeSelect.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_dress_size');
                    if (errEl) {
                        errEl.textContent = 'Dress size is required';
                        errEl.classList.add('show');
                    }
                }
            }

            // Validate other required fields
            requiredFields.forEach(field => {
                const el = document.getElementById(field.id);
                const errEl = document.getElementById('error-' + field.id);

                if (el && !el.value.trim()) {
                    isValid = false;
                    el.classList.add('is-invalid');
                    if (errEl) {
                        errEl.textContent = field.msg;
                        errEl.classList.add('show');
                    }

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

            if (!isValid) {
                e.preventDefault();
            }
        });

        // Restrict shoe size to 2 digits
        const shoeSizeInput = document.getElementById('mobile_shoe_size');
        if (shoeSizeInput) {
            shoeSizeInput.addEventListener('input', function() {
                if (this.value.length > 2) {
                    this.value = this.value.slice(0, 2);
                }
            });
        }
    }

    // Step 4 - ID Document Upload Logic
    const step4Form = document.getElementById('mobile-step4-form');
    if (step4Form) {
        const idUploadInput = document.getElementById('mobile_upload_id_document');
        const idKeyInput = document.getElementById('mobile_id_document_key');
        const uploadCard = document.getElementById('mobile_upload_label');
        const uploadLabelText = document.getElementById('mobile_upload_label_text');
        const progressContainer = document.getElementById('mobile_upload_progress');
        const progressFill = document.getElementById('mobile_upload_progress_fill');
        const uploadSuccess = document.getElementById('mobile_upload_success');
        const submitButton = document.getElementById('mobile_step4_submit');

        // Image compression utility
        const compressImage = (file, maxSizeMB = 10, quality = 0.75) => {
            return new Promise((resolve, reject) => {
                if (file.type.indexOf('image/') === -1) {
                    resolve(file);
                    return;
                }

                const shouldCompress = file.size > maxSizeMB * 1024 * 1024;
                if (!shouldCompress && file.size <= 1 * 1024 * 1024) {
                    resolve(file);
                    return;
                }

                let compressionQuality = quality;
                if (file.size > 20 * 1024 * 1024) {
                    compressionQuality = 0.65;
                } else if (file.size > 10 * 1024 * 1024) {
                    compressionQuality = 0.7;
                }

                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = event => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        let width = img.width;
                        let height = img.height;
                        let MAX_DIMENSION = 2048;
                        if (file.size > 20 * 1024 * 1024) {
                            MAX_DIMENSION = 1920;
                        } else if (file.size > 10 * 1024 * 1024) {
                            MAX_DIMENSION = 2048;
                        }

                        if (width > MAX_DIMENSION || height > MAX_DIMENSION) {
                            if (width > height) {
                                height *= MAX_DIMENSION / width;
                                width = MAX_DIMENSION;
                            } else {
                                width *= MAX_DIMENSION / height;
                                height = MAX_DIMENSION;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(blob => {
                            if (!blob) {
                                resolve(file);
                                return;
                            }
                            const newFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            if (shouldCompress || newFile.size < file.size) {
                                resolve(newFile);
                            } else {
                                resolve(file);
                            }
                        }, 'image/jpeg', compressionQuality);
                    };
                };
                reader.onerror = error => reject(error);
            });
        };

        // File input change handler
        if (idUploadInput) {
            idUploadInput.addEventListener('change', async function() {
                if (this.files.length === 0) return;

                const file = this.files[0];

                // Validate image type
                if (!file.type.startsWith('image/')) {
                    alert('Only image files are allowed for ID document.');
                    this.value = '';
                    return;
                }

                // Show progress
                progressContainer.style.display = 'block';
                progressFill.style.width = '0%';
                uploadCard.classList.remove('uploaded');
                uploadSuccess.style.display = 'none';
                uploadLabelText.textContent = 'Compressing...';
                submitButton.disabled = true;

                try {
                    // Compress image if needed
                    const compressedFile = await compressImage(file, 10, 0.75);
                    
                    // Update input with compressed file
                    const dt = new DataTransfer();
                    dt.items.add(compressedFile);
                    this.files = dt.files;

                    uploadLabelText.textContent = 'Uploading...';
                    progressFill.style.width = '10%';

                    // Get presigned URL
                    const presignRes = await fetch('{{ route("talent.onboarding.presign-id-document") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            file_name: compressedFile.name,
                            file_type: compressedFile.type,
                        }),
                    });

                    if (!presignRes.ok) {
                        let msg = 'Could not prepare upload.';
                        try {
                            const data = await presignRes.json();
                            msg = data.message || msg;
                        } catch (e) {}
                        throw new Error(msg);
                    }

                    const presign = await presignRes.json();

                    // Upload to S3 with progress
                    await new Promise((resolve, reject) => {
                        const xhr = new XMLHttpRequest();
                        xhr.open('PUT', presign.url, true);
                        xhr.setRequestHeader('Content-Type', presign.headers['Content-Type'] || compressedFile.type);

                        xhr.upload.addEventListener('progress', (e) => {
                            if (e.lengthComputable) {
                                const pct = Math.round((e.loaded / e.total) * 100);
                                progressFill.style.width = `${pct}%`;
                            }
                        });

                        xhr.onload = () => {
                            if (xhr.status >= 200 && xhr.status < 300) {
                                if (idKeyInput) idKeyInput.value = presign.key;
                                progressFill.style.width = '100%';
                                
                                // Clear file input (submit only key)
                                const dt = new DataTransfer();
                                this.files = dt.files;
                                
                                // Show success
                                uploadCard.classList.add('uploaded');
                                uploadLabelText.textContent = 'Document uploaded';
                                uploadSuccess.style.display = 'block';
                                submitButton.disabled = false;
                                
                                setTimeout(() => {
                                    progressContainer.style.display = 'none';
                                }, 1000);
                                
                                resolve();
                            } else {
                                reject(new Error(`Upload failed: ${xhr.status} ${xhr.statusText}`));
                            }
                        };

                        xhr.onerror = () => {
                            let errorMsg = 'Upload failed (network error).';
                            if (xhr.status === 0) {
                                errorMsg = 'CORS error: Please check S3 bucket CORS configuration.';
                            }
                            reject(new Error(errorMsg));
                        };

                        xhr.send(compressedFile);
                    });

                } catch (err) {
                    console.error('ID document upload failed', err);
                    progressContainer.style.display = 'none';
                    uploadLabelText.textContent = 'Upload ID document';
                    submitButton.disabled = false;
                    alert(err && err.message ? err.message : 'ID document upload failed.');
                }
            });
        }

        // Form validation
        step4Form.addEventListener('submit', function(e) {
            if (!idKeyInput || !idKeyInput.value) {
                e.preventDefault();
                alert('Please upload your ID document before continuing.');
                return false;
            }
        });
    }

    // Step 5 - Profile Photos and Video Upload Logic
    const step5Form = document.getElementById('mobile-step5-form');
    if (step5Form) {
        // Global state for Step 5 photos
        window.mobileStep5PhotoItems = window.mobileStep5PhotoItems || [];
        window.mobileStep5UploadsInFlight = window.mobileStep5UploadsInFlight || 0;

        // Image compression utility (reuse from Step 4)
        const compressImage = (file, maxSizeMB = 10, quality = 0.75) => {
            return new Promise((resolve, reject) => {
                if (file.type.indexOf('image/') === -1) {
                    resolve(file);
                    return;
                }

                const shouldCompress = file.size > maxSizeMB * 1024 * 1024;
                if (!shouldCompress && file.size <= 1 * 1024 * 1024) {
                    resolve(file);
                    return;
                }

                let compressionQuality = quality;
                if (file.size > 20 * 1024 * 1024) {
                    compressionQuality = 0.65;
                } else if (file.size > 10 * 1024 * 1024) {
                    compressionQuality = 0.7;
                }

                const reader = new FileReader();
                reader.readAsDataURL(file);
                reader.onload = event => {
                    const img = new Image();
                    img.src = event.target.result;
                    img.onload = () => {
                        const canvas = document.createElement('canvas');
                        const ctx = canvas.getContext('2d');

                        let width = img.width;
                        let height = img.height;
                        let MAX_DIMENSION = 2048;
                        if (file.size > 20 * 1024 * 1024) {
                            MAX_DIMENSION = 1920;
                        } else if (file.size > 10 * 1024 * 1024) {
                            MAX_DIMENSION = 2048;
                        }

                        if (width > MAX_DIMENSION || height > MAX_DIMENSION) {
                            if (width > height) {
                                height *= MAX_DIMENSION / width;
                                width = MAX_DIMENSION;
                            } else {
                                width *= MAX_DIMENSION / height;
                                height = MAX_DIMENSION;
                            }
                        }

                        canvas.width = width;
                        canvas.height = height;
                        ctx.drawImage(img, 0, 0, width, height);

                        canvas.toBlob(blob => {
                            if (!blob) {
                                resolve(file);
                                return;
                            }
                            const newFile = new File([blob], file.name, {
                                type: 'image/jpeg',
                                lastModified: Date.now()
                            });

                            if (shouldCompress || newFile.size < file.size) {
                                resolve(newFile);
                            } else {
                                resolve(file);
                            }
                        }, 'image/jpeg', compressionQuality);
                    };
                };
                reader.onerror = error => reject(error);
            });
        };

        // Upload to S3 helper
        const uploadToS3Put = (url, headers, file, onProgress) => {
            return new Promise((resolve, reject) => {
                const xhr = new XMLHttpRequest();
                xhr.open('PUT', url, true);
                if (headers && headers['Content-Type']) {
                    xhr.setRequestHeader('Content-Type', headers['Content-Type']);
                } else if (file.type) {
                    xhr.setRequestHeader('Content-Type', file.type);
                }

                xhr.upload.addEventListener('progress', (e) => {
                    if (e.lengthComputable) {
                        onProgress(Math.round((e.loaded / e.total) * 100));
                    }
                });

                xhr.onload = () => {
                    if (xhr.status >= 200 && xhr.status < 300) {
                        resolve();
                    } else {
                        reject(new Error(`Upload failed: ${xhr.status} ${xhr.statusText}`));
                    }
                };

                xhr.onerror = () => {
                    let errorMsg = 'Upload failed (network error).';
                    if (xhr.status === 0) {
                        errorMsg = 'CORS error: Please check S3 bucket CORS configuration.';
                    }
                    reject(new Error(errorMsg));
                };

                xhr.send(file);
            });
        };

        // Profile Photo Upload
        const profilePhotoInput = document.getElementById('mobile_profile_photo');
        const profilePhotoPreview = document.getElementById('mobile_profile_photo_preview');
        const profilePhotoKey = document.getElementById('mobile_profile_photo_key');
        const profilePhotoProgress = document.getElementById('mobile_profile_photo_progress');
        const profilePhotoProgressFill = document.getElementById('mobile_profile_photo_progress_fill');

        if (profilePhotoInput) {
            profilePhotoInput.addEventListener('change', async function() {
                if (this.files.length === 0) return;

                const file = this.files[0];
                if (!file.type.startsWith('image/')) {
                    alert('Only image files are allowed.');
                    this.value = '';
                    return;
                }

                profilePhotoProgress.style.display = 'block';
                profilePhotoProgressFill.style.width = '0%';

                try {
                    const compressedFile = await compressImage(file, 10, 0.75);
                    
                    // Show preview
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        profilePhotoPreview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
                    };
                    reader.readAsDataURL(compressedFile);

                    // Get presigned URL
                    const presignRes = await fetch('{{ route("talent.onboarding.presign-additional-photo") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            file_name: compressedFile.name,
                            file_type: compressedFile.type,
                        }),
                    });

                    if (!presignRes.ok) {
                        throw new Error('Could not prepare upload.');
                    }

                    const presign = await presignRes.json();

                    // Upload to S3
                    await uploadToS3Put(presign.url, presign.headers, compressedFile, (pct) => {
                        profilePhotoProgressFill.style.width = `${pct}%`;
                    });

                    profilePhotoKey.value = presign.key;
                    profilePhotoProgress.style.display = 'none';
                    
                    // Clear file input
                    this.value = '';
                } catch (err) {
                    console.error('Profile photo upload failed', err);
                    profilePhotoProgress.style.display = 'none';
                    alert(err.message || 'Profile photo upload failed.');
                }
            });
        }

        // Additional Photos Upload
        const additionalPhotosInput = document.getElementById('mobile_additional_photos');
        const additionalPhotosLabel = document.getElementById('mobile_additional_photos_label');
        const additionalPhotosPreviews = document.getElementById('mobile_additional_photos_previews');
        const step5PhotoKeys = document.getElementById('mobile_step5_photo_keys');

        const presignPhoto = async (file) => {
            const res = await fetch('{{ route("talent.onboarding.presign-additional-photo") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                credentials: 'same-origin',
                body: JSON.stringify({
                    file_name: file.name,
                    file_type: file.type,
                }),
            });

            if (!res.ok) {
                throw new Error('Could not prepare upload.');
            }

            return await res.json();
        };

        const uploadOnePhotoItem = async (item) => {
            if (!item || !item.file || item.status === 'uploading' || item.status === 'done') return;

            item.status = 'uploading';
            item.progress = 0;
            item.error = null;
            window.mobileStep5UploadsInFlight = (window.mobileStep5UploadsInFlight || 0) + 1;
            updatePhotoPreviewProgress(item.id, 0);

            try {
                const presign = await presignPhoto(item.file);
                await uploadToS3Put(presign.url, presign.headers, item.file, (pct) => {
                    item.progress = pct;
                    updatePhotoPreviewProgress(item.id, pct);
                });

                item.key = presign.key;
                item.status = 'done';
                item.progress = 100;
                updatePhotoPreviewProgress(item.id, 100);
                syncPhotoKeys();
            } catch (e) {
                item.status = 'error';
                item.error = e && e.message ? e.message : 'Upload failed.';
                updatePhotoPreviewError(item.id, item.error);
            } finally {
                window.mobileStep5UploadsInFlight = Math.max(0, (window.mobileStep5UploadsInFlight || 0) - 1);
            }
        };

        const updatePhotoPreviewProgress = (itemId, progress) => {
            const preview = document.querySelector(`[data-photo-item-id="${itemId}"]`);
            if (preview) {
                const progressFill = preview.querySelector('.photo-preview-progress-fill');
                if (progressFill) {
                    progressFill.style.width = `${progress}%`;
                }
            }
        };

        const updatePhotoPreviewError = (itemId, error) => {
            const preview = document.querySelector(`[data-photo-item-id="${itemId}"]`);
            if (preview) {
                const errorDiv = document.createElement('div');
                errorDiv.className = 'photo-preview-error';
                errorDiv.textContent = error;
                preview.appendChild(errorDiv);
            }
        };

        const syncPhotoKeys = () => {
            if (!step5PhotoKeys) return;
            step5PhotoKeys.innerHTML = '';
            window.mobileStep5PhotoItems
                .filter(i => i.status === 'done' && i.key)
                .forEach(i => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'additional_photo_keys[]';
                    input.value = i.key;
                    step5PhotoKeys.appendChild(input);
                });
        };

        const renderPhotoPreviews = () => {
            if (!additionalPhotosPreviews) return;
            additionalPhotosPreviews.innerHTML = '';
            
            window.mobileStep5PhotoItems.forEach((item) => {
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'photo-preview-item-mobile';
                    div.dataset.photoItemId = item.id;
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <div class="photo-preview-progress">
                            <div class="photo-preview-progress-fill" style="width: ${item.progress}%;"></div>
                        </div>
                        <button type="button" class="photo-preview-remove" onclick="removePhotoItem('${item.id}')">×</button>
                    `;
                    additionalPhotosPreviews.appendChild(div);
                };
                reader.readAsDataURL(item.file);
            });

            if (additionalPhotosLabel) {
                additionalPhotosLabel.textContent = `${window.mobileStep5PhotoItems.length} photos selected`;
            }
        };

        window.removePhotoItem = (itemId) => {
            window.mobileStep5PhotoItems = window.mobileStep5PhotoItems.filter(i => i.id !== itemId);
            renderPhotoPreviews();
            syncPhotoKeys();
        };

        if (additionalPhotosInput) {
            additionalPhotosInput.addEventListener('change', async function() {
                if (this.files.length === 0) return;

                const files = Array.from(this.files);
                const imageFiles = files.filter(f => f.type.startsWith('image/'));

                if (imageFiles.length === 0) {
                    alert('Only image files are allowed.');
                    this.value = '';
                    return;
                }

                // Limit to 10 photos
                if (window.mobileStep5PhotoItems.length + imageFiles.length > 10) {
                    alert('Maximum 10 photos allowed.');
                    this.value = '';
                    return;
                }

                // Compress and add photos
                const filePromises = imageFiles.map(async (file) => {
                    try {
                        return await compressImage(file, 10, 0.75);
                    } catch (err) {
                        console.error('Compression failed', err);
                        return file;
                    }
                });

                const processedFiles = await Promise.all(filePromises);
                const newItems = processedFiles.map(file => ({
                    id: `${Date.now()}_${Math.random().toString(16).slice(2)}`,
                    file,
                    progress: 0,
                    status: 'queued',
                    key: null,
                    error: null,
                }));

                window.mobileStep5PhotoItems.push(...newItems);
                this.value = '';
                renderPhotoPreviews();
                syncPhotoKeys();

                // Start uploads
                newItems.forEach(item => uploadOnePhotoItem(item));
            });
        }

        // Video Upload
        const videoInput = document.getElementById('mobile_video_upload');
        const videoLabel = document.getElementById('mobile_video_label');
        const videoKey = document.getElementById('mobile_video_key');
        const videoProgress = document.getElementById('mobile_video_progress');
        const videoProgressFill = document.getElementById('mobile_video_progress_fill');

        if (videoInput) {
            videoInput.addEventListener('change', async function() {
                if (this.files.length === 0) return;

                const file = this.files[0];
                if (!file.type.startsWith('video/')) {
                    alert('Only video files are allowed.');
                    this.value = '';
                    return;
                }

                videoProgress.style.display = 'block';
                videoProgressFill.style.width = '0%';
                videoLabel.textContent = 'Uploading...';

                try {
                    // Get presigned URL (using same endpoint as photos for now, backend should handle video)
                    const presignRes = await fetch('{{ route("talent.onboarding.presign-additional-photo") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({
                            file_name: file.name,
                            file_type: file.type,
                        }),
                    });

                    if (!presignRes.ok) {
                        throw new Error('Could not prepare upload.');
                    }

                    const presign = await presignRes.json();

                    // Upload to S3
                    await uploadToS3Put(presign.url, presign.headers, file, (pct) => {
                        videoProgressFill.style.width = `${pct}%`;
                    });

                    videoKey.value = presign.key;
                    videoLabel.textContent = `Video uploaded (${(file.size / 1024 / 1024).toFixed(1)} MB)`;
                    videoProgress.style.display = 'none';
                    
                    // Clear file input
                    this.value = '';
                } catch (err) {
                    console.error('Video upload failed', err);
                    videoProgress.style.display = 'none';
                    videoLabel.textContent = 'Add Video Optional (0)';
                    alert(err.message || 'Video upload failed.');
                }
            });
        }

        // Form validation
        step5Form.addEventListener('submit', function(e) {
            // Check if at least one photo is uploaded
            const hasPhotos = window.mobileStep5PhotoItems.some(i => i.status === 'done');
            if (!hasPhotos) {
                e.preventDefault();
                alert('Please upload at least one photo before submitting.');
                return false;
            }

            // Check if uploads are in progress
            if (window.mobileStep5UploadsInFlight > 0) {
                e.preventDefault();
                alert('Please wait for all uploads to complete.');
                return false;
            }
        });
    }
})();
</script>
@endsection
