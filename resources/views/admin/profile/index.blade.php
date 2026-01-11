@extends('layouts.admin')
@section('content')
<style>


    .profile-frame { max-width: 1200px; margin: 0 auto; display: flex; flex-direction: column; gap: 20px; }
    .profile-shell { margin-bottom: 10px; }
    .profile-head { display: flex; align-items: center; gap: 15px; margin-bottom: 20px; }
    .back-arrow { color: #6b7280; font-size: 18px; cursor: pointer; }
    .profile-head-text { display: flex; flex-direction: column; }
    .profile-title { font-weight: 400; color: #111827; font-size: 24px; margin: 0; }
    .profile-sub { color: #6b7280; font-size: 14px; margin: 2px 0 0 0; }
    .profile-actions { margin-left: auto; display: flex; gap: 10px; align-items: center; }
    .profile-btn { border: 1px solid #e5e7eb; background: #ffffff; color: #111827; border-radius: 10px; padding: 10px 14px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .profile-btn:hover { background: #f3f4f6; text-decoration: none; color: #0f172a; }
    /* unify logout with same style */

    .profile-card { background: #fff; border: 1px solid #edf0f3; border-radius: 16px; padding: 24px; box-shadow: 0 4px 12px rgba(0,0,0,0.03); position: relative; }
    .section-title { font-weight: 600; color: #111827; font-size: 15px; margin: 0 0 24px 0; }
    
    .card-edit-btn { position: absolute; top: 20px; right: 20px; background: #1a1d23; color: #fff; border: none; border-radius: 8px; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center; font-size: 14px; cursor: pointer; }

    .avatar-row { display: flex; align-items: center; gap: 20px; }
    .avatar-circle { width: 80px; height: 80px; border-radius: 50%; background: #000; display: grid; place-items: center; color: #fff; font-weight: 700; font-size: 24px; overflow: hidden; }
    .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
    .avatar-info { display: flex; flex-direction: column; gap: 4px; }
    .avatar-label { font-weight: 600; color: #111827; font-size: 14px; }
    .avatar-hint { color: #9ca3af; font-size: 13px; }

    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .field-block { display: flex; flex-direction: column; gap: 8px; }
    .field-label { color: #111827; font-size: 13px; font-weight: 500; }
    .field-wrapper { position: relative; }
    .field-icon { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: #9ca3af; font-size: 14px; }
    .field-input { width: 100%; border: 1px solid #f3f4f6; background: #f9fafb; border-radius: 10px; padding: 12px 12px 12px 40px; font-size: 14px; color: #111827; outline: none; }
    .field-input::placeholder { color: #9ca3af; }

    .about-textarea { width: 100%; border: 1px solid #f3f4f6; background: #f9fafb; border-radius: 10px; padding: 16px; font-size: 14px; color: #111827; min-height: 100px; resize: none; outline: none; }

    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
    }

    .save-btn-container { display: flex; justify-content: flex-end; margin-top: 10px; }
    .save-btn { background: #1a1d23; color: #fff; border: none; border-radius: 10px; padding: 12px 24px; font-size: 14px; font-weight: 600; cursor: pointer; }
</style>

@php
    $admin = auth('admin')->user();
    $initials = $admin && ($admin->first_name || $admin->last_name || $admin->name)
        ? collect(explode(' ', trim(($admin->first_name ?: '').' '.($admin->last_name ?: '') ?: $admin->name)))->map(fn($p) => substr($p,0,1))->implode('')
        : 'AU';
@endphp

<div class="profile-shell">
    <div class="profile-frame">
        <div class="profile-head">
            <i class="fa fa-angle-left back-arrow" onclick="window.history.back()"></i>
            <div class="profile-head-text">
                <p class="profile-title">My Profile</p>
                <p class="profile-sub">Manage your personal information</p>
            </div>
            <div class="profile-actions">
                <a class="profile-btn" href="{{ route('profile.password.edit') }}">
                    <i class="fas fa-shield-alt"></i> Privacy Setup
                </a>
                <form method="POST" action="{{ route('admin.logout') }}" style="margin:0;">
                    @csrf
                    <button type="submit" class="profile-btn">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Profile Photo Card -->
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
                    <div class="avatar-info">
                        <span class="avatar-label">Profile Picture</span>
                        <span class="avatar-hint">JPG or PNG. Max size of 5MB.</span>
                        <input type="file" name="profile_photo" style="margin-top: 8px; font-size: 12px;" accept="image/*">
                    </div>
                </div>
            </div>

            <!-- Personal Information Card -->
            <div class="profile-card" style="margin-top: 20px;">
                <p class="section-title">Personal Information</p>
                <button type="button" class="card-edit-btn" onclick="toggleEdit(this)"><i class="fas fa-pencil-alt"></i></button>
                <div class="info-grid">
                    <div class="field-block">
                        <label class="field-label">First Name</label>
                        <div class="field-wrapper">
                            <i class="far fa-user field-icon"></i>
                            <input name="first_name" class="field-input" type="text" value="{{ old('first_name', $admin->first_name) }}" placeholder="Admin" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Last Name</label>
                        <div class="field-wrapper">
                            <i class="far fa-user field-icon"></i>
                            <input name="last_name" class="field-input" type="text" value="{{ old('last_name', $admin->last_name) }}" placeholder="User" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Email Address</label>
                        <div class="field-wrapper">
                            <i class="far fa-envelope field-icon"></i>
                            <input name="email" class="field-input" type="email" value="{{ old('email', $admin->email) }}" placeholder="admin@bricks.studio" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Phone Number</label>
                        <div class="field-wrapper">
                            <i class="fa fa-phone field-icon"></i>
                            <input name="phone_number" class="field-input" type="text" value="{{ old('phone_number', $admin->phone_number) }}" placeholder="+971 50 123 4567" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Location</label>
                        <div class="field-wrapper">
                            <i class="fas fa-map-marker-alt field-icon"></i>
                            <input name="location" class="field-input" type="text" value="{{ old('location', $admin->location) }}" placeholder="Dubai, UAE" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Website</label>
                        <div class="field-wrapper">
                            <i class="fas fa-globe field-icon"></i>
                            <input name="website" class="field-input" type="text" value="{{ old('website', $admin->website) }}" placeholder="bricks.studio" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- About Card -->
            <div class="profile-card" style="margin-top: 20px;">
                <p class="section-title">About</p>
                <button type="button" class="card-edit-btn" onclick="toggleEdit(this)"><i class="fas fa-pencil-alt"></i></button>
                <div class="field-block">
                    <label class="field-label">Bio</label>
                    <textarea name="bio" class="about-textarea" placeholder="Experienced studio manager with a passion for creating exceptional visual content..." readonly>{{ old('bio', $admin->bio) }}</textarea>
                </div>
            </div>

            <!-- Professional Information Card -->
            <div class="profile-card" style="margin-top: 20px;">
                <p class="section-title">Professional Information</p>
                <div class="info-grid">
                    <div class="field-block">
                        <label class="field-label">Role</label>
                        <div class="field-wrapper">
                            <i class="fas fa-briefcase field-icon"></i>
                            <input name="role_title" class="field-input" type="text" value="{{ old('role_title', $admin->role_title) }}" placeholder="Studio Manager" readonly>
                        </div>
                    </div>
                    <div class="field-block">
                        <label class="field-label">Member Since</label>
                        <div class="field-wrapper">
                            <i class="far fa-calendar-alt field-icon"></i>
                            <input name="member_since" class="field-input" type="text" value="{{ old('member_since', $admin->member_since) }}" placeholder="January 2024" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <div class="save-btn-container">
                <button type="submit" class="save-btn">Update Profile</button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleEdit(btn) {
        const card = btn.closest('.profile-card');
        const inputs = card.querySelectorAll('input, textarea');
        const icon = btn.querySelector('i');

        const isReadonly = inputs[0].hasAttribute('readonly');

        inputs.forEach(input => {
            if (isReadonly) {
                input.removeAttribute('readonly');
                input.style.backgroundColor = '#fff';
                input.style.border = '1px solid #e5e7eb';
            } else {
                input.setAttribute('readonly', true);
                input.style.backgroundColor = '#f9fafb';
                input.style.border = '1px solid #f3f4f6';
            }
        });

        if (isReadonly) {
            icon.classList.remove('fa-pencil-alt');
            icon.classList.add('fa-check');
            btn.style.backgroundColor = '#10b981'; // Green for active edit
        } else {
            icon.classList.remove('fa-check');
            icon.classList.add('fa-pencil-alt');
            btn.style.backgroundColor = '#1a1d23';
        }
    }
</script>
@endsection
