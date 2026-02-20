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
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Arimo', sans-serif;
            min-height: 100vh;
        }

        .login-shell {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 30px 16px;
        }

        .login-card {
            width: min(480px, 100%);
            background: linear-gradient(180deg, #ffffff 0%, #f7f9fd 100%);
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid #e4e8f0;
            padding: 32px 28px 28px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 28px;
        }

        .login-header img {
            height: 30px;
            width: 150px;
            margin-bottom: 20px;
        }

        .login-title {
            color: var(--ink-900);
            font-weight: 800;
            font-size: 24px;
            margin: 0 0 8px;
        }

        .login-subtitle {
            color: var(--ink-500);
            font-size: 14px;
            margin: 0;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            color: var(--ink-900);
            font-weight: 700;
            font-size: 13px;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid var(--border);
            border-radius: 10px;
            font-size: 14px;
            color: var(--ink-900);
            background: #fff;
            transition: all 0.15s ease;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(15, 15, 15, 0.1);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            display: block;
            color: #ef4444;
            font-size: 12px;
            margin-top: 6px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 24px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: var(--primary);
        }

        .form-check-label {
            color: var(--ink-700);
            font-size: 13px;
            cursor: pointer;
            margin: 0;
            text-transform: none;
            letter-spacing: normal;
            font-weight: 500;
        }

        .primary-btn {
            width: 100%;
            border: none;
            background: var(--primary);
            color: #fff;
            padding: 14px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 14px;
            letter-spacing: 0.1px;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 16px 30px rgba(0, 0, 0, 0.18);
            margin-bottom: 16px;
        }

        .primary-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.22);
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 20px 0;
            color: var(--ink-500);
            font-size: 12px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid var(--border);
        }

        .divider::before {
            margin-right: 12px;
        }

        .divider::after {
            margin-left: 12px;
        }

        .google-signin-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            color: var(--ink-700);
            cursor: pointer;
            font-family: 'Arimo', sans-serif;
            font-size: 14px;
            font-weight: 600;
            height: 48px;
            padding: 0 16px;
            text-decoration: none;
            width: 100%;
            box-sizing: border-box;
            transition: all 0.15s ease;
        }

        .google-signin-btn:hover {
            background-color: #f8f9fa;
            border-color: var(--ink-500);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            text-decoration: none;
            color: var(--ink-700);
        }

        .google-icon {
            width: 20px;
            height: 20px;
            margin-right: 12px;
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
            margin-top: 18px;
            text-decoration: none;
        }

        .secondary-link span {
            border-bottom: 1px solid #3f3f3f;
            padding-bottom: 2px;
        }

        @media (max-width: 520px) {
            .login-card {
                border-radius: 14px;
                padding: 24px 20px 20px;
            }
        }
    </style>
@endsection

@section('content')
    <div class="login-shell">
        <div class="login-card">
            <div class="login-header">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
                <h2 class="login-title">Admin Login</h2>
                <p class="login-subtitle">Sign in to access the admin dashboard</p>
            </div>

            <form method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <div class="form-group">
                    <label for="email">{{ trans('global.login_email') }}</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autofocus>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">{{ trans('global.login_password') }}</label>
                    <input id="password" type="password"
                        class="form-control @error('password') is-invalid @enderror" name="password" required>
                    @error('password')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <button type="submit" class="primary-btn">{{ trans('global.login') }}</button>
            </form>

            <a class="secondary-link" href="{{ route('talent.login') }}">
                <span>Sign in as Model</span>
            </a>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-control');
            inputs.forEach(input => {
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter' || e.keyCode === 13) {
                        e.preventDefault();
                        const form = this.closest('form');
                        if (form) {
                            form.submit();
                        }
                    }
                });
            });
        });
    </script>
@endsection
