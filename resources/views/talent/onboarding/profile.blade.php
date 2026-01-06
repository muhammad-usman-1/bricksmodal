@extends('layouts.app')

@section('styles')
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
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
            --primary: #0f0f0f;
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
            max-width: 580px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            position: relative;
            overflow: hidden;
            
        }

        .wizard-hero {
            background: #0f1524;
            padding: 24px 28px 28px;
            color: #fff;
            text-align: center;
        }

        .wizard-logo img {
            height: 24px;
            margin-bottom: 16px;
        }

        .hero-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .hero-sub {
            font-size: 13px;
            color: #9ca3af;
            margin-bottom: 24px;
        }

        .progress-track {
            display: flex;
            gap: 6px;
            justify-content: center;
        }

        .progress-bar {
            height: 4px;
            width: 24px;
            background: #2d3748;
            border-radius: 4px;
            transition: all 0.3s ease;
        }

        .progress-bar.is-active {
            background: #fff;
            width: 40px;
        }

        .progress-bar.is-complete {
            background: #10b981;
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
            font-size: 13px;
            font-weight: 600;
            color: var(--ink-700);
            margin-bottom: 8px;
        }

        .control {
            width: 100%;
            height: 42px;
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 0 14px;
            font-size: 14px;
            background: var(--control);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
            color: var(--ink-900);
        }

        .control:focus {
            outline: none;
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.1);
        }

        .dob-wrap, .nationality-wrapper {
            position: relative;
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
            border-radius: 10px;
            padding: 0 12px;
            height: 42px;
            font-size: 13px;
        }

        .phone-row {
            display: grid;
            grid-template-columns: 110px 1fr;
            gap: 10px;
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
            background: #0f1524;
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(15, 21, 36, 0.15);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 16px rgba(15, 21, 36, 0.2);
            background: #000000;
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
            background: #f3f4f6;
            padding: 4px;
            border-radius: 12px;
            display: flex;
        }

        .seg-btn {
            flex: 1;
            border: none;
            background: transparent;
            padding: 8px;
            border-radius: 8px;
            font-weight: 600;
            color: #6b7280;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            font-size: 13px;
        }

        .seg-btn.is-active {
            background: #fff;
            color: #111827;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .seg-icon img {
            width: 16px;
            height: 16px;
        }

        .hijab-group {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 8px;
        }

        .hijab-option {
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 12px;
            display: flex;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: all 0.15s ease;
        }

        .hijab-option:has(:checked) {
            border-color: #10b981;
            background: #f0fdf9;
            color: #065f46;
        }

        .hijab-option input {
            display: none;
        }

        .hijab-check {
            width: 20px;
            height: 20px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            background: #fff;
        }

        .hijab-option input:checked + .hijab-check {
            background: #10b981;
            border-color: #10b981;
        }

        .mini-card {
            background: #f8fafc;
            border: 1px solid #eef1f6;
            border-radius: 12px;
            padding: 14px;
            margin-bottom: 12px;
        }

        .mini-card h5 {
            margin: 0 0 10px;
            font-size: 13px;
            font-weight: 600;
        }

        .upload-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
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
            width: 42px;
            height: 42px;
            border-radius: 12px;
            background: #eef1f7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #6a7388;
        }

        .upload-label {
            font-weight: 600;
            color: var(--ink-700);
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

        .field-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .file-name {
             font-size: 11px; color:#555;
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
                <div class="wizard-logo">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="Bricks Studio">
                </div>
                <div class="hero-title">Complete Your Profile</div>
                <div class="hero-sub">Step <span data-step-label>{{ match($currentStep) { 'step-1' => 1, 'step-2' => 2, 'step-3' => 3, 'step-4' => 4, default => 1 } }}</span> of 4</div>
                <div class="progress-track" aria-hidden="true">
                    <span class="progress-bar {{ $currentStep == 'step-1' ? 'is-active' : ($profile->onboarding_steps_completed >= 1 ? 'is-complete' : '') }}" data-progress-index="0"></span>
                    <span class="progress-bar {{ $currentStep == 'step-2' ? 'is-active' : ($profile->onboarding_steps_completed >= 2 ? 'is-complete' : '') }}" data-progress-index="1"></span>
                    <span class="progress-bar {{ $currentStep == 'step-3' ? 'is-active' : ($profile->onboarding_steps_completed >= 3 ? 'is-complete' : '') }}" data-progress-index="2"></span>
                    <span class="progress-bar {{ $currentStep == 'step-4' ? 'is-active' : ($profile->onboarding_steps_completed >= 4 ? 'is-complete' : '') }}" data-progress-index="3"></span>
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

                @if($currentStep == 'step-1')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-1') }}" enctype="multipart/form-data">
                    @csrf
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
                                    <input id="date_of_birth" name="date_of_birth" class="control dob-input" type="date" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" required>
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
                                <select class="pill-select" name="country_code_display" disabled style="background-color: #e9ecef; cursor: not-allowed;">
                                    <option value="kw" {{ (old('country_code', $profile->country_code ?? auth('talent')->user()->phone_country_code ?? 'kw') == 'kw') ? 'selected' : '' }}>KW +965</option>
                                </select>
                                <input type="hidden" name="country_code" value="{{ old('country_code', $profile->country_code ?? auth('talent')->user()->phone_country_code ?? 'kw') }}">
                                <input class="control" id="mobile_number" name="mobile_number" type="tel" 
                                    value="{{ old('mobile_number', $profile->mobile_number ?? auth('talent')->user()->phone_number) }}" 
                                    readonly style="background-color: #e9ecef; cursor: not-allowed;" required>
                             </div>

                             <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px;">Do you have a WhatsApp number on the same number?</label>
                             <div class="radio-row" style="margin-top: 6px;">
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="same" {{ old('whatsapp_choice', 'same') == 'same' ? 'checked' : '' }}> Yes
                                </label>
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="alt" {{ old('whatsapp_choice') == 'alt' ? 'checked' : '' }}> No
                                </label>
                             </div>
                             <div id="whatsapp_number_section" style="display:none; margin-top:12px;">
                                <label style="font-size: 12px; font-weight:600; margin-bottom:6px;">WhatsApp Number</label>
                                <div class="phone-row">
                                     <select class="pill-select" name="whatsapp_country_code">
                                         <option value="kw" selected>KW +965</option>
                                     </select>
                                     <input class="control" id="whatsapp_number_input" name="whatsapp_number" type="tel" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}">
                                </div>
                             </div>
                        </div>

                        <div class="actions">
                            <button type="submit" class="btn-primary">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M13 6l6 6-6 6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-2')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-2') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="2">
                        <div class="step-title">Appearance</div>
                        <p class="step-sub">Step 2 of 4 • Measurements and appearance.</p>

                        <div class="field-grid">
                            <div class="field">
                                <label for="height">Height (cm)</label>
                                <input id="height" name="height" class="control" type="number" step="0.1" value="{{ old('height', $profile->height) }}">
                                <input type="hidden" id="height_unit" value="cm">
                            </div>
                            <div class="field">
                                <label for="weight">Weight (kg)</label>
                                <input id="weight" name="weight" class="control" type="number" step="0.1" value="{{ old('weight', $profile->weight) }}">
                            </div>
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            <label>Select Your Gender</label>
                            <div class="segmented" data-segment>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="male">Male</button>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="female">Female</button>
                            </div>
                            <input type="hidden" name="gender" id="gender" value="{{ old('gender', $profile->gender ?? 'male') }}">
                        </div>

                        <div class="field" id="hijab_preference_section" style="margin-top: 10px; display:none;">
                            <label>Hijab Preference</label>
                            <div class="hijab-group">
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="wear_hijab" {{ old('hijab_preference', $profile->hijab_preference) == 'wear_hijab' ? 'checked' : '' }}>
                                    <span class="hijab-check">✓</span> <span>Hijabi</span>
                                </label>
                                <label class="hijab-option">
                                    <input type="radio" name="hijab_preference" value="no_hijab" {{ old('hijab_preference', $profile->hijab_preference) == 'no_hijab' ? 'checked' : '' }}>
                                    <span class="hijab-check">✓</span> <span>Non-Hijabi</span>
                                </label>
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top:12px;">
                            <div class="field" id="hair_color_field">
                                <label for="hair_color">Hair Color</label>
                                <input id="hair_color" name="hair_color" class="control" type="text" value="{{ old('hair_color', $profile->hair_color) }}">
                            </div>
                            <div class="field">
                                <label for="eye_color">Eye Color</label>
                                <input id="eye_color" name="eye_color" class="control" type="text" value="{{ old('eye_color', $profile->eye_color) }}">
                            </div>
                        </div>

                        <div class="field">
                            <label for="skin_tone">Skin Tone</label>
                            <select id="skin_tone" name="skin_tone" class="control">
                                <option value="">Select skin tone</option>
                                @foreach(['Fair','Light','Medium','Olive','Brown','Dark'] as $tone)
                                    <option value="{{$tone}}" {{ old('skin_tone', $profile->skin_tone) == $tone ? 'selected' : '' }}>{{$tone}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="field-grid">
                            <div class="mini-card">
                                <h5>Visible Tattoos?</h5>
                                <div class="radio-row">
                                    <label><input type="radio" name="has_visible_tattoos" value="0" checked> No</label>
                                    <label><input type="radio" name="has_visible_tattoos" value="1"> Yes</label>
                                </div>
                            </div>
                            <div class="mini-card">
                                <h5>Piercings?</h5>
                                <div class="radio-row">
                                    <label><input type="radio" name="has_piercings" value="0" checked> No</label>
                                    <label><input type="radio" name="has_piercings" value="1"> Yes</label>
                                </div>
                            </div>
                        </div>

                        <div class="action-group" style="margin-top: 20px;">
                            <a href="{{ route('talent.onboarding.show', 'step-1') }}" class="btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg> Back
                            </a>
                            <button type="submit" class="btn-primary">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M13 6l6 6-6 6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-3')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-3') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="3">
                        <div class="step-title">Measurements</div>
                        <p class="step-sub">Step 3 of 4</p>

                        <div class="field-grid">
                            <div class="field">
                                <label for="chest">Chest / Bust (cm)</label>
                                <input id="chest" name="chest" class="control" type="number" step="0.1" value="{{ old('chest', $profile->chest) }}" required>
                            </div>
                            <div class="field">
                                <label for="waist">Waist (cm)</label>
                                <input id="waist" name="waist" class="control" type="number" step="0.1" value="{{ old('waist', $profile->waist) }}" required>
                            </div>
                        </div>
                        <div class="field-grid">
                            <div class="field">
                                <label for="hips">Hips (cm)</label>
                                <input id="hips" name="hips" class="control" type="number" step="0.1" value="{{ old('hips', $profile->hips) }}" required>
                            </div>
                            <div class="field">
                                <label for="shoe_size">Shoe Size (EU)</label>
                                <input id="shoe_size" name="shoe_size" class="control" type="number" step="0.1" value="{{ old('shoe_size', $profile->shoe_size) }}" required>
                            </div>
                        </div>

                        <div class="action-group" style="margin-top: 20px;">
                            <a href="{{ route('talent.onboarding.show', 'step-2') }}" class="btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg> Back
                            </a>
                            <button type="submit" class="btn-primary">
                                Next
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14" /><path d="M13 6l6 6-6 6" /></svg>
                            </button>
                        </div>
                    </div>
                </form>
                @endif

                @if($currentStep == 'step-4')
                <form method="POST" action="{{ route('talent.onboarding.store', 'step-4') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="step-panel is-active" data-step="4">
                        <div class="step-title">Identity & Media</div>
                        <p class="step-sub">Step 4 of 4</p>

                        <div id="id-documents-section">
                             <div class="field">
                                 <label for="civil_id_number">Civil ID Number</label>
                                 <input id="civil_id_number" name="civil_id_number" class="control" type="text" value="{{ old('civil_id_number', $profile->civil_id_number) }}">
                                 @error('civil_id_number') <span class="field-error">{{ $message }}</span> @enderror
                             </div>
                             <div class="upload-grid" style="margin-top:12px;">
                                 <label class="upload-card" for="upload_front">
                                     <input id="upload_front" name="id_front" type="file" accept="image/*" style="display:none;">
                                     <div class="upload-inner">
                                         <div class="upload-icon">ID</div>
                                         <div class="upload-label" data-file-label="front">Front ID</div>
                                     </div>
                                 </label>
                                 @error('id_front') <span class="field-error" style="text-align:center;">{{ $message }}</span> @enderror

                                 <label class="upload-card" for="upload_back">
                                     <input id="upload_back" name="id_back" type="file" accept="image/*" style="display:none;">
                                     <div class="upload-inner">
                                         <div class="upload-icon">ID</div>
                                         <div class="upload-label" data-file-label="back">Back ID</div>
                                     </div>
                                 </label>
                                 @error('id_back') <span class="field-error" style="text-align:center;">{{ $message }}</span> @enderror
                             </div>
                        </div>

                        <div id="profile-photos-section" style="margin-top: 24px;">
                             <div style="display:flex; justify-content:space-between; margin-bottom:12px;">
                                 <div><strong>Profile Photos</strong></div>
                                 <div id="upload-video-button-section">
                                      <button type="button" id="show-video-upload-btn" class="btn-primary" style="height:32px; font-size:12px;">Upload Video</button>
                                 </div>
                             </div>
                             <div class="upload-grid">
                                 <label class="upload-card" for="upload_headshot">
                                     <input id="upload_headshot" name="headshot" type="file" accept="image/*" style="display:none;">
                                     <div class="upload-inner">
                                         <div class="upload-icon">IMG</div>
                                         <div class="upload-label" data-file-label="headshot">Headshot</div>
                                     </div>
                                 </label>
                                 @error('headshot') <span class="field-error" style="text-align:center;">{{ $message }}</span> @enderror

                                 <label class="upload-card" for="upload_fullbody">
                                     <input id="upload_fullbody" name="fullbody" type="file" accept="image/*" style="display:none;">
                                     <div class="upload-inner">
                                         <div class="upload-icon">IMG</div>
                                         <div class="upload-label" data-file-label="fullbody">Full Body</div>
                                     </div>
                                 </label>
                                 @error('fullbody') <span class="field-error" style="text-align:center;">{{ $message }}</span> @enderror
                             </div>
                        </div>

                        <div id="video-section" style="display:none; margin-top:24px;">
                             <div style="display:flex; justify-content:space-between;">
                                 <h4>Profile Video</h4>
                                 <button type="button" id="back-to-images-btn" class="btn-secondary">Back to Images</button>
                             </div>
                             <label class="upload-card" for="upload_video" style="margin-top:12px;">
                                 <input id="upload_video" name="video" type="file" accept="video/*" style="display:none;">
                                 <div class="upload-inner">
                                     <div class="upload-icon">VID</div>
                                     <div class="upload-label" data-file-label="video">Upload Video</div>
                                 </div>
                             </label>
                             @error('video') <span class="field-error" style="text-align:center;">{{ $message }}</span> @enderror
                        </div>

                        <div class="action-group" style="margin-top: 20px;">
                            <a href="{{ route('talent.onboarding.show', 'step-3') }}" class="btn-secondary">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 18l-6-6 6-6" /></svg> Back
                            </a>
                            <button type="submit" class="btn-primary" style="background:#0b9f62;">
                                Submit Application
                            </button>
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

            // 1. WhatsApp Logic
            const whatsappRadios = document.querySelectorAll('input[name="whatsapp_choice"]');
            const whatsappNumberSection = document.getElementById('whatsapp_number_section');
            const whatsappInput = document.getElementById('whatsapp_number_input');

            function toggleWhatsappFields() {
                const choice = document.querySelector('input[name="whatsapp_choice"]:checked')?.value;
                if (whatsappNumberSection) {
                    if (choice === 'alt') {
                        whatsappNumberSection.style.display = 'block';
                        whatsappInput?.setAttribute('required', 'required');
                    } else {
                        whatsappNumberSection.style.display = 'none';
                        whatsappInput?.removeAttribute('required');
                    }
                }
            }
            whatsappRadios.forEach(r => r.addEventListener('change', toggleWhatsappFields));
            toggleWhatsappFields();

            // 2. Nationality Flag Logic
            const natSelect = document.getElementById('nationality');
            const natFlag = document.getElementById('nationality_flag');

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
                        hijabRadios.forEach(r => r.removeAttribute('required'));
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

                     // File Input Change
                     input.addEventListener('change', () => {
                         if(input.files[0]) {
                             if(label) label.textContent = input.files[0].name;
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

                 // Video Toggles
                 const showVid = document.getElementById('show-video-upload-btn');
                 const vidSec = document.getElementById('video-section');
                 const idSec = document.getElementById('id-documents-section');
                 const photoSec = document.getElementById('profile-photos-section');
                 const backImg = document.getElementById('back-to-images-btn');

                 if(showVid && vidSec) {
                     showVid.addEventListener('click', () => {
                         vidSec.style.display = 'block';
                         idSec.style.display = 'none';
                         photoSec.style.display = 'none';
                     });
                 }
                 if(backImg && vidSec) {
                     backImg.addEventListener('click', () => {
                         vidSec.style.display = 'none';
                         idSec.style.display = '';
                         photoSec.style.display = '';
                     });
                 }
            }
            initStep4();

        })();
    </script>
@endsection
