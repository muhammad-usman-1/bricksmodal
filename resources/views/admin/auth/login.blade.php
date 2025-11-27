@extends('layouts.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="text-center mt-2">
                    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo" style="height: 30px; width:150px">
                </div>
                <hr>
                <div class="card-body">

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <div class="form-group">
                            <label for="email">{{ trans('global.login_email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password">{{ trans('global.login_password') }}</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ trans('global.remember_me') }}</label>
                        </div>

                        <button type="submit" class="btn btn-dark btn-block">{{ trans('global.login') }}</button>
                    </form>

                    <hr>

                    <div class="text-center">
                        <a href="{{ route('admin.login.google') }}" class="google-signin-btn">
                            <svg class="google-icon" viewBox="0 0 24 24">
                                <path fill="#4285F4"
                                    d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z" />
                                <path fill="#34A853"
                                    d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z" />
                                <path fill="#FBBC05"
                                    d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z" />
                                <path fill="#EA4335"
                                    d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z" />
                            </svg>
                            Sign in with Google
                        </a>
                    </div>

                    <style>
                        .google-signin-btn {
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            background-color: #fff;
                            border: 1px solid #dadce0;
                            border-radius: 4px;
                            color: #3c4043;
                            cursor: pointer;
                            font-family: 'Roboto', sans-serif;
                            font-size: 14px;
                            font-weight: 500;
                            height: 40px;
                            letter-spacing: 0.25px;
                            padding: 0 12px;
                            text-decoration: none;

                            /* Make it same width as login button */
                            width: 100%;
                            box-sizing: border-box;
                            /* ensures padding included in width */
                            margin-top: 10px;
                            /* optional spacing below login button */
                        }


                        .google-signin-btn:hover {
                            background-color: #f8f9fa;
                            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                        }

                        .google-signin-btn:active {
                            background-color: #f1f3f4;
                        }

                        .google-icon {
                            width: 18px;
                            height: 18px;
                            margin-right: 12px;
                        }
                    </style>
                </div>
            </div>
        </div>
    </div>
@endsection
