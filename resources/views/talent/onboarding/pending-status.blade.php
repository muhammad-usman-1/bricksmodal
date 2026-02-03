@extends('layouts.app')

@section('styles')
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
            max-width: 580px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            position: relative;
            overflow: hidden;
            text-align: center;
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
            margin: 0;
            color: #ffffff;
        }

        .wizard-body {
            padding: 48px 32px 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .status-card {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 16px;
            padding: 32px 24px;
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 16px;
        }

        .status-icon-box {
            width: 64px;
            height: 64px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #f97316;
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .status-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--ink-900);
            margin: 0;
        }

        .status-desc {
            font-size: 15px;
            color: var(--ink-600);
            line-height: 1.6;
            margin: 0;
            max-width: 400px;
        }

        .logout-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            background: black;
            color: white;
            border: none;
            border-radius: 12px;
            padding: 0 32px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            gap: 10px;
           
        }

        .logout-btn:active {
            transform: translateY(0);
        }

        @media (max-width: 640px) {
            .wizard-card {
                border-radius: 16px;
            }
            .wizard-body {
                padding: 32px 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="wizard-shell">
        <div class="wizard-card">
            <div class="wizard-body">
                <div class="status-card">
                    <div class="status-icon-box">
                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"></circle>
                            <line x1="12" y1="8" x2="12" y2="12"></line>
                            <line x1="12" y1="16" x2="12.01" y2="16"></line>
                        </svg>
                    </div>
                    <h2 class="status-title">Account Pending Verification</h2>
                    <p class="status-desc">
                        Thank you for your patience. Your profile is currently being reviewed by our team. You'll be able to access your dashboard as soon as your account is approved.
                    </p>
                </div>

                <form method="POST" action="{{ route('talent.logout') }}" style="width: 100%;">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                            <polyline points="16 17 21 12 16 7"></polyline>
                            <line x1="21" y1="12" x2="9" y2="12"></line>
                        </svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
