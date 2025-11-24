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
            height: 30px;
            width: 150px;
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

    <!-- Logo -->
    <div class="logo-container">
        <img src="{{ asset('storage/bricks_logo.png') }}" alt="BRICKS Model Logo">
    </div>

    <div class="auth-container">
        <div class="auth-box">
            <h6 class="auth-title">Admin Login</h6>

            @if ($errors->any())
                <div class="alert alert-danger" style="background: #f8d7da; color: #721c24; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('status'))
                <div class="alert alert-success" style="background: #d4edda; color: #155724; padding: 12px; border-radius: 6px; margin-bottom: 15px; font-size: 13px;">
                    {{ session('status') }}
                </div>
            @endif

            <form id="auth-form" method="POST" action="{{ route('admin.login.submit') }}">
                @csrf

                <!-- intl-tel-input visible field -->
                <div class="phone-input">
                    <input id="phone" type="tel" name="phone_number" placeholder="Enter your phone number" required>
                </div>

                <button type="submit" id="submit-btn" class="submit-btn">Submit</button>

                <label class="remember">
                    <input type="checkbox" name="remember">
                    <span>Remember me</span>
                </label>
            </form>
        </div>
    </div>

    <script>
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
        // If script is deferred in layout, run immediately; otherwise wait a tick
        document.readyState === 'loading' ?
            window.addEventListener('DOMContentLoaded', initPhone) :
            initPhone();
    </script>
@endsection
