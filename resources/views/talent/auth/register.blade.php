@extends('layouts.app')

@section('content')
    <style>
        body {
            background: #fff;
            font-family: 'Poppins', sans-serif;
        }

        .logo-container {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
        }

        .logo-container img {
            height: 20px;
            width: 100px;
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
            <!-- Logo -->
            <div class="logo-container" style="position: static; margin-bottom: 20px;">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
            </div>

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
                Already have an account?
                <a href="{{ route('talent.login') }}">Login</a>
            </div>

            <div class="bottom-text" style="margin-top: 10px;">
                <a href="{{ route('admin.login') }}">Sign in as Admin</a>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // ---- Phone (intl-tel-input) ----
            const phoneInput = document.querySelector('#phone');
            let iti = null;

            // Supported countries from config
            const supportedCountries = {!! json_encode(array_keys(config('countries.supported', ['kw', 'sa', 'ae', 'bh', 'om', 'qa']))) !!};
            const preferredCountries = {!! json_encode(config('countries.preferred', ['kw', 'sa', 'ae', 'bh'])) !!};
            const initialCountry = {!! json_encode(config('countries.initial', 'kw')) !!};

            function initPhone() {
                if (!window.intlTelInput) return; // in case the lib is already globally loaded in layout
                iti = window.intlTelInput(phoneInput, {
                    initialCountry: initialCountry,
                    onlyCountries: supportedCountries,
                    preferredCountries: preferredCountries,
                    separateDialCode: true,
                    utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@19.5.6/build/js/utils.js'
                });
            }

            initPhone();

            const form = document.getElementById('auth-form');

            // ---- Phone submit handler ----
            form.addEventListener('submit', function(e) {
                const inputValue = phoneInput.value.trim();
                if (iti) {
                    document.getElementById('phone_country_code').value = '+' + (iti.getSelectedCountryData()
                        .dialCode || '');
                    // national number without dial code
                    const number = iti.getNumber(intlTelInputUtils.numberFormat.NATIONAL) || inputValue;
                    document.getElementById('phone_number').value = number.replace(/\s+/g, '').trim();
                } else {
                    // fallback: parse the input
                    if (inputValue.startsWith('+')) {
                        const parts = inputValue.split(/\s+/);
                        document.getElementById('phone_country_code').value = parts[0];
                        document.getElementById('phone_number').value = parts.slice(1).join('').replace(/\D/g, '');
                    } else {
                        document.getElementById('phone_country_code').value = '+965';
                        document.getElementById('phone_number').value = inputValue.replace(/\D/g, '');
                    }
                }
            });
        });
    </script>
@endsection
