@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #ffffff;
            --ink-900: #0f1524;
            --ink-800: #1c2435;
            --ink-700: #3b4150;
            --ink-600: #4b5563;
            --ink-500: #6b7280;
            --border: #e5e8ef;
            --primary: black;
            --control: #fbfcff;
        }

        body {
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Arimo', sans-serif;
            color: #1f1f1f;
        }


        .wizard-shell {
            min-height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .wizard-card {
            width: 100%;
            max-width: 680px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            position: relative;
            overflow: hidden;

        }

        .wizard-hero {
            background: black;
            padding: 40px 32px 32px;
            color: #fff;
            text-align: left;
        }

        .hero-title {
            font-size: 24px;
            font-weight: 400;
            margin: 0 0 16px;
            color: #ffffff;
        }

        .hero-sub {
            font-size: 14px;
            color: #888888;
            margin-bottom: 12px;
            display: block;
        }

        .progress-track {
            display: flex;
            gap: 8px;
            justify-content: flex-start;
        }

        .progress-bar {
            height: 4px;
            flex: 1;
            background: #333333;
            border-radius: 2px;
            transition: background 0.3s ease;
        }

        .progress-bar.is-active,
        .progress-bar.is-complete {
            background: #ffffff;
        }

        .wizard-body {
            padding: 24px 28px 32px;
        }

        .step-panel {
            /* display: none; */
        }
        .step-panel.is-active {
            display: block;
            animation: fadeIn 0.4s ease forwards;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .step-title {
            font-size: 18px;
            font-weight: 700;
            color: var(--ink-900);
            margin-bottom: 6px;
        }

        .step-sub {
            font-size: 14px;
            color: var(--ink-500);
            margin-bottom: 24px;
            line-height: 1.5;
        }

        .field-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .field {
            margin-bottom: 16px;
        }

        .field label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: black;
            margin-bottom: 12px;
             font-family: 'Arimo', sans-serif;
        }

        .control {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            font-size: 15px;
            color: #838181;
            transition: all 0.2s;
        }

        .control:focus {
            outline: none;
            border-color: #000;
            box-shadow: 0 0 0 1px #000;
        }

        .control::placeholder {
            color: #9ca3af;
        }

        /* Keep DOB and Nationality inputs light grey */
        .dob-input,
        .nationality-select {
            background: #f5f5f5;
        }

        .dob-wrap, .nationality-wrapper {
            position: relative;
        }

        /* DOB: use native date picker, disallow future dates */
        .dob-input {
            background: #f5f5f5;
        }

        .dob-wrap svg, .nationality-wrapper svg {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            pointer-events: none;
        }

        .nationality-flag {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 2;
        }

        .nationality-select {
            padding-left: 36px;
        }

        .radio-row {
            display: flex;
            gap: 16px;
        }

        .radio-row label {
            cursor: pointer;
            font-weight: 500;
            color: var(--ink-700);
        }

        .pill-select {
            appearance: none;
            background: var(--control);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 0 12px;
            height: 48px;
            font-size: 13px;
        }

        .phone-row {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 10px;
            align-items: stretch;
        }

        .country-code-display {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 48px;
            padding: 0 12px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #f5f5f5;
            font-size: 14px;
            color: #1f1f1f;
            white-space: nowrap;
            box-sizing: border-box;
        }

        .country-flag {
            width: auto;
            height: 18px;
            aspect-ratio: 4 / 3;
            display: inline-block;
            flex-shrink: 0;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }

        .actions {
            margin-top: 32px;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 12px;
        }

        .action-group {
             display: flex;
             justify-content: flex-end;
             gap: 12px;
        }

        .btn-primary, .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            border-radius: 10px;
            padding: 0 20px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            gap: 8px;
        }

        .btn-primary {
            background: black;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .btn-primary:hover, .btn-primary:focus, .btn-primary:active, .btn-primary:focus-visible {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15) !important;
            background: #000000 !important;
            color: #ffffff !important;
            outline: none !important;
            -webkit-tap-highlight-color: transparent;
        }

        .btn-primary:active {
            transform: translateY(0);
            background: #000000 !important;
        }

        .btn-submit {
            background: #0fb478 !important;
            color: white !important;
            border: none !important;
        }

        .btn-primary:disabled, .btn-primary[disabled] {
            background: black !important;
            opacity: 0.7;
            cursor: not-allowed;
        }

        .btn-submit:hover, .btn-submit:focus, .btn-submit:active {
            background: #0fb478 !important;
            color: white !important;
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 180, 120, 0.3) !important;
        }

        .btn-secondary {
            background: #fff;
            color: var(--ink-700);
            border: 1px solid var(--border);
        }

        .btn-secondary:hover {
            background: #f9fafc;
            border-color: #d1d5db;
        }

        .segmented {
            background: transparent;
            padding: 0;
            display: flex;
            gap: 12px;
            justify-content: flex-start;
        }

        .seg-btn {

            height: 48px;
            border: 1px solid #e5e7eb;
            background: #ffffff;
            border-radius: 12px;
            font-weight: 500;
            color: #1a1a1a;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: flex-start;
            padding: 0 16px;
            gap: 12px;
            font-size: 15px;
            transition: all 0.2s;
            flex: none;
        }

        .seg-btn img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .seg-btn.is-active {
            background: black;
            color: #ffffff;
            border-color: black;
        }

        /* Icon Inversion Logic to match image exactly */
        /* Inactive buttons (white bg) -> Black icons */
        /* Active buttons (dark bg) -> White icons */

        .seg-btn:not(.is-active) img {
            filter: brightness(0); /* Force black */
        }

        .seg-btn.is-active img {
            filter: brightness(0) invert(1); /* Force white regardless of source color */
        }

        /* Override for specific user-provided default colors if they mismatch filters */
        /* If original male.png is black, filter:brightness(0) keeps it black. */
        /* If original female.png is white, filter:brightness(0) turns it black. */
        /* If active, invert(1) brightness(100) turns any color to white. */

        .hijab-group {
            display: inline-flex;
            gap: 32px;

            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 18px 24px;
            align-items: center;
            justify-content: flex-start;
        }

        .hijab-option {
            border: none;
            background: transparent;
            padding: 0;
            display: flex;
            flex-direction: row;
            align-items: center;
            gap: 12px;
            cursor: pointer;
            transition: all 0.2s;
            flex: none;
        }

        .hijab-option input {
            appearance: none;
            width: 24px;
            height: 24px;
            border: 1.5px solid #d1d5db;
            border-radius: 7px;
            margin: 0;
            display: grid;
            place-content: center;
            cursor: pointer;
            background: #fff;
            transition: all 0.2s;
        }

        .hijab-option input::before {
            content: "✓";
            color: white;
            font-size: 12px;
            font-weight: bold;
            display: none;
        }

        .hijab-option input:checked {
            background: #1a1a1a;
            border-color: #1a1a1a;
        }

        .hijab-option input:checked::before {
            display: block;
        }

        .hijab-option span {
            font-size: 15px;
            font-weight: 500;
            color: #1a1a1a;
            white-space: nowrap;
        }

        .mini-card {
            background: #f9fafb;
            border: none;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 0;
            flex: 1;
        }

        .mini-card h5 {
            margin: 0 0 16px;
            font-size: 14px;
            font-weight: 500;
            color: #1a1a1a;
        }

        .action-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 32px;
        }
        /* Step 4: when only submit is visible, keep it right */
        .action-group.step4-right-only {
            justify-content: flex-end;
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #4b5563;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link:hover {
            color: #1a1a1a;
            text-decoration: none !important;
        }

        .back-link:active, .back-link:focus {
            outline: none !important;
            text-decoration: none !important;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        .upload-grid.grid-3 {
            grid-template-columns: 1fr 1fr 1fr;
        }

        .upload-card {
            border: 1px dashed #d1d5db;
            border-radius: 12px;
            padding: 20px 10px;
            text-align: center;
            cursor: pointer;


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
            width: auto;
            height: auto;
            background: transparent;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 4px;
        }

        .upload-icon img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            opacity: 0.6;
        }

        .upload-label {
            font-weight: 500;
            color: #6a7388;
            font-size: 14px;
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            padding: 0 10px;
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

        .add-more-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #6366f1;
            font-weight: 600;
            font-size: 14px;
            text-decoration: none;
            cursor: pointer;
            margin-bottom: 20px;
            padding: 10px 18px;
            border: 1px solid #e0e7ff;
            border-radius: 10px;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        .add-more-link:hover {
            color: #4f46e5;
            background: #f5f7ff;
            border-color: #c7d2fe;
            text-decoration: none !important;
        }

        .additional-photos-area {
            border: 2px dashed #d1d5db;
            border-radius: 16px;
            padding: 40px;
            text-align: center;
            background: #f9fafc;
            cursor: pointer;
            transition: all 0.2s;
        }

        .additional-photos-area.drag-over {
            border-color: #6366f1;
            background: #f5f3ff;
        }

        .photo-previews {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
            gap: 12px;
            margin-top: 20px;
        }

        .photo-preview-item {
            position: relative;
            aspect-ratio: 1;
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid #e5e7eb;
        }

        .photo-preview-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .alert strong {
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .alert ul {
            margin: 0;
            padding-left: 18px;
        }

        .alert li {
            margin-bottom: 4px;
        }

        .field-error, .error-text {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
            min-height: 18px; /* Reserve space for one line of error text */
            visibility: hidden; /* Use visibility instead of display to prevent layout shifts */
        }

        .info-box {
            background: #fff8f1;
            border: 1px solid #ffd8b1;
            border-radius: 12px;
            padding: 16px 20px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
            margin-bottom: 24px;
        }

        .info-box svg {
            color: #f97316;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-box-content {
            color: #9a3412;
            font-size: 14px;
            line-height: 1.5;
            font-weight: 500;
        }

        @media (max-width: 520px) {
            .wizard-card {
                border-radius: 14px;
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

        .required-asterisk {
            color: #dc3545;
            margin-left: 2px;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <div class="wizard-shell">
        <div class="wizard-card">
            <div class="wizard-hero">
                <!-- Logo removed -->
                <div class="hero-title" style="display: flex; justify-content: space-between; align-items: center;">
                    {{ \App\Helpers\Bilingual::get('onboarding.complete_profile') }}
                    <a href="javascript:void(0)" id="logout-trigger" style="color: #ffffff; opacity: 0.8; transition: opacity 0.2s;" title="Logout">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                    </a>
                </div>
                <form id="logout-form" action="{{ route('talent.logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
                <div class="hero-sub">{{ \App\Helpers\Bilingual::get('onboarding.step') }} <span data-step-label>{{ match($currentStep) { 'step-1' => 1, 'step-2' => 2, 'step-3' => 3, 'step-4' => 4, 'step-5' => 5, default => 1 } }}</span> {{ \App\Helpers\Bilingual::get('onboarding.of') }} 5</div>
                <div class="progress-track" aria-hidden="true">
                    <span class="progress-bar {{ $initialStep == 1 ? 'is-active' : ($initialStep > 1 ? 'is-complete' : '') }}" data-progress-index="0"></span>
                    <span class="progress-bar {{ $initialStep == 2 ? 'is-active' : ($initialStep > 2 ? 'is-complete' : '') }}" data-progress-index="1"></span>
                    <span class="progress-bar {{ $initialStep == 3 ? 'is-active' : ($initialStep > 3 ? 'is-complete' : '') }}" data-progress-index="2"></span>
                    <span class="progress-bar {{ $initialStep == 4 ? 'is-active' : ($initialStep > 4 ? 'is-complete' : '') }}" data-progress-index="3"></span>
                    <span class="progress-bar {{ $initialStep == 5 ? 'is-active' : ($initialStep > 5 ? 'is-complete' : '') }}" data-progress-index="4"></span>
                </div>
            </div>

            <div class="wizard-body">
                {{-- Removed top stacked error box; field-level errors only --}}

                @if($currentStep == 'step-1')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-1') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="1">
                        @php
                            $whatsappChoice = old('whatsapp_choice');
                            if (!$whatsappChoice && isset($profile)) {
                                $whatsappChoice = ($profile->whatsapp_number && $profile->whatsapp_number != $profile->mobile_number) ? 'alt' : 'same';
                            }
                            $whatsappChoice = $whatsappChoice ?: 'same';
                        @endphp
                        <!-- Redundant titles removed -->


                        <div class="field-grid">
                            <div class="field">
                                <label for="first_name">{{ \App\Helpers\Bilingual::get('onboarding.first_name') }} <span class="required-asterisk">*</span></label>
                                <input id="first_name" name="first_name" class="control" type="text" placeholder="Enter first name" value="{{ old('first_name', $profile->first_name) }}" required pattern="[a-zA-Z\s]+" autocomplete="given-name">
                                <span class="error-text" id="error-first_name" style="color:#dc3545; font-size:12px; margin-top:4px;">First name is required (English letters only)</span>
                            </div>
                            <div class="field">
                                <label for="last_name">{{ \App\Helpers\Bilingual::get('onboarding.last_name') }} <span class="required-asterisk">*</span></label>
                                <input id="last_name" name="last_name" class="control" type="text" placeholder="Enter last name" value="{{ old('last_name', $profile->last_name) }}" required pattern="[a-zA-Z\s]+" autocomplete="family-name">
                                <span class="error-text" id="error-last_name" style="color:#dc3545; font-size:12px; margin-top:4px;">Last name is required (English letters only)</span>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 8px;">
                            <div class="field">
                                <label for="date_of_birth">{{ \App\Helpers\Bilingual::get('onboarding.date_of_birth') }} <span class="required-asterisk">*</span></label>
                                <div class="dob-wrap">
                                    <input
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        class="control dob-input @error('date_of_birth') is-invalid @enderror"
                                        type="date"
                                        value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}"
                                        max="{{ \Carbon\Carbon::now()->subYears(18)->format('Y-m-d') }}"
                                        required
                                    >
                                    @error('date_of_birth')
                                        <span class="field-error">{{ $message }}</span>
                                    @enderror
                                </div>
                                <span class="error-text" id="error-date_of_birth" style="color:#dc3545; font-size:12px; margin-top:4px;">Date of birth is required</span>
                            </div>
                            <div class="field">
                                <label for="nationality">{{ \App\Helpers\Bilingual::get('onboarding.nationality') }} <span class="required-asterisk">*</span></label>
                                <div class="nationality-wrapper">
                                    <span id="nationality_flag" class="fi nationality-flag" style="display:none;"></span>
                                    <select id="nationality" name="nationality" class="control nationality-select" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_nationality') }}</option>
                                        <option value="Unspecified" {{ old('nationality', $profile->nationality) == 'Unspecified' ? 'selected' : '' }}>Unspecified</option>
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $code }}" {{ old('nationality', $profile->nationality) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                             <label for="mobile_number">{{ \App\Helpers\Bilingual::get('onboarding.mobile_number') }} <span class="required-asterisk">*</span></label>
                             <div class="phone-row" style="margin-bottom: 16px;">
                                <div class="country-code-display" style="background:#e9ecef;">
                                    <span class="fi fi-kw country-flag" title="Kuwait"></span>
                                    <span>+965</span>
                                </div>
                                <input type="hidden" name="country_code" value="{{ old('country_code', $profile->country_code ?? auth('talent')->user()->phone_country_code ?? 'kw') }}">
                                <input class="control" id="mobile_number" name="mobile_number" type="tel"
                                    value="{{ old('mobile_number', $profile->mobile_number ?? auth('talent')->user()->phone_number) }}"
                                    readonly style="background-color: #e9ecef; cursor: not-allowed;" required>
                             </div>

                             <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px;">{{ \App\Helpers\Bilingual::get('onboarding.whatsapp_question') }}</label>
                             <div class="radio-row" style="margin-top: 6px;">
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" id="wa_same" name="whatsapp_choice" value="same" {{ $whatsappChoice == 'same' ? 'checked' : '' }}> {{ \App\Helpers\Bilingual::get('onboarding.yes') }}
                                </label>
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" id="wa_alt" name="whatsapp_choice" value="alt" {{ $whatsappChoice == 'alt' ? 'checked' : '' }}> {{ \App\Helpers\Bilingual::get('onboarding.no') }}
                                </label>
                             </div>
                             <div id="whatsapp_number_section" style="display:none; margin-top:12px;">
                                <label style="font-size: 12px; font-weight:600; margin-bottom:6px;">{{ \App\Helpers\Bilingual::get('onboarding.whatsapp_number') }}</label>
                                <div class="phone-row">
                                     <div class="country-code-display">
                                         <span class="fi fi-kw country-flag" title="Kuwait"></span>
                                         <span>+965</span>
                                     </div>
                                     <input type="hidden" name="whatsapp_country_code" value="kw">
                                     <input class="control" id="whatsapp_number_input" name="whatsapp_number" type="tel" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}" maxlength="8" pattern="\d*">
                                </div>
                                <span id="whatsapp-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: 4px;">WhatsApp number must be 8 digits</span>
                             </div>
                             <script>
                                (function() {
                                    try {
                                        var waSec = document.getElementById('whatsapp_number_section');
                                        var waInp = document.getElementById('whatsapp_number_input');
                                        var waErr = document.getElementById('whatsapp-error');
                                        var form = document.querySelector('form[action*="step-1"]');

                                        function doToggle(forceVal) {
                                            var val = forceVal;
                                            if(!val) {
                                                var chk = document.querySelector('input[name="whatsapp_choice"]:checked');
                                                val = chk ? chk.value : 'same';
                                            }

                                            if(waSec) {
                                                waSec.style.display = (val === 'alt') ? 'block' : 'none';
                                                if(waInp) {
                                                    if(val === 'alt') {
                                                        waInp.setAttribute('required', 'required');
                                                    } else {
                                                        waInp.removeAttribute('required');
                                                        // clear error state when hidden
                                                        waInp.classList.remove('is-invalid');
                                                        waInp.style.borderColor = '#e5e7eb';
                                                        if(waErr) waErr.style.display = 'none';
                                                    }
                                                }
                                            }
                                        }

                                        // Restrict WhatsApp input to numbers only
                                        if (waInp) {
                                            waInp.addEventListener('input', function(e) {
                                                this.value = this.value.replace(/\D/g, '');

                                                // Clear error on input
                                                if (this.classList.contains('is-invalid')) {
                                                    this.classList.remove('is-invalid');
                                                    this.style.borderColor = '#e5e7eb';
                                                    if(waErr) waErr.style.display = 'none';
                                                }
                                            });
                                        }

                                        // Bind radio events
                                        var radios = document.getElementsByName('whatsapp_choice');
                                        for(var i=0; i<radios.length; i++) {
                                            radios[i].addEventListener('change', function(e) { doToggle(this.value); });
                                        }

                                        // Form Validation
                                        if (form) {
                                            // Name inputs - English only, auto-capitalize
                                            ['first_name', 'last_name'].forEach(id => {
                                                const el = document.getElementById(id);
                                                if(el) {
                                                    // Function to capitalize name
                                                    const capitalizeName = (text) => {
                                                        // Remove any non-English letters and spaces
                                                        text = text.replace(/[^a-zA-Z\s]/g, '');
                                                        // Capitalize first letter of each word
                                                        return text.toLowerCase().replace(/\b\w/g, function(char) {
                                                            return char.toUpperCase();
                                                        });
                                                    };
                                                    
                                                    // Prevent Arabic and non-English characters on input
                                                    el.addEventListener('input', function(e) {
                                                        const cursorPos = this.selectionStart;
                                                        const oldValue = this.value;
                                                        const newValue = capitalizeName(this.value);
                                                        
                                                        this.value = newValue;
                                                        
                                                        // Restore cursor position (adjust for removed characters)
                                                        const diff = newValue.length - oldValue.length;
                                                        this.setSelectionRange(cursorPos + diff, cursorPos + diff);
                                                    });
                                                    
                                                    // Handle paste events
                                                    el.addEventListener('paste', function(e) {
                                                        e.preventDefault();
                                                        let pastedText = (e.clipboardData || window.clipboardData).getData('text');
                                                        pastedText = capitalizeName(pastedText);
                                                        this.value = pastedText;
                                                    });
                                                    
                                                    // Handle keydown to prevent Arabic characters
                                                    el.addEventListener('keydown', function(e) {
                                                        // Allow: backspace, delete, tab, escape, enter, arrow keys
                                                        if ([8, 9, 27, 13, 46, 35, 36, 37, 38, 39, 40].indexOf(e.keyCode) !== -1 ||
                                                            // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                                                            (e.keyCode === 65 && e.ctrlKey === true) ||
                                                            (e.keyCode === 67 && e.ctrlKey === true) ||
                                                            (e.keyCode === 86 && e.ctrlKey === true) ||
                                                            (e.keyCode === 88 && e.ctrlKey === true)) {
                                                            return;
                                                        }
                                                        // Block if character is not English letter or space
                                                        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 90)) && (e.keyCode < 96 || e.keyCode > 105) && e.keyCode !== 32) {
                                                            e.preventDefault();
                                                        }
                                                    });
                                                }
                                            });

                                            form.addEventListener('submit', function(e) {
                                                let isValid = true;
                                                const requiredIds = [
                                                    { id: 'first_name', msg: 'First name is required' },
                                                    { id: 'last_name', msg: 'Last name is required' },
                                                    { id: 'date_of_birth', msg: 'Date of birth is required' },
                                                    { id: 'nationality', msg: 'Nationality is required' }
                                                ];

                                                // Validate core fields
                                                requiredIds.forEach(item => {
                                                    const el = document.getElementById(item.id);
                                                    const errEl = document.getElementById('error-' + item.id);

                                                    if (el && !el.value.trim()) {
                                                        isValid = false;
                                                        el.classList.add('is-invalid');
                                                        el.style.borderColor = '#dc3545';
                                                        if(errEl) errEl.style.visibility = 'visible';

                                                        // Add input listener to clear error
                                                        el.addEventListener('input', function() {
                                                            this.classList.remove('is-invalid');
                                                            this.style.borderColor = '#e5e7eb';
                                                            if(errEl) errEl.style.visibility = 'hidden';
                                                        }, { once: true });

                                                        // For select inputs (nationality)
                                                        el.addEventListener('change', function() {
                                                            this.classList.remove('is-invalid');
                                                            this.style.borderColor = '#e5e7eb';
                                                            if(errEl) errEl.style.visibility = 'hidden';
                                                        }, { once: true });
                                                    }
                                                });

                                                // Validate WhatsApp if alternate is selected
                                                const chk = document.querySelector('input[name="whatsapp_choice"]:checked');
                                                const choice = chk ? chk.value : 'same';

                                                if (choice === 'alt' && waInp) {
                                                    const val = waInp.value.replace(/\D/g, '');
                                                    if (val.length !== 8) {
                                                        isValid = false;
                                                        waInp.classList.add('is-invalid');
                                                        waInp.style.borderColor = '#dc3545';
                                                        if(waErr) waErr.style.display = 'block';
                                                        e.preventDefault();
                                                    }
                                                }

                                                if (!isValid) e.preventDefault();
                                            });
                                        }

                                        // Init immediately
                                        doToggle();

                                    } catch(e) { console.error('WA Script Error:', e); }
                                })();
                             </script>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 32px;">
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-2')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-2') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="2">
                        <!-- Redundant titles removed -->


                        <div class="field-grid">
                            <div class="field">
                                <label for="height">{{ \App\Helpers\Bilingual::get('onboarding.height') }} <span class="required-asterisk">*</span></label>
                                <input id="height" name="height" class="control" type="number" step="0.1" placeholder="e.g. 175" value="{{ old('height', $profile->height) }}" required>
                                <input type="hidden" id="height_unit" value="cm">
                                <span class="error-text" id="error-height" style="color:#dc3545; font-size:12px; margin-top:4px;">Height is required</span>
                            </div>
                            <div class="field">
                                <label for="weight">{{ \App\Helpers\Bilingual::get('onboarding.weight') }} <span class="required-asterisk">*</span></label>
                                <input id="weight" name="weight" class="control" type="number" step="0.1" placeholder="e.g. 60" value="{{ old('weight', $profile->weight) }}" required>
                                <span class="error-text" id="error-weight" style="color:#dc3545; font-size:12px; margin-top:4px;">Weight is required</span>
                            </div>
                        </div>

                        <div class="field" style="margin-top: 20px;">
                            <label>{{ \App\Helpers\Bilingual::get('onboarding.select_gender') }}</label>
                            <div class="segmented" data-segment>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="male">
                                    <img src="{{ asset('images/male.png') }}" alt="" class="male-icon">
                                    {{ \App\Helpers\Bilingual::get('onboarding.male') }}
                                </button>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="female">
                                    <img src="{{ asset('images/female.png') }}" alt="" class="female-icon">
                                    {{ \App\Helpers\Bilingual::get('onboarding.female') }}
                                </button>
                            </div>
                            <input type="hidden" name="gender" id="gender" value="{{ old('gender', $profile->gender ?? 'male') }}">
                        </div>

                        <div class="field" id="hijab_preference_section" style="margin-top: 24px; display:none;">
                            <label>{{ \App\Helpers\Bilingual::get('onboarding.hijab_preference') }}</label>
                            <p style="font-size: 13px; color: #666;">{{ \App\Helpers\Bilingual::get('onboarding.hijab_help') }}</p>
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

                        <div class="field-grid" style="margin-top:20px; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px;">
                            <div class="field" id="hair_color_field">
                                <label for="hair_color">{{ \App\Helpers\Bilingual::get('onboarding.hair_color') }} <span class="required-asterisk">*</span></label>
                                <div style="position:relative;">
                                    @php
                                        $hairOptions = ['Black','Brown','Blonde','Auburn','Red','Grey','White','Bald','Dyed / Colored'];
                                        $hairSelected = old('hair_color', $profile->hair_color);
                                    @endphp
                                    <select id="hair_color" name="hair_color" class="control" style="appearance:none;" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_hair_color') }}</option>
                                        @foreach($hairOptions as $option)
                                            <option value="{{ $option }}" {{ $hairSelected === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span class="error-text" id="error-hair_color" style="color:#dc3545; font-size:12px; margin-top:4px;">Hair color is required</span>
                            </div>
                            <div class="field">
                                <label for="eye_color">{{ \App\Helpers\Bilingual::get('onboarding.eye_color') }} <span class="required-asterisk">*</span></label>
                                <div style="position:relative;">
                                    @php
                                        $eyeOptions = ['Brown','Hazel','Blue','Green','Gray','Amber'];
                                        $eyeSelected = old('eye_color', $profile->eye_color);
                                    @endphp
                                    <select id="eye_color" name="eye_color" class="control" style="appearance:none;" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_eye_color') }}</option>
                                        @foreach($eyeOptions as $option)
                                            <option value="{{ $option }}" {{ $eyeSelected === $option ? 'selected' : '' }}>{{ $option }}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span class="error-text" id="error-eye_color" style="color:#dc3545; font-size:12px; margin-top:4px;">Eye color is required</span>
                            </div>
                            <div class="field">
                                <label for="skin_tone">{{ \App\Helpers\Bilingual::get('onboarding.skin_tone') }} <span class="required-asterisk">*</span></label>
                                <div style="position:relative;">
                                    <select id="skin_tone" name="skin_tone" class="control" style="appearance:none;" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_skin_color') }}</option>
                                        @foreach(['Fair','Light','Medium','Olive','Brown','Dark'] as $tone)
                                            <option value="{{$tone}}" {{ old('skin_tone', $profile->skin_tone) == $tone ? 'selected' : '' }}>{{$tone}}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span class="error-text" id="error-skin_tone" style="color:#dc3545; font-size:12px; margin-top:4px;">Skin tone is required</span>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 24px; gap: 16px;">
                            <div class="mini-card">
                                <h5>
                                    <div style="text-align: left;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.visible_tattoos', [], 'en') }}</div>
                                    <div style="text-align: right; direction: rtl;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.visible_tattoos', [], 'ar') }}</div>
                                </h5>
                                <div style="display: flex; gap: 24px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_visible_tattoos" value="0" {{ old('has_visible_tattoos', $profile->has_visible_tattoos ?? 0) == 0 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> {{ \App\Helpers\Bilingual::get('onboarding.no') }}
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_visible_tattoos" value="1" {{ old('has_visible_tattoos', $profile->has_visible_tattoos) == 1 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> {{ \App\Helpers\Bilingual::get('onboarding.yes') }}
                                    </label>
                                </div>
                            </div>
                            <div class="mini-card">
                                <h5>
                                    <div style="text-align: left;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.piercings', [], 'en') }}</div>
                                    <div style="text-align: right; direction: rtl;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.piercings', [], 'ar') }}</div>
                                </h5>
                                <div style="display: flex; gap: 24px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_piercings" value="0" {{ old('has_piercings', $profile->has_piercings ?? 0) == 0 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> {{ \App\Helpers\Bilingual::get('onboarding.no') }}
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_piercings" value="1" {{ old('has_piercings', $profile->has_piercings) == 1 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> {{ \App\Helpers\Bilingual::get('onboarding.yes') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-1') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                {{ \App\Helpers\Bilingual::get('onboarding.back') }}
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('form[action*="step-2"]');
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        let isValid = true;
                                        function attachClearListener(el, errEl) {
                                            if (!el || !errEl) return;
                                            ['input', 'change'].forEach(evt => {
                                                el.addEventListener(evt, function() {
                                                    this.classList.remove('is-invalid');
                                                    this.style.borderColor = '#e5e7eb';
                                                    errEl.style.visibility = 'hidden';
                                                }, { once: true });
                                            });
                                        }

                                        const requiredIds = [
                                            { id: 'height', msg: 'Height is required' },
                                            { id: 'weight', msg: 'Weight is required' },
                                            { id: 'hair_color', msg: 'Hair color is required' },
                                            { id: 'eye_color', msg: 'Eye color is required' },
                                            { id: 'skin_tone', msg: 'Skin tone is required' }
                                        ];

                                        // Strict Validation for Height/Weight
                                        const heightEl = document.getElementById('height');
                                        if(heightEl && heightEl.value) {
                                            const hVal = parseFloat(heightEl.value);
                                            if(hVal < 50 || hVal > 300) {
                                                isValid = false;
                                                heightEl.classList.add('is-invalid');
                                                heightEl.style.borderColor = '#dc3545';
                                                const hErr = document.getElementById('error-height');
                                                if(hErr) {
                                                    hErr.textContent = 'Height must be between 50 and 300 cm.';
                                                    hErr.style.visibility = 'visible';
                                                    attachClearListener(heightEl, hErr);
                                                }
                                            }
                                        }

                                        const weightEl = document.getElementById('weight');
                                        if(weightEl && weightEl.value) {
                                            const wVal = parseFloat(weightEl.value);
                                            if(wVal < 40 || wVal > 200) {
                                                isValid = false;
                                                weightEl.classList.add('is-invalid');
                                                weightEl.style.borderColor = '#dc3545';
                                                const wErr = document.getElementById('error-weight');
                                                if(wErr) {
                                                    wErr.textContent = 'Weight must be between 40 and 200 kg.';
                                                    wErr.style.visibility = 'visible';
                                                    attachClearListener(weightEl, wErr);
                                                }
                                            }
                                        }

                                        requiredIds.forEach(item => {
                                            const el = document.getElementById(item.id);
                                            const errEl = document.getElementById('error-' + item.id);

                                            if (el && el.offsetParent !== null && !el.value.trim()) {
                                                isValid = false;
                                                el.classList.add('is-invalid');
                                                el.style.borderColor = '#dc3545';
                                                if(errEl) {
                                                    errEl.textContent = item.msg;
                                                    errEl.style.visibility = 'visible';
                                                    attachClearListener(el, errEl);
                                                }
                                            }
                                        });

                                        if (!isValid) e.preventDefault();
                                    });
                                }
                            });
                        </script>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-3')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="3">
                        <div style="margin-bottom: 32px;">
                            <p style="font-size: 14px; color: #888; margin-bottom: 4px; text-align: left;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.measurements_info', [], 'en') }}</p>
                            <p style="font-size: 14px; color: #888; margin-bottom: 0; text-align: right; direction: rtl;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.measurements_info', [], 'ar') }}</p>
                        </div>

                        @php
                            $isFemale = strtolower($profile->gender ?? '') === 'female';
                            $gridColumns = $isFemale ? 'repeat(3, minmax(0,1fr))' : 'repeat(2, minmax(0,1fr))';
                        @endphp
                        <div class="field-grid" id="step3-field-grid" style="grid-template-columns: {{ $gridColumns }}; gap: 16px;">
                            <div class="field" style="display: flex; flex-direction: column;">
                                <label for="t_shirt_size">{{ \App\Helpers\Bilingual::get('onboarding.t_shirt_size') }} <span class="required-asterisk">*</span></label>
                                <div style="position:relative; margin-top: auto;">
                                    <select id="t_shirt_size" name="t_shirt_size" class="control" style="appearance:none;" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_size') }}</option>
                                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                            <option value="{{ $size }}" {{ old('t_shirt_size', $profile->t_shirt_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span class="error-text" id="error-t_shirt_size" style="color:#dc3545; font-size:12px; margin-top:4px;">T-shirt size is required</span>
                            </div>
                            @if($isFemale)
                            <div class="field" id="dress_size_field" style="display: flex; flex-direction: column;">
                                <label for="dress_size">{{ \App\Helpers\Bilingual::get('onboarding.dress_size') }} <span class="required-asterisk">*</span></label>
                                <div style="position:relative; margin-top: auto;">
                                    <select id="dress_size" name="dress_size" class="control" style="appearance:none;" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_size') }}</option>
                                        @foreach(['XXS', 'XS', 'S', 'M', 'L', 'XL', 'XXL', 'XXXL'] as $size)
                                            <option value="{{ $size }}" {{ old('dress_size', $profile->dress_size) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                                </div>
                                <span class="error-text" id="error-dress_size" style="color:#dc3545; font-size:12px; margin-top:4px;">Dress size is required</span>
                            </div>
                            @else
                            <input type="hidden" name="dress_size" value="">
                            @endif
                            <div class="field" style="display: flex; flex-direction: column;">
                                <label for="shoe_size">{{ \App\Helpers\Bilingual::get('onboarding.shoe_size') }} <span class="required-asterisk">*</span></label>
                                <input id="shoe_size" name="shoe_size" class="control" type="number" step="0.1" placeholder="e.g. 39" value="{{ old('shoe_size', $profile->shoe_size) }}" required style="margin-top: auto;">
                                <span class="error-text" id="error-shoe_size" style="color:#dc3545; font-size:12px; margin-top:4px;">Shoe size is required</span>
                            </div>

                        </div>

                        <div class="action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-2') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                {{ \App\Helpers\Bilingual::get('onboarding.back') }}
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.querySelector('form[action*="step-3"]');

                                // Restrict Shoe Size to 2 digits
                                const shoeInput = document.getElementById('shoe_size');
                                if(shoeInput) {
                                    shoeInput.addEventListener('input', function() {
                                        if(this.value.length > 2) this.value = this.value.slice(0, 2);
                                    });
                                }

                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        let isValid = true;
                                        
                                        // Check if dress_size field is visible (gender is female)
                                        const dressSizeField = document.getElementById('dress_size_field');
                                        const isDressSizeRequired = dressSizeField && dressSizeField.style.display !== 'none';
                                        
                                        const requiredIds = [
                                            { id: 't_shirt_size', msg: 'T-shirt size is required' },
                                            { id: 'shoe_size', msg: 'Shoe size is required' }
                                        ];
                                        
                                        // Only add dress_size validation if field is visible
                                        if (isDressSizeRequired) {
                                            requiredIds.push({ id: 'dress_size', msg: 'Dress size is required' });
                                        }

                                        requiredIds.forEach(item => {
                                            const el = document.getElementById(item.id);
                                            const errEl = document.getElementById('error-' + item.id);

                                            if (el && !el.value.trim()) {
                                                isValid = false;
                                                el.classList.add('is-invalid');
                                                el.style.borderColor = '#dc3545';
                                                if(errEl) errEl.style.visibility = 'visible';

                                                ['input', 'change'].forEach(evt => {
                                                    el.addEventListener(evt, function() {
                                                        this.classList.remove('is-invalid');
                                                        this.style.borderColor = '#e5e7eb';
                                                        if(errEl) errEl.style.visibility = 'hidden';
                                                    }, { once: true });
                                                });
                                            }
                                        });

                                        if (!isValid) e.preventDefault();
                                    });
                                }
                            });
                        </script>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-4')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="4">
                        <div class="info-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <div class="info-box-content">
                                <div>
                                    <div style="text-align: left;">{!! \Illuminate\Support\Facades\Lang::get('onboarding.id_document_info', [], 'en') !!}</div>
                                    <div style="text-align: right; direction: rtl;">{!! \Illuminate\Support\Facades\Lang::get('onboarding.id_document_info', [], 'ar') !!}</div>
                                </div>
                            </div>
                        </div>

                        <div id="id-documents-section">
                             @php
                                // Retrieve ID document from S3 bucket
                                $hasDoc = !empty($profile->id_document_front);
                                $docUrl = null;

                                if ($hasDoc) {
                                    // Check if it's already a full URL from S3/CloudFront (starts with http:// or https://)
                                    if (filter_var($profile->id_document_front, FILTER_VALIDATE_URL)) {
                                        // Direct S3/CloudFront URL
                                        $docUrl = $profile->id_document_front;
                                    } else {
                                        // Relative path - build the full S3 URL
                                        $docDisk = config('filesystems.cloud', 's3');
                                        $storageDisk = \Illuminate\Support\Facades\Storage::disk($docDisk);
                                        try {
                                            $docUrl = $storageDisk->url($profile->id_document_front);
                                        } catch (\Exception $e) {
                                            // Fallback for local storage
                                            $docUrl = asset('storage/' . $profile->id_document_front);
                                        }
                                    }
                                }
                             @endphp

                             @if($hasDoc)
                             <div class="field" style="margin-bottom: 16px;">
                                 <label style="margin-bottom: 8px; display: block;">{{ \App\Helpers\Bilingual::get('onboarding.current_id_document') }}</label>
                                 <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; margin-bottom: 12px;">
                                     <div style="position: relative; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; background: #f9fafb;">
                                         <img src="{{ $docUrl }}" alt="ID Document" style="width: 100%; height: 150px; object-fit: cover;">
                                         <div style="padding: 8px; background: #fff; text-align: center; font-size: 12px; color: #6b7280;">{{ \App\Helpers\Bilingual::get('onboarding.document') }}</div>
                                     </div>
                                 </div>
                                 <p style="font-size: 13px; color: #6b7280; margin: 0;">{{ \App\Helpers\Bilingual::get('onboarding.replace_document_info') }}</p>
                             </div>
                             @endif

                             <div class="field">
                                 <label>{{ $hasDoc ? \App\Helpers\Bilingual::get('onboarding.upload_new_id') : \App\Helpers\Bilingual::get('onboarding.id_document') }}</label>
                                 <label class="upload-card" for="upload_id_document_front" style="width:100%; margin:0;">
                                     <input id="upload_id_document_front" name="id_document_front" type="file" accept="image/*" style="display:none;">
                                     <div class="upload-inner">
                                         <div class="upload-icon">
                                             <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                         </div>
                                         <div class="upload-label" data-file-label="id_document_front">{{ \App\Helpers\Bilingual::get('onboarding.drop_file') }}</div>
                                         <div class="progress-bar-container" style="display:none; width: 100%; height: 4px; background: #e5e7eb; border-radius: 2px; margin-top: 8px; overflow: hidden;">
                                             <div class="progress-bar-fill" style="width: 0%; height: 100%; background: #10b981; transition: width 0.2s ease;"></div>
                                         </div>
                                     </div>
                                 </label>
                                 <input type="hidden" id="id_document_key" name="id_document_key" value="">
                                 @error('id_document_front')
                                     <span class="field-error">{{ $message }}</span>
                                 @enderror
                             </div>
                        </div>

                        <div class="action-group" id="step4-action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-3') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                {{ \App\Helpers\Bilingual::get('onboarding.back') }}
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                {{ \App\Helpers\Bilingual::get('onboarding.next') }}
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-5')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-5') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="5">
                        <div id="portfolio-photos-section" style="margin-top: 0;">
                            <div style="margin-bottom:12px;">
                                <label style="margin-bottom: 0;">{{ \App\Helpers\Bilingual::get('onboarding.add_photos') }}</label>
                            </div>
                            <div class="field">
                                <label class="upload-card" for="additional_photos_input" style="width:100%; margin:0;">
                                    <input type="file" id="additional_photos_input" name="additional_photos[]" multiple accept="image/*" style="display: none;">
                                    <div class="upload-inner">
                                        <div class="upload-icon">
                                            <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                        </div>
                                        <div class="upload-label">{{ \App\Helpers\Bilingual::get('onboarding.drop_multiple_photos') }}</div>
                                        <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">{{ \App\Helpers\Bilingual::get('onboarding.select_multiple_hint') }}</p>
                                    </div>
                                </label>
                            </div>
                            <div id="additional-photo-previews" class="photo-previews"></div>
                            <div id="step5-photo-keys" style="display:none;"></div>
                        </div>

                        <div id="video-upload-section" style="margin-top: 24px;">
                            <div style="margin-bottom:12px;">
                                <label>{{ \App\Helpers\Bilingual::get('onboarding.profile_video') }}</label>
                            </div>
                            <div class="field">
                                <label class="upload-card" for="upload_video" style="width:100%; margin:0;">
                                    <input id="upload_video" name="video" type="file" accept="video/*" style="display:none;">
                                    <div class="upload-inner">
                                        <div class="upload-icon">
                                            <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                        </div>
                                        <div class="upload-label" data-file-label="video">{{ \App\Helpers\Bilingual::get('onboarding.drop_file') }}</div>
                                        <div class="progress-bar-container" style="display:none; width: 100%; height: 4px; background: #e5e7eb; border-radius: 2px; margin-top: 8px; overflow: hidden;">
                                            <div class="progress-bar-fill" style="width: 0%; height: 100%; background: #10b981; transition: width 0.2s ease;"></div>
                                        </div>
                                    </div>
                                </label>
                                @error('video')
                                    <span class="field-error">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="action-group" id="step5-action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-4') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                {{ \App\Helpers\Bilingual::get('onboarding.back') }}
                            </a>
                            <button type="submit" class="btn-primary btn-submit" style="padding: 0 32px;">
                                {{ \App\Helpers\Bilingual::get('onboarding.submit_application') }}
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                <!-- Upload Progress Modal -->
                <div id="upload-progress-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); z-index: 10000; align-items: center; justify-content: center; flex-direction: column;">
                    <div style="background: #fff; border-radius: 12px; padding: 24px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                            <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #1f2937;">Uploading Images</h3>
                            <button type="button" id="upload-modal-close-btn" style="display: none; background: none; border: none; cursor: pointer; padding: 4px; color: #6b7280;" title="Close">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="6" x2="6" y2="18"></line>
                                    <line x1="6" y1="6" x2="18" y2="18"></line>
                                </svg>
                            </button>
            </div>
                        <div id="upload-progress-list" style="max-height: 400px; overflow-y: auto;">
                            <!-- Upload items will be inserted here -->
                        </div>
                        <div id="upload-modal-footer" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center;">
                            <p id="upload-modal-status" style="margin: 0; font-size: 14px; color: #6b7280;">Preparing uploads...</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        (function () {
            // Helper Functions
            function clearFieldError(field) {
                const fieldContainer = field.closest('.field');
                if (fieldContainer) {
                    fieldContainer.classList.remove('has-error');
                    fieldContainer.querySelector('.field-error')?.remove();
                }
            }

            document.addEventListener('input', e => { if (e.target.hasAttribute('required')) clearFieldError(e.target); });
            document.addEventListener('change', e => { if (e.target.hasAttribute('required')) clearFieldError(e.target); });

            // 1. WhatsApp Logic - Moved inline for reliability
            // See Step 1 HTML block above

            // 2. Nationality Flag Logic
            const natSelect = document.getElementById('nationality');
            const natFlag = document.getElementById('nationality_flag');

            // 2b. DOB: native date picker is used; max attribute prevents future dates.

            function toggleNationalityFlag() {
                if (natSelect && natFlag) {
                    const code = natSelect.value ? natSelect.value.toLowerCase() : '';
                    if (code && code !== 'unspecified') {
                        natFlag.className = `fi nationality-flag fi-${code}`;
                        natFlag.style.display = 'block';
                    } else {
                        natFlag.style.display = 'none';
                    }
                }
            }
            if (natSelect) {
                natSelect.addEventListener('change', toggleNationalityFlag);
                toggleNationalityFlag(); // Init
            }

            // 3. Gender/Hijab Logic
            function toggleGenderBasedFields() {
                const gender = document.getElementById('gender')?.value;
                const hijabSection = document.getElementById('hijab_preference_section');
                const hairColor = document.getElementById('hair_color_field');
                const hijabRadios = document.querySelectorAll('input[name="hijab_preference"]');

                if (hijabSection) {
                    if (gender === 'female') {
                        hijabSection.style.display = 'block';
                        hijabRadios.forEach(r => r.setAttribute('required', 'required'));

                        // Default to Non-Hijabi if nothing selected
                        const checkedHijab = document.querySelector('input[name="hijab_preference"]:checked');
                        if (!checkedHijab) {
                            const nonHijabRadio = document.querySelector('input[name="hijab_preference"][value="no_hijab"]');
                            if (nonHijabRadio) nonHijabRadio.checked = true;
                        }

                        const selectedHijab = document.querySelector('input[name="hijab_preference"]:checked')?.value;
                        if (hairColor) {
                            if (selectedHijab === 'wear_hijab') {
                                hairColor.style.display = 'none';
                                document.getElementById('hair_color')?.removeAttribute('required');
                            } else {
                                hairColor.style.display = 'block';
                            }
                        }
                    } else {
                        hijabSection.style.display = 'none';
                        hijabRadios.forEach(r => {
                            r.removeAttribute('required');
                            r.checked = false; // Clear selection
                        });
                        if (hairColor) hairColor.style.display = 'block';
                    }
                }
            }

            // Segmented Controls
            document.querySelectorAll('[data-seg-btn]').forEach(btn => {
                btn.addEventListener('click', () => {
                    const group = btn.closest('[data-segment]');
                    group.querySelectorAll('.seg-btn').forEach(b => b.classList.remove('is-active'));
                    btn.classList.add('is-active');
                    const target = document.querySelector(btn.dataset.targetInput);
                    if(target) {
                        target.value = btn.dataset.value;
                        if(target.id === 'gender') toggleGenderBasedFields();
                    }
                });
                // Init active state
                const target = document.querySelector(btn.dataset.targetInput);
                if(target && target.value === btn.dataset.value) btn.classList.add('is-active');
            });

            document.querySelectorAll('input[name="hijab_preference"]').forEach(r => r.addEventListener('change', toggleGenderBasedFields));
            setTimeout(toggleGenderBasedFields, 100);

            // 4. File Upload Features (Step 4 and Step 5)
            // Declare activeCompressions in global scope so form submission handlers can access it
            window.activeCompressions = window.activeCompressions || 0;
            let activeCompressions = window.activeCompressions;

            // Upload Progress Modal Management
            const uploadModal = document.getElementById('upload-progress-modal');
            const uploadProgressList = document.getElementById('upload-progress-list');
            const uploadModalStatus = document.getElementById('upload-modal-status');
            const uploadModalCloseBtn = document.getElementById('upload-modal-close-btn');
            const uploadModalTracker = {
                items: new Map(), // id -> {fileName, progress, status, error}
                addItem: function(id, fileName) {
                    this.items.set(id, { fileName, progress: 0, status: 'uploading', error: null });
                    this.render();
                },
                updateProgress: function(id, progress) {
                    const item = this.items.get(id);
                    if (item) {
                        item.progress = progress;
                        this.render();
                    }
                },
                markComplete: function(id) {
                    const item = this.items.get(id);
                    if (item) {
                        item.status = 'complete';
                        item.progress = 100;
                        this.render();
                    }
                    this.checkAllComplete();
                },
                markError: function(id, error) {
                    const item = this.items.get(id);
                    if (item) {
                        item.status = 'error';
                        item.error = error;
                        this.render();
                    }
                    this.checkAllComplete();
                },
                render: function() {
                    uploadProgressList.innerHTML = '';
                    let allComplete = true;
                    let hasError = false;

                    this.items.forEach((item, id) => {
                        const div = document.createElement('div');
                        div.style.cssText = 'padding: 12px; margin-bottom: 8px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;';

                        const fileName = document.createElement('div');
                        fileName.style.cssText = 'font-size: 14px; font-weight: 500; color: #1f2937; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;';
                        fileName.innerHTML = `
                            <span>${this.escapeHtml(item.fileName)}</span>
                            <span style="font-size: 12px; color: ${item.status === 'complete' ? '#10b981' : item.status === 'error' ? '#ef4444' : '#6b7280'};">
                                ${item.status === 'complete' ? '✓ Ready' : item.status === 'error' ? '✗ Failed' : 'Uploading...'}
                            </span>
                        `;
                        div.appendChild(fileName);

                        if (item.status === 'uploading') {
                            const progressBar = document.createElement('div');
                            progressBar.style.cssText = 'width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;';
                            const fill = document.createElement('div');
                            fill.style.cssText = `width: ${item.progress}%; height: 100%; background: #10b981; transition: width 0.3s ease;`;
                            progressBar.appendChild(fill);
                            div.appendChild(progressBar);
                            allComplete = false;
                        } else if (item.status === 'error') {
                            const errorContainer = document.createElement('div');
                            errorContainer.style.cssText = 'margin-top: 8px;';

                            const errorMsg = document.createElement('div');
                            errorMsg.style.cssText = 'font-size: 12px; color: #ef4444; margin-bottom: 4px;';
                            errorMsg.textContent = item.error || 'Upload failed';
                            errorContainer.appendChild(errorMsg);

                            // Add retry button for failed uploads
                            const retryBtn = document.createElement('button');
                            retryBtn.type = 'button';
                            retryBtn.textContent = 'Retry';
                            retryBtn.style.cssText = 'font-size: 12px; padding: 4px 12px; background: #10b981; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;';
                            retryBtn.onclick = () => {
                                // Find the item in step5PhotoItems or trigger Step 4 retry
                                if (id.startsWith('step4-id-doc-')) {
                                    // Step 4 retry - trigger file input change again
                                    const idInput = document.getElementById('upload_id_document_front');
                                    if (idInput && idInput.files.length > 0) {
                                        const event = new Event('change', { bubbles: true });
                                        idInput.dispatchEvent(event);
                                    }
                                } else {
                                    // Step 5 retry - find item and retry upload
                                    const step5Item = window.step5PhotoItems?.find(i => i.id === id);
                                    if (step5Item && window.uploadOnePhotoItem) {
                                        step5Item.status = 'queued';
                                        step5Item.error = null;
                                        window.uploadOnePhotoItem(step5Item);
                                    }
                                }
                            };
                            errorContainer.appendChild(retryBtn);
                            div.appendChild(errorContainer);
                            hasError = true;
                        }

                        uploadProgressList.appendChild(div);
                    });

                    if (allComplete && this.items.size > 0) {
                        if (hasError) {
                            uploadModalStatus.innerHTML = 'Some uploads failed. Click "Retry" on failed items or check S3 CORS configuration.<br><small style="color: #9ca3af; margin-top: 4px; display: block;">CORS must allow PUT requests from: ' + window.location.origin + '</small>';
                            uploadModalStatus.style.color = '#ef4444';
                        } else {
                            uploadModalStatus.textContent = 'All images uploaded successfully! Ready to submit.';
                            uploadModalStatus.style.color = '#10b981';
                        }
                        uploadModalCloseBtn.style.display = 'block';
                    } else if (this.items.size > 0) {
                        const uploading = Array.from(this.items.values()).filter(i => i.status === 'uploading').length;
                        uploadModalStatus.textContent = `Uploading ${uploading} image${uploading !== 1 ? 's' : ''}...`;
                        uploadModalStatus.style.color = '#6b7280';
                        uploadModalCloseBtn.style.display = 'none';
                    }
                },
                checkAllComplete: function() {
                    const allComplete = Array.from(this.items.values()).every(item =>
                        item.status === 'complete' || item.status === 'error'
                    );
                    if (allComplete && this.items.size > 0) {
                        // Auto-close after 2 seconds if all successful, or keep open if errors
                        const hasError = Array.from(this.items.values()).some(item => item.status === 'error');
                        if (!hasError) {
                            setTimeout(() => {
                                if (Array.from(this.items.values()).every(item => item.status === 'complete')) {
                                    this.hide();
                                }
                            }, 2000);
                        }
                    }
                },
                show: function() {
                    if (uploadModal) {
                        uploadModal.style.display = 'flex';
                    }
                },
                hide: function() {
                    if (uploadModal) {
                        uploadModal.style.display = 'none';
                    }
                },
                clear: function() {
                    this.items.clear();
                    this.render();
                },
                escapeHtml: function(text) {
                    const div = document.createElement('div');
                    div.textContent = text;
                    return div.innerHTML;
                }
            };

            // Close modal button
            if (uploadModalCloseBtn) {
                uploadModalCloseBtn.addEventListener('click', () => {
                    uploadModalTracker.hide();
                });
            }

            // Close modal when clicking outside (only if all uploads complete)
            if (uploadModal) {
                uploadModal.addEventListener('click', (e) => {
                    if (e.target === uploadModal) {
                        const allComplete = Array.from(uploadModalTracker.items.values()).every(item =>
                            item.status === 'complete' || item.status === 'error'
                        );
                        if (allComplete && uploadModalTracker.items.size > 0) {
                            uploadModalTracker.hide();
                        }
                    }
                });
            }

            function initFileUploadSteps() {
                 const steps = document.querySelectorAll('[data-step="4"], [data-step="5"]');
                 if(steps.length === 0) return;

                 // Use global variable
                 activeCompressions = window.activeCompressions || 0;

                  const updateSubmitButton = () => {
                      // Don't update button states during compression
                      // Both Step 4 and Step 5 buttons should remain enabled during compression
                      // Buttons are only disabled when user actually clicks submit
                      // This function is kept for potential future use but doesn't modify buttons during compression
                  };


                 // --- Image Compression Utility ---
                 const compressImage = (file, maxSizeMB = 10, quality = 0.75) => {
                     return new Promise((resolve, reject) => {
                         if (file.type.indexOf('image/') === -1) {
                             resolve(file); // Not an image, return as-is
                             return;
                         }

                         // Always compress if > maxSizeMB, otherwise compress for optimization
                         const shouldCompress = file.size > maxSizeMB * 1024 * 1024;
                         if (!shouldCompress && file.size <= 1 * 1024 * 1024) {
                             resolve(file); // Small file, no compression needed
                             return;
                         }

                         // Adjust quality based on file size
                         let compressionQuality = quality;
                         if (file.size > 20 * 1024 * 1024) {
                             compressionQuality = 0.65; // More aggressive for very large files
                         } else if (file.size > 10 * 1024 * 1024) {
                             compressionQuality = 0.7; // Moderate for large files
                         }

                         const reader = new FileReader();
                         reader.readAsDataURL(file);
                         reader.onload = event => {
                             const img = new Image();
                             img.src = event.target.result;
                             img.onload = () => {
                                 const canvas = document.createElement('canvas');
                                 const ctx = canvas.getContext('2d');

                                 // Simple scaling logic (maintain aspect ratio)
                                 let width = img.width;
                                 let height = img.height;

                                 // Reduce dimensions if extremely large - more aggressive for very large files
                                 let MAX_DIMENSION = 2048;
                                 if (file.size > 20 * 1024 * 1024) {
                                     MAX_DIMENSION = 1920; // More aggressive for very large files
                                 } else if (file.size > 10 * 1024 * 1024) {
                                     MAX_DIMENSION = 2048; // Standard for large files
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
                                         resolve(file); // Fallback to original
                                         return;
                                     }
                                     const newFile = new File([blob], file.name, {
                                         type: 'image/jpeg',
                                         lastModified: Date.now()
                                     });

                                     // Always use compressed version if file was > maxSizeMB
                                     // Otherwise use it if it's smaller
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


                 // File Labels & Drag-and-Drop
                 document.querySelectorAll('.upload-card').forEach(card => {
                     const input = card.querySelector('input[type="file"]');
                     const label = card.querySelector('.upload-label');

                     if (!input) return;

                     // FIX: Skip generic logic for Step 5 "Additional Photos" to prevent duplication
                     // It has its own dedicated handlers below
                     if (input.id === 'additional_photos_input') return;

                      const trimFileName = (name, maxLength = 25) => {
                          if (name.length <= maxLength) return name;
                          return name.substring(0, maxLength - 3) + '...';
                      };

                      // File Input Change
                      input.addEventListener('change', async () => {
                          if(input.files.length > 0) {
                              const file = input.files[0]; // Define file here

                              // Compression applied for single files (Step 4 / Video)
                              if (file.type.startsWith('image/')) {
                                 // STRICT VALIDATION: If this is Video Upload, reject images
                                 if (input.id === 'upload_video') {
                                     alert('Only video files are allowed here.');
                                     input.value = '';
                                     return;
                                 }

                                  try {
                                      window.activeCompressions = (window.activeCompressions || 0) + 1;
                                      activeCompressions = window.activeCompressions;
                                      // Don't call updateSubmitButton() - button should stay enabled during compression

                                      // Always compress if > 10MB, otherwise compress anyway to ensure quality
                                      const compressedFile = await compressImage(file, 10, 0.75);

                                      // Update input files with compressed version
                                      const dt = new DataTransfer();
                                      dt.items.add(compressedFile);
                                      input.files = dt.files;

                                      // Store compressed file reference for Step 4
                                      if (input.id === 'upload_id_document_front') {
                                          input.dataset.compressedFile = 'true';
                                      }

                                      console.log('Image compressed:', {
                                          original: file.name,
                                          originalSize: (file.size / 1024 / 1024).toFixed(2) + ' MB',
                                          compressedSize: (compressedFile.size / 1024 / 1024).toFixed(2) + ' MB'
                                      });
                                  } catch (e) {
                                      console.error("Compression failed", e);
                                      // Keep original file if compression fails
                                  } finally {
                                      window.activeCompressions = Math.max(0, (window.activeCompressions || 0) - 1);
                                      activeCompressions = window.activeCompressions;
                                      // Don't call updateSubmitButton() - button should stay enabled during compression
                                  }
                              }

                              /* 3. Handle Video Upload (Step 5) */
                             if (input.id === 'upload_video') {
                                 // STRICT VALIDATION: Ensure it is a video
                                 if (!input.files[0].type.startsWith('video/')) {
                                     alert('Only video files are allowed.');
                                     input.value = '';
                                     return;
                                 }

                                 const container = card.querySelector('.progress-bar-container');
                                  const fill = card.querySelector('.progress-bar-fill');
                                  const labelDiv = card.querySelector('.upload-label');

                                  if (container && fill) {
                                      container.style.display = 'block';
                                      fill.style.width = '0%';

                                      // Optional: Update label to indicate processing
                                      if(labelDiv) labelDiv.textContent = 'Compressing...';

                                      // Simulated compression/upload progress for video
                                      let progress = 0;
                                      const interval = setInterval(() => {
                                          progress += 5; // Slower for video
                                          fill.style.width = `${progress}%`;

                                          if (progress >= 100) {
                                              clearInterval(interval);
                                              // Show Success Message
                                              const successMsg = document.createElement('div');
                                              successMsg.className = 'upload-success-msg';
                                              successMsg.style.color = '#10b981';
                                              successMsg.style.fontSize = '12px';
                                              successMsg.style.marginTop = '4px';
                                              successMsg.style.fontWeight = '500';
                                              successMsg.textContent = 'Done, Now Click Next to Submit your Profile images and video.';

                                              // Remove old success message if exists
                                              const oldMsg = card.querySelector('.upload-success-msg');
                                              if(oldMsg) oldMsg.remove();

                                              card.querySelector('.upload-inner').appendChild(successMsg);

                                              // Restore file name in label
                                              if(labelDiv && input.files[0]) {
                                                  // slightly delayed to let user see "Compression" context if desired, or just show file name now
                                                  setTimeout(() => {
                                                       labelDiv.textContent = trimFileName(input.files[0].name);
                                                  }, 1000);
                                              }
                                          }
                                      }, 100);
                                  }
                              }

                              if(label) {
                                  if(input.files.length === 1) {
                                      label.textContent = trimFileName(input.files[0].name);
                                  } else {
                                      label.textContent = `${input.files.length} files selected`;
                                  }
                              }
                              card.style.borderColor = '#10b981';
                              card.style.background = '#f0fdf9';
                          }
                      });

                      // Step 4 ID document - compression is handled in the main change handler above
                      // This handler just shows progress UI
                      if (input.id === 'upload_id_document_front') {
                          // Remove duplicate event listener - compression is already handled above
                          // Just add progress UI update after compression completes
                          const originalChangeHandler = input.onchange;
                          input.addEventListener('change', async () => {
                              if (input.files.length > 0) {
                                  // STRICT VALIDATION: Ensure it is an image
                                  if (!input.files[0].type.startsWith('image/')) {
                                      alert('Only image files are allowed for ID.');
                                      input.value = '';
                                      return;
                                  }

                                 const container = card.querySelector('.progress-bar-container');
                                  const fill = card.querySelector('.progress-bar-fill');

                                  if (container && fill) {
                                      container.style.display = 'block';
                                      fill.style.width = '0%';

                                      // Clear validation error immediately
                                      clearFieldError(input);

                                      // Upload to S3 via presigned URL (single AJAX request)
                                      const idKeyInput = document.getElementById('id_document_key');
                                      if (idKeyInput) idKeyInput.value = '';
                                      window.step4IdUploadInFlight = (window.step4IdUploadInFlight || 0) + 1;

                                      // Clear previous uploads and show modal
                                      uploadModalTracker.clear();
                                      uploadModalTracker.show();

                                      const uploadId = 'step4-id-doc-' + Date.now();
                                      const fileName = input.files[0].name;

                                      const uploadWithProgress = (url, headers, file) => {
                                          return new Promise((resolve, reject) => {
                                              // Show modal and add item
                                              uploadModalTracker.show();
                                              uploadModalTracker.addItem(uploadId, fileName);

                                              const xhr = new XMLHttpRequest();
                                              xhr.open('PUT', url, true);
                                              if (headers && headers['Content-Type']) {
                                                  xhr.setRequestHeader('Content-Type', headers['Content-Type']);
                                              } else if (file.type) {
                                                  xhr.setRequestHeader('Content-Type', file.type);
                                              }
                                              xhr.upload.addEventListener('progress', (e) => {
                                                  if (e.lengthComputable) {
                                                      const pct = Math.round((e.loaded / e.total) * 100);
                                                      fill.style.width = `${pct}%`;
                                                      // Update modal progress
                                                      uploadModalTracker.updateProgress(uploadId, pct);
                                                  }
                                              });
                                              xhr.onload = () => {
                                                  if (xhr.status >= 200 && xhr.status < 300) {
                                                      uploadModalTracker.markComplete(uploadId);
                                                      resolve();
                                                  } else {
                                                      const errorMsg = `Upload failed (S3): ${xhr.status} ${xhr.statusText}`;
                                                      console.error('S3 upload error:', xhr.status, xhr.responseText);
                                                      uploadModalTracker.markError(uploadId, errorMsg);
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
                                                  uploadModalTracker.markError(uploadId, errorMsg);
                                                  reject(new Error(errorMsg));
                                              };
                                              xhr.onabort = () => {
                                                  uploadModalTracker.markError(uploadId, 'Upload cancelled.');
                                                  reject(new Error('Upload cancelled.'));
                                              };
                                              xhr.send(file);
                                          });
                                      };

                                      // Wait a beat for compression swap (main handler updates input.files)
                                      setTimeout(async () => {
                                          try {
                                              const fileToUpload = input.files[0];
                                              const presignRes = await fetch('{{ route("talent.onboarding.presign-id-document") }}', {
                                                  method: 'POST',
                                                  headers: {
                                                      'Content-Type': 'application/json',
                                                      'X-Requested-With': 'XMLHttpRequest',
                                                      'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                  },
                                                  credentials: 'same-origin',
                                                  body: JSON.stringify({
                                                      file_name: fileToUpload.name,
                                                      file_type: fileToUpload.type,
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
                                              await uploadWithProgress(presign.url, presign.headers, fileToUpload);

                                              if (idKeyInput) idKeyInput.value = presign.key;

                                              // Clear the file input so Step 4 submits only the key (no multipart upload)
                                              try {
                                                  const dt = new DataTransfer();
                                                  input.files = dt.files;
                                              } catch (e) {
                                                  input.value = '';
                                              }

                                              // Success message
                                                  const successMsg = document.createElement('div');
                                                  successMsg.className = 'upload-success-msg';
                                                  successMsg.style.color = '#10b981';
                                                  successMsg.style.fontSize = '12px';
                                                  successMsg.style.marginTop = '4px';
                                                  successMsg.style.fontWeight = '500';
                                                  successMsg.textContent = 'Done, Now Click Next to Submit the document.';

                                                  const oldMsg = card.querySelector('.upload-success-msg');
                                              if (oldMsg) oldMsg.remove();
                                                  card.querySelector('.upload-inner').appendChild(successMsg);
                                          } catch (err) {
                                              console.error('ID document upload failed', err);
                                              fill.style.width = '0%';
                                              alert(err && err.message ? err.message : 'ID document upload failed.');
                                          } finally {
                                              window.step4IdUploadInFlight = Math.max(0, (window.step4IdUploadInFlight || 0) - 1);
                                          }
                                      }, 300);
                                  }
                              }
                          });
                      }

                     // Drag Events
                     ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                         card.addEventListener(eventName, preventDefaults, false);
                     });

                     function preventDefaults(e) {
                         e.preventDefault();
                         e.stopPropagation();
                     }

                     card.addEventListener('drop', handleDrop, false);

                     function handleDrop(e) {
                         const dt = e.dataTransfer;
                         const files = dt.files;

                         if(files.length > 0) {
                             input.files = files;
                             // Trigger change event manually (this will trigger compression logic above)
                             const event = new Event('change');
                             input.dispatchEvent(event);
                         }
                     }
                  });

                  // Portfolio Photos Multi-Upload Logic (Step 5)
                  // Each photo is uploaded with an individual AJAX request directly to S3 (presigned PUT).
                  const multiInput = document.getElementById('additional_photos_input');
                  const multiUploadArea = multiInput ? multiInput.closest('.upload-card') : null;
                  const previewContainer = document.getElementById('additional-photo-previews');
                  const uploadedKeysContainer = document.getElementById('step5-photo-keys');

                  // Global-ish state for Step 5 photos.
                  window.step5PhotoItems = window.step5PhotoItems || [];
                  window.step5UploadsInFlight = window.step5UploadsInFlight || 0;

                  if (multiUploadArea && multiInput) {
                      multiInput.addEventListener('change', (e) => {
                          addFiles(e.target.files);
                          multiInput.value = ''; // Reset to allow re-selecting same files
                      });

                      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                          multiUploadArea.addEventListener(eventName, e => {
                              e.preventDefault();
                              e.stopPropagation();
                          }, false);
                      });

                      multiUploadArea.addEventListener('drop', e => {
                          const dt = e.dataTransfer;
                          addFiles(dt.files);
                      }, false);
                  }

                  function addFiles(files) {
                      // Remove any previous error message
                      const existingError = document.getElementById('step5-file-error');
                      if(existingError) existingError.remove();

                      // Process all files and compress if needed
                      const filePromises = Array.from(files).map(async (file) => {
                          // Allow only images for Step 5 Photos
                          if (file.type.indexOf('image/') === -1) {
                               return null; // Skip non-images
                          }

                          // Always compress images > 10MB, but also compress smaller ones for consistency
                          if (file.size > 10 * 1024 * 1024) {
                                window.activeCompressions = (window.activeCompressions || 0) + 1;
                                activeCompressions = window.activeCompressions;
                                // Don't call updateSubmitButton() for Step 5 - button should stay enabled during compression

                                try {
                                    const compressed = await compressImage(file, 10, 0.75);
                                    console.log('Photo compressed:', {
                                        original: file.name,
                                        originalSize: (file.size / 1024 / 1024).toFixed(2) + ' MB',
                                        compressedSize: (compressed.size / 1024 / 1024).toFixed(2) + ' MB'
                                    });
                                    return compressed;
                                } catch (err) {
                                    console.error('Compression failed for', file.name, err);
                                    return file; // Fallback to original
                                } finally {
                                    window.activeCompressions = Math.max(0, (window.activeCompressions || 0) - 1);
                                    activeCompressions = window.activeCompressions;
                                    // Don't call updateSubmitButton() for Step 5 - button should stay enabled during compression
                                }
                          } else {
                                // For smaller files, still compress to ensure consistent quality
                                if (file.size > 1 * 1024 * 1024) { // Compress files > 1MB for consistency
                                    window.activeCompressions = (window.activeCompressions || 0) + 1;
                                    activeCompressions = window.activeCompressions;
                                    // Don't call updateSubmitButton() for Step 5 - button should stay enabled during compression
                                    try {
                                        const compressed = await compressImage(file, 1, 0.8);
                                        return compressed;
                                    } catch (err) {
                                        return file;
                                    } finally {
                                        window.activeCompressions = Math.max(0, (window.activeCompressions || 0) - 1);
                                        activeCompressions = window.activeCompressions;
                                        // Don't call updateSubmitButton() for Step 5 - button should stay enabled during compression
                                    }
                                }
                                return file;
                          }
                      });

                      // Wait for all compressions to complete
                      Promise.all(filePromises).then(processedFiles => {
                          // Filter out nulls (non-images)
                          const validFiles = processedFiles.filter(f => f !== null);

                          // Add all processed files as items and start uploads
                          const newItems = validFiles.map(file => ({
                              id: `${Date.now()}_${Math.random().toString(16).slice(2)}`,
                              file,
                              progress: 0,
                              status: 'queued', // queued | uploading | done | error
                              key: null,
                              error: null,
                          }));

                          window.step5PhotoItems.push(...newItems);

                          // Keep the real <input type=file> empty to avoid multipart uploads;
                          // we submit only uploaded keys.
                          clearMultiInputFiles();

                          renderPreviews();
                          syncHiddenKeys();

                          // Clear modal and show it for new batch
                          uploadModalTracker.clear();
                          if (newItems.length > 0) {
                              uploadModalTracker.show();
                          }

                          // Kick off uploads (each photo = one request)
                          newItems.forEach(item => uploadOnePhotoItem(item));
                      });
                  }

                  function clearMultiInputFiles() {
                      if (!multiInput) return;
                      try {
                      const dt = new DataTransfer();
                      multiInput.files = dt.files;
                      } catch (e) {
                          // Some browsers may not support setting files; at least clear value.
                          multiInput.value = '';
                      }
                  }

                  function syncHiddenKeys() {
                      if (!uploadedKeysContainer) return;
                      uploadedKeysContainer.innerHTML = '';
                      window.step5PhotoItems
                          .filter(i => i.status === 'done' && i.key)
                          .forEach(i => {
                              const input = document.createElement('input');
                              input.type = 'hidden';
                              input.name = 'additional_photo_keys[]';
                              input.value = i.key;
                              uploadedKeysContainer.appendChild(input);
                          });
                  }

                  // Expose helpers for the Step 5 submit handler (defined outside this scope)
                  window.clearStep5MultiInputFiles = clearMultiInputFiles;
                  window.syncStep5HiddenKeys = syncHiddenKeys;
                  window.uploadOnePhotoItem = uploadOnePhotoItem; // Expose for retry functionality

                  async function presignPhoto(file) {
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
                          let msg = 'Could not prepare upload.';
                          try {
                              const data = await res.json();
                              msg = data.message || msg;
                          } catch (e) {}
                          throw new Error(msg);
                      }

                      return await res.json();
                  }

                  function uploadToS3Put(url, headers, file, onProgress) {
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
                  }

                  async function uploadOnePhotoItem(item) {
                      if (!item || !item.file) return;

                      // If already uploading/done, skip.
                      if (item.status === 'uploading' || item.status === 'done') return;

                      item.status = 'uploading';
                      item.progress = 0;
                      item.error = null;
                      window.step5UploadsInFlight = (window.step5UploadsInFlight || 0) + 1;
                      updatePreviewProgress(item.id, 0);

                      // Show modal and add item
                      uploadModalTracker.show();
                      uploadModalTracker.addItem(item.id, item.file.name);

                      try {
                          const presign = await presignPhoto(item.file);
                          await uploadToS3Put(presign.url, presign.headers, item.file, (pct) => {
                              item.progress = pct;
                              updatePreviewProgress(item.id, pct);
                              // Update modal progress
                              uploadModalTracker.updateProgress(item.id, pct);
                          });

                          item.key = presign.key;
                          item.status = 'done';
                          item.progress = 100;
                          updatePreviewProgress(item.id, 100);
                          uploadModalTracker.markComplete(item.id);
                          syncHiddenKeys();
                      } catch (e) {
                          item.status = 'error';
                          item.error = e && e.message ? e.message : 'Upload failed.';
                          updatePreviewError(item.id, item.error);
                          uploadModalTracker.markError(item.id, item.error);
                      } finally {
                          window.step5UploadsInFlight = Math.max(0, (window.step5UploadsInFlight || 0) - 1);
                      }
                  }

                  function renderPreviews() {
                      previewContainer.innerHTML = '';
                      if (window.step5PhotoItems.length > 0) {
                          const uploadLabel = multiUploadArea.querySelector('.upload-label');
                          if (uploadLabel) uploadLabel.textContent = `${window.step5PhotoItems.length} photos selected`;
                          window.step5PhotoItems.forEach((item) => {
                              const file = item.file;
                              const reader = new FileReader();
                              reader.onload = (e) => {
                                  const div = document.createElement('div');
                                  div.className = 'photo-preview-item';
                                  div.dataset.photoItemId = item.id;
                                  div.innerHTML = `
                                    <div class="preview-image-container" style="position: relative; width: 100px; height: 100px;">
                                        <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                        <div class="upload-progress-container" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: rgba(0,0,0,0.2); z-index: 10;">
                                            <div class="upload-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.5s ease; box-shadow: 0 0 2px rgba(0,0,0,0.5);"></div>
                                        </div>
                                    </div>`;
                                  previewContainer.appendChild(div);
                                  // Initialize progress based on current state
                                  updatePreviewProgress(item.id, item.progress || 0);
                                  if (item.status === 'error' && item.error) updatePreviewError(item.id, item.error);
                              };
                              reader.readAsDataURL(file);
                          });
                      } else {
                          const uploadLabel = multiUploadArea.querySelector('.upload-label');
                          if (uploadLabel) uploadLabel.textContent = 'Drop multiple photos here or click to browse';
                      }
                  }

                  function updatePreviewProgress(itemId, pct) {
                      const el = previewContainer.querySelector(`[data-photo-item-id="${itemId}"] .upload-progress-bar`);
                      if (el) el.style.width = `${pct}%`;
                  }

                  function updatePreviewError(itemId, message) {
                      const wrapper = previewContainer.querySelector(`[data-photo-item-id="${itemId}"]`);
                      if (!wrapper) return;
                      wrapper.style.opacity = '0.6';
                      wrapper.title = message || 'Upload failed.';
                  }
            }
            initFileUploadSteps();

            // --- VALIDATION LOGIC ---

            // Helper: Validate Pattern
            function restrictInput(input, pattern, removePattern) {
                input.addEventListener('input', function() {
                    if (removePattern) {
                        this.value = this.value.replace(removePattern, '');
                    }
                });
            }

            // 1. Weight & Height: Numbers only (prevent non-numeric input)
            const numberFields = ['height', 'weight', 'shoe_size'];
            numberFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    restrictInput(el, null, /[^0-9.]/g);
                }
            });

            // 3. Form Submission Validation - Specific Targeting
            // 3. Form Submission Validation - Specific Targeting
            // Removed legacy attachValidation() to prevent SweetAlerts.
            // Validation is now handled by inline scripts in each step.

            // 4. Submission Loading State (Step 4 & Step 5)
            const submissionForms = [
                { form: document.querySelector('form[action*="step-4"]'), btn: document.querySelector('#step4-action-group button[type="submit"]') },
                { form: document.querySelector('form[action*="step-5"]'), btn: document.querySelector('#step5-action-group button[type="submit"]') }
            ];

            submissionForms.forEach(({ form, btn }) => {
                if (form && btn) {
                    // Store original button content
                    const originalButtonHTML = btn.innerHTML;
                    const originalButtonText = btn.textContent.trim();

                    // Function to disable button and show "Submitting"
                    const disableButton = () => {
                        btn.setAttribute('disabled', 'disabled');
                        btn.style.opacity = '0.7';
                        btn.style.cursor = 'wait';
                        btn.innerHTML = 'Submitting... <svg class="animate-spin" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px; animation: spin 1s linear infinite;"><path d="M21 12a9 9 0 1 1-6.219-8.56" /></svg>';

                        // Add spin animation style if not present
                        if(!document.getElementById('spin-style')) {
                            const style = document.createElement('style');
                            style.id = 'spin-style';
                            style.innerHTML = `@keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } } .animate-spin { animation: spin 1s linear infinite; }`;
                            document.head.appendChild(style);
                        }
                    };

                    // Function to re-enable button
                    const enableButton = () => {
                        btn.removeAttribute('disabled');
                        btn.style.opacity = '1';
                        btn.style.cursor = 'pointer';
                        btn.innerHTML = originalButtonHTML;
                    };

                    form.addEventListener('submit', async function(e) {
                        // For Step 5, use regular form submission to preserve session flash
                        if (form.action.includes('step-5')) {
                            // Step 5 photos upload via AJAX directly to S3.
                            // We must wait for BOTH compressions and uploads to complete, then submit only the S3 keys.
                                e.preventDefault();
                                disableButton();

                            const startedAt = Date.now();
                            const timeoutMs = 120000; // 2 minutes

                            const waitAndSubmit = () => {
                                    const compressions = window.activeCompressions || 0;
                                const uploads = window.step5UploadsInFlight || 0;

                                if ((compressions > 0 || uploads > 0) && (Date.now() - startedAt) < timeoutMs) {
                                    setTimeout(waitAndSubmit, 150);
                                    return;
                                }

                                // If any photo failed, don't submit.
                                const items = window.step5PhotoItems || [];
                                const hasError = items.some(i => i && i.status === 'error');
                                if (hasError) {
                                    enableButton();
                                    alert('Some photos failed to upload. Please try selecting them again and submit.');
                                return;
                            }

                                // Build hidden inputs for uploaded keys and ensure the file input is empty (no multipart upload).
                                if (window.syncStep5HiddenKeys) window.syncStep5HiddenKeys();
                                if (window.clearStep5MultiInputFiles) window.clearStep5MultiInputFiles();

                                form.submit();
                            };

                            waitAndSubmit();
                            return;
                        }

                        // For Step 4, use fetch API
                        e.preventDefault(); // Always prevent default to handle submission manually

                        // Wait for any ongoing compressions before submission
                        const currentCompressions = window.activeCompressions || 0;
                        if (currentCompressions > 0) {
                            console.log('Waiting for compressions to complete...', currentCompressions);

                            // Disable button while waiting
                            disableButton();

                            // Wait for compressions to finish
                            const checkInterval = setInterval(() => {
                                const compressions = window.activeCompressions || 0;
                                if (compressions === 0) {
                                    clearInterval(checkInterval);
                                    console.log('All compressions complete, submitting form...');
                                    // Continue with form submission
                                    submitForm();
                                }
                            }, 100);

                            // Timeout after 30 seconds
                            setTimeout(() => {
                                const compressions = window.activeCompressions || 0;
                                if (compressions > 0) {
                                    clearInterval(checkInterval);
                                    console.warn('Compression timeout, submitting anyway...');
                                    submitForm();
                                }
                            }, 30000);

                            return;
                        }

                        // Start submission
                        submitForm();

                        async function submitForm() {
                            // Disable button immediately
                            disableButton();

                            // Step 5 is submitted via normal form submit (handled above).

                            // For Step 4, ensure compressed file is ready
                            if (form.action.includes('step-4')) {
                                const idInput = document.getElementById('upload_id_document_front');
                                const idKeyInput = document.getElementById('id_document_key');
                                const uploads = window.step4IdUploadInFlight || 0;

                                // If user picked a file but upload hasn't finished, wait.
                                if (uploads > 0) {
                                    enableButton();
                                    alert('Please wait for the ID document upload to finish.');
                                    return;
                                }

                                // If there is no existing doc and no uploaded key, block submission.
                                const hasExistingDoc = {{ !empty($profile->id_document_front) ? 'true' : 'false' }};
                                const hasUploadedKey = !!(idKeyInput && idKeyInput.value);

                                if (!hasExistingDoc && !hasUploadedKey) {
                                    enableButton();
                                    alert('Please upload your ID document before continuing.');
                                    return;
                                }

                                // Ensure we do not send the file via multipart (we submit only the key).
                                if (idInput && idInput.files && idInput.files.length > 0) {
                                    try {
                                        const dt = new DataTransfer();
                                        idInput.files = dt.files;
                                    } catch (e) {
                                        idInput.value = '';
                                    }
                                }
                            }

                            try {
                                // Create FormData from form
                                const formData = new FormData(form);

                                // Submit using fetch - let browser follow redirects automatically
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    body: formData,
                                    headers: {
                                        'X-Requested-With': 'XMLHttpRequest',
                                    },
                                    credentials: 'same-origin'
                                });

                                // Check if response was redirected (Laravel redirect on success)
                                // When fetch follows a redirect, response.redirected will be true
                                // or response.url will be different from form.action
                                if (response.redirected || (response.url && response.url !== form.action)) {
                                    // Success - follow the redirect
                                    window.location.href = response.url;
                                    return; // Don't re-enable button as we're redirecting
                                }

                                // Check if response is JSON (validation errors)
                                const contentType = response.headers.get('content-type') || '';
                                if (contentType.includes('application/json')) {
                                    const data = await response.json();

                                    if (data.errors || data.message) {
                                        // Validation errors - re-enable button
                                        enableButton();

                                        // Display errors
                                        console.error('Validation errors:', data.errors || data.message);
                                        const errorMsg = data.message || 'Please fix the errors and try again.';
                                        alert(errorMsg);
                                        return;
                                    }
                                }

                                // Check for error status codes
                                if (response.status >= 400) {
                                    enableButton();

                                    // Try to get error message
                                    try {
                                        const text = await response.text();
                                        try {
                                            const errorData = JSON.parse(text);
                                            alert(errorData.message || 'An error occurred. Please try again.');
                                        } catch {
                                            // Not JSON, might be HTML error page
                                            alert('An error occurred. Please try again.');
                                        }
                                    } catch {
                                        alert('An error occurred. Please try again.');
                                    }
                                    return;
                                }

                                // Success (200 status) - check if response contains redirect info
                                if (response.status === 200) {
                                    const text = await response.text();

                                    // Try to extract redirect URL from HTML response (Laravel might return HTML)
                                    const redirectMatch = text.match(/window\.location\s*=\s*['"]([^'"]+)['"]/) ||
                                                         text.match(/location\.href\s*=\s*['"]([^'"]+)['"]/) ||
                                                         text.match(/<meta[^>]*http-equiv=["']refresh["'][^>]*content=["'][^;]*url=([^"']+)/i) ||
                                                         text.match(/<script[^>]*>[\s\S]*?window\.location\s*=\s*['"]([^'"]+)['"]/i);

                                    if (redirectMatch && redirectMatch[1]) {
                                        window.location.href = redirectMatch[1];
                                        return; // Don't re-enable button as we're redirecting
                                    }

                                    // If no redirect found but status is 200, assume success
                                    // For Step 4, redirect to next step
                                    if (form.action.includes('step-4')) {
                                        window.location.href = '{{ route("talent.onboarding.show", "step-5") }}';
                                        return;
                                    }

                                    // Fallback: reload only if we can't determine redirect
                                    window.location.reload();
                                    return;
                                }

                                // Fallback: if we get here, something unexpected happened
                                enableButton();
                                console.warn('Unexpected response status:', response.status);

                            } catch (error) {
                                // Network error or other exception
                                console.error('Submission error:', error);
                                enableButton();
                                alert('Network error. Please check your connection and try again.');
                            }
                        }
                    });
                }
            });


            // Logout Logic - Robust
            const logoutLinks = document.querySelectorAll('#logout-trigger');
            logoutLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation(); // Stop bubbling
                    console.log('Logout clicked');

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
                        if (result.isConfirmed) {
                            const form = document.getElementById('logout-form');
                            if(form) form.submit();
                        }
                    });
                });
            });

            // Hover effect for logout icon
            const logoutTrigger = document.getElementById('logout-trigger');
            if (logoutTrigger) {
                logoutTrigger.addEventListener('mouseenter', () => logoutTrigger.style.opacity = '1');
                logoutTrigger.addEventListener('mouseleave', () => logoutTrigger.style.opacity = '0.8');
            }

        })();
    </script>
@endsection