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
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Arimo:wght@400;700&display=swap" rel="stylesheet">
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
        
        /* Mobile Card Styles */
        .mobile-auth-card {
            display: none;
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
            box-sizing: border-box;
            overflow: hidden;
        }

        .mobile-auth-card * {
            box-sizing: border-box;
        }

        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .mobile-header .logo-wrap {
            margin-bottom: 0 !important;
        }

        .mobile-auth-card h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 44px;
            line-height: 1.1;
            margin: 0 0 16px;
            color: #1a1a1a;
            text-align: left;
            letter-spacing: -0.02em;
        }

        .lang-toggle {
            display: inline-flex;
            gap: 4px;
            padding: 4px;
            background: #f3f4f6;
            border-radius: 24px;
            margin-bottom: 0px;
        }

        .lang-btn {
            padding: 6px 18px;
            border: none;
            border-radius: 20px;
            background: transparent;
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s;
        }

        .lang-btn.active {
            background: #ffffff;
            color: #1a1a1a;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            font-weight: 700;
        }

        .mobile-auth-card .lead {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
            color: #6a7682;
            margin: 0 0 40px;
            letter-spacing: -0.01em;
            white-space: nowrap;
        }

        .mobile-auth-card .field-label {
            font-size: 16px;
            font-weight: 500;
            color: #4b5563;
            margin-bottom: 12px;
        }

        .mobile-auth-card .phone-wrapper {
            display: flex;
            gap: 8px;
            margin-bottom: 24px;
        }

        .mobile-auth-card .country-code-display {
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
            font-family: 'Space Grotesk', sans-serif;
        }

        .mobile-auth-card .phone-input-wrapper {
            position: relative;
            flex: 1;
        }

        .mobile-auth-card .phone-input-wrapper svg {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #7f8ea0;
            width: 18px;
            height: 18px;
            pointer-events: none;
        }

        .mobile-auth-card .phone-input {
            width: 100%;
            height: 52px;
            padding: 0 14px 0 46px;
            border: 1px solid #e6e6e6;
            border-radius: 14px;
            font-size: 14px;
            font-family: 'Space Grotesk', sans-serif;
            background: #ffffff;
            color: #1f1f1f;
        }

        .mobile-auth-card .phone-input::placeholder {
            color: #9ca3af;
        }

        .mobile-auth-card .submit-btn {
            width: 100%;
            height: 60px;
            background: #1a1a1a;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            margin-top: 10px;
            margin-bottom: 16px;
            transition: background 0.2s;
        }

        .mobile-auth-card .submit-btn:active {
            background: #333;
        }

        .mobile-auth-card .links-container {
            font-size: 14px;
            color: #6b7280;
            margin-top: 24px;
        }

        .mobile-auth-card .links-container a {
            color: #6b7280;
            text-decoration: none;
        }

        .mobile-auth-card .links-container a.admin-link {
            text-decoration: underline;
        }

        .mobile-auth-card .signup-text {
            margin-top: 24px;
            text-align: left;
            font-size: 14px;
            color: #6b7280;
        }

        .mobile-auth-card .signup-text a {
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
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
            white-space: nowrap;
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
        .phone-input {
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
        .phone-input:focus {
            outline: none;
            border-color: #b7bec6;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.06);
        }
        .phone-input.is-invalid {
            border-color: #dc3545;
        }
        .phone-input.is-invalid:focus {
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
        @media (max-width: 768px) {
            body {
                padding: 40px 20px;
                align-items: center;
            }
            .auth-card {
                display: none;
            }
            .mobile-auth-card {
                display: block;
            }
        }
        
        @media (max-width: 400px) {
            body {
                padding: 20px 12px;
            }
            .mobile-auth-card {
                padding: 32px 20px;
                max-width: 100%;
            }
            .mobile-auth-card h1 {
                font-size: 38px;
            }
            .mobile-auth-card .lead {
                font-size: 13px;
                white-space: normal;
            }
        }
    </style>
</head>
<body>
    <div class="auth-card">
        <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
        <div class="eyebrow">Studio</div>
        <h1>Welcome Back</h1>
        <p class="lead">Enter your phone number to access the model portal</p>

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

        <form method="POST" action="{{ route('talent.login.submit') }}" class="auth-form" novalidate>
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
                    <input class="phone-input" type="tel" placeholder="00000000" maxlength="8">
                </div>
            </div>
            <div class="phone-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: 8px; margin-bottom: 12px; text-align: left;">
                Phone number is required.
            </div>

            <input type="hidden" name="phone_country_code" class="phone_country_code">
            <input type="hidden" name="phone_number" class="phone_number">

            <button type="submit" class="submit-btn" style="height: 52px; border: none; border-radius: 14px; background: black; color: #ffffff; font-size: 14px; font-weight: 500; display: flex; align-items: center; justify-content: center; gap: 10px; cursor: pointer; margin-top: 6px; margin-bottom: 14px;">
                Continue
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>

        <a class="secondary-link" href="{{ route('landing') }}"><span>Sign in as an Admin</span></a>
    </div>

    <div class="mobile-auth-card">
        <div class="mobile-header">
            <div class="logo-wrap">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Logo" style="width: 140px;">
            </div>
            
            <div class="lang-toggle">
                <button type="button" class="lang-btn active" data-lang="en">English</button>
                <button type="button" class="lang-btn" data-lang="ar">عربي</button>
            </div>
        </div>

        <div id="mobile-content-en">
            <h1>Welcome<br>Back</h1>
            <p class="lead">Enter your phone number to access the model portal</p>
        </div>

        <div id="mobile-content-ar" style="display: none; direction: rtl; text-align: right;">
            <h1 style="font-family: 'Arimo', sans-serif; text-align: right;">مرحباً<br>بعودتك</h1>
            <p class="lead" style="font-family: 'Arimo', sans-serif; white-space: normal; text-align: right;">أدخل رقم هاتفك للوصول إلى بوابة النماذج</p>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('talent.login.submit') }}" class="auth-form" novalidate>
            @csrf
            
            <div id="mobile-label-en" class="field-label">Phone Number</div>
            <div id="mobile-label-ar" class="field-label" style="display: none; direction: rtl; text-align: right;">رقم الهاتف</div>
            
            <div class="phone-wrapper">
                <div class="country-code-display">
                    <span class="fi fi-kw country-flag" title="Kuwait"></span>
                    <span>+965</span>
                </div>
                <div class="phone-input-wrapper">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6A19.79 19.79 0 0 1 2.12 4.18 2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>
                    </svg>
                    <input type="tel" name="phone_number" class="phone-input" placeholder="00000000" maxlength="8">
                </div>
            </div>
            <div class="phone-error" style="display: none; color: #dc3545; font-size: 12px; margin-top: -12px; margin-bottom: 12px; text-align: left;">
                Phone number is required.
            </div>

            <input type="hidden" name="phone_country_code" class="phone_country_code">
            <input type="hidden" name="phone_number" class="phone_number">

            <button type="submit" class="submit-btn" style="height: 60px; background: #1a1a1a; color: #ffffff; border: none; border-radius: 12px; font-size: 16px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 12px; cursor: pointer; margin-top: 10px; margin-bottom: 16px;">
                <span id="mobile-btn-en">Continue</span>
                <span id="mobile-btn-ar" style="display: none;">استمرار</span>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>

        <div class="links-container">
            <span id="mobile-admin-en">Sign in as an <a href="{{ route('landing') }}" class="admin-link">Admin</a></span>
            <span id="mobile-admin-ar" style="display: none; direction: rtl; text-align: right;">تسجيل الدخول كـ <a href="{{ route('landing') }}" class="admin-link">مسؤول</a></span>
        </div>

        <div class="signup-text">
            <span id="mobile-signup-en">Don't have an profile? <a href="">Apply Now</a></span>
            <span id="mobile-signup-ar" style="display: none; direction: rtl; text-align: right;">ليس لديك ملف تعريف؟ <a href="{{ route('talent.register') }}">قدم الآن</a></span>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile Language Toggle Logic
            const langBtns = document.querySelectorAll('.lang-btn');
            const contentEn = document.getElementById('mobile-content-en');
            const contentAr = document.getElementById('mobile-content-ar');
            const labelEn = document.getElementById('mobile-label-en');
            const labelAr = document.getElementById('mobile-label-ar');
            const btnEn = document.getElementById('mobile-btn-en');
            const btnAr = document.getElementById('mobile-btn-ar');
            const adminEn = document.getElementById('mobile-admin-en');
            const adminAr = document.getElementById('mobile-admin-ar');
            const signupEn = document.getElementById('mobile-signup-en');
            const signupAr = document.getElementById('mobile-signup-ar');
            const phoneInputs = document.querySelectorAll('.phone-input');

            langBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    langBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    const isAr = btn.dataset.lang === 'ar';
                    
                    if (isAr) {
                        contentEn.style.display = 'none';
                        contentAr.style.display = 'block';
                        labelEn.style.display = 'none';
                        labelAr.style.display = 'block';
                        btnEn.style.display = 'none';
                        btnAr.style.display = 'inline';
                        adminEn.style.display = 'none';
                        adminAr.style.display = 'block';
                        signupEn.style.display = 'none';
                        signupAr.style.display = 'block';
                        phoneInputs.forEach(input => input.placeholder = 'أدخل رقمك');
                    } else {
                        contentEn.style.display = 'block';
                        contentAr.style.display = 'none';
                        labelEn.style.display = 'block';
                        labelAr.style.display = 'none';
                        btnEn.style.display = 'inline';
                        btnAr.style.display = 'none';
                        adminEn.style.display = 'block';
                        adminAr.style.display = 'none';
                        signupEn.style.display = 'block';
                        signupAr.style.display = 'none';
                        phoneInputs.forEach(input => input.placeholder = 'Enter your number');
                    }
                });
            });

            const forms = document.querySelectorAll('.auth-form');

            forms.forEach(form => {
                const phoneInput = form.querySelector('.phone-input');
                const errorMsg = form.querySelector('.phone-error');
                const countryCodeField = form.querySelector('.phone_country_code');
                const phoneNumberField = form.querySelector('.phone_number');

                function validatePhone() {
                    let inputValue = phoneInput.value.trim();
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

                phoneInput.addEventListener('input', function(e) {
                    e.target.value = e.target.value.replace(/\D/g, '');
                    
                    if (phoneInput.classList.contains('is-invalid')) {
                        validatePhone();
                    }
                });

                phoneInput.addEventListener('blur', function() {
                    validatePhone();
                });

                form.addEventListener('submit', function(e) {
                    if (!validatePhone()) {
                        e.preventDefault();
                        return;
                    }

                    const inputValue = phoneInput.value.trim();
                    let phoneNumber = inputValue.replace(/\D/g, '');
                    
                    // If it starts with 965, strip it for the phone_number field
                    if (phoneNumber.startsWith('965') && phoneNumber.length > 8) {
                        phoneNumber = phoneNumber.substring(3);
                    }

                    countryCodeField.value = '+965';
                    phoneNumberField.value = phoneNumber;
                });
            });
        });
    </script>
</body>
</html>
