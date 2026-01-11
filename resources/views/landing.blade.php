<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bricks Studio</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&family=Arimo:wght@400&display=swap" rel="stylesheet">
    <style>
        :root {
            color-scheme: light only;
        }
        * {
            box-sizing: border-box;
        }
        body {
            margin: 0;
            min-height: 100vh;
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Space Grotesk', sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            
            padding: 100px 20px 40px;
        }
        .auth-card {
            width: 100%;
            max-width: 380px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.08);
            padding: 30px 26px 30px;
            text-align: center;
        }
        .logo {
            width: 150px;
            margin: 0 auto 5px;
        }
        .eyebrow {
            text-transform: uppercase;
            font-size: 11px;
            letter-spacing: 0.35em;
            color: #a0a0a0;
            margin-bottom: 10px;
        }
        h1 {
            font-family: 'Arimo', sans-serif;
            font-weight: 400;
            font-size: 24px;
            line-height: 36px;
            margin: 0 0 10px;
            color: #1a1a1a;
            text-align: center;
        }
        p {
            font-family: 'Arimo', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #5a5a5a;
            margin: 0 0 26px;
            text-align: center;
        }
        .btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            width: 100%;
            padding: 12px 14px;
            border-radius: 14px;
            border: 1px solid #e6e6e6;
            background: #ffffff;
            color: #1f1f1f;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            margin-bottom: 14px;
            stroke:#D1D5DC;
        }
        .btn svg {
            flex-shrink: 0;
        }
        
        .btn:focus-visible {
            outline: 2px solid #111;
            outline-offset: 4px;
        }
        .btn.google {
            border-color: #e4e4e4;
        }
        .btn.gmail {
            border-color: #f0f0f0;
        }
        .btn.primary {
            background: #111111;
            color: #ffffff;
            border: none;
            margin-top: 6px;
        }
        .btn.primary:hover {
            box-shadow: 0 20px 35px rgba(0, 0, 0, 0.25);
        }
        .secondary-link {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            color: #3f3f3f;
            font-size: 12px;
            
            text-decoration: none;
        }
        .secondary-link span {
            border-bottom: 1px solid #3f3f3f;
            padding-bottom: 2px;
        }
        @media (max-width: 420px) {
            .auth-card {
                padding: 30px 24px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="Bricks Studio">
        <div class="eyebrow">Studio</div>
        <h1>Welcome Back</h1>
        <p>Sign in with Google to access your admin<br>dashboard</p>

        <a class="btn google" href="{{ route('admin.login.google') }}">
            <img src="{{ asset('images/GoogleIcon.png') }}" alt="Google Logo" width="18" height="18">
            Continue with Google
        </a>

        <a class="btn gmail" href="{{ route('admin.login') }}">
            <img src="{{ asset('images/emailicon.png') }}" alt="Mail Logo" width="21" height="18">
            Continue with Gmail
        </a>

        <a class="btn primary" href="{{ route('admin.login') }}">
            Continue
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14" />
                <path d="M13 6l6 6-6 6" />
            </svg>
        </a>

        <a class="secondary-link" href="{{ route('talent.login') }}">
            <span>Sign in as model</span>
        </a>
    </div>
</body>
</html>
