<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bricks Studio - Complete Your Profile</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600;700&family=Space+Grotesk:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    @php
        $bgImageUrl = isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png');
    @endphp
    
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
            font-family: 'Arimo', sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
        }

        /* Desktop Card Styles */
        .intro-card {
            width: 100%;
            max-width: 506px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 35px 70px rgba(0, 0, 0, 0.08);
            padding: 30px 26px 30px;
            text-align: left;
            position: relative;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 24px;
        }

        .logo-wrap img {
            width: 140px;
            height: auto;
        }

        .eyebrow {
            font-size: 11px;
            letter-spacing: 0.22em;
            text-transform: uppercase;
            color: #7c7c7c;
            margin-top: 4px;
        }

        h1 {
            font-size: 24px;
            font-weight: 700;
            margin: 0 0 8px;
            color: #1a1a1a;
            font-family: 'Space Grotesk', sans-serif;
        }

        .lead {
            font-family: 'Arimo', sans-serif;
            font-size: 14px;
            color: #6a7682;
            margin: 0 0 24px;
        }

        /* Mobile Card Styles */
        .mobile-intro-card {
            display: none;
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 34px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
            box-sizing: border-box;
            overflow: hidden;
        }

        .mobile-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
        }

        .mobile-header .logo-wrap {
            margin-bottom: 0 !important;
            text-align: left;
        }

        .mobile-intro-card h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 40px;
            line-height: 1.1;
            margin: 0 0 16px;
            color: #1a1a1a;
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

        .mobile-intro-card .lead {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
            color: #6a7682;
            margin: 0 0 30px;
            letter-spacing: -0.01em;
        }

        .info-box {
            display: flex;
            gap: 12px;
            padding: 16px;
            background: #eef3fb;
            border: 1px solid #d7e2f5;
            border-radius: 14px;
            margin-bottom: 24px;
        }

        .info-ico {
            width: 32px;
            height: 32px;
            border-radius: 999px;
            background: linear-gradient(180deg, #f7f9fd 0%, #e7efff 100%);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #4d74cc;
            flex-shrink: 0;
            font-weight: 700;
        }

        .info-text h3 {
            margin: 0 0 4px;
            font-size: 14px;
            font-weight: 700;
            color: #2f3a4d;
        }

        .info-text p {
            margin: 0;
            font-size: 12px;
            color: #4f5c70;
            line-height: 1.5;
        }

        .section-title {
            font-size: 14px;
            color: #111111;
            font-weight: 700;
            margin: 8px 0 12px;
        }

        .needs-list {
            list-style: none;
            padding: 0;
            margin: 0 0 24px;
            display: grid;
            gap: 16px;
        }

        .needs-item {
            display: grid;
            grid-template-columns: 32px 1fr;
            gap: 12px;
            font-size: 14px;
            color: #1f2937;
        }

        .badge-num {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: #f2f4f7;
            color: #4a5568;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .needs-text {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .needs-text strong {
            font-size: 14px;
            color: #111827;
        }

        .needs-text span {
            font-size: 13px;
            color: #4b5563;
        }

        .estimate {
            padding: 14px;
            border-radius: 12px;
            border: 1px solid #edf0f4;
            background: #f8fafc;
            font-size: 13px;
            display: grid;
            grid-template-columns: 28px 1fr;
            gap: 10px;
            color: #1f2937;
            margin-bottom: 24px;
            align-items: center;
        }

        .estimate svg {
            color: #4b5563;
        }

        .cta {
            width: 100%;
            border: none;
            background: #000000;
            color: #ffffff;
            height: 52px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .cta:hover {
            opacity: 0.9;
        }

        .mobile-intro-card .cta {
            height: 60px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            body {
                padding: 40px 20px;
                display: flex;
                align-items: center;
            }
            .intro-card {
                display: none;
            }
            .mobile-intro-card {
                display: block;
                max-width: 100%;
                border-radius: 24px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
                background: #ffffff;
            }
        }

        @media (max-width: 380px) {
            .mobile-intro-card {
                padding: 40px 24px;
            }
            .mobile-intro-card h1 {
                font-size: 34px;
            }
        }
    </style>
</head>
<body>
    <div class="intro-shell">
        <!-- Desktop Card -->
        <div class="intro-card">
            <div class="logo-wrap">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="Bricks Studio logo">
                <div class="eyebrow">Studio</div>
            </div>

            <h1>Complete Your Profile</h1>
            <p class="lead">One-time setup to get you started</p>

            <div class="info-box">
                <div class="info-ico">i</div>
                <div class="info-text">
                    <h3>Important Notice</h3>
                    <p>This is a one-time profile setup. <br>You will provide basic information, body measurements, and verification documents. Once done, you will not need to go through this process again.</p>
                </div>
            </div>

            <div class="section-title">What you'll need:</div>
            <ul class="needs-list">
                <li class="needs-item">
                    <span class="badge-num">1</span>
                    <span class="needs-text">
                        <strong>Personal Information</strong>
                        <span>Name, date of birth, nationality, and contact details</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">2</span>
                    <span class="needs-text">
                        <strong>Physical Attributes</strong>
                        <span>Height, weight, gender, hair color, eye color, skin tone, tattoos, and piercings</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">3</span>
                    <span class="needs-text">
                        <strong>Body Measurements</strong>
                        <span>T-shirt size, dress size (if applicable), and shoe size</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">4</span>
                    <span class="needs-text">
                        <strong>ID Verification</strong>
                        <span>Upload your government-issued ID (Civil ID or Passport)</span>
                    </span>
                </li>
                <li class="needs-item">
                    <span class="badge-num">5</span>
                    <span class="needs-text">
                        <strong>Photos & Video</strong>
                        <span>Upload additional photos and a short profile video</span>
                    </span>
                </li>
            </ul>

            <div class="estimate">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <div><strong>Estimated time: 5-10 minutes</strong><br><span style="color:#4b5563;">Make sure you have all required information ready</span></div>
            </div>

            <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                Get Started
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>
        </div>

        <!-- Mobile Card -->
        <div class="mobile-intro-card">
            <div class="mobile-header">
                <div class="logo-wrap">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Logo" style="width: 140px;">
                </div>
                
                <div class="lang-toggle">
                    <button type="button" class="lang-btn active" data-lang="en">English</button>
                    <button type="button" class="lang-btn" data-lang="ar">عربي</button>
                </div>
            </div>

            <!-- English Content -->
            <div id="mobile-content-en">
                <h1>Complete Your Profile</h1>
                <p class="lead">One-time setup to get you started</p>

                <div class="info-box">
                    <div class="info-ico">i</div>
                    <div class="info-text">
                        <h3>Important Notice</h3>
                        <p>This is a one-time profile setup. Once done, you will not need to go through this process again.</p>
                    </div>
                </div>

                <div class="section-title">What you'll need:</div>
                <ul class="needs-list">
                    <li class="needs-item">
                        <span class="badge-num">1</span>
                        <span class="needs-text">
                            <strong>Personal Information</strong>
                            <span>Name, nationality, contact details</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num">2</span>
                        <span class="needs-text">
                            <strong>Physical Attributes</strong>
                            <span>Height, weight, and features</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num">3</span>
                        <span class="needs-text">
                            <strong>Body Measurements</strong>
                            <span>T-shirt, dress, and shoe sizes</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num">4</span>
                        <span class="needs-text">
                            <strong>ID Verification</strong>
                            <span>Civil ID or Passport</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num">5</span>
                        <span class="needs-text">
                            <strong>Photos & Video</strong>
                            <span>Short profile video</span>
                        </span>
                    </li>
                </ul>

                <div class="estimate">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <div><strong>Estimated time: 5-10 minutes</strong></div>
                </div>

                <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                    Continue
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>

            <!-- Arabic Content -->
            <div id="mobile-content-ar" style="display: none; direction: rtl; text-align: right;">
                <h1 style="font-family: 'Arimo', sans-serif;">أكمل ملفك الشخصي</h1>
                <p class="lead" style="font-family: 'Arimo', sans-serif;">إعداد لمرة واحدة للبدء</p>

                <div class="info-box">
                    <div class="info-ico">i</div>
                    <div class="info-text">
                        <h3 style="font-family: 'Arimo', sans-serif;">ملاحظة هامة</h3>
                        <p style="font-family: 'Arimo', sans-serif;">هذا إعداد لمرة واحدة للملف الشخصي. بمجرد الانتهاء، لن تحتاج إلى القيام بذلك مرة أخرى.</p>
                    </div>
                </div>

                <div class="section-title" style="font-family: 'Arimo', sans-serif;">ماذا ستحتاج:</div>
                <ul class="needs-list">
                    <li class="needs-item">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">1</span>
                        <span class="needs-text">
                            <strong style="font-family: 'Arimo', sans-serif;">معلومات شخصية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">الاسم، الجنسية، تفاصيل الاتصال</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">2</span>
                        <span class="needs-text">
                            <strong style="font-family: 'Arimo', sans-serif;">الصفات البدنية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">الطول، الوزن، والمواصفات</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">3</span>
                        <span class="needs-text">
                            <strong style="font-family: 'Arimo', sans-serif;">قياسات الجسم</strong>
                            <span style="font-family: 'Arimo', sans-serif;">مقاسات الملابس، الفستان، والحذاء</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">4</span>
                        <span class="needs-text">
                            <strong style="font-family: 'Arimo', sans-serif;">التحقق من الهوية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">البطاقة المدنية أو جواز السفر</span>
                        </span>
                    </li>
                    <li class="needs-item">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">5</span>
                        <span class="needs-text">
                            <strong style="font-family: 'Arimo', sans-serif;">الصور والفيديو</strong>
                            <span style="font-family: 'Arimo', sans-serif;">فيديو تعريفي قصير</span>
                        </span>
                    </li>
                </ul>

                <div class="estimate" style="grid-template-columns: 28px 1fr;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <div><strong style="font-family: 'Arimo', sans-serif;">الوقت المقدر: 5-10 دقائق</strong></div>
                </div>

                <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                    <span style="font-family: 'Arimo', sans-serif;">استمرار</span>
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: scaleX(-1);">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const langBtns = document.querySelectorAll('.lang-btn');
            const contentEn = document.getElementById('mobile-content-en');
            const contentAr = document.getElementById('mobile-content-ar');
            const mobileCard = document.querySelector('.mobile-intro-card');

            langBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    langBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    const isAr = btn.dataset.lang === 'ar';
                    
                    if (isAr) {
                        contentEn.style.display = 'none';
                        contentAr.style.display = 'block';
                        mobileCard.style.direction = 'rtl';
                        mobileCard.style.textAlign = 'right';
                        document.querySelector('.mobile-header').style.flexDirection = 'row-reverse';
                        document.querySelector('.mobile-header .logo-wrap').style.textAlign = 'right';
                    } else {
                        contentEn.style.display = 'block';
                        contentAr.style.display = 'none';
                        mobileCard.style.direction = 'ltr';
                        mobileCard.style.textAlign = 'left';
                        document.querySelector('.mobile-header').style.flexDirection = 'row';
                        document.querySelector('.mobile-header .logo-wrap').style.textAlign = 'left';
                    }
                });
            });
        });
    </script>
</body>
</html>
