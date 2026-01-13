@php
    $adminSettings = \App\Models\AdminSetting::singleton();
    $bg = $adminSettings && $adminSettings->background_image_url
        ? $adminSettings->background_image_url
        : asset('images/models_bg.png');

    $logo = asset('images/bricks_logo.png');
    $activeRole = $activeRole ?? 'model'; // model | admin
    $pageTitle = $pageTitle ?? 'Bricks Studio';
    $headline = $headline ?? 'Welcome Back';
    $subheading = $subheading ?? 'Choose how you want to continue.';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600&family=Arimo:wght@400;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --ink-900: #0f172a;
            --ink-700: #334155;
            --ink-500: #64748b;
            --card: #ffffff;
            --border: rgba(15, 23, 42, 0.12);
            --shadow: 0 30px 80px rgba(15, 23, 42, 0.16);
            --radius-xl: 22px;
            --radius-lg: 14px;
            --radius-md: 12px;
            --primary: #0f1014;
            --primary-2: #111827;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Space Grotesk', system-ui, -apple-system, Segoe UI, Roboto, sans-serif;
            color: var(--ink-900);
            background: #ffffff url('{{ $bg }}') center center / cover no-repeat fixed;
        }

        .auth-bg {
            min-height: 100vh;
            width: 100%;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding: 90px 18px 40px;
            position: relative;
        }

        .auth-bg::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.84) 55%, rgba(255,255,255,0.78) 100%);
            pointer-events: none;
        }

        .auth-card {
            width: 100%;
            max-width: 440px;
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: var(--radius-xl);
            box-shadow: var(--shadow);
            padding: 28px 26px 24px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(6px);
        }

        .brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            margin-bottom: 16px;
        }

        .brand-logo {
            width: 150px;
            height: auto;
            margin: 0 auto 8px;
        }

        .brand-eyebrow {
            font-family: 'Arimo', sans-serif;
            font-size: 11px;
            letter-spacing: 0.36em;
            text-transform: uppercase;
            color: #9aa0ac;
            margin-bottom: 10px;
        }

        .headline {
            font-family: 'Arimo', sans-serif;
            font-weight: 600;
            font-size: 26px;
            line-height: 1.25;
            margin: 0 0 8px;
            color: #111827;
        }

        .subhead {
            font-family: 'Arimo', sans-serif;
            font-size: 13px;
            line-height: 1.6;
            color: #5b6475;
            margin: 0 0 18px;
        }

        .role-toggle {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 0 0 18px;
        }

        .role-pill {
            border: 1px solid rgba(15, 23, 42, 0.12);
            border-radius: 14px;
            padding: 12px 12px;
            background: #ffffff;
            cursor: pointer;
            transition: all 0.16s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
            color: inherit;
        }

        .role-pill:hover {
            border-color: rgba(15, 23, 42, 0.24);
            box-shadow: 0 10px 24px rgba(15, 23, 42, 0.08);
            text-decoration: none;
        }

        .role-pill.active {
            border-color: rgba(17, 24, 39, 0.55);
            box-shadow: 0 14px 30px rgba(17, 24, 39, 0.14);
        }

        .role-icon {
            width: 38px;
            height: 38px;
            border-radius: 12px;
            display: grid;
            place-items: center;
            background: #f3f4f6;
            color: #111827;
            flex-shrink: 0;
        }

        .role-pill.active .role-icon {
            background: var(--primary-2);
            color: #ffffff;
        }

        .role-meta { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
        .role-title { font-size: 13px; font-weight: 700; color: #111827; }
        .role-desc { font-family: 'Arimo', sans-serif; font-size: 12px; color: #6b7280; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

        .slot {
            min-height: 14px;
            margin: 0 0 14px;
        }

        .divider {
            height: 1px;
            background: rgba(15, 23, 42, 0.08);
            margin: 14px 0;
        }

        .continue {
            width: 100%;
            height: 52px;
            border: none;
            border-radius: 14px;
            background: var(--primary);
            color: #ffffff;
            font-size: 14px;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            text-decoration: none;
            transition: transform 0.18s ease, box-shadow 0.18s ease;
        }

        .continue:hover {
            transform: translateY(-1px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.18);
            text-decoration: none;
            color: #ffffff;
        }

        .continue:focus-visible {
            outline: 2px solid #111827;
            outline-offset: 4px;
        }

        .footer-note {
            margin-top: 12px;
            text-align: center;
            font-family: 'Arimo', sans-serif;
            font-size: 12px;
            color: #6b7280;
        }

        @media (max-width: 420px) {
            .auth-card { padding: 24px 18px 20px; }
            .role-desc { display: none; }
        }
    </style>

    @yield('styles')
</head>
<body>
<div class="auth-bg">
    <div class="auth-card" data-auth-shell>
        <div class="brand">
            <img class="brand-logo" src="{{ $logo }}" alt="Bricks Studio">
            <div class="brand-eyebrow">Studio</div>
            <h1 class="headline">{{ $headline }}</h1>
            <p class="subhead">{{ $subheading }}</p>
        </div>

        <div class="role-toggle" data-role-toggle>
            <button type="button" class="role-pill {{ $activeRole === 'model' ? 'active' : '' }}" data-role="model">
                <span class="role-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </span>
                <span class="role-meta">
                    <span class="role-title">Sign in as Model</span>
                    <span class="role-desc">Talent portal access</span>
                </span>
            </button>
            <button type="button" class="role-pill {{ $activeRole === 'admin' ? 'active' : '' }}" data-role="admin">
                <span class="role-icon" aria-hidden="true">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 1l3 5 5 1-3.5 4 1 6-5.5-3-5.5 3 1-6L4 7l5-1 3-5z"></path>
                    </svg>
                </span>
                <span class="role-meta">
                    <span class="role-title">Sign in as Admin</span>
                    <span class="role-desc">Dashboard & management</span>
                </span>
            </button>
        </div>

        <div class="slot">
            @yield('auth_sections')
        </div>

        <a class="continue" href="{{ $activeRole === 'admin' ? route('admin.login') : route('talent.login') }}" data-continue-link>
            Continue
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M5 12h14" />
                <path d="M13 6l6 6-6 6" />
            </svg>
        </a>

        @hasSection('auth_footer')
            <div class="divider"></div>
            @yield('auth_footer')
        @else
            <div class="footer-note">Need a different role? Switch above and press Continue.</div>
        @endif
    </div>
</div>

<script>
    (function () {
        const shell = document.querySelector('[data-auth-shell]');
        if (!shell) return;

        const roleButtons = Array.from(shell.querySelectorAll('[data-role-toggle] [data-role]'));
        const continueLink = shell.querySelector('[data-continue-link]');

        const routes = {
            model: @json(route('talent.login')),
            admin: @json(route('admin.login')),
        };

        const setRole = (role) => {
            roleButtons.forEach(btn => btn.classList.toggle('active', btn.dataset.role === role));
            if (continueLink && routes[role]) continueLink.setAttribute('href', routes[role]);
            try { localStorage.setItem('authRole', role); } catch (e) {}
        };

        roleButtons.forEach(btn => {
            btn.addEventListener('click', () => setRole(btn.dataset.role));
        });

        // Load remembered role (but do not override server default if it's explicitly set)
        try {
            const remembered = localStorage.getItem('authRole');
            const serverActive = roleButtons.find(b => b.classList.contains('active'))?.dataset?.role;
            if (remembered && !serverActive) setRole(remembered);
        } catch (e) {}
    })();
</script>

@yield('scripts')
</body>
</html>




