@extends('layouts.admin')
@section('content')
<style>
    .profile-shell { padding: 0px 0px 8px; }
    .profile-frame { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 14px; }
    .profile-head { display: flex; align-items: center; justify-content: space-between; padding: 14px 18px;  }
    .profile-head-left { display: flex; flex-direction: column; gap: 4px; }
    .profile-title { font-weight: 700; color: #111827; font-size: 16px; margin: 0; }
    .profile-sub { color: #6b7280; font-size: 12px; margin: 0; }
    .profile-actions { display: flex; align-items: center; gap: 8px; }
    .profile-action { background: #0f172a; color: #fff; border: none; border-radius: 8px; padding: 10px 14px; font-size: 12px; box-shadow: 0 8px 18px rgba(0,0,0,0.14); }
    .back-btn { border: 1px solid #e5e7eb; background: #fff; color: #111827; border-radius: 8px; padding: 10px 14px; font-size: 12px; }
    .privacy-btn { border: 1px solid #e5e7eb; background: #fff; color: #111827; border-radius: 8px; padding: 10px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 6px; }

    .profile-card { background: #fff; border: 1px solid #edf0f3; border-radius: 12px; padding: 18px 18px 16px; box-shadow: 0 10px 22px rgba(15,23,42,0.06); }
    .profile-card + .profile-card { margin-top: 6px; }
    .section-title { font-weight: 700; color: #111827; font-size: 13px; margin: 0 0 12px 0; }
    .section-sub { color: #6b7280; font-size: 12px; margin-bottom: 14px; }

    .avatar-row { display: flex; align-items: center; gap: 12px; padding: 6px 0; flex-wrap: wrap; }
    .avatar-circle { width: 56px; height: 56px; border-radius: 50%; background: #0f172a; display: grid; place-items: center; color: #fff; font-weight: 700; overflow: hidden; }
    .avatar-circle img { width: 100%; height: 100%; object-fit: cover; display: block; }
    .avatar-meta { display: flex; flex-direction: column; gap: 4px; }
    .avatar-name { font-weight: 700; color: #111827; font-size: 13px; }
    .avatar-hint { color: #9ca3af; font-size: 11px; }
    .upload-input { border: 1px dashed #d1d5db; padding: 10px 12px; border-radius: 10px; background: #f9fafb; font-size: 12px; color: #4b5563; }

    .info-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 12px; }
    .field-block { display: flex; flex-direction: column; gap: 6px; }
    .field-label { color: #6b7280; font-size: 11px; text-transform: uppercase; letter-spacing: 0.4px; margin: 0; }
    .field-input { width: 100%; border: 1px solid #eef0f3; background: #f9fafb; border-radius: 10px; padding: 11px 12px; font-size: 12px; color: #1f2937; }

    .about-box { width: 100%; border: 1px solid #eef0f3; background: #f9fafb; border-radius: 10px; padding: 12px; font-size: 12px; color: #1f2937; min-height: 90px; }

    .mini-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 12px; }

    @media (max-width: 640px) {
        .profile-head { flex-direction: column; align-items: flex-start; gap: 10px; }
    }
</style>

@php
    $admin = auth('admin')->user();
    $initials = $admin && ($admin->first_name || $admin->last_name || $admin->name)
        ? collect(explode(' ', trim(($admin->first_name ?: '').' '.($admin->last_name ?: '') ?: $admin->name)))->map(fn($p) => substr($p,0,1))->implode('')
        : 'AU';
@endphp
<div class="profile-shell">
    <div class="profile-frame">
        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="profile-head">
                <div class="profile-head-left">
                    <p class="profile-title">My Profile</p>
                    <p class="profile-sub">Manage your personal information</p>
                </div>
                <div class="profile-actions">
                        <a href="{{ route('profile.password.edit') }}" class="privacy-btn" title="Privacy &amp; Security">
                            <i class="fas fa-shield-alt"></i>
                            Privacy Setup
                        </a>
                    <button type="button" class="back-btn" onclick="window.history.back();">Back</button>
                    <button type="submit" class="profile-action">Save</button>
                </div>
            </div>

            <div class="profile-card">
                <p class="section-title">Profile Photo</p>
                <div class="avatar-row">
                    <div class="avatar-circle">
                        @if($admin->profile_photo_path ?? false)
                            <img src="{{ asset('storage/'.$admin->profile_photo_path) }}" alt="Profile photo">
                        @else
                            {{ $initials }}
                        @endif
                    </div>
                    <div class="avatar-meta">
                        <span class="avatar-name">Profile Picture</span>
                        <span class="avatar-hint">JPG or PNG. Max size of 5MB.</span>
                        <input class="upload-input" type="file" name="profile_photo" accept="image/*">
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <p class="section-title">Personal Information</p>
                <div class="info-grid">
                    <div class="field-block">
                        <label class="field-label">First Name</label>
                        <input name="first_name" class="field-input" type="text" value="{{ old('first_name', $admin->first_name) }}" placeholder="Admin">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Last Name</label>
                        <input name="last_name" class="field-input" type="text" value="{{ old('last_name', $admin->last_name) }}" placeholder="User">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Email Address</label>
                        <input name="email" class="field-input" type="email" value="{{ old('email', $admin->email) }}" placeholder="admin@bricks.studio">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Phone Number</label>
                        <input name="phone_number" class="field-input" type="text" value="{{ old('phone_number', $admin->phone_number) }}" placeholder="+971 123 123 4567">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Location</label>
                        <input name="location" class="field-input" type="text" value="{{ old('location', $admin->location) }}" placeholder="Dubai, UAE">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Website</label>
                        <input name="website" class="field-input" type="text" value="{{ old('website', $admin->website) }}" placeholder="bricks.studio">
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <p class="section-title">About</p>
                <textarea name="bio" class="about-box" placeholder="Tell something about yourself...">{{ old('bio', $admin->bio) }}</textarea>
            </div>

            <div class="profile-card">
                <p class="section-title">Professional Information</p>
                <div class="mini-grid">
                    <div class="field-block">
                        <label class="field-label">Role</label>
                        <input name="role_title" class="field-input" type="text" value="{{ old('role_title', $admin->role_title) }}" placeholder="Studio Manager">
                    </div>
                    <div class="field-block">
                        <label class="field-label">Member Since</label>
                        <input name="member_since" class="field-input" type="text" value="{{ old('member_since', $admin->member_since) }}" placeholder="January 2024">
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
