@extends('layouts.talent')

@section('content')
<style>
    :root {
        --text-dark: #111827;
        --text-gray: #6b7280;
        --bg-light: #f9fafb;
        --card-bg: #ffffff;
        --border-color: #e5e7eb;
    }

    /* Override or ensure body settings if needed, though layout usually handles this */
    .profile-dashboard {
        margin-top: 10px;
        font-family: 'Inter', sans-serif; /* Ensure font matches design */
        color: var(--text-dark);
    }

    /* Common Card Style */
    .dash-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05); /* Subtle shadow per design */
    }

    /* Header Section */
    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .profile-info-wrap {
        display: flex;
        gap: 20px;
        align-items: center;
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background-color: #f3f4f6;
        background-size: cover;
        background-position: center;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        color: #9ca3af;
        border: 1px solid var(--border-color);
        flex-shrink: 0;
    }

    .profile-names h1 {
        font-size: 20px;
        font-weight: 700;
        margin: 0 0 4px 0;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .profile-meta {
        font-size: 14px;
        color: var(--text-gray);
        display: flex;
        flex-direction: column;
        gap: 2px;
        line-height: 1.4;
    }

    .rating-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 600;
        color: #111827;
        margin-top: 6px;
    }
    .rating-badge i {
        color: #fca510; /* Star color */
        font-size: 12px;
    }

    .btn-edit-profile {
        background: #111827;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 8px 16px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex; /* Changed to inline-flex for better alignment */
        align-items: center;
        gap: 8px;
        cursor: pointer;
        text-decoration: none;
        transition: background 0.2s;
    }
    .btn-edit-profile:hover {
        background: #1f2937;
        color: #fff;
        text-decoration: none;
    }

    /* Stats Grid */
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 24px;
    }

    .stat-card {
        background: var(--card-bg);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 20px;
        display: flex;
        flex-direction: column;
    }

    .stat-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 12px;
        font-size: 13px;
        font-weight: 600;
        color: #374151;
    }
    .stat-header i {
        font-size: 14px;
        color: #111827; /* Dark icon */
    }

    .stat-value {
        font-size: 24px;
        font-weight: 700;
        color: var(--text-dark);
        margin-bottom: 4px;
        line-height: 1.2;
    }

    .stat-trend {
        font-size: 12px;
        font-weight: 500;
        margin-top: auto; /* Push to bottom if height varies */
    }
    .trend-up { color: #10b981; }
    .trend-neutral { color: #6b7280; }

    /* Measurements */
    .section-title-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 16px;
        font-size: 16px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .icon-box {
        background: #111827; /* Dark background for icon box */
        color: #fff;
        width: 24px;
        height: 24px;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
    }

    .measurements-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        margin-bottom: 32px;
    }

    .measurement-box {
        background: #f9fafb; /* Light gray bg for measurement items */
        border-radius: 8px;
        padding: 16px;
        border: 1px solid transparent; /* Optional */
    }

    .measure-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        margin-bottom: 4px;
        letter-spacing: 0.02em;
    }

    .measure-value {
        font-size: 18px; /* Slightly larger */
        font-weight: 700;
        color: #111827;
    }

    .measure-unit {
        font-size: 12px;
        font-weight: 500;
        color: #9ca3af;
        margin-left: 2px;
        vertical-align: middle;
    }

    /* Portfolio */
    .portfolio-section {
        margin-bottom: 32px;
    }

    .portfolio-header {
        display: flex;
        justify-content: space-between; /* Space betwen Title and 'View all' */
        align-items: center;
        margin-bottom: 12px;
    }

    .portfolio-header h3 {
        font-size: 14px;
        font-weight: 600;
        color: #374151;
        margin: 0;
    }

    .view-all-link {
        font-size: 12px;
        color: #9ca3af;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        font-weight: 500;
        transition: color 0.2s;
    }
    .view-all-link:hover {
        color: #6b7280;
    }

    .photo-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }

    .photo-item {
        aspect-ratio: 1; /* Square aspect ratio */
        border-radius: 8px;
        overflow: hidden;
        background: #f3f4f6;
        border: 1px solid #e5e7eb;
        position: relative;
    }

    .photo-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.3s;
    }
    .photo-item:hover img {
        transform: scale(1.02); /* Subtle zoom effect */
    }

    /* Upload overlay for edit mode */
    .photo-item .upload-overlay {
        position: absolute;
        inset: 0;
        background: rgba(15, 23, 42, 0.5);
        color: #fff;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 4px;
        font-size: 13px;
        font-weight: 600;
        backdrop-filter: blur(2px);
        z-index: 15;
        border-radius: 8px;
    }
    .is-editing .photo-item.is-editable .upload-overlay {
        display: flex !important;
    }
    .photo-item.is-editable {
        cursor: pointer;
    }
    .photo-item.is-editable input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
        z-index: 20;
        width: 100%;
        height: 100%;
    }
    .photo-item .upload-overlay img {
        width: 20px;
        height: 20px;
        filter: brightness(0) invert(1);
        object-fit: contain;
    }
    .photo-item .remove-photo-link {
        color: #ff4d4f !important;
        font-size: 11px;
        margin-top: 8px;
        cursor: pointer;
        text-decoration: underline;
    }
    .photo-item .remove-photo-link:hover {
        color: #ff7875 !important;
    }

    /* Footer / Rates */
    .rates-container {
        border-top: 1px solid var(--border-color);
        padding-top: 24px;
        display: flex;
        justify-content: space-between;
        align-items: flex-end; /* Align bottom */
    }

    .rate-block {
        display: flex;
        flex-direction: column;
    }

    .rate-label {
        font-size: 11px;
        font-weight: 700;
        color: #6b7280;
        margin-bottom: 4px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .rate-amount {
        font-size: 20px;
        font-weight: 700;
        color: #111827;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            gap: 16px;
        }
        .btn-edit-profile {
            width: 100%;
            justify-content: center;
        }
        .measurements-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .photo-grid {
            gap: 12px;
        }
    }

    @media (max-width: 480px) {
        .stats-grid {
            grid-template-columns: 1fr;
        }
        .photo-grid {
             /* Keep 3 columns or switch to scroll? 3 is usually tight on mobile but doable if no margin */
             gap: 8px;
        }
        .rates-container {
            flex-direction: column;
            gap: 16px;
        }
    }

    /* Edit Panel */
    .edit-card {
        border: 1px solid var(--border-color);
        box-shadow: 0 8px 22px rgba(17, 24, 39, 0.07);
    }
    .edit-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }
    .edit-card-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 700;
        color: var(--text-dark);
    }
    .edit-card-header p {
        margin: 4px 0 0;
        color: var(--text-gray);
        font-size: 13px;
    }
    .edit-actions {
        display: inline-flex;
        gap: 10px;
    }
    .btn-cancel-edit,
    .btn-save-edit {
        border: 1px solid var(--border-color);
        background: #ffffff;
        color: #111827;
        border-radius: 8px;
        padding: 9px 14px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .btn-cancel-edit:hover {
        background: #f3f4f6;
    }
    .btn-save-edit {
        background: #111827;
        color: #ffffff;
        border-color: #111827;
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.12);
    }
    .btn-save-edit:hover {
        background: #1f2937;
    }
    .edit-section-title {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        margin: 14px 0 10px;
    }
    .edit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 14px 16px;
    }
    .form-field label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        margin-bottom: 6px;
    }
    .form-control-lite,
    .textarea-control {
        width: 100%;
        border: 1px solid var(--border-color);
        border-radius: 8px;
        padding: 10px 12px;
        font-size: 14px;
        color: var(--text-dark);
        background: #ffffff;
    }
    .form-control-lite:focus,
    .textarea-control:focus {
        outline: none;
        border-color: #111827;
        box-shadow: 0 0 0 1px #11182714;
    }
    .textarea-control {
        min-height: 110px;
        resize: vertical;
    }
    .checkbox-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 10px;
    }
    .checkbox-pill {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 12px;
        background: #f9fafb;
        border: 1px solid var(--border-color);
        border-radius: 10px;
    }
    .checkbox-pill input {
        width: 16px;
        height: 16px;
    }
    .checkbox-pill span {
        font-size: 13px;
        color: #374151;
        font-weight: 600;
    }
    .file-note {
        font-size: 12px;
        color: #6b7280;
        margin-top: 4px;
    }

    /* Image Upload Tiles */
    .image-upload-tile:hover {
        border-color: #10b981;
        background: #f0fdf4;
    }
    .image-upload-tile:hover .image-overlay {
        display: flex !important;
    }
    .image-upload-tile img {
        border-radius: 12px;
    }
</style>

@php
    $resolveMediaUrl = function ($path) {
        if (! $path) {
            return null;
        }

        if (is_array($path)) {
            $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
        }

        if (! $path) {
            return null;
        }

        $isAbsolute = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//']);
        $awsUrl = rtrim((string) env('AWS_URL'), '/');
        $storage = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'));

        if ($isAbsolute) {
            if ($awsUrl && \Illuminate\Support\Str::startsWith($path, $awsUrl)) {
                $relative = ltrim(\Illuminate\Support\Str::after($path, $awsUrl), '/');
                try {
                    return $storage->temporaryUrl($relative, now()->addMinutes(60));
                } catch (\Exception $e) {
                    try {
                        return $storage->url($relative);
                    } catch (\Exception $e2) {
                        return $path;
                    }
                }
            }

            return $path;
        }

        $clean = ltrim($path, '/');
        try {
            return $storage->url($clean);
        } catch (\Exception $e) {
            try {
                return $storage->temporaryUrl($clean, now()->addMinutes(60));
            } catch (\Exception $e2) {
                return null;
            }
        }
    };

    $primaryAvatar = $resolveMediaUrl($profile->headshot_center_path ?? null);
@endphp

<div class="profile-dashboard">

    <!-- Top Card: Header -->
    <div class="dash-card profile-header">
        <div class="profile-info-wrap">
            <div class="profile-avatar" style="{{ $primaryAvatar ? 'background-image: url('.e($primaryAvatar).')' : '' }}">
                @if(! $primaryAvatar)
                    <span>{{ substr($profile->legal_name, 0, 1) }}</span>
                @endif
            </div>
            <div class="profile-names">
                <h1>{{ $profile->legal_name }}</h1>
                <div class="profile-meta">
                    <span>
                        {{ $profile->gender ? ucfirst($profile->gender) : 'Model' }}
                        @if($profile->date_of_birth)
                            • {{ \Carbon\Carbon::parse($profile->date_of_birth)->age }} Years
                        @endif
                    </span>
                    <span>{{ $profile->location ?? 'Location' }}</span> <!-- Location placeholder -->
                </div>
                <!-- Static Rating for Visual Parity -->
                <div class="rating-badge">
                    <i class="fas fa-star"></i> 4.5 (12 reviews)
                </div>
            </div>
        </div>

        <!-- Edit Profile Link -->
        <button type="button" class="btn-edit-profile" id="editProfileBtn">
            <i class="fas fa-pen"></i> Edit
        </button>
    </div>

    @php
        $openEditCard = $errors->any();
    @endphp

    <div class="dash-card edit-card {{ $openEditCard ? '' : 'd-none' }}" id="editCard" data-open-on-load="{{ $openEditCard ? '1' : '0' }}">
        <form method="POST" action="{{ route('talent.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="edit-card-header">
                <div>
                    <h3>Edit profile</h3>
                    <p>Update your public profile details, measurements, and portfolio.</p>
                </div>
                <div class="edit-actions">
                    <button type="button" class="btn-cancel-edit" id="cancelEditBtn">Cancel</button>
                    <button type="submit" class="btn-save-edit">Save changes</button>
                </div>
            </div>

            <div class="edit-section-title">Basic details</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="legal_name">Legal name</label>
                    <input id="legal_name" type="text" name="legal_name" class="form-control-lite" value="{{ old('legal_name', $profile->legal_name) }}" required>
                </div>
                <div class="form-field">
                    <label for="display_name">Display name</label>
                    <input id="display_name" type="text" name="display_name" class="form-control-lite" value="{{ old('display_name', $profile->display_name) }}">
                </div>
                <div class="form-field">
                    <label for="email">Email</label>
                    <input id="email" type="email" name="email" class="form-control-lite" value="{{ old('email', $profile->user->email ?? $profile->email) }}" required>
                </div>
                <div class="form-field">
                    <label for="date_of_birth">Date of birth</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" class="form-control-lite" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="form-field">
                    <label for="gender">Gender</label>
                    @php $genderValue = old('gender', $profile->gender); @endphp
                    <select id="gender" name="gender" class="form-control-lite">
                        <option value="">Select gender</option>
                        <option value="male" {{ $genderValue === 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ $genderValue === 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ $genderValue === 'other' ? 'selected' : '' }}>Other</option>
                        <option value="prefer_not_to_say" {{ $genderValue === 'prefer_not_to_say' ? 'selected' : '' }}>Prefer not to say</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="whatsapp_number">WhatsApp number</label>
                    <input id="whatsapp_number" type="text" name="whatsapp_number" class="form-control-lite" placeholder="+965 5xxxxxxx" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}">
                </div>
            </div>

            <div class="edit-section-title">Rates & Bio</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="daily_rate">Daily rate</label>
                    <input id="daily_rate" type="number" min="0" step="0.01" name="daily_rate" class="form-control-lite" value="{{ old('daily_rate', $profile->daily_rate) }}">
                </div>
                <div class="form-field">
                    <label for="rate">Rate</label>
                    <input id="rate" type="number" min="0" step="0.01" name="rate" class="form-control-lite" value="{{ old('rate', $profile->rate) }}">
                </div>
                <div class="form-field" style="grid-column: 1 / -1;">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" class="textarea-control" maxlength="1000" placeholder="Tell casting directors about your experience, strengths, and preferences.">{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>

            <div class="edit-section-title">Measurements & Appearance</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="height">Height (cm)</label>
                    <input id="height" type="number" step="0.1" min="0" name="height" class="form-control-lite" value="{{ old('height', $profile->height) }}">
                </div>
                <div class="form-field">
                    <label for="weight">Weight (kg)</label>
                    <input id="weight" type="number" step="0.1" min="0" name="weight" class="form-control-lite" value="{{ old('weight', $profile->weight) }}">
                </div>
                <div class="form-field">
                    <label for="chest">Chest (cm)</label>
                    <input id="chest" type="number" step="0.1" min="0" name="chest" class="form-control-lite" value="{{ old('chest', $profile->chest) }}">
                </div>
                <div class="form-field">
                    <label for="waist">Waist (cm)</label>
                    <input id="waist" type="number" step="0.1" min="0" name="waist" class="form-control-lite" value="{{ old('waist', $profile->waist) }}">
                </div>
                <div class="form-field">
                    <label for="hips">Hips (cm)</label>
                    <input id="hips" type="number" step="0.1" min="0" name="hips" class="form-control-lite" value="{{ old('hips', $profile->hips) }}">
                </div>
                <div class="form-field">
                    <label for="shoe_size">Shoe size (EU)</label>
                    <input id="shoe_size" type="number" step="0.5" min="0" name="shoe_size" class="form-control-lite" value="{{ old('shoe_size', $profile->shoe_size) }}">
                </div>
                <div class="form-field">
                    <label for="skin_tone">Skin tone</label>
                    @php $skinTone = old('skin_tone', $profile->skin_tone); @endphp
                    <select id="skin_tone" name="skin_tone" class="form-control-lite">
                        <option value="">Select skin tone</option>
                        @foreach($skinToneOptions as $value => $label)
                            <option value="{{ $value }}" {{ $skinTone === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="hair_color">Hair color</label>
                    <input id="hair_color" type="text" name="hair_color" class="form-control-lite" value="{{ old('hair_color', $profile->hair_color) }}">
                </div>
                <div class="form-field">
                    <label for="eye_color">Eye color</label>
                    <input id="eye_color" type="text" name="eye_color" class="form-control-lite" value="{{ old('eye_color', $profile->eye_color) }}">
                </div>
            </div>


            <div class="file-note" style="margin-top: 8px;">
                Max upload size: 6 MB for photos, 4 MB for ID images.
            </div>
        </form>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid">
        <!-- 1. Shoots Completed -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-camera"></i> Shoots completed
            </div>
            <div class="stat-value">{{ $shootsCompleted ?? 0 }}</div>
            <div class="stat-trend trend-up">Life time</div>
        </div>

        <!-- 2. Profile Views -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-eye"></i> Profile views
            </div>
            <div class="stat-value">2,341</div>
            <div class="stat-trend trend-up">+12% increase</div>
        </div>

        <!-- 3. Shortlisted -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-bookmark"></i> Shortlisted
            </div>
            <div class="stat-value">73%</div>
            <div class="stat-trend trend-up">Conversion rate</div>
        </div>

        <!-- 4. Last Active -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="far fa-clock"></i> Last active
            </div>
            <div class="stat-value">2h ago</div>
            <div class="stat-trend trend-neutral">In Kuwait</div>
        </div>
    </div>

    <!-- Measurements Section -->
    <div class="dash-card">
        <div class="section-title-row">
            <div class="icon-box"><i class="fas fa-ruler-combined"></i></div>
            <span>Measurements</span>
        </div>
        <div class="measurements-grid">
            <div class="measurement-box">
                <div class="measure-label">Height</div>
                <div class="measure-value">{{ $profile->height ?? '-' }} <span class="measure-unit">cm</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">Weight</div>
                <div class="measure-value">{{ $profile->weight ?? '-' }} <span class="measure-unit">kg</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">Waist</div>
                <div class="measure-value">{{ $profile->waist ?? '-' }} <span class="measure-unit">cm</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">Shoe Size</div>
                <div class="measure-value">{{ $profile->shoe_size ?? '-' }} <span class="measure-unit">EU</span></div>
            </div>
        </div>
    </div>

    <!-- Media Portfolio -->
    <div class="dash-card">
        <div class="section-title-row">
            <div class="icon-box"><i class="fas fa-images"></i></div>
            <span>Media Portfolio</span>
        </div>

        <!-- Headshots -->
        <div class="portfolio-section">
            <div class="portfolio-header">
                <h3>Headshots</h3>
                <a href="#" class="view-all-link">View all <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
            </div>
            <div class="photo-grid">
                <!-- Center -->
                <div class="photo-item is-editable" data-field="headshot_center_path">
                    @if($profile->headshot_center_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_center_path) }}" alt="Center Headshot" class="preview-img">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->headshot_center_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->headshot_center_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="headshot_center_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
                <!-- Left -->
                <div class="photo-item is-editable" data-field="headshot_left_path">
                    @if($profile->headshot_left_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_left_path) }}" alt="Left Headshot" class="preview-img">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->headshot_left_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->headshot_left_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="headshot_left_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
                <!-- Right -->
                <div class="photo-item is-editable" data-field="headshot_right_path">
                    @if($profile->headshot_right_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_right_path) }}" alt="Right Headshot" class="preview-img">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->headshot_right_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->headshot_right_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="headshot_right_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
            </div>
        </div>

        <!-- Full Body -->
        <div class="portfolio-section">
            <div class="portfolio-header">
                <h3>Full Body</h3>
                <a href="#" class="view-all-link">View all <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
            </div>
            <div class="photo-grid">
                 <!-- Front -->
                 <div class="photo-item is-editable" data-field="full_body_front_path">
                    @if($profile->full_body_front_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_front_path) }}" alt="Full Body Front" class="preview-img">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->full_body_front_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->full_body_front_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="full_body_front_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
                <!-- Right -->
                 <div class="photo-item is-editable" data-field="full_body_right_path">
                    @if($profile->full_body_right_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_right_path) }}" alt="Full Body Right" class="preview-img">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->full_body_right_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->full_body_right_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="full_body_right_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
                <!-- Back -->
                 <div class="photo-item is-editable" data-field="full_body_back_path">
                    @if($profile->full_body_back_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_back_path) }}" alt="Full Body Back" class="preview-img">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $profile->full_body_back_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                        @if($profile->full_body_back_path)
                        <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                        @endif
                    </div>
                    <input type="file" name="full_body_back_path" class="media-file-input" accept="image/*" style="display:none">
                </div>
            </div>
        </div>

        <!-- Additional Profile Images -->
        @php
            $additionalPhotos = $profile->media()->where('type', 'profile')->get();
        @endphp
        @if($additionalPhotos->count() > 0)
        <div class="portfolio-section">
            <div class="portfolio-header">
                <h3>Additional Photos</h3>
            </div>
            <div class="photo-grid" id="additionalPhotosGrid">
                @foreach($additionalPhotos as $media)
                    <div class="photo-item is-editable" data-media-id="{{ $media->id }}" style="position: relative;">
                        @if($media->file_path)
                            <img src="{{ $resolveMediaUrl($media->file_path) }}" alt="Additional Photo" class="preview-img">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                        @endif
                        <div class="upload-overlay">
                            <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                            <span class="upload-text">{{ $media->file_path ? 'Replace Photo' : 'Upload Photo' }}</span>
                            @if($media->file_path)
                            <span class="remove-photo-link" onclick="removeMediaImage(this, event)">Remove Photo</span>
                            @endif
                        </div>
                        <input type="file" name="additional_photos[]" class="media-file-input" accept="image/*" style="display:none">
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Footer / Rates -->
        <div class="rates-container">
            <div class="rate-block">
                <span class="rate-label">Rate</span>
                <span class="rate-amount">${{ $profile->rate ? number_format($profile->rate, 2) : '0.00' }}</span>
            </div>
            <div class="rate-block" style="text-align:right;">
                <span class="rate-label">Daily Rate</span>
                <span class="rate-amount">${{ $profile->daily_rate ? number_format($profile->daily_rate, 2) : '0.00' }}/day</span>
            </div>
        </div>

    </div>

    <!-- Note on Terms/Policies in footer -->
    <div style="margin-top: 24px;">
        <p style="font-size: 18px; color: #9ca3af; margin:0; display: flex; align-items: center; gap: 6px;">
            <span style="background: #fef3c7; color: #d97706; width: 4px; height: 16px; border-radius: 2px;"></span>
            Your profile details and portfolio are visible to casting directors. Please ensure they are always up to date.
        </p>
    </div>

    <!-- Upload Progress Modal -->
    <div id="upload-progress-modal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.75); z-index: 10000; align-items: center; justify-content: center; flex-direction: column;">
        <div style="background: #fff; border-radius: 12px; padding: 24px; width: 90%; max-width: 500px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04);">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px;">
                <h3 style="margin: 0; font-size: 18px; font-weight: 600; color: #1f2937;">Uploading Images</h3>
                <button type="button" id="upload-modal-close-btn" style="display: none; background: none; border: none; cursor: pointer; padding: 4px; color: #6b7280;" title="Close">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div id="upload-progress-list" style="max-height: 400px; overflow-y: auto;">
                <!-- Upload items will be inserted here -->
            </div>
            <div id="upload-modal-footer" style="margin-top: 20px; padding-top: 16px; border-top: 1px solid #e5e7eb; text-align: center;">
                <p id="upload-modal-status" style="margin: 0; font-size: 14px; color: #6b7280;">Preparing uploads...</p>
            </div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    // Upload Progress Modal Management
    const uploadModal = document.getElementById('upload-progress-modal');
    const uploadProgressList = document.getElementById('upload-progress-list');
    const uploadModalStatus = document.getElementById('upload-modal-status');
    const uploadModalCloseBtn = document.getElementById('upload-modal-close-btn');
    const uploadModalTracker = {
        items: new Map(), // id -> {fileName, progress, status, error}
        addItem: function(id, fileName) {
            this.items.set(id, { fileName, progress: 0, status: 'uploading', error: null });
            this.render();
        },
        updateProgress: function(id, progress) {
            const item = this.items.get(id);
            if (item) {
                item.progress = progress;
                this.render();
            }
        },
        markComplete: function(id) {
            const item = this.items.get(id);
            if (item) {
                item.status = 'complete';
                item.progress = 100;
                this.render();
            }
            this.checkAllComplete();
        },
        markError: function(id, error) {
            const item = this.items.get(id);
            if (item) {
                item.status = 'error';
                item.error = error;
                this.render();
            }
            this.checkAllComplete();
        },
        render: function() {
            if (!uploadProgressList) return;
            uploadProgressList.innerHTML = '';
            let allComplete = true;
            let hasError = false;

            this.items.forEach((item, id) => {
                const div = document.createElement('div');
                div.style.cssText = 'padding: 12px; margin-bottom: 8px; border: 1px solid #e5e7eb; border-radius: 8px; background: #f9fafb;';

                const fileName = document.createElement('div');
                fileName.style.cssText = 'font-size: 14px; font-weight: 500; color: #1f2937; margin-bottom: 8px; display: flex; align-items: center; justify-content: space-between;';
                fileName.innerHTML = `
                    <span>${this.escapeHtml(item.fileName)}</span>
                    <span style="font-size: 12px; color: ${item.status === 'complete' ? '#10b981' : item.status === 'error' ? '#ef4444' : '#6b7280'};">
                        ${item.status === 'complete' ? '✓ Ready' : item.status === 'error' ? '✗ Failed' : 'Uploading...'}
                    </span>
                `;
                div.appendChild(fileName);

                if (item.status === 'uploading') {
                    const progressBar = document.createElement('div');
                    progressBar.style.cssText = 'width: 100%; height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;';
                    const fill = document.createElement('div');
                    fill.style.cssText = `width: ${item.progress}%; height: 100%; background: #10b981; transition: width 0.3s ease;`;
                    progressBar.appendChild(fill);
                    div.appendChild(progressBar);
                    allComplete = false;
                } else if (item.status === 'error') {
                    const errorContainer = document.createElement('div');
                    errorContainer.style.cssText = 'margin-top: 8px;';

                    const errorMsg = document.createElement('div');
                    errorMsg.style.cssText = 'font-size: 12px; color: #ef4444; margin-bottom: 4px;';
                    errorMsg.textContent = item.error || 'Upload failed';
                    errorContainer.appendChild(errorMsg);

                    const retryBtn = document.createElement('button');
                    retryBtn.type = 'button';
                    retryBtn.textContent = 'Retry';
                    retryBtn.style.cssText = 'font-size: 12px; padding: 4px 12px; background: #10b981; color: #fff; border: none; border-radius: 4px; cursor: pointer; font-weight: 500;';
                    retryBtn.onclick = () => {
                        const photoItem = window.additionalPhotoItems?.find(i => i.id === id);
                        if (photoItem && window.uploadOnePhotoItem) {
                            photoItem.status = 'queued';
                            photoItem.error = null;
                            window.uploadOnePhotoItem(photoItem);
                        }
                    };
                    errorContainer.appendChild(retryBtn);
                    div.appendChild(errorContainer);
                    hasError = true;
                }

                uploadProgressList.appendChild(div);
            });

            if (allComplete && this.items.size > 0 && uploadModalStatus) {
                if (hasError) {
                    uploadModalStatus.innerHTML = 'Some uploads failed. Click "Retry" on failed items or check S3 CORS configuration.<br><small style="color: #9ca3af; margin-top: 4px; display: block;">CORS must allow PUT requests from: ' + window.location.origin + '</small>';
                    uploadModalStatus.style.color = '#ef4444';
                } else {
                    uploadModalStatus.textContent = 'All images uploaded successfully! Ready to submit.';
                    uploadModalStatus.style.color = '#10b981';
                }
                if (uploadModalCloseBtn) uploadModalCloseBtn.style.display = 'block';
            } else if (this.items.size > 0 && uploadModalStatus) {
                const uploading = Array.from(this.items.values()).filter(i => i.status === 'uploading').length;
                uploadModalStatus.textContent = `Uploading ${uploading} image${uploading !== 1 ? 's' : ''}...`;
                uploadModalStatus.style.color = '#6b7280';
                if (uploadModalCloseBtn) uploadModalCloseBtn.style.display = 'none';
            }
        },
        checkAllComplete: function() {
            const allComplete = Array.from(this.items.values()).every(item =>
                item.status === 'complete' || item.status === 'error'
            );
            if (allComplete && this.items.size > 0) {
                const hasError = Array.from(this.items.values()).some(item => item.status === 'error');
                if (!hasError) {
                    setTimeout(() => {
                        if (Array.from(this.items.values()).every(item => item.status === 'complete')) {
                            this.hide();
                        }
                    }, 2000);
                }
            }
        },
        show: function() {
            if (uploadModal) {
                uploadModal.style.display = 'flex';
            }
        },
        hide: function() {
            if (uploadModal) {
                uploadModal.style.display = 'none';
            }
        },
        clear: function() {
            this.items.clear();
            this.render();
        },
        escapeHtml: function(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
    };

    // Close modal button
    if (uploadModalCloseBtn) {
        uploadModalCloseBtn.addEventListener('click', () => {
            uploadModalTracker.hide();
        });
    }

    // Close modal when clicking outside (only if all uploads complete)
    if (uploadModal) {
        uploadModal.addEventListener('click', (e) => {
            if (e.target === uploadModal) {
                const allComplete = Array.from(uploadModalTracker.items.values()).every(item =>
                    item.status === 'complete' || item.status === 'error'
                );
                if (allComplete && uploadModalTracker.items.size > 0) {
                    uploadModalTracker.hide();
                }
            }
        });
    }

    // Additional Photos Upload Functionality
    window.additionalPhotoItems = [];
    const additionalPhotosInput = document.getElementById('additionalPhotosInput');
    const additionalPhotosUploadArea = document.getElementById('additionalPhotosUploadArea');
    const additionalPhotosPreview = document.getElementById('additionalPhotosPreview');
    const uploadedKeysContainer = document.getElementById('uploadedKeysContainer');

    async function presignPhoto(file) {
        const res = await fetch('{{ route("talent.profile.presign-additional-photo") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
            },
            credentials: 'same-origin',
            body: JSON.stringify({
                file_name: file.name,
                file_type: file.type,
            }),
        });

        if (!res.ok) {
            let msg = 'Could not prepare upload.';
            try {
                const data = await res.json();
                msg = data.message || msg;
            } catch (e) {}
            throw new Error(msg);
        }

        return await res.json();
    }

    function uploadToS3Put(url, headers, file, onProgress) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            xhr.open('PUT', url, true);
            if (headers && headers['Content-Type']) {
                xhr.setRequestHeader('Content-Type', headers['Content-Type']);
            } else if (file.type) {
                xhr.setRequestHeader('Content-Type', file.type);
            }

            xhr.upload.addEventListener('progress', (e) => {
                if (e.lengthComputable) {
                    onProgress(Math.round((e.loaded / e.total) * 100));
                }
            });

            xhr.onload = () => {
                if (xhr.status >= 200 && xhr.status < 300) {
                    resolve();
                } else {
                    const errorMsg = `Upload failed (S3): ${xhr.status} ${xhr.statusText}`;
                    console.error('S3 upload error:', xhr.status, xhr.responseText);
                    reject(new Error(errorMsg));
                }
            };
            xhr.onerror = () => {
                console.error('Network error during S3 upload', {
                    status: xhr.status,
                    statusText: xhr.statusText,
                    responseText: xhr.responseText
                });
                let errorMsg = 'Upload failed (network error).';
                if (xhr.status === 0) {
                    errorMsg = 'CORS error: S3 bucket must allow PUT requests from this domain. Please configure CORS on your S3 bucket.';
                } else if (xhr.status === 403) {
                    errorMsg = 'Access denied. Check S3 bucket permissions and CORS configuration.';
                } else if (xhr.status === 404) {
                    errorMsg = 'S3 endpoint not found. Check bucket name and region configuration.';
                }
                reject(new Error(errorMsg));
            };
            xhr.onabort = () => reject(new Error('Upload cancelled.'));
            xhr.send(file);
        });
    }

    async function uploadOnePhotoItem(item) {
        if (!item || !item.file) return;

        if (item.status === 'uploading' || item.status === 'done') return;

        item.status = 'uploading';
        item.progress = 0;
        item.error = null;
        updatePreviewProgress(item.id, 0);

        uploadModalTracker.show();
        uploadModalTracker.addItem(item.id, item.file.name);

        try {
            const presign = await presignPhoto(item.file);
            await uploadToS3Put(presign.url, presign.headers, item.file, (pct) => {
                item.progress = pct;
                updatePreviewProgress(item.id, pct);
                uploadModalTracker.updateProgress(item.id, pct);
            });

            item.key = presign.key;
            item.status = 'done';
            item.progress = 100;
            updatePreviewProgress(item.id, 100);
            uploadModalTracker.markComplete(item.id);
            syncHiddenKeys();
        } catch (e) {
            item.status = 'error';
            item.error = e && e.message ? e.message : 'Upload failed.';
            updatePreviewError(item.id, item.error);
            uploadModalTracker.markError(item.id, item.error);
        }
    }
    window.uploadOnePhotoItem = uploadOnePhotoItem;

    function syncHiddenKeys() {
        if (!uploadedKeysContainer) return;
        uploadedKeysContainer.innerHTML = '';
        window.additionalPhotoItems
            .filter(i => i.status === 'done' && i.key)
            .forEach(i => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'additional_photo_keys[]';
                input.value = i.key;
                uploadedKeysContainer.appendChild(input);
            });
    }

    function renderPreviews() {
        if (!additionalPhotosPreview) return;
        additionalPhotosPreview.innerHTML = '';
        if (window.additionalPhotoItems.length > 0) {
            window.additionalPhotoItems.forEach((item) => {
                const file = item.file;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'photo-preview-item';
                    div.dataset.photoItemId = item.id;
                    div.style.cssText = 'position: relative; width: 100px; height: 100px; border-radius: 8px; overflow: hidden; border: 1px solid #e5e7eb;';
                    div.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="width: 100%; height: 100%; object-fit: cover;">
                        <div class="upload-progress-container" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: rgba(0,0,0,0.2); z-index: 10;">
                            <div class="upload-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.5s ease;"></div>
                        </div>
                        <button type="button" class="remove-photo-btn" style="position: absolute; top: 4px; right: 4px; background: rgba(0,0,0,0.7); color: #fff; border: none; border-radius: 50%; width: 24px; height: 24px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 12px;">
                            <i class="fas fa-times"></i>
                        </button>
                    `;
                    additionalPhotosPreview.appendChild(div);
                    updatePreviewProgress(item.id, item.progress || 0);
                    if (item.status === 'error' && item.error) updatePreviewError(item.id, item.error);

                    // Add remove button handler
                    const removeBtn = div.querySelector('.remove-photo-btn');
                    if (removeBtn) {
                        removeBtn.addEventListener('click', () => {
                            window.additionalPhotoItems = window.additionalPhotoItems.filter(i => i.id !== item.id);
                            div.remove();
                            syncHiddenKeys();
                        });
                    }
                };
                reader.readAsDataURL(file);
            });
        }
    }

    function updatePreviewProgress(itemId, pct) {
        const el = additionalPhotosPreview?.querySelector(`[data-photo-item-id="${itemId}"] .upload-progress-bar`);
        if (el) el.style.width = `${pct}%`;
    }

    function updatePreviewError(itemId, message) {
        const wrapper = additionalPhotosPreview?.querySelector(`[data-photo-item-id="${itemId}"]`);
        if (!wrapper) return;
        wrapper.style.opacity = '0.6';
        wrapper.title = message || 'Upload failed.';
    }

    // Image compression utility
    async function compressImage(file, maxSizeMB = 10, quality = 0.75) {
        if (file.type.indexOf('image/') === -1) {
            return file;
        }

        return new Promise((resolve, reject) => {
            if (file.size <= maxSizeMB * 1024 * 1024) {
                resolve(file);
                return;
            }

            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let width = img.width;
                    let height = img.height;

                    const maxDim = 4000;
                    if (width > maxDim || height > maxDim) {
                        if (width > height) {
                            height *= maxDim / width;
                            width = maxDim;
                        } else {
                            width *= maxDim / height;
                            height = maxDim;
                        }
                    }

                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);

                    canvas.toBlob((blob) => {
                        const compressedFile = new File([blob], file.name, {
                            type: 'image/jpeg',
                            lastModified: Date.now(),
                        });
                        resolve(compressedFile);
                    }, 'image/jpeg', quality);
                };
                img.onerror = reject;
            };
            reader.onerror = reject;
        });
    }

    // Setup additional photos upload
    if (additionalPhotosInput && additionalPhotosUploadArea) {
        additionalPhotosUploadArea.addEventListener('click', () => {
            additionalPhotosInput.click();
        });

        additionalPhotosUploadArea.addEventListener('dragover', (e) => {
            e.preventDefault();
            additionalPhotosUploadArea.style.borderColor = '#10b981';
            additionalPhotosUploadArea.style.background = '#f0fdf4';
        });

        additionalPhotosUploadArea.addEventListener('dragleave', () => {
            additionalPhotosUploadArea.style.borderColor = '#e5e7eb';
            additionalPhotosUploadArea.style.background = '#f9fafb';
        });

        additionalPhotosUploadArea.addEventListener('drop', (e) => {
            e.preventDefault();
            additionalPhotosUploadArea.style.borderColor = '#e5e7eb';
            additionalPhotosUploadArea.style.background = '#f9fafb';

            const files = Array.from(e.dataTransfer.files).filter(f => f.type.startsWith('image/'));
            if (files.length > 0) {
                handleFilesSelected(files);
            }
        });

        additionalPhotosInput.addEventListener('change', function(e) {
            const files = Array.from(this.files).filter(f => f.type.startsWith('image/'));
            if (files.length > 0) {
                handleFilesSelected(files);
            }
        });
    }

    async function handleFilesSelected(files) {
        const filePromises = files.map(async (file) => {
            try {
                return await compressImage(file, 10, 0.75);
            } catch (err) {
                console.error('Compression error:', err);
                return file;
            }
        });

        const processedFiles = await Promise.all(filePromises);
        const validFiles = processedFiles.filter(f => f !== null);

        const newItems = validFiles.map(file => ({
            id: `${Date.now()}_${Math.random().toString(16).slice(2)}`,
            file,
            progress: 0,
            status: 'queued',
            key: null,
            error: null,
        }));

        window.additionalPhotoItems.push(...newItems);

        if (additionalPhotosInput) {
            additionalPhotosInput.value = '';
        }

        renderPreviews();
        syncHiddenKeys();

        uploadModalTracker.clear();
        if (newItems.length > 0) {
            uploadModalTracker.show();
        }

        newItems.forEach(item => uploadOnePhotoItem(item));
    }

    document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('editProfileBtn');
        const editCard = document.getElementById('editCard');
        const cancelBtn = document.getElementById('cancelEditBtn');

        const openEditCard = () => {
            if (!editCard) return;
            editCard.classList.remove('d-none');
            document.body.classList.add('is-editing');
            editCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
        };

        if (editBtn) {
            editBtn.addEventListener('click', function (e) {
                e.preventDefault();
                openEditCard();
            });
        }

        if (cancelBtn && editCard) {
            cancelBtn.addEventListener('click', function (e) {
                e.preventDefault();
                editCard.classList.add('d-none');
                document.body.classList.remove('is-editing');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        if (editCard && editCard.dataset.openOnLoad === '1') {
            openEditCard();
            document.body.classList.add('is-editing');
        }

        if (window.jQuery && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options'
            });
        }

        // Setup photo-item upload handlers (similar to talentprofile/show)
        const editForm = document.querySelector('.is-editing') || document.body;
        const photoItems = document.querySelectorAll('.photo-item.is-editable');

        photoItems.forEach(item => {
            const fileInput = item.querySelector('input[type="file"]');
            if (!fileInput) return;

            let filePickerOpen = false;
            let justDropped = false;

            // Click handler
            item.addEventListener('click', function(e) {
                if (e.target.closest('.remove-photo-link')) return;
                if (e.target === fileInput) return;
                if (justDropped) {
                    justDropped = false;
                    return;
                }
                if (fileInput.dataset.processing === 'true') return;
                if (filePickerOpen) return;

                const isEditing = !editCard.classList.contains('d-none');
                if (isEditing) {
                    e.preventDefault();
                    e.stopPropagation();
                    filePickerOpen = true;
                    fileInput.click();
                    setTimeout(() => { filePickerOpen = false; }, 1000);
                }
            });

            fileInput.addEventListener('focus', () => { filePickerOpen = true; });
            fileInput.addEventListener('blur', () => { setTimeout(() => { filePickerOpen = false; }, 200); });

            // File change handler - trigger AJAX upload
            fileInput.addEventListener('change', function(e) {
                e.stopImmediatePropagation();
                if (!this.files || !this.files[0]) return;
                if (this.dataset.processing === 'true') return;

                const file = this.files[0];
                const field = item.dataset.field;
                this.dataset.processing = 'true';

                // Preview image immediately
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = item.querySelector('img.preview-img');
                    if (!img) {
                        const placeholder = item.querySelector('div:not(.upload-overlay)');
                        if (placeholder) placeholder.remove();
                        img = document.createElement('img');
                        img.className = 'preview-img';
                        img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; display: block;';
                        item.insertBefore(img, item.firstChild);
                    }
                    img.src = e.target.result;

                    // Update overlay
                    const overlay = item.querySelector('.upload-overlay');
                    if (overlay) {
                        const uploadText = overlay.querySelector('.upload-text');
                        if (uploadText) uploadText.textContent = 'Replace Photo';
                        let removeLink = overlay.querySelector('.remove-photo-link');
                        if (!removeLink) {
                            removeLink = document.createElement('span');
                            removeLink.className = 'remove-photo-link';
                            removeLink.textContent = 'Remove Photo';
                            removeLink.onclick = (e) => removeMediaImage(removeLink, e);
                            overlay.appendChild(removeLink);
                        }
                    }
                };
                reader.readAsDataURL(file);

                // Upload to S3 via AJAX
                uploadImageToS3(file, field, item, fileInput);
            });
        });

        // Function to upload image to S3 (similar to talentprofile/show)
        async function uploadImageToS3(file, field, tile, input) {
            if (tile.dataset.uploading === 'true') return;
            tile.dataset.uploading = 'true';

            const uploadId = `upload_${Date.now()}_${Math.random().toString(16).slice(2)}`;
            uploadModalTracker.addItem(uploadId, file.name);

            try {
                // Get presigned URL
                const presignRes = await fetch('{{ route("talent.profile.presign-additional-photo") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                    },
                    credentials: 'same-origin',
                    body: JSON.stringify({
                        file_name: file.name,
                        file_type: file.type,
                        field: field,
                    }),
                });

                if (!presignRes.ok) {
                    throw new Error('Failed to get upload URL');
                }

                const { url, headers, key } = await presignRes.json();

                // Upload to S3
                await uploadToS3Put(url, headers, file, (progress) => {
                    uploadModalTracker.updateProgress(uploadId, progress);
                });

                uploadModalTracker.markComplete(uploadId);

                // Store S3 key in hidden input
                const form = document.querySelector('form[action*="profile"]');
                if (form) {
                    // Remove old hidden inputs for this field
                    const existingInputs = form.querySelectorAll(`input[name="uploaded_keys[]"][data-field="${field}"]`);
                    existingInputs.forEach(inp => inp.remove());

                    // Add new hidden input
                    const keyInput = document.createElement('input');
                    keyInput.type = 'hidden';
                    keyInput.name = 'uploaded_keys[]';
                    keyInput.value = key;
                    keyInput.dataset.field = field;
                    form.appendChild(keyInput);

                    const fieldInput = document.createElement('input');
                    fieldInput.type = 'hidden';
                    fieldInput.name = 'uploaded_fields[]';
                    fieldInput.value = field;
                    fieldInput.dataset.field = field;
                    form.appendChild(fieldInput);
                }

            } catch (error) {
                uploadModalTracker.markError(uploadId, error.message);
            } finally {
                input.dataset.processing = 'false';
                tile.dataset.uploading = 'false';
                input.value = '';
            }
        }

        // Function to remove media image
        function removeMediaImage(link, e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }

            const tile = link.closest('.photo-item');
            const field = tile?.dataset.field;
            const mediaId = tile?.dataset.mediaId;

            // Remove preview
            const img = tile?.querySelector('img.preview-img');
            if (img) {
                img.remove();
            }

            // Add placeholder
            const placeholder = document.createElement('div');
            placeholder.style.cssText = 'width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;';
            placeholder.textContent = 'No Image';
            tile.insertBefore(placeholder, tile.firstChild);

            // Update overlay
            const overlay = tile?.querySelector('.upload-overlay');
            if (overlay) {
                const uploadText = overlay.querySelector('.upload-text');
                if (uploadText) uploadText.textContent = 'Upload Photo';
                const removeLink = overlay.querySelector('.remove-photo-link');
                if (removeLink) removeLink.remove();
            }

            // Mark for deletion
            const form = document.querySelector('form[action*="profile"]');
            if (form && field) {
                // Remove uploaded_keys for this field
                const keyInputs = form.querySelectorAll(`input[name="uploaded_keys[]"][data-field="${field}"]`);
                keyInputs.forEach(inp => inp.remove());
                const fieldInputs = form.querySelectorAll(`input[name="uploaded_fields[]"][data-field="${field}"]`);
                fieldInputs.forEach(inp => inp.remove());

                // Add deletion marker if it's a hardcoded field
                if (field && !mediaId) {
                    let deleteInput = form.querySelector(`input[name="deleted_fields[]"][value="${field}"]`);
                    if (!deleteInput) {
                        deleteInput = document.createElement('input');
                        deleteInput.type = 'hidden';
                        deleteInput.name = 'deleted_fields[]';
                        deleteInput.value = field;
                        form.appendChild(deleteInput);
                    }
                }
            }

            if (mediaId && form) {
                let deletedInput = form.querySelector(`input[name="deleted_media_ids[]"][value="${mediaId}"]`);
                if (!deletedInput) {
                    deletedInput = document.createElement('input');
                    deletedInput.type = 'hidden';
                    deletedInput.name = 'deleted_media_ids[]';
                    deletedInput.value = mediaId;
                    form.appendChild(deletedInput);
                }
            }
        }

        // Add more photos button functionality
        function addMorePhotoSlots(e) {
            if (e) e.preventDefault();
            const grid = document.getElementById('additionalPhotosGrid');
            if (!grid) return;

            const newItem = document.createElement('div');
            newItem.className = 'photo-item is-editable';
            newItem.style.cssText = 'position: relative;';
            newItem.innerHTML = `
                <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                <div class="upload-overlay">
                    <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                    <span class="upload-text">Upload Photo</span>
                </div>
                <input type="file" name="additional_photos[]" class="media-file-input" accept="image/*" style="display:none">
            `;

            grid.appendChild(newItem);

            // Setup handlers for new item
            const fileInput = newItem.querySelector('input[type="file"]');
            if (fileInput) {
                fileInput.addEventListener('change', function() {
                    if (this.files && this.files[0]) {
                        handleFilesSelected([this.files[0]]);
                    }
                });
            }

            newItem.addEventListener('click', function(e) {
                if (e.target.closest('.remove-photo-link')) return;
                if (fileInput) fileInput.click();
            });
        }

        // Add "Add More Photos" button to additional photos section
        const additionalPhotosSection = document.querySelector('#additionalPhotosGrid')?.closest('.portfolio-section');
        if (additionalPhotosSection) {
            const addMoreBtn = document.createElement('button');
            addMoreBtn.type = 'button';
            addMoreBtn.className = 'add-more-btn';
            addMoreBtn.style.cssText = 'margin-top: 12px; padding: 8px 16px; background: #10b981; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; display: none;';
            addMoreBtn.innerHTML = '<i class="fas fa-plus"></i> Add More Photos';
            addMoreBtn.onclick = addMorePhotoSlots;
            additionalPhotosSection.appendChild(addMoreBtn);

            // Show button in edit mode
            function toggleAddMoreButton() {
                const isEditing = !editCard.classList.contains('d-none');
                addMoreBtn.style.display = isEditing ? 'block' : 'none';
            }
            if (editBtn) editBtn.addEventListener('click', () => setTimeout(toggleAddMoreButton, 100));
            if (cancelBtn) cancelBtn.addEventListener('click', () => setTimeout(toggleAddMoreButton, 100));
            toggleAddMoreButton();
        }

        // Show/hide delete buttons for additional photos in edit mode
        function toggleEditModeForPhotos() {
            const isEditing = !editCard.classList.contains('d-none');
            const deleteButtons = document.querySelectorAll('.delete-media-btn');
            deleteButtons.forEach(btn => {
                btn.style.display = isEditing ? 'flex' : 'none';
            });
        }

        // Toggle delete buttons when edit card opens/closes
        if (editBtn) {
            const originalEditHandler = editBtn.onclick;
            editBtn.addEventListener('click', () => {
                setTimeout(toggleEditModeForPhotos, 100);
            });
        }
        if (cancelBtn) {
            cancelBtn.addEventListener('click', () => {
                setTimeout(toggleEditModeForPhotos, 100);
            });
        }
        toggleEditModeForPhotos();

        // Handle delete media buttons
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-media-btn')) {
                const btn = e.target.closest('.delete-media-btn');
                const mediaId = btn.dataset.mediaId;
                const photoItem = btn.closest('.photo-item');

                if (mediaId && photoItem) {
                    // Add to deleted_media_ids hidden input
                    const form = document.querySelector('form[action*="profile"]');
                    if (form) {
                        let deletedInput = form.querySelector(`input[name="deleted_media_ids[]"][value="${mediaId}"]`);
                        if (!deletedInput) {
                            deletedInput = document.createElement('input');
                            deletedInput.type = 'hidden';
                            deletedInput.name = 'deleted_media_ids[]';
                            deletedInput.value = mediaId;
                            form.appendChild(deletedInput);
                        }
                    }

                    // Hide the photo item
                    photoItem.style.opacity = '0.5';
                    photoItem.style.pointerEvents = 'none';
                }
            }
        });
    });
</script>
@endsection
