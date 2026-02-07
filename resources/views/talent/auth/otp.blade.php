@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #ffffff url('{{ isset($adminSettings) && $adminSettings->background_image_url ? $adminSettings->background_image_url : asset('images/models_bg.png') }}') center center / cover no-repeat fixed;
            font-family: 'Arimo', sans-serif;
        }

        .auth-shell {
            min-height: calc(100vh - 60px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .auth-card {
            width: 100%;
            max-width: 380px;
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 25px 55px rgba(0, 0, 0, 0.14);
            padding: 32px 28px 24px;
            text-align: center;
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
        }

        h1 {
            font-weight: 400;
            font-size: 20px;
            line-height: 30px;
            margin: 0 0 10px;
            color: #1a1a1a;
        }

        .lead {
            font-size: 13px;
            line-height: 1.6;
            color: #4f4f4f;
            margin: 0 0 30px;
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
        }

        .meta a,
        .meta a:visited,
        .meta a:hover,
        .meta a:active {
            color: #3f3f3f;
            text-decoration: none;
        }

        .alt-link,
        .alt-link:visited,
        .alt-link:hover,
        .alt-link:active {
            display: inline-block;
            margin-top: 14px;
            font-size: 12px;
            color: #3f3f3f;
            text-decoration: none;
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
    </style>

    <div class="auth-shell">
        <div class="auth-card">
            <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
            <div class="eyebrow">Studio</div>
            <h1>Verify Identity</h1>
            <p class="lead">Enter the code sent to<br>{{ ($phone['phone_country_code'] ?? '') . ' ' . ($phone['phone_number'] ?? '') }}</p>

            
                @if(isset($otp) && !empty($otp))
                <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center;">
                    <div style="font-size: 11px; color: #92400e; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-weight: 600;">Testing Mode</div>
                    <div style="font-size: 24px; font-weight: 700; color: #92400e; letter-spacing: 4px; font-family: 'Courier New', monospace;">{{ $otp }}</div>
                    <div style="font-size: 11px; color: #92400e; margin-top: 4px;">This OTP is stored in the database</div>
                </div>
                @else
                <div style="background: #fee2e2; border: 1px solid #f87171; border-radius: 8px; padding: 12px; margin-bottom: 20px; text-align: center;">
                    <div style="font-size: 11px; color: #991b1b; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 4px; font-weight: 600;">Debug Info</div>
                    <div style="font-size: 12px; color: #991b1b;">OTP not found. Phone: {{ ($phone['phone_country_code'] ?? 'N/A') . ' ' . ($phone['phone_number'] ?? 'N/A') }}</div>
                </div>
                @endif
            

            @if ($errors->any())
                <div class="error-message">
                    @foreach ($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form id="otp-form" method="POST" action="{{ route('talent.otp.verify') }}">
                @csrf

                <div class="otp-grid">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*" autocomplete="one-time-code">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                </div>

                <input type="hidden" name="otp" id="otp-hidden" value="{{ old('otp') }}">

                <button type="submit" class="submit-btn">
                    Verify & Login
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M12 17v-3" />
                        <rect x="4" y="10" width="16" height="10" rx="2" ry="2" />
                        <path d="M8 10V7a4 4 0 0 1 8 0v3" />
                    </svg>
                </button>

                <div class="meta">
                    <span id="countdown">55 secs left.</span>
                    <a href="{{ route('talent.login.submit') }}">Resend code</a>
                </div>

                <a class="alt-link" href="{{ route('talent.login') }}"><span>Change Phone Number</span></a>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // OTP auto-advance, digits only, paste support, hidden field combine + 55s timer
        (function() {
            const inputs = Array.from(document.querySelectorAll('.otp-input'));
            const hidden = document.getElementById('otp-hidden');
            const grid = document.querySelector('.otp-grid');

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
                        const btn = document.querySelector('.submit-btn');
                        if (btn) btn.click();
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

            // paste whole code (e.g. "1234")
            grid.addEventListener('paste', e => {
                const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(0, inputs.length);
                if (!txt) return;
                e.preventDefault();
                inputs.forEach((el, idx) => el.value = txt[idx] || '');
                (txt.length < inputs.length ? inputs[txt.length] : inputs[inputs.length - 1]).focus();
                setHidden();
            });

            // focus first on load
            inputs[0]?.focus();

            // countdown 55s
            let s = 55;
            const el = document.getElementById('countdown');
            const tick = () => {
                el.textContent = `${s} secs left.`;
                if (s-- <= 0) clearInterval(iv);
            };
            const iv = setInterval(tick, 1000);
            tick();
        })();
    </script>
@endsection
