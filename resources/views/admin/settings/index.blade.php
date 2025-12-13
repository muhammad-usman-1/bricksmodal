@extends('layouts.admin')
@section('content')
<style>
    .settings-shell { padding: 5px 12px 28px; }
    .settings-frame { max-width: 1240px; margin: 0 auto; }
    .settings-top { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
    .back-btn { border: none; background: transparent; color: #2c2d30; font-size: 14px; display: inline-flex; align-items: center; gap: 8px; padding: 6px 4px; cursor: pointer; }
    .primary-btn { background: #0f0f11; color: #fff; border: none; border-radius: 8px; padding: 10px 16px; font-size: 13px; box-shadow: 0 8px 18px rgba(0,0,0,0.14); }

    .settings-title { font-size: 18px; font-weight: 600; color: #2b2d33; margin: 4px 0; }
    .settings-sub { color: #7a7f8a; font-size: 12px; }

    .card-block { background: #fff; border: 1px solid #e6e6ea; border-radius: 12px; box-shadow: 0 10px 28px rgba(15, 23, 42, 0.08); padding: 12px 14px; }
    .card-block + .card-block { margin-top: 14px; }
    .card-head { display: flex; align-items: center; gap: 10px; margin-bottom: 10px; }
    .card-head .icon-circle { width: 32px; height: 32px; border-radius: 50%; background: #f0f4ff; color: #2455ff; display: grid; place-items: center; font-size: 14px; }
    .card-title { margin: 0; font-weight: 600; color: #2c2d33; font-size: 14px; }
    .card-sub { margin: 0; color: #8b8f99; font-size: 12px; }

    .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 10px 0; border-top: 1px solid #f1f2f4; }
    .toggle-row:first-of-type { border-top: none; }
    .toggle-text { display: flex; flex-direction: column; gap: 2px; }
    .toggle-label { font-size: 13px; color: #2c2d33; }
    .toggle-hint { font-size: 11px; color: #9aa0ab; }

    .switch { position: relative; width: 40px; height: 22px; display: inline-block; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; inset: 0; background: #d9d9de; border-radius: 999px; transition: all 0.2s ease; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 2px; bottom: 2px; background: #fff; border-radius: 50%; box-shadow: 0 2px 8px rgba(0,0,0,0.12); transition: all 0.2s ease; }
    input:checked + .slider { background: #101014; }
    input:checked + .slider:before { transform: translateX(18px); }

    .inline-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 12px; margin-top: 6px; }
    .field { display: flex; flex-direction: column; gap: 6px; }
    .field label { font-size: 12px; color: #6f7480; }
    .select-lite { position: relative; }
    .select-lite select { appearance: none; width: 100%; padding: 12px 14px; border: 1px solid #e1e4ea; border-radius: 8px; background: #fff; font-size: 12px; color: #4a4f59; outline: none; }
    .select-lite:after { content: '\25BE'; position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #9aa0ab; font-size: 12px; pointer-events: none; }

    .appearance-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 12px; margin-top: 10px; }
    .appearance-card { border: 1px solid #e1e4ea; border-radius: 10px; padding: 14px; display: grid; place-items: center; gap: 8px; background: #fff; cursor: pointer; position: relative; }
    .appearance-card.active { border-color: #0f1014; box-shadow: 0 10px 24px rgba(15,16,20,0.12); }
    .appearance-card .icon-wrap { width: 42px; height: 42px; border-radius: 12px; background: #f4f5f8; display: grid; place-items: center; font-size: 16px; color: #2c2d33; }
    .appearance-card.active .icon-wrap { background: #0f1014; color: #fff; }
    .appearance-card span { font-size: 12px; color: #2c2d33; }
    .appearance-card input { position: absolute; inset: 0; opacity: 0; cursor: pointer; }

    @media (max-width: 640px) {
        .settings-top { flex-direction: column; align-items: flex-start; gap: 10px; }
    }

    /* Dark theme styles for settings page */
    html[data-theme="dark"] .settings-shell {
        background: #0f1114;
    }

    html[data-theme="dark"] .back-btn {
        color: #d1d5db;
    }

    html[data-theme="dark"] .back-btn:hover {
        color: #f3f4f6;
    }

    html[data-theme="dark"] .primary-btn {
        background: #3b82f6;
        box-shadow: 0 8px 18px rgba(59, 130, 246, 0.3);
    }

    html[data-theme="dark"] .primary-btn:hover {
        background: #2563eb;
    }

    html[data-theme="dark"] .card-block {
        background: #1a1d23;
        border-color: #2d3138;
        box-shadow: 0 10px 28px rgba(0, 0, 0, 0.3);
    }

    html[data-theme="dark"] .card-head .icon-circle {
        background: #252932;
        color: #3b82f6;
    }

    html[data-theme="dark"] .toggle-row {
        border-top-color: #2d3138;
    }

    html[data-theme="dark"] .appearance-card {
        background: #1a1d23;
        border-color: #2d3138;
    }

    html[data-theme="dark"] .appearance-card:hover {
        border-color: #3b82f6;
    }

    html[data-theme="dark"] .appearance-card.active {
        border-color: #3b82f6;
        box-shadow: 0 10px 24px rgba(59, 130, 246, 0.3);
    }

    html[data-theme="dark"] .appearance-card .icon-wrap {
        background: #252932;
        color: #9ca3af;
    }

    html[data-theme="dark"] .appearance-card.active .icon-wrap {
        background: #3b82f6;
        color: #fff;
    }

    html[data-theme="dark"] .appearance-card span {
        color: #e5e7eb;
    }
</style>

<div class="settings-shell">
    <div class="settings-frame">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            <div class="settings-top">
                <div style="display:flex; align-items:center; gap:10px;">
                    <button class="back-btn" type="button" onclick="window.history.back();"><i class="fas fa-arrow-left"></i></button>
                    <div>
                        <div class="settings-title">Account Settings</div>
                        <div class="settings-sub">Manage your preferences and notifications</div>
                    </div>
                </div>
                <button class="primary-btn" type="submit">Save Changes</button>
            </div>

            <div class="card-block">
                <div class="card-head">
                    <div class="icon-circle"><i class="fas fa-bell"></i></div>
                    <div>
                        <p class="card-title">Notifications</p>
                        <p class="card-sub">Control how you receive notifications</p>
                    </div>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">Email Notifications</span>
                        <span class="toggle-hint">Receive notifications via email</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="email_notifications" value="0">
                        <input type="checkbox" name="email_notifications" value="1" {{ $settings->email_notifications ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">Push Notifications</span>
                        <span class="toggle-hint">Receive push notifications in browser</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="push_notifications" value="0">
                        <input type="checkbox" name="push_notifications" value="1" {{ $settings->push_notifications ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">Talent Updates</span>
                        <span class="toggle-hint">Get notified about talent profile changes</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="talent_updates" value="0">
                        <input type="checkbox" name="talent_updates" value="1" {{ $settings->talent_updates ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">Shoot Reminders</span>
                        <span class="toggle-hint">Reminders for upcoming shoots</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="shoot_reminders" value="0">
                        <input type="checkbox" name="shoot_reminders" value="1" {{ $settings->shoot_reminders ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">Payment Alerts</span>
                        <span class="toggle-hint">Notifications for payment activities</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="payment_alerts" value="0">
                        <input type="checkbox" name="payment_alerts" value="1" {{ $settings->payment_alerts ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
                <div class="toggle-row">
                    <div class="toggle-text">
                        <span class="toggle-label">System Updates</span>
                        <span class="toggle-hint">Updates about new features and improvements</span>
                    </div>
                    <label class="switch">
                        <input type="hidden" name="system_updates" value="0">
                        <input type="checkbox" name="system_updates" value="1" {{ $settings->system_updates ? 'checked' : '' }}>
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <div class="card-block">
                <div class="card-head">
                    <div class="icon-circle"><i class="fas fa-language"></i></div>
                    <div>
                        <p class="card-title">Language &amp; Region</p>
                        <p class="card-sub">Customize language and regional preferences</p>
                    </div>
                </div>
                <div class="inline-grid">
                    <div class="field">
                        <label>Language</label>
                        <div class="select-lite">
                            <select name="language">
                                <option value="">Choose your language</option>
                                @foreach(['English','Arabic','French'] as $lang)
                                    <option value="{{ $lang }}" {{ $settings->language === $lang ? 'selected' : '' }}>{{ $lang }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label>Timezone</label>
                        <div class="select-lite">
                            <select name="timezone">
                                <option value="">Choose your time zone</option>
                                @foreach(['UTC','Asia/Kuwait','Europe/London'] as $tz)
                                    <option value="{{ $tz }}" {{ $settings->timezone === $tz ? 'selected' : '' }}>{{ $tz }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-block">
                <div class="card-head">
                    <div class="icon-circle"><i class="far fa-clock"></i></div>
                    <div>
                        <p class="card-title">Date &amp; Time</p>
                        <p class="card-sub">Customize how dates and times are displayed</p>
                    </div>
                </div>
                <div class="inline-grid">
                    <div class="field">
                        <label>Date Format</label>
                        <div class="select-lite">
                            <select name="date_format">
                                @foreach(['Choose your date format','MM/DD/YYYY','DD/MM/YYYY','YYYY-MM-DD'] as $format)
                                    <option value="{{ $format === 'Choose your date format' ? '' : $format }}" {{ $settings->date_format === $format ? 'selected' : '' }}>{{ $format }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="field">
                        <label>Time Format</label>
                        <div class="select-lite">
                            <select name="time_format">
                                @foreach(['Choose your time format','12-hour','24-hour'] as $format)
                                    <option value="{{ $format === 'Choose your time format' ? '' : $format }}" {{ $settings->time_format === $format ? 'selected' : '' }}>{{ $format }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-block">
                <div class="card-head">
                    <div class="icon-circle"><i class="fas fa-adjust"></i></div>
                    <div>
                        <p class="card-title">Appearance</p>
                        <p class="card-sub">Customize the look and feel of the app</p>
                    </div>
                </div>
                <div class="appearance-grid">
                    @php $appearance = $settings->appearance; @endphp
                    <label class="appearance-card {{ $appearance === 'light' ? 'active' : '' }}">
                        <input type="radio" name="appearance" value="light" {{ $appearance === 'light' ? 'checked' : '' }}>
                        <div class="icon-wrap"><i class="fas fa-sun"></i></div>
                        <span>Light</span>
                    </label>
                    <label class="appearance-card {{ $appearance === 'dark' ? 'active' : '' }}">
                        <input type="radio" name="appearance" value="dark" {{ $appearance === 'dark' ? 'checked' : '' }}>
                        <div class="icon-wrap"><i class="fas fa-moon"></i></div>
                        <span>Dark</span>
                    </label>
                    <label class="appearance-card {{ $appearance === 'system' ? 'active' : '' }}">
                        <input type="radio" name="appearance" value="system" {{ $appearance === 'system' ? 'checked' : '' }}>
                        <div class="icon-wrap"><i class="fas fa-desktop"></i></div>
                        <span>System</span>
                    </label>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    // Toggle active styling when selecting an appearance card so UI reflects the chosen value instantly
    document.addEventListener('DOMContentLoaded', () => {
        const cards = Array.from(document.querySelectorAll('.appearance-card'));
        cards.forEach(card => {
            const input = card.querySelector('input[type="radio"]');
            input.addEventListener('change', () => {
                cards.forEach(c => c.classList.remove('active'));
                card.classList.add('active');
            });
        });
    });
</script>
@endsection
