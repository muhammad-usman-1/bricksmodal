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
            background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
            font-family: 'Space Grotesk', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            width: 100%;
            max-width: 380px;
            border-radius: 20px;
            background: #ffffff;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.08);
            padding: 36px 32px 28px;
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
            font-style: normal;
            font-size: 24px;
            line-height: 36px;
            letter-spacing: 0;
            text-align: center;
            margin: 0 0 12px;
            color: #171717;
        }
        p {
             font-family: 'Arimo', sans-serif;
              font-style: normal;
            margin: 0 0 28px;
            color: #6f6f6f;
            font-size: 13px;
            line-height: 1.6;
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
        .btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
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
            margin-top: 12px;
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
            <svg width="18" height="18" viewBox="0 0 24 24" aria-hidden="true">
                <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
            </svg>
            Continue with Google
        </a>

        <a class="btn gmail" href="{{ route('admin.login') }}">
            <svg width="21" height="21" viewBox="0 0 24 24" aria-hidden="true">
                <rect x="3.5" y="6" width="17" height="12" rx="3" fill="none" stroke="#1f1f1f" stroke-width="1.4" />
                <path d="M5 8.5L12 14.25L19 8.5" fill="none" stroke="#1f1f1f" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M5 16L9.5 12.5" fill="none" stroke="#1f1f1f" stroke-width="1.4" stroke-linecap="round" />
                <path d="M19 16L14.5 12.5" fill="none" stroke="#1f1f1f" stroke-width="1.4" stroke-linecap="round" />
            </svg>
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
