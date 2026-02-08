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

        .wizard-body {
            padding: 48px 32px 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        .status-card {
            background: #fef2f2;
            border: 1px solid #fecaca;
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
            background: #fee2e2;
            border: 1px solid #fecaca;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #dc2626;
            margin-bottom: 8px;
            box-shadow: 0 4px 12px rgba(220, 38, 38, 0.1);
        }

        .status-title {
            font-size: 20px;
            font-weight: 700;
            color: #dc2626;
            margin: 0;
        }

        .status-desc {
            font-size: 15px;
            color: var(--ink-600);
            line-height: 1.6;
            margin: 0;
            max-width: 400px;
        }

        .verification-notes {
            background: #fff;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            margin-top: 8px;
            width: 100%;
            text-align: left;
        }

        .verification-notes-label {
            font-size: 12px;
            font-weight: 600;
            color: #991b1b;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .verification-notes-text {
            font-size: 14px;
            color: var(--ink-700);
            line-height: 1.5;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            width: 100%;
        }

        .btn-primary {
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

        .btn-primary:hover {
            background: #111;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            text-decoration: none;
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            height: 48px;
            background: #f3f4f6;
            color: var(--ink-700);
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 0 32px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
            gap: 10px;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            text-decoration: none;
            color: var(--ink-900);
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
                            <line x1="15" y1="9" x2="9" y2="15"></line>
                            <line x1="9" y1="9" x2="15" y2="15"></line>
                        </svg>
                    </div>
                    <h2 class="status-title">Your Profile Has Been Rejected</h2>
                    <p class="status-desc">
                        We're sorry, but your profile application has been rejected. Please review the feedback below and consider submitting a new application with the necessary corrections.
                    </p>

                    @if($profile->verification_notes)
                    <div class="verification-notes">
                        <div class="verification-notes-label">Rejection Reason:</div>
                        <div class="verification-notes-text">{{ $profile->verification_notes }}</div>
                    </div>
                    @endif
                </div>

                <div class="action-buttons">
                    <a href="{{ route('talent.onboarding.intro') }}" class="btn-primary">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="16" y1="13" x2="8" y2="13"></line>
                            <line x1="16" y1="17" x2="8" y2="17"></line>
                            <polyline points="10 9 9 9 8 9"></polyline>
                        </svg>
                        Start New Application
                    </a>

                    <form method="POST" action="{{ route('talent.logout') }}" style="width: 100%;">
                        @csrf
                        <button type="submit" class="btn-secondary">
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
    </div>
@endsection
