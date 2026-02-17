<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bricks Studio - Verify Identity</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Arimo:wght@400;700&display=swap" rel="stylesheet">
    @php
        $adminSettings = isset($adminSettings) ? $adminSettings : \App\Models\AdminSetting::singleton();
        $bgImageUrl = $adminSettings->background_image_url ?: asset('images/models_bg.png');
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
            padding: 40px 34px; /* Increased side padding */
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

        .mobile-auth-card .otp-grid {
            display: flex;
            gap: 10px;
            margin-bottom: 24px;
        }

        .mobile-auth-card .otp-input {
            flex: 1;
            max-width: 65px;
            height: 65px;
            border: 1px solid #1a1a1a;
            border-radius: 12px;
            background: #ffffff;
            text-align: center;
            font-size: 24px;
            font-weight: 600;
            color: #1a1a1a;
            outline: none;
        }

        .mobile-auth-card .otp-input:focus {
            border-color: #1a1a1a;
            box-shadow: 0 0 0 2px rgba(26, 26, 26, 0.05);
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

        .mobile-auth-card .meta {
            font-size: 14px;
            color: #6b7280;
            margin-bottom: 14px;
        }

        .mobile-auth-card .meta a {
            color: #6b7280;
            text-decoration: underline;
        }

        .mobile-auth-card .alt-link {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 14px;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
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
            font-family: 'Arimo', sans-serif;
        }

        h1 {
            font-family: 'Arimo', sans-serif;
            font-weight: 400;
            font-size: 20px;
            line-height: 30px;
            margin: 0 0 10px;
            color: #1a1a1a;
            text-align: center;
        }

        .lead {
            font-family: 'Arimo', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #4f4f4f;
            margin: 0 0 30px;
            text-align: center;
        }

        .otp-grid {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 22px;
        }

        .otp-input {
            width: 54px;
            height: 48px;
            border: 1px solid #202020;
            border-radius: 10px;
            background: #f8f9fa;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #1a1a1a;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .otp-input:focus {
            border-color: #202020;
            box-shadow: 0 0 0 3px rgba(32, 32, 32, 0.12);
            background: #ffffff;
        }

        .submit-btn {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 10px;
            background: black;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .meta {
            font-size: 12px;
            color: #555;
            margin-top: 12px;
            text-align: center;
        }

        .meta a {
            color: #3f3f3f;
            text-decoration: none;
        }

        .alt-link {
            display: inline-block;
            margin-top: 14px;
            font-size: 12px;
            color: #3f3f3f;
            text-decoration: none;
            text-align: center;
            width: 100%;
        }

        .alt-link span {
            border-bottom: 1px solid #3f3f3f;
            padding-bottom: 2px;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-bottom: 15px;
            text-align: center;
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
                padding: 32px 24px; /* Slightly increased from login's 20px */
                max-width: 100%;
            }
            .mobile-auth-card h1 {
                font-size: 38px;
            }
            .mobile-auth-card .lead {
                font-size: 13px;
                white-space: normal;
            }
            .mobile-auth-card .otp-grid {
                gap: 8px;
            }
            .mobile-auth-card .otp-input {
                max-width: 60px;
                height: 60px;
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <!-- Desktop Card -->
    <div class="auth-card">
        <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
        <div class="eyebrow">Studio</div>
        <h1>Verify Identity</h1>
        <p class="lead">Enter the code sent to<br>{{ ($phone['phone_country_code'] ?? '') . ' ' . ($phone['phone_number'] ?? '') }}</p>

        @if(isset($debugging) && isset($otp) && !empty($otp))
            <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center;">
                <div style="font-size: 11px; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-weight: 600;">Testing Mode</div>
                <div style="font-size: 24px; font-weight: 700; color: #92400e; letter-spacing: 4px; font-family: 'Courier New', monospace;">{{ $otp }}</div>
                <div style="font-size: 11px; color: #92400e; margin-top: 4px;">This OTP is stored in the database</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form class="otp-form" method="POST" action="{{ route('talent.otp.verify') }}">
            @csrf
            <div class="otp-grid">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            </div>
            <input type="hidden" name="otp" class="otp-hidden" value="{{ old('otp') }}">
            <button type="submit" class="submit-btn">
                Verify & Login
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 17v-3" />
                    <rect x="4" y="10" width="16" height="10" rx="2" ry="2" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                </svg>
            </button>
            <div class="meta">
                <span class="countdown">55 secs left.</span>
                <a href="{{ route('talent.login.submit') }}">Resend code</a>
            </div>
            <a class="alt-link" href="{{ route('talent.login') }}"><span>Change Phone Number</span></a>
        </form>
    </div>

    <!-- Mobile Card -->
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
            <h1>Verify Your<br>Identity</h1>
            <p class="lead">Enter the code sent to<br>{{ ($phone['phone_country_code'] ?? '') . ' ' . ($phone['phone_number'] ?? '') }}</p>
        </div>

        <div id="mobile-content-ar" style="display: none; direction: rtl; text-align: right;">
            <h1 style="font-family: 'Arimo', sans-serif; text-align: right;">تحقق من<br>هويتك</h1>
            <p class="lead" style="font-family: 'Arimo', sans-serif; text-align: right; white-space: normal;">أدخل الرمز المرسل إلى<br>{{ ($phone['phone_country_code'] ?? '') . ' ' . ($phone['phone_number'] ?? '') }}</p>
        </div>

        @if(isset($debugging) && isset($otp) && !empty($otp))
            <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 12px; padding: 16px; margin-bottom: 24px; text-align: center;">
                <div style="font-size: 11px; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-weight: 600;">Testing Mode</div>
                <div style="font-size: 28px; font-weight: 700; color: #92400e; letter-spacing: 6px;">{{ $otp }}</div>
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message" style="text-align: left;">
                @foreach ($errors->all() as $error)
                    {{ $error }}
                @endforeach
            </div>
        @endif

        <form class="otp-form" method="POST" action="{{ route('talent.otp.verify') }}">
            @csrf
            <div class="otp-grid">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            </div>
            <input type="hidden" name="otp" class="otp-hidden" value="{{ old('otp') }}">
            <button type="submit" class="submit-btn" style="height: 60px;">
                <span id="mobile-btn-en">Verify & Login</span>
                <span id="mobile-btn-ar" style="display: none;">تحقق وتسجيل الدخول</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 17v-3" />
                    <rect x="4" y="10" width="16" height="10" rx="2" ry="2" />
                    <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                </svg>
            </button>
            <div class="meta">
                <span class="countdown" data-en=" secs left." data-ar=" ثانية متبقية.">55 secs left.</span>
                <a href="{{ route('talent.login.submit') }}" id="mobile-resend-en">Resend code</a>
                <a href="{{ route('talent.login.submit') }}" id="mobile-resend-ar" style="display: none; direction: rtl; text-align: right;">إعادة إرسال الرمز</a>
            </div>
            <a class="alt-link" href="{{ route('talent.login') }}">
                <span id="mobile-change-en">Change Phone Number</span>
                <span id="mobile-change-ar" style="display: none; direction: rtl; text-align: right;">تغيير رقم الهاتف</span>
            </a>
        </form>
    </div>

    <script>
        (function() {
            // Mobile Language Toggle Logic
            const langBtns = document.querySelectorAll('.lang-btn');
            const contentEn = document.getElementById('mobile-content-en');
            const contentAr = document.getElementById('mobile-content-ar');
            const btnTEn = document.getElementById('mobile-btn-en');
            const btnTAr = document.getElementById('mobile-btn-ar');
            const resendEn = document.getElementById('mobile-resend-en');
            const resendAr = document.getElementById('mobile-resend-ar');
            const changeEn = document.getElementById('mobile-change-en');
            const changeAr = document.getElementById('mobile-change-ar');
            const countdownEls = document.querySelectorAll('.countdown');
            
            let systemLang = 'en';

            langBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    langBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    systemLang = btn.dataset.lang;
                    const isAr = systemLang === 'ar';
                    
                    if (isAr) {
                        contentEn.style.display = 'none';
                        contentAr.style.display = 'block';
                        btnTEn.style.display = 'none';
                        btnTAr.style.display = 'inline';
                        resendEn.style.display = 'none';
                        resendAr.style.display = 'inline';
                        changeEn.style.display = 'none';
                        changeAr.style.display = 'inline';
                    } else {
                        contentEn.style.display = 'block';
                        contentAr.style.display = 'none';
                        btnTEn.style.display = 'inline';
                        btnTAr.style.display = 'none';
                        resendEn.style.display = 'inline';
                        resendAr.style.display = 'none';
                        changeEn.style.display = 'inline';
                        changeAr.style.display = 'none';
                    }
                    updateCountdownDisplay();
                });
            });

            const forms = document.querySelectorAll('.otp-form');
            
            forms.forEach(form => {
                const inputs = Array.from(form.querySelectorAll('.otp-input'));
                const hidden = form.querySelector('.otp-hidden');
                const grid = form.querySelector('.otp-grid');

                function setHidden() {
                    hidden.value = inputs.map(i => i.value || '').join('');
                }

                inputs.forEach((el, i) => {
                    el.addEventListener('keydown', e => {
                        if (e.key === 'Backspace' && !el.value && i > 0) {
                            inputs[i - 1].focus();
                            return;
                        }
                        if (e.key === 'ArrowLeft' && i > 0) {
                            e.preventDefault();
                            inputs[i - 1].focus();
                        }
                        if (e.key === 'ArrowRight' && i < inputs.length - 1) {
                            e.preventDefault();
                            inputs[i + 1].focus();
                        }
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            form.querySelector('.submit-btn')?.click();
                        }
                        if (!/^\d$/.test(e.key) && !['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight', 'Enter'].includes(e.key)) {
                            e.preventDefault();
                        }
                    });
                    el.addEventListener('input', () => {
                        el.value = el.value.replace(/\D/g, '').slice(0, 1);
                        if (el.value && i < inputs.length - 1) inputs[i + 1].focus();
                        setHidden();
                    });
                });

                grid.addEventListener('paste', e => {
                    const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, inputs.length);
                    if (!txt) return;
                    e.preventDefault();
                    inputs.forEach((el, idx) => el.value = txt[idx] || '');
                    (txt.length < inputs.length ? inputs[txt.length] || inputs[inputs.length-1] : inputs[inputs.length - 1]).focus();
                    setHidden();
                });
            });

            // Auto-focus first visible input
            setTimeout(() => {
                const visibleInputs = Array.from(document.querySelectorAll('.otp-input')).filter(el => el.offsetParent !== null);
                if (visibleInputs.length > 0) visibleInputs[0].focus();
            }, 100);

            // Synchronized countdown
            let s = 55;
            
            const updateCountdownDisplay = () => {
                countdownEls.forEach(el => {
                    const suffix = systemLang === 'ar' ? el.dataset.ar : el.dataset.en;
                    if (systemLang === 'ar') {
                        el.style.direction = 'rtl';
                        el.style.textAlign = 'right';
                    } else {
                        el.style.direction = 'ltr';
                        el.style.textAlign = 'left';
                    }
                    el.textContent = `${s}${suffix}`;
                });
            };

            const tick = () => {
                updateCountdownDisplay();
                if (s-- <= 0) clearInterval(iv);
            };
            const iv = setInterval(tick, 1000);
            tick();
        })();
    </script>
</body>
</html>
