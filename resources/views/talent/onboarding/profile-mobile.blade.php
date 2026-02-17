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
            padding: 40px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .mobile-card {
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 24px;
            max-width: 480px;
            width: 100%;
            margin: 0 auto;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            box-sizing: border-box;
            overflow: hidden;
        }

        @media (max-width: 380px) {
            .mobile-card {
                padding: 40px 16px;
            }
        }

        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            padding-top: 10px;
            gap: 12px;
        }

        .mobile-header-left,
        .mobile-header-right {
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 0;
        }

        .mobile-header-left {
            flex: 1;
        }

        .mobile-header-right {
            flex: 0 0 auto;
        }

        .icon-btn {
            width: 36px;
            height: 36px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 10px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            color: #1c2435;
            padding: 0;
            flex: 0 0 auto;
        }

        .icon-btn:hover {
            border-color: #000000;
        }

        .icon-btn svg {
            width: 18px;
            height: 18px;
            display: block;
        }

        .logout-form,
        .mobile-logout-form {
            margin: 0;
        }

        .top-title-row {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
        }

        .rtl .top-title-row {
            flex-direction: row-reverse;
        }

        .top-title-row .icon-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
        }

        .mobile-logo {
            width: 140px;
            height: auto;
            display: block;
        }

        .lang-toggle {
            display: inline-flex;
            gap: 4px;
            padding: 4px;
            background: #f3f4f6;
            border-radius: 24px;
            margin-bottom: 0px;
        }

        .lang-btn {
            padding: 6px 18px;
            border: none;
            border-radius: 20px;
            background: transparent;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Arimo', sans-serif;
        }

        .lang-btn.active {
            background: #ffffff;
            color: #1a1a1a;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .mobile-title {
            font-size: 40px;
            font-weight: 700;
            color: #1a1a1a;
            margin: 0 0 10px;
            line-height: 1.1;
            text-align: left;
            font-family: 'Space Grotesk', sans-serif;
            letter-spacing: -0.02em;
        }

        .mobile-subtitle {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 26px;
            text-align: left;
            font-family: 'Arimo', sans-serif;
        }

        .progress-indicator {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
            margin-bottom: 24px;
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

        .date-input-wrapper svg {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
            width: 18px;
            height: 18px;
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
            gap: 0;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
        }

        .country-code-selector {
            flex: 0 0 auto;
            padding: 12px 16px;
            border: none;
            background: #f9fafb;
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
            border: none;
            border-left: 1px solid #e5e7eb;
            font-size: 16px;
            background: #ffffff !important;
            color: #1c2435;
            border-radius: 0;
        }

        .phone-input[readonly] {
            background: #f9fafb !important;
            cursor: default;
        }

        body.rtl .phone-input-group {
            flex-direction: row-reverse;
        }

        body.rtl .phone-input {
            border-left: none;
            border-right: 1px solid #e5e7eb;
            text-align: right;
        }

        .phone-input-group .form-input {
            flex: 1;
            border: none !important;
            border-left: 1px solid #e5e7eb !important;
            border-radius: 0 !important;
            background: #ffffff !important;
        }

        body.rtl .phone-input-group .form-input {
            border-left: none !important;
            border-right: 1px solid #e5e7eb !important;
        }

        .whatsapp-checkbox-wrapper {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
        }

        .hijab-group {
            display: flex;
            gap: 16px;
            margin-top: 12px;
        }

        .hijab-option {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 14px 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 14px;
            color: #4b5563;
        }

        .hijab-option input[type="radio"] {
            margin: 0;
            accent-color: #000000;
            width: 18px;
            height: 18px;
        }

        .hijab-option:has(input:checked) {
            border-color: #000000;
            background-color: #f9fafb;
            color: #000000;
        }

        .mini-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 16px;
        }

        .mini-card h5 {
            margin: 0 0 12px 0;
            font-size: 14px;
            color: #1c2435;
            font-weight: 600;
        }

        .mini-card .radio-group {
            display: flex;
            gap: 24px;
        }

        .mini-card .radio-label {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            cursor: pointer;
            color: #4b5563;
        }

        .mini-card input[type="radio"] {
            accent-color: #000000;
            width: 18px;
            height: 18px;
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

        .step-action-row {
            display: flex;
            gap: 12px;
            margin-top: 32px;
        }

        .rtl .step-action-row {
            flex-direction: row-reverse;
        }

        .step-action-row .next-button {
            margin-top: 0;
            width: auto;
            flex: 1;
        }

        .back-button {
            width: auto;
            flex: 1;
            padding: 16px 24px;
            background: #ffffff;
            color: #000000;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 0;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
        }

        .back-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.10);
            border-color: #000000;
        }

        .back-button:active {
            transform: translateY(0);
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
            background: #1c2435 !important;
            color: #ffffff !important;
            border-color: #1c2435 !important;
        }

        /* Gender Radio Button Labels */
        #gender-male-label:hover,
        #gender-female-label:hover {
            border-color: #000000;
        }

        #gender-male-label input:checked ~ *,
        #gender-female-label input:checked ~ * {
            color: inherit;
        }

        .gender-icon {
            font-size: 18px;
        }

        /* Size Select */
        .size-select-wrapper {
            position: relative;
            display: flex;
            align-items: center;
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
            <div class="mobile-header-left">
                <div class="logo-wrap">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS" class="mobile-logo">
                </div>
            </div>

            <div class="mobile-header-right">
                <div class="lang-toggle">
                    <button type="button" class="lang-btn active" data-lang="en">English</button>
                    <button type="button" class="lang-btn" data-lang="ar">عربي</button>
                </div>
            </div>
        </div>

        <div id="en-header">
            <div class="top-title-row">
                <h1 class="mobile-title">JOIN THE COMMUNITY</h1>
                <form method="POST" action="{{ route('talent.logout') }}" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="icon-btn" aria-label="Logout" title="Logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                    </button>
                </form>
            </div>
            <p class="mobile-subtitle">Create your account to get started</p>
        </div>

        <div id="ar-header" style="display: none; direction: rtl; text-align: right;">
            <div class="top-title-row">
                <h1 class="mobile-title" style="font-family: 'Arimo', sans-serif; text-align: right;">انضم إلى المجتمع</h1>
                <form method="POST" action="{{ route('talent.logout') }}" class="mobile-logout-form">
                    @csrf
                    <button type="submit" class="icon-btn" aria-label="Logout" title="Logout">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <path d="M16 17l5-5-5-5"></path>
                            <path d="M21 12H9"></path>
                        </svg>
                    </button>
                </form>
            </div>
            <p class="mobile-subtitle" style="font-family: 'Arimo', sans-serif; text-align: right;">أنشئ حسابك للبدء</p>
        </div>
            
        <!-- Progress Indicator (5 steps) -->
        <div class="progress-indicator">
            <div class="progress-bar {{ $initialStep >= 1 ? 'active' : '' }}"></div>
            <div class="progress-bar {{ $initialStep >= 2 ? 'active' : '' }}"></div>
            <div class="progress-bar {{ $initialStep >= 3 ? 'active' : '' }}"></div>
            <div class="progress-bar {{ $initialStep >= 4 ? 'active' : '' }}"></div>
            <div class="progress-bar {{ $initialStep >= 5 ? 'active' : '' }}"></div>
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
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.first_name', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.first_name', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_first_name" 
                    name="first_name" 
                    class="form-input" 
                    type="text" 
                    placeholder="First name" 
                    data-en-placeholder="First name" 
                    data-ar-placeholder="الاسم الأول"
                    value="{{ old('first_name', $profile->first_name) }}" 
                    required 
                    pattern="[a-zA-Z\s]+" 
                    autocomplete="given-name"
                >
                <span class="error-text" id="error-mobile_first_name">
                    <span class="lang-en">First name is required (English letters only)</span>
                    <span class="lang-ar" style="display:none;">الاسم الأول مطلوب (أحرف إنجليزية فقط)</span>
                </span>
            </div>

            <!-- Last Name -->
            <div class="form-field">
                <label for="mobile_last_name" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.last_name', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.last_name', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_last_name" 
                    name="last_name" 
                    class="form-input" 
                    type="text" 
                    placeholder=" Last name" 
                    data-en-placeholder="Last name"
                    data-ar-placeholder="الاسم الأخير"
                    value="{{ old('last_name', $profile->last_name) }}" 
                    required 
                    pattern="[a-zA-Z\s]+" 
                    autocomplete="family-name"
                >
                <span class="error-text" id="error-mobile_last_name">
                    <span class="lang-en">Last name is required (English letters only)</span>
                    <span class="lang-ar" style="display:none;">اسم العائلة مطلوب (أحرف إنجليزية فقط)</span>
                </span>
            </div>

            <!-- Date of Birth -->
            <div class="form-field">
                <label for="mobile_date_of_birth" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.date_of_birth', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.date_of_birth', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
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
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>
                @error('date_of_birth')
                    <span class="error-text show">{{ $message }}</span>
                @enderror
                <span class="error-text" id="error-mobile_date_of_birth">
                    <span class="lang-en">Date of birth is required</span>
                    <span class="lang-ar" style="display:none;">تاريخ الميلاد مطلوب</span>
                </span>
            </div>

            <!-- Where are you from? (Nationality) -->
            <div class="form-field">
                <label for="mobile_nationality" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.nationality', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.nationality', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="country-select-wrapper" style="display: flex; align-items: center; gap: 8px;">
                    <span id="mobile_nationality_flag" class="fi" style="font-size: 20px; display: none;"></span>
                    <select id="mobile_nationality" name="nationality" class="form-input" required style="flex: 1;">
                        <option value="">
                            <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_nationality', [], 'en') }}</span>
                            <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_nationality', [], 'ar') }}</span>
                        </option>
                        <option value="Unspecified" {{ old('nationality', $profile->nationality) == 'Unspecified' ? 'selected' : '' }}>Unspecified</option>
                        @foreach($countries as $code => $name)
                            <option value="{{ $code }}" {{ old('nationality', $profile->nationality) == $code ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_nationality">
                    <span class="lang-en">Nationality is required</span>
                    <span class="lang-ar" style="display:none;">الجنسية مطلوبة</span>
                </span>
            </div>

            <!-- Mobile Number -->
            <div class="form-field">
                <label for="mobile_number" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.mobile_number', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.mobile_number', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="phone-input-group">
                    <div class="country-code-selector">
                        <span class="fi fi-kw country-flag" title="Kuwait"></span>
                        <span>+965</span>
                    </div>
                    <input type="hidden" name="country_code" value="{{ old('country_code', $profile->country_code ?? auth('talent')->user()->phone_country_code ?? 'kw') }}">
                    <input 
                        class="form-input" 
                        id="mobile_number" 
                        name="mobile_number" 
                        type="tel" 
                        value="{{ old('mobile_number', $profile->mobile_number ?? auth('talent')->user()->phone_number) }}" 
                        readonly 
                        required
                        style="background: #f9fafb !important; cursor: default;"
                    >
                </div>
            </div>

            <!-- WhatsApp Option -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.whatsapp_question', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.whatsapp_question', [], 'ar') }}</span>
                </label>
                <div class="gender-buttons" style="margin-bottom: 0;">
                    <button type="button" class="gender-btn {{ $whatsappChoice == 'same' ? 'active' : '' }}" data-wa-choice="same">
                        <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.yes', [], 'en') }}</span>
                        <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.yes', [], 'ar') }}</span>
                    </button>
                    <button type="button" class="gender-btn {{ $whatsappChoice == 'alt' ? 'active' : '' }}" data-wa-choice="alt">
                        <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.no', [], 'en') }}</span>
                        <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.no', [], 'ar') }}</span>
                    </button>
                </div>
                <input type="hidden" id="mobile_whatsapp_choice" name="whatsapp_choice" value="{{ $whatsappChoice }}">
                
                <!-- WhatsApp Number Input (shown when choice is 'alt') -->
                <div class="whatsapp-number-section {{ $whatsappChoice == 'alt' ? 'show' : '' }}" id="mobile_whatsapp_section">
                    <label for="mobile_whatsapp_number" class="form-label" style="margin-top: 16px;">
                        <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.whatsapp_number', [], 'en') }}</span>
                        <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.whatsapp_number', [], 'ar') }}</span>
                        <span class="required-asterisk">*</span>
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
                            data-en-placeholder="XXXX XXXX"
                            data-ar-placeholder="XXXX XXXX"
                        >
                    </div>
                    <span id="mobile-whatsapp-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: 4px;">WhatsApp number must be 8 digits</span>
                </div>
            </div>

            <!-- Next Button -->
            <button type="submit" class="next-button">
                <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'en') }}</span>
                <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'ar') }}</span>
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
            <div class="mobile-header-text">
                <div class="lang-en">
                    <h1 class="mobile-title">BODY DETAILS</h1>
                    <p class="mobile-subtitle">Enter your measurements, sizing, and physical details</p>
                </div>
                <div class="lang-ar" style="display:none; direction: rtl; text-align: right;">
                    <h1 class="mobile-title" style="font-family: 'Arimo', sans-serif;">تفاصيل الجسم</h1>
                    <p class="mobile-subtitle" style="font-family: 'Arimo', sans-serif;">أدخل قياساتك وتفاصيلك البدنية</p>
                </div>
            </div>

            <!-- Height -->
            <div class="form-field">
                <label for="mobile_height" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.height', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.height', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_height" 
                    name="height" 
                    class="form-input" 
                    type="number" 
                    step="0.1" 
                    placeholder="175 cm" 
                    data-en-placeholder="175 cm"
                    data-ar-placeholder="175 سم"
                    value="{{ old('height', $profile->height) }}" 
                    required
                >
                <span class="error-text" id="error-mobile_height">
                    <span class="lang-en">Height is required</span>
                    <span class="lang-ar" style="display:none;">الطول مطلوب</span>
                </span>
            </div>

            <!-- Weight -->
            <div class="form-field">
                <label for="mobile_weight" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.weight', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.weight', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <input 
                    id="mobile_weight" 
                    name="weight" 
                    class="form-input" 
                    type="number" 
                    step="0.1" 
                    placeholder="70 kg" 
                    data-en-placeholder="70 kg"
                    data-ar-placeholder="70 كجم"
                    value="{{ old('weight', $profile->weight) }}" 
                    required
                >
                <span class="error-text" id="error-mobile_weight">
                    <span class="lang-en">Weight is required</span>
                    <span class="lang-ar" style="display:none;">الوزن مطلوب</span>
                </span>
            </div>

            <!-- Gender Selection -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_gender', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_gender', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="radio-group" style="display: flex; gap: 12px; margin-top: 8px;">
                    <label class="radio-label" style="flex: 1; padding: 14px 16px; border: 1px solid #e5e7eb; border-radius: 10px; background: #ffffff; color: #1c2435; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;" id="gender-male-label">
                        <input type="radio" name="gender" value="male" id="mobile_gender_male" {{ old('gender', $profile->gender ?? 'male') == 'male' ? 'checked' : '' }} required style="display: none;">
                        <img src="{{ asset('images/male.png') }}" alt="" class="gender-image-icon" style="width: 20px; height: 20px;">
                        <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.male', [], 'en') }}</span>
                        <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.male', [], 'ar') }}</span>
                    </label>
                    <label class="radio-label" style="flex: 1; padding: 14px 16px; border: 1px solid #e5e7eb; border-radius: 10px; background: #ffffff; color: #1c2435; font-size: 14px; font-weight: 500; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px; transition: all 0.2s ease;" id="gender-female-label">
                        <input type="radio" name="gender" value="female" id="mobile_gender_female" {{ old('gender', $profile->gender ?? 'male') == 'female' ? 'checked' : '' }} required style="display: none;">
                        <img src="{{ asset('images/female.png') }}" alt="" class="gender-image-icon" style="width: 20px; height: 20px;">
                        <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.female', [], 'en') }}</span>
                        <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.female', [], 'ar') }}</span>
                    </label>
                </div>
                <span class="error-text" id="error-mobile_gender">
                    <span class="lang-en">Gender selection is required</span>
                    <span class="lang-ar" style="display:none;">اختيار الجنس مطلوب</span>
                </span>
            </div>

            <!-- Hijab Preference (Female Only) -->
            <div class="form-field" id="mobile_hijab_preference_section" style="display: {{ old('gender', $profile->gender ?? 'male') == 'female' ? 'block' : 'none' }};">
                <label class="form-label">
                    <span class="lang-en">{{ \App\Helpers\Bilingual::get('onboarding.hijab_preference') }}</span>
                    <span class="lang-ar" style="display:none;">تفضيل الحجاب</span>
                </label>
                <div class="hijab-group">
                    <label class="hijab-option">
                        <input type="radio" name="hijab_preference" value="wear_hijab" {{ old('hijab_preference', $profile->hijab_preference ?? 'wear_hijab') == 'wear_hijab' ? 'checked' : '' }}>
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.hijabi') }}</span>
                    </label>
                    <label class="hijab-option">
                        <input type="radio" name="hijab_preference" value="no_hijab" {{ old('hijab_preference', $profile->hijab_preference) == 'no_hijab' ? 'checked' : '' }}>
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.non_hijabi') }}</span>
                    </label>
                </div>
            </div>

            <!-- Skin Color Selection -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \App\Helpers\Bilingual::get('onboarding.skin_tone') }}</span>
                    <span class="lang-ar" style="display:none;">لون البشرة</span>
                    <span class="required-asterisk">*</span>
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
                <span class="error-text" id="error-mobile_skin_tone">
                    <span class="lang-en">Skin color is required</span>
                    <span class="lang-ar" style="display:none;">لون البشرة مطلوب</span>
                </span>
            </div>

            <!-- Hair Color Selection -->
            <div class="form-field" id="mobile_hair_color_field">
                <label class="form-label">
                    <span class="lang-en">{{ \App\Helpers\Bilingual::get('onboarding.hair_color') }}</span>
                    <span class="lang-ar" style="display:none;">لون الشعر</span>
                    <span class="required-asterisk">*</span>
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
                <span class="error-text" id="error-mobile_hair_color">
                    <span class="lang-en">Hair color is required</span>
                    <span class="lang-ar" style="display:none;">لون الشعر مطلوب</span>
                </span>
            </div>

            <!-- Eye Color Selection -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \App\Helpers\Bilingual::get('onboarding.eye_color') }}</span>
                    <span class="lang-ar" style="display:none;">لون العين</span>
                    <span class="required-asterisk">*</span>
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
                <span class="error-text" id="error-mobile_eye_color">
                    <span class="lang-en">Eye color is required</span>
                    <span class="lang-ar" style="display:none;">لون العين مطلوب</span>
                </span>
            </div>

            <!-- Tattoos & Piercings -->
            <div class="mini-card">
                <h5>
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.visible_tattoos', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.visible_tattoos', [], 'ar') }}</span>
                </h5>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="has_visible_tattoos" value="0" {{ old('has_visible_tattoos', $profile->has_visible_tattoos ?? 0) == 0 ? 'checked' : '' }} required> 
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.no') }}</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="has_visible_tattoos" value="1" {{ old('has_visible_tattoos', $profile->has_visible_tattoos) == 1 ? 'checked' : '' }} required> 
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.yes') }}</span>
                    </label>
                </div>
            </div>

            <div class="mini-card">
                <h5>
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.piercings', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.piercings', [], 'ar') }}</span>
                </h5>
                <div class="radio-group">
                    <label class="radio-label">
                        <input type="radio" name="has_piercings" value="0" {{ old('has_piercings', $profile->has_piercings ?? 0) == 0 ? 'checked' : '' }} required> 
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.no') }}</span>
                    </label>
                    <label class="radio-label">
                        <input type="radio" name="has_piercings" value="1" {{ old('has_piercings', $profile->has_piercings) == 1 ? 'checked' : '' }} required> 
                        <span>{{ \App\Helpers\Bilingual::get('onboarding.yes') }}</span>
                    </label>
                </div>
            </div>
            
            <!-- Display backend validation errors -->
            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-top: 16px;">
                    <ul style="margin: 0; padding-left: 20px; color: #991b1b; font-size: 14px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="step-action-row">
                <a href="{{ route('talent.onboarding.show', 'step-1') }}" class="back-button">
                    <span class="lang-en">Back</span>
                    <span class="lang-ar" style="display:none;">رجوع</span>
                </a>
                <button type="submit" class="next-button">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'ar') }}</span>
                    <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </form>
        @endif

        @if($currentStep == 'step-3')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step3-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <div class="mobile-header-text">
                    <div class="lang-en">
                        <h1 class="mobile-title">SIZING DETAILS</h1>
                        <p class="mobile-subtitle">Complete your profile with your measurements and sizes</p>
                    </div>
                    <div class="lang-ar" style="display:none; direction: rtl; text-align: right;">
                        <h1 class="mobile-title" style="font-family: 'Arimo', sans-serif;">تفاصيل المقاسات</h1>
                        <p class="mobile-subtitle" style="font-family: 'Arimo', sans-serif;">أكمل ملفك الشخصي بقياساتك</p>
                    </div>
                </div>
            </div>

            <!-- T-Shirt Size -->
            <div class="form-field">
                <label for="mobile_t_shirt_size" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.t_shirt_size', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.t_shirt_size', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <select id="mobile_t_shirt_size" name="t_shirt_size" class="form-input" required>
                        <option value="">
                            <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_size', [], 'en') }}</span>
                            <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_size', [], 'ar') }}</span>
                        </option>
                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <option value="{{ $size }}" {{ old('t_shirt_size', $profile->t_shirt_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_t_shirt_size">
                    <span class="lang-en">T-shirt size is required</span>
                    <span class="lang-ar" style="display:none;">مقاس التي شيرت مطلوب</span>
                </span>
            </div>

            <!-- Dress Size (only for females) -->
            @php
                $isFemale = strtolower($profile->gender ?? 'male') === 'female';
            @endphp
            @if($isFemale)
            <div class="form-field" id="mobile_dress_size_field">
                <label for="mobile_dress_size" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.dress_size', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.dress_size', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <select id="mobile_dress_size" name="dress_size" class="form-input" required>
                        <option value="">
                            <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_size', [], 'en') }}</span>
                            <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.select_size', [], 'ar') }}</span>
                        </option>
                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                            <option value="{{ $size }}" {{ old('dress_size', $profile->dress_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                        @endforeach
                    </select>
                </div>
                <span class="error-text" id="error-mobile_dress_size">
                    <span class="lang-en">Dress size is required</span>
                    <span class="lang-ar" style="display:none;">مقاس الفستان مطلوب</span>
                </span>
            </div>
            @else
            <input type="hidden" name="dress_size" value="">
            @endif

            <!-- Shoe Size -->
            <div class="form-field">
                <label for="mobile_shoe_size" class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.shoe_size', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.shoe_size', [], 'ar') }}</span>
                    <span class="required-asterisk">*</span>
                </label>
                <div class="size-select-wrapper">
                    <input 
                        id="mobile_shoe_size" 
                        name="shoe_size" 
                        class="form-input" 
                        type="number" 
                        step="0.1" 
                        placeholder="Select Size" 
                        data-en-placeholder="Select Size"
                        data-ar-placeholder="اختر المقاس"
                        value="{{ old('shoe_size', $profile->shoe_size) }}" 
                        required
                    >
                </div>
                <span class="error-text" id="error-mobile_shoe_size">
                    <span class="lang-en">Shoe size is required</span>
                    <span class="lang-ar" style="display:none;">مقاس الحذاء مطلوب</span>
                </span>
            </div>

            <div class="step-action-row">
                <a href="{{ route('talent.onboarding.show', 'step-2') }}" class="back-button">
                    <span class="lang-en">Back</span>
                    <span class="lang-ar" style="display:none;">رجوع</span>
                </a>
                <button type="submit" class="next-button">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'ar') }}</span>
                    <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </form>
        @endif

        @if($currentStep == 'step-4')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step4-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <div class="mobile-header-text">
                    <div class="lang-en">
                        <h1 class="mobile-title">ID VERIFICATION</h1>
                        <p class="mobile-subtitle">Upload your ID document for verification</p>
                    </div>
                    <div class="lang-ar" style="display:none; direction: rtl; text-align: right;">
                        <h1 class="mobile-title" style="font-family: 'Arimo', sans-serif;">التحقق من الهوية</h1>
                        <p class="mobile-subtitle" style="font-family: 'Arimo', sans-serif;">حمل وثيقة الهوية الخاصة بك للتحقق</p>
                    </div>
                </div>
            </div>

            <!-- ID Document Info -->
            <div class="id-info-box">
                <p style="font-size: 14px; color: #1c2435; line-height: 1.5; margin: 0;">
                    <span class="lang-en">{!! \Illuminate\Support\Facades\Lang::get('onboarding.id_document_info', [], 'en') !!}</span>
                    <span class="lang-ar" style="display:none;">{!! \Illuminate\Support\Facades\Lang::get('onboarding.id_document_info', [], 'ar') !!}</span>
                </p>
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
                        <div class="upload-label-mobile" id="mobile_upload_label_text">
                            <span class="lang-en">Upload ID document</span>
                            <span class="lang-ar" style="display:none;">تحميل وثيقة الهوية</span>
                        </div>
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
                    <span class="lang-en">✓ Document uploaded successfully</span>
                    <span class="lang-ar" style="display:none;">✓ تم تحميل الوثيقة بنجاح</span>
                </div>
            </div>

            <!-- Security Message -->
            <div class="security-message">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="flex-shrink: 0;">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M9 12l2 2 4-4"/>
                </svg>
                <span>
                    <span class="lang-en">Your information is securely verified and protected with industry-leading encryption</span>
                    <span class="lang-ar" style="display:none;">معلوماتك يتم التحقق منها وحمايتها بشكل آمن باستخدام تشفير رائد في الصناعة</span>
                </span>
            </div>

            <div class="step-action-row">
                <a href="{{ route('talent.onboarding.show', 'step-3') }}" class="back-button">
                    <span class="lang-en">Back</span>
                    <span class="lang-ar" style="display:none;">رجوع</span>
                </a>
                <button type="submit" class="next-button" id="mobile_step4_submit">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.next', [], 'ar') }}</span>
                    <svg class="chevron-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M9 18l6-6-6-6"/>
                    </svg>
                </button>
            </div>
        </form>
        @endif

        @if($currentStep == 'step-5')
        <form method="POST" action="{{ route('talent.onboarding.store', 'step-5') }}" enctype="multipart/form-data" novalidate class="mobile-form" id="mobile-step5-form">
            @csrf
            
            <!-- Header -->
            <div class="mobile-header">
                <div class="mobile-header-text">
                    <div class="lang-en">
                        <h1 class="mobile-title">PROFILE MEDIA</h1>
                        <p class="mobile-subtitle">Upload your profile photos and a short intro video</p>
                    </div>
                    <div class="lang-ar" style="display:none; direction: rtl; text-align: right;">
                        <h1 class="mobile-title" style="font-family: 'Arimo', sans-serif;">وسائط الملف الشخصي</h1>
                        <p class="mobile-subtitle" style="font-family: 'Arimo', sans-serif;">حمل صورك الشخصية وفيديو تعريفي قصير</p>
                    </div>
                </div>
            </div>

            <!-- Additional Photos Upload -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.add_photos', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.add_photos', [], 'ar') }}</span>
                </label>
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
                            <span class="lang-en">Upload up to 10 clear, natural photos (no filters) to improve your chances of being selected.</span>
                            <span class="lang-ar" style="display:none;">حمل حتى 10 صور واضحة وطبيعية (بدون فلاتر) لزيادة فرص اختيارك.</span>
                        </div>
                    </div>
                </label>
                <div id="mobile_additional_photos_previews" class="photo-previews-mobile"></div>
                <div id="mobile_step5_photo_keys" style="display:none;"></div>
            </div>

            <!-- Video Upload -->
            <div class="form-field">
                <label class="form-label">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.profile_video', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.profile_video', [], 'ar') }}</span>
                </label>
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
                        <div class="upload-label-mobile" id="mobile_video_label">
                            <span class="lang-en">Add Video (Optional)</span>
                            <span class="lang-ar" style="display:none;">إضافة فيديو (اختياري)</span>
                        </div>
                        <div class="progress-bar-container-mobile" id="mobile_video_progress" style="display:none;">
                            <div class="progress-bar-fill-mobile" id="mobile_video_progress_fill" style="width: 0%;"></div>
                        </div>
                    </div>
                </label>
                @error('video')
                    <span class="error-text show" style="margin-top: 8px;">{{ $message }}</span>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="step-action-row">
                <a href="{{ route('talent.onboarding.show', 'step-4') }}" class="back-button">
                    <span class="lang-en">Back</span>
                    <span class="lang-ar" style="display:none;">رجوع</span>
                </a>
                <button type="submit" class="next-button" id="mobile_step5_submit">
                    <span class="lang-en">{{ \Illuminate\Support\Facades\Lang::get('onboarding.submit_application', [], 'en') }}</span>
                    <span class="lang-ar" style="display:none;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.submit_application', [], 'ar') }}</span>
                </button>
            </div>
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
    
    // Language Toggle logic
    const langBtns = document.querySelectorAll('.lang-btn');
    const enHeader = document.getElementById('en-header');
    const arHeader = document.getElementById('ar-header');

    // Logout confirmation (match desktop profile.blade.php behavior)
    const mobileLogoutForms = document.querySelectorAll('form.mobile-logout-form');
    if (mobileLogoutForms && mobileLogoutForms.length > 0) {
        mobileLogoutForms.forEach((logoutForm) => {
            logoutForm.addEventListener('submit', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const doSubmit = () => logoutForm.submit();

                if (typeof Swal !== 'undefined' && Swal && typeof Swal.fire === 'function') {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Even if you log out, all the data filled so far in the completed steps will be saved.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#1a1a1a',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, Log me out',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) doSubmit();
                    });
                } else {
                    // Fallback if SweetAlert isn't loaded on this page
                    if (window.confirm('Are you sure you want to log out?')) {
                        doSubmit();
                    }
                }
            });
        });
    }
    
    // Toggle function
    function setLanguage(lang) {
        // Update buttons
        langBtns.forEach(btn => {
            btn.classList.toggle('active', btn.dataset.lang === lang);
        });

        // Toggle English/Arabic content
        const enContent = document.querySelectorAll('.lang-en');
        const arContent = document.querySelectorAll('.lang-ar');
        
        if (lang === 'ar') {
            enContent.forEach(el => el.style.display = 'none');
            arContent.forEach(el => el.style.display = 'block');
            
            if (enHeader) enHeader.style.display = 'none';
            if (arHeader) arHeader.style.display = 'block';
            document.body.style.direction = 'rtl';
            document.body.classList.add('rtl');
            
            // Update form elements
            const forms = document.querySelectorAll('.mobile-form');
            forms.forEach(f => {
                f.style.direction = 'rtl';
                f.style.textAlign = 'right';
            });
            
            const labels = document.querySelectorAll('.form-label');
            labels.forEach(l => l.style.textAlign = 'right');

            const titles = document.querySelectorAll('.mobile-title');
            titles.forEach(t => t.style.textAlign = 'right');
            const subtitles = document.querySelectorAll('.mobile-subtitle');
            subtitles.forEach(s => s.style.textAlign = 'right');

            const logoWrap = document.querySelector('.logo-wrap');
            if (logoWrap) logoWrap.style.textAlign = 'right';

            const progress = document.querySelector('.progress-indicator');
            if (progress) progress.style.justifyContent = 'flex-end';

            const dateSvs = document.querySelectorAll('.date-input-wrapper svg');
            dateSvs.forEach(s => {
                s.style.right = 'auto';
                s.style.left = '16px';
            });

            const countryCodes = document.querySelectorAll('.country-code-selector');
            countryCodes.forEach(c => c.style.flexDirection = 'row-reverse');

            // Update placeholders
            const inputs = document.querySelectorAll('.form-input, .phone-input');
            inputs.forEach(input => {
                if (input.dataset.arPlaceholder) {
                    input.placeholder = input.dataset.arPlaceholder;
                }
            });

        } else {
            enContent.forEach(el => el.style.display = 'block');
            arContent.forEach(el => el.style.display = 'none');

            if (enHeader) enHeader.style.display = 'block';
            if (arHeader) arHeader.style.display = 'none';
            document.body.style.direction = 'ltr';
            document.body.classList.remove('rtl');

            const forms = document.querySelectorAll('.mobile-form');
            forms.forEach(f => {
                f.style.direction = 'ltr';
                f.style.textAlign = 'left';
            });

            const labels = document.querySelectorAll('.form-label');
            labels.forEach(l => l.style.textAlign = 'left');

            const titles = document.querySelectorAll('.mobile-title');
            titles.forEach(t => t.style.textAlign = 'left');
            const subtitles = document.querySelectorAll('.mobile-subtitle');
            subtitles.forEach(s => s.style.textAlign = 'left');

            const logoWrap = document.querySelector('.logo-wrap');
            if (logoWrap) logoWrap.style.textAlign = 'left';

            const progress = document.querySelector('.progress-indicator');
            if (progress) progress.style.justifyContent = 'flex-start';

            const dateSvs = document.querySelectorAll('.date-input-wrapper svg');
            dateSvs.forEach(s => {
                s.style.right = '16px';
                s.style.left = 'auto';
            });

            const countryCodes = document.querySelectorAll('.country-code-selector');
            countryCodes.forEach(c => c.style.flexDirection = 'row');

            // Update placeholders
            const inputs = document.querySelectorAll('.form-input, .phone-input');
            inputs.forEach(input => {
                if (input.dataset.enPlaceholder) {
                    input.placeholder = input.dataset.enPlaceholder;
                }
            });
        }
    }

    langBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            setLanguage(this.dataset.lang);
        });
    });

    const form = document.getElementById('mobile-step1-form') || document.getElementById('mobile-step2-form') || document.getElementById('mobile-step3-form') || document.getElementById('mobile-step4-form') || document.getElementById('mobile-step5-form');
    if (!form) return;

    const whatsappChoiceInput = document.getElementById('mobile_whatsapp_choice');
    const whatsappSection = document.getElementById('mobile_whatsapp_section');
    const whatsappNumberInput = document.getElementById('mobile_whatsapp_number');
    const whatsappError = document.getElementById('mobile-whatsapp-error');

    // WhatsApp choice buttons logic
    const waButtons = document.querySelectorAll('.gender-btn[data-wa-choice]');
    if (waButtons.length > 0 && whatsappChoiceInput) {
        waButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const choice = this.dataset.waChoice;
                whatsappChoiceInput.value = choice;
                
                // Update active state
                waButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Toggle section
                if (whatsappSection) {
                    if (choice === 'alt') {
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

    // Form validation (only for Step 1)
    form.addEventListener('submit', function(e) {
        // Skip validation if this is Step 2, 3, 4, or 5 (they have their own validation)
        const currentFormId = form.id;
        if (currentFormId === 'mobile-step2-form' || currentFormId === 'mobile-step3-form' || 
            currentFormId === 'mobile-step4-form' || currentFormId === 'mobile-step5-form') {
            return true; // Let the specific step validation handle it
        }
        
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
        if (whatsappChoiceInput && whatsappChoiceInput.value === 'alt' && whatsappNumberInput) {
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
    if (whatsappChoiceInput && whatsappChoiceInput.value === 'alt' && whatsappSection) {
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
        // Gender Selection - Radio Button Logic
        const genderMaleRadio = document.getElementById('mobile_gender_male');
        const genderFemaleRadio = document.getElementById('mobile_gender_female');
        const genderMaleLabel = document.getElementById('gender-male-label');
        const genderFemaleLabel = document.getElementById('gender-female-label');
        const hijabSection = document.getElementById('mobile_hijab_preference_section');
        const hairColorField = document.getElementById('mobile_hair_color_field');
        
        // Function to update label styles based on radio selection
        function updateGenderLabels() {
            if (genderMaleRadio && genderMaleLabel && genderFemaleRadio && genderFemaleLabel) {
                if (genderMaleRadio.checked) {
                    genderMaleLabel.style.backgroundColor = '#1c2435';
                    genderMaleLabel.style.color = '#ffffff';
                    genderMaleLabel.style.borderColor = '#1c2435';
                    genderFemaleLabel.style.backgroundColor = '#ffffff';
                    genderFemaleLabel.style.color = '#1c2435';
                    genderFemaleLabel.style.borderColor = '#e5e7eb';
                } else if (genderFemaleRadio.checked) {
                    genderFemaleLabel.style.backgroundColor = '#1c2435';
                    genderFemaleLabel.style.color = '#ffffff';
                    genderFemaleLabel.style.borderColor = '#1c2435';
                    genderMaleLabel.style.backgroundColor = '#ffffff';
                    genderMaleLabel.style.color = '#1c2435';
                    genderMaleLabel.style.borderColor = '#e5e7eb';
                }
            }
        }
        
        // Function to update hijab section visibility
        function updateHijabVisibility() {
            const currentGender = genderFemaleRadio && genderFemaleRadio.checked ? 'female' : 'male';
            
            if (currentGender === 'female') {
                // Show hijab section
                if (hijabSection) {
                    hijabSection.style.display = 'block';
                    hijabSection.style.visibility = 'visible';
                }
                
                // Handle hair color field based on hijab preference
                const hijabRadios = step2Form.querySelectorAll('input[name="hijab_preference"]');
                let selectedHijab = '';
                hijabRadios.forEach(radio => {
                    if (radio.checked) selectedHijab = radio.value;
                });
                
                if (hairColorField) {
                    if (selectedHijab === 'wear_hijab') {
                        hairColorField.style.display = 'none';
                    } else {
                        hairColorField.style.display = 'block';
                    }
                }
            } else {
                // Hide hijab section
                if (hijabSection) {
                    hijabSection.style.display = 'none';
                    hijabSection.style.visibility = 'hidden';
                }
                if (hairColorField) {
                    hairColorField.style.display = 'block';
                }
            }
        }
        
        // Add change handlers to gender radio buttons
        if (genderMaleRadio) {
            genderMaleRadio.addEventListener('change', function() {
                updateGenderLabels();
                updateHijabVisibility();
            });
        }
        
        if (genderFemaleRadio) {
            genderFemaleRadio.addEventListener('change', function() {
                updateGenderLabels();
                updateHijabVisibility();
            });
        }
        
        // Also listen for click events on labels to ensure styles update immediately
        if (genderMaleLabel) {
            genderMaleLabel.addEventListener('click', function() {
                setTimeout(function() {
                    updateGenderLabels();
                    updateHijabVisibility();
                }, 10);
            });
        }
        
        if (genderFemaleLabel) {
            genderFemaleLabel.addEventListener('click', function() {
                setTimeout(function() {
                    updateGenderLabels();
                    updateHijabVisibility();
                }, 10);
            });
        }
        
        // Listen for hijab preference changes
        const hijabRadios = step2Form.querySelectorAll('input[name="hijab_preference"]');
        hijabRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                updateHijabVisibility();
            });
        });
        
        // Initialize on page load
        updateGenderLabels();
        updateHijabVisibility();

        // Chip Selection (Skin, Hair, Eye Color)
        const chipGroups = document.querySelectorAll('.chip-group');
        chipGroups.forEach(group => {
            const chips = group.querySelectorAll('.chip');
            chips.forEach(chip => {
                chip.addEventListener('click', function(e) {
                    e.preventDefault();
                    const value = this.dataset.value;
                    const fieldContainer = group.closest('.form-field');
                    const hiddenInput = fieldContainer ? fieldContainer.querySelector('input[type="hidden"]') : null;
                    
                    chips.forEach(c => c.classList.remove('active'));
                    this.classList.add('active');
                    
                    if (hiddenInput) {
                        hiddenInput.value = value;
                        // Clear any error state
                        hiddenInput.classList.remove('is-invalid');
                        const errEl = document.getElementById('error-' + hiddenInput.id);
                        if (errEl) errEl.classList.remove('show');
                    }
                });
            });
        });

        // Toggle Switch Logic
        const toggleSwitches = document.querySelectorAll('.toggle-switch');
        toggleSwitches.forEach(toggle => {
            const radios = toggle.querySelectorAll('input[type="radio"]');
            const slider = toggle.querySelector('.toggle-slider');
            
            // Make the toggle switch clickable (but not the label wrapper)
            const toggleWrapper = toggle.closest('.toggle-item');
            if (toggleWrapper) {
                toggleWrapper.style.cursor = 'pointer';
                toggleWrapper.addEventListener('click', function(e) {
                    // Don't prevent default if clicking directly on radio
                    if (e.target.type === 'radio') return;
                    
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Find the currently checked radio
                    const checkedRadio = toggle.querySelector('input[type="radio"]:checked');
                    // Toggle to the other value
                    if (checkedRadio && checkedRadio.value === '0') {
                        const otherRadio = toggle.querySelector('input[type="radio"][value="1"]');
                        if (otherRadio) {
                            otherRadio.checked = true;
                            checkedRadio.checked = false;
                            otherRadio.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    } else if (checkedRadio && checkedRadio.value === '1') {
                        const otherRadio = toggle.querySelector('input[type="radio"][value="0"]');
                        if (otherRadio) {
                            otherRadio.checked = true;
                            checkedRadio.checked = false;
                            otherRadio.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    } else {
                        // If nothing checked, check the first one (value 0)
                        const firstRadio = toggle.querySelector('input[type="radio"][value="0"]');
                        if (firstRadio) {
                            firstRadio.checked = true;
                            firstRadio.dispatchEvent(new Event('change', { bubbles: true }));
                        }
                    }
                });
            }
            
            // Update visual state on change
            radios.forEach(radio => {
                radio.addEventListener('change', function() {
                    // CSS handles the visual state based on which radio is checked
                    if (this.value === '1') {
                        if (slider) {
                            slider.style.backgroundColor = '#000000';
                        }
                    } else {
                        if (slider) {
                            slider.style.backgroundColor = '#d1d5db';
                        }
                    }
                });
                
                // Initialize state
                if (radio.checked) {
                    radio.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });
        });

        // Form Validation
        step2Form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.error-text.show').forEach(el => el.classList.remove('show'));

            // Validate height
            const heightEl = document.getElementById('mobile_height');
            if (!heightEl || !heightEl.value || heightEl.value.trim() === '') {
                isValid = false;
                heightEl.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_height');
                if (errEl) {
                    errEl.textContent = 'Height is required';
                    errEl.classList.add('show');
                }
            } else {
                const hVal = parseFloat(heightEl.value);
                if (isNaN(hVal) || hVal < 50 || hVal > 300) {
                    isValid = false;
                    heightEl.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_height');
                    if (errEl) {
                        errEl.textContent = 'Height must be between 50 and 300 cm';
                        errEl.classList.add('show');
                    }
                }
            }

            // Validate weight
            const weightEl = document.getElementById('mobile_weight');
            if (!weightEl || !weightEl.value || weightEl.value.trim() === '') {
                isValid = false;
                weightEl.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_weight');
                if (errEl) {
                    errEl.textContent = 'Weight is required';
                    errEl.classList.add('show');
                }
            } else {
                const wVal = parseFloat(weightEl.value);
                if (isNaN(wVal) || wVal < 40 || wVal > 200) {
                    isValid = false;
                    weightEl.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_weight');
                    if (errEl) {
                        errEl.textContent = 'Weight must be between 40 and 200 kg';
                        errEl.classList.add('show');
                    }
                }
            }

            // Validate gender (using radio buttons)
            const genderMaleRadio = document.getElementById('mobile_gender_male');
            const genderFemaleRadio = document.getElementById('mobile_gender_female');
            const genderSelected = (genderMaleRadio && genderMaleRadio.checked) || (genderFemaleRadio && genderFemaleRadio.checked);
            const selectedGender = genderFemaleRadio && genderFemaleRadio.checked ? 'female' : (genderMaleRadio && genderMaleRadio.checked ? 'male' : '');
            
            if (!genderSelected) {
                isValid = false;
                const genderMaleLabel = document.getElementById('gender-male-label');
                const genderField = genderMaleLabel ? genderMaleLabel.closest('.form-field') : null;
                const errEl = document.getElementById('error-mobile_gender');
                if (errEl) {
                    errEl.classList.add('show');
                } else if (genderField) {
                    const errMsg = document.createElement('span');
                    errMsg.id = 'error-mobile_gender';
                    errMsg.className = 'error-text show';
                    errMsg.innerHTML = '<span class="lang-en">Please select your gender</span><span class="lang-ar" style="display:none;">يرجى اختيار الجنس</span>';
                    genderField.appendChild(errMsg);
                }
            } else {
                const errEl = document.getElementById('error-mobile_gender');
                if (errEl) errEl.classList.remove('show');
            }

            // Validate hijab preference if female
            if (selectedGender === 'female') {
                const hijabRadios = step2Form.querySelectorAll('input[name="hijab_preference"]');
                const hijabChecked = Array.from(hijabRadios).some(r => r.checked);
                if (!hijabChecked) {
                    isValid = false;
                    const hijabSection = document.getElementById('mobile_hijab_preference_section');
                    if (hijabSection) {
                        // Remove any existing error message
                        const existingErr = hijabSection.querySelector('.error-text.show');
                        if (existingErr && existingErr.id !== 'error-mobile_gender') existingErr.remove();
                        
                        const errMsg = document.createElement('div');
                        errMsg.className = 'error-text show';
                        errMsg.style.cssText = 'background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-top: 8px; color: #991b1b; font-size: 14px;';
                        errMsg.innerHTML = '<span class="lang-en">Please select your hijab preference</span><span class="lang-ar" style="display:none;">يرجى اختيار تفضيل الحجاب</span>';
                        hijabSection.appendChild(errMsg);
                        setTimeout(() => errMsg.remove(), 5000);
                    }
                }
            }

            // Validate skin tone
            const skinToneEl = document.getElementById('mobile_skin_tone');
            if (!skinToneEl || !skinToneEl.value || skinToneEl.value.trim() === '') {
                isValid = false;
                skinToneEl.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_skin_tone');
                if (errEl) {
                    errEl.textContent = 'Skin color is required';
                    errEl.classList.add('show');
                }
            }

            // Validate hair color (only if field is visible and not wearing hijab)
            const hairColorEl = document.getElementById('mobile_hair_color');
            const hairColorField = document.getElementById('mobile_hair_color_field');
            const isHairColorVisible = hairColorField && hairColorField.style.display !== 'none';
            
            if (isHairColorVisible && (!hairColorEl || !hairColorEl.value || hairColorEl.value.trim() === '')) {
                isValid = false;
                hairColorEl.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_hair_color');
                if (errEl) {
                    errEl.textContent = 'Hair color is required';
                    errEl.classList.add('show');
                }
            }

            // Validate eye color
            const eyeColorEl = document.getElementById('mobile_eye_color');
            if (!eyeColorEl || !eyeColorEl.value || eyeColorEl.value.trim() === '') {
                isValid = false;
                eyeColorEl.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_eye_color');
                if (errEl) {
                    errEl.textContent = 'Eye color is required';
                    errEl.classList.add('show');
                }
            }

            // Validate toggle switches (has_visible_tattoos and has_piercings)
            // These should always have a checked value due to default checked state, but verify
            const tattoosRadios = document.querySelectorAll('input[name="has_visible_tattoos"]');
            const tattoosChecked = Array.from(tattoosRadios).some(r => r.checked);
            if (!tattoosChecked) {
                isValid = false;
                // Ensure at least one is checked
                const firstTattooRadio = tattoosRadios[0];
                if (firstTattooRadio) {
                    firstTattooRadio.checked = true;
                }
            }

            const piercingsRadios = document.querySelectorAll('input[name="has_piercings"]');
            const piercingsChecked = Array.from(piercingsRadios).some(r => r.checked);
            if (!piercingsChecked) {
                isValid = false;
                // Ensure at least one is checked
                const firstPiercingRadio = piercingsRadios[0];
                if (firstPiercingRadio) {
                    firstPiercingRadio.checked = true;
                }
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return false;
            }
            
            // Form is valid, allow submission
            return true;
        });

    }

    // Step 3 Form Logic
    const step3Form = document.getElementById('mobile-step3-form');
    if (step3Form) {
        // Form Validation
        step3Form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Clear previous errors
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.error-text.show').forEach(el => el.classList.remove('show'));

            // Validate T-shirt size
            const tShirtSizeSelect = document.getElementById('mobile_t_shirt_size');
            if (!tShirtSizeSelect || !tShirtSizeSelect.value) {
                isValid = false;
                tShirtSizeSelect.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_t_shirt_size');
                if (errEl) {
                    errEl.classList.add('show');
                }
            }

            // Validate dress size if field is visible (female)
            const dressSizeField = document.getElementById('mobile_dress_size_field');
            const dressSizeSelect = document.getElementById('mobile_dress_size');
            if (dressSizeField && dressSizeField.style.display !== 'none' && dressSizeSelect) {
                if (!dressSizeSelect.value) {
                    isValid = false;
                    dressSizeSelect.classList.add('is-invalid');
                    const errEl = document.getElementById('error-mobile_dress_size');
                    if (errEl) {
                        errEl.classList.add('show');
                    }
                }
            }

            // Validate shoe size
            const shoeSizeInput = document.getElementById('mobile_shoe_size');
            if (!shoeSizeInput || !shoeSizeInput.value || shoeSizeInput.value.trim() === '') {
                isValid = false;
                shoeSizeInput.classList.add('is-invalid');
                const errEl = document.getElementById('error-mobile_shoe_size');
                if (errEl) {
                    errEl.classList.add('show');
                }
            }

            if (!isValid) {
                e.preventDefault();
                // Scroll to first error
                const firstError = document.querySelector('.is-invalid');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
                return false;
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
                                try {
                                    const dt = new DataTransfer();
                                    this.files = dt.files;
                                } catch (e) {
                                    this.value = '';
                                }
                                
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
                                const errorMsg = `Upload failed (S3): ${xhr.status} ${xhr.statusText}`;
                                console.error('S3 upload error:', xhr.status, xhr.responseText);
                                reject(new Error(errorMsg));
                            }
                        };

                        xhr.onerror = () => {
                            console.error('Network error during S3 upload', {
                                status: xhr.status,
                                statusText: xhr.statusText,
                                responseText: xhr.responseText
                            });
                            // Check if it's likely a CORS error (status 0 usually indicates CORS)
                            let errorMsg = 'Upload failed (network error).';
                            if (xhr.status === 0) {
                                errorMsg = 'CORS error: S3 bucket must allow PUT requests from this domain. Please configure CORS on your S3 bucket.';
                            } else if (xhr.status === 403) {
                                errorMsg = 'Access denied. Check S3 bucket permissions and CORS configuration.';
                            } else if (xhr.status === 404) {
                                errorMsg = 'S3 endpoint not found. Check bucket name and region configuration.';
                            }
                            reject(new Error(errorMsg));
                        };
                        
                        xhr.onabort = () => {
                            reject(new Error('Upload cancelled.'));
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

    // Step 5 - Additional Photos and Video Upload Logic
    const step5Form = document.getElementById('mobile-step5-form');
    if (step5Form) {
        // Global state for Step 5 photos
        window.mobileStep5PhotoItems = window.mobileStep5PhotoItems || [];
        window.mobileStep5UploadsInFlight = window.mobileStep5UploadsInFlight || 0;
        const step5SubmitBtn = document.getElementById('mobile_step5_submit');

        const setStep5SubmitDisabled = (disabled) => {
            if (!step5SubmitBtn) return;
            step5SubmitBtn.disabled = !!disabled;
            step5SubmitBtn.style.opacity = disabled ? '0.7' : '1';
            step5SubmitBtn.style.cursor = disabled ? 'not-allowed' : 'pointer';
        };

        const updateStep5SubmitState = () => {
            const items = window.mobileStep5PhotoItems || [];
            const anyPending = items.some(i => i && (i.status === 'queued' || i.status === 'uploading'));
            const inFlight = (window.mobileStep5UploadsInFlight || 0) > 0;
            // Disable while any upload is pending/in progress; re-enable when all uploads complete
            setStep5SubmitDisabled(anyPending || inFlight);
        };

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

        // Upload to S3 helper (match desktop Step 5 behavior)
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
                        const errorMsg = `Upload failed (S3): ${xhr.status} ${xhr.statusText}`;
                        console.error('S3 upload error:', xhr.status, xhr.responseText);
                        reject(new Error(errorMsg));
                    }
                };

                xhr.onerror = () => {
                    console.error('Network error during S3 upload', {
                        status: xhr.status,
                        statusText: xhr.statusText,
                        responseText: xhr.responseText
                    });
                    // Check if it's likely a CORS error (status 0 usually indicates CORS)
                    let errorMsg = 'Upload failed (network error).';
                    if (xhr.status === 0) {
                        errorMsg = 'CORS error: S3 bucket must allow PUT requests from this domain. Please configure CORS on your S3 bucket.';
                    } else if (xhr.status === 403) {
                        errorMsg = 'Access denied. Check S3 bucket permissions and CORS configuration.';
                    } else if (xhr.status === 404) {
                        errorMsg = 'S3 endpoint not found. Check bucket name and region configuration.';
                    }
                    reject(new Error(errorMsg));
                };

                xhr.onabort = () => reject(new Error('Upload cancelled.'));

                xhr.send(file);
            });
        };

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
            updateStep5SubmitState();

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
                // If we errored, we're no longer pending; if we succeeded, we're complete.
                if (item && item.status !== 'uploading' && item.status !== 'queued') {
                    // no-op, state is already set
                }
                updateStep5SubmitState();
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
            updateStep5SubmitState();
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
                updateStep5SubmitState();

                // Start uploads
                newItems.forEach(item => uploadOnePhotoItem(item));
            });
        }

        // Video Upload - handled via traditional file upload (not S3 presigned)
        // Backend expects 'video' file input, not video_key
        const videoInput = document.getElementById('mobile_video_upload');
        const videoLabel = document.getElementById('mobile_video_label');
        const videoProgress = document.getElementById('mobile_video_progress');
        const videoProgressFill = document.getElementById('mobile_video_progress_fill');

        if (videoInput) {
            videoInput.addEventListener('change', function() {
                if (this.files.length === 0) return;

                const file = this.files[0];
                if (!file.type.startsWith('video/')) {
                    alert('Only video files are allowed.');
                    this.value = '';
                    return;
                }

                // Show progress (simulated since video uploads via form submission)
                videoProgress.style.display = 'block';
                videoProgressFill.style.width = '0%';
                videoLabel.textContent = 'Video selected - will upload on submit';

                // Simulate progress for UX
                let progress = 0;
                const interval = setInterval(() => {
                    progress += 5;
                    videoProgressFill.style.width = `${Math.min(progress, 90)}%`;
                    if (progress >= 90) {
                        clearInterval(interval);
                    }
                }, 100);

                // Update label
                videoLabel.textContent = `Video: ${file.name} (${(file.size / 1024 / 1024).toFixed(1)} MB)`;
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

            // Ensure all photo keys are synced before submit
            syncPhotoKeys();
        });

        // Init submit button state (in case of restored state/back navigation)
        updateStep5SubmitState();
    }
})();
</script>
@endsection
