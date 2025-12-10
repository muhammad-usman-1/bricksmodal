@extends('layouts.app')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500&display=swap" rel="stylesheet">

    <style>
        body {
            background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
            font-family: 'Arimo', sans-serif;
        }

        .auth-shell {
            min-height: calc(100vh - 60px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 18px 45px rgba(0, 0, 0, 0.12);
            padding: 32px 28px 24px;
            text-align: center;
        }

        .logo {
            width: 150px;
            margin: 0 auto 6px;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 0.28em;
            text-transform: uppercase;
            color: #7b7b7b;
            margin-bottom: 20px;
        }

        h1 {
            font-weight: 400;
            font-size: 24px;
            line-height: 36px;
            margin: 0 0 10px;
            color: #1a1a1a;
        }

        .lead {
            font-size: 13px;
            line-height: 1.6;
            color: #5a5a5a;
            margin: 0 0 26px;
        }

        .field-label {
            display: block;
            text-align: left;
            font-size: 13px;
            font-weight: 500;
            color: #1f1f1f;
            margin-bottom: 8px;
        }

        .phone-wrapper {
            position: relative;
            margin-bottom: 18px;
        }

        .phone-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #7f8ea0;
        }

        #phone {
            width: 100%;
            height: 46px;
            padding: 0 14px 0 46px;
            border: 1px solid #e1e5eb;
            border-radius: 10px;
            font-size: 14px;
            color: #1f1f1f;
            background: #ffffff;
        }

        #phone:focus {
            outline: none;
            border-color: #b7bec6;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }

        .submit-btn {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            background: #111111;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
        }
        .alert {
            text-align: left;
        }
        .secondary-link,
        .secondary-link:visited,
        .secondary-link:hover,
        .secondary-link:active {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            color: #3f3f3f;
            font-size: 12px;
            margin-top: 12px;
            text-decoration: none;
        }
        .secondary-link span {
            border-bottom: 1px solid #3f3f3f;
            padding-bottom: 2px;
        }
    </style>

    <div class="auth-shell">
        <div class="auth-card">
            <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
            <div class="eyebrow">Studio</div>
            <h1>Welcome Back</h1>
            <p class="lead">Enter your phone number to access the<br>model portal</p>

            @if ($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    {{ session('status') }}
                </div>
            @endif

            <form id="auth-form" method="POST" action="{{ route('talent.login.submit') }}">
                @csrf

                <label class="field-label" for="phone">Phone Number</label>
                <div class="phone-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2L8.09 9.91a16 16 0 0 0 6 6l1.34-1.34a2 2 0 0 1 2-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <input id="phone" type="tel" placeholder="+1 (555) 000-0000" required>
                </div>

                <input type="hidden" name="phone_country_code" id="phone_country_code">
                <input type="hidden" name="phone_number" id="phone_number">

                <button type="submit" id="submit-btn" class="submit-btn">
                    Continue
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </form>

            <a class="secondary-link" href="{{ route('landing') }}"><span>Sign in as an Admin</span></a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('#phone');
            const form = document.getElementById('auth-form');

            form.addEventListener('submit', function(e) {
                const inputValue = phoneInput.value.trim();
                const cleaned = inputValue.replace(/[^\d+]/g, '');
                let countryCode = '+1';
                let national = '';

                if (cleaned.startsWith('+')) {
                    const digits = cleaned.slice(1).replace(/\D/g, '');
                    const codeDigits = digits.slice(0, 3) || '1';
                    countryCode = '+' + codeDigits;
                    national = digits.slice(codeDigits.length);
                } else {
                    const digits = cleaned.replace(/\D/g, '');
                    national = digits;
                }

                document.getElementById('phone_country_code').value = countryCode;
                document.getElementById('phone_number').value = national;
            });
        });
    </script>
@endsection
