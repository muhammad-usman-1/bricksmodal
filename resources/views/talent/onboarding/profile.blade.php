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
             background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat;
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
            background: #e5e7eb;
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
            background: #e5e7eb;
        }

        .control::placeholder {
            color: #000000;
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
            background: #e5e7eb;
            color: #000000;
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
            height: 42px;
        }

        .weight-input {
            background: var(--control) !important;
            border: 1px solid var(--border) !important;
            color: var(--ink-900) !important;
            padding-right: 12px;
        }

        .weight-input:focus {
            background: #fff !important;
            color: var(--ink-900) !important;
            border-color: #cdd4e3 !important;
            box-shadow: 0 0 0 3px rgba(56, 115, 255, 0.12) !important;
        }

        .weight-input::placeholder {
            color: #9aa3b5 !important;
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
            background-color: #e5e7eb !important;
            color: #000000 !important;
            border: 1px solid var(--border) !important;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="%23000000" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 12 15 18 9" /></svg>');
            background-repeat: no-repeat;
            background-position: calc(100% - 12px) 50%;
            padding-right: 42px;
            padding-left: 50px;
            height: 42px;
            border-radius: 10px;
            position: relative;
            z-index: 1;
            /* Hide scrollbar */
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }

        select.control.nationality-select::-webkit-scrollbar {
            display: none; /* Chrome, Safari, Opera */
        }

        select.control.nationality-select:focus {
            border-color: #cdd4e3 !important;
            box-shadow: 0 0 0 3px rgba(56, 115, 255, 0.12) !important;
            background-color: #e5e7eb !important;
        }

        .nationality-wrapper {
            position: relative;
        }

        .nationality-flag {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            width: 28px;
            height: 18px;
            object-fit: cover;
            border-radius: 2px;
            pointer-events: none;
            z-index: 100;
            background: transparent;
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
            background: var(--primary) !important;
            color: #ffffff !important;
        }

        .btn-submit:hover,
        .btn-submit:focus,
        .btn-submit:active {
            background: #0b9f62 !important;
            color: #ffffff !important;
        }

        .btn-primary:focus,
        .btn-primary:active {
            background: var(--primary) !important;
            color: #ffffff !important;
            outline: none;
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
            display: inline-flex;
            align-items: center;
            justify-content: center;
        }

        .seg-btn .seg-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .seg-btn {
            background: #ffffff;
            border-color: var(--border);
        }

        .seg-btn.is-active {
            background: #0f0f0f;
            color: #ffffff;
            border-color: #0f0f0f;
        }

        /* Male icon (black) - when selected: make white */
        .seg-btn.is-active[data-value="male"] .seg-icon img {
            filter: brightness(0) invert(1);
        }

        /* Male icon (black) - when not selected: stays black (no filter) */
        .seg-btn:not(.is-active)[data-value="male"] .seg-icon img {
            filter: none;
        }

        /* Female icon (white) - when selected: stays white (no filter) */
        .seg-btn.is-active[data-value="female"] .seg-icon img {
            filter: none;
        }

        /* Female icon (white) - when not selected: make black */
        .seg-btn:not(.is-active)[data-value="female"] .seg-icon img {
            filter: brightness(0);
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
            height: 42px;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: var(--control);
            color: var(--ink-900);
            padding: 0 12px;
            font-size: 13px;
            outline: none;
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .measurement-input:focus {
            border-color: #cdd4e3;
            box-shadow: 0 0 0 3px rgba(56, 115, 255, 0.12);
            background: #fff;
        }

        .measurement-input::placeholder {
            color: #9aa3b5;
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

        .upload-card.drag-over {
            border-color: #0f0f0f;
            background: #f0f4ff;
            box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.1);
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

        .field-error {
            color: #dc3545;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .field.has-error .control,
        .field.has-error select.control {
            border-color: #dc3545;
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
                                <div class="nationality-wrapper">
                                    @php
                                        $selectedNationality = old('nationality', $profile->nationality);
                                        $flagDisplay = $selectedNationality ? 'block' : 'none';
                                        $flagSrc = $selectedNationality ? 'https://flagcdn.com/w160/' . strtolower($selectedNationality) . '.png' : '';
                                    @endphp
                                    <img id="nationality_flag" class="nationality-flag" src="{{ $flagSrc }}" alt="" style="display: {{ $flagDisplay }};">
                                    <select id="nationality" name="nationality" class="control nationality-select" required>
                                        <option value="" {{ $selectedNationality === null ? 'selected' : '' }}>Select nationality</option>
                                        @php
                                            $countries = [
                                                'af' => 'Afghanistan', 'al' => 'Albania', 'dz' => 'Algeria', 'as' => 'American Samoa', 'ad' => 'Andorra', 'ao' => 'Angola', 'ai' => 'Anguilla', 'aq' => 'Antarctica', 'ag' => 'Antigua and Barbuda', 'ar' => 'Argentina', 'am' => 'Armenia', 'aw' => 'Aruba', 'au' => 'Australia', 'at' => 'Austria', 'az' => 'Azerbaijan',
                                                'bs' => 'Bahamas', 'bh' => 'Bahrain', 'bd' => 'Bangladesh', 'bb' => 'Barbados', 'by' => 'Belarus', 'be' => 'Belgium', 'bz' => 'Belize', 'bj' => 'Benin', 'bm' => 'Bermuda', 'bt' => 'Bhutan', 'bo' => 'Bolivia', 'ba' => 'Bosnia and Herzegovina', 'bw' => 'Botswana', 'br' => 'Brazil', 'io' => 'British Indian Ocean Territory', 'bn' => 'Brunei', 'bg' => 'Bulgaria', 'bf' => 'Burkina Faso', 'bi' => 'Burundi',
                                                'cv' => 'Cabo Verde', 'kh' => 'Cambodia', 'cm' => 'Cameroon', 'ca' => 'Canada', 'ky' => 'Cayman Islands', 'cf' => 'Central African Republic', 'td' => 'Chad', 'cl' => 'Chile', 'cn' => 'China', 'cx' => 'Christmas Island', 'cc' => 'Cocos Islands', 'co' => 'Colombia', 'km' => 'Comoros', 'cg' => 'Congo', 'cd' => 'Congo (DRC)', 'ck' => 'Cook Islands', 'cr' => 'Costa Rica', 'ci' => 'Côte d\'Ivoire', 'hr' => 'Croatia', 'cu' => 'Cuba', 'cw' => 'Curaçao', 'cy' => 'Cyprus', 'cz' => 'Czech Republic',
                                                'dk' => 'Denmark', 'dj' => 'Djibouti', 'dm' => 'Dominica', 'do' => 'Dominican Republic',
                                                'ec' => 'Ecuador', 'eg' => 'Egypt', 'sv' => 'El Salvador', 'gq' => 'Equatorial Guinea', 'er' => 'Eritrea', 'ee' => 'Estonia', 'sz' => 'Eswatini', 'et' => 'Ethiopia',
                                                'fk' => 'Falkland Islands', 'fo' => 'Faroe Islands', 'fj' => 'Fiji', 'fi' => 'Finland', 'fr' => 'France', 'gf' => 'French Guiana', 'pf' => 'French Polynesia', 'tf' => 'French Southern Territories',
                                                'ga' => 'Gabon', 'gm' => 'Gambia', 'ge' => 'Georgia', 'de' => 'Germany', 'gh' => 'Ghana', 'gi' => 'Gibraltar', 'gr' => 'Greece', 'gl' => 'Greenland', 'gd' => 'Grenada', 'gp' => 'Guadeloupe', 'gu' => 'Guam', 'gt' => 'Guatemala', 'gg' => 'Guernsey', 'gn' => 'Guinea', 'gw' => 'Guinea-Bissau', 'gy' => 'Guyana',
                                                'ht' => 'Haiti', 'hm' => 'Heard Island', 'hn' => 'Honduras', 'hk' => 'Hong Kong', 'hu' => 'Hungary',
                                                'is' => 'Iceland', 'in' => 'India', 'id' => 'Indonesia', 'ir' => 'Iran', 'iq' => 'Iraq', 'ie' => 'Ireland', 'im' => 'Isle of Man', 'il' => 'Israel', 'it' => 'Italy',
                                                'jm' => 'Jamaica', 'jp' => 'Japan', 'je' => 'Jersey', 'jo' => 'Jordan',
                                                'kz' => 'Kazakhstan', 'ke' => 'Kenya', 'ki' => 'Kiribati', 'kp' => 'Korea (North)', 'kr' => 'Korea (South)', 'kw' => 'Kuwait', 'kg' => 'Kyrgyzstan',
                                                'la' => 'Laos', 'lv' => 'Latvia', 'lb' => 'Lebanon', 'ls' => 'Lesotho', 'lr' => 'Liberia', 'ly' => 'Libya', 'li' => 'Liechtenstein', 'lt' => 'Lithuania', 'lu' => 'Luxembourg',
                                                'mo' => 'Macao', 'mg' => 'Madagascar', 'mw' => 'Malawi', 'my' => 'Malaysia', 'mv' => 'Maldives', 'ml' => 'Mali', 'mt' => 'Malta', 'mh' => 'Marshall Islands', 'mq' => 'Martinique', 'mr' => 'Mauritania', 'mu' => 'Mauritius', 'yt' => 'Mayotte', 'mx' => 'Mexico', 'fm' => 'Micronesia', 'md' => 'Moldova', 'mc' => 'Monaco', 'mn' => 'Mongolia', 'me' => 'Montenegro', 'ms' => 'Montserrat', 'ma' => 'Morocco', 'mz' => 'Mozambique', 'mm' => 'Myanmar',
                                                'na' => 'Namibia', 'nr' => 'Nauru', 'np' => 'Nepal', 'nl' => 'Netherlands', 'nc' => 'New Caledonia', 'nz' => 'New Zealand', 'ni' => 'Nicaragua', 'ne' => 'Niger', 'ng' => 'Nigeria', 'nu' => 'Niue', 'nf' => 'Norfolk Island', 'mk' => 'North Macedonia', 'mp' => 'Northern Mariana Islands', 'no' => 'Norway',
                                                'om' => 'Oman',
                                                'pk' => 'Pakistan', 'pw' => 'Palau', 'ps' => 'Palestine', 'pa' => 'Panama', 'pg' => 'Papua New Guinea', 'py' => 'Paraguay', 'pe' => 'Peru', 'ph' => 'Philippines', 'pn' => 'Pitcairn', 'pl' => 'Poland', 'pt' => 'Portugal', 'pr' => 'Puerto Rico',
                                                'qa' => 'Qatar',
                                                're' => 'Réunion', 'ro' => 'Romania', 'ru' => 'Russia', 'rw' => 'Rwanda',
                                                'bl' => 'Saint Barthélemy', 'sh' => 'Saint Helena', 'kn' => 'Saint Kitts and Nevis', 'lc' => 'Saint Lucia', 'mf' => 'Saint Martin', 'pm' => 'Saint Pierre and Miquelon', 'vc' => 'Saint Vincent and the Grenadines', 'ws' => 'Samoa', 'sm' => 'San Marino', 'st' => 'São Tomé and Príncipe', 'sa' => 'Saudi Arabia', 'sn' => 'Senegal', 'rs' => 'Serbia', 'sc' => 'Seychelles', 'sl' => 'Sierra Leone', 'sg' => 'Singapore', 'sx' => 'Sint Maarten', 'sk' => 'Slovakia', 'si' => 'Slovenia', 'sb' => 'Solomon Islands', 'so' => 'Somalia', 'za' => 'South Africa', 'gs' => 'South Georgia', 'ss' => 'South Sudan', 'es' => 'Spain', 'lk' => 'Sri Lanka', 'sd' => 'Sudan', 'sr' => 'Suriname', 'sj' => 'Svalbard and Jan Mayen', 'se' => 'Sweden', 'ch' => 'Switzerland', 'sy' => 'Syria',
                                                'tw' => 'Taiwan', 'tj' => 'Tajikistan', 'tz' => 'Tanzania', 'th' => 'Thailand', 'tl' => 'Timor-Leste', 'tg' => 'Togo', 'tk' => 'Tokelau', 'to' => 'Tonga', 'tt' => 'Trinidad and Tobago', 'tn' => 'Tunisia', 'tr' => 'Turkey', 'tm' => 'Turkmenistan', 'tc' => 'Turks and Caicos Islands', 'tv' => 'Tuvalu',
                                                'ug' => 'Uganda', 'ua' => 'Ukraine', 'ae' => 'United Arab Emirates', 'gb' => 'United Kingdom', 'um' => 'United States Minor Outlying Islands', 'us' => 'United States', 'uy' => 'Uruguay', 'uz' => 'Uzbekistan',
                                                'vu' => 'Vanuatu', 've' => 'Venezuela', 'vn' => 'Vietnam', 'vg' => 'Virgin Islands (British)', 'vi' => 'Virgin Islands (U.S.)',
                                                'wf' => 'Wallis and Futuna', 'eh' => 'Western Sahara',
                                                'ye' => 'Yemen',
                                                'zm' => 'Zambia', 'zw' => 'Zimbabwe'
                                            ];
                                            $selectedNationality = old('nationality', $profile->nationality);
                                        @endphp
                                        @foreach($countries as $code => $name)
                                            <option value="{{ $code }}" {{ $selectedNationality === $code ? 'selected' : '' }}>{{ $name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            @php
                                $whatsappChoice = old(
                                    'whatsapp_choice',
                                    ($profile->whatsapp_number && $profile->whatsapp_number !== $profile->mobile_number) ? 'alt' : 'same'
                                );
                                $authTalent = auth('talent')->user();
                                $loginMobile = old('mobile_number', $profile->mobile_number ?? ($authTalent->phone_number ?? ''));
                                $loginCountry = old('country_code', $profile->country_code ?? ($authTalent->phone_country_code ?? 'kw'));
                                $whatsappDefault = old('whatsapp_number', $profile->whatsapp_number ?? $loginMobile);

                                // Format country code for display
                                $countryCodes = [
                                    'kw' => '+965',
                                    'ae' => '+971',
                                    'sa' => '+966',
                                    'bh' => '+973',
                                    'qa' => '+974',
                                    'om' => '+968'
                                ];
                                $displayCountryCode = $countryCodes[$loginCountry] ?? '+965';
                                $countryNames = [
                                    'kw' => 'KW',
                                    'ae' => 'AE',
                                    'sa' => 'SA',
                                    'bh' => 'BH',
                                    'qa' => 'QA',
                                    'om' => 'OM'
                                ];
                                $displayCountryName = $countryNames[$loginCountry] ?? 'KW';
                            @endphp
                                <input type="hidden" name="country_code" id="country_code" value="{{ $loginCountry }}">
                                <input type="hidden" id="mobile_number" name="mobile_number" value="{{ $loginMobile }}">

                            <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; display: block;">Do you have a WhatsApp number on the same number?</label>

                            <div class="radio-row" style="margin-top: 6px;">
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="same" {{ $whatsappChoice === 'alt' ? '' : 'checked' }}>
                                    Yes
                                </label>
                                <label style="display:flex;align-items:center;gap:4px;">
                                    <input type="radio" name="whatsapp_choice" value="alt" {{ $whatsappChoice === 'alt' ? 'checked' : '' }} id="whatsapp_no">
                                    No
                                </label>
                            </div>

                            <div style="margin-top: 12px;">
                                <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; display: block;">Mobile Number</label>
                                <div class="phone-row">
                                    <div class="pill-prefix" style="width: 110px; display: flex; align-items: center; justify-content: center; height: 42px; background: var(--control); border: 1px solid var(--border); border-radius: 10px; color: var(--ink-700); font-size: 13px;">
                                        {{ $displayCountryName }} {{ $displayCountryCode }}
                                    </div>
                                    <input class="control" type="text" value="{{ $loginMobile }}" readonly style="background: #e5e7eb; color: #000000; cursor: not-allowed; opacity: 0.7;">
                                </div>
                            </div>

                            <div id="whatsapp_number_section" style="display: {{ $whatsappChoice === 'alt' ? 'block' : 'none' }}; margin-top: 12px;">
                                <label style="font-size: 12px; font-weight: 600; color: var(--ink-700); margin-bottom: 6px; display: block;">WhatsApp Number</label>
                                <div class="phone-row">
                                    <select class="pill-select" name="whatsapp_country_code" id="whatsapp_country_code" aria-label="WhatsApp country code">
                                        <option value="kw" {{ $loginCountry === 'kw' ? 'selected' : '' }}>KW +965</option>
                                        <option value="ae" {{ $loginCountry === 'ae' ? 'selected' : '' }}>AE +971</option>
                                        <option value="sa" {{ $loginCountry === 'sa' ? 'selected' : '' }}>SA +966</option>
                                        <option value="bh" {{ $loginCountry === 'bh' ? 'selected' : '' }}>BH +973</option>
                                        <option value="qa" {{ $loginCountry === 'qa' ? 'selected' : '' }}>QA +974</option>
                                        <option value="om" {{ $loginCountry === 'om' ? 'selected' : '' }}>OM +968</option>
                                    </select>
                                    <input class="control" id="whatsapp_number_input" name="whatsapp_number" type="tel" placeholder="(555) 000-0000" aria-label="WhatsApp number" value="{{ $whatsappDefault }}" {{ $whatsappChoice === 'alt' ? 'required' : '' }}>
                                </div>
                            </div>
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
                                <label for="height">Height (cm)</label>
                                <input type="hidden" name="height_unit" value="cm">
                                <input id="height" name="height" class="control" type="number" step="0.1" min="0" placeholder="e.g. 175" value="{{ old('height', $profile->height) }}">
                            </div>
                            <div class="field">
                                <label for="weight">Weight(kg)</label>
                                <input id="weight" name="weight" class="control weight-input" type="number" step="0.1" min="0" placeholder="e.g. 60" value="{{ old('weight', $profile->weight) }}">
                            </div>
                        </div>

                        <div class="field" style="margin-top: 10px;">
                            <label>Select Your Gender</label>
                            <div class="segmented" data-segment>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="male">
                                    <span class="seg-icon">
                                        <img src="{{ asset('images/male.png') }}" alt="Male">
                                    </span> Male
                                </button>
                                <button type="button" class="seg-btn" data-seg-btn data-target-input="#gender" data-value="female">
                                    <span class="seg-icon">
                                        <img src="{{ asset('images/female.png') }}" alt="Female">
                                    </span> Female
                                </button>
                            </div>
                            <input type="hidden" name="gender" id="gender" value="{{ old('gender', $profile->gender ?? 'male') }}">
                        </div>

                        <div class="field" id="hijab_preference_section" style="margin-top: 10px;">
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
                            <div class="field" id="hair_color_field" style="display: flex;">
                                <label for="hair_color">Hair Color</label>
                                <input id="hair_color" name="hair_color" class="control" type="text" placeholder="e.g. Brown" value="{{ old('hair_color', $profile->hair_color) }}">
                            </div>
                            <div class="field" style="display: flex;">
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
                                <input id="chest" name="chest" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 90" value="{{ old('chest', $profile->chest) }}" required />
                            </div>
                            <div class="field">
                                <label for="waist_cm">Waist (cm)</label>
                                <input id="waist_cm" name="waist" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 70" value="{{ old('waist', $profile->waist) }}" required />
                            </div>
                        </div>

                        <div class="field-grid" style="margin-top: 14px;">
                            <div class="field">
                                <label for="hips_cm">Hips (cm)</label>
                                <input id="hips_cm" name="hips" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 95" value="{{ old('hips', $profile->hips) }}" required />
                            </div>
                            <div class="field">
                                <label for="shoe_size">Shoe Size (EU)</label>
                                <input id="shoe_size" name="shoe_size" class="measurement-input" type="number" step="0.1" min="0" placeholder="e.g. 39" value="{{ old('shoe_size', $profile->shoe_size) }}" required />
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

                        <div class="field" style="margin-top: 14px; margin-bottom: 14px;">
                            <label for="civil_id_number">Civil ID Number</label>
                            <input id="civil_id_number" name="civil_id_number" class="control" type="text" placeholder="Enter your Civil ID number" value="{{ old('civil_id_number', $profile->civil_id_number) }}" required>
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

                        <div style="margin-top: 24px; padding-top: 24px; border-top: 1px solid var(--border);">
                            <div class="step-title" style="font-size: 15px; margin-bottom: 8px;">Profile Photos</div>
                            <p class="step-sub" style="margin-bottom: 14px;">Upload your headshot and full-body photo for your profile.</p>

                            <div class="upload-grid">
                                <label class="upload-card" for="upload_headshot" style="display: flex; flex-direction: column; align-items: center; justify-content: center;">
                                    <input id="upload_headshot" name="headshot" type="file" accept="image/*" style="display:none;" {{ $profile->headshot_center_path ? '' : 'required' }}>
                                    <div class="upload-inner">
                                        <div class="upload-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                        </div>
                                        <div class="upload-label">Headshot (Centered)</div>
                                        <div data-file-label="headshot">Click to upload Headshot</div>
                                    </div>
                                </label>

                                <label class="upload-card" for="upload_fullbody">
                                    <input id="upload_fullbody" name="fullbody" type="file" accept="image/*" style="display:none;" {{ $profile->full_body_front_path ? '' : 'required' }}>
                                    <div class="upload-inner">
                                        <div class="upload-icon">
                                            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                                <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                                <polyline points="17 8 12 3 7 8"></polyline>
                                                <line x1="12" y1="3" x2="12" y2="15"></line>
                                            </svg>
                                        </div>
                                        <div class="upload-label">Full-Body Front Image</div>
                                        <div data-file-label="fullbody">Click to upload Full-Body Photo</div>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="action-group" style="margin-top: 10px;">
                            <button type="button" class="btn-secondary" data-prev>
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M15 18l-6-6 6-6" />
                                </svg>
                                Back
                            </button>
                            <button type="submit" class="btn-primary btn-submit" style="background:#0b9f62;">
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

                // Update gender-based fields when Step 2 is displayed
                if (current === 1) { // Step 2 (0-indexed)
                    setTimeout(() => {
                        toggleGenderBasedFields();
                    }, 50);
                }

                // Initialize drag and drop when Step 4 is displayed
                if (current === 3) { // Step 4 (0-indexed)
                    setTimeout(() => {
                        initializeDragAndDrop();
                    }, 50);
                }
            }

            // Clear errors for a field
            function clearFieldError(field) {
                const fieldContainer = field.closest('.field');
                if (fieldContainer) {
                    fieldContainer.classList.remove('has-error');
                    const errorMsg = fieldContainer.querySelector('.field-error');
                    if (errorMsg) {
                        errorMsg.remove();
                    }
                }
            }

            // Show error for a field
            function showFieldError(field, message) {
                const fieldContainer = field.closest('.field');
                if (!fieldContainer) return;

                fieldContainer.classList.add('has-error');

                // Remove existing error message if any
                const existingError = fieldContainer.querySelector('.field-error');
                if (existingError) {
                    existingError.remove();
                }

                // Add error message
                const errorMsg = document.createElement('span');
                errorMsg.className = 'field-error';
                errorMsg.textContent = message;

                // Insert after the input/select element
                const inputElement = fieldContainer.querySelector('.control, select, input[type="file"]');
                if (inputElement && inputElement.parentElement) {
                    inputElement.parentElement.insertBefore(errorMsg, inputElement.nextSibling);
                } else {
                    fieldContainer.appendChild(errorMsg);
                }
            }

            // Validation function for each step
            function validateStep(stepIndex) {
                const stepPanel = steps[stepIndex];
                if (!stepPanel) return true;

                // Clear all previous errors in this step
                stepPanel.querySelectorAll('.has-error').forEach(el => {
                    el.classList.remove('has-error');
                });
                stepPanel.querySelectorAll('.field-error').forEach(el => {
                    el.remove();
                });

                const requiredFields = stepPanel.querySelectorAll('[required]');
                let isValid = true;

                requiredFields.forEach(field => {
                    // Skip hidden fields
                    if (field.offsetParent === null && field.type !== 'hidden') {
                        return;
                    }

                    // Check if field is visible (not in a hidden parent)
                    let isVisible = true;
                    let parent = field.parentElement;
                    while (parent && parent !== stepPanel) {
                        if (parent.style.display === 'none' ||
                            window.getComputedStyle(parent).display === 'none') {
                            isVisible = false;
                            break;
                        }
                        parent = parent.parentElement;
                    }

                    if (!isVisible) return;

                    // Validate field
                    let fieldInvalid = false;
                    let errorMessage = '';

                    if (field.type === 'radio' || field.type === 'checkbox') {
                        const name = field.name;
                        const checked = stepPanel.querySelector(`[name="${name}"]:checked`);
                        if (!checked) {
                            fieldInvalid = true;
                            const label = field.closest('.field')?.querySelector('label')?.textContent?.trim() || field.name;
                            errorMessage = `${label} is required`;
                        }
                    } else if (field.type === 'file') {
                        if (!field.files || field.files.length === 0) {
                            fieldInvalid = true;
                            const label = field.closest('label')?.querySelector('.upload-label')?.textContent?.trim() ||
                                         field.closest('label')?.textContent?.trim() ||
                                         field.name;
                            errorMessage = `${label} is required`;
                        }
                    } else {
                        if (!field.value || field.value.trim() === '') {
                            fieldInvalid = true;
                            const label = field.closest('.field')?.querySelector('label')?.textContent?.trim() || field.name;
                            errorMessage = `${label} is required`;
                        }
                    }

                    if (fieldInvalid) {
                        isValid = false;
                        showFieldError(field, errorMessage);
                    } else {
                        clearFieldError(field);
                    }
                });

                // Scroll to first error if validation failed
                if (!isValid) {
                    const firstError = stepPanel.querySelector('.has-error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        const firstInput = firstError.querySelector('input, select, textarea');
                        if (firstInput) {
                            firstInput.focus();
                        }
                    }
                }

                return isValid;
            }

            // Clear errors when user starts typing/selecting
            document.addEventListener('input', function(e) {
                if (e.target.hasAttribute('required')) {
                    clearFieldError(e.target);
                }
            });

            document.addEventListener('change', function(e) {
                if (e.target.hasAttribute('required')) {
                    clearFieldError(e.target);
                }
            });

            function next() {
                // Validate current step before proceeding
                if (!validateStep(current)) {
                    return;
                }

                if (current < steps.length - 1) {
                    current += 1;
                    render();
                }
            }

            function prev() {
                // No validation needed when going back
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

            // Function to toggle hijab and hair color fields based on gender and hijab preference
            function toggleGenderBasedFields() {
                const genderInput = document.getElementById('gender');
                const hijabSection = document.getElementById('hijab_preference_section');
                const hairColorField = document.getElementById('hair_color_field');
                const hijabRadios = document.querySelectorAll('input[name="hijab_preference"]');

                if (!genderInput) return;

                const gender = genderInput.value;

                // Show/hide hijab preference based on gender
                if (hijabSection) {
                    if (gender === 'female') {
                        hijabSection.style.display = 'block';
                        // Make hijab preference required for female
                        hijabRadios.forEach(radio => {
                            radio.setAttribute('required', 'required');
                        });

                        // Check hijab preference for hair color visibility
                        const selectedHijab = document.querySelector('input[name="hijab_preference"]:checked');
                        if (hairColorField) {
                            if (selectedHijab && selectedHijab.value === 'wear_hijab') {
                                hairColorField.style.display = 'none';
                                const hairColorInput = document.getElementById('hair_color');
                                if (hairColorInput) {
                                    hairColorInput.removeAttribute('required');
                                }
                            } else {
                                hairColorField.style.display = 'flex';
                            }
                        }
                    } else {
                        // Male selected - hide hijab section
                        hijabSection.style.display = 'none';
                        // Show hair color for male
                        if (hairColorField) {
                            hairColorField.style.display = 'flex';
                        }
                        // Remove required from hijab preference
                        hijabRadios.forEach(radio => {
                            radio.removeAttribute('required');
                        });
                    }
                }
            }

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
                            // Trigger gender-based field toggling
                            if (target.id === 'gender') {
                                toggleGenderBasedFields();
                            }
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
                    // Initialize gender-based fields
                    if (targetInput.id === 'gender') {
                        toggleGenderBasedFields();
                    }
                }
            });

            // Listen for hijab preference changes
            document.querySelectorAll('input[name="hijab_preference"]').forEach(radio => {
                radio.addEventListener('change', toggleGenderBasedFields);
            });

            // WhatsApp alt toggle and syncing
            const whatsappRadios = document.querySelectorAll('input[name="whatsapp_choice"]');
            const whatsappNumberSection = document.getElementById('whatsapp_number_section');
            const whatsappInput = document.getElementById('whatsapp_number_input');

            function toggleWhatsappFields() {
                const choice = document.querySelector('input[name="whatsapp_choice"]:checked')?.value;

                if (choice === 'alt') {
                    // When "No" is selected: Show WhatsApp number
                    if (whatsappNumberSection) {
                        whatsappNumberSection.style.display = 'block';
                        if (whatsappInput) {
                            whatsappInput.setAttribute('required', 'required');
                        }
                    }
                } else {
                    // When "Yes" is selected: Hide WhatsApp number
                    if (whatsappNumberSection) {
                        whatsappNumberSection.style.display = 'none';
                        if (whatsappInput) {
                            whatsappInput.removeAttribute('required');
                        }
                    }
                }
            }

            whatsappRadios.forEach(radio => {
                radio.addEventListener('change', toggleWhatsappFields);
            });

            toggleWhatsappFields(); // Initialize on page load

            // Nationality flag display
            const nationalitySelect = document.getElementById('nationality');
            const nationalityFlag = document.getElementById('nationality_flag');

            function updateNationalityFlag() {
                if (!nationalitySelect || !nationalityFlag) return;

                const selectedValue = nationalitySelect.value;
                if (selectedValue && selectedValue !== '') {
                    const flagUrl = `https://flagcdn.com/w160/${selectedValue.toLowerCase()}.png`;
                    nationalityFlag.src = flagUrl;
                    nationalityFlag.alt = nationalitySelect.options[nationalitySelect.selectedIndex].text;
                    nationalityFlag.style.display = 'block';
                    nationalityFlag.style.visibility = 'visible';
                    nationalityFlag.style.opacity = '1';

                    // Reset onerror handler
                    nationalityFlag.onerror = function() {
                        // Try alternative flag source if first fails
                        this.src = `https://flagcdn.com/w160/${selectedValue.toLowerCase()}.png`;
                            this.onerror = function() {
                                this.style.display = 'none';
                        };
                    };
                } else {
                    nationalityFlag.style.display = 'none';
                }
            }

            if (nationalitySelect && nationalityFlag) {
                nationalitySelect.addEventListener('change', updateNationalityFlag);
                // Initialize on page load - use setTimeout to ensure DOM is ready
                setTimeout(() => {
                    updateNationalityFlag();
                }, 100);

                // Also update immediately if there's already a selected value
                if (nationalitySelect.value) {
                    updateNationalityFlag();
                }
            }

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

            // file label update for profile photo uploads
            const headshotInput = document.getElementById('upload_headshot');
            const fullbodyInput = document.getElementById('upload_fullbody');
            const headshotLabel = document.querySelector('[data-file-label="headshot"]');
            const fullbodyLabel = document.querySelector('[data-file-label="fullbody"]');

            function updateLabel(input, label) {
                if (!input || !label) return;
                    const fileName = input.files && input.files[0] ? input.files[0].name : '';
                    label.textContent = fileName || label.dataset.defaultText || label.textContent;
            }

            if (frontLabel) frontLabel.dataset.defaultText = frontLabel.textContent;
            if (backLabel) backLabel.dataset.defaultText = backLabel.textContent;
            if (headshotLabel) headshotLabel.dataset.defaultText = headshotLabel.textContent;
            if (fullbodyLabel) fullbodyLabel.dataset.defaultText = fullbodyLabel.textContent;

            // Update labels on file change
            if (frontInput) {
                frontInput.addEventListener('change', () => updateLabel(frontInput, frontLabel));
            }
            if (backInput) {
                backInput.addEventListener('change', () => updateLabel(backInput, backLabel));
            }
            if (headshotInput) {
                headshotInput.addEventListener('change', () => updateLabel(headshotInput, headshotLabel));
            }
            if (fullbodyInput) {
                fullbodyInput.addEventListener('change', () => updateLabel(fullbodyInput, fullbodyLabel));
            }

            // Drag and drop functionality
            function setupDragAndDrop(uploadCard, fileInput) {
                if (!uploadCard || !fileInput) return;

                function preventDefaults(e) {
                    e.preventDefault();
                    e.stopPropagation();
                }

                // Prevent default drag behaviors on the upload card
                ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                    uploadCard.addEventListener(eventName, preventDefaults, false);
                });

                // Highlight drop area when item is dragged over it
                uploadCard.addEventListener('dragenter', (e) => {
                    preventDefaults(e);
                    uploadCard.classList.add('drag-over');
                }, false);

                uploadCard.addEventListener('dragover', (e) => {
                    preventDefaults(e);
                    uploadCard.classList.add('drag-over');
                }, false);

                uploadCard.addEventListener('dragleave', (e) => {
                    preventDefaults(e);
                    // Only remove highlight if we're leaving the card itself, not a child element
                    if (!uploadCard.contains(e.relatedTarget)) {
                        uploadCard.classList.remove('drag-over');
                    }
                }, false);

                uploadCard.addEventListener('drop', (e) => {
                    preventDefaults(e);
                    uploadCard.classList.remove('drag-over');

                    const dt = e.dataTransfer;
                    const files = dt.files;

                    if (files.length > 0) {
                        // Validate file type (images only)
                        const file = files[0];
                        if (file.type.startsWith('image/')) {
                            // Create a new FileList-like object
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(file);
                            fileInput.files = dataTransfer.files;

                            // Trigger change event to update label
                            const changeEvent = new Event('change', { bubbles: true });
                            fileInput.dispatchEvent(changeEvent);
                        } else {
                            alert('Please drop an image file.');
                        }
                    }
                }, false);
            }

            // Setup drag and drop for upload cards (only when step 4 is active)
            function initializeDragAndDrop() {
                // Only initialize if step 4 is active
                const step4Panel = document.querySelector('[data-step="4"]');
                if (!step4Panel || !step4Panel.classList.contains('is-active')) {
                    return;
                }

                // Setup drag and drop for ID documents
                const frontCard = document.querySelector('label[for="upload_front"]');
                const backCard = document.querySelector('label[for="upload_back"]');

                // Setup drag and drop for profile images
                const headshotCard = document.querySelector('label[for="upload_headshot"]');
                const fullbodyCard = document.querySelector('label[for="upload_fullbody"]');

                if (frontCard && frontInput) setupDragAndDrop(frontCard, frontInput);
                if (backCard && backInput) setupDragAndDrop(backCard, backInput);
                if (headshotCard && headshotInput) setupDragAndDrop(headshotCard, headshotInput);
                if (fullbodyCard && fullbodyInput) setupDragAndDrop(fullbodyCard, fullbodyInput);
            }

            // Initialize on page load if step 4 is already active
            setTimeout(() => {
                if (current === 3) {
                    initializeDragAndDrop();
                }
            }, 200);

            if (stepInput) {
                const form = document.getElementById('profile-wizard');
                if (form) {
                    form.addEventListener('submit', () => {
                        stepInput.value = current + 1;
                    });
                }
            }

            // Initialize gender-based fields on page load
            setTimeout(() => {
                toggleGenderBasedFields();
            }, 100);

            render();
        })();
    </script>
@endsection
