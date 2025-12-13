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

        <!-- Two-Factor Authentication Section -->
        <div class="privacy-card" style="margin-top: 14px;">
            <div class="section-row">
                <div class="section-icon"><i class="fas fa-shield-alt"></i></div>
                <div class="section-text">
                    <p class="section-title">Two-Factor Authentication</p>
                    <p class="section-hint">Add an extra layer of security to your account</p>
                </div>
            </div>

            @if($twoFactorEnabled ?? false)
                <!-- 2FA Enabled State -->
                <div class="two-factor-enabled" style="margin-top: 14px;">
                    <div class="two-factor-status success">
                        <i class="fas fa-check-circle"></i>
                        <div>
                            <strong>Two-Factor Authentication Enabled</strong>
                            <p>Your account is protected with two-factor authentication.</p>
                        </div>
                    </div>

                    @if(session('recoveryCodes'))
                        <div class="recovery-codes-box" style="margin-top: 14px; padding: 14px; background: #fef3c7; border: 1px solid #fde68a; border-radius: 10px;">
                            <p style="margin: 0 0 8px 0; font-size: 11px; color: #92400e; font-weight: 600;">Save these recovery codes in a safe place:</p>
                            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; font-family: monospace; font-size: 11px; color: #78350f;">
                                @foreach(session('recoveryCodes') as $code)
                                    <code style="padding: 4px 8px; background: #fff; border-radius: 4px;">{{ $code }}</code>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.two-factor.disable') }}" class="form-stack" style="margin-top: 14px;">
                        @csrf
                        <div class="field">
                            <label for="disable_password">Confirm Password to Disable</label>
                            <div class="input-wrap">
                                <input id="disable_password" name="password" type="password" required autocomplete="current-password" placeholder="Enter your password">
                                <span class="eye" onclick="togglePassword('disable_password')"><i class="far fa-eye"></i></span>
                            </div>
                        </div>
                        <button type="submit" class="btn-update" style="background: #ef4444;">Disable Two-Factor Authentication</button>
                    </form>
                </div>
            @else
                <!-- 2FA Disabled State -->
                <div class="two-factor-warning" style="margin-top: 14px;">
                    <div class="warning-box">
                        <div class="warning-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="warning-content">
                            <strong class="warning-title">Two-Factor Authentication Disabled</strong>
                            <p class="warning-text">Enable 2FA to protect your account from unauthorized access.</p>
                            <div class="warning-actions">
                                <button type="button" class="btn-enable-2fa" onclick="show2FASetup()">Enable Two-Factor Authentication</button>
                                <div class="auth-app-logo">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="opacity: 0.6;">
                                        <circle cx="12" cy="12" r="10" fill="#4285F4"/>
                                        <circle cx="18" cy="8" r="6" fill="#34A853"/>
                                        <rect x="6" y="6" width="12" height="12" rx="2" fill="white"/>
                                        <text x="12" y="16" font-family="Arial" font-size="10" font-weight="bold" fill="#34A853" text-anchor="middle">M</text>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2FA Setup Modal/Form (Hidden by default) -->
                <div class="two-factor-setup" id="twoFactorSetup" style="display: none; margin-top: 16px; padding-top: 16px; border-top: 1px solid #eef0f4;">
                    <div class="setup-content">
                        <h4 style="margin: 0 0 8px 0; font-size: 13px; color: #111827;">Scan QR Code</h4>
                        <p style="margin: 0 0 14px 0; font-size: 11px; color: #6b7280;">Scan this QR code with your authenticator app (Google Authenticator, Authy, etc.)</p>

                        @if($twoFactorQrCode ?? false)
                            <div class="qr-code-container">
                                <div class="qr-code">
                                    {!! $twoFactorQrCode !!}
                                </div>
                            </div>
                            <p style="margin: 14px 0 8px 0; font-size: 11px; color: #6b7280; text-align: center;">Or enter this code manually:</p>
                            <div class="manual-code">
                                <code style="font-size: 12px; color: #111827; background: #f9fafb; padding: 8px 12px; border-radius: 6px; display: block; text-align: center; letter-spacing: 2px; font-family: monospace;">{{ $twoFactorSecret ?? '' }}</code>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.two-factor.enable') }}" class="form-stack" style="margin-top: 16px;">
                            @csrf
                            <div class="field">
                                <label for="two_factor_code">Enter Verification Code</label>
                                <div class="input-wrap">
                                    <input id="two_factor_code" name="code" type="text" maxlength="25" required autocomplete="one-time-code" placeholder="Enter code" style="text-align: center; letter-spacing: 2px; font-size: 16px;">
                                </div>
                                @if($errors->has('code'))
                                    <div class="invalid-feedback" style="display:block; color: #ef4444; font-size: 11px; margin-top: 4px;">{{ $errors->first('code') }}</div>
                                @endif
                            </div>
                            <button type="submit" class="btn-update">Verify and Enable</button>
                            <button type="button" class="btn-cancel-2fa" onclick="hide2FASetup()">Cancel</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<style>
    .two-factor-warning {
        margin-top: 10px;
    }
    .warning-box {
        background: #fef3c7;
        border: 1px solid #fde68a;
        border-radius: 10px;
        padding: 14px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .warning-icon {
        color: #f59e0b;
        font-size: 18px;
        margin-top: 2px;
    }
    .warning-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    .warning-title {
        color: #f59e0b;
        font-size: 12px;
        margin: 0;
    }
    .warning-text {
        color: #d97706;
        font-size: 11px;
        margin: 0;
    }
    .warning-actions {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-top: 8px;
    }
    .btn-enable-2fa {
        background: linear-gradient(90deg, #0d0d0f, #1c1d21);
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    .btn-enable-2fa:hover {
        opacity: 0.9;
    }
    .auth-app-logo {
        opacity: 0.5;
    }
    .qr-code-container {
        display: flex;
        justify-content: center;
        margin: 14px 0;
    }
    .qr-code {
        width: 200px;
        height: 200px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 8px;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .qr-code svg {
        width: 100%;
        height: 100%;
        max-width: 184px;
        max-height: 184px;
    }
    .manual-code {
        margin-bottom: 14px;
    }
    .btn-cancel-2fa {
        background: transparent;
        color: #6b7280;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 11px;
        font-size: 12px;
        cursor: pointer;
        margin-top: 4px;
        width: 100%;
    }
    .two-factor-enabled {
        margin-top: 10px;
    }
    .two-factor-status {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px;
        background: #d1fae5;
        border: 1px solid #6ee7b7;
        border-radius: 10px;
    }
    .two-factor-status.success {
        color: #065f46;
    }
    .two-factor-status.success i {
        font-size: 18px;
    }
    .two-factor-status strong {
        display: block;
        font-size: 12px;
        margin-bottom: 2px;
    }
    .two-factor-status p {
        margin: 0;
        font-size: 11px;
        opacity: 0.8;
    }

    /* Dark theme support */
    html[data-theme="dark"] .warning-box {
        background: #78350f;
        border-color: #92400e;
    }
    html[data-theme="dark"] .warning-title {
        color: #fde68a;
    }
    html[data-theme="dark"] .warning-text {
        color: #fcd34d;
    }
    html[data-theme="dark"] .two-factor-setup {
        border-top-color: #2d3138;
    }
    html[data-theme="dark"] .qr-code {
        background: #1a1d23;
        border-color: #2d3138;
    }
    html[data-theme="dark"] .manual-code code {
        background: #252932;
        color: #e5e7eb;
    }
    html[data-theme="dark"] .btn-cancel-2fa {
        border-color: #2d3138;
        color: #9ca3af;
    }
    html[data-theme="dark"] .two-factor-status {
        background: #064e3b;
        border-color: #065f46;
        color: #d1fae5;
    }
    html[data-theme="dark"] .recovery-codes-box {
        background: #78350f;
        border-color: #92400e;
    }
    html[data-theme="dark"] .recovery-codes-box p {
        color: #fde68a;
    }
    html[data-theme="dark"] .recovery-codes-box code {
        background: #1a1d23;
        color: #fcd34d;
    }
</style>

<script>
    function show2FASetup() {
        document.getElementById('twoFactorSetup').style.display = 'block';
    }
    function hide2FASetup() {
        document.getElementById('twoFactorSetup').style.display = 'none';
    }
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        const icon = input.nextElementSibling.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
    // Auto-format 2FA code input - allow digits and dashes for recovery codes
    document.addEventListener('DOMContentLoaded', function() {
        const codeInput = document.getElementById('two_factor_code');
        if (codeInput) {
            codeInput.addEventListener('input', function(e) {
                // Allow digits, letters, and dashes, remove spaces
                e.target.value = e.target.value.replace(/[^\dA-Za-z-]/g, '');
            });
        }
    });
</script>
@endsection
