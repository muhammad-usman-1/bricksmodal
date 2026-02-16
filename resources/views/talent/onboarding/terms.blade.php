@extends('layouts.app')

@section('content')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@600;700;800&family=Arimo:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Space Grotesk', sans-serif;
            color: #1f1f1f;
        }

        .terms-shell {
            min-height: calc(100vh - 40px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 28px 16px 36px;
        }

        .terms-card {
            width: 100%;
            max-width: 920px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 20px 44px rgba(18, 33, 61, 0.12);
            padding: 22px 22px 18px;
            position: relative;
        }

        /* Mobile Card Styles */
        .mobile-terms-card {
            display: none;
            width: 100%;
            max-width: 480px;
            background: #ffffff;
            border-radius: 24px;
            padding: 40px 28px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            text-align: left;
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
            scrollbar-width: none;
            -ms-overflow-style: none;
        }

        .mobile-terms-scroll::-webkit-scrollbar {
            display: none;
        }

        .mobile-terms-scroll ul {
            padding-left: 20px;
            margin: 0;
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
            gap: 10px;
            font-size: 13px;
            color: #1f2937;
            margin-bottom: 12px;
            cursor: pointer;
        }

        .accept-row input {
            margin-top: 2px;
            width: 18px;
            height: 18px;
            border-radius: 6px;
            accent-color: #111111;
        }

        .cta {
            width: 100%;
            height: 52px;
            border: none;
            background: black;
            color: #ffffff;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
        }

        .cta:disabled {
            opacity: 0.55;
            cursor: not-allowed;
        }

        @media (max-width: 768px) {
            .terms-card {
                display: none;
            }
            .mobile-terms-card {
                display: block;
            }
            .terms-shell {
                padding: 40px 20px;
            }
        }
    </style>

    <div class="terms-shell">
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
                            <li>The Platform reserves the right to amend, update, or modify this document at any time. Continued use of the Platform constitutes acceptance of such changes. It is the sole responsibility of the user to review this document periodically, and the Platform bears no obligation to provide notice of updates.</li>
                            <li>The Platform may not be used by individuals who are below the legal age. Where an account relates to an individual below the legal age, the creation and use of such account must be performed exclusively by a parent or legal guardian. By doing so, the parent or legal guardian expressly acknowledges full legal responsibility for the account, all activities conducted through it, and all content submitted on behalf of the minor, and confirms compliance with all applicable child protection laws and regulations. The Platform bears no liability arising from the creation or use of an account on behalf of a minor and reserves the right to suspend or terminate such accounts at its sole discretion.</li>
                            <li>This document applies to all visitors and users of the Platform, including registered users, performers, talents, advertisers, and any other parties who access or utilize the Platform's services. It applies to all data collected through the Platform, registration forms, communications, or any related services.</li>
                            <li>The Platform may collect information provided directly by users, including but not limited to name, date of birth, gender, nationality, contact details, photographs, video recordings, physical characteristics, experience, social media accounts, and any other information required to provide the service. In later stages, financial or banking information may be requested where paid advertising services are involved.</li>
                            <li>Certain information may also be collected automatically, such as IP address, device type, browser type, operating system, browsing activity within the Platform, and login and logout timestamps, for operational, technical, and analytical purposes.</li>
                            <li>User data is retained for as long as the account remains active. Upon a user's request to delete an account, the account will be deactivated, and data will be permanently deleted following the Platform's internal retention period, which may extend for a defined period (such as twelve months). The Platform may retain certain data where required for legal, regulatory, accounting, or dispute resolution purposes.</li>
                            <li>Collected data is used for operating and managing the Platform, administering user accounts, matching performers with advertising opportunities, enabling communication between parties, improving services, collecting Platform commissions, sending notifications, complying with legal obligations, and for any other purpose reasonably related to the provision of the services.</li>
                            <li>Users acknowledge and agree that the Platform may share certain data, as deemed necessary, with third parties including advertisers, payment service providers, technical service providers, or governmental authorities where required by law, court order, or official request.</li>
                            <li>The Platform uses cookies and similar technologies to enhance user experience and analyze performance. Continued use of the Platform constitutes consent to such use.</li>
                            <li>Users represent and warrant that all information, images, videos, and content submitted or uploaded to the Platform are accurate, authentic, and up to date. Users bear full legal responsibility for any false, misleading, altered, fabricated, or impersonated content, whether intentional or otherwise. The Platform reserves the right to verify content at any time and to take any action it deems appropriate without liability.</li>
                            <li>Performers and talents expressly grant the Platform exclusive and unrestricted rights to use all images, videos, and content submitted through the Platform, including the right to sublicense such content to advertisers or relevant third parties, without further consent, unless otherwise agreed in writing under specific exclusivity arrangements.</li>
                            <li>The Platform may now or in the future engage with agencies or entities that manage or represent talents. Any agency creating or managing accounts on behalf of talents represents that it holds valid legal authorization to do so and assumes full responsibility for the accuracy of all submitted data and content, as well as compliance with this document on behalf of its represented talents. The Platform bears no responsibility for any internal disputes or contractual relationships between agencies and their talents.</li>
                            <li>The Platform operates solely as a digital intermediary and does not act as an employer, production company, or guarantor of engagements, income, or outcomes. The Platform does not guarantee the conclusion of any agreement or the success of any advertising opportunity and bears no liability for failed negotiations or disputes between parties.</li>
                            <li>Users, particularly performers and talents, agree to act professionally, follow applicable instructions and requirements, and maintain the confidentiality of any information related to the Platform, campaigns, or other parties. The Platform reserves the right to take any measures it deems appropriate in the event of non-compliance, including modification or cancellation of participation, financial or administrative adjustments, or other actions deemed necessary, without incurring liability.</li>
                            <li>The Platform is entitled to collect a commission calculated as a percentage of the gross value of any advertising engagement facilitated through the Platform. Commissions may be deducted at source or collected through approved payment methods and are non-refundable once the engagement has been executed. Any attempt to circumvent the Platform or avoid commission payments may result in appropriate action at the Platform's discretion.</li>
                            <li>The Platform bears no responsibility for any taxes, duties, or governmental charges applicable to users. Each user is solely responsible for their own tax and regulatory obligations.</li>
                            <li>The Platform may contain links to external websites or services and bears no responsibility for their content, policies, or practices.</li>
                            <li>To the fullest extent permitted by law, the Platform disclaims all liability for any direct, indirect, incidental, consequential, or special damages arising from the use of the Platform or reliance on any content or services provided through it. The Platform's role is limited to providing technical and organizational intermediation only.</li>
                            <li>The Platform shall not be liable for delays or failures resulting from events beyond its reasonable control, including technical failures, governmental actions, natural disasters, or force majeure events.</li>
                            <li>Users consent to receiving communications and notifications via email, text messages, in-platform notifications, or any other communication channels used by the Platform.</li>
                            <li>This document shall be governed by and construed in accordance with the laws of the State of Wyoming, United States of America. The Platform reserves the exclusive right to determine the appropriate jurisdiction, venue, or alternative dispute resolution mechanism for any dispute, at its sole discretion.</li>
                        </ul>
                    </div>
                </div>

                <div class="terms-pane is-ar">
                    <h2>العربية</h2>
                    <div class="terms-text" id="terms-ar-desktop">
                        <ul>
                            <li>تشير هذه الوثيقة إلى منصة Bricks Community، وهي منصة رقمية مملوكة وتدار من قبل Bricks Marketing LLC، وهي شركة ذات مسؤولية محدودة مسجلة في ولاية وايومنغ – الولايات المتحدة الأمريكية، ويُشار إليها لاحقاً بـ "المنصة" أو "نحن".</li>
                            <li>باستخدامك للمنصة أو تسجيلك فيها أو الاستفادة من أي من خدماتها، فإنك تقر بموافقتك الكاملة وغير المشروطة على جميع ما ورد في هذه الوثيقة، وعلى أي تعديلات أو تحديثات مستقبلية تطرأ عليها، دون الحاجة إلى إشعار مسبق.</li>
                            <li>تحتفظ المنصة بحقها الكامل في تعديل أو تحديث هذه السياسة في أي وقت، ويُعد استمرار استخدام المنصة قبولاً صريحاً بذلك، وتقع مسؤولية مراجعة هذه الشروط والسياسات بشكل دوري على المستخدم وحده، دون أي مسؤولية على المنصة.</li>
                            <li>لا يُسمح باستخدام المنصة أو التسجيل فيها لمن هم دون السن القانوني. وفي حال كان الحساب متعلقاً بشخص دون السن القانوني، فإن إنشاء الحساب أو استخدام المنصة يتم حصراً من قبل ولي الأمر أو الوصي القانوني، ويُعد ذلك إقراراً صريحاً منه بتحمله المسؤولية القانونية الكاملة عن الحساب وجميع الأنشطة والمحتوى المرتبط به، وبموافقته على جميع الشروط والسياسات نيابةً عن القاصر، وبالتزامه بكافة القوانين والأنظمة المعمول بها ذات الصلة بحماية القُصَّر. ولا تتحمل المنصة أي مسؤولية قانونية ناتجة عن قيام أي شخص بإنشاء أو إدارة حساب لقاصر، وتحتفظ بحقها في تعليق أو حذف الحساب متى رأت ما يستدعي ذلك.</li>
                            <li>تنطبق هذه السياسة على جميع زوار المنصة والمستخدمين المسجلين، سواء كانوا ممثلين أو مؤدين أو معلنين أو أي أطراف أخرى تستفيد من خدمات المنصة، وتشمل أي بيانات يتم جمعها عبر الموقع الإلكتروني أو نماذج التسجيل أو وسائل التواصل أو أي خدمات مرتبطة بالمنصة.</li>
                            <li>قد تقوم المنصة بجمع بيانات يقدمها المستخدم بشكل مباشر، وتشمل على سبيل المثال لا الحصر الاسم وتاريخ الميلاد والجنس والجنسية ووسائل التواصل والصور الشخصية والمقاطع المرئية أو التعريفية والمواصفات الشكلية والخبرات السابقة وحسابات التواصل الاجتماعي وأي معلومات أخرى ضرورية لتقديم الخدمة. كما قد يتم في مراحل لاحقة طلب بيانات مالية أو بنكية في حال تنفيذ أعمال إعلانية مدفوعة.</li>
                            <li>كما يتم جمع بعض البيانات تلقائياً عند استخدام المنصة، مثل عنوان بروتوكول الإنترنت (IP) ونوع الجهاز والمتصفح ونظام التشغيل وسجل التصفح وتاريخ ووقت الدخول والخروج، وذلك لأغراض تشغيلية وتقنية وتحليلية.</li>
                            <li>يتم الاحتفاظ بالبيانات طالما كان الحساب نشطاً. وعند طلب المستخدم حذف حسابه، يتم تعطيله، ويتم الحذف النهائي والدائم للبيانات بعد انتهاء فترة الاحتفاظ الداخلية المعتمدة من المنصة، والتي قد تمتد لفترة زمنية لاحقة (مثل سنة واحدة)، مع احتفاظ المنصة ببعض البيانات إذا تطلب الأمر ذلك لأسباب قانونية أو محاسبية أو تنظيمية أو لتسوية نزاعات محتملة.</li>
                            <li>تُستخدم البيانات التي يتم جمعها لأغراض تشغيل المنصة وإدارة الحسابات ومطابقة الممثلين والمؤدين مع الفرص الإعلانية وتمكين التواصل بين الأطراف وتحسين جودة الخدمات وتحصيل عمولة المنصة وإرسال الإشعارات والتنبيهات والامتثال للالتزامات القانونية والتنظيمية وأي أغراض أخرى مرتبطة بطبيعة تقديم الخدمة.</li>
                            <li>يوافق المستخدم على قيام المنصة بمشاركة بعض بياناته، بالقدر الذي تراه مناسباً، مع أطراف ثالثة مثل المعلنين أو مزودي خدمات الدفع أو شركات الدعم التقني أو الجهات الرسمية إذا طُلب ذلك بموجب القانون أو أمر قضائي أو إجراء رسمي.</li>
                            <li>تستخدم المنصة ملفات تعريف الارتباط (Cookies) وتقنيات مشابهة لتحسين تجربة المستخدم وتحليل الأداء، ويُعد استمرار استخدام المنصة موافقة على ذلك.</li>
                            <li>يقر المستخدم بأن جميع البيانات والصور والمقاطع والمحتوى الذي يقدمه أو يرفعه عبر المنصة يجب أن يكون صحيحاً ودقيقاً وحقيقياً، ويتحمل المسؤولية القانونية الكاملة عن أي محتوى غير صحيح أو مضلل أو معدل أو مفبرك أو منتحل، سواء كان ذلك بقصد أو دون قصد. ويحق للمنصة التحقق من صحة المحتوى في أي وقت واتخاذ ما تراه مناسباً دون أدنى مسؤولية.</li>
                            <li>كما يقر المستخدم، وبالأخص الممثل أو المؤدي، بموافقته على منح المنصة حقوقاً حصرية وغير محدودة لاستخدام الصور والمقاطع المرئية والمحتوى الذي قام بتقديمه، بما في ذلك منح هذه الحقوق للمعلنين أو أطراف أخرى ذات صلة، دون الرجوع إليه، ما لم يوجد اتفاق مكتوب بخلاف ذلك في حالات محددة.</li>
                            <li>يجوز للمنصة حالياً أو مستقبلاً التعامل مع جهات أو شركات تقوم بإدارة أو تمثيل مواهب أو مؤدين، ويُعد تسجيل أو إدارة أي حساب من قبل تلك الجهات إقراراً منها بامتلاكها التفويض القانوني اللازم، وبصحة جميع البيانات والمحتوى المقدم، وبالتزامها الكامل بجميع الشروط والسياسات نيابةً عن الأشخاص التابعين لها، دون أن تتحمل المنصة أي مسؤولية عن أي نزاعات أو علاقات تعاقدية داخلية بينهم.</li>
                            <li>تعمل المنصة كوسيط إلكتروني فقط، ولا تُعد جهة توظيف أو شركة إنتاج، ولا تضمن إبرام أي تعاقد أو تحقيق أي دخل أو فرص إعلانية لأي مستخدم، ولا تتحمل أي مسؤولية عن نتائج التعاقد أو فشله أو أي نزاعات تنشأ بين الأطراف.</li>
                            <li>يلتزم المستخدم، وبالأخص الممثل أو المؤدي، بالتصرف بشكل مهني والالتزام بالتعليمات والمتطلبات المعتمدة والمحافظة على سرية أي معلومات أو مواد تتعلق بالمنصة أو الحملات أو الأطراف الأخرى. ويوافق المستخدم على حق المنصة في اتخاذ أي إجراءات تراها مناسبة في حال الإخلال بهذه الالتزامات، بما في ذلك تعديل أو إلغاء المشاركة في أي عمل، أو إجراء تسويات مالية أو تنظيمية، أو اتخاذ أي خطوات أخرى تراها ضرورية، دون أن يترتب على ذلك أي مسؤولية على المنصة.</li>
                            <li>تتقاضى المنصة عمولة كنسبة مئوية من إجمالي قيمة أي إعلان أو عمل يتم من خلال المنصة، ويتم تحصيل العمولة بطريق الخصم من المنبع أو عبر وسائل الدفع المعتمدة، وتعد العمولة غير قابلة للاسترداد بعد تنفيذ العمل. ويحظر التحايل أو إبرام أي اتفاق خارج المنصة بقصد تجنب العمولة، ويحق للمنصة في حال ثبوت ذلك اتخاذ أي إجراءات تراها مناسبة.</li>
                            <li>لا تتحمل المنصة أي التزامات ضريبية عن المستخدمين، ويتحمل كل مستخدم أي ضرائب أو رسوم مفروضة عليه وفق النظام القانوني المعمول به لديه.</li>
                            <li>قد تحتوي المنصة على روابط لمواقع أو خدمات خارجية، ولا تتحمل المنصة أي مسؤولية عن محتواها أو سياساتها أو ممارساتها.</li>
                            <li>لا تتحمل المنصة أي مسؤولية عن أي خسائر أو أضرار مباشرة أو غير مباشرة أو تبعية تنشأ عن استخدام المنصة أو الاعتماد على أي محتوى أو خدمة مقدمة من خلالها، ويقتصر دورها على الوساطة التقنية والتنظيمية فقط.</li>
                            <li>لا تكون المنصة مسؤولة عن أي تأخير أو فشل ناتج عن ظروف خارجة عن الإرادة، بما في ذلك الأعطال التقنية أو القرارات الحكومية أو الكوارث الطبيعية أو حالات القوة القاهرة.</li>
                            <li>يوافق المستخدم على تلقي الإشعارات والمراسلات عبر البريد الإلكتروني أو الرسائل النصية أو داخل المنصة أو عبر أي وسيلة تواصل أخرى تعتمدها المنصة.</li>
                            <li>تخضع هذه الوثيقة وتفسر وفق قوانين ولاية وايومنغ – الولايات المتحدة الأمريكية، مع احتفاظ المنصة بحقها الكامل في اختيار الجهة القضائية المختصة أو أي وسيلة أخرى لتسوية النزاعات وفق ما تراه مناسباً.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="divider"></div>

            <form method="POST" action="{{ route('talent.onboarding.terms.accept') }}" class="termsForm">
                @csrf
                <label class="accept-row">
                    <input type="checkbox" name="accept_terms" value="1" class="accept_terms" required>
                    <span>
                        <span>I have read and agree to the Privacy Policy & Terms of Use.</span>
                        <span class="ar-text" style="color:#4b5563; display: block; margin-top: 4px;">أقر بأنني قرأت ووافقت على سياسة الخصوصية وشروط الاستخدام.</span>
                    </span>
                </label>
                <div class="actions">
                    <button type="submit" class="cta continueBtn" disabled>
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
                    {{--英语内容通过JS克隆或者在这里重复，为了性能这里直接重复关键内容 --}}
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
                    <li>تشير هذه الوثيقة إلى منصة Bricks Community، وهي منصة رقمية مملوكة وتدار من قبل Bricks Marketing LLC، وهي شركة ذات مسؤولية محدودة مسجلة في ولاية وايومنغ – الولايات المتحدة الأمريكية.</li>
                    <li>باستخدامك للمنصة أو تسجيلك فيها، فإنك تقر بموافقتك الكاملة على جميع ما ورد في هذه الوثيقة وعلى أي تعديلات مستقبلية تطرأ عليها.</li>
                    <li>تحتفظ المنصة بحقها الكامل في تعديل أو تحديث هذه السياسة في أي وقت، ويُعد استمرار استخدام المنصة قبولاً صريحاً بذلك.</li>
                    <li>لا يُسمح باستخدام المنصة لمن هم دون السن القانوني، ويجب إنشاء الحساب حصراً من قبل ولي الأمر أو الوصي القانوني.</li>
                    <li>تنطبق هذه السياسة على جميع زوار المنصة والمستخدمين المسجلين وتشمل أي بيانات يتم جمعها عبر المنصة.</li>
                    <li>تقوم المنصة بجمع بيانات يقدمها المستخدم بشكل مباشر مثل الاسم والجنسية ووسائل التواصل والصور والمواصفات الشكلية.</li>
                    <li>يتم الاحتفاظ بالبيانات طالما كان الحساب نشطاً، وتُحذف نهائياً بعد انتهاء فترة الاحتفاظ الداخلية عند طلب حذف الحساب.</li>
                    <li>تُستخدم البيانات لأغراض تشغيل المنصة ومطابقة الممثلين مع الفرص الإعلانية وتحسين جودة الخدمات.</li>
                    <li>يوافق المستخدم على مشاركة بعض بياناته مع أطراف ثالثة مثل المعلنين أو مزودي خدمات الدفع عند الضرورة.</li>
                    <li>يقر المستخدم بأن جميع البيانات والصور والمقاطع التي يقدمها صحيحة وحقيقية، ويتحمل المسؤولية القانونية الكاملة عن أي محتوى مضلل.</li>
                    <li>يمنح المستخدم المنصة حقوقاً حصرية وغير محدودة لاستخدام الصور والمحتوى المقدم، بما في ذلك منح هذه الحقوق للمعلنين.</li>
                    <li>تعمل المنصة كوسيط إلكتروني فقط، ولا تُعد جهة توظيف أو شركة إنتاج، ولا تضمن إبرام أي تعاقد أو تحقيق أي دخل.</li>
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
@endsection

