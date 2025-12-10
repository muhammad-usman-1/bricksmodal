@extends('talent.onboarding.layout')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-page: #f4f6fb;
            --card-bg: #ffffff;
            --ink-900: #0f1524;
            --ink-700: #3b4150;
            --muted: #7b8191;
            --border: #e4e7ef;
            --primary: #0f0f0f;
            --control: #f6f7fb;
            --shadow: 0 22px 55px rgba(0, 13, 37, 0.12);
            --radius: 14px;
        }

        body {
             background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
            font-family: 'Arimo', sans-serif;

            color: var(--ink-900);
        }

        .wizard-shell {
            min-height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 14px 38px;
        }

        .wizard-card {
            width: 100%;
            max-width: 660px;
            background: var(--card-bg);
            border-radius: 16px;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .wizard-hero {
            background: linear-gradient(135deg, #161616 0%, #0e0e0e 100%);
            color: #ffffff;
            padding: 18px 18px 22px;
            position: relative;
        }

        .wizard-logo {
            text-align: right;
        }

        .wizard-logo img {
            width: 128px;
            height: auto;
        }

        .hero-title {
            font-size: 18px;
            font-weight: 600;
            margin: 8px 0 6px;
        }

        .hero-sub {
            font-size: 13px;
            color: #d5d7de;
            margin-bottom: 12px;
        }

        .progress-track {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            height: 6px;
        }

        .progress-bar {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 999px;
            position: relative;
            overflow: hidden;
        }

        .progress-bar.is-active::after,
        .progress-bar.is-complete::after {
            content: '';
            position: absolute;
            inset: 0;
            background: #ffffff;
            border-radius: 999px;
        }

        .wizard-body {
            padding: 22px 20px 20px;
        }

        .step-title {
            font-size: 16px;
            font-weight: 600;
            margin: 0 0 6px;
            color: var(--ink-900);
        }

        .step-sub {
            margin: 0 0 18px;
            color: var(--muted);
            font-size: 13px;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field label {
            font-size: 12px;
            font-weight: 600;
            color: var(--ink-700);
        }

        .control,
        select.control {
            width: 100%;
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--control);
            padding: 0 12px;
            font-size: 13px;
            color: var(--ink-900);
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .control:focus,
        select.control:focus {
            border-color: #cdd4e3;
            box-shadow: 0 0 0 3px rgba(56, 115, 255, 0.12);
            background: #fff;
        }

        .control::placeholder {
            color: #9aa3b5;
        }

        .dob-wrap {
            position: relative;
        }

        .dob-wrap svg {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa3b5;
            pointer-events: none;
        }

        .dob-input {
            padding-right: 48px;
            background: #f6f7fb;
            cursor: pointer;
        }

        .height-row {
            display: grid;
            grid-template-columns: 100px 1fr;
            gap: 10px;
        }

        .unit-select {
            appearance: none;
            background-color: #1f1f1f;
            color: #f3f4f6;
            border: 1px solid #0f0f0f;
            border-radius: 10px;
            height: 44px;
            padding: 0 12px;
            font-size: 13px;
            font-weight: 600;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e5e7eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) 50%;
        }

        .unit-select:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.14);
        }

        .height-input,
        .weight-input {
            height: 44px;
        }

        .weight-input {
            background: #2d2d32;
            border: 1px solid #1c1c21;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.15);
            color: #f6f7fb;
            padding-right: 38px;

            background-repeat: no-repeat;
            background-position: calc(100% - 12px) 50%;
        }

        .weight-input::placeholder {
            color: #b5b9c5;
        }

        .weight-input::-webkit-outer-spin-button,
        .weight-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .weight-input {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        select.control.nationality-select {
            appearance: none;
            background-color: #1f1f1f !important;
            color: #f3f4f6 !important;
            border: 1px solid #0f0f0f !important;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23e5e7eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) 50%;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.05);
            padding-right: 42px;
            height: 44px;
            border-radius: 10px;
        }

        select.control.nationality-select:focus {
            border-color: #0f0f0f !important;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.16);
            background-color: #1f1f1f !important;
        }

        .phone-row {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 10px;
        }

        .pill-select {
            appearance: none;
            background: var(--control);
            border: 1px solid var(--border);
            border-radius: 10px;
            height: 42px;
            padding: 0 12px;
            font-size: 13px;
            color: var(--ink-900);
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%235b6171" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 10px) 50%;
        }

        .radio-row {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: var(--ink-700);
            margin-top: 6px;
        }

        .radio-row input {
            margin-right: 4px;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 18px;
        }

        .btn-primary {
            min-width: 110px;
            height: 44px;
            border: none;
            border-radius: 10px;
            background: var(--primary);
            color: #ffffff;
            font-size: 13px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.16);
        }

        .btn-secondary {
            min-width: 100px;
            height: 44px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #fff;
            color: var(--ink-700);
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            cursor: pointer;
            transition: transform 0.18s ease;
        }

        .btn-secondary:hover {
            transform: translateY(-1px);
        }

        .action-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
            flex-wrap: wrap;
        }

        .step-panel {
            display: none;
        }

        .step-panel.is-active {
            display: block;
        }

        .placeholder {
            border: 1px dashed var(--border);
            border-radius: 12px;
            padding: 18px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
        }

        .control-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            background: var(--control);
            padding: 0 10px;
        }

        .control-wrap input {
            border: none;
            background: transparent;
            width: 100%;
            outline: none;
            font-size: 13px;
            color: var(--ink-900);
        }

        .pill-prefix,
        .pill-append {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 10px;
            background: #ebedf4;
            border-radius: 8px;
            font-weight: 700;
            font-size: 12px;
            color: #4a5163;
            white-space: nowrap;
        }

        .pill-append {
            gap: 6px;
        }

        .segmented {
            display: inline-flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .seg-btn {
            border: 1px solid var(--border);
            background: #f3f4f8;
            color: var(--ink-700);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease, border-color 0.15s ease;
        }

        .seg-btn .seg-icon {
            width: 20px;
            height: 20px;
            border-radius: 999px;
            background: #dfe3ec;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 800;
            color: var(--ink-700);
        }

        .seg-btn.is-active {
            background: #0f0f0f;
            color: #ffffff;
            border-color: #0f0f0f;
        }

        .seg-btn.is-active .seg-icon {
            background: #ffffff;
            color: #0f0f0f;
        }

        .muted-note {
            margin-top: 6px;
            color: var(--muted);
            font-size: 12px;
        }

        .mini-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: #f7f8fc;
            padding: 12px;
        }

        .mini-card h5 {
            margin: 0 0 8px;
            font-size: 12px;
            font-weight: 700;
            color: var(--ink-700);
        }

        .skin-select {
            appearance: none;
            background: #0f0f0f;
            color: #ffffff;
            border: none;
            border-radius: 10px;
            height: 44px;
            padding: 0 14px;
            font-size: 13px;
            font-weight: 600;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23ffffff" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) 50%;
        }

        .pill-toggle-row {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .pill-toggle-row .seg-btn {
            background: #f6f7fb;
        }

        .measurement-input {
            width: 100%;
            height: 44px;
            border-radius: 10px;
            border: none;
            background: #3a3b40;
            color: #ffffff;
            padding: 0 12px;
            font-size: 13px;
            outline: none;
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, 0.15);
        }

        .measurement-input::placeholder {
            color: #b5b9c5;
        }

        .measurement-input::-webkit-outer-spin-button,
        .measurement-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .measurement-input {
            -moz-appearance: textfield;
            appearance: textfield;
        }

        .hijab-group {
            border: 1px solid #dfe3eb;
            border-radius: 10px;
            background: #ffffff;
            padding: 12px 14px;
            display: flex;
            gap: 20px;
            align-items: center;
            min-height: 54px;
        }

        .hijab-option {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #1a2130;
            user-select: none;
        }

        .hijab-option input {
            position: absolute;
            opacity: 0;
            pointer-events: none;
        }

        .hijab-check {
            width: 28px;
            height: 28px;
            border-radius: 8px;
            border: 1px solid #cdd3dd;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            color: transparent;
            transition: all 0.15s ease;
        }

        .hijab-option input:checked + .hijab-check {
            background: #0f0f0f;
            border-color: #0f0f0f;
            color: #ffffff;
        }

        .hijab-check svg {
            width: 14px;
            height: 14px;
        }

        .notice {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border: 1px solid #f8d9c7;
            background: #fff5ef;
            color: #c45f32;
            border-radius: 10px;
            padding: 12px 14px;
            font-size: 13px;
            line-height: 1.5;
        }

        .notice-icon {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            background: #f3c9b3;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #a8421b;
            flex-shrink: 0;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 14px;
            margin-bottom: 16px;
        }

        .upload-card {
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #f9fafc;
            min-height: 160px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 14px;
            cursor: pointer;
            transition: border-color 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .upload-card:hover {
            border-color: #c6ccda;
            background: #ffffff;
            box-shadow: 0 10px 28px rgba(0, 13, 37, 0.08);
        }

        .upload-inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            color: #606779;
            font-size: 13px;
            font-weight: 600;
        }

        .upload-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eef1f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6a7388;
        }

        .alert {
            border: 1px solid #f5c2c7;
            background: #f8d7da;
            color: #842029;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 12px;
            font-size: 13px;
        }

        .alert ul {
            margin: 8px 0 0;
            padding-left: 18px;
        }

        .upload-label {
            font-weight: 600;
            color: var(--ink-700);
        }

        @media (max-width: 520px) {
            .wizard-card {
                max-width: 100%;
            }

            .field-grid {
                grid-template-columns: 1fr;
            }

            .phone-row {
                grid-template-columns: 1fr;
            }

            .actions {
                justify-content: center;
            }
        }
    </style>

    <div class="wizard-shell">
        <div class="wizard-card">
            <div class="wizard-hero">
                <div class="wizard-logo">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Studio">
                </div>
                <div class="hero-title">Complete Your Profile</div>
                <div class="hero-sub">Step <span data-step-label>1</span> of 4</div>
                <div class="progress-track" aria-hidden="true">
                    <span class="progress-bar is-active" data-progress-index="0"></span>
                    <span class="progress-bar" data-progress-index="1"></span>
                    <span class="progress-bar" data-progress-index="2"></span>
                    <span class="progress-bar" data-progress-index="3"></span>
                </div>
            </div>

            <div class="wizard-body">
                @if ($errors->any())
                    <div class="alert" role="alert">
                        <strong>Please fix the errors below:</strong>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form id="profile-wizard" method="POST" action="{{ route('talent.onboarding.store', 'profile') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="current_step" id="current_step" value="{{ old('current_step', 1) }}">
                    <div class="step-panel is-active" data-step="1">
                        <div class="step-title">Complete Your Profile</div>
                        <p class="step-sub">Tell us about yourself to start your profile setup.</p>

                        <div class="field-grid">
                            <div class="field">
                                <label for="first_name">First Name</label>
                                <input id="first_name" name="first_name" class="control" type="text" placeholder="Enter first name" value="{{ old('first_name', $profile->first_name) }}" required>
                            </div>
                            <div class="field">
                                <label for="last_name">Last Name</label>
                                <input id="last_name" name="last_name" class="control" type="text" placeholder="Enter last name" value="{{ old('last_name', $profile->last_name) }}" required>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 8px;">
                            <div class="field">
                                <label for="date_of_birth">Date of Birth</label>
                                <div class="dob-wrap">
                                    <input id="date_of_birth" name="date_of_birth" class="control dob-input" type="date" placeholder="mm/dd/yy" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" required>
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                        <path d="M8 14h.01"></path>
                                        <path d="M12 14h.01"></path>
                                        <path d="M16 14h.01"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="field">
                                <label for="nationality">Nationality</label>
                                <select id="nationality" name="nationality" class="control nationality-select">
                                    <option value="" {{ old('nationality', $profile->nationality) === null ? 'selected' : '' }}>Select nationality</option>
                                    <option value="kw" {{ old('nationality', $profile->nationality) === 'kw' ? 'selected' : '' }}>Kuwaiti</option>
                                    <option value="ae" {{ old('nationality', $profile->nationality) === 'ae' ? 'selected' : '' }}>Emirati</option>
                                    <option value="sa" {{ old('nationality', $profile->nationality) === 'sa' ? 'selected' : '' }}>Saudi</option>
                                    <option value="bh" {{ old('nationality', $profile->nationality) === 'bh' ? 'selected' : '' }}>Bahraini</option>
                                    <option value="qa" {{ old('nationality', $profile->nationality) === 'qa' ? 'selected' : '' }}>Qatari</option>
                                    <option value="om" {{ old('nationality', $profile->nationality) === 'om' ? 'selected' : '' }}>Omani</option>
                                    <option value="other" {{ old('nationality', $profile->nationality) === 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            <label>Mobile Number</label>
                            @php
                                $whatsappChoice = old(
                                    'whatsapp_choice',
                                    ($profile->whatsapp_number && $profile->whatsapp_number !== $profile->mobile_number) ? 'alt' : 'same'
                                );
                                $authTalent = auth('talent')->user();
                                $loginMobile = old('mobile_number', $profile->mobile_number ?? ($authTalent->phone_number ?? ''));
                                $loginCountry = old('country_code', $profile->country_code ?? ($authTalent->phone_country_code ?? 'kw'));
                                $whatsappDefault = old('whatsapp_number', $profile->whatsapp_number ?? $loginMobile);
                            @endphp
                            <div class="phone-row">
                                <select class="pill-select" name="country_code" aria-label="Country code" required>
                                    <option value="kw" {{ $loginCountry === 'kw' ? 'selected' : '' }}>KW +965</option>
                                    <option value="ae" {{ $loginCountry === 'ae' ? 'selected' : '' }}>AE +971</option>
                                    <option value="sa" {{ $loginCountry === 'sa' ? 'selected' : '' }}>SA +966</option>
                                    <option value="bh" {{ $loginCountry === 'bh' ? 'selected' : '' }}>BH +973</option>
                                    <option value="qa" {{ $loginCountry === 'qa' ? 'selected' : '' }}>QA +974</option>
                                    <option value="om" {{ $loginCountry === 'om' ? 'selected' : '' }}>OM +968</option>
                                </select>
                                <input class="control" id="mobile_number" name="mobile_number" type="tel" placeholder="(555) 000-0000" aria-label="Phone number" value="{{ $loginMobile }}" required>
                            </div>
                            <div class="radio-row">
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="same" {{ $whatsappChoice === 'alt' ? '' : 'checked' }}>
                                    No (same number)
                                </label>
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="alt" {{ $whatsappChoice === 'alt' ? 'checked' : '' }}>
                                    Yes
                                </label>
                                <span style="color: #9aa3b5;">I have another number for WhatsApp.</span>
                            </div>
                            <input type="hidden" name="whatsapp_number" id="whatsapp_number" value="{{ $whatsappDefault }}">
                        </div>

                        <div class="actions">
                            <button type="button" class="btn-primary" data-next>
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="step-panel" data-step="2">
                        <div class="step-title">Complete Your Profile</div>
                        <p class="step-sub">Step 2 of 4 • Measurements and appearance.</p>

                        <div class="field-grid">
                            <div class="field">
                                <label for="height">Height</label>
                                <div class="height-row">
                                    @php
                                        $heightUnit = old('height_unit', 'cm');
                                    @endphp
                                    <select id="height_unit" name="height_unit" class="unit-select" aria-label="Height unit">
                                        <option value="cm" {{ $heightUnit === 'cm' ? 'selected' : '' }}>cm</option>
                                        <option value="ft" {{ $heightUnit === 'ft' ? 'selected' : '' }}>ft</option>
                                    </select>
                                    <input id="height" name="height" class="control height-input" type="number" step="0.1" min="0" placeholder="e.g. 175" value="{{ old('height', $profile->height) }}">
                                </div>
                            </div>
                            <div class="field">
                                <label for="weight">Weight</label>
                                <input id="weight" name="weight" class="control weight-input" type="number" step="0.1" min="0" placeholder="e.g. 60" value="{{ old('weight', $profile->weight) }}">
                            </div>
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            <label>Select Your Gender</label>
                            <div class="segmented" data-segment>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="male">
                                    <span class="seg-icon">♂</span> Male
                                </button>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="female">
                                    <span class="seg-icon">♀</span> Female
                                </button>
                            </div>
                            <input type="hidden" name="gender" id="gender" value="{{ old('gender', $profile->gender ?? 'male') }}">
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            <label>Hijab Preference</label>
                            <div class="muted-note">This helps us match you with appropriate casting calls</div>
                            <div class="hijab-group">
                                @php
                                    $hijabChoice = old('hijab_preference', $profile->hijab_preference ?? 'wear_hijab');
                                @endphp
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="wear_hijab" {{ $hijabChoice === 'wear_hijab' ? 'checked' : '' }}>
                                    <span class="hijab-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="5 13 10 18 19 7" />
                                        </svg>
                                    </span>
                                    <span>Hijabi</span>
                                </label>
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="no_hijab" {{ $hijabChoice === 'no_hijab' ? 'checked' : '' }}>
                                    <span class="hijab-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <polyline points="5 13 10 18 19 7" />
                                        </svg>
                                    </span>
                                    <span>Non-Hijabi</span>
                                </label>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 12px;">
                            <div class="field">
                                <label for="hair_color">Hair Color</label>
                                <input id="hair_color" name="hair_color" class="control" type="text" placeholder="e.g. Brown" value="{{ old('hair_color', $profile->hair_color) }}">
                            </div>
                            <div class="field">
                                <label for="eye_color">Eye Color</label>
                                <input id="eye_color" name="eye_color" class="control" type="text" placeholder="e.g. Blue" value="{{ old('eye_color', $profile->eye_color) }}">
                            </div>
                        </div>

                        <div class="field" style="margin-top: 12px;">
                            <label for="skin_tone">Skin Tone</label>
                            <select id="skin_tone" name="skin_tone" class="skin-select">
                                <option value="" {{ old('skin_tone', $profile->skin_tone) === null ? 'selected' : '' }}>e.g. Fair, Medium, Olive, Dark</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Fair' ? 'selected' : '' }}>Fair</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Light' ? 'selected' : '' }}>Light</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Medium' ? 'selected' : '' }}>Medium</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Olive' ? 'selected' : '' }}>Olive</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Brown' ? 'selected' : '' }}>Brown</option>
                                <option {{ old('skin_tone', $profile->skin_tone) === 'Dark' ? 'selected' : '' }}>Dark</option>
                            </select>
                        </div>

                        <div class="field-grid" style="margin-top: 12px;">
                            <div class="mini-card">
                                <h5>Do you have visible tattoos?</h5>
                                <div class="radio-row">
                                    <label style="display:flex;align-items:center;gap:4px;">
                                        <input type="radio" name="has_visible_tattoos" value="0" checked>
                                        No
                                    </label>
                                    <label style="display:flex;align-items:center;gap:4px;">
                                        <input type="radio" name="has_visible_tattoos" value="1">
                                        Yes
                                    </label>
                                </div>
                            </div>

                            <div class="mini-card">
                                <h5>Do you have piercings?</h5>
                                <div class="radio-row">
                                    <label style="display:flex;align-items:center;gap:4px;">
                                        <input type="radio" name="has_piercings" value="0" checked>
                                        No
                                    </label>
                                    <label style="display:flex;align-items:center;gap:4px;">
                                        <input type="radio" name="has_piercings" value="1">
                                        Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="action-group" style="margin-top: 18px;">
                            <button type="button" class="btn-secondary" data-prev>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                                Back
                            </button>
                            <button type="button" class="btn-primary" data-next>
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="step-panel" data-step="3">
                        <div class="step-title">Complete Your Profile</div>
                        <div class="mini-card" style="margin-bottom: 14px; background:#ffffff; border: none; padding:0;">
                            <p style="margin:0; color: var(--muted); font-size: 13px;">Please provide accurate measurements to help us match you with fitting outfits.</p>
                        </div>

                        <div class="field-grid" style="margin-top: 4px;">
                            <div class="field">
                                <label for="chest">Chest / Bust (cm)</label>
                                <input id="chest" name="chest" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 90" value="{{ old('chest') }}" />
                            </div>
                            <div class="field">
                                <label for="waist_cm">Waist (cm)</label>
                                <input id="waist_cm" name="waist" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 70" value="{{ old('waist') }}" />
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 14px;">
                            <div class="field">
                                <label for="hips_cm">Hips (cm)</label>
                                <input id="hips_cm" name="hips" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 95" value="{{ old('hips') }}" />
                            </div>
                            <div class="field">
                                <label for="shoe_size">Shoe Size (EU)</label>
                                <input id="shoe_size" name="shoe_size" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 39" value="{{ old('shoe_size') }}" />
                            </div>
                        </div>

                        <div class="action-group" style="margin-top: 20px;">
                            <button type="button" class="btn-secondary" data-prev>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                                Back
                            </button>
                            <button type="button" class="btn-primary" data-next>
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="step-panel" data-step="4">
                        <div class="step-title">Complete Your Profile</div>
                        <p class="step-sub">Step 4 of 4 • Identity verification</p>

                        <div class="notice">
                            <span class="notice-icon">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"></path>
                                    <line x1="12" y1="9" x2="12" y2="13"></line>
                                    <line x1="12" y1="17" x2="12.01" y2="17"></line>
                                </svg>
                            </span>
                            <span>We need a copy of your Civil ID or Passport to verify your identity. This information is kept strictly confidential.</span>
                        </div>

                        <div class="upload-grid">
                            <label class="upload-card" for="upload_front">
                                <input id="upload_front" name="id_front" type="file" accept="image/*" style="display:none;" {{ $profile->id_front_path ? '' : 'required' }}>
                                <div class="upload-inner">
                                    <div class="upload-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                    </div>
                                    <div class="upload-label">Civil ID / Passport (Front)</div>
                                    <div data-file-label="front">Click to upload Front Side</div>
                                </div>
                            </label>

                            <label class="upload-card" for="upload_back">
                                <input id="upload_back" name="id_back" type="file" accept="image/*" style="display:none;" {{ $profile->id_back_path ? '' : 'required' }}>
                                <div class="upload-inner">
                                    <div class="upload-icon">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                            <polyline points="17 8 12 3 7 8"></polyline>
                                            <line x1="12" y1="3" x2="12" y2="15"></line>
                                        </svg>
                                    </div>
                                    <div class="upload-label">Civil ID / Passport (Back)</div>
                                    <div data-file-label="back">Click to upload Back Side</div>
                                </div>
                            </label>
                        </div>

                        <div class="action-group" style="margin-top: 10px;">
                            <button type="button" class="btn-secondary" data-prev>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                                Back
                            </button>
                            <button type="submit" class="btn-primary" style="background:#0b9f62;">
                                Submit Application
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M5 12h14" />
                                    <path d="M13 6l6 6-6 6" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        (function () {
            const steps = Array.from(document.querySelectorAll('[data-step]'));
            const progressBars = Array.from(document.querySelectorAll('[data-progress-index]'));
            const stepLabel = document.querySelector('[data-step-label]');
            const stepInput = document.getElementById('current_step');
            const initial = Math.max(1, Math.min(parseInt(stepInput?.value || '1', 10), steps.length));
            let current = initial - 1;

            function render() {
                steps.forEach((panel, idx) => {
                    panel.classList.toggle('is-active', idx === current);
                });

                progressBars.forEach((bar, idx) => {
                    bar.classList.toggle('is-active', idx === current);
                    bar.classList.toggle('is-complete', idx < current);
                });

                if (stepLabel) {
                    stepLabel.textContent = current + 1;
                }

                if (stepInput) {
                    stepInput.value = current + 1;
                }
            }

            function next() {
                if (current < steps.length - 1) {
                    current += 1;
                    render();
                }
            }

            function prev() {
                if (current > 0) {
                    current -= 1;
                    render();
                }
            }

            document.addEventListener('click', (e) => {
                if (e.target.closest('[data-next]')) {
                    e.preventDefault();
                    next();
                }
                if (e.target.closest('[data-prev]')) {
                    e.preventDefault();
                    prev();
                }
            });

            // segmented toggles
            document.querySelectorAll('[data-segment]').forEach(group => {
                const buttons = group.querySelectorAll('[data-seg-btn]');
                buttons.forEach(btn => {
                    btn.addEventListener('click', () => {
                        buttons.forEach(b => b.classList.remove('is-active'));
                        btn.classList.add('is-active');

                        const target = btn.dataset.targetInput ? document.querySelector(btn.dataset.targetInput) : null;
                        if (target && btn.dataset.value !== undefined) {
                            target.value = btn.dataset.value;
                        }
                    });
                });

                const targetInput = buttons[0]?.dataset?.targetInput ? document.querySelector(buttons[0].dataset.targetInput) : null;
                if (targetInput) {
                    const activeButton = Array.from(buttons).find(b => b.dataset.value === targetInput.value);
                    if (activeButton) {
                        buttons.forEach(b => b.classList.remove('is-active'));
                        activeButton.classList.add('is-active');
                    }
                }
            });

            // WhatsApp alt toggle and syncing
            const whatsappRadios = document.querySelectorAll('input[name="whatsapp_choice"]');
            const whatsappHidden = document.getElementById('whatsapp_number');
            const mobileInput = document.getElementById('mobile_number');

            function syncWhatsapp() {
                const choice = document.querySelector('input[name="whatsapp_choice"]:checked')?.value;
                if (!mobileInput || !whatsappHidden) return;

                if (choice === 'alt') {
                    mobileInput.readOnly = false;
                    whatsappHidden.value = mobileInput.value;
                } else {
                    mobileInput.readOnly = true;
                    whatsappHidden.value = mobileInput.value;
                }
            }

            whatsappRadios.forEach(r => r.addEventListener('change', syncWhatsapp));
            if (mobileInput) {
                mobileInput.addEventListener('input', syncWhatsapp);
            }

            syncWhatsapp();

            // Height placeholder updates based on unit
            const heightUnit = document.getElementById('height_unit');
            const heightInput = document.getElementById('height');
            if (heightUnit && heightInput) {
                const updateHeightPlaceholder = () => {
                    heightInput.placeholder = heightUnit.value === 'ft' ? 'e.g. 5.6' : 'e.g. 175';
                };
                heightUnit.addEventListener('change', updateHeightPlaceholder);
                updateHeightPlaceholder();
            }

            // file label update for ID uploads
            const frontInput = document.getElementById('upload_front');
            const backInput = document.getElementById('upload_back');
            const frontLabel = document.querySelector('[data-file-label="front"]');
            const backLabel = document.querySelector('[data-file-label="back"]');

            function updateLabel(input, label) {
                if (!input || !label) return;
                input.addEventListener('change', () => {
                    const fileName = input.files && input.files[0] ? input.files[0].name : '';
                    label.textContent = fileName || label.dataset.defaultText || label.textContent;
                });
            }

            if (frontLabel) frontLabel.dataset.defaultText = frontLabel.textContent;
            if (backLabel) backLabel.dataset.defaultText = backLabel.textContent;

            updateLabel(frontInput, frontLabel);
            updateLabel(backInput, backLabel);

            if (stepInput) {
                const form = document.getElementById('profile-wizard');
                if (form) {
                    form.addEventListener('submit', () => {
                        stepInput.value = current + 1;
                    });
                }
            }

            render();
        })();
    </script>
@endsection
