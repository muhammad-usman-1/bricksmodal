@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card-bg: #ffffff;
        --text-main: #0f172a;
        --text-sub: #64748b;
        --border: #e2e8f0;
        --primary: #000;
        --accent: #22c55e;
    }
    body { background: var(--bg); font-family: 'Inter', sans-serif; }

    /* Main Container */
    .dashboard-container {
        margin: 0 auto;
    }

    /* Top Card - Details & Map */
    .sh-top-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px;
        display: flex;
        gap: 30px;
        margin-bottom: 24px;
    }
    .sh-details {
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .sh-badge {
        background: #000;
        color: #fff;
        padding: 6px 16px;
        border-radius: 30px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        align-self: flex-start;
        margin-bottom: 16px;
        letter-spacing: 0.5px;
    }
    .sh-title {
        font-size: 22px;
        font-weight: 700;
        color: var(--text-main);
        margin-bottom: 6px;
    }
    .sh-client {
        font-size: 14px;
        color: var(--text-sub);
        margin-bottom: 24px;
    }
    .sh-meta-row {
        display: flex;
        gap: 40px; /* Increased gap for separation */
        margin-bottom: auto;
    }
    .sh-meta-item {
        display: flex;
        gap: 12px;
        align-items: flex-start; /* Align icon with text top */
    }
    .sh-meta-icon {
        /* Removed background box to match image */
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94a3b8; /* Light grey icon color */
        font-size: 20px;
        margin-top: 2px; /* Slight adjustment for alignment */
    }
    .sh-meta-text div:first-child {
        font-size: 12px;
        color: #94a3b8; /* Caption color */
        font-weight: 500;
        margin-bottom: 2px;
    }
    .sh-meta-text div:last-child {
        font-size: 14px;
        color: #0f172a; /* Main text color */
        font-weight: 600;
        white-space: nowrap;
    }

    .sh-actions {
        display: flex;
        gap: 12px;
        margin-top: 24px;
    }
    .nav-btn {
        padding: 10px 20px;
        border-radius: 10px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
    }
    .nav-btn.primary {
        background: #0f172a;
        color: #fff;
        border: 1px solid #0f172a;
    }
    .nav-btn.secondary {
        background: #fff;
        color: #0f172a;
        border: 1px solid #e2e8f0;
    }
    .nav-btn:hover { opacity: 0.9; text-decoration: none; }

    .sh-map-container {
        flex: 0 0 450px;
        background: #f1f5f9;
        border-radius: 20px;
        position: relative;
        overflow: hidden;
        min-height: 200px;
        border: 1px solid var(--border);
    }
    .sh-map-container iframe {
        filter: grayscale(0.5) contrast(1.1) brightness(1.05);
        transition: filter 0.3s ease;
    }
    .sh-map-container:hover iframe {
        filter: grayscale(0);
    }
    .sh-map-btn {
        position: absolute;
        bottom: 16px;
        right: 16px;
        background: #1e1e1e;
        color: #fff;
        padding: 10px 20px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 500;
        border: none;
        cursor: pointer;
        text-decoration: none !important;
        z-index: 10;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        transition: all 0.2s ease;
    }
    .sh-map-btn:hover {
        background: #000;
        transform: translateY(-1px);
        color: #fff !important;
    }

    /* Progress Stepper Card */
    .sh-progress-card {
        background: var(--card-bg);
        border-radius: 20px;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        padding: 24px 32px;
        margin-bottom: 32px;
    }
    .sh-progress-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--text-sub);
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 24px;
    }
    .sh-stepper {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 800px;
    }
    .sh-step {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 12px;
        position: relative;
        z-index: 1;
    }
    .sh-step-circle {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #f1f5f9;
        color: #94a3b8;
        display: grid;
        place-items: center;
        font-size: 18px;
        font-weight: 600;
        transition: all 0.3s;
    }
    .sh-step.completed .sh-step-circle {
        background: #0f172a;
        color: #fff;
    }
    .sh-step.active .sh-step-circle {
        background: #0f172a;
        color: #fff;
        box-shadow: 0 0 0 4px rgba(15, 23, 42, 0.1);
    }
    .sh-step-label {
        font-size: 13px;
        font-weight: 600;
        color: #94a3b8;
    }
    .sh-step.completed .sh-step-label,
    .sh-step.active .sh-step-label {
        color: #0f172a;
    }
    .sh-line {
        flex: 1;
        height: 2px;
        background: #e2e8f0;
        margin: 0 16px;
        margin-bottom: 24px; /* Align with circle center roughly */
    }
    .sh-line.filled { background: #0f172a; }

    /* Talent Pool Section */
    .sh-pool-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        margin-bottom: 20px;
    }
    .sh-pool-title h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--text-main);
        margin: 0 0 4px 0;
    }
    .sh-pool-title p {
        font-size: 13px;
        color: var(--text-sub);
        margin: 0;
    }
    .sh-filter-group {
        position: relative;
    }
    .sh-filter-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        background: #fff;
        color: #0f172a;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
    }
    .sh-filter-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 6px);
        min-width: 180px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        box-shadow: 0 12px 28px rgba(15,23,42,0.12);
        padding: 6px 0;
        display: none;
        z-index: 30;
    }
    .sh-filter-menu.show { display: block; }
    .sh-filter-menu button {
        width: 100%;
        background: transparent;
        border: none;
        text-align: left;
        padding: 9px 14px;
        font-size: 13px;
        color: #0f172a;
        cursor: pointer;
    }
    .sh-filter-menu button:hover {
        background: #f8fafc;
    }

    /* Talent Grid - matching talents.blade.php */
    .sh-talent-grid {
        margin-bottom: 10px;
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        align-items: stretch;
        justify-items: stretch;
    }
    .sh-talent-card {
        position: relative;
        background: #f0f1f3;
        border-radius: 10px;
        overflow: hidden;
        height: 340px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        border: 1px solid var(--border);
        transition: transform 0.2s ease;
        cursor: pointer;
    }
    .sh-talent-card:hover { transform: translateY(-4px); }
    .sh-talent-img-container {
        position: relative;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 1;
    }
    .sh-talent-img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        opacity: 0;
        transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out;
        transform: scale(1.05);
        z-index: 1;
    }
    .sh-talent-img.active {
        opacity: 1;
        transform: scale(1);
        z-index: 1;
    }
    .sh-talent-card:hover .sh-talent-img:not(.active) { opacity: 0; }
    .sh-talent-card:hover .sh-talent-img.active { opacity: 1; transform: scale(1); }

    .badge-active {
        position: absolute;
        top: 15px;
        left: 15px;
        background: #e6f7ed;
        color: #15803d;
        border-radius: 20px;
        padding: 4px 12px;
        font-size: 11px;
        font-weight: 700;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        z-index: 20;
        pointer-events: none;
    }
    .badge-active::before {
        content: '';
        width: 6px;
        height: 6px;
        background: #10b981;
        border-radius: 50%;
    }

    .card-overlay {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        height: 50%;
        padding: 20px 18px 15px;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        z-index: 10;
        pointer-events: none;
        transition: none;
    }

    .overlay-top {
        position: relative;
        width: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-bottom: 4px;
        transition: none;
    }
    .overlay-flag {
        position: absolute;
        left: 0;
        top: 0;
        width: auto;
        height: 22px;
        aspect-ratio: 4 / 3;
        display: inline-block;
        transition: none;
        background-size: contain;
        background-position: center;
        background-repeat: no-repeat;
    }
    .overlay-meta-info {
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: rgba(255,255,255,0.9);
        font-weight: 500;
        transition: none;
    }

    .talent-name {
        font-weight: 600;
        font-size: 16px;
        margin: 4px 0 12px;
        text-align: center;
        transition: none;
    }

    .card-divider {
        width: 100%;
        height: 1px;
        background: rgba(255,255,255,0.3);
        margin-bottom: 12px;
        transition: none;
    }

    .overlay-bottom {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        transition: none;
    }
    .joined-info {
        display: flex;
        flex-direction: column;
        gap: 2px;
        transition: none;
    }
    .joined-label {
        font-size: 9px;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        color: rgba(255,255,255,0.7);
        font-weight: 700;
        transition: none;
    }
    .joined-date {
        font-size: 12px;
        font-weight: 500;
        color: #fff;
        transition: none;
    }

    @media (max-width: 768px) {
        .sh-top-card { flex-direction: column; }
        .sh-map-container { flex: auto; min-height: 200px; }
        .sh-meta-row { flex-direction: column; gap: 16px; }
        .sh-progress-card { padding: 20px; overflow-x: auto; }
        .sh-stepper { min-width: 600px; }
    }
</style>

@php
    $hasProject = !is_null($project);
    if($hasProject) {
        $title = $project->project_name ?? 'Untitled Shoot';
        $client = $project->client_name ?? ($project->user->name ?? 'Unknown Client');
        $location = $project->location ?? 'Location Pending';
        $rawDate = $project->getRawOriginal('shoot_date_time');
        $durationRaw = $project->duration ?? '';
        $duration = preg_replace('/[^0-9]/', '', $durationRaw);

        $dateDisplay = '-';
        $timeDisplay = '-';

        if ($rawDate) {
            try {
                $carbonDate = \Carbon\Carbon::parse($rawDate);
                $dateDisplay = $carbonDate->format('Y-m-d');
                $timeOnly = $carbonDate->format('H:i');
                $timeDisplay = $timeOnly . ($duration ? " ({$duration} Hours)" : '');
            } catch (\Exception $e) {
                 $dateDisplay = $project->shoot_date_time ?? '-';
                 $timeDisplay = $duration ? "({$duration} Hours)" : '-';
            }
        }
        $status = $project->status ?? 'open';

        $steps = ['Advertised', 'Shortlisted', 'Selected', 'Completed'];
        $currentStepIndex = 1;
        if($status == 'completed') $currentStepIndex = 3;
        if($status == 'advertised') $currentStepIndex = 0;
    }
@endphp

<div class="dashboard-container">
    @if($hasProject)
        <!-- Top Card -->
        <div class="sh-top-card">
            <div class="sh-details">
                <div class="sh-badge">Shoot Listed</div>
                <h1 class="sh-title">{{ $title }}</h1>
                <div class="sh-client">Client: {{ $client }}</div>

                <div class="sh-meta-row">
                    <!-- Date -->
                    <div class="sh-meta-item">
                        <div class="sh-meta-icon"><i class="far fa-calendar-alt"></i></div>
                        <div class="sh-meta-text">
                            <div>Date</div>
                            <div>{{ $dateDisplay }}</div>
                        </div>
                    </div>

                    <!-- Time -->
                    <div class="sh-meta-item">
                        <div class="sh-meta-icon"><i class="far fa-clock"></i></div>
                        <div class="sh-meta-text">
                            <div>Time</div>
                            <div>{{ $timeDisplay }}</div>
                        </div>
                    </div>

                    <!-- Location -->
                    <div class="sh-meta-item">
                        <div class="sh-meta-icon"><i class="fas fa-map-marker-alt" style="color:#94a3b8;"></i></div>
                        <div class="sh-meta-text">
                            <div>Location</div>
                            <div>{{ $location }}</div>
                        </div>
                    </div>
                </div>

                <div class="sh-actions">
                    <a href="#" class="nav-btn primary">Invite Talents</a>
                    <a href="{{ route('admin.casting-requirements.edit', $project->id) }}" class="nav-btn secondary">Edit Shoot</a>
                    <button id="shareBtn" class="nav-btn secondary" data-url="{{ route('admin.casting-requirements.show', $project->id) }}">
                        <i class="fas fa-share-alt"></i> Share
                    </button>
                </div>
            </div>

            <div class="sh-map-container">
                @if($location && $location != 'Not Set' && $location != 'Location Pending')
                <iframe
                    width="100%"
                    height="100%"
                    frameborder="0"
                    style="border:0;"
                    src="https://maps.google.com/maps?q={{ urlencode($location) }}&t=&z=13&ie=UTF8&iwloc=&output=embed"
                    allowfullscreen>
                </iframe>
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($location) }}" target="_blank" class="sh-map-btn">
                    Open Google Maps
                </a>
                @else
                <div style="display:grid; place-items:center; height:100%; background:#f1f5f9; color:#94a3b8;">
                    No Location Set
                </div>
                @endif
            </div>
        </div>

        <!-- Stepper -->
        <div class="sh-progress-card">
            <div class="sh-progress-title">Shoot Progress</div>
            <div class="sh-stepper">
                @foreach($steps as $index => $step)
                    @php
                        $isCompleted = $index < $currentStepIndex;
                        $isActive = $index == $currentStepIndex;
                        $isLast = $index == count($steps) - 1;
                    @endphp

                    <div class="sh-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                        <div class="sh-step-circle">
                            @if($isCompleted) <i class="fas fa-check"></i>
                            @elseif($isActive) <i class="fas fa-check"></i>
                            @else {{ $index + 1 }}
                            @endif
                        </div>
                        <div class="sh-step-label">{{ $step }}</div>
                    </div>

                    @if(!$isLast)
                        <div class="sh-line {{ $index < $currentStepIndex ? 'filled' : '' }}"></div>
                    @endif
                @endforeach
            </div>
        </div>

        <!-- Talent Pool -->
        <div class="sh-pool-header">
            <div class="sh-pool-title">
                <h3>Talent Pool</h3>
                <p>View and manage applicants for this shoot.</p>
            </div>
            <div class="sh-filter-group">
                <button type="button" class="sh-filter-btn" id="sh-filter-toggle">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <div class="sh-filter-menu" id="sh-filter-menu">
                    <button data-filter="all">All</button>
                    <button data-filter="applied">Applied</button>
                    <button data-filter="shortlisted">Shortlisted</button>
                    <button data-filter="selected">Selected</button>
                    <button data-filter="rejected">Rejected</button>
                    <button data-filter="did_not_show">Didn't Show</button>
                </div>
            </div>
        </div>

        @php
            $applicants = $project->castingApplications ?? collect();
            $fallbackImg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="360"><rect width="300" height="360" rx="18" fill="#e5e7eb"/><path d="M150 170c28 0 50-22 50-50s-22-50-50-50-50 22-50 50 22 50 50 50Zm0 20c-42 0-80 19-92 56-2 6 2 12 8 12h168c6 0 10-6 8-12-12-37-50-56-92-56Z" fill="#cbd5e1"/></svg>');
        @endphp

        @if($applicants->isEmpty())
            <div style="text-align: center; padding: 60px 20px; background: #f9fafb; border-radius: 12px; border: 1px dashed #e2e8f0;">
                <div style="font-size: 48px; color: #cbd5e1; margin-bottom: 16px;">
                    <i class="fas fa-users"></i>
                </div>
                <h4 style="font-size: 16px; font-weight: 600; color: #64748b; margin: 0 0 8px 0;">No Applicants Yet</h4>
                <p style="color: #94a3b8; font-size: 13px; margin: 0;">Applicants for this shoot will appear here once they apply.</p>
            </div>
        @else
            <div class="sh-talent-grid">
                @foreach($applicants as $application)
                    @php
                        $talent = $application->talent_profile;
                        if (!$talent) continue;

                        $displayName = $talent->display_name ?? $talent->legal_name ?? 'Unknown';
                        $gender = strtoupper($talent->gender ?? 'N/A');
                        $dob = optional($talent->date_of_birth);
                        $age = $dob ? $dob->age : null;
                        $ageText = $age ? "• $age YEARS" : '';
                        $joinedAt = optional($talent->created_at)->format('d M Y') ?? '--';
                        $flagCode = $talent->nationality ?? $talent->country_code ?? $talent->country ?? null;
                        $isVerified = strtolower($talent->verification_status ?? 'pending') === 'approved';

                        $avatarCandidate = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                        if (is_array($avatarCandidate)) {
                            $avatarCandidate = $avatarCandidate['url'] ?? ($avatarCandidate['path'] ?? ($avatarCandidate[0] ?? null));
                        }
                        $avatar = null;
                        if ($avatarCandidate) {
                            if (\Illuminate\Support\Str::startsWith($avatarCandidate, ['http://', 'https://', 'data:'])) {
                                $avatar = $avatarCandidate;
                            } else {
                                $normalized = ltrim($avatarCandidate, '/');
                                $storageRelative = \Illuminate\Support\Str::startsWith($normalized, 'storage/') ? substr($normalized, 8) : $normalized;
                                if (file_exists(public_path('storage/' . $storageRelative))) {
                                    $avatar = asset('storage/' . $storageRelative);
                                } elseif (file_exists(public_path($normalized))) {
                                    $avatar = asset($normalized);
                                } else {
                                    $avatar = asset('storage/' . $storageRelative);
                                }
                            }
                        }
                        $avatar = $avatar ?: $fallbackImg;

                        // Collect all images for hover effect
                        $headshotImages = [];
                        $fullBodyImages = [];

                        $normalizeImage = function($path) {
                            if (!$path) return null;
                            if (is_array($path)) {
                                $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
                            }
                            if (!$path) return null;
                            if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', 'data:'])) {
                                return $path;
                            }
                            $normalized = ltrim($path, '/');
                            $storageRelative = \Illuminate\Support\Str::startsWith($normalized, 'storage/') ? substr($normalized, 8) : $normalized;
                            if (file_exists(public_path('storage/' . $storageRelative))) {
                                return asset('storage/' . $storageRelative);
                            } elseif (file_exists(public_path($normalized))) {
                                return asset($normalized);
                            } else {
                                return asset('storage/' . $storageRelative);
                            }
                        };

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

                        $allImages = array_merge($headshotImages, $fullBodyImages);
                        if (empty($allImages)) {
                            $allImages = [$avatar];
                        }
                    @endphp
                    <div class="sh-talent-card" data-url="{{ route('admin.talent-profiles.show', $talent->id) }}" data-images='@json($allImages)' data-status="{{ strtolower($application->status ?? 'applied') }}">
                        <div class="sh-talent-img-container">
                            @foreach($allImages as $index => $imgSrc)
                                <img class="sh-talent-img {{ $index === 0 ? 'active' : '' }}" src="{{ $imgSrc }}" alt="{{ $displayName }} - Image {{ $index + 1 }}" data-index="{{ $index }}">
                            @endforeach
                        </div>
                        <span class="badge-active">{{ $isVerified ? 'Active' : 'Pending' }}</span>
                        <div class="card-overlay">
                            <div class="overlay-top">
                                <div class="overlay-flag">
                                    @if($flagCode && strlen($flagCode) === 2)
                                        <span class="fi fi-{{ strtolower($flagCode) }}" title="{{ $flagCode }}"></span>
                                    @endif
                                </div>
                                <span class="overlay-meta-info">{{ $gender }} {{ $ageText }}</span>
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
        @endif
    @else
        <!-- Empty State -->
        <div style="text-align: center; padding: 100px 20px; background: #fff; border-radius: 20px; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <div style="font-size: 60px; color: #cbd5e1; margin-bottom: 20px;">
                <i class="fas fa-calendar-times"></i>
            </div>
            <h2 style="font-size: 24px; font-weight: 700; color: var(--text-main); margin-bottom: 10px;">No Shoot Currently In Progress</h2>
            <p style="color: var(--text-sub); margin-bottom: 30px;">There are no shoots scheduled for today or in the near future.</p>
            <a href="{{ route('admin.casting-requirements.create') }}" class="nav-btn primary">Create New Shoot</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
@parent
<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterToggle = document.getElementById('sh-filter-toggle');
    const filterMenu = document.getElementById('sh-filter-menu');
    const cards = Array.from(document.querySelectorAll('.sh-talent-card'));

    if (filterToggle && filterMenu) {
        filterToggle.addEventListener('click', function(e) {
            e.stopPropagation();
            filterMenu.classList.toggle('show');
        });

        filterMenu.querySelectorAll('button[data-filter]').forEach(btn => {
            btn.addEventListener('click', function() {
                const value = this.dataset.filter;
                filterMenu.classList.remove('show');

                cards.forEach(card => {
                    const status = (card.dataset.status || '').toLowerCase();
                    const match = value === 'all' || status === value;
                    card.style.display = match ? '' : 'none';
                });
            });
        });

        document.addEventListener('click', function(e) {
            if (!filterMenu.contains(e.target) && !filterToggle.contains(e.target)) {
                filterMenu.classList.remove('show');
            }
        });
    }

    const shareBtn = document.getElementById('shareBtn');

    if (shareBtn) {
        shareBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const url = this.getAttribute('data-url');
            const fullUrl = window.location.origin + url;

            // Try to use the modern Clipboard API
            if (navigator.clipboard && window.isSecureContext) {
                navigator.clipboard.writeText(fullUrl).then(function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Link Copied!',
                        text: 'The shoot link has been copied to your clipboard.',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }).catch(function(err) {
                    // Fallback if clipboard API fails
                    fallbackCopyToClipboard(fullUrl);
                });
            } else {
                // Fallback for older browsers
                fallbackCopyToClipboard(fullUrl);
            }
        });
    }

    function fallbackCopyToClipboard(text) {
        const textArea = document.createElement('textarea');
        textArea.value = text;
        textArea.style.position = 'fixed';
        textArea.style.left = '-999999px';
        document.body.appendChild(textArea);
        textArea.select();

        try {
            document.execCommand('copy');
            Swal.fire({
                icon: 'success',
                title: 'Link Copied!',
                text: 'The shoot link has been copied to your clipboard.',
                timer: 2000,
                showConfirmButton: false
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Copy Failed',
                text: 'Could not copy link. Please try again.',
                confirmButtonColor: '#0f172a'
            });
        } finally {
            document.body.removeChild(textArea);
        }
    }

    // Talent card functionality
    // Make cards clickable
    cards.forEach(card => {
        card.addEventListener('click', function(e) {
            const url = this.dataset.url;
            if (url) {
                window.location.href = url;
            }
        });
    });

    // Image rotation on hover
    cards.forEach(card => {
        const images = card.querySelectorAll('.sh-talent-img');
        if (images.length <= 1) return; // No rotation needed if only one image

        let rotationInterval = null;
        let currentIndex = 0;

        card.addEventListener('mouseenter', function() {
            rotationInterval = setInterval(() => {
                images[currentIndex].classList.remove('active');
                currentIndex = (currentIndex + 1) % images.length;
                images[currentIndex].classList.add('active');
            }, 400);
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
});
</script>
@endsection
