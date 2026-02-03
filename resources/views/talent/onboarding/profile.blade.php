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

        .field-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
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
                    <span class="progress-bar {{ $currentStep == 'step-1' ? 'is-active' : ($profile->onboarding_steps_completed >= 1 ? 'is-complete' : '') }}" data-progress-index="0"></span>
                    <span class="progress-bar {{ $currentStep == 'step-2' ? 'is-active' : ($profile->onboarding_steps_completed >= 2 ? 'is-complete' : '') }}" data-progress-index="1"></span>
                    <span class="progress-bar {{ $currentStep == 'step-3' ? 'is-active' : ($profile->onboarding_steps_completed >= 3 ? 'is-complete' : '') }}" data-progress-index="2"></span>
                    <span class="progress-bar {{ $currentStep == 'step-4' ? 'is-active' : ($profile->onboarding_steps_completed >= 4 ? 'is-complete' : '') }}" data-progress-index="3"></span>
                    <span class="progress-bar {{ $currentStep == 'step-5' ? 'is-active' : ($profile->onboarding_steps_completed >= 5 ? 'is-complete' : '') }}" data-progress-index="4"></span>
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
                                <input id="first_name" name="first_name" class="control" type="text" placeholder="Enter first name" value="{{ old('first_name', $profile->first_name) }}" required>
                                <span class="error-text" id="error-first_name" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">First name is required</span>
                            </div>
                            <div class="field">
                                <label for="last_name">{{ \App\Helpers\Bilingual::get('onboarding.last_name') }} <span class="required-asterisk">*</span></label>
                                <input id="last_name" name="last_name" class="control" type="text" placeholder="Enter last name" value="{{ old('last_name', $profile->last_name) }}" required>
                                <span class="error-text" id="error-last_name" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Last name is required</span>
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
                                <span class="error-text" id="error-date_of_birth" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Date of birth is required</span>
                            </div>
                            <div class="field">
                                <label for="nationality">{{ \App\Helpers\Bilingual::get('onboarding.nationality') }} <span class="required-asterisk">*</span></label>
                                <div class="nationality-wrapper">
                                    <span id="nationality_flag" class="fi nationality-flag" style="display:none;"></span>
                                    <select id="nationality" name="nationality" class="control nationality-select" required>
                                        <option value="">{{ \App\Helpers\Bilingual::get('onboarding.select_nationality') }}</option>
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
                                            // Name inputs character restriction
                                            ['first_name', 'last_name'].forEach(id => {
                                                const el = document.getElementById(id);
                                                if(el) {
                                                    el.addEventListener('input', function() {
                                                        this.value = this.value.replace(/[^a-zA-Z\u0600-\u06FF\s]/g, '');
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
                                                        if(errEl) errEl.style.display = 'block';
                                                        
                                                        // Add input listener to clear error
                                                        el.addEventListener('input', function() {
                                                            this.classList.remove('is-invalid');
                                                            this.style.borderColor = '#e5e7eb';
                                                            if(errEl) errEl.style.display = 'none';
                                                        }, { once: true });
                                                        
                                                        // For select inputs (nationality)
                                                        el.addEventListener('change', function() {
                                                            this.classList.remove('is-invalid');
                                                            this.style.borderColor = '#e5e7eb';
                                                            if(errEl) errEl.style.display = 'none';
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
                                <span class="error-text" id="error-height" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Height is required</span>
                            </div>
                            <div class="field">
                                <label for="weight">{{ \App\Helpers\Bilingual::get('onboarding.weight') }} <span class="required-asterisk">*</span></label>
                                <input id="weight" name="weight" class="control" type="number" step="0.1" placeholder="e.g. 60" value="{{ old('weight', $profile->weight) }}" required>
                                <span class="error-text" id="error-weight" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Weight is required</span>
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
                                <span class="error-text" id="error-hair_color" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Hair color is required</span>
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
                                <span class="error-text" id="error-eye_color" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Eye color is required</span>
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
                                <span class="error-text" id="error-skin_tone" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Skin tone is required</span>
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
                                        const requiredIds = [
                                            { id: 'height', msg: 'Height is required' },
                                            { id: 'weight', msg: 'Weight is required' },
                                            { id: 'hair_color', msg: 'Hair color is required' },
                                            { id: 'eye_color', msg: 'Eye color is required' },
                                            { id: 'skin_tone', msg: 'Skin tone is required' }
                                        ];
                                        
                                        requiredIds.forEach(item => {
                                            const el = document.getElementById(item.id);
                                            const errEl = document.getElementById('error-' + item.id);
                                            
                                            if (el && el.offsetParent !== null && !el.value.trim()) {
                                                isValid = false;
                                                el.classList.add('is-invalid');
                                                el.style.borderColor = '#dc3545';
                                                if(errEl) errEl.style.display = 'block';
                                                
                                                ['input', 'change'].forEach(evt => {
                                                    el.addEventListener(evt, function() {
                                                        this.classList.remove('is-invalid');
                                                        this.style.borderColor = '#e5e7eb';
                                                        if(errEl) errEl.style.display = 'none';
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

                @if($currentStep == 'step-3')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="step-panel is-active" data-step="3">
                        <div style="margin-bottom: 32px;">
                            <p style="font-size: 14px; color: #888; margin-bottom: 4px; text-align: left;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.measurements_info', [], 'en') }}</p>
                            <p style="font-size: 14px; color: #888; margin-bottom: 0; text-align: right; direction: rtl;">{{ \Illuminate\Support\Facades\Lang::get('onboarding.measurements_info', [], 'ar') }}</p>
                        </div>

                        <div class="field-grid" style="grid-template-columns: repeat(3, minmax(0,1fr)); gap: 16px;">
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
                                <span class="error-text" id="error-t_shirt_size" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">T-shirt size is required</span>
                            </div>
                            <div class="field" style="display: flex; flex-direction: column;">
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
                                <span class="error-text" id="error-dress_size" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Dress size is required</span>
                            </div>
                            <div class="field" style="display: flex; flex-direction: column;">
                                <label for="shoe_size">{{ \App\Helpers\Bilingual::get('onboarding.shoe_size') }} <span class="required-asterisk">*</span></label>
                                <input id="shoe_size" name="shoe_size" class="control" type="number" step="0.1" placeholder="e.g. 39" value="{{ old('shoe_size', $profile->shoe_size) }}" required style="margin-top: auto;">
                                <span class="error-text" id="error-shoe_size" style="display:none; color:#dc3545; font-size:12px; margin-top:4px;">Shoe size is required</span>
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
                                if (form) {
                                    form.addEventListener('submit', function(e) {
                                        let isValid = true;
                                        const requiredIds = [
                                            { id: 't_shirt_size', msg: 'T-shirt size is required' },
                                            { id: 'dress_size', msg: 'Dress size is required' },
                                            { id: 'shoe_size', msg: 'Shoe size is required' }
                                        ];
                                        
                                        requiredIds.forEach(item => {
                                            const el = document.getElementById(item.id);
                                            const errEl = document.getElementById('error-' + item.id);
                                            
                                            if (el && !el.value.trim()) {
                                                isValid = false;
                                                el.classList.add('is-invalid');
                                                el.style.borderColor = '#dc3545';
                                                if(errEl) errEl.style.display = 'block';
                                                
                                                ['input', 'change'].forEach(evt => {
                                                    el.addEventListener(evt, function() {
                                                        this.classList.remove('is-invalid');
                                                        this.style.borderColor = '#e5e7eb';
                                                        if(errEl) errEl.style.display = 'none';
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
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data">
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
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-5') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="5">
                        <div id="portfolio-photos-section" style="margin-top: 0;">
                            <div style="margin-bottom:12px; display: flex; justify-content: space-between; align-items: center;">
                                <label style="margin-bottom: 0;">{{ \App\Helpers\Bilingual::get('onboarding.add_photos') }}</label>
                                <button type="button" id="camera-capture-btn" style="background: none; border: none; outline: none; box-shadow: none; cursor: pointer; padding: 4px; display: flex; align-items: center; justify-content: center;" title="Take photo with camera">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                        <circle cx="12" cy="13" r="4"></circle>
                                    </svg>
                                </button>
                                <input type="file" id="camera_capture_input" accept="image/*" capture="environment" style="display: none;">
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

                <!-- Camera Modal -->
                <div id="camera-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 9999; align-items: center; justify-content: center; flex-direction: column;">
                    <div style="position: relative; width: 90%; max-width: 640px; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                        <video id="camera-feed" autoplay playsinline style="width: 100%; height: auto; display: block; background: #000;"></video>
                        <canvas id="camera-canvas" style="display: none;"></canvas>

                        <div style="padding: 16px; background: #fff; display: flex; justify-content: center; gap: 16px;">
                            <button type="button" id="camera-cancel-btn" style="padding: 8px 16px; border-radius: 4px; border: 1px solid #d1d5db; background: #fff; cursor: pointer;">Cancel</button>
                            <button type="button" id="camera-shutter-btn" class="btn-primary" style="padding: 8px 24px; border-radius: 4px; border: none; background: #10b981; color: #fff; font-weight: 600; cursor: pointer;">Capture Photo</button>
                        </div>
                    </div>
                </div>
                @endif
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
                    if (code) {
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
            function initFileUploadSteps() {
                 const steps = document.querySelectorAll('[data-step="4"], [data-step="5"]');
                 if(steps.length === 0) return;
                 
                 let activeCompressions = 0;
                 const step5SubmitBtn = document.querySelector('#step5-action-group button[type="submit"]');

                 const updateSubmitButton = () => {
                     if(!step5SubmitBtn) return;
                     if(activeCompressions > 0) {
                         step5SubmitBtn.setAttribute('disabled', 'disabled');
                         step5SubmitBtn.style.opacity = '0.7';
                         step5SubmitBtn.style.cursor = 'not-allowed';
                         step5SubmitBtn.dataset.originalText = step5SubmitBtn.dataset.originalText || step5SubmitBtn.innerText;
                         step5SubmitBtn.innerText = 'Compressing...';
                     } else {
                         step5SubmitBtn.removeAttribute('disabled');
                         step5SubmitBtn.style.opacity = '1';
                         step5SubmitBtn.style.cursor = 'pointer';
                         if(step5SubmitBtn.dataset.originalText) {
                             step5SubmitBtn.innerText = step5SubmitBtn.dataset.originalText;
                         }
                     }
                 };

                 
                 // --- Image Compression Utility ---
                 const compressImage = (file, maxSizeMB = 10, quality = 0.7) => {
                     return new Promise((resolve, reject) => {
                         if (file.type.indexOf('image/') === -1 || file.size <= maxSizeMB * 1024 * 1024) {
                             resolve(file); // No compression needed
                             return;
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
                                 
                                 // Reduce dimensions if extremely large
                                 const MAX_DIMENSION = 2048; 
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
                                     
                                     // If compressed version is smaller, use it. Otherwise use original.
                                     if (newFile.size < file.size) {
                                         resolve(newFile);
                                     } else {
                                         resolve(file);
                                     }
                                 }, 'image/jpeg', quality);
                             };
                         };
                         reader.onerror = error => reject(error);
                     });
                 };


                 // File Labels & Drag-and-Drop
                 document.querySelectorAll('.upload-card').forEach(card => {
                     const input = card.querySelector('input[type="file"]');
                     const label = card.querySelector('.upload-label');

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
                                     const compressedFile = await compressImage(file);
                                      // Update input files with compressed version
                                      const dt = new DataTransfer();
                                      dt.items.add(compressedFile);
                                      input.files = dt.files;
                                  } catch (e) {
                                      console.error("Compression failed", e);
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
                                              successMsg.textContent = 'Successfully Compressed';
                                              
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

                      // Simulate upload progress for Step 4 ID document
                      if (input.id === 'upload_id_document_front') {
                          input.addEventListener('change', () => {
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

                                      // Simulated upload progress
                                      let progress = 0;
                                      const interval = setInterval(() => {
                                          progress += 10;
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
                                              successMsg.textContent = 'Successfully Uploaded';
                                              
                                              // Remove old success message if exists
                                              const oldMsg = card.querySelector('.upload-success-msg');
                                              if(oldMsg) oldMsg.remove();
                                              
                                              card.querySelector('.upload-inner').appendChild(successMsg);
                                          }
                                      }, 50);
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

                  // Portfolio Photos Multi-Upload Logic
                  const multiInput = document.getElementById('additional_photos_input');
                  const multiUploadArea = multiInput ? multiInput.closest('.upload-card') : null;
                  const previewContainer = document.getElementById('additional-photo-previews');

                  let selectedFiles = [];

                  // Camera Capture Logic
                  const cameraBtn = document.getElementById('camera-capture-btn');
                  const cameraInput = document.getElementById('camera_capture_input');
                  const cameraModal = document.getElementById('camera-modal');
                  const cameraVideo = document.getElementById('camera-feed');
                  const cameraCanvas = document.getElementById('camera-canvas');
                  const shutterBtn = document.getElementById('camera-shutter-btn');
                  const cancelBtn = document.getElementById('camera-cancel-btn');
                  let stream = null;

                  if (cameraBtn) {
                      cameraBtn.addEventListener('click', async () => {
                          const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

                          if (isMobile && cameraInput) {
                              cameraInput.click();
                          } else if (cameraModal && cameraVideo) {
                              try {
                                  stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user' } });
                                  cameraVideo.srcObject = stream;
                                  cameraModal.style.display = 'flex';
                              } catch (err) {
                                  console.error("Camera access denied or error:", err);
                                  if(cameraInput) cameraInput.click(); // Fallback
                              }
                          }
                      });
                  }

                  if (cameraInput) {
                      cameraInput.addEventListener('change', (e) => {
                          addFiles(e.target.files);
                          cameraInput.value = '';
                      });
                  }

                  // Webcam Modal Logic
                  if (shutterBtn && cameraVideo && cameraCanvas) {
                      shutterBtn.addEventListener('click', () => {
                          const context = cameraCanvas.getContext('2d');
                          cameraCanvas.width = cameraVideo.videoWidth;
                          cameraCanvas.height = cameraVideo.videoHeight;
                          context.drawImage(cameraVideo, 0, 0, cameraVideo.videoWidth, cameraVideo.videoHeight);

                          cameraCanvas.toBlob(blob => {
                              const file = new File([blob], "camera-capture.jpg", { type: "image/jpeg" });
                              addFiles([file]);
                              stopCamera();
                          }, 'image/jpeg');
                      });
                  }

                  if (cancelBtn) {
                      cancelBtn.addEventListener('click', stopCamera);
                  }

                  function stopCamera() {
                      if (stream) {
                          stream.getTracks().forEach(track => track.stop());
                      }
                      if (cameraModal) cameraModal.style.display = 'none';
                  }

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

                      Array.from(files).forEach(file => {
                          // Allow only images for Step 5 Photos
                          if (file.type.indexOf('image/') === -1) {
                               // Optional: specific error for non-images
                               return;
                          }

                          // Strict Validation for Step 5 Size
                          if (file.size > 10 * 1024 * 1024) {
                              // Show validation error
                              const errorDiv = document.createElement('div');
                              errorDiv.id = 'step5-file-error';
                              errorDiv.style.color = '#dc3545';
                              errorDiv.style.marginTop = '8px';
                              errorDiv.style.fontSize = '14px';
                              errorDiv.textContent = 'Image size is too large. Please upload an image up to 10MB.';
                              
                              if(multiUploadArea) {
                                  multiUploadArea.parentNode.insertBefore(errorDiv, multiUploadArea.nextSibling);
                              }
                              // Do NOT add file
                              return;
                          }

                          selectedFiles.push(file);
                      });
                      
                      syncInputFiles();
                      renderPreviews();
                  }

                  function syncInputFiles() {
                      if (!multiInput) return;
                      const dt = new DataTransfer();
                      selectedFiles.forEach(file => dt.items.add(file));
                      multiInput.files = dt.files;
                  }

                  function renderPreviews() {
                      previewContainer.innerHTML = '';
                      if (selectedFiles.length > 0) {
                          const uploadLabel = multiUploadArea.querySelector('.upload-label');
                          if (uploadLabel) uploadLabel.textContent = `${selectedFiles.length} photos selected`;
                          selectedFiles.forEach((file, index) => {
                              const reader = new FileReader();
                              reader.onload = (e) => {
                                  const div = document.createElement('div');
                                  div.className = 'photo-preview-item';
                                  div.innerHTML = `
                                    <div class="preview-image-container" style="position: relative; width: 100px; height: 100px;">
                                        <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover; border-radius: 4px;">
                                        <div class="upload-progress-container" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: rgba(0,0,0,0.2); z-index: 10;">
                                            <div class="upload-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.5s ease; box-shadow: 0 0 2px rgba(0,0,0,0.5);"></div>
                                        </div>
                                    </div>`;
                                  previewContainer.appendChild(div);
                                  
                                  // Simulate progress for newly added item
                                  // Use a slightly longer timeout to ensure DOM update
                                  setTimeout(() => {
                                      const progressBar = div.querySelector('.upload-progress-bar');
                                      if(progressBar) {
                                          progressBar.style.width = '100%';
                                      }
                                  }, 50);
                              };
                              reader.readAsDataURL(file);
                          });
                      } else {
                          const uploadLabel = multiUploadArea.querySelector('.upload-label');
                          if (uploadLabel) uploadLabel.textContent = 'Drop multiple photos here or click to browse';
                      }
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
