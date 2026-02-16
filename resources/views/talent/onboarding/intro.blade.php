@extends('layouts.app')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Arimo:wght@400;500;600&family=Space+Grotesk:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Space Grotesk', sans-serif;
            color: #1f1f1f;
        }

        .intro-shell {
            min-height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px 36px;
        }

        .intro-card {
            width: 100%;
            max-width: 506px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            padding: 24px 22px 22px;
            position: relative;
        }

        /* Mobile Card Styles */
        .mobile-intro-card {
            display: none;
            width: 100%;
            max-width: 420px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
            min-height: 600px;
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

        .mobile-intro-card h1 {
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

        .mobile-intro-card .lead {
            font-family: 'Space Grotesk', sans-serif;
            font-size: 13.5px;
            line-height: 1.5;
            color: #6a7682;
            margin: 0 0 30px;
            letter-spacing: -0.01em;
        }

        .mobile-intro-card .info-box {
            display: flex;
            gap: 12px;
            padding: 16px;
            background: #eef3fb;
            border: 1px solid #d7e2f5;
            border-radius: 14px;
            margin-bottom: 24px;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 16px;
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
            font-size: 22px;
            font-weight: 600;
            margin: 8px 0 6px;
            color: #212121;
            text-align: center;
            font-family: 'Arimo', sans-serif;
        }

        .lead {
            font-family: 'Arimo', sans-serif;
            font-size: 13px;
            color: #586272;
            text-align: center;
            margin: 0 0 16px;
        }

        .info-box {
            display: flex;
            gap: 12px;
            padding: 14px;
            background: #eef3fb;
            border: 1px solid #d7e2f5;
            border-radius: 12px;
            margin-bottom: 16px;
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
            margin: 0 0 16px;
            display: grid;
            gap: 12px;
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
            padding: 14px 14px 12px;
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

        .consent {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 24px;
        }

        .consent input {
            margin-top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 6px;
            accent-color: #111111;
            border: 1px solid #111111;
        }

        .cta {
            width: 100%;
            border: none;
            background: black;
            color: #ffffff;
            height: 52px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            cursor: pointer;
        }

        .mobile-intro-card .cta {
            height: 60px;
            border-radius: 12px;
            font-size: 16px;
        }

        @media (max-width: 768px) {
            .intro-card {
                display: none;
            }
            .mobile-intro-card {
                display: block;
            }
        }
    </style>

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

            <div class="estimate" style="margin-bottom: 14px;">
                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <polyline points="12 6 12 12 16 14"></polyline>
                </svg>
                <div><strong>Estimated time: 5-10 minutes</strong><br><span style="color:#4b5563;">Make sure you have all required information ready</span></div>
            </div>

            <label class="consent" style="margin-bottom: 12px; font-family: 'Arimo', sans-serif;">
                <input type="checkbox" checked aria-label="Privacy acceptance">
                <span>Any privacy acceptance text</span>
            </label>

            <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                Get Started
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
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

            <div id="mobile-content-en">
                <h1>Complete<br>Your Profile</h1>
                <p class="lead" style="text-align: start;">One-time setup to get you started</p>

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
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>

            <div id="mobile-content-ar" style="display: none; direction: rtl; text-align: right;">
                <h1 style="font-family: 'Arimo', sans-serif; text-align: right;">أكمل<br>ملفك الشخصي</h1>
                <p class="lead" style="font-family: 'Arimo', sans-serif; text-align: right;">إعداد لمرة واحدة للبدء</p>

                <div class="info-box">
                    <div class="info-ico">i</div>
                    <div class="info-text">
                        <h3 style="font-family: 'Arimo', sans-serif;">ملاحظة هامة</h3>
                        <p style="font-family: 'Arimo', sans-serif;">هذا إعداد لمرة واحدة للملف الشخصي. بمجرد الانتهاء، لن تحتاج إلى القيام بذلك مرة أخرى.</p>
                    </div>
                </div>

                <div class="section-title" style="font-family: 'Arimo', sans-serif;">ماذا ستحتاج:</div>
                <ul class="needs-list">
                    <li class="needs-item" style="grid-template-columns: 32px 1fr;">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">1</span>
                        <span class="needs-text" style="text-align: right;">
                            <strong style="font-family: 'Arimo', sans-serif;">معلومات شخصية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">الاسم، الجنسية، تفاصيل الاتصال</span>
                        </span>
                    </li>
                    <li class="needs-item" style="grid-template-columns: 32px 1fr;">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">2</span>
                        <span class="needs-text" style="text-align: right;">
                            <strong style="font-family: 'Arimo', sans-serif;">الصفات البدنية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">الطول، الوزن، والمواصفات</span>
                        </span>
                    </li>
                    <li class="needs-item" style="grid-template-columns: 32px 1fr;">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">3</span>
                        <span class="needs-text" style="text-align: right;">
                            <strong style="font-family: 'Arimo', sans-serif;">قياسات الجسم</strong>
                            <span style="font-family: 'Arimo', sans-serif;">مقاسات الملابس، الفستان، والحذاء</span>
                        </span>
                    </li>
                    <li class="needs-item" style="grid-template-columns: 32px 1fr;">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">4</span>
                        <span class="needs-text" style="text-align: right;">
                            <strong style="font-family: 'Arimo', sans-serif;">التحقق من الهوية</strong>
                            <span style="font-family: 'Arimo', sans-serif;">البطاقة المدنية أو جواز السفر</span>
                        </span>
                    </li>
                    <li class="needs-item" style="grid-template-columns: 32px 1fr;">
                        <span class="badge-num" style="font-family: 'Space Grotesk', sans-serif;">5</span>
                        <span class="needs-text" style="text-align: right;">
                            <strong style="font-family: 'Arimo', sans-serif;">الصور والفيديو</strong>
                            <span style="font-family: 'Arimo', sans-serif;">فيديو تعريفي قصير</span>
                        </span>
                    </li>
                </ul>

                <div class="estimate" style="direction: rtl; grid-template-columns: 28px 1fr;">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    <div style="text-align: right;"><strong style="font-family: 'Arimo', sans-serif;">الوقت المقدر: 5-10 دقائق</strong></div>
                </div>

                <button type="button" class="cta" onclick="window.location='{{ $startRoute ?? route('talent.onboarding.show', 'profile') }}'">
                    <span style="font-family: 'Arimo', sans-serif;">استمرار</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="transform: scaleX(-1);">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const langBtns = document.querySelectorAll('.lang-btn');
            const contentEn = document.getElementById('mobile-content-en');
            const contentAr = document.getElementById('mobile-content-ar');

            langBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    langBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    
                    if (btn.dataset.lang === 'ar') {
                        contentEn.style.display = 'none';
                        contentAr.style.display = 'block';
                    } else {
                        contentEn.style.display = 'block';
                        contentAr.style.display = 'none';
                    }
                });
            });
        });
    </script>
@endsection
