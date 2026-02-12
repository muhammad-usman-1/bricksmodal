<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bricks Studio - Model Login</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&family=Arimo:wght@400&display=swap" rel="stylesheet">
    <link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
    @php
        $bgImageUrl = isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png');
    @endphp
    <link rel="preload" href="{{ $bgImageUrl }}" as="image">
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
            background: #ffffff url('{{ $bgImageUrl }}') center center / cover no-repeat fixed;
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
            font-family: 'Arimo', sans-serif;
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
        .lead {
            font-family: 'Arimo', sans-serif;
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
            display: flex;
            gap: 8px;
            margin-bottom: 14px;
        }
        .country-code-display {
            display: flex;
            align-items: center;
            gap: 6px;
            height: 52px;
            padding: 0 12px;
            border: 1px solid #e6e6e6;
            border-radius: 14px;
            background: #f8f9fa;
            font-size: 14px;
            color: #1f1f1f;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .country-flag {
            width: auto;
            height: 18px;
            aspect-ratio: 4 / 3;
            display: inline-block;
            flex-shrink: 0;
            background-size: contain;
            background-position: center;
            background-repeat: no-repeat;
        }
        .phone-input-wrapper {
            position: relative;
            flex: 1;
        }
        .phone-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #7f8ea0;
        }
        #phone {
            width: 100%;
            height: 52px;
            padding: 0 14px 0 46px;
            border: 1px solid #e6e6e6;
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Space Grotesk', sans-serif;
            color: #1f1f1f;
            background: #ffffff;
        }
        #phone:focus {
            outline: none;
            border-color: #b7bec6;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }
        #phone.is-invalid {
            border-color: #dc3545;
        }
        #phone.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }
        .submit-btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 14px;
            background: black;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;

            margin-top: 6px;
            margin-bottom: 14px;
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
            font-size: 14px;
            font-weight: 400;
            text-decoration: none;
            font-family: 'Arimo', sans-serif;
        }
        .secondary-link span {
            border-bottom: 1px solid #3f3f3f;
            padding-bottom: 2px;
        }
        @media (max-width: 420px) {
            body {
                padding: 80px 16px 32px;
            }
            .auth-card {
                padding: 30px 24px 24px;
            }
        }
    </style>
</head>
<body>
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

        <form id="auth-form" method="POST" action="{{ route('talent.login.submit') }}" novalidate>
            @csrf

            <label class="field-label" for="phone">Phone Number</label>
            <div class="phone-wrapper">
                <div class="country-code-display">
                    <span class="fi fi-kw country-flag" title="Kuwait"></span>
                    <span>+965</span>
                </div>
                <div class="phone-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2L8.09 9.91a16 16 0 0 0 6 6l1.34-1.34a2 2 0 0 1 2-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                    </svg>
                    <input id="phone" type="tel" placeholder="00000000" maxlength="8">
                </div>
            </div>
            <div id="phone-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: 8px; margin-bottom: 12px; text-align: left;">
                Phone number is required.
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const phoneInput = document.querySelector('#phone');
            const form = document.getElementById('auth-form');
            const errorMsg = document.getElementById('phone-error');

            function validatePhone() {
                const inputValue = phoneInput.value.trim();
                const phoneNumber = inputValue.replace(/\D/g, '');
                
                if (phoneNumber.length === 0) {
                    phoneInput.classList.add('is-invalid');
                    errorMsg.textContent = 'Phone number is required.';
                    errorMsg.style.display = 'block';
                    return false;
                } else if (phoneNumber.length < 8) {
                    phoneInput.classList.add('is-invalid');
                    errorMsg.textContent = 'Phone number cannot be less than 8 digits';
                    errorMsg.style.display = 'block';
                    return false;
                } else {
                    phoneInput.classList.remove('is-invalid');
                    errorMsg.style.display = 'none';
                    return true;
                }
            }

            // Only allow digits in phone input
            phoneInput.addEventListener('input', function(e) {
                e.target.value = e.target.value.replace(/\D/g, '');
                
                // Validate on input if there was an error
                if (phoneInput.classList.contains('is-invalid')) {
                    validatePhone();
                }
            });

            // Validate on blur (when user leaves the field)
            phoneInput.addEventListener('blur', function() {
                validatePhone();
            });

            // Handle Enter key on phone input
            phoneInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const btn = document.getElementById('submit-btn');
                    if(btn) btn.click();
                }
            });

            form.addEventListener('submit', function(e) {
                if (!validatePhone()) {
                    e.preventDefault();
                    return;
                }

                const inputValue = phoneInput.value.trim();
                const phoneNumber = inputValue.replace(/\D/g, '');
                const countryCode = '+965';

                document.getElementById('phone_country_code').value = countryCode;
                document.getElementById('phone_number').value = phoneNumber;
            });
        });
    </script>
</body>
</html>
