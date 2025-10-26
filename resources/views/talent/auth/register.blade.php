@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .auth-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
        }

        .auth-box {
            text-align: center;
            width: 100%;
            max-width: 400px;
            transform-origin: center;
        }

        /* subtle zoom on desktop */
        @media (min-width: 1200px) {
            .auth-box {
                transform: scale(1.3);
            }
        }

        .auth-title {
            font-weight: 600;
            margin-bottom: 25px;
            font-size: 16px;
            color: #000;
        }

        /* Tabs */
        .tab-buttons {
            display: flex;
            justify-content: center;
            border-radius: 8px;
            overflow: hidden;
      
            width: fit-content;
            margin: 0 auto 25px;
        }

        .tab-buttons button {
            /* no flex:1 */
            width: 140px;
            padding: 5px 0;
            border: none;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            transition: .3s;
            background: #f4e6e3;
            color: #000;
        }

        .tab-buttons button.active {
            background: #b18b86;
            color: #fff;
        }

        .phone-input {
            margin-bottom: 20px;
        }

        input[type="tel"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 14px;
        }

        .submit-btn {
            background: #b18b86;
            border: none;
            color: #fff;
            padding: 5px 30px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: .3s;
            margin-bottom: 15px;
        }

        .submit-btn:hover {
            background: #9c7a74;
        }

        .remember {
            font-size: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            margin-bottom: 20px;
        }

        .bottom-text {
            font-size: 12px;
            color: #000;
        }

        .bottom-text a {
            color: #0066ff;
            text-decoration: none;
            font-weight: 500;
        }

        .bottom-text a:hover {
            text-decoration: underline;
        }
    </style>

    <div class="auth-container">
        <div class="auth-box">
            <h6 class="auth-title">Sign up to BRICKS Model</h6>

            <div class="tab-buttons">
                <button type="button" id="tab-login" class="active">Login</button>
                <button type="button" id="tab-create">Create account</button>
            </div>

            <form id="auth-form" method="POST" action="{{ route('talent.register.submit') }}">
                @csrf

                <!-- intl-tel-input visible field -->
                <div class="phone-input">
                    <input id="phone" type="tel" placeholder="Enter your phone number" required>
                </div>

                <!-- these are filled from intlTelInput on submit -->
                <input type="hidden" name="phone_country_code" id="phone_country_code">
                <input type="hidden" name="phone_number" id="phone_number">

                <button type="submit" id="submit-btn" class="submit-btn">Submit</button>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </form>

            <div class="bottom-text">
                Don’t have an Bricks Account?
                <a href="javascript:void(0)" id="bottom-link">Already have</a>
            </div>
        </div>
    </div>
 

    <script>
        // ---- Tabs state ----
        const state = {
            tab: 'login', // 'login' | 'create'
            routes: {
                login: @json(route('talent.login.submit')),
                create: @json(route('talent.register.submit')) // <- change if needed
            }
        };

        const form = document.getElementById('auth-form');
        const btnLogin = document.getElementById('tab-login');
        const btnCreate = document.getElementById('tab-create');
        const submitBtn = document.getElementById('submit-btn');
        const bottomLink = document.getElementById('bottom-link');

        function applyTab() {
            // toggle active style
            btnLogin.classList.toggle('active', state.tab === 'login');
            btnCreate.classList.toggle('active', state.tab === 'create');

            // swap form action + button text + bottom link text
            form.action = state.routes[state.tab];
            submitBtn.textContent = 'Submit';
            bottomLink.textContent = (state.tab === 'login') ? 'Create one' : 'Back to Login';
        }

        btnLogin.addEventListener('click', () => {
            state.tab = 'login';
            applyTab();
        });
        btnCreate.addEventListener('click', () => {
            state.tab = 'create';
            applyTab();
        });
        bottomLink.addEventListener('click', () => {
            state.tab = (state.tab === 'login') ? 'create' : 'login';
            applyTab();
        });

        // ---- Phone (intl-tel-input) ----
        const phoneInput = document.querySelector('#phone');
        let iti = null;

        function initPhone() {
            if (!window.intlTelInput) return; // in case the lib is already globally loaded in layout
            iti = window.intlTelInput(phoneInput, {
                initialCountry: 'kw',
                preferredCountries: ['kw', 'ae', 'pk', 'us'],
                separateDialCode: true,
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
            });
        }
        // If script is deferred in layout, run immediately; otherwise wait a tick
        document.readyState === 'loading' ?
            window.addEventListener('DOMContentLoaded', initPhone) :
            initPhone();

        // push dial code + national number into hidden inputs on submit
        form.addEventListener('submit', function(e) {
            if (iti) {
                document.getElementById('phone_country_code').value = '+' + (iti.getSelectedCountryData()
                    .dialCode || '');
                // national number without dial code
                const number = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL) || phoneInput.value;
                document.getElementById('phone_number').value = number.replace(/\s+/g, '').trim();
            } else {
                // fallback: treat whole value as phone_number; no country code
                document.getElementById('phone_number').value = phoneInput.value.trim();
            }
        });

         
    </script>
@endsection
