@extends('layouts.admin')
@section('content')
<style>
    .privacy-shell { padding: 16px 8px 28px; background: #f7f9fb; }
    .privacy-frame { max-width: 720px; margin: 0 auto; }
    .privacy-card { background: #fff; border: 1px solid #e8ebef; border-radius: 14px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 16px 18px 18px; }
    .privacy-head { display: flex; align-items: center; gap: 10px; margin-bottom: 14px; }
    .privacy-title { font-weight: 700; color: #111827; margin: 0; font-size: 15px; }
    .privacy-sub { margin: 0; color: #6b7280; font-size: 12px; }
    .section-row { border: 1px solid #eef0f4; border-radius: 12px; padding: 14px; background: #fafbfc; display: flex; gap: 10px; align-items: flex-start; }
    .section-icon { width: 28px; height: 28px; border-radius: 8px; background: #0f172a; color: #fff; display: grid; place-items: center; font-size: 12px; }
    .section-text { display: flex; flex-direction: column; gap: 2px; }
    .section-title { font-weight: 700; color: #111827; font-size: 13px; margin: 0; }
    .section-hint { color: #6b7280; font-size: 11px; margin: 0; }
    .form-stack { display: flex; flex-direction: column; gap: 10px; margin-top: 14px; }
    .field { display: flex; flex-direction: column; gap: 6px; }
    .field label { font-size: 11px; color: #6b7280; }
    .input-wrap { position: relative; }
    .input-wrap input { width: 100%; border: 1px solid #e3e6ed; border-radius: 10px; padding: 11px 36px 11px 12px; font-size: 12px; color: #111827; background: #fff; }
    .input-wrap .eye { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 12px; }
    .helper { font-size: 11px; color: #9ca3af; margin-top: -4px; }
    .btn-update { width: 100%; border: none; border-radius: 8px; padding: 11px; font-size: 12px; font-weight: 600; color: #fff; background: linear-gradient(90deg, #0d0d0f, #1c1d21); box-shadow: 0 10px 22px rgba(0,0,0,0.16); margin-top: 6px; }
    .back-link { display: inline-flex; align-items: center; gap: 6px; color: #4b5563; font-size: 12px; text-decoration: none; margin-bottom: 10px; }
</style>

<div class="privacy-shell">
    <div class="privacy-frame">
        <a href="{{ url()->previous() }}" class="back-link"><i class="fas fa-arrow-left"></i> Privacy & Security</a>
        <div class="privacy-card">
            <div class="section-row">
                <div class="section-icon"><i class="fas fa-key"></i></div>
                <div class="section-text">
                    <p class="section-title">Change Password</p>
                    <p class="section-hint">Update your password to keep your account secure</p>
                </div>
            </div>

            <form method="POST" action="{{ route('profile.password.update') }}" class="form-stack">
                @csrf
                <div class="field">
                    <label for="current_password">Current Password</label>
                    <div class="input-wrap">
                        <input id="current_password" name="current_password" type="password" autocomplete="current-password" placeholder="Enter current password" class="{{ $errors->has('current_password') ? 'is-invalid' : '' }}">
                        <span class="eye"><i class="far fa-eye"></i></span>
                    </div>
                    @if($errors->has('current_password'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('current_password') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label for="password">New Password</label>
                    <div class="input-wrap">
                        <input id="password" name="password" type="password" autocomplete="new-password" placeholder="Enter new password" class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
                        <span class="eye"><i class="far fa-eye"></i></span>
                    </div>
                    <div class="helper">Password must be at least 8 characters long</div>
                    @if($errors->has('password'))
                        <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                    @endif
                </div>

                <div class="field">
                    <label for="password_confirmation">Confirm New Password</label>
                    <div class="input-wrap">
                        <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" placeholder="Confirm new password">
                        <span class="eye"><i class="far fa-eye"></i></span>
                    </div>
                </div>

                <button type="submit" class="btn-update">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
