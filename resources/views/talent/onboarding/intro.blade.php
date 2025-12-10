@extends('layouts.app')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        body {
           background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
            font-family: 'Arimo', sans-serif;
            color: #1f1f1f;
        }

        .intro-shell {
            min-height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px 36px;
        }

        .intro-card {
            width: 100%;
            max-width: 506px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            padding: 24px 22px 22px;
            position: relative;
        }

        .disclaimer {
            position: absolute;
            top: -28px;
            left: 0;
            right: 0;
            margin: 0 auto;
            text-align: center;
            font-size: 13px;
            color: #9aa3b2;
            letter-spacing: 0.6px;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 16px;
        }

        .logo-wrap img {
            width: 140px;
            height: auto;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #7c7c7c;
            margin-top: 4px;
        }

        h1 {
            font-size: 22px;
            font-weight: 600;
            margin: 8px 0 6px;
            color: #212121;
            text-align: center;
        }

        .lead {
            font-size: 13px;
            color: #586272;
            text-align: center;
            margin: 0 0 16px;
        }

        .info-box {
            display: flex;
            gap: 12px;
            padding: 14px;
            background: #eef3fb;
            border: 1px solid #d7e2f5;
            border-radius: 12px;
            margin-bottom: 16px;
        }

        .info-ico {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: linear-gradient(180deg, #f7f9fd 0%, #e7efff 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4d74cc;
            flex-shrink: 0;
            font-weight: 700;
        }

        .info-text h3 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
            color: #2f3a4d;
        }

        .info-text p {
            margin: 0;
            font-size: 12px;
            color: #4f5c70;
            line-height: 1.5;
        }

        .section-title {
            font-size: 14px;
            color: #111111;
            font-weight: 700;
            margin: 8px 0 12px;
        }

        .needs-list {
            list-style: none;
            padding: 0;
            margin: 0 0 16px;
            display: grid;
            gap: 12px;
        }

        .needs-item {
            display: grid;
            grid-template-columns: 32px 1fr;
            gap: 12px;
            font-size: 14px;
            color: #1f2937;
        }

        .badge-num {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #f2f4f7;
            color: #4a5568;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .needs-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .needs-text strong {
            font-size: 14px;
            color: #111827;
        }

        .needs-text span {
            font-size: 13px;
            color: #4b5563;
        }

        .estimate {
            padding: 14px 14px 12px;
            border-radius: 12px;
            border: 1px solid #edf0f4;
            background: #f8fafc;
            font-size: 13px;
            display: grid;
            grid-template-columns: 28px 1fr;
            gap: 10px;
            color: #1f2937;
            margin-bottom: 14px;
            align-items: center;
        }

        .estimate svg {
            color: #4b5563;
        }

        .consent {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 4px;
        }

        .consent input {
            margin-top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 6px;
            accent-color: #111111;
            border: 1px solid #111111;
        }

        .cta {
            width: 100%;
            border: none;
            background: #111111;
            color: #ffffff;
            height: 46px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .cta:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.18);
        }

        .subtle-link {
            display: block;
            margin-top: 12px;
            text-align: center;
            font-size: 12px;
            color: #6a7485;
            text-decoration: none;
        }

        .subtle-link span {
            border-bottom: 1px solid #c3c9d4;
            padding-bottom: 2px;
        }
    </style>

    <div class="intro-shell">
        <div class="intro-card">

            <div class="logo-wrap">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="Bricks Studio logo">
                <div class="eyebrow">Studio</div>
            </div>

            <h1>Complete Your Profile</h1>
            <p class="lead">One-time setup to get you started</p>

            <div class="info-box">
                <div class="info-ico">i</div>
                <div class="info-text">
                    <h3>Important Notice</h3>
                    <p>This is a one-time profile setup. <br>You will provide basic information, body measurements, and verification documents. Once done, you will not need to go through this process again.</p>
                </div>
            </div>

            <div class="section-title">What you'll need:</div>
            <ul class="needs-list">
                <li class="needs-item">
                    <span class="badge-num">1</span>
                    <span class="needs-text">
                        <strong>Personal Information</strong>
                        <span>Name, date of birth, nationality, and contact details</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">2</span>
                    <span class="needs-text">
                        <strong>Physical Attributes</strong>
                        <span>Height, weight, hair color, eye color, and body measurements</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">3</span>
                    <span class="needs-text">
                        <strong>Additional Details</strong>
                        <span>Tattoos, piercings, and other distinguishing features</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">4</span>
                    <span class="needs-text">
                        <strong>ID Verification</strong>
                        <span>Upload front and back of your government-issued ID</span>
                    </span>
                </li>
            </ul>

            <div class="estimate">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <div><strong>Estimated time: 5-10 minutes</strong><br><span style="color:#4b5563;">Make sure you have all required information ready</span></div>
            </div>

            <label class="consent">
                <input type="checkbox" checked aria-label="Privacy acceptance">
                <span>Any privacy acceptance text</span>
            </label>

            <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                Get Started
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>

        </div>
    </div>
@endsection
