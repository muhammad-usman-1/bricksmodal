@extends('layouts.admin')
@section('content')
<link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
<style>
    :root {
        --bg: #f7f8fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e5e7eb;
        --shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        --pill-green: #e6f7ed;
        --pill-green-text: #15803d;
    }

    body { background: var(--bg); }

    .talents-shell { position: relative; }
    .talents-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 10px 0 12px;
    }
    .talents-head h5 {  color: black;
           font-family: 'Arimo', sans-serif; color: black; font-size: 24px;  font-weight: 400; line-height: 36px;}
    .talents-head .meta { margin: 4px 0; color: var(--ink-500); font-size: 13px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .search-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; }
    .search-input { min-width: 240px; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--ink-700); background: #fff; }
    .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 6px; }
    .pill-btn { border: 1px solid var(--border); background: #fff; color: var(--ink-700); border-radius: 8px; padding: 7px 12px; font-size: 12px; cursor: pointer; transition: all .15s ease; }
    .pill-btn.active { background: black; color: #fff; border-color: #0f1524; }

    .talent-grid { padding-bottom: 15px; display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .talent-card { position: relative; background: #f0f1f3; border-radius: 10px; overflow: hidden; height: 340px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid var(--border); transition: transform 0.2s ease; cursor: pointer; }
    .talent-card:hover { transform: translateY(-4px); }
    .talent-img-container { position: relative; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
    .talent-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; display: none; z-index: 1; }
    .talent-img.active { display: block; z-index: 1; }
    .talent-card:hover .talent-img:not(.active) { display: none; }
    .talent-card:hover .talent-img.active { display: block; }

    .badge-active {
        position: absolute; top: 15px; left: 15px;
        background: #e6f7ed; color: #15803d;
        border-radius: 20px; padding: 4px 12px;
        font-size: 11px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        z-index: 20;
        pointer-events: none;
    }
    .badge-active::before {
        content: ''; width: 6px; height: 6px; background: #10b981; border-radius: 50%;
    }
    .badge-pending {
        background: #fffbeb; color: #f59e0b;
    }
    .badge-pending::before {
        background: #f59e0b;
    }
    .badge-rejected {
        background: #fee2e2; color: #dc2626;
    }
    .badge-rejected::before {
        background: #dc2626;
    }
    .badge-suspended {
        background: #f3f4f6; color: #6b7280;
    }
    .badge-suspended::before {
        background: #6b7280;
    }

    .card-ellipsis { position: absolute; top: 12px; right: 15px; z-index: 30; }
    .dropdown-toggle-btn { color: #111; font-size: 16px; cursor: pointer; opacity: 0.6; transition: opacity 0.2s; }
    .dropdown-toggle-btn:hover { opacity: 1; }

    .actions-dropdown-container { position: relative; display: inline-block; }
    .actions-dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 8px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        min-width: 140px;
        display: none;
        overflow: hidden;
    }
    .actions-dropdown-menu.active { display: block; animation: dropdownFade 0.2s ease; }

    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .header-actions { position: relative; }
    .header-actions-btn {
        background: none;
        border: none;
        color: var(--ink-900);
        font-size: 20px;
        cursor: pointer;
        padding: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        outline: none;
    }
    .header-actions-btn:focus, .header-actions-btn:active {
        outline: none;
        border: none;
        background: none;
    }

    /* Empty State Styles */
    .empty-state {
        grid-column: 1 / -1;
        padding: 60px 20px;
        text-align: center;
        background: #fff;
        border-radius: 16px;
        border: 1px dashed #e2e8f0;
        margin: 20px 0;
    }
    .empty-state-content {
        max-width: 400px;
        margin: 0 auto;
    }
    .empty-icon {
        width: 64px;
        height: 64px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
    }
    .empty-icon i {
        font-size: 24px;
        color: #94a3b8;
    }
    .empty-state h3 {
        font-size: 18px;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 8px;
    }
    .empty-state p {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }
    .header-dropdown {
        position: absolute;
        top: 100%;
        right: 0;
        margin-top: 8px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 1000;
        min-width: 180px;
        display: none;
        overflow: hidden;
    }
    .header-dropdown.active { display: block; animation: dropdownFade 0.2s ease; }
    .header-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px 16px;
        font-size: 13px;
        color: var(--ink-700);
        text-decoration: none;
        transition: background 0.12s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .header-dropdown-item:hover { background: #f3f5f9; color: var(--ink-900); text-decoration: none; }
    .header-dropdown-item i { font-size: 14px; width: 16px; text-align: center; }

    .actions-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--ink-700);
        text-decoration: none;
        transition: background 0.12s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .actions-dropdown-item:hover { background: #f3f5f9; color: var(--ink-900); text-decoration: none; }
    .actions-dropdown-item.text-danger { color: #dc2626; }
    .actions-dropdown-item.text-danger:hover { background: #fef2f2; }

    .card-overlay {
        position: absolute; left: 0; right: 0; bottom: 0;
        height: 50%;
        padding: 20px 18px 15px;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex; flex-direction: column;
        justify-content: flex-end;
        z-index: 10;
        pointer-events: none;
        transition: none;
    }

    .overlay-top { position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; margin-bottom: 4px; transition: none; }
    .overlay-flag { position: absolute; left: 0; top: 0; width: auto; height: 22px; aspect-ratio: 4 / 3; display: inline-block; transition: none; background-size: contain; background-position: center; background-repeat: no-repeat; }
    .overlay-meta-info { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.9); font-weight: 500; transition: none; }

    .talent-name { font-weight: 600; font-size: 16px; margin: 4px 0 12px; text-align: center; transition: none; }

    .card-divider { width: 100%; height: 1px; background: rgba(255,255,255,0.3); margin-bottom: 12px; transition: none; }

    .overlay-bottom { display: flex; justify-content: space-between; align-items: flex-end; transition: none; }
    .joined-info { display: flex; flex-direction: column; gap: 2px; transition: none; }
    .joined-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7); font-weight: 700; transition: none; }
    .joined-date { font-size: 12px; font-weight: 500; color: #fff; transition: none; }

    @media (max-width: 640px) {
        .talent-card { height: 280px; }
        .card-overlay { height: 110px; }
    }

    .add-talent-btn {
        background: black;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;

        text-decoration: none;
        transition: all 0.2s ease;
    }

    .add-talent-btn:hover {
        background: #111111;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .add-talent-btn i {
        font-size: 16px;
    }

    .talent-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;

        padding: 12px 24px;
        display: flex;
        justify-content: flex-end;
        z-index: 50;

    }

    /* Combine Profiles Modal Styles */
    .combine-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.6);
        z-index: 10000;
        overflow-y: auto;
        padding: 20px;
    }
    .combine-modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .combine-modal-content {
        background: #fff;
        border-radius: 16px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
    }
    .combine-modal-header {
        padding: 24px;
        border-bottom: 1px solid var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .combine-modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: var(--ink-900);
    }
    .combine-modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: var(--ink-500);
        cursor: pointer;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        transition: all 0.2s;
    }
    .combine-modal-close:hover {
        background: #f3f5f9;
        color: var(--ink-900);
    }
    .combine-modal-body {
        padding: 24px;
    }
    .profile-select-section {
        margin-bottom: 32px;
    }
    .profile-select-section h4 {
        font-size: 16px;
        font-weight: 600;
        color: var(--ink-900);
        margin-bottom: 12px;
    }
    .profile-select-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 24px;
    }
    .profile-select-card {
        border: 2px solid var(--border);
        border-radius: 12px;
        padding: 16px;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
    }
    .profile-select-card:hover {
        border-color: #000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    }
    .profile-select-card.selected {
        border-color: #000;
        background: #f9fafb;
    }
    .profile-select-card.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .profile-select-card h5 {
        margin: 0 0 8px 0;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink-900);
    }
    .profile-select-card .profile-info {
        font-size: 12px;
        color: var(--ink-500);
        margin-bottom: 4px;
    }
    .merge-options-section {
        margin-top: 32px;
        padding-top: 32px;
        border-top: 1px solid var(--border);
    }
    .merge-options-section h4 {
        font-size: 16px;
        font-weight: 600;
        color: var(--ink-900);
        margin-bottom: 20px;
    }
    .merge-option-group {
        margin-bottom: 24px;
    }
    .merge-option-group label {
        display: block;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink-700);
        margin-bottom: 8px;
    }
    .merge-option-group .radio-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    .merge-option-group .radio-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 10px;
        border: 1px solid var(--border);
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
    }
    .merge-option-group .radio-option:hover {
        background: #f9fafb;
        border-color: #000;
    }
    .merge-option-group .radio-option input[type="radio"] {
        margin: 0;
    }
    .merge-option-group .radio-option label {
        margin: 0;
        font-weight: 400;
        cursor: pointer;
        flex: 1;
    }
    .image-merge-options {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 12px;
        margin-top: 12px;
    }
    .image-merge-option {
        border: 2px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
    }
    .image-merge-option:hover {
        border-color: #000;
    }
    .image-merge-option.selected {
        border-color: #000;
        background: #f9fafb;
    }
    .image-merge-option input[type="radio"] {
        margin-bottom: 8px;
    }
    .image-merge-option label {
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        display: block;
    }
    .combine-modal-footer {
        padding: 24px;
        border-top: 1px solid var(--border);
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }
    .btn-combine {
        background: #000;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-combine:hover:not(:disabled) {
        background: #111;
    }
    .btn-combine:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    .btn-cancel {
        background: #fff;
        color: var(--ink-700);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 24px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-cancel:hover {
        background: #f9fafb;
    }
</style>

@php
    $activeCount = $stats['approved'] ?? ($talents->where('verification_status', 'approved')->count());
    $totalTalents = $stats['total'] ?? $talents->count();
    $fallbackImg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="360"><rect width="300" height="360" rx="18" fill="#e5e7eb"/><path d="M150 170c28 0 50-22 50-50s-22-50-50-50-50 22-50 50 22 50 50 50Zm0 20c-42 0-80 19-92 56-2 6 2 12 8 12h168c6 0 10-6 8-12-12-37-50-56-92-56Z" fill="#cbd5e1"/></svg>');

    // Define toUrl function first
    $toUrl = function($path) {
        if (!$path) return null;
        if (is_array($path)) {
            $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
        }
        if (!$path) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }

        // Cache key for this URL
        $cacheKey = 'talent_image_url_' . md5($path);

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

        return $resolvedUrl ?: asset('storage/' . ltrim($path, '/'));
    };

    // Collect all profile images for preloading
    $allProfileImages = [];
    foreach ($talents as $talent) {
        $talentImages = [];
        if ($talent->headshot_left_path) {
            $img = $toUrl($talent->headshot_left_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->headshot_center_path) {
            $img = $toUrl($talent->headshot_center_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->headshot_right_path) {
            $img = $toUrl($talent->headshot_right_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->full_body_front_path) {
            $img = $toUrl($talent->full_body_front_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->full_body_right_path) {
            $img = $toUrl($talent->full_body_right_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->full_body_back_path) {
            $img = $toUrl($talent->full_body_back_path);
            if ($img) $talentImages[] = $img;
        }
        if ($talent->media) {
            foreach ($talent->media as $mediaItem) {
                $img = $toUrl($mediaItem->file_path);
                if ($img) $talentImages[] = $img;
            }
        }
        $allProfileImages = array_merge($allProfileImages, array_filter($talentImages));
    }
    // Remove duplicates and filter out data URIs
    $allProfileImages = array_unique(array_filter($allProfileImages, function($img) {
        return $img && !\Illuminate\Support\Str::startsWith($img, 'data:');
    }));
@endphp

@foreach($allProfileImages as $preloadImg)
    <link rel="preload" as="image" href="{{ $preloadImg }}" fetchpriority="high">
@endforeach

<div class="talents-shell">
    <div class="talents-head">
        <div>
            <h5>Talents</h5>
            <div class="meta">
                <strong>{{ $activeCount }} active talents</strong>
                <span>•</span>
                <span>Manage and verify profiles</span>
            </div>
        </div>
        <div class="header-actions">
            <button type="button" class="header-actions-btn" id="headerActionsBtn" aria-label="Management options">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="header-dropdown" id="headerDropdown">
                <a href="{{ route('admin.talent-profiles.suspended') }}" class="header-dropdown-item">
                    <i class="fas fa-pause-circle"></i> Suspend Talent
                </a>
                <a href="{{ route('admin.talent-profiles.rejected') }}" class="header-dropdown-item">
                    <i class="fas fa-times-circle"></i> Reject Talent
                </a>
                @if(auth()->user()->isSuperAdmin())
                <button type="button" class="header-dropdown-item" id="combineProfilesBtn">
                    <i class="fas fa-code-branch"></i> Combine Profiles
                </button>
                @endif
            </div>
        </div>
    </div>

    <div class="search-row">
        <input type="text" id="talentSearch" class="search-input" placeholder="Search talents...">
    </div>
    <div class="filter-pills" id="filterPills">
        <button class="pill-btn active" data-filter="all">All Talents</button>
        <button class="pill-btn" data-filter="male">Male</button>
        <button class="pill-btn" data-filter="female">Female</button>
        <button class="pill-btn" data-filter="pending">Pending</button>
    </div>

    @if($talents->isEmpty())
        <div class="text-muted" style="padding:20px 0;">{{ trans('global.no_talents_found') }}</div>
    @else
        <div class="talent-grid" id="talentGrid">
            @foreach($talents as $talent)
                @php
                    $displayName = $talent->display_name ?? $talent->legal_name ?? trans('global.not_set');
                    $gender = strtolower($talent->gender ?? '');
                    $status = strtolower($talent->verification_status ?? 'pending');
                    $isVerified = $status === 'approved';
                    $isRejected = $status === 'rejected';
                    $dob = optional($talent->date_of_birth);
                    $age = $dob ? $dob->age : null;
                    $ageText = $age ? "• $age YEARS" : '';
                    $joinedAt = optional($talent->created_at)->format('d M Y') ?? '--';
                    $flagCode = $talent->nationality ?? $talent->country_code ?? $talent->country ?? null;
                    $flagUrl = $flagCode && strlen($flagCode) === 2 ? 'https://flagcdn.com/w40/' . strtolower($flagCode) . '.png' : null;
                    $avatarCandidate = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                    $avatar = $toUrl($avatarCandidate) ?: $fallbackImg;

                    // Helper function to normalize image path
                    $normalizeImage = $toUrl;

                    // 1. Collect from hardcoded columns
                    $headshotImages = [];
                    if ($talent->headshot_left_path) {
                        $img = $normalizeImage($talent->headshot_left_path);
                        if ($img) $headshotImages[] = $img;
                    }
                    if ($talent->headshot_center_path) {
                        $img = $normalizeImage($talent->headshot_center_path);
                        if ($img) $headshotImages[] = $img;
                    }
                    if ($talent->headshot_right_path) {
                        $img = $normalizeImage($talent->headshot_right_path);
                        if ($img) $headshotImages[] = $img;
                    }

                    $fullBodyImages = [];
                    if ($talent->full_body_front_path) {
                        $img = $normalizeImage($talent->full_body_front_path);
                        if ($img) $fullBodyImages[] = $img;
                    }
                    if ($talent->full_body_right_path) {
                        $img = $normalizeImage($talent->full_body_right_path);
                        if ($img) $fullBodyImages[] = $img;
                    }
                    if ($talent->full_body_back_path) {
                        $img = $normalizeImage($talent->full_body_back_path);
                        if ($img) $fullBodyImages[] = $img;
                    }

                    // 2. Collect from talent_media relationship
                    $talentMediaImages = [];
                    if ($talent->media) {
                        foreach ($talent->media as $mediaItem) {
                            $img = $normalizeImage($mediaItem->file_path);
                            if ($img) $talentMediaImages[] = $img;
                        }
                    }

                    // Combine all images (media table first, then headshots, then full-body)
                    $allImages = array_merge($talentMediaImages, $headshotImages, $fullBodyImages);
                    if (empty($allImages)) {
                        $allImages = [$avatar];
                    }
                @endphp
                @php
                    $isSuspended = $status === 'suspended';
                    $onboardingStep = $talent->onboarding_steps_completed ?? 0;
                    $hasCompletedStep5 = $onboardingStep >= 5 && $talent->onboarding_step === 'step-5';
                @endphp
                <div class="talent-card" data-gender="{{ $gender }}" data-status="{{ $status }}" data-name="{{ Str::lower($displayName) }}" data-url="{{ route('admin.talent-profiles.show', $talent->id) }}" data-images='@json($allImages)' data-onboarding-step="{{ $onboardingStep }}" data-completed-step5="{{ $hasCompletedStep5 ? '1' : '0' }}">
                    <div class="talent-img-container">
                        @foreach($allImages as $index => $imgSrc)
                            <img class="talent-img {{ $index === 0 ? 'active' : '' }}"
                                 src="{{ $imgSrc }}"
                                 alt="{{ $displayName }} - Image {{ $index + 1 }}"
                                 data-index="{{ $index }}"
                                 loading="eager"
                                 decoding="async"
                                 fetchpriority="{{ $index === 0 ? 'high' : 'auto' }}">
                        @endforeach
                    </div>
                    <span class="badge-active {{ $isVerified ? '' : ($isSuspended ? 'badge-suspended' : ($isRejected ? 'badge-rejected' : 'badge-pending')) }}">
                        {{ $isVerified ? 'Active' : ($isSuspended ? 'Suspended' : ($isRejected ? 'Rejected' : 'Pending')) }}
                    </span>
                    <div class="card-ellipsis actions-dropdown-container">
                        <span class="dropdown-toggle-btn"><i class="fas fa-ellipsis-v"></i></span>
                        <div class="actions-dropdown-menu">
                            <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="actions-dropdown-item">
                                <i class="far fa-eye"></i> View Profile
                            </a>
                            <form action="{{ route('admin.talent-profiles.destroy', $talent->id) }}" method="POST" class="delete-talent-form" data-swal-confirm="Are you sure? All the data will be deleted." style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="actions-dropdown-item text-danger">
                                    <i class="far fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-overlay">
                        <div class="overlay-top">
                            @if($flagCode && strtolower($flagCode) !== 'unspecified' && strlen($flagCode) === 2)
                            <div class="overlay-flag">
                                <span class="fi fi-{{ strtolower($flagCode) }}" title="{{ $flagCode }}"></span>
                            </div>
                            @endif
                            <span class="overlay-meta-info">{{ strtoupper($gender ?: 'N/A') }} {{ $ageText }}</span>
                        </div>
                        <p class="talent-name">{{ $displayName }}</p>
                        <div class="card-divider"></div>
                        <div class="overlay-bottom">
                            <div class="joined-info">
                                <span class="joined-label">Joined</span>
                                <span class="joined-date">{{ $joinedAt }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="emptyState" class="empty-state d-none">
            <div class="empty-state-content">
                <div class="empty-icon">
                    <i class="fas fa-search"></i>
                </div>
                <h3 id="emptyStateTitle">No talents found</h3>
                <p id="emptyStateText">We couldn't find any talents matching your criteria.</p>
            </div>
        </div>
    @endif
</div>

<!-- Combine Profiles Modal -->
@if(auth()->user()->isSuperAdmin())
<div class="combine-modal" id="combineModal">
    <div class="combine-modal-content">
        <div class="combine-modal-header">
            <h3><i class="fas fa-code-branch"></i> Combine Talent Profiles</h3>
            <button type="button" class="combine-modal-close" id="closeCombineModal">&times;</button>
        </div>
        <div class="combine-modal-body">
            <form id="combineProfilesForm">
                <div class="profile-select-section">
                    <h4>Select Two Profiles to Combine</h4>
                    <div class="profile-select-grid">
                        <div>
                            <h5 style="margin-bottom: 12px; font-size: 14px; font-weight: 600;">Primary Profile (will be kept)</h5>
                            <select name="primary_profile_id" id="primaryProfileSelect" class="search-input" required>
                                <option value="">Select primary profile...</option>
                                @foreach($talents as $talent)
                                    <option value="{{ $talent->id }}" data-name="{{ $talent->display_name ?? $talent->legal_name }}" data-phone="{{ $talent->mobile_number ?? $talent->whatsapp_number ?? 'N/A' }}">
                                        {{ $talent->display_name ?? $talent->legal_name }} ({{ $talent->mobile_number ?? $talent->whatsapp_number ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="primaryProfileInfo" style="margin-top: 12px; padding: 12px; background: #f9fafb; border-radius: 8px; display: none;">
                                <div style="font-size: 12px; color: var(--ink-500);">Selected Profile:</div>
                                <div id="primaryProfileDetails" style="font-size: 14px; font-weight: 600; color: var(--ink-900); margin-top: 4px;"></div>
                            </div>
                        </div>
                        <div>
                            <h5 style="margin-bottom: 12px; font-size: 14px; font-weight: 600;">Secondary Profile (will be merged into primary)</h5>
                            <select name="secondary_profile_id" id="secondaryProfileSelect" class="search-input" required>
                                <option value="">Select secondary profile...</option>
                                @foreach($talents as $talent)
                                    <option value="{{ $talent->id }}" data-name="{{ $talent->display_name ?? $talent->legal_name }}" data-phone="{{ $talent->mobile_number ?? $talent->whatsapp_number ?? 'N/A' }}">
                                        {{ $talent->display_name ?? $talent->legal_name }} ({{ $talent->mobile_number ?? $talent->whatsapp_number ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            <div id="secondaryProfileInfo" style="margin-top: 12px; padding: 12px; background: #f9fafb; border-radius: 8px; display: none;">
                                <div style="font-size: 12px; color: var(--ink-500);">Selected Profile:</div>
                                <div id="secondaryProfileDetails" style="font-size: 14px; font-weight: 600; color: var(--ink-900); margin-top: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="merge-options-section" id="mergeOptionsSection" style="display: none;">
                    <h4>Merge Options</h4>

                    <div class="merge-option-group">
                        <label>Primary Phone Number</label>
                        <div class="radio-group" id="phoneNumberOptions">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>

                    <div class="merge-option-group">
                        <label>Profile Data to Keep</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="profile_data" id="profileDataPrimary" value="primary" checked>
                                <label for="profileDataPrimary">Keep primary profile data (merge missing fields from secondary)</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="profile_data" id="profileDataSecondary" value="secondary">
                                <label for="profileDataSecondary">Keep secondary profile data (merge missing fields from primary)</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="profile_data" id="profileDataMerge" value="merge">
                                <label for="profileDataMerge">Merge intelligently (keep most complete data)</label>
                            </div>
                        </div>
                    </div>

                    <div class="merge-option-group">
                        <label>Images to Keep</label>
                        <div class="radio-group">
                            <div class="radio-option">
                                <input type="radio" name="images" id="imagesPrimary" value="primary" checked>
                                <label for="imagesPrimary">Keep primary profile images</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="images" id="imagesSecondary" value="secondary">
                                <label for="imagesSecondary">Keep secondary profile images</label>
                            </div>
                            <div class="radio-option">
                                <input type="radio" name="images" id="imagesMerge" value="merge">
                                <label for="imagesMerge">Merge images (keep all unique images)</label>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="combine-modal-footer">
            <button type="button" class="btn-cancel" id="cancelCombineBtn">Cancel</button>
            <button type="button" class="btn-combine" id="submitCombineBtn" disabled>Combine Profiles</button>
        </div>
    </div>
</div>
@endif

<div class="talent-footer">
    <a href="{{ route('admin.talent-profiles.create') }}" class="add-talent-btn">
        <i class="fas fa-plus"></i> Add Talent
    </a>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const headerActionsBtn = document.getElementById('headerActionsBtn');
        const headerDropdown = document.getElementById('headerDropdown');

        if (headerActionsBtn && headerDropdown) {
            headerActionsBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                headerDropdown.classList.toggle('active');
            });

            document.addEventListener('click', function(e) {
                if (!headerDropdown.contains(e.target) && !headerActionsBtn.contains(e.target)) {
                    headerDropdown.classList.remove('active');
                }
            });
        }

        const pills = Array.from(document.querySelectorAll('#filterPills .pill-btn'));
        const searchInput = document.getElementById('talentSearch');
        const cards = Array.from(document.querySelectorAll('#talentGrid .talent-card'));

        function applyFilters() {
            const activePill = pills.find(p => p.classList.contains('active'));
            const filter = activePill ? activePill.dataset.filter : 'all';
            const term = (searchInput?.value || '').toLowerCase();

            let visibleCount = 0;
            cards.forEach(card => {
                const gender = card.dataset.gender || '';
                const status = (card.dataset.status || '').toLowerCase();
                const name = card.dataset.name || '';

                const matchesSearch = !term || name.includes(term);

                let matchesFilter = false;
                
                // Filter by status first, then by gender if applicable
                if (filter === 'all') {
                    // All Talents button should only show approved/verified talents
                    matchesFilter = status === 'approved' || status === 'verified';
                } else if (filter === 'male') {
                    // Male filter should only show approved/verified male talents
                    matchesFilter = gender === 'male' && (status === 'approved' || status === 'verified');
                } else if (filter === 'female') {
                    // Female filter should only show approved/verified female talents
                    matchesFilter = gender === 'female' && (status === 'approved' || status === 'verified');
                } else if (filter === 'pending') {
                    // Pending filter should only show pending talents
                    matchesFilter = status === 'pending';
                } else if (filter === 'rejected') {
                    // Rejected filter should only show rejected talents
                    matchesFilter = status === 'rejected';
                } else if (filter === 'suspended') {
                    // Suspended filter should only show suspended talents
                    matchesFilter = status === 'suspended';
                }

                const isVisible = matchesSearch && matchesFilter;
                card.style.display = isVisible ? '' : 'none';
                if (isVisible) visibleCount++;
            });

            // Handle empty state
            const emptyState = document.getElementById('emptyState');
            const emptyStateTitle = document.getElementById('emptyStateTitle');
            const emptyStateText = document.getElementById('emptyStateText');

            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.classList.remove('d-none');
                    if (filter === 'pending') {
                        emptyStateTitle.textContent = 'No pending talents';
                        emptyStateText.textContent = 'There are no pending talents to review at the moment.';
                    } else if (filter === 'rejected') {
                        emptyStateTitle.textContent = 'No rejected talents';
                        emptyStateText.textContent = 'There are no rejected talents at the moment.';
                    } else if (filter === 'suspended') {
                        emptyStateTitle.textContent = 'No suspended talents';
                        emptyStateText.textContent = 'There are no suspended talents at the moment.';
                    } else if (term) {
                        emptyStateTitle.textContent = 'No results found';
                        emptyStateText.textContent = `We couldn't find any talents matching "${term}".`;
                    } else {
                        emptyStateTitle.textContent = 'No talents found';
                        emptyStateText.textContent = "We couldn't find any talents matching your criteria.";
                    }
                } else {
                    emptyState.classList.add('d-none');
                }
            }
        }

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                applyFilters();
            });
        });

        searchInput?.addEventListener('input', applyFilters);

        // Initial apply
        applyFilters();

        // Make cards clickable
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't navigate if clicking on the ellipsis menu
                if (e.target.closest('.card-ellipsis')) {
                    return;
                }
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Cache API for persistent image storage across page visits
        const imageCacheName = 'talent-profile-images-v1';

        // Function to cache an image URL
        async function cacheImage(url) {
            try {
                if ('caches' in window) {
                    const cache = await caches.open(imageCacheName);
                    // Check if already cached
                    const cached = await cache.match(url);
                    if (!cached) {
                        // Fetch and cache the image
                        try {
                            await cache.add(url);
                        } catch (e) {
                            // If cache.add fails (CORS), try fetch with proper headers
                            try {
                                const response = await fetch(url, {
                                    mode: 'cors',
                                    credentials: 'omit',
                                    cache: 'force-cache'
                                });
                                if (response.ok) {
                                    await cache.put(url, response.clone());
                                }
                            } catch (fetchError) {
                                // Silently fail - browser will handle caching
                            }
                        }
                    }
                }
            } catch (e) {
                // Silently fail - browser native caching will handle it
            }
        }

        // Function to load image from cache first, then network
        async function loadImageWithCache(imgElement, originalUrl) {
            // Check cache first
            if ('caches' in window) {
                try {
                    const cache = await caches.open(imageCacheName);
                    const cachedResponse = await cache.match(originalUrl);

                    if (cachedResponse) {
                        // Use cached image - create blob URL
                        const blob = await cachedResponse.blob();
                        const objectUrl = URL.createObjectURL(blob);

                        // Set image source to cached blob
                        if (imgElement.tagName === 'IMG') {
                            imgElement.src = objectUrl;
                            // Clean up object URL after image loads
                            imgElement.onload = function() {
                                URL.revokeObjectURL(objectUrl);
                            };
                        }
                        return Promise.resolve();
                    }
                } catch (e) {
                    // Fall through to normal loading
                }
            }

            // Not in cache, load normally
            return new Promise((resolve) => {
                const preloadImg = new Image();
                preloadImg.onload = () => {
                    // Cache the image for future visits
                    cacheImage(originalUrl);
                    resolve();
                };
                preloadImg.onerror = () => {
                    resolve(); // Continue even if fails
                };
                preloadImg.src = originalUrl;
            });
        }

        // Preload and cache all images immediately on page load
        const allImages = document.querySelectorAll('.talent-img');
        const imagePromises = [];

        allImages.forEach(img => {
            const imgSrc = img.src;
            if (imgSrc && !imgSrc.startsWith('data:')) {
                if (!img.complete) {
                    // Image not loaded yet - load from cache or network
                    const promise = loadImageWithCache(img, imgSrc);
                    imagePromises.push(promise);
                } else {
                    // Image already loaded - cache it for future visits
                    cacheImage(imgSrc);
                    imagePromises.push(Promise.resolve());
                }
            } else {
                imagePromises.push(Promise.resolve());
            }
        });

        // Wait for all images to load, then ensure they're all cached
        Promise.all(imagePromises).then(() => {
            console.log('All profile images loaded and cached for future visits');

            // Double-check all images are cached
            allImages.forEach(img => {
                if (img.src && !img.src.startsWith('data:')) {
                    cacheImage(img.src);
                }
            });
        });

        // Image rotation on hover (images are now preloaded)
        cards.forEach(card => {
            const images = card.querySelectorAll('.talent-img');
            if (images.length <= 1) return; // No rotation needed if only one image

            let rotationInterval = null;
            let currentIndex = 0;

            card.addEventListener('mouseenter', function() {
                // Start rotation immediately on hover - no delay
                if (rotationInterval) return; // Already rotating

                // Change to next image immediately (no pause)
                images[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add('active');

                // Continue rotation with fast interval (no pause)
                rotationInterval = setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 150); // Fast rotation - 150ms between changes
            });

            card.addEventListener('mouseleave', function() {
                if (rotationInterval) {
                    clearInterval(rotationInterval);
                    rotationInterval = null;
                }
                images.forEach((img, idx) => {
                    img.classList.toggle('active', idx === 0);
                });
                currentIndex = 0;
            });
        });

        // Ellipsis dropdown toggle
        const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');
        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const menu = this.nextElementSibling;

                // Close other open menus
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });

                menu.classList.toggle('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        });

        // Direct SweetAlert handler for delete forms (backup for global listener)
        document.querySelectorAll('.delete-talent-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (form.dataset.swalConfirmed === 'true') return;

                e.preventDefault();
                e.stopPropagation();

                const message = form.dataset.swalConfirm || 'Are you sure?';

                Swal.fire({
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.swalConfirmed = 'true';
                        form.submit();
                    }
                });
            });
        });

        @if(auth()->user()->isSuperAdmin())
        // Combine Profiles Modal Functionality
        const combineModal = document.getElementById('combineModal');
        const combineProfilesBtn = document.getElementById('combineProfilesBtn');
        const closeCombineModal = document.getElementById('closeCombineModal');
        const cancelCombineBtn = document.getElementById('cancelCombineBtn');
        const submitCombineBtn = document.getElementById('submitCombineBtn');
        const primaryProfileSelect = document.getElementById('primaryProfileSelect');
        const secondaryProfileSelect = document.getElementById('secondaryProfileSelect');
        const mergeOptionsSection = document.getElementById('mergeOptionsSection');
        const phoneNumberOptions = document.getElementById('phoneNumberOptions');

        function openCombineModal() {
            if (combineModal) {
                combineModal.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
        }

        function closeCombineModalFunc() {
            if (combineModal) {
                combineModal.classList.remove('active');
                document.body.style.overflow = '';
                // Reset form
                document.getElementById('combineProfilesForm').reset();
                mergeOptionsSection.style.display = 'none';
                submitCombineBtn.disabled = true;
                document.getElementById('primaryProfileInfo').style.display = 'none';
                document.getElementById('secondaryProfileInfo').style.display = 'none';
            }
        }

        function updatePhoneNumberOptions() {
            const primaryId = primaryProfileSelect.value;
            const secondaryId = secondaryProfileSelect.value;
            
            if (!primaryId || !secondaryId) {
                phoneNumberOptions.innerHTML = '';
                return;
            }

            const primaryOption = primaryProfileSelect.options[primaryProfileSelect.selectedIndex];
            const secondaryOption = secondaryProfileSelect.options[secondaryProfileSelect.selectedIndex];
            const primaryPhone = primaryOption.dataset.phone || 'N/A';
            const secondaryPhone = secondaryOption.dataset.phone || 'N/A';

            phoneNumberOptions.innerHTML = `
                <div class="radio-option">
                    <input type="radio" name="primary_phone" id="phonePrimary" value="primary" checked>
                    <label for="phonePrimary">Primary Profile: ${primaryPhone}</label>
                </div>
                <div class="radio-option">
                    <input type="radio" name="primary_phone" id="phoneSecondary" value="secondary">
                    <label for="phoneSecondary">Secondary Profile: ${secondaryPhone}</label>
                </div>
            `;
        }

        function updateProfileInfo() {
            const primaryId = primaryProfileSelect.value;
            const secondaryId = secondaryProfileSelect.value;
            
            if (primaryId) {
                const option = primaryProfileSelect.options[primaryProfileSelect.selectedIndex];
                const name = option.dataset.name || 'N/A';
                const phone = option.dataset.phone || 'N/A';
                document.getElementById('primaryProfileDetails').textContent = `${name} (${phone})`;
                document.getElementById('primaryProfileInfo').style.display = 'block';
            } else {
                document.getElementById('primaryProfileInfo').style.display = 'none';
            }

            if (secondaryId) {
                const option = secondaryProfileSelect.options[secondaryProfileSelect.selectedIndex];
                const name = option.dataset.name || 'N/A';
                const phone = option.dataset.phone || 'N/A';
                document.getElementById('secondaryProfileDetails').textContent = `${name} (${phone})`;
                document.getElementById('secondaryProfileInfo').style.display = 'block';
            } else {
                document.getElementById('secondaryProfileInfo').style.display = 'none';
            }

            // Disable secondary profile option in primary select and vice versa
            Array.from(primaryProfileSelect.options).forEach(opt => {
                opt.disabled = opt.value === secondaryId;
            });
            Array.from(secondaryProfileSelect.options).forEach(opt => {
                opt.disabled = opt.value === primaryId;
            });

            // Show merge options if both profiles are selected
            if (primaryId && secondaryId && primaryId !== secondaryId) {
                mergeOptionsSection.style.display = 'block';
                updatePhoneNumberOptions();
                submitCombineBtn.disabled = false;
            } else {
                mergeOptionsSection.style.display = 'none';
                submitCombineBtn.disabled = true;
            }
        }

        if (combineProfilesBtn) {
            combineProfilesBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                openCombineModal();
            });
        }

        if (closeCombineModal) {
            closeCombineModal.addEventListener('click', closeCombineModalFunc);
        }

        if (cancelCombineBtn) {
            cancelCombineBtn.addEventListener('click', closeCombineModalFunc);
        }

        // Close modal when clicking outside
        if (combineModal) {
            combineModal.addEventListener('click', function(e) {
                if (e.target === combineModal) {
                    closeCombineModalFunc();
                }
            });
        }

        if (primaryProfileSelect) {
            primaryProfileSelect.addEventListener('change', updateProfileInfo);
        }

        if (secondaryProfileSelect) {
            secondaryProfileSelect.addEventListener('change', updateProfileInfo);
        }

        if (submitCombineBtn) {
            submitCombineBtn.addEventListener('click', function() {
                const primaryId = primaryProfileSelect.value;
                const secondaryId = secondaryProfileSelect.value;

                if (!primaryId || !secondaryId || primaryId === secondaryId) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select two different profiles to combine.'
                    });
                    return;
                }

                const formData = {
                    primary_profile_id: primaryId,
                    secondary_profile_id: secondaryId,
                    primary_phone: document.querySelector('input[name="primary_phone"]:checked')?.value || 'primary',
                    profile_data: document.querySelector('input[name="profile_data"]:checked')?.value || 'primary',
                    images: document.querySelector('input[name="images"]:checked')?.value || 'primary',
                };

                // Close modal first
                closeCombineModalFunc();

                // Confirm before combining
                Swal.fire({
                    title: 'Combine Profiles?',
                    text: 'This action will merge the secondary profile into the primary profile. The secondary profile will be deleted. This cannot be undone.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, combine them',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Show loading
                        Swal.fire({
                            title: 'Combining profiles...',
                            text: 'Please wait while we merge the profiles.',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });

                        // Submit the combine request
                        fetch('{{ route("admin.talent-profiles.combine") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify(formData)
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Profiles Combined!',
                                    text: data.message || 'The profiles have been successfully combined.',
                                    confirmButtonColor: '#000'
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: data.message || 'Failed to combine profiles. Please try again.'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'An error occurred while combining profiles. Please try again.'
                            });
                        });
                    }
                });
            });
        }
        @endif
    });
</script>
@endsection
