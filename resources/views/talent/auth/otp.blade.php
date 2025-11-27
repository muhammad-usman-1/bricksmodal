@extends('layouts.app')

@section('content')
    <style>
        :root {
            --border: #e1d0cd;
            --shadow: 0 2px 0 rgba(0, 0, 0, .06);
            --ink: #111;
            --muted: #4a3f3d;
            --btn: #8f6662;
            /* mauve like mock */
            --btn-h: #7d5956;
        }

        .logo-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .logo-container img {
        height: 25px;
        width: 100px;
    }

        /* Page layout: plain white, centered stack */
        .otp-page {
            min-height: 80vh;
            display: flex;
            align-items: flex-start;
            justify-content: center;
            padding-top: 30vh;

            font-family: 'Poppins', system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            color: var(--ink);
        }
         /* subtle zoom on desktop */
        @media (min-width: 1200px) {
            .otp-page {
                transform: scale(1.2);
            }
        }
        .otp-wrap {
            text-align: center;
        }

        .otp-title {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 28px;
        }

        .otp-grid {
            display: flex;
            gap: 12px;
            justify-content: center;
            margin-bottom: 22px;
        }

        .otp-input {
            width: 48px;
            height: 40px;
            border: 1px solid var(--border);
            border-radius: 6px;
            background: #fff;
            text-align: center;
            font-size: 18px;
            font-weight: 600;
            color: #222;
            outline: none;
            box-shadow: var(--shadow);
            transition: border-color .15s, box-shadow .15s;
        }

        .otp-input:focus {
            border-color: #cdb3b0;
            box-shadow: 0 0 0 3px rgba(177, 139, 134, .18);
        }

        .otp-hint {
            font-size: 12px;
            color: var(--muted);
            margin: 8px 0 28px;
        }

        .btn-verify {
            display: inline-block;
            min-width: 140px;
            background: var(--btn);
            color: #fff;
            border: 0;
            border-radius: 8px;
            padding: 9px 18px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: .15s;
        }

        .btn-verify:hover {
            background: var(--btn-h);
        }
    </style>

    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
    </div>

    <div class="otp-page">
        <div class="otp-wrap">
            <div class="otp-title">OTP Code Verification</div>

            <form id="otp-form" method="POST" action="{{ route('talent.otp.verify') }}">
                @csrf

                <div class="otp-grid">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*"
                        autocomplete="one-time-code">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                    <input class="otp-input" type="text" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                </div>

                <input type="hidden" name="otp" id="otp-hidden" value="{{ old('otp') }}">

                <div class="otp-hint">Send code again <span id="countdown">00:20</span></div>

                <button type="submit" class="btn-verify">Verify</button>

                <div style="margin-top: 32px; font-size: 13px; color: var(--muted);">
                    <div style="margin-bottom: 12px;">
                        <a href="{{ route('talent.login.submit') }}" style="color: var(--muted); text-decoration: none;">Send code again</a>
                    </div>
                    <div>
                        <a href="{{ route('talent.login') }}" style="color: var(--muted); text-decoration: none;">Change phone number</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // OTP auto-advance, digits only, paste support, hidden field combine + 20s timer
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
                    if (!/^\d$/.test(e.key) && !['Backspace', 'Tab', 'Delete', 'ArrowLeft',
                            'ArrowRight'].includes(e.key)) {
                        e.preventDefault();
                    }
                });
                el.addEventListener('input', () => {
                    el.value = el.value.replace(/\D/g, '').slice(0, 1);
                    if (el.value && i < inputs.length - 1) inputs[i + 1].focus();
                    setHidden();
                });
            });

            // paste whole code (e.g. "123456")
            grid.addEventListener('paste', e => {
                const txt = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '').slice(
                    0, inputs.length);
                if (!txt) return;
                e.preventDefault();
                inputs.forEach((el, idx) => el.value = txt[idx] || '');
                (txt.length < inputs.length ? inputs[txt.length] : inputs[inputs.length - 1]).focus();
                setHidden();
            });

            // focus first on load
            inputs[0]?.focus();

            // countdown 00:20
            let s = 20,
                el = document.getElementById('countdown');
            const tick = () => {
                el.textContent = '00:' + String(s).padStart(2, '0');
                if (s-- <= 0) clearInterval(iv);
            };
            const iv = setInterval(tick, 1000);
            tick();
        })();
    </script>
@endsection
