<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bricks Studio - Terms of Use</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700;800&family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
            font-family: 'Arimo', sans-serif;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 100px 20px 40px;
        }
        .terms-card {
            width: 100%;
            max-width: 700px;
            background: #ffffff;
            border-radius: 20px;

            padding: 30px 26px 30px;
            text-align: center;
        }

        /* Mobile Card Styles */
        .mobile-terms-card {
            display: none;
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 34px; /* Increased side padding */
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
            box-sizing: border-box;
            overflow: hidden;
        }

        .mobile-terms-card * {
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

        .mobile-terms-card h1 {
            font-family: 'Space Grotesk', sans-serif;
            font-weight: 800;
            font-size: 38px;
            line-height: 1.1;
            margin: 0 0 20px;
            color: #1a1a1a;
            letter-spacing: -0.02em;
            text-align: left;
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

        .mobile-terms-scroll {
            height: 380px;
            overflow-y: auto;
            border: 1px solid #f3f4f6;
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 24px;
            background: #fafafa;
            scrollbar-width: thin;
            scrollbar-color: #e6e6e6 transparent;
        }

        .mobile-terms-scroll::-webkit-scrollbar {
            width: 4px;
        }
        .mobile-terms-scroll::-webkit-scrollbar-thumb {
            background: #e6e6e6;
            border-radius: 2px;
        }

        .mobile-terms-scroll ul {
            padding-left: 20px;
            margin: 0;
            list-style-type: disc;
        }

        .mobile-terms-scroll li {
            margin-bottom: 12px;
            font-size: 13.5px;
            line-height: 1.5;
            color: #1f2937;
        }

        .mobile-terms-scroll.is-ar {
            direction: rtl;
            text-align: right;
        }

        .mobile-terms-scroll.is-ar ul {
            padding-left: 0;
            padding-right: 20px;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 14px;
        }

        .logo-wrap img {
            width: 140px;
            height: auto;
        }

        .terms-card h1 {
            font-family: 'Arimo', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin: 8px 0 4px;
            color: #212121;
            text-align: center;
        }

        .sub {
            font-family: 'Arimo', sans-serif;
            font-size: 13px;
            color: #586272;
            text-align: center;
            margin: 0 0 16px;
        }

        .terms-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }

        .terms-pane {
            border: 1px solid #edf0f4;
            background: #f8fafc;
            border-radius: 12px;
            padding: 14px;
            max-height: 420px;
            overflow: auto;
            scrollbar-width: none;
            -ms-overflow-style: none;
            text-align: left;
        }

        .terms-pane::-webkit-scrollbar {
            display: none;
        }

        .terms-pane h2 {
            font-size: 13px;
            font-weight: 800;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #111827;
            margin: 0 0 10px;
        }

        .terms-text p, .terms-text li {
            margin: 0 0 10px;
            font-size: 13px;
            line-height: 1.6;
            color: #1f2937;
        }

        .terms-text ul {
            margin: 0;
            padding-left: 20px;
            list-style-type: disc;
        }

        .terms-pane.is-ar .terms-text ul {
            padding-right: 20px;
            padding-left: 0;
        }

        .terms-pane.is-ar {
            direction: rtl;
            text-align: right;
            font-family: 'Arimo', sans-serif;
        }

        .divider {
            height: 1px;
            background: #eef1f5;
            margin: 16px 0 14px;
        }

        .accept-row {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            font-size: 14px;
            color: #1f2937;
            margin-bottom: 16px;
            cursor: pointer;
            text-align: left;
        }

        .accept-row input {
            margin-top: 3px;
            width: 20px;
            height: 20px;
            border-radius: 6px;
            accent-color: #111111;
            flex-shrink: 0;
        }

        .cta {
            width: 100%;
            height: 60px;
            border: none;
            background: #1a1a1a;
            color: #ffffff;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .cta:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        .desktop-footer-cta {
            height: 52px;
            max-width: 300px;
            margin: 0 auto;
        }

        @media (max-width: 768px) {
            body {
                padding: 40px 20px;
                align-items: center;
            }
            .terms-card {
                display: none;
            }
            .mobile-terms-card {
                display: block;
            }
        }

        @media (max-width: 400px) {
            body {
                padding: 20px 12px;
            }
            .mobile-terms-card {
                padding: 32px 24px;
                max-width: 100%;
            }
            .mobile-terms-card h1 {
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <!-- Desktop Card -->
    <div class="terms-card">
        <div class="logo-wrap">
            <img src="{{ asset('images/bricks_logo.png') }}" alt="Bricks Studio logo">
        </div>
        <h1>Privacy Policy & Terms of Use</h1>
        <p class="sub">Please read and accept to continue</p>

        <div class="terms-grid">
            <div class="terms-pane">
                <h2>English</h2>
                <div class="terms-text" id="terms-en-desktop">
                    <ul>
                        <li>This document applies to Bricks Community, a digital platform owned and operated by Bricks Marketing LLC, a Limited Liability Company registered in the State of Wyoming, United States of America (the "Platform", "we", or "us").</li>
                        <li>By accessing, registering on, or using the Platform or any of its services, you acknowledge and agree to be bound by this Privacy Policy and Terms of Use, including any future updates or modifications, without the need for prior notice.</li>
                        <li>The Platform reserves the right to amend, update, or modify this document at any time. Continued use of the Platform constitutes acceptance of such changes.</li>
                        <li>The Platform may not be used by individuals who are below the legal age. Where an account relates to an individual below the legal age, the creation and use of such account must be performed exclusively by a parent or legal guardian.</li>
                        <li>This document applies to all visitors and users of the Platform. It applies to all data collected through the Platform, registration forms, communications, or any related services.</li>
                        <li>The Platform may collect information provided directly by users, including but not limited to name, nationality, contact details, photographs, video recordings, physical characteristics, and experience.</li>
                        <li>User data is retained for as long as the account remains active. Upon a user's request to delete an account, the data will be permanently deleted after an internal retention period.</li>
                        <li>Collected data is used for operating the Platform, matching performers with advertising opportunities, enabling communication, and complying with legal obligations.</li>
                        <li>Users acknowledge and agree that the Platform may share certain data with third parties including advertisers, payment providers, or governmental authorities where required by law.</li>
                        <li>The Platform uses cookies and similar technologies to enhance user experience and analyze performance.</li>
                        <li>Users represent and warrant that all submitted content is accurate and authentic. Users bear full legal responsibility for any false or misleading content.</li>
                        <li>Performers grant the Platform exclusive and unrestricted rights to use all submitted images and videos, including sublicensing to advertisers.</li>
                    </ul>
                </div>
            </div>

            <div class="terms-pane is-ar">
                <h2>العربية</h2>
                <div class="terms-text" id="terms-ar-desktop">
                    <ul>
                        <li>تشير هذه الوثيقة إلى منصة Bricks Community، وهي منصة رقمية مملوكة وتدار من قبل شركة Bricks Marketing LLC في الولايات المتحدة الأمريكية.</li>
                        <li>باستخدامك للمنصة أو تسجيلك فيها، فإنك تقر بموافقتك الكاملة على جميع ما ورد في هذه الوثيقة، وعلى أي تعديلات مستقبلية تطرأ عليها.</li>
                        <li>تحتفظ المنصة بحقها الكامل في تعديل أو تحديث هذه السياسة في أي وقت، ويُعد استمرار استخدام المنصة قبولاً صريحاً بذلك.</li>
                        <li>لا يُسمح باستخدام المنصة لمن هم دون السن القانوني. وفي حال كان الحساب متعلقاً بقاصر، يجب إنشاؤه من قبل ولي الأمر أو الوصي القانوني.</li>
                        <li>تنطبق هذه السياسة على جميع زوار المنصة والمستخدمين المسجلين، وتشمل أي بيانات يتم جمعها عبر المنصة أو نماذج التسجيل.</li>
                        <li>قد تقوم المنصة بجمع بيانات يقدمها المستخدم بشكل مباشر، مثل الاسم والجنسية ووسائل التواصل والصور والمواصفات والخبرات.</li>
                        <li>يتم الاحتفاظ بالبيانات طالما كان الحساب نشطاً، وتُحذف نهائياً بعد انتهاء فترة الاحتفاظ الداخلية عند طلب الحذف.</li>
                        <li>تُستخدم البيانات لأغراض تشغيل المنصة ومطابقة الممثلين مع الفرص الإعلانية والامتثال للالتزامات القانونية والتنظيمية.</li>
                        <li>يوافق المستخدم على مشاركة بعض بياناته مع أطراف ثالثة مثل المعلنين أو مزودي خدمات الدفع أو الجهات الرسمية إذا تطلب القانون ذلك.</li>
                        <li>تستخدم المنصة ملفات تعريف الارتباط وتقنيات مشابهة لتحسين تجربة المستخدم وتحليل الأداء.</li>
                        <li>يقر المستخدم بأن جميع البيانات والمحتوى الذي يقدمه صحيح ودقيق، ويتحمل المسؤولية القانونية الكاملة عن أي محتوى مضلل.</li>
                        <li>يمنح المستخدم المنصة حقوقاً حصرية وغير محدودة لاستخدام الصور والمقاطع، بما في ذلك منح هذه الحقوق للمعلنين.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="divider"></div>

        <form method="POST" action="{{ route('talent.onboarding.terms.accept') }}" class="termsForm">
            @csrf
            <label class="accept-row" style="justify-content: center;">
                <input type="checkbox" name="accept_terms" value="1" class="accept_terms" required>
                <span>
                    <span style="display: block;">I have read and agree to the Privacy Policy & Terms of Use.</span>
                    <span class="ar-text" style="color:#4b5563; display: block; margin-top: 4px; font-family: 'Arimo', sans-serif;">أقر بأنني قرأت ووافقت على سياسة الخصوصية وشروط الاستخدام.</span>
                </span>
            </label>
            <div class="actions">
                <button type="submit" class="cta continueBtn desktop-footer-cta" disabled>
                    Continue
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12h14" />
                        <path d="M13 6l6 6-6 6" />
                    </svg>
                </button>
            </div>
        </form>
    </div>

    <!-- Mobile Card -->
    <div class="mobile-terms-card">
        <div class="mobile-header">
            <div class="logo-wrap" style="text-align: left;">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="Bricks logo">
            </div>

            <div class="lang-toggle">
                <button type="button" class="lang-btn active" data-lang="en">English</button>
                <button type="button" class="lang-btn" data-lang="ar">عربي</button>
            </div>
        </div>

        <h1 id="mobile-title-en">Privacy Policy &<br>Terms of Use:</h1>
        <h1 id="mobile-title-ar" style="display: none; direction: rtl; text-align: right;">سياسة الخصوصية و<br>شروط الاستخدام:</h1>

        <div class="mobile-terms-scroll" id="mobile-content-en">
            <ul>
                <li>This document applies to Bricks Community, a digital platform owned and operated by Bricks Marketing LLC, a Limited Liability Company registered in the State of Wyoming, United States of America (the "Platform", "we", or "us").</li>
                <li>By accessing, registering on, or using the Platform or any of its services, you acknowledge and agree to be bound by this Privacy Policy and Terms of Use, including any future updates or modifications, without the need for prior notice.</li>
                <li>The Platform reserves the right to amend, update, or modify this document at any time. Continued use of the Platform constitutes acceptance of such changes.</li>
                <li>The Platform may not be used by individuals who are below the legal age. Where an account relates to an individual below the legal age, the creation and use of such account must be performed exclusively by a parent or legal guardian.</li>
                <li>This document applies to all visitors and users of the Platform. It applies to all data collected through the Platform, registration forms, communications, or any related services.</li>
                <li>The Platform may collect information provided directly by users, including name, nationality, contact details, photographs, video recordings, physical characteristics, and experience.</li>
                <li>User data is retained for as long as the account remains active. Upon a user's request to delete an account, the data will be permanently deleted after an internal retention period.</li>
                <li>Collected data is used for operating the Platform, matches performers with advertising opportunities, enabling communication, and complying with legal obligations.</li>
                <li>Users acknowledge that the Platform may share certain data with third parties including advertisers, payment providers, or governmental authorities where required by law.</li>
                <li>Users represent that all submitted content is accurate and authentic. Users bear full legal responsibility for any false or misleading content.</li>
                <li>Performers grant the Platform exclusive and unrestricted rights to use all submitted images and videos, including sublicensing to advertisers.</li>
                <li>The Platform operates solely as a digital intermediary and does not act as an employer or production company.</li>
            </ul>
        </div>

        <div class="mobile-terms-scroll is-ar" id="mobile-content-ar" style="display: none;">
            <ul>
                <li>تشير هذه الوثيقة إلى منصة Bricks Community، وهي منصة رقمية مملوكة وتدار من قبل شركة Bricks Marketing LLC في الولايات المتحدة الأمريكية.</li>
                <li>باستخدامك للمنصة، فإنك تقر بموافقتك الكاملة على جميع ما ورد في هذه الوثيقة وعلى أي تعديلات مستقبلية تطرأ عليها.</li>
                <li>تحتفظ المنصة بحقها في تعديل أو تحديث هذه السياسة في أي وقت، ويُعد استمرار الاستخدام قبولاً صريحاً بذلك.</li>
                <li>لا يُسمح باستخدام المنصة لمن هم دون السن القانوني، ويجب إنشاء الحساب من قبل ولي الأمر أو الوصي القانوني.</li>
                <li>تنطبق هذه السياسة على جميع الزوار والمستخدمين وتشمل أي بيانات يتم جمعها عبر المنصة.</li>
                <li>تقوم المنصة بجمع بيانات يقدمها المستخدم مباشرة مثل الاسم والجنسية والصور والمواصفات والخبرات.</li>
                <li>يتم الاحتفاظ بالبيانات طالما كان الحساب نشطاً، وتُحذف نهائياً بعد انتهاء فترة الاحتفاظ عند طلب الحذف.</li>
                <li>تُستخدم البيانات لتشغيل المنصة ومطابقة المواهب مع الفرص الإعلانية وتحسين الخدمات.</li>
                <li>يوافق المستخدم على مشاركة بعض بياناته مع المعلنين أو مزودي الدفع أو الجهات الرسمية عند الضرورة.</li>
                <li>يقر المستخدم بأن جميع البيانات والصور المقدمة صحيحة، ويتحمل المسؤولية القانونية الكاملة عن أي محتوى مضلل.</li>
                <li>يمنح المستخدم المنصة حقوقاً حصرية لاستخدام الصور والمحتوى، بما في ذلك منح هذه الحقوق للمعلنين.</li>
                <li>تعمل المنصة كوسيط إلكتروني فقط، ولا تُعد جهة توظيف ولا تضمن إبرام أي تعاقد أو تحقيق أي دخل.</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('talent.onboarding.terms.accept') }}" class="termsForm">
            @csrf
            <label class="accept-row">
                <input type="checkbox" name="accept_terms" value="1" class="accept_terms" required>
                <span id="mobile-label-en" style="font-family: 'Space Grotesk', sans-serif;">I have read and agree to the Privacy Policy & Terms of Use.</span>
                <span id="mobile-label-ar" style="font-family: 'Arimo', sans-serif; display: none; direction: rtl; text-align: right; flex: 1;">أقر بأنني قرأت ووافقت على سياسة الخصوصية وشروط الاستخدام.</span>
            </label>
            <button type="submit" class="cta continueBtn" disabled>
                <span id="mobile-btn-text-en">Continue</span>
                <span id="mobile-btn-text-ar" style="display: none;">استمرار</span>
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14" />
                    <path d="M13 6l6 6-6 6" />
                </svg>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Function to sync checkbox and button for any form
            function setupTermsForm(form) {
                const cb = form.querySelector('.accept_terms');
                const btn = form.querySelector('.continueBtn');

                function sync() {
                    btn.disabled = !cb.checked;
                }
                cb.addEventListener('change', sync);
                sync();

                form.addEventListener('submit', function (e) {
                    if (!cb.checked) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            text: 'Please accept the terms to continue.',
                            confirmButtonColor: '#000000',
                        });
                    }
                });
            }

            // Setup all terms forms (desktop and mobile)
            document.querySelectorAll('.termsForm').forEach(setupTermsForm);

            // Mobile Language Toggle Logic
            const langBtns = document.querySelectorAll('.lang-btn');
            const contentEn = document.getElementById('mobile-content-en');
            const contentAr = document.getElementById('mobile-content-ar');

            const labelEn = document.getElementById('mobile-label-en');
            const labelAr = document.getElementById('mobile-label-ar');
            const btnEn = document.getElementById('mobile-btn-text-en');
            const btnAr = document.getElementById('mobile-btn-text-ar');
            const titleEn = document.getElementById('mobile-title-en');
            const titleAr = document.getElementById('mobile-title-ar');

            langBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    langBtns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');

                    if (btn.dataset.lang === 'ar') {
                        contentEn.style.display = 'none';
                        contentAr.style.display = 'block';
                        labelEn.style.display = 'none';
                        labelAr.style.display = 'block';
                        btnEn.style.display = 'none';
                        btnAr.style.display = 'inline';
                        titleEn.style.display = 'none';
                        titleAr.style.display = 'block';
                    } else {
                        contentEn.style.display = 'block';
                        contentAr.style.display = 'none';
                        labelEn.style.display = 'block';
                        labelAr.style.display = 'none';
                        btnEn.style.display = 'inline';
                        btnAr.style.display = 'none';
                        titleEn.style.display = 'block';
                        titleAr.style.display = 'none';
                    }
                });
            });
        });
    </script>
</body>
</html>
