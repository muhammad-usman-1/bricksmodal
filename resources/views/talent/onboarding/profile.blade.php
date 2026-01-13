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
            color: #666666;
            margin-bottom: 12px;
        }

        .control {
            width: 100%;
            height: 48px;
            padding: 0 16px;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #ffffff;
            font-size: 15px;
            color: #1a1a1a;
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
            width: 140px;
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
            transition: all 0.2s ease;
            background: #f9fafc;
        }

        .upload-card:hover, .upload-card.drag-over {
            border-color: #8b5cf6;
            background: #f5f3ff;
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
    </style>
@endsection

@section('content')
    <div class="wizard-shell">
        <div class="wizard-card">
            <div class="wizard-hero">
                <!-- Logo removed -->
                <div class="hero-title" style="display: flex; justify-content: space-between; align-items: center;">
                    Complete Your Profile
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
                <div class="hero-sub">Step <span data-step-label>{{ match($currentStep) { 'step-1' => 1, 'step-2' => 2, 'step-3' => 3, 'step-4' => 4, default => 1 } }}</span> of 4</div>
                <div class="progress-track" aria-hidden="true">
                    <span class="progress-bar {{ $currentStep == 'step-1' ? 'is-active' : ($profile->onboarding_steps_completed >= 1 ? 'is-complete' : '') }}" data-progress-index="0"></span>
                    <span class="progress-bar {{ $currentStep == 'step-2' ? 'is-active' : ($profile->onboarding_steps_completed >= 2 ? 'is-complete' : '') }}" data-progress-index="1"></span>
                    <span class="progress-bar {{ $currentStep == 'step-3' ? 'is-active' : ($profile->onboarding_steps_completed >= 3 ? 'is-complete' : '') }}" data-progress-index="2"></span>
                    <span class="progress-bar {{ $currentStep == 'step-4' ? 'is-active' : ($profile->onboarding_steps_completed >= 4 ? 'is-complete' : '') }}" data-progress-index="3"></span>
                </div>
            </div>

            <div class="wizard-body">
                {{-- Removed top stacked error box; field-level errors only --}}

                @if($currentStep == 'step-1')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-1') }}" enctype="multipart/form-data">
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
                                    <input
                                        id="date_of_birth"
                                        name="date_of_birth"
                                        class="control dob-input"
                                        type="date"
                                        value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}"
                                        max="{{ \Carbon\Carbon::now()->subDay()->format('Y-m-d') }}"
                                        required
                                    >
                                </div>
                            </div>
                            <div class="field">
                                <label for="nationality">Nationality</label>
                                <div class="nationality-wrapper">
                                    <span id="nationality_flag" class="fi nationality-flag" style="display:none;"></span>
                                    <select id="nationality" name="nationality" class="control nationality-select" required>
                                        <option value="">Select nationality</option>
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $code }}" {{ old('nationality', $profile->nationality) == $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field">
                             <label for="mobile_number">Mobile Number</label>
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

                             <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px;">Do you have a WhatsApp number on the same number?</label>
                             <div class="radio-row" style="margin-top: 6px;">
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" id="wa_same" name="whatsapp_choice" value="same" {{ $whatsappChoice == 'same' ? 'checked' : '' }}> Yes
                                </label>
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" id="wa_alt" name="whatsapp_choice" value="alt" {{ $whatsappChoice == 'alt' ? 'checked' : '' }}> No
                                </label>
                             </div>
                             <div id="whatsapp_number_section" style="display:none; margin-top:12px;">
                                <label style="font-size: 12px; font-weight:600; margin-bottom:6px;">WhatsApp Number</label>
                                <div class="phone-row">
                                     <div class="country-code-display">
                                         <span class="fi fi-kw country-flag" title="Kuwait"></span>
                                         <span>+965</span>
                                     </div>
                                     <input type="hidden" name="whatsapp_country_code" value="kw">
                                     <input class="control" id="whatsapp_number_input" name="whatsapp_number" type="tel" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}">
                                </div>
                             </div>
                             <script>
                                (function() {
                                    try {
                                        var waSec = document.getElementById('whatsapp_number_section');
                                        var waInp = document.getElementById('whatsapp_number_input');

                                        function doToggle(forceVal) {
                                            var val = forceVal;
                                            if(!val) {
                                                var chk = document.querySelector('input[name="whatsapp_choice"]:checked');
                                                val = chk ? chk.value : 'same';
                                            }

                                            if(waSec) {
                                                waSec.style.display = (val === 'alt') ? 'block' : 'none';
                                                if(waInp) {
                                                    if(val === 'alt') waInp.setAttribute('required', 'required');
                                                    else waInp.removeAttribute('required');
                                                }
                                            }
                                        }

                                        // Bind events
                                        var radios = document.getElementsByName('whatsapp_choice');
                                        for(var i=0; i<radios.length; i++) {
                                            radios[i].addEventListener('change', function(e) { doToggle(this.value); });
                                        }

                                        // Init immediately
                                        doToggle();

                                    } catch(e) { console.error('WA Script Error:', e); }
                                })();
                             </script>
                        </div>

                        <div style="display: flex; justify-content: flex-end; margin-top: 32px;">
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                Next
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-2')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-2') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="2">
                        <!-- Redundant titles removed -->


                        <div class="field-grid">
                            <div class="field">
                                <label for="height">Height (cm)</label>
                                <input id="height" name="height" class="control" type="number" step="0.1" placeholder="e.g. 175" value="{{ old('height', $profile->height) }}" required>
                                <input type="hidden" id="height_unit" value="cm">
                            </div>
                            <div class="field">
                                <label for="weight">Weight (kg)</label>
                                <input id="weight" name="weight" class="control" type="number" step="0.1" placeholder="e.g. 60" value="{{ old('weight', $profile->weight) }}" required>
                            </div>
                        </div>

                        <div class="field" style="margin-top: 20px;">
                            <label>Select Your Gender</label>
                            <div class="segmented" data-segment>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="male">
                                    <img src="{{ asset('images/male.png') }}" alt="" class="male-icon">
                                    Male
                                </button>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="female">
                                    <img src="{{ asset('images/female.png') }}" alt="" class="female-icon">
                                    Female
                                </button>
                            </div>
                            <input type="hidden" name="gender" id="gender" value="{{ old('gender', $profile->gender ?? 'male') }}">
                        </div>

                        <div class="field" id="hijab_preference_section" style="margin-top: 24px; display:none;">
                            <label>Hijab Preference</label>
                            <p style="font-size: 13px; color: #666;">This helps us match you with appropriate casting calls</p>
                            <div class="hijab-group">
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="wear_hijab" {{ old('hijab_preference', $profile->hijab_preference ?? 'wear_hijab') == 'wear_hijab' ? 'checked' : '' }}>
                                    <span>Hijabi</span>
                                </label>
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="no_hijab" {{ old('hijab_preference', $profile->hijab_preference) == 'no_hijab' ? 'checked' : '' }}>
                                    <span>Non-Hijabi</span>
                                </label>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top:20px;">
                            <div class="field" id="hair_color_field">
                                <label for="hair_color">Hair Color</label>
                                <input id="hair_color" name="hair_color" class="control" type="text" placeholder="e.g. Brown" value="{{ old('hair_color', $profile->hair_color) }}">
                            </div>
                            <div class="field">
                                <label for="eye_color">Eye Color</label>
                                <input id="eye_color" name="eye_color" class="control" type="text" placeholder="e.g. Blue" value="{{ old('eye_color', $profile->eye_color) }}" required>
                            </div>
                        </div>

                        <div class="field" style="margin-top: 20px;">
                            <label for="skin_tone">Skin Tone</label>
                            <div style="position:relative;">
                                <select id="skin_tone" name="skin_tone" class="control" style="appearance:none;" required>
                                    <option value="">e.g. Fair, Medium, Olive, Dark</option>
                                    @foreach(['Fair','Light','Medium','Olive','Brown','Dark'] as $tone)
                                        <option value="{{$tone}}" {{ old('skin_tone', $profile->skin_tone) == $tone ? 'selected' : '' }}>{{$tone}}</option>
                                    @endforeach
                                </select>
                                <svg style="position:absolute; right:16px; top:50%; transform:translateY(-50%); color:#9ca3af; pointer-events:none;" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 9l6 6 6-6"/></svg>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 24px; gap: 16px;">
                            <div class="mini-card">
                                <h5>Do you have visible tattoos?</h5>
                                <div style="display: flex; gap: 24px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_visible_tattoos" value="0" {{ old('has_visible_tattoos', $profile->has_visible_tattoos ?? 0) == 0 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> No
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_visible_tattoos" value="1" {{ old('has_visible_tattoos', $profile->has_visible_tattoos) == 1 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> Yes
                                    </label>
                                </div>
                            </div>
                            <div class="mini-card">
                                <h5>Do you have piercings?</h5>
                                <div style="display: flex; gap: 24px;">
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_piercings" value="0" {{ old('has_piercings', $profile->has_piercings ?? 0) == 0 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> No
                                    </label>
                                    <label style="display: flex; align-items: center; gap: 8px; font-size: 14px; cursor: pointer; color: #4b5563;">
                                        <input type="radio" name="has_piercings" value="1" {{ old('has_piercings', $profile->has_piercings) == 1 ? 'checked' : '' }} style="accent-color: #1a1a1a; width: 18px; height: 18px;" required> Yes
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-1') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                Back
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                Next
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-3')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="3">
                        <p style="font-size: 14px; color: #888; margin-bottom: 32px;">Please provide accurate measurements to help us match you with fitting outfits.</p>

                        <div class="field-grid">
                            <div class="field">
                                <label for="chest">Chest / Bust (cm)</label>
                                <input id="chest" name="chest" class="control" type="number" step="0.1" placeholder="e.g. 90" value="{{ old('chest', $profile->chest) }}" required>
                            </div>
                            <div class="field">
                                <label for="waist">Waist (cm)</label>
                                <input id="waist" name="waist" class="control" type="number" step="0.1" placeholder="e.g. 70" value="{{ old('waist', $profile->waist) }}" required>
                            </div>
                        </div>
                        <div class="field-grid">
                            <div class="field">
                                <label for="hips">Hips (cm)</label>
                                <input id="hips" name="hips" class="control" type="number" step="0.1" placeholder="e.g. 95" value="{{ old('hips', $profile->hips) }}" required>
                            </div>
                            <div class="field">
                                <label for="shoe_size">Shoe Size (EU)</label>
                                <input id="shoe_size" name="shoe_size" class="control" type="number" step="0.1" placeholder="e.g. 39" value="{{ old('shoe_size', $profile->shoe_size) }}" required>
                            </div>
                        </div>

                        <div class="action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-2') }}" class="back-link">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                Back
                            </a>
                            <button type="submit" class="btn-primary" style="padding: 0 32px;">
                                Next
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-left: 4px;"><path d="M9 18l6-6-6-6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-4')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="4">
                        <div id="step-4-main-section">
                            <div class="info-box">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14 2 14 8 20 8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10 9 9 9 8 9"></polyline>
                            </svg>
                            <div class="info-box-content">
                                We need a copy of your Civil ID or Passport and your headshot to verify your identity.
                                <br>This information is kept strictly confidential.
                            </div>
                        </div>


                        <div id="id-documents-section">
                             <div class="field">
                                 <label for="civil_id_number">Civil ID Number</label>
                                 <input id="civil_id_number" name="civil_id_number" class="control" type="text" placeholder="e.g. 290010101234" value="{{ old('civil_id_number', $profile->civil_id_number) }}">
                                 @error('civil_id_number')
                                     <span class="field-error">{{ $message }}</span>
                                 @enderror
                             </div>
                                 <div class="upload-grid" style="margin-top:12px;">
                                     <div class="field">
                                         <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; display: block;">Front ID</label>
                                         <label class="upload-card" for="upload_front" style="width:100%; margin:0;">
                                             <input id="upload_front" name="id_front" type="file" accept="image/*" style="display:none;">
                                             <div class="upload-inner">
                                                 <div class="upload-icon">
                                                     <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                                 </div>
                                                 <div class="upload-label" data-file-label="front">Drop files here to upload</div>
                                             </div>
                                         </label>
                                         @error('id_front')
                                             <span class="field-error">{{ $message }}</span>
                                         @enderror
                                     </div>
                                     <div class="field">
                                         <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; display: block;">Back ID</label>
                                         <label class="upload-card" for="upload_back" style="width:100%; margin:0;">
                                             <input id="upload_back" name="id_back" type="file" accept="image/*" style="display:none;">
                                             <div class="upload-inner">
                                                 <div class="upload-icon">
                                                     <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                                 </div>
                                                 <div class="upload-label" data-file-label="back">Drop files here to upload</div>
                                             </div>
                                         </label>
                                         @error('id_back')
                                             <span class="field-error">{{ $message }}</span>
                                         @enderror
                                     </div>
                                 </div>
                        </div>

                        <div id="profile-photos-section" style="margin-top: 24px;">
                             <div style="margin-bottom:12px;">
                                 <strong>Profile Photos & Video</strong>
                             </div>
                              <div class="upload-grid grid-3">
                                 <div class="field">
                                     <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; display: block;">Headshot</label>
                                     <label class="upload-card" for="upload_headshot" style="width:100%; margin:0;">
                                         <input id="upload_headshot" name="headshot" type="file" accept="image/*" style="display:none;">
                                         <div class="upload-inner">
                                             <div class="upload-icon">
                                                 <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                             </div>
                                             <div class="upload-label" data-file-label="headshot">Drop files here to upload</div>
                                         </div>
                                     </label>
                                     @error('headshot')
                                         <span class="field-error">{{ $message }}</span>
                                     @enderror
                                 </div>
                                 <div class="field">
                                     <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; display: block;">Full Body</label>
                                     <label class="upload-card" for="upload_fullbody" style="width:100%; margin:0;">
                                         <input id="upload_fullbody" name="fullbody" type="file" accept="image/*" style="display:none;">
                                         <div class="upload-inner">
                                             <div class="upload-icon">
                                                 <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                             </div>
                                             <div class="upload-label" data-file-label="fullbody">Drop files here to upload</div>
                                         </div>
                                     </label>
                                     @error('fullbody')
                                         <span class="field-error">{{ $message }}</span>
                                     @enderror
                                 </div>
                                 <div class="field">
                                     <label style="font-size: 12px; font-weight: 600; color: #666; margin-bottom: 8px; display: block;">Profile Video (Optional)</label>
                                     <label class="upload-card" for="upload_video" style="width:100%; margin:0;">
                                         <input id="upload_video" name="video" type="file" accept="video/*" style="display:none;">
                                         <div class="upload-inner">
                                             <div class="upload-icon">
                                                 <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                             </div>
                                             <div class="upload-label" data-file-label="video">Drop files here to upload</div>
                                         </div>
                                     </label>
                                     @error('video')
                                         <span class="field-error">{{ $message }}</span>
                                     @enderror
                                 </div>
                             </div>
                        </div>
                        </div>

                        <div id="additional-photos-section" style="display: none;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                                <strong style="font-size: 16px;">Additional Portfolio Photos</strong>
                                <a id="back-to-main-step4" class="add-more-link" style="margin-bottom: 0; color: #6b7280;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                                    Back
                                </a>
                            </div>

                            <div id="multi-upload-area" class="additional-photos-area">
                                <input type="file" id="additional_photos_input" name="additional_photos[]" multiple accept="image/*" style="display: none;">
                                <div class="upload-inner">
                                    <div class="upload-icon">
                                        <img src="{{ asset('images/upload.png') }}" alt="Upload">
                                    </div>
                                    <div class="upload-label">Drop multiple photos here or click to browse</div>
                                    <p style="font-size: 12px; color: #9ca3af; margin-top: 4px;">You can select multiple files at once</p>
                                </div>
                            </div>

                            <div id="additional-photo-previews" class="photo-previews"></div>
                        </div>

                        <div class="action-group" id="step4-action-group">
                            <a href="{{ route('talent.onboarding.show', 'step-3') }}" class="back-link" id="step4-back-to-step3">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg>
                                Back
                            </a>
                            <div style="display: flex; align-items: center; gap: 24px;">
                                <a id="add-more-photos-btn" class="add-more-link" style="margin-bottom: 0;">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><line x1="5" y1="12" x2="19" y2="12"></line></svg>
                                    Add More Photos
                                </a>
                                <button type="submit" class="btn-primary btn-submit" style="padding: 0 32px;">
                                    Submit Application
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

            // 4. File Upload Features (Step 4)
            function initStep4() {
                 const step4 = document.querySelector('[data-step="4"]');
                 if(!step4) return;

                 // File Labels & Drag-and-Drop
                 document.querySelectorAll('.upload-card').forEach(card => {
                     const input = card.querySelector('input[type="file"]');
                     const label = card.querySelector('.upload-label');

                     if (!input) return;

                      const trimFileName = (name, maxLength = 25) => {
                          if (name.length <= maxLength) return name;
                          return name.substring(0, maxLength - 3) + '...';
                      };

                      // File Input Change
                      input.addEventListener('change', () => {
                          if(input.files[0]) {
                              if(label) label.textContent = trimFileName(input.files[0].name);
                              card.style.borderColor = '#10b981';
                              card.style.background = '#f0fdf9';
                          }
                      });

                     // Drag Events
                     ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                         card.addEventListener(eventName, preventDefaults, false);
                     });

                     function preventDefaults(e) {
                         e.preventDefault();
                         e.stopPropagation();
                     }

                     ['dragenter', 'dragover'].forEach(eventName => {
                         card.addEventListener(eventName, highlight, false);
                     });

                     ['dragleave', 'drop'].forEach(eventName => {
                         card.addEventListener(eventName, unhighlight, false);
                     });

                     function highlight(e) {
                         card.classList.add('drag-over');
                         card.style.borderColor = '#8b5cf6';
                         card.style.background = '#f5f3ff';
                     }

                     function unhighlight(e) {
                         card.classList.remove('drag-over');
                         if (!input.files[0]) { // Reset only if no file selected
                             card.style.borderColor = '#d1d5db';
                             card.style.background = '#f9fafc';
                         }
                     }

                     card.addEventListener('drop', handleDrop, false);

                     function handleDrop(e) {
                         const dt = e.dataTransfer;
                         const files = dt.files;

                         if(files.length > 0) {
                             input.files = files;
                             // Trigger change event manually
                             const event = new Event('change');
                             input.dispatchEvent(event);
                         }
                     }
                  });

                  // Additional Photos Logic
                  const addMoreBtn = document.getElementById('add-more-photos-btn');
                  const backToMainBtn = document.getElementById('back-to-main-step4');
                  const mainSection = document.getElementById('step-4-main-section');
                  const additionalSection = document.getElementById('additional-photos-section');
                  const backToStep3Btn = document.getElementById('step4-back-to-step3');
                  const step4ActionGroup = document.getElementById('step4-action-group');
                  const multiUploadArea = document.getElementById('multi-upload-area');
                  const multiInput = document.getElementById('additional_photos_input');
                  const previewContainer = document.getElementById('additional-photo-previews');

                  let selectedFiles = [];

                  if (addMoreBtn && additionalSection && mainSection) {
                      addMoreBtn.addEventListener('click', () => {
                          mainSection.style.display = 'none';
                          additionalSection.style.display = 'block';
                          addMoreBtn.style.display = 'none'; // Hide the "Add more" button
                          // Hide back-to-step-3 while inside "Add More Photos"
                          if (backToStep3Btn) backToStep3Btn.style.display = 'none';
                          // Keep submit aligned to the right when only one action is visible
                          if (step4ActionGroup) step4ActionGroup.classList.add('step4-right-only');
                      });
                  }

                  if (backToMainBtn && additionalSection && mainSection) {
                      backToMainBtn.addEventListener('click', () => {
                          additionalSection.style.display = 'none';
                          mainSection.style.display = 'block';
                          if(addMoreBtn) addMoreBtn.style.display = 'inline-flex'; // Show it back
                          // Show back-to-step-3 again when returning to main Step 4 sections
                          if (backToStep3Btn) backToStep3Btn.style.display = '';
                          if (step4ActionGroup) step4ActionGroup.classList.remove('step4-right-only');
                      });
                  }

                  if (multiUploadArea && multiInput) {
                      multiUploadArea.addEventListener('click', () => multiInput.click());

                      multiInput.addEventListener('change', (e) => {
                          addFiles(e.target.files);
                          // Clear the input so selecting the same file again triggers change
                          multiInput.value = '';
                      });

                      ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                          multiUploadArea.addEventListener(eventName, e => {
                              e.preventDefault();
                              e.stopPropagation();
                          }, false);
                      });

                      ['dragenter', 'dragover'].forEach(eventName => {
                          multiUploadArea.addEventListener(eventName, () => multiUploadArea.classList.add('drag-over'), false);
                      });

                      ['dragleave', 'drop'].forEach(eventName => {
                          multiUploadArea.addEventListener(eventName, () => multiUploadArea.classList.remove('drag-over'), false);
                      });

                      multiUploadArea.addEventListener('drop', e => {
                          const dt = e.dataTransfer;
                          addFiles(dt.files);
                      }, false);
                  }

                  function addFiles(files) {
                      Array.from(files).forEach(file => {
                          selectedFiles.push(file);
                      });
                      syncAndRender();
                  }

                  function syncAndRender() {
                      // 1. Sync with hidden input
                      const dt = new DataTransfer();
                      selectedFiles.forEach(file => dt.items.add(file));
                      multiInput.files = dt.files;

                      // 2. Render previews
                      previewContainer.innerHTML = '';
                      if (selectedFiles.length > 0) {
                          multiUploadArea.querySelector('.upload-label').textContent = `${selectedFiles.length} photos selected`;
                          selectedFiles.forEach((file, index) => {
                              const reader = new FileReader();
                              reader.onload = (e) => {
                                  const div = document.createElement('div');
                                  div.className = 'photo-preview-item';
                                  div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                                  // Optional: Add remove button here if needed in future
                                  previewContainer.appendChild(div);
                              };
                              reader.readAsDataURL(file);
                          });
                      } else {
                          multiUploadArea.querySelector('.upload-label').textContent = 'Drop multiple photos here or click to browse';
                      }
                  }
            }
            initStep4();

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
            const numberFields = ['height', 'weight', 'chest', 'waist', 'hips', 'shoe_size'];
            numberFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    restrictInput(el, null, /[^0-9.]/g);
                }
            });

            // 2. Hair & Eye Color: Text only (no numbers)
            const textFields = ['hair_color', 'eye_color'];
            textFields.forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    restrictInput(el, null, /[0-9]/g);
                }
            });

            // 3. Form Submission Validation - Specific Targeting
            function attachValidation() {
                // Step 2 Form
                const formStep2 = document.querySelector('form[action*="step-2"]');
                if (formStep2) {
                    formStep2.addEventListener('submit', function(e) {
                         let isValid = true;
                         let errorMsg = '';

                        // Validate Step 2
                        const height = document.getElementById('height')?.value;
                        const weight = document.getElementById('weight')?.value;
                        const hairColor = document.getElementById('hair_color');
                        const eyeColor = document.getElementById('eye_color')?.value;
                        const skinTone = document.getElementById('skin_tone')?.value;

                        // Check required text/number fields
                        if (!height || !weight || !eyeColor || !skinTone) {
                            isValid = false;
                            errorMsg = 'Please fill in all required fields (Height, Weight, Eye Color, Skin Tone).';
                        }

                        // Check hair color if visible
                        if (isValid && hairColor && hairColor.offsetParent !== null && !hairColor.value) {
                             isValid = false;
                             errorMsg = 'Please enter your Hair Color.';
                        }

                        // Validate specific formats again
                        if (isValid && (/[^0-9.]/.test(height) || /[^0-9.]/.test(weight))) {
                            isValid = false;
                             errorMsg = 'Height and Weight must be numbers.';
                        }

                        if (isValid && (/[0-9]/.test(eyeColor) || (hairColor && /[0-9]/.test(hairColor.value)))) {
                             isValid = false;
                             errorMsg = 'Hair Color and Eye Color must be text only (no numbers).';
                        }

                        // Check radios
                         if (isValid) {
                             const tattoos = document.querySelector('input[name="has_visible_tattoos"]:checked');
                             if (!tattoos) {
                                  isValid = false;
                                  errorMsg = 'Please select if you have visible tattoos.';
                             }
                         }

                         if (isValid) {
                             const piercings = document.querySelector('input[name="has_piercings"]:checked');
                             if (!piercings) {
                                  isValid = false;
                                  errorMsg = 'Please select if you have piercings.';
                             }
                         }

                         if (!isValid) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Missing Information',
                                text: errorMsg,
                                confirmButtonColor: '#1a1a1a'
                            });
                        }
                    });
                }

                // Step 3 Form
                const formStep3 = document.querySelector('form[action*="step-3"]');
                if(formStep3) {
                     formStep3.addEventListener('submit', function(e) {
                        const chest = document.getElementById('chest')?.value;
                        const waist = document.getElementById('waist')?.value;
                        const hips = document.getElementById('hips')?.value;
                        const shoeSize = document.getElementById('shoe_size')?.value;

                        if (!chest || !waist || !hips || !shoeSize) {
                            e.preventDefault();
                            Swal.fire({
                                icon: 'error',
                                title: 'Missing Information',
                                text: 'Please fill in all measurement fields.',
                                confirmButtonColor: '#1a1a1a'
                            });
                        }
                     });
                }
            }
            attachValidation();


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
                        confirmButtonText: 'Yes, log me out',
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
