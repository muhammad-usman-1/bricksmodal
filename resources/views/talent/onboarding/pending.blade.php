@extends('layouts.app')

@section('styles')
    <style>
        :root {
            --bg: #f6f7fb;
            --card: #f9fafc;
            --ink-900: #0f1524;
            --ink-800: #1c2435;
            --ink-700: #3b4150;
            --ink-600: #4b5563;
            --ink-500: #6b7280;
            --border: #e5e8ef;
            --primary: #0f0f0f;
            --muted: #6b7280;
            --success: #111827;
            --radius: 18px;
            --shadow: 0 26px 60px rgba(6, 19, 46, 0.16);
        }

        body {
            background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
            font-family: 'Arimo', sans-serif;
        }

        .pending-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .modal-card {
            width: min(520px, 100%);
            background: linear-gradient(180deg, #ffffff 0%, #f7f9fd 100%);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid #e4e8f0;
            padding: 18px 18px 16px;
        }

        .modal-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 4px 4px 12px;
        }

        .head-copy {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .eyebrow {
            margin: 0;
            color: var(--ink-900);
            font-weight: 700;
            font-size: 14px;
        }

        .timestamp {
            margin: 0;
            color: var(--ink-500);
            font-size: 12px;
        }

        .icon-btn {
            border: none;
            background: transparent;
            color: var(--ink-500);
            padding: 6px;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.15s ease;
        }

        .icon-btn:hover {
            background: #f0f2f6;
        }

        .modal-body {
            text-align: left;
            padding: 0 6px 0;
        }

        .thank-card {
            background: #f4f6fb;
            border: 1px solid #e6e9f0;
            border-radius: 12px;
            padding: 16px 16px 14px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);

            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        .status-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: #f1f4f8;
            color: #0f172a;
            margin-top: 4px;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.85);
        }

        .thank-copy {
            display: flex;
            flex-direction: column;
            gap: 12px;
            flex: 1;
            min-width: 0;
        }

        .title {
            margin: 0 0 8px;
            color: var(--ink-900);
            font-weight: 800;
            font-size: 20px;
        }

        .lede {

            color: var(--ink-600);
            font-size: 14px;
            
            max-width: 360px;
        }

        .notice-card {
            display: grid;
            grid-template-columns: 38px 1fr;
            gap: 12px;
            align-items: center;
            background: #ffffff;
            border: 1px solid #e5e8ef;
            border-radius: 10px;
            padding: 12px 14px;

            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.04);
        }

        .notice-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: #e8eef7;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #2f3750;
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.8);
        }

        .notice-title {
            font-weight: 800;
            color: var(--ink-900);
            margin: 0 0 2px;
            font-size: 14px;
        }

        .notice-text {
            margin: 0;
            color: var(--ink-700);
            font-size: 13px;
            line-height: 1.5;
        }

        .steps-card {
            background: #eef3ff;
            border: 1px solid #dbe6ff;
            border-radius: 12px;
            padding: 14px 16px 14px;
            text-align: left;
            margin-top: 12px;
            box-shadow: 0 10px 24px rgba(0, 0, 0, 0.04);
        }

        .steps-title {
            margin: 0 0 10px;
            color: var(--ink-900);
            font-weight: 800;
            font-size: 14px;
        }

        .steps-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: grid;
            gap: 10px;
        }

        .steps-list li {
            display: grid;
            grid-template-columns: 30px 1fr;
            gap: 10px;
            align-items: center;
            color: var(--ink-700);
            font-size: 14px;
        }

        .steps-list .num {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #111827;
            color: #fff;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 13px;
        }

        .actions {
            margin-top: 18px;
            padding: 0 6px;
        }

        .primary-btn {
            width: 100%;
            border: none;
            background: var(--primary);
            color: #fff;
            padding: 14px 16px;
            border-radius: 10px;
            font-weight: 700;
            letter-spacing: 0.1px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.18);
        }

        .primary-btn:hover {
            transform: translateY(-1px);
        }

        @media (max-width: 520px) {
            .modal-card {
                border-radius: 14px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="pending-shell">
        <div class="modal-card">
            <div class="modal-head">
                <div class="head-copy">
                    <p class="eyebrow">Application Submitted</p>
                    <p class="timestamp">Just now</p>
                </div>
                <button type="button" class="icon-btn" aria-label="Close" onclick="window.location='{{ route('talent.pending') }}'">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>

            <div class="modal-body">
                <div class="thank-card">
                    <div class="status-icon" aria-hidden="true">
                        <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10" />
                            <polyline points="9 12 11 14 15 10" />
                        </svg>
                    </div>

                    <div class="thank-copy">
                        <h3 class="title">Thank you for your application!</h3>
                        <p class="lede">Your profile has been successfully submitted and is now under review. Our team will carefully verify all the information you provided.</p>

                        <div class="notice-card">
                            <div class="notice-icon" aria-hidden="true">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <circle cx="12" cy="12" r="10" />
                                    <line x1="12" y1="8" x2="12" y2="12" />
                                    <line x1="12" y1="16" x2="12.01" y2="16" />
                                </svg>
                            </div>
                            <div>
                                <div class="notice-title">Important Notice</div>
                                <p class="notice-text">Profile verification will take up to <strong>1-2 business days</strong><br>You'll receive a notification once your profile is approved.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="steps-card">
                    <div class="steps-title">What happens next?</div>
                    <ol class="steps-list">
                        <li><span class="num">1</span> <span>Our team reviews your profile and documents</span></li>
                        <li><span class="num">2</span> <span>You receive an email notification about your approval</span></li>
                        <li><span class="num">3</span> <span>Access your dashboard and start browsing casting calls</span></li>
                    </ol>
                </div>

                <div class="actions">
                    <button type="button" class="primary-btn" onclick="window.location='{{ route('talent.pending') }}'">Got It Thanks!</button>
                </div>
            </div>
        </div>
    </div>
@endsection
