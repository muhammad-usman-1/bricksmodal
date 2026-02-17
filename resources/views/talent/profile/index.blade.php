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
        flex: 1;
    }

    .profile-summary-image {
        width: 80px;
        height: 80px;
        border-radius: 12px;
        overflow: hidden;
        flex-shrink: 0;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .profile-summary-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
    }

    .profile-summary-image span {
        font-size: 32px;
        font-weight: 600;
        color: #9ca3af;
    }

    .profile-name-title {
        font-size: 24px;
        font-weight: 600;
        margin: 0;
        color: var(--text-dark);
        line-height: 1.2;
    }

    .profile-names h1 {
        font-size: 20px;
        font-weight: 600;
        margin: 0;
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

    /* Video upload controls - only visible in edit mode */
    .video-edit-only {
        display: none !important;
    }
    .is-editing .video-edit-only {
        display: flex !important;
    }
    /* Allow block display when explicitly set */
    .is-editing #video-upload-area.video-edit-only {
        display: block !important;
    }
    .video-upload-label {
        cursor: pointer;
        pointer-events: auto;
    }
    /* Only disable when input is disabled AND not in edit mode */
    #upload_video:disabled ~ .upload-inner,
    #upload_video:disabled + .upload-inner {
        opacity: 0.5;
    }
    .is-editing #upload_video:disabled ~ .upload-inner,
    .is-editing #upload_video:disabled + .upload-inner {
        opacity: 1;
    }
    /* Disable label when input is disabled and not in edit mode */
    #upload_video:disabled ~ * .video-upload-label,
    body:not(.is-editing) #upload_video:disabled ~ * .video-upload-label {
        cursor: default;
        pointer-events: none;
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
    /* Photo removal disabled for talents (no remove UI) */
    .photo-item .remove-photo-link {
        display: none !important;
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
    $resolveMediaUrl = function ($path) use ($profile) {
        if (! $path) {
            return null;
        }

        if (is_array($path)) {
            $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
        }

        if (! $path) {
            return null;
        }

        // Cache key for this URL
        $cacheKey = 'talent_image_url_' . md5($path . '_' . ($profile->id ?? ''));

        // Try to get from cache first (cache for 6 hours)
        $cachedUrl = \Illuminate\Support\Facades\Cache::get($cacheKey);
        if ($cachedUrl !== null) {
            return $cachedUrl;
        }

        $isAbsolute = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//']);
        $awsUrl = rtrim((string) env('AWS_URL'), '/');
        $s3Disk = config('filesystems.cloud', 's3');
        $storage = \Illuminate\Support\Facades\Storage::disk($s3Disk);
        $defaultDisk = config('filesystems.default', 'public');
        $defaultStorage = \Illuminate\Support\Facades\Storage::disk($defaultDisk);

        $resolvedUrl = null;

        // If path is already a full URL (likely from S3/CloudFront)
        if ($isAbsolute) {
            // If it matches AWS_URL (CloudFront CDN), use it directly for better caching
            if ($awsUrl && \Illuminate\Support\Str::startsWith($path, $awsUrl)) {
                $resolvedUrl = $path; // Use CloudFront URL directly - it's already optimized
            } else {
                // Check if it's an S3 URL that we can convert to CloudFront
                if ($awsUrl && (strpos($path, '.s3.') !== false || strpos($path, 's3.amazonaws.com') !== false)) {
                    // Extract the key from S3 URL and construct CloudFront URL
                    $parsed = parse_url($path);
                    if (isset($parsed['path'])) {
                        $key = ltrim($parsed['path'], '/');
                        $resolvedUrl = rtrim($awsUrl, '/') . '/' . $key;
                    } else {
                        $resolvedUrl = $path;
                    }
                } else {
                    $resolvedUrl = $path; // Use as-is if it's already a valid URL
                }
            }
        } else {
            // Relative path - try to resolve it
            $clean = ltrim($path, '/');

            // If AWS_URL (CloudFront) is configured, use it for permanent URLs
            if ($awsUrl) {
                try {
                    // Try to get permanent URL via CloudFront
                    $resolvedUrl = rtrim($awsUrl, '/') . '/' . $clean;
                } catch (\Exception $e) {
                    // Fallback to storage URL
                    try {
                        $resolvedUrl = $storage->url($clean);
                    } catch (\Exception $e2) {
                        // Last resort: try default storage
                        try {
                            $resolvedUrl = $defaultStorage->url($clean);
                        } catch (\Exception $e3) {
                            $resolvedUrl = null;
                        }
                    }
                }
            } else {
                // No CloudFront - try to get permanent URL first
                try {
                    $resolvedUrl = $storage->url($clean);
                } catch (\Exception $e) {
                    // If permanent URL fails, use temporary URL with longer expiration (7 days for better caching)
                    try {
                        $resolvedUrl = $storage->temporaryUrl($clean, now()->addDays(7));
                    } catch (\Exception $e2) {
                        // Last resort: try default storage
                        try {
                            $resolvedUrl = $defaultStorage->url($clean);
                        } catch (\Exception $e3) {
                            $resolvedUrl = null;
                        }
                    }
                }
            }
        }

        // Cache the resolved URL for 6 hours
        if ($resolvedUrl) {
            \Illuminate\Support\Facades\Cache::put($cacheKey, $resolvedUrl, now()->addHours(6));
        }

        return $resolvedUrl;
    };

    // Collect all available profile images (similar to admin talentProfiles/show.blade.php)
    $standardPhotoFields = [
        'headshot_center_path' => 'Headshot (Center)',
        'headshot_left_path'   => 'Headshot (Left)',
        'headshot_right_path'  => 'Headshot (Right)',
        'full_body_front_path' => 'Full Body (Front)',
        'full_body_right_path' => 'Full Body (Right)',
        'full_body_back_path'  => 'Full Body (Back)',
    ];

    $allPhotos = [];
    foreach ($standardPhotoFields as $field => $label) {
        $path = $profile->{$field};
        if ($path) {
            $allPhotos[] = $path;
        }
    }

    // Collect from media relationship
    $mediaItems = $profile->media()->get();
    foreach ($mediaItems as $media) {
        if ($media->file_path) {
            $allPhotos[] = $media->file_path;
        }
    }

    // Get random profile image (or first available)
    $randomImagePath = null;
    if (!empty($allPhotos)) {
        $randomImagePath = $allPhotos[array_rand($allPhotos)];
    }
    $primaryAvatar = $randomImagePath ? $resolveMediaUrl($randomImagePath) : null;

    // Get talent name
    $talentName = $profile->display_name ?? $profile->legal_name ?? 'Not Set';

    // Get phone number
    $phoneNumber = $profile->whatsapp_number ?? $profile->mobile_number ?? ($profile->user->phone_number ?? null);
    $phoneDisplay = $phoneNumber ? '+965 ' . $phoneNumber : 'Not Set';

    // Get status
    $status = $profile->verification_status ?? 'pending';
    $statusLabels = [
        'approved' => \App\Helpers\Bilingual::get('admin_talent_profile.approved') ?? 'Approved',
        'pending' => \App\Helpers\Bilingual::get('admin_talent_profile.pending') ?? 'Pending',
        'rejected' => \App\Helpers\Bilingual::get('admin_talent_profile.rejected') ?? 'Rejected',
        'suspended' => \App\Helpers\Bilingual::get('admin_talent_profile.suspended') ?? 'Suspended',
        'under_review' => 'Under Review',
    ];
    $statusLabel = $statusLabels[$status] ?? ucfirst($status);
    $statusClass = match($status) {
        'approved' => 'status-approved',
        'pending' => 'status-pending',
        'rejected' => 'status-rejected',
        'suspended' => 'status-suspended',
        'under_review' => 'status-pending',
        default => 'status-pending',
    };
@endphp

<div class="profile-dashboard">

    <!-- Top Card: Header -->
    <div class="dash-card profile-header">
        <div class="profile-info-wrap">
            <!-- Profile Image -->
            <div class="profile-summary-image">
                @if($primaryAvatar)
                    <img src="{{ e($primaryAvatar) }}" alt="{{ e($talentName) }}">
                @else
                    <span>{{ strtoupper(substr($talentName, 0, 1)) }}</span>
                @endif
            </div>

            <!-- Profile Info -->
            <div style="flex: 1; display: flex; flex-direction: column; gap: 8px;">
                <!-- Name and Profile Number -->
                <div>
                    <h1 class="profile-name-title">#{{ $profile->id }} - {{ $talentName }}</h1>
                </div>

                <!-- Status -->
                <div>
                    <span class="status-badge {{ $statusClass }}" style="display: inline-block; padding: 4px 12px; border-radius: 6px; font-size: 12px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em;">
                        {{ $statusLabel }}
                    </span>
                </div>

                <!-- Phone Number -->
                <div style="color: var(--text-gray); font-size: 14px;">
                    <span>{{ $phoneDisplay }}</span>
                </div>
            </div>
        </div>

        <!-- Edit Profile Link -->
        <button type="button" class="btn-edit-profile" id="editProfileBtn">
            <i class="fas fa-pen"></i> {{ \App\Helpers\Bilingual::get('talent.profile_edit') }}
        </button>
    </div>

    <style>
        .status-badge.status-approved {
            background: #e6f7ed;
            color: #15803d;
        }
        .status-badge.status-pending {
            background: #fffbeb;
            color: #f59e0b;
        }
        .status-badge.status-rejected {
            background: #fee2e2;
            color: #dc2626;
        }
        .status-badge.status-suspended {
            background: #f3f4f6;
            color: #6b7280;
        }
    </style>

    @php
        $openEditCard = $errors->any();
    @endphp

    <div class="dash-card edit-card {{ $openEditCard ? '' : 'd-none' }}" id="editCard" data-open-on-load="{{ $openEditCard ? '1' : '0' }}">
        <form method="POST" action="{{ route('talent.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="edit-card-header">
                <div>
                    <h3>{{ \App\Helpers\Bilingual::get('talent.profile_edit_title') }}</h3>
                    <p>{{ \App\Helpers\Bilingual::get('talent.profile_edit_description') }}</p>
                </div>
                <div class="edit-actions">
                    <button type="button" class="btn-cancel-edit" id="cancelEditBtn">{{ \App\Helpers\Bilingual::get('talent.profile_cancel') }}</button>
                    <button type="submit" class="btn-save-edit">{{ \App\Helpers\Bilingual::get('talent.profile_save_changes') }}</button>
                </div>
            </div>

            <div class="edit-section-title">{{ \App\Helpers\Bilingual::get('talent.profile_basic_details') }}</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="legal_name">{{ \App\Helpers\Bilingual::get('talent.profile_legal_name') }}</label>
                    <input id="legal_name" type="text" name="legal_name" class="form-control-lite" value="{{ old('legal_name', $profile->legal_name) }}" required>
                </div>
                <div class="form-field">
                    <label for="display_name">{{ \App\Helpers\Bilingual::get('talent.profile_display_name') }}</label>
                    <input id="display_name" type="text" name="display_name" class="form-control-lite" value="{{ old('display_name', $profile->display_name) }}">
                </div>
                <div class="form-field">
                    <label for="email">{{ \App\Helpers\Bilingual::get('talent.profile_email') }}</label>
                    <input id="email" type="email" name="email" class="form-control-lite" value="{{ old('email', $profile->user->email ?? $profile->email) }}" required>
                </div>
                <div class="form-field">
                    <label for="date_of_birth">{{ \App\Helpers\Bilingual::get('talent.profile_date_of_birth') }}</label>
                    <input id="date_of_birth" type="date" name="date_of_birth" class="form-control-lite" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}">
                </div>
                <div class="form-field">
                    <label for="gender">{{ \App\Helpers\Bilingual::get('talent.profile_gender') }}</label>
                    @php $genderValue = old('gender', $profile->gender); @endphp
                    <select id="gender" name="gender" class="form-control-lite">
                        <option value="">{{ \App\Helpers\Bilingual::get('talent.profile_select_gender') }}</option>
                        <option value="male" {{ $genderValue === 'male' ? 'selected' : '' }}>{{ \App\Helpers\Bilingual::get('talent.profile_male') }}</option>
                        <option value="female" {{ $genderValue === 'female' ? 'selected' : '' }}>{{ \App\Helpers\Bilingual::get('talent.profile_female') }}</option>
                        <option value="other" {{ $genderValue === 'other' ? 'selected' : '' }}>{{ \App\Helpers\Bilingual::get('talent.profile_other') }}</option>
                        <option value="prefer_not_to_say" {{ $genderValue === 'prefer_not_to_say' ? 'selected' : '' }}>{{ \App\Helpers\Bilingual::get('talent.profile_prefer_not_to_say') }}</option>
                    </select>
                </div>
                <div class="form-field">
                    <label for="whatsapp_number">{{ \App\Helpers\Bilingual::get('talent.profile_whatsapp_number') }}</label>
                    <input id="whatsapp_number" type="text" name="whatsapp_number" class="form-control-lite" placeholder="+965 5xxxxxxx" value="{{ old('whatsapp_number', $profile->whatsapp_number) }}">
                </div>
            </div>

            <div class="edit-section-title">{{ \App\Helpers\Bilingual::get('talent.profile_bio') }}</div>
            <div class="edit-grid">
                <div class="form-field" style="grid-column: 1 / -1;">
                    <label for="bio">{{ \App\Helpers\Bilingual::get('talent.profile_bio') }}</label>
                    <textarea id="bio" name="bio" class="textarea-control" maxlength="1000" placeholder="{{ \App\Helpers\Bilingual::get('talent.profile_bio_placeholder') }}">{{ old('bio', $profile->bio) }}</textarea>
                </div>
            </div>

            <div class="edit-section-title">{{ \App\Helpers\Bilingual::get('talent.profile_measurements_appearance') }}</div>
            <div class="edit-grid">
                <div class="form-field">
                    <label for="height">{{ \App\Helpers\Bilingual::get('talent.profile_height') }}</label>
                    <input id="height" type="number" step="0.1" min="0" name="height" class="form-control-lite" value="{{ old('height', $profile->height) }}">
                </div>
                <div class="form-field">
                    <label for="weight">{{ \App\Helpers\Bilingual::get('talent.profile_weight') }}</label>
                    <input id="weight" type="number" step="0.1" min="0" name="weight" class="form-control-lite" value="{{ old('weight', $profile->weight) }}">
                </div>
                <div class="form-field">
                    <label for="chest">{{ \App\Helpers\Bilingual::get('talent.profile_chest') }}</label>
                    <input id="chest" type="number" step="0.1" min="0" name="chest" class="form-control-lite" value="{{ old('chest', $profile->chest) }}">
                </div>
                <div class="form-field">
                    <label for="waist">{{ \App\Helpers\Bilingual::get('talent.profile_waist') }}</label>
                    <input id="waist" type="number" step="0.1" min="0" name="waist" class="form-control-lite" value="{{ old('waist', $profile->waist) }}">
                </div>
                <div class="form-field">
                    <label for="hips">{{ \App\Helpers\Bilingual::get('talent.profile_hips') }}</label>
                    <input id="hips" type="number" step="0.1" min="0" name="hips" class="form-control-lite" value="{{ old('hips', $profile->hips) }}">
                </div>
                <div class="form-field">
                    <label for="shoe_size">{{ \App\Helpers\Bilingual::get('talent.profile_shoe_size') }}</label>
                    <input id="shoe_size" type="number" step="0.5" min="0" name="shoe_size" class="form-control-lite" value="{{ old('shoe_size', $profile->shoe_size) }}">
                </div>
                <div class="form-field">
                    <label for="skin_tone">{{ \App\Helpers\Bilingual::get('talent.profile_skin_tone') }}</label>
                    @php $skinTone = old('skin_tone', $profile->skin_tone); @endphp
                    <select id="skin_tone" name="skin_tone" class="form-control-lite">
                        <option value="">{{ \App\Helpers\Bilingual::get('talent.profile_select_skin_tone') }}</option>
                        @foreach($skinToneOptions as $value => $label)
                            <option value="{{ $value }}" {{ $skinTone === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-field">
                    <label for="hair_color">{{ \App\Helpers\Bilingual::get('talent.profile_hair_color') }}</label>
                    <input id="hair_color" type="text" name="hair_color" class="form-control-lite" value="{{ old('hair_color', $profile->hair_color) }}">
                </div>
                <div class="form-field">
                    <label for="eye_color">{{ \App\Helpers\Bilingual::get('talent.profile_eye_color') }}</label>
                    <input id="eye_color" type="text" name="eye_color" class="form-control-lite" value="{{ old('eye_color', $profile->eye_color) }}">
                </div>
            </div>


            <div class="file-note" style="margin-top: 8px;">
                {{ \App\Helpers\Bilingual::get('talent.profile_max_upload_note') }}
            </div>

            <!-- Hidden input for remove_video flag -->
            <input type="hidden" name="remove_video" value="0" id="remove_video_input">
        </form>
    </div>

    <!-- Media Portfolio -->
    <div class="dash-card media-portfolio-card" id="mediaPortfolioCard">
        <div class="section-title-row">
            <div class="icon-box"><i class="fas fa-images"></i></div>
            <span>{{ \App\Helpers\Bilingual::get('talent.profile_media_portfolio') }}</span>
        </div>

        <!-- Additional Profile Images -->
        @php
            $additionalPhotos = $profile->media()->where('type', 'profile')->get();
        @endphp
        @if($additionalPhotos->count() > 0)
        <div class="portfolio-section">
            <div class="portfolio-header">
                <h3>Profile Images</h3>
            </div>
            <div class="photo-grid" id="additionalPhotosGrid">
                @foreach($additionalPhotos as $media)
                    <div class="photo-item is-editable" data-media-id="{{ $media->id }}" style="position: relative;">
                        @if($media->file_path)
                            <img src="{{ $resolveMediaUrl($media->file_path) }}" alt="Additional Photo" class="preview-img" loading="lazy" decoding="async" fetchpriority="low">
                        @else
                            <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                        @endif
                        <div class="upload-progress-container" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: rgba(0,0,0,0.2); z-index: 10; display: none;">
                            <div class="upload-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.5s ease; box-shadow: 0 0 2px rgba(0,0,0,0.5);"></div>
                        </div>
                    <div class="upload-overlay">
                        <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                        <span class="upload-text">{{ $media->file_path ? \App\Helpers\Bilingual::get('talent.profile_replace_photo') : \App\Helpers\Bilingual::get('talent.profile_upload_photo') }}</span>
                    </div>
                        <input type="file" name="additional_photos[]" class="media-file-input" accept="image/*" style="display:none">
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Profile Video -->
        <div class="portfolio-section" style="margin-top: 32px;">
            <div class="portfolio-header">
                <h3>{{ \App\Helpers\Bilingual::get('talent.profile_video') }}</h3>
            </div>
            <div id="video-upload-section" style="margin-top: 16px;">
                @if($muxPlaybackId)
                    <!-- Video Preview - Always shown when video exists -->
                    <div id="video-preview-container" style="margin-bottom: 16px;">
                        <div style="position: relative; width: 100%; max-width: 600px; background: #000; border-radius: 8px; overflow: hidden;">
                            <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden;">
                                <iframe
                                    src="https://stream.mux.com/{{ $muxPlaybackId }}.m3u8"
                                    style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;"
                                    allow="autoplay; encrypted-media"
                                    allowfullscreen
                                ></iframe>
                            </div>
                        </div>
                        <!-- Replace/Remove buttons - Only shown in edit mode -->
                        <div id="video-action-buttons" class="video-edit-only" style="margin-top: 12px; display: none; gap: 12px; align-items: center; flex-wrap: wrap;">
                            <button type="button" id="replace-video-btn" class="btn-replace-video" style="background: #f3f4f6; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 16px; font-size: 14px; font-weight: 600; color: #374151; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-upload" style="margin-right: 6px;"></i> {{ \App\Helpers\Bilingual::get('talent.profile_replace_video') }}
                            </button>
                            <button type="button" id="remove-video-btn" class="btn-remove-video" style="background: #fee2e2; border: 1px solid #fecaca; border-radius: 6px; padding: 8px 16px; font-size: 14px; font-weight: 600; color: #991b1b; cursor: pointer; transition: all 0.2s;">
                                <i class="fas fa-trash" style="margin-right: 6px;"></i> {{ \App\Helpers\Bilingual::get('talent.profile_remove_video') }}
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Upload Area - Shown by default when no video, or in edit mode when video exists -->
                <div id="video-upload-area" style="display: {{ $muxPlaybackId ? 'none' : 'block' }};">
                    <label class="upload-card video-upload-label" for="upload_video" style="width:100%; margin:0; cursor: default; pointer-events: none; opacity: 0.6;">
                        <input id="upload_video" name="video" type="file" accept="video/*" style="display:none;" disabled>
                        <div class="upload-inner" style="padding: 40px 20px; text-align: center; border: 2px dashed #d1d5db; border-radius: 8px; background: #f9fafb; transition: all 0.2s;">
                            <div class="upload-icon" style="margin-bottom: 12px;">
                                <i class="fas fa-video" style="font-size: 32px; color: #9ca3af;"></i>
                            </div>
                            <div class="upload-label" data-file-label="video" style="font-size: 14px; font-weight: 600; color: #6b7280; margin-bottom: 4px;">
                                {{ \App\Helpers\Bilingual::get('talent.profile_click_upload_drag_drop') }}
                            </div>
                            <div style="font-size: 12px; color: #9ca3af; margin-top: 4px;">
                                {{ \App\Helpers\Bilingual::get('talent.profile_video_formats') }}
                            </div>
                            <div class="progress-bar-container" id="video-progress-container" style="display:none; width: 100%; height: 6px; background: #e5e7eb; border-radius: 3px; margin-top: 16px; overflow: hidden;">
                                <div class="progress-bar-fill" id="video-progress-fill" style="width: 0%; height: 100%; background: #10b981; transition: width 0.3s ease;"></div>
                            </div>
                            <div id="video-upload-status" style="margin-top: 12px; font-size: 13px; color: #6b7280; display: none;"></div>
                        </div>
                    </label>
                    @error('video')
                        <div style="margin-top: 8px; color: #dc2626; font-size: 13px;">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Row -->
    {{--  <div class="stats-grid">
        <!-- 1. Shoots Completed -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-camera"></i> {{ \App\Helpers\Bilingual::get('talent.profile_shoots_completed') }}
            </div>
            <div class="stat-value">{{ $shootsCompleted ?? 0 }}</div>
            <div class="stat-trend trend-up">{{ \App\Helpers\Bilingual::get('talent.profile_lifetime') }}</div>
        </div>

        <!-- 2. Profile Views -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-eye"></i> {{ \App\Helpers\Bilingual::get('talent.profile_views') }}
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
    </div>  --}}

    <!-- Measurements Section -->
    <div class="dash-card">
        <div class="section-title-row">
            <div class="icon-box"><i class="fas fa-ruler-combined"></i></div>
            <span>{{ \App\Helpers\Bilingual::get('talent.profile_measurements_appearance') }}</span>
        </div>
        <div class="measurements-grid">
            <div class="measurement-box">
                <div class="measure-label">{{ \App\Helpers\Bilingual::get('talent.profile_height') }}</div>
                <div class="measure-value">{{ $profile->height ?? '-' }} <span class="measure-unit">cm</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">{{ \App\Helpers\Bilingual::get('talent.profile_weight') }}</div>
                <div class="measure-value">{{ $profile->weight ?? '-' }} <span class="measure-unit">kg</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">{{ \App\Helpers\Bilingual::get('talent.profile_waist') }}</div>
                <div class="measure-value">{{ $profile->waist ?? '-' }} <span class="measure-unit">cm</span></div>
            </div>
            <div class="measurement-box">
                <div class="measure-label">{{ \App\Helpers\Bilingual::get('talent.profile_shoe_size') }}</div>
                <div class="measure-value">{{ $profile->shoe_size ?? '-' }} <span class="measure-unit">EU</span></div>
            </div>
        </div>
    </div>

    {{-- ID Document Section (Hidden - Talents cannot see ID documents) --}}
    @php
        // ID document section is hidden from talents for privacy/security reasons
        $idDocUrl = null;
        $hasIdDoc = false;
    @endphp

    {{-- @if($hasIdDoc && $idDocUrl)
    <div class="dash-card" style="width: 100%;">
        <div class="section-title-row">
            <div class="icon-box"><i class="fas fa-id-card"></i></div>
            <span>{{ \App\Helpers\Bilingual::get('talent.profile_id_document') }}</span>
        </div>
        <div style="margin-top: 16px;">
            <div style="border: 1px solid var(--border-color); border-radius: 8px; overflow: hidden; background: #f9fafb; padding: 16px; display: flex; align-items: center; justify-content: center; width: 100%;">
                @if(str_ends_with(strtolower($idDocUrl), '.pdf'))
                    <div style="text-align: center;">
                        <i class="fas fa-file-pdf" style="font-size: 48px; color: #dc2626; margin-bottom: 12px;"></i>
                        <div style="font-size: 14px; color: var(--text-gray); margin-bottom: 12px;">ID Document (PDF)</div>
                        <a href="{{ $idDocUrl }}" target="_blank" style="display: inline-block; padding: 8px 16px; background: #111827; color: #fff; border-radius: 6px; text-decoration: none; font-size: 14px; font-weight: 600;">
                            <i class="fas fa-external-link-alt" style="margin-right: 6px;"></i> View Document
                        </a>
                    </div>
                @else
                    <img src="{{ $idDocUrl }}" alt="ID Document" style="width: 100%; height: auto; object-fit: contain; border-radius: 4px; display: block;" loading="lazy" decoding="async">
                @endif
            </div>
            <p style="margin-top: 12px; font-size: 13px; color: var(--text-gray); text-align: center;">
                <i class="fas fa-lock" style="margin-right: 6px;"></i> {{ \App\Helpers\Bilingual::get('talent.profile_id_readonly_note') }}
            </p>
        </div>
    </div>
    @endif --}}

    <!-- Note on Terms/Policies in footer -->
    <div style="margin-top: 24px;">
        <p style="font-size: 18px; color: #9ca3af; margin:0; display: flex; align-items: center; gap: 6px;">
            <span style="background: #fef3c7; color: #d97706; width: 4px; height: 16px; border-radius: 2px;"></span>
            {{ \App\Helpers\Bilingual::get('talent.profile_visible_note') }}
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
        const mediaPortfolioCard = document.getElementById('mediaPortfolioCard');

        // Mobile-only: move Media Portfolio above Basic Details when editing
        const isMobile = () => window.matchMedia && window.matchMedia('(max-width: 768px)').matches;
        const mediaPortfolioOriginalParent = mediaPortfolioCard ? mediaPortfolioCard.parentElement : null;
        const mediaPortfolioOriginalNext = mediaPortfolioCard ? mediaPortfolioCard.nextElementSibling : null;

        const moveMediaPortfolioIntoEditCard = () => {
            if (!mediaPortfolioCard || !editCard) return;
            if (!isMobile()) return;
            const formEl = editCard.querySelector('form');
            if (!formEl) return;
            const firstSectionTitle = formEl.querySelector('.edit-section-title');
            if (!firstSectionTitle) return;

            // Only move if not already inside the edit form
            if (mediaPortfolioCard.parentElement !== formEl) {
                formEl.insertBefore(mediaPortfolioCard, firstSectionTitle);
            }
        };

        const restoreMediaPortfolioPosition = () => {
            if (!mediaPortfolioCard || !mediaPortfolioOriginalParent) return;
            // Only restore if it's been moved into edit form
            if (mediaPortfolioCard.parentElement !== mediaPortfolioOriginalParent) {
                if (mediaPortfolioOriginalNext) {
                    mediaPortfolioOriginalParent.insertBefore(mediaPortfolioCard, mediaPortfolioOriginalNext);
                } else {
                    mediaPortfolioOriginalParent.appendChild(mediaPortfolioCard);
                }
            }
        };

        const openEditCard = () => {
            if (!editCard) return;
            editCard.classList.remove('d-none');
            document.body.classList.add('is-editing');
            moveMediaPortfolioIntoEditCard();
            editCard.scrollIntoView({ behavior: 'smooth', block: 'start' });
            toggleVideoUploadControls(true);
        };

        const closeEditCard = () => {
            if (!editCard) return;
            editCard.classList.add('d-none');
            document.body.classList.remove('is-editing');
            toggleVideoUploadControls(false);
            restoreMediaPortfolioPosition();
        };

        const toggleVideoUploadControls = (isEditing) => {
            const videoUploadArea = document.getElementById('video-upload-area');
            const videoActionButtons = document.getElementById('video-action-buttons');
            const videoInput = document.getElementById('upload_video');
            const videoUploadLabel = document.querySelector('.video-upload-label');
            const hasVideo = document.getElementById('video-preview-container');

            if (isEditing) {
                // Enable video upload in edit mode
                if (videoInput) {
                    videoInput.disabled = false;
                }
                // Show upload area in edit mode (even if video exists, for replacement)
                if (videoUploadArea) {
                    videoUploadArea.style.display = 'block';
                }
                if (videoActionButtons) {
                    videoActionButtons.style.display = 'flex';
                }
                // Enable label interactions in edit mode
                if (videoUploadLabel) {
                    videoUploadLabel.style.cursor = 'pointer';
                    videoUploadLabel.style.pointerEvents = 'auto';
                    videoUploadLabel.style.opacity = '1';
                }
            } else {
                // Disable video upload when not in edit mode
                if (videoInput) {
                    videoInput.disabled = true;
                }
                if (videoActionButtons) {
                    videoActionButtons.style.display = 'none';
                }
                // Hide upload area if video exists, show if no video (but disabled)
                if (videoUploadArea) {
                    videoUploadArea.style.display = hasVideo ? 'none' : 'block';
                }
                // Disable label interactions when not in edit mode
                if (videoUploadLabel) {
                    videoUploadLabel.style.cursor = 'default';
                    videoUploadLabel.style.pointerEvents = 'none';
                    videoUploadLabel.style.opacity = '0.6';
                }
            }
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
                closeEditCard();
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        }

        // Initialize video upload controls based on current state
        const isInitiallyEditing = editCard && !editCard.classList.contains('d-none');
        toggleVideoUploadControls(isInitiallyEditing);

        if (editCard && editCard.dataset.openOnLoad === '1') {
            openEditCard();
            document.body.classList.add('is-editing');
        }

        // If user resizes from mobile->desktop while editing, put the portfolio back
        window.addEventListener('resize', () => {
            if (!document.body.classList.contains('is-editing')) return;
            if (!isMobile()) restoreMediaPortfolioPosition();
            if (isMobile()) moveMediaPortfolioIntoEditCard();
        });

        if (window.jQuery && $('.select2').length) {
            $('.select2').select2({
                width: '100%',
                placeholder: 'Select options'
            });
        }

        // Setup photo-item upload handlers (similar to onboarding step 5)
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
                if (e.target.closest('.upload-progress-container')) return;
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
                const mediaId = item.dataset.mediaId;
                this.dataset.processing = 'true';

                // Show progress container
                const progressContainer = item.querySelector('.upload-progress-container');
                const progressBar = item.querySelector('.upload-progress-bar');
                if (progressContainer) {
                    progressContainer.style.display = 'block';
                }
                if (progressBar) {
                    progressBar.style.width = '0%';
                }

                // Preview image immediately
                const reader = new FileReader();
                reader.onload = function(e) {
                    let img = item.querySelector('img.preview-img');
                    if (!img) {
                        const placeholder = item.querySelector('div:not(.upload-overlay):not(.upload-progress-container)');
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
                        if (uploadText) uploadText.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_replace_photo') }}';
                    }
                };
                reader.readAsDataURL(file);

                // Upload to S3 via AJAX
                uploadImageToS3(file, mediaId, item, fileInput);
            });
        });

        // Function to upload image to S3 (similar to onboarding step 5)
        async function uploadImageToS3(file, mediaId, tile, input) {
            if (tile.dataset.uploading === 'true') return;
            tile.dataset.uploading = 'true';

            const uploadId = `upload_${mediaId || Date.now()}_${Math.random().toString(16).slice(2)}`;
            uploadModalTracker.show();
            uploadModalTracker.addItem(uploadId, file.name);

            const progressContainer = tile.querySelector('.upload-progress-container');
            const progressBar = tile.querySelector('.upload-progress-bar');

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
                    }),
                });

                if (!presignRes.ok) {
                    let msg = 'Failed to get upload URL';
                    try {
                        const data = await presignRes.json();
                        msg = data.message || msg;
                    } catch (e) {}
                    throw new Error(msg);
                }

                const { url, headers, key } = await presignRes.json();

                // Upload to S3 with progress tracking
                await uploadToS3Put(url, headers, file, (progress) => {
                    // Update modal progress
                    uploadModalTracker.updateProgress(uploadId, progress);
                    // Update tile progress bar
                    if (progressBar) {
                        progressBar.style.width = `${progress}%`;
                    }
                });

                uploadModalTracker.markComplete(uploadId);

                // Hide progress bar after a short delay
                if (progressContainer) {
                    setTimeout(() => {
                        progressContainer.style.display = 'none';
                        if (progressBar) progressBar.style.width = '0%';
                    }, 1000);
                }

                // Store S3 key in hidden input
                const form = document.querySelector('form[action*="profile"]');
                if (form) {
                    // Remove old hidden inputs for this media item
                    if (mediaId) {
                        const existingInputs = form.querySelectorAll(`input[name="updated_media_keys[]"][data-media-id="${mediaId}"]`);
                        existingInputs.forEach(inp => inp.remove());
                        const existingMediaIds = form.querySelectorAll(`input[name="updated_media_ids[]"][value="${mediaId}"]`);
                        existingMediaIds.forEach(inp => inp.remove());
                    }

                    // Add new hidden inputs
                    const keyInput = document.createElement('input');
                    keyInput.type = 'hidden';
                    keyInput.name = mediaId ? 'updated_media_keys[]' : 'additional_photo_keys[]';
                    keyInput.value = key;
                    if (mediaId) {
                        keyInput.dataset.mediaId = mediaId;
                    }
                    form.appendChild(keyInput);

                    if (mediaId) {
                        const mediaIdInput = document.createElement('input');
                        mediaIdInput.type = 'hidden';
                        mediaIdInput.name = 'updated_media_ids[]';
                        mediaIdInput.value = mediaId;
                        form.appendChild(mediaIdInput);
                    }
                }

            } catch (error) {
                uploadModalTracker.markError(uploadId, error.message);
                if (progressBar) {
                    progressBar.style.background = '#ef4444';
                }
                alert(error.message || 'Upload failed. Please try again.');
            } finally {
                input.dataset.processing = 'false';
                tile.dataset.uploading = 'false';
                input.value = '';
            }
        }

        // Function to remove media image (DISABLED for talents)
        function removeMediaImage(link, e) {
            if (e) {
                e.preventDefault();
                e.stopPropagation();
            }
            return;

            const tile = link.closest('.photo-item');
            if (!tile) return;

            const mediaId = tile?.dataset.mediaId;

            // Remove preview
            const img = tile?.querySelector('img.preview-img');
            if (img) {
                img.remove();
            }

            // Remove progress bar if visible
            const progressContainer = tile.querySelector('.upload-progress-container');
            if (progressContainer) {
                progressContainer.style.display = 'none';
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
                if (uploadText) uploadText.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_upload_photo') }}';
                const removeLink = overlay.querySelector('.remove-photo-link');
                if (removeLink) removeLink.remove();
            }

            // Mark for deletion
            const form = document.querySelector('form[action*="profile"]');
            if (form) {
                // Remove any uploaded/updated keys for this media item
                if (mediaId) {
                    const keyInputs = form.querySelectorAll(`input[name="updated_media_keys[]"][data-media-id="${mediaId}"]`);
                    keyInputs.forEach(inp => inp.remove());
                    const mediaIdInputs = form.querySelectorAll(`input[name="updated_media_ids[]"][value="${mediaId}"]`);
                    mediaIdInputs.forEach(inp => inp.remove());
                }

                // Add deletion marker for media ID
                if (mediaId) {
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
        }
        window.removeMediaImage = removeMediaImage;

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
                <div class="upload-progress-container" style="position: absolute; bottom: 0; left: 0; width: 100%; height: 6px; background: rgba(0,0,0,0.2); z-index: 10; display: none;">
                    <div class="upload-progress-bar" style="width: 0%; height: 100%; background: #10b981; transition: width 0.5s ease; box-shadow: 0 0 2px rgba(0,0,0,0.5);"></div>
                </div>
                <div class="upload-overlay">
                    <img src="{{ asset('images/upload.png') }}" alt="Upload" style="width: 20px; height: 20px; filter: brightness(0) invert(1);">
                    <span class="upload-text">{{ \App\Helpers\Bilingual::get('talent.profile_upload_photo') }}</span>
                </div>
                <input type="file" name="additional_photos[]" class="media-file-input" accept="image/*" style="display:none">
            `;

            grid.appendChild(newItem);

            // Setup handlers for new item (similar to existing items)
            const fileInput = newItem.querySelector('input[type="file"]');
            if (fileInput) {
                let filePickerOpen = false;

                newItem.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-photo-link')) return;
                    if (e.target.closest('.upload-progress-container')) return;
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

                fileInput.addEventListener('change', function(e) {
                    e.stopImmediatePropagation();
                    if (!this.files || !this.files[0]) return;
                    if (this.dataset.processing === 'true') return;

                    const file = this.files[0];
                    this.dataset.processing = 'true';

                    // Show progress container
                    const progressContainer = newItem.querySelector('.upload-progress-container');
                    const progressBar = newItem.querySelector('.upload-progress-bar');
                    if (progressContainer) {
                        progressContainer.style.display = 'block';
                    }
                    if (progressBar) {
                        progressBar.style.width = '0%';
                    }

                    // Preview image immediately
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        let img = newItem.querySelector('img.preview-img');
                        if (!img) {
                            const placeholder = newItem.querySelector('div:not(.upload-overlay):not(.upload-progress-container)');
                            if (placeholder) placeholder.remove();
                            img = document.createElement('img');
                            img.className = 'preview-img';
                            img.style.cssText = 'width: 100%; height: 100%; object-fit: cover; display: block;';
                            newItem.insertBefore(img, newItem.firstChild);
                        }
                        img.src = e.target.result;

                        // Update overlay
                        const overlay = newItem.querySelector('.upload-overlay');
                        if (overlay) {
                            const uploadText = overlay.querySelector('.upload-text');
                            if (uploadText) uploadText.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_replace_photo') }}';
                        }
                    };
                    reader.readAsDataURL(file);

                    // Upload to S3 via AJAX (no mediaId for new items)
                    uploadImageToS3(file, null, newItem, fileInput);
                });
            }
        }

        // Add "Add More Photos" button to additional photos section
        const additionalPhotosSection = document.querySelector('#additionalPhotosGrid')?.closest('.portfolio-section');
        if (additionalPhotosSection) {
            const addMoreBtn = document.createElement('button');
            addMoreBtn.type = 'button';
            addMoreBtn.className = 'add-more-btn';
            addMoreBtn.style.cssText = 'margin-top: 12px; padding: 8px 16px; background: #10b981; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 600; display: none;';
                    addMoreBtn.innerHTML = '<i class="fas fa-plus"></i> {{ \App\Helpers\Bilingual::get('talent.profile_add_more_images') ?? 'Add more Profile Images' }}';
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

        // Show/hide delete buttons for additional photos in edit mode (DISABLED)
        function toggleEditModeForPhotos() {
            const deleteButtons = document.querySelectorAll('.delete-media-btn');
            deleteButtons.forEach(btn => {
                btn.style.display = 'none';
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

        // Handle delete media buttons (DISABLED)
        document.addEventListener('click', function(e) {
            if (e.target.closest('.delete-media-btn')) {
                e.preventDefault();
                e.stopPropagation();
                return false;
            }
        });

        // Video Upload Handling
        const videoInput = document.getElementById('upload_video');
        const videoProgressContainer = document.getElementById('video-progress-container');
        const videoProgressFill = document.getElementById('video-progress-fill');
        const videoUploadStatus = document.getElementById('video-upload-status');
        const replaceVideoBtn = document.getElementById('replace-video-btn');
        const removeVideoBtn = document.getElementById('remove-video-btn');
        const videoPreviewContainer = document.getElementById('video-preview-container');
        const videoUploadArea = document.getElementById('video-upload-area');
        const videoUploadLabel = document.querySelector('.video-upload-label');

        // Enable drag and drop only in edit mode
        if (videoUploadArea && videoUploadLabel) {
            // Prevent all drag and drop when not in edit mode
            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                videoUploadArea.addEventListener(eventName, function(e) {
                    // Only allow if in edit mode and input is enabled
                    if (!document.body.classList.contains('is-editing') || videoInput?.disabled) {
                        e.preventDefault();
                        e.stopPropagation();
                        return false;
                    }
                }, true); // Use capture phase to catch early
            });

            // Enable drag and drop visual feedback in edit mode only
            videoUploadArea.addEventListener('dragover', function(e) {
                if (document.body.classList.contains('is-editing') && !videoInput?.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    const uploadInner = videoUploadArea.querySelector('.upload-inner');
                    if (uploadInner) {
                        uploadInner.style.borderColor = '#10b981';
                        uploadInner.style.background = '#f0fdf4';
                    }
                }
            });

            videoUploadArea.addEventListener('dragleave', function(e) {
                if (document.body.classList.contains('is-editing') && !videoInput?.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    const uploadInner = videoUploadArea.querySelector('.upload-inner');
                    if (uploadInner) {
                        uploadInner.style.borderColor = '#d1d5db';
                        uploadInner.style.background = '#f9fafb';
                    }
                }
            });

            videoUploadArea.addEventListener('drop', function(e) {
                // Only process if in edit mode and input is enabled
                if (!document.body.classList.contains('is-editing') || videoInput?.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                e.preventDefault();
                e.stopPropagation();
                const uploadInner = videoUploadArea.querySelector('.upload-inner');
                if (uploadInner) {
                    uploadInner.style.borderColor = '#d1d5db';
                    uploadInner.style.background = '#f9fafb';
                }

                const files = e.dataTransfer.files;
                if (files && files.length > 0 && files[0].type.startsWith('video/')) {
                    videoInput.files = files;
                    videoInput.dispatchEvent(new Event('change', { bubbles: true }));
                }
            });

            // Prevent click when not in edit mode
            videoUploadLabel.addEventListener('click', function(e) {
                if (!document.body.classList.contains('is-editing') || videoInput?.disabled) {
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
            });
        }

        if (videoInput) {
            videoInput.addEventListener('change', function(e) {
                // Don't process if not in edit mode
                if (!document.body.classList.contains('is-editing') || this.disabled) {
                    this.value = '';
                    return;
                }
                const file = e.target.files[0];
                if (!file) return;

                // Validate file type
                if (!file.type.startsWith('video/')) {
                    alert('Please select a valid video file.');
                    this.value = '';
                    return;
                }

                // Validate file size (500MB max)
                const maxSize = 500 * 1024 * 1024; // 500MB
                if (file.size > maxSize) {
                    alert('Video file size exceeds maximum allowed size of 500MB.');
                    this.value = '';
                    return;
                }

                // Show progress
                if (videoProgressContainer) {
                    videoProgressContainer.style.display = 'block';
                }
                if (videoProgressFill) {
                    videoProgressFill.style.width = '0%';
                }
                if (videoUploadStatus) {
                    videoUploadStatus.style.display = 'block';
                    videoUploadStatus.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_uploading_video') }}';
                    videoUploadStatus.style.color = '#6b7280';
                }

                // Update label
                const label = document.querySelector('[data-file-label="video"]');
                if (label) {
                    label.textContent = file.name;
                }

                // Submit form with video file (like onboarding step 5)
                const form = document.querySelector('form[action*="profile"]');
                if (form) {
                    // Build FormData with all form fields + video file
                    const formData = new FormData(form);
                    formData.append('video', file);

                    // Use XMLHttpRequest for better file upload support and progress tracking
                    const xhr = new XMLHttpRequest();

                    // Track upload progress
                    xhr.upload.addEventListener('progress', (e) => {
                        if (e.lengthComputable && videoProgressFill) {
                            // Show upload progress (0-90% for file upload, then wait for Mux processing)
                            const uploadProgress = Math.min(90, Math.round((e.loaded / e.total) * 90));
                            videoProgressFill.style.width = uploadProgress + '%';
                        }
                    });

                    // Handle completion
                    xhr.addEventListener('load', () => {
                        if (xhr.status >= 200 && xhr.status < 300) {
                            // Success - show 100% and reload
                            if (videoProgressFill) {
                                videoProgressFill.style.width = '100%';
                            }
                            if (videoUploadStatus) {
                                videoUploadStatus.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_video_uploaded') }}';
                                videoUploadStatus.style.color = '#10b981';
                            }

                            // Reload page after a short delay to show updated video
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500);
                        } else {
                            // Handle errors
                            let errorMsg = 'Upload failed';
                            try {
                                const response = JSON.parse(xhr.responseText);
                                errorMsg = response.message || response.error || errorMsg;
                            } catch (e) {
                                // Try to extract error from HTML response
                                const errorMatch = xhr.responseText.match(/<div[^>]*class="[^"]*error[^"]*"[^>]*>([^<]+)<\/div>/i);
                                if (errorMatch) {
                                    errorMsg = errorMatch[1];
                                }
                            }

                            if (videoProgressFill) {
                                videoProgressFill.style.width = '0%';
                            }
                            if (videoUploadStatus) {
                                videoUploadStatus.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_upload_failed') }}: ' + errorMsg;
                                videoUploadStatus.style.color = '#dc2626';
                            }
                            alert('Video upload failed: ' + errorMsg);
                            this.value = '';
                        }
                    });

                    // Handle errors
                    xhr.addEventListener('error', () => {
                        if (videoProgressFill) {
                            videoProgressFill.style.width = '0%';
                        }
                        if (videoUploadStatus) {
                            videoUploadStatus.textContent = '{{ \App\Helpers\Bilingual::get('talent.profile_upload_failed') }}';
                            videoUploadStatus.style.color = '#dc2626';
                        }
                        alert('Network error. Please check your connection and try again.');
                        this.value = '';
                    });

                    // Handle abort
                    xhr.addEventListener('abort', () => {
                        if (videoProgressFill) {
                            videoProgressFill.style.width = '0%';
                        }
                        this.value = '';
                    });

                    // Start upload
                    xhr.open('POST', form.action, true);
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.send(formData);
                }
            });
        }

        // Handle replace video button
        if (replaceVideoBtn) {
            replaceVideoBtn.addEventListener('click', function() {
                if (!document.body.classList.contains('is-editing')) return;
                if (videoPreviewContainer) videoPreviewContainer.style.display = 'none';
                if (videoUploadArea) {
                    videoUploadArea.style.display = 'block';
                    // Ensure input is enabled
                    if (videoInput) videoInput.disabled = false;
                }
                if (videoInput) videoInput.click();
            });
        }

        // Handle remove video button
        if (removeVideoBtn) {
            removeVideoBtn.addEventListener('click', function() {
                const confirmMsg = '{{ \App\Helpers\Bilingual::get('talent.profile_remove_video') }}' + '?';
                if (!confirm(confirmMsg)) {
                    return;
                }

                const form = document.querySelector('form[action*="profile"]');
                if (form) {
                    // Set remove_video flag
                    const removeInput = document.getElementById('remove_video_input');
                    if (removeInput) {
                        removeInput.value = '1';
                    }

                    // Submit form
                    form.submit();
                }
            });
        }
    });
</script>
@endsection
