@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #ffffff url('{{ asset('images/landing.jpg') }}') center center / cover no-repeat;
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
            border: 1px solid #1a1a1a;
            border-radius: 10px;
            background: #1a1a1a;
            text-align: center;
            font-size: 20px;
            font-weight: 600;
            color: #ffffff;
            outline: none;
            transition: border-color .15s, box-shadow .15s, background .15s;
        }

        .otp-input:focus {
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.08);
            background: #1a1a1a;
        }

        .submit-btn {
            width: 100%;
            height: 46px;
            border: none;
            border-radius: 10px;
            background: #111111;
            color: #ffffff;
            font-size: 14px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 12px 28px rgba(0, 0, 0, 0.2);
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
    </style>

    <div class="auth-shell">
        <div class="auth-card">
            <img class="logo" src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
            <div class="eyebrow">Studio</div>
            <h1>Verify Identity</h1>
            <p class="lead">Enter the code sent to<br>{{ ($phone['phone_country_code'] ?? '') . ' ' . ($phone['phone_number'] ?? '') }}</p>

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
                    if (!/^\d$/.test(e.key) && !['Backspace', 'Tab', 'Delete', 'ArrowLeft', 'ArrowRight'].includes(e.key)) {
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
