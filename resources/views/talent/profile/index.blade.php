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
        $selectedLanguages = old('languages', $profile->languages->pluck('id')->toArray());
        $selectedLabels = old('labels', $profile->labels->pluck('id')->toArray());
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

            <div class="edit-section-title">Languages & Labels</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="languages">Languages</label>
                    <select id="languages" name="languages[]" class="form-control-lite select2" multiple>
                        @foreach($languages as $language)
                            <option value="{{ $language->id }}" {{ in_array($language->id, $selectedLanguages ?? []) ? 'selected' : '' }}>
                                {{ $language->title }}
                            </option>
                        @endforeach
                    </select>
                    <div class="file-note">Select all languages you are comfortable working in.</div>
                </div>
                <div class="form-field" style="grid-column: 1 / -1;">
                    <label>Labels (at least one)</label>
                    <div class="checkbox-grid">
                        @foreach($availableLabels as $label)
                            <label class="checkbox-pill">
                                <input type="checkbox" name="labels[]" value="{{ $label->id }}" {{ in_array($label->id, $selectedLabels ?? []) ? 'checked' : '' }}>
                                <span>{{ $label->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="edit-section-title">Media uploads</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="headshot_center">Headshot (center)</label>
                    <input id="headshot_center" type="file" name="headshot_center" class="form-control-file">
                    @if($profile->headshot_center_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->headshot_center_path) }}" target="_blank">View</a></div>
                    @endif
                </div>
                <div class="form-field">
                    <label for="headshot_left">Headshot (left)</label>
                    <input id="headshot_left" type="file" name="headshot_left" class="form-control-file">
                    @if($profile->headshot_left_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->headshot_left_path) }}" target="_blank">View</a></div>
                    @endif
                </div>
                <div class="form-field">
                    <label for="headshot_right">Headshot (right)</label>
                    <input id="headshot_right" type="file" name="headshot_right" class="form-control-file">
                    @if($profile->headshot_right_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->headshot_right_path) }}" target="_blank">View</a></div>
                    @endif
                </div>
                <div class="form-field">
                    <label for="full_body_front">Full body (front)</label>
                    <input id="full_body_front" type="file" name="full_body_front" class="form-control-file">
                    @if($profile->full_body_front_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->full_body_front_path) }}" target="_blank">View</a></div>
                    @endif
                </div>
                <div class="form-field">
                    <label for="full_body_right">Full body (right)</label>
                    <input id="full_body_right" type="file" name="full_body_right" class="form-control-file">
                    @if($profile->full_body_right_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->full_body_right_path) }}" target="_blank">View</a></div>
                    @endif
                </div>
                <div class="form-field">
                    <label for="full_body_back">Full body (back)</label>
                    <input id="full_body_back" type="file" name="full_body_back" class="form-control-file">
                    @if($profile->full_body_back_path)
                        <div class="file-note">Current: <a href="{{ $resolveMediaUrl($profile->full_body_back_path) }}" target="_blank">View</a></div>
                    @endif
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
                <div class="photo-item">
                    @if($profile->headshot_center_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_center_path) }}" alt="Center Headshot">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Left -->
                <div class="photo-item">
                    @if($profile->headshot_left_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_left_path) }}" alt="Left Headshot">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Right -->
                <div class="photo-item">
                    @if($profile->headshot_right_path)
                        <img src="{{ $resolveMediaUrl($profile->headshot_right_path) }}" alt="Right Headshot">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
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
                 <div class="photo-item">
                    @if($profile->full_body_front_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_front_path) }}" alt="Full Body Front">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Right -->
                 <div class="photo-item">
                    @if($profile->full_body_right_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_right_path) }}" alt="Full Body Right">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Back -->
                 <div class="photo-item">
                    @if($profile->full_body_back_path)
                        <img src="{{ $resolveMediaUrl($profile->full_body_back_path) }}" alt="Full Body Back">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

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

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const editBtn = document.getElementById('editProfileBtn');
        const editCard = document.getElementById('editCard');
        const cancelBtn = document.getElementById('cancelEditBtn');

        const openEditCard = () => {
            if (!editCard) return;
            editCard.classList.remove('d-none');
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
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        if (editCard && editCard.dataset.openOnLoad === '1') {
            openEditCard();
        }

        if (window.jQuery && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options'
            });
        }
    });
</script>
@endsection
