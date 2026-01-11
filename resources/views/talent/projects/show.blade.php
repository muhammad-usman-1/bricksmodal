@extends('layouts.talent')

@section('content')
@php
    $timezone = config('app.timezone', 'UTC');
    $shootStart = null;
    $rawShootDates = array_filter([
        $castingRequirement->shoot_date_time ?? null,
        $castingRequirement->shoot_date_display ?? null,
    ]);

    foreach ($rawShootDates as $rawShootDate) {
        try {
            $shootStart = \Illuminate\Support\Carbon::parse($rawShootDate, $timezone);
            break;
        } catch (\Throwable $e) {
            continue;
        }
    }

    // Format date as "2025-06-15"
    $formattedShootDate = null;
    if ($shootStart) {
        $formattedShootDate = $shootStart->format('Y-m-d');
    } elseif ($castingRequirement->shoot_date_display) {
        try {
            $parsed = \Carbon\Carbon::parse($castingRequirement->shoot_date_display);
            $formattedShootDate = $parsed->format('Y-m-d');
        } catch (\Exception $e) {
            $formattedShootDate = $castingRequirement->shoot_date_display;
        }
    } else {
        $formattedShootDate = 'Date TBD';
    }

    $formattedShootTime = $shootStart ? $shootStart->format('H:i') : '08:00';
    $duration = $castingRequirement->duration ?? '3 hours';

    // Calculate application deadline
    $applicationDeadline = null;
    if ($shootStart) {
        $applicationDeadline = $shootStart->copy()->subDays(7)->format('Y-m-d');
    } else {
        $applicationDeadline = \Carbon\Carbon::now()->addDays(7)->format('Y-m-d');
    }

    $locationQuery = $castingRequirement->location ? $castingRequirement->location . ', Kuwait' : null;
    $mapSrc = $locationQuery ? 'https://www.google.com/maps?q=' . rawurlencode($locationQuery) . '&t=&z=15&ie=UTF8&iwloc=B&output=embed' : null;
    $googleMapsUrl = $locationQuery ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($locationQuery) : null;

    $moodboardImages = $castingRequirement->reference;
@endphp

<style>
    .project-show-page {
        background: #f3f4f6;
        min-height: 100vh;
        padding: 32px 0;
        font-family: 'Inter', sans-serif;
    }

    .project-show-container {

        margin: 0 auto;

    }

    /* Top Card */
    .top-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.03);
        margin-bottom: 32px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    @media (min-width: 768px) {
        .top-card {
            flex-direction: row;
        }
    }

    .about-shoot-panel {
        padding: 32px;
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .map-panel {
        background: #e5e7eb;
        position: relative;
        min-height: 250px;
        flex: 1;
    }

    .map-panel iframe {
        width: 100%;
        height: 100%;
        border: 0;
        position: absolute;
        top: 0;
        left: 0;
    }

    .map-overlay-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
        background: #111827;
        color: #fff;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: background 0.2s;
        z-index: 10;
    }
    .map-overlay-btn:hover {
        background: #000;
        color: #fff;
    }

    .section-pill {
        display: inline-block;
        background: #111827;
        color: #fff;
        border-radius: 20px;
        padding: 6px 14px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        margin-bottom: 20px;
        align-self: flex-start;
    }

    .project-title {
        font-size: 24px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .client-name {
        font-size: 15px;
        color: #6b7280;
        margin: 0 0 32px 0;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 32px;
    }

    @media (max-width: 640px) {
        .info-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-size: 12px;
        color: #9ca3af;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .info-value {
        font-size: 14px;
        color: #111827;
        font-weight: 600;
    }

    .deadline-banner {
        background: #fffbeb;
        border: 1px solid #fcd34d; /* Slight border for visibility */
        color: #b45309;
        padding: 10px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
        align-self: flex-start;
    }


    /* Main Layout Grid */
    .main-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 32px;
    }

    @media (min-width: 1024px) {
        .main-grid {
            grid-template-columns: 2fr 1fr;
        }
    }

    /* Left Column Sections */
    .content-section {
        margin-bottom: 32px;
    }

    .section-heading {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 20px;
    }

    /* Requirements Styling */
    .req-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 16px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .req-left {
        display: flex;
        align-items: center;
        gap: 16px;
    }

    .req-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #111827;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 14px;
        flex-shrink: 0;
    }

    .req-details h4 {
        margin: 0;
        font-size: 16px;
        font-weight: 700;
        color: #111827;
    }

    .req-details p {
        margin: 4px 0 0 0;
        font-size: 14px;
        color: #6b7280;
    }

    .rate-pill {
        padding: 6px 16px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .rate-premium {
        background: #d1fae5;
        color: #065f46;
    }
    .rate-basic {
        background: #dbeafe;
        color: #1e40af;
    }

    /* Brief & Moodboard */
    .brief-card, .moodboard-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .brief-text {
        color: #4b5563;
        line-height: 1.6;
        font-size: 15px;
    }

    .moodboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 16px;
    }

    .moodboard-img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Right Sidebar - Apply Card */
    .apply-card {
        background: #111827;
        border-radius: 16px;
        padding: 32px;
        color: #fff;
        position: sticky;
        top: 32px;
    }

    .apply-title {
        font-size: 22px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #fff;
    }

    .apply-subtitle {
        color: #9ca3af;
        font-size: 14px;
        line-height: 1.5;
        margin-bottom: 32px;
    }

    .apply-btn {
        display: block;
        width: 100%;
        background: #fff;
        color: #111827;
        text-align: center;
        padding: 14px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        text-decoration: none;
        margin-bottom: 24px;
        transition: background 0.2s;
        border: none;
        cursor: pointer;
    }
    .apply-btn:hover {
        background: #f3f4f6;
        text-decoration: none;
        color: #111827;
    }
    .apply-btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    .apply-terms {
        font-size: 12px;
        color: #6b7280;
        line-height: 1.5;
        margin-bottom: 32px;
        padding-bottom: 32px;
        border-bottom: 1px solid #374151;
    }

    .apply-meta {
        display: flex;
        justify-content: space-between;
        margin-bottom: 12px;
    }
    .meta-label {
        font-size: 14px;
        color: #9ca3af;
    }
    .meta-value {
        font-size: 14px;
        color: #fff;
        font-weight: 600;
    }

    /* Modal Styles */
    .apply-modal-overlay {
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10000;
        padding: 16px;
    }

    .apply-modal-container {
        background: #fff;
        border-radius: 12px;
        max-width: 500px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        display: flex;
        flex-direction: column;
    }

    .modal-header {
        padding: 24px;
        border-bottom: 1px solid #f3f4f6;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .modal-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 4px 0;
    }

    .modal-subtitle {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    .close-modal-btn {
        background: #f3f4f6;
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        cursor: pointer;
        transition: background 0.2s;
    }
    .close-modal-btn:hover {
        background: #e5e7eb;
        color: #374151;
    }

    .modal-body {
        padding: 24px;
        overflow-y: auto;
    }

    .section-label {
        font-size: 14px;
        font-weight: 700;
        color: #374151;
        margin-bottom: 12px;
        display: block;
    }

    /* Shoot Summary Card */
    .summary-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 24px;
    }

    .summary-project {
        font-size: 15px;
        font-weight: 700;
        color: #111827;
        margin-bottom: 4px;
    }

    .summary-client {
        font-size: 13px;
        color: #6b7280;
        margin-bottom: 16px;
    }

    .summary-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr); /* Ensure 3 columns for Date, Time, Location */
        gap: 12px;
    }

    .summary-item {
        display: flex;
        flex-direction: column; /* Icon and text */
        gap: 4px;
    }

    .summary-icon-label {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #9ca3af;
        font-weight: 500;
    }

    .summary-value {
        font-size: 13px;
        color: #111827;
        font-weight: 600;
        line-height: 1.4;
    }

    /* Form Elements */
    .input-group {
        margin-bottom: 24px;
    }

    .rate-input-wrapper {
        position: relative;
    }

    .rate-input {
        width: 100%;
        padding: 12px 40px 12px 16px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        color: #111827;
        outline: none;
        transition: border-color 0.2s;
    }
    .rate-input:focus {
        border-color: #d1d5db;
    }

    .currency-symbol {
        position: absolute;
        right: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        font-weight: 600;
    }

    /* Confirmation Box */
    .confirmation-box {
        background: #fffbeb;
        border: 1px solid #fcd34d;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 24px;
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .checkbox-item {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .checkbox-custom {
        width: 20px;
        height: 20px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        cursor: pointer;
        position: relative;
        margin-top: 2px;
    }

    .checkbox-input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
        height: 100%;
        width: 100%;
        z-index: 10;
        margin: 0;
    }

    /* Style when checked */
    .checkbox-input:checked + .checkbox-bg {
        background: #111827;
        border-color: #111827;
    }

    .checkbox-bg {
        width: 100%;
        height: 100%;
        border-radius: 2px;
        background: #fff;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .checkbox-input:checked + .checkbox-bg::after {
        content: '✓';
        color: #fff;
        font-size: 14px;
        font-weight: 700;
    }

    .checkbox-text {
        font-size: 14px;
        color: #111827;
        font-weight: 600;
        line-height: 1.4;
    }

    .checkbox-subtext {
        font-size: 12px;
        color: #6b7280;
        font-weight: 400;
        display: block;
        margin-top: 2px;
    }

    /* Message Area */
    .message-area {
        width: 100%;
        padding: 12px;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        min-height: 100px;
        font-size: 14px;
        font-family: inherit;
        resize: vertical;
        margin-bottom: 8px;
    }

    .char-count {
        text-align: right;
        font-size: 12px;
        color: #9ca3af;
    }

    /* Footer */
    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f3f4f6;
        display: flex;
        justify-content: flex-end;
        gap: 12px;
    }

    .btn-cancel {
        padding: 10px 20px;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        border-radius: 6px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
    }
    .btn-submit {
        padding: 10px 24px;
        background: #9ca3af; /* Disabled gray initially */
        color: #fff;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    /* Active submit state */
    .btn-submit.active {
        background: #111827; /* Dark active color */
    }

</style>

<div class="project-show-page">
    <div class="project-show-container">

        <!-- Top Section -->
        <div class="top-card">
            <div class="about-shoot-panel">
                <div class="section-pill">ABOUT SHOOT</div>
                <h1 class="project-title">{{ $castingRequirement->project_name }}</h1>
                <p class="client-name">Client: {{ $castingRequirement->client_name ?? 'Zara Official' }}</p>

                <div class="info-grid">
                    <div class="info-item">
                        <span class="info-label"><i class="far fa-calendar"></i> Date</span>
                        <span class="info-value">{{ $formattedShootDate }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="far fa-clock"></i> Time</span>
                        <span class="info-value">{{ $formattedShootTime }} ({{ $duration }})</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label"><i class="fas fa-map-marker-alt"></i> Location</span>
                        <span class="info-value">{{ $castingRequirement->location ?? 'Malibu Beach, CA' }}</span>
                    </div>
                </div>

                <div class="deadline-banner">
                    Application Deadline: {{ $applicationDeadline }}
                </div>
            </div>

            <div class="map-panel">
                @if($mapSrc)
                    <iframe src="{{ $mapSrc }}" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                    @if($googleMapsUrl)
                        <a href="{{ $googleMapsUrl }}" target="_blank" class="map-overlay-btn">Open Google Maps</a>
                    @endif
                @else
                    <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#9ca3af;">
                        No Location Data
                    </div>
                @endif
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-grid">

            <!-- Left Column -->
            <div class="left-col">

                <!-- Requirements -->
                <div class="content-section">
                    <h2 class="section-heading">Requirements</h2>
                    @if($castingRequirement->modelRequirements->isNotEmpty())
                        @foreach($castingRequirement->modelRequirements as $index => $modelReq)
                            @php
                                $genderLabel = \App\Models\CastingRequirement::GENDER_SELECT[$modelReq->gender] ?? 'Any';
                                $ageRange = \App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$modelReq->age_range_key] ?? null;
                                $ageLabel = $ageRange ? $ageRange['label'] : 'Any Age';
                                $rate = $modelReq->rate ?? null;
                                $isPremium = $rate && $rate > 500;
                            @endphp
                            <div class="req-card">
                                <div class="req-left">
                                    <div class="req-number">{{ $index + 1 }}</div>
                                    <div class="req-details">
                                        <h4>{{ $modelReq->title ?? $genderLabel . ' Models (' . $ageLabel . ')' }}</h4>
                                        <p>Any Skin Tone • Any Height</p>
                                    </div>
                                </div>
                                <div class="req-right">
                                    <span class="rate-pill {{ $isPremium ? 'rate-premium' : 'rate-basic' }}">
                                        {{ $isPremium ? 'PREMIUM RATE' : 'BASIC RATE' }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="req-card"><p>No specific requirements listed.</p></div>
                    @endif
                </div>

                <!-- Shoot Brief -->
                <div class="content-section">
                    <h2 class="section-heading">Shoot Brief</h2>
                    <div class="brief-card">
                        <p class="brief-text">
                            {{ $castingRequirement->description ?? 'We are looking for diverse, confident models to showcase our new summer collection in a vibrant beach setting. The shoot will capture the essence of summer freedom and style, featuring flowing fabrics, bright colors, and natural lighting. Models should be comfortable with outdoor shooting conditions and able to convey energy and joy through their poses. Previous experience with fashion photography is preferred but not required. We value authenticity and natural beauty over conventional standards.' }}
                        </p>
                    </div>
                </div>

                <!-- Moodboard -->
                @if($moodboardImages && $moodboardImages->isNotEmpty())
                <div class="content-section">
                    <h2 class="section-heading">Moodboard</h2>
                    <div class="moodboard-card">
                        <div class="moodboard-grid">
                            @foreach($moodboardImages->take(3) as $image)
                                <img src="{{ $image->getUrl() }}" alt="Moodboard" class="moodboard-img">
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

            </div>

            <!-- Right Column (Apply) -->
            <div class="right-col">
                <div class="apply-card">
                    <h3 class="apply-title">Ready to Apply?</h3>
                    <p class="apply-subtitle">Submit your profile for review by the casting team.</p>

                    @if(isset($existingApplication) && $existingApplication)
                        <button class="apply-btn" disabled>APPLIED</button>
                    @else
                        <!-- Trigger Modal -->
                        <button type="button" class="apply-btn" id="openApplyModal">APPLY NOW ></button>
                    @endif

                    <div class="apply-terms">
                        By applying, you agree to our terms and conditions. Response time is typically 2-3 business days.
                    </div>

                    <div class="apply-meta">
                        <span class="meta-label">Duration:</span>
                        <span class="meta-value">{{ $duration }}</span>
                    </div>
                    <div class="apply-meta">
                        <span class="meta-label">Apply Before:</span>
                        <span class="meta-value">{{ $applicationDeadline }}</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<!-- Modal Structure -->
@if(!isset($existingApplication) || !$existingApplication)
<div id="applyModal" class="apply-modal-overlay" style="display: none;">
    <div class="apply-modal-container">
        <!-- Header -->
        <div class="modal-header">
            <div>
                <h3 class="modal-title">Apply for Shoot</h3>
                <p class="modal-subtitle">Confirm your availability and submit your profile for review.</p>
            </div>
            <button id="closeApplyModal" class="close-modal-btn">&times;</button>
        </div>

        <form method="POST" action="{{ route('talent.projects.apply', $castingRequirement) }}" id="applyForm">
            @csrf

            <div class="modal-body">
                <!-- Shoot Summary -->
                <label class="section-label">Shoot Summary</label>
                <div class="summary-card">
                    <div class="summary-project">{{ $castingRequirement->project_name }}</div>
                    <div class="summary-client">Client: {{ $castingRequirement->client_name ?? 'Client Name' }}</div>

                    <div class="summary-grid">
                        <div class="summary-item">
                            <div class="summary-icon-label"><i class="far fa-calendar"></i> Date</div>
                            <div class="summary-value">{{ $formattedShootDate }}</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon-label"><i class="far fa-clock"></i> Time</div>
                            <div class="summary-value">{{ $formattedShootTime }} ({{ $duration }})</div>
                        </div>
                        <div class="summary-item">
                            <div class="summary-icon-label"><i class="fas fa-map-marker-alt"></i> Location</div>
                            <div class="summary-value">{{ $castingRequirement->location ?? 'Location' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Rate Input -->
                <div class="input-group" style="align-items: flex-start;">
                    <label class="section-label" style="margin-top: 10px;">Enter Your Shoot Rate (KWD)</label>
                    <div class="rate-input-wrapper" style="align-items: center;">
                        <input type="number" name="rate" class="rate-input" placeholder="Enter your rate (KWD)" step="0.01" required>

                    </div>
                </div>

                <!-- Confirmation Box -->
                <label class="section-label">Confirmation</label>
                <div class="confirmation-box">
                    <!-- Checkbox 1 -->
                    <div class="checkbox-item">
                        <div class="checkbox-custom">
                            <input type="checkbox" class="checkbox-input" required>
                            <div class="checkbox-bg"></div>
                        </div>
                        <div>
                            <span class="checkbox-text">I am available on the shoot date.</span>
                            <span class="checkbox-subtext">Confirm you can attend on {{ $formattedShootDate }}</span>
                        </div>
                    </div>

                    <!-- Checkbox 2 -->
                    <div class="checkbox-item">
                        <div class="checkbox-custom">
                            <input type="checkbox" class="checkbox-input" required>
                            <div class="checkbox-bg"></div>
                        </div>
                        <div>
                            <span class="checkbox-text">I agree to the shoot duration and location.</span>
                            <span class="checkbox-subtext">{{ $duration }} at {{ $castingRequirement->location ?? 'Location' }}</span>
                        </div>
                    </div>

                    <!-- Checkbox 3 -->
                    <div class="checkbox-item">
                        <div class="checkbox-custom">
                            <input type="checkbox" class="checkbox-input" required>
                            <div class="checkbox-bg"></div>
                        </div>
                        <div>
                            <span class="checkbox-text">I confirm my profile information is accurate.</span>
                            <span class="checkbox-subtext">Ensure your portfolio and details are up to date</span>
                        </div>
                    </div>

                    <!-- Checkbox 4 -->
                    <div class="checkbox-item">
                        <div class="checkbox-custom">
                            <input type="checkbox" class="checkbox-input" required>
                            <div class="checkbox-bg"></div>
                        </div>
                        <div>
                            <span class="checkbox-text">I confirm my profile is accurate, I have no undisclosed injuries...</span>
                            <span class="checkbox-subtext">Please read <a href="#" style="color:#2563eb;">terms and polices</a></span>
                        </div>
                    </div>
                </div>

                <!-- Message -->
                <div class="input-group" style="margin-bottom: 0;">
                    <label class="section-label">Message to Casting Team (Optional)</label>
                    <textarea name="talent_notes" class="message-area" placeholder="Optional message for the casting team" maxlength="300" id="messageBox"></textarea>
                    <div class="char-count"><span id="charCount">0</span>/300</div>
                </div>
            </div>

            <!-- Footer -->
            <div class="modal-footer">
                <button type="button" class="btn-cancel" id="cancelApply">Cancel</button>
                <button type="submit" class="btn-submit active">APPLY NOW ></button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('applyModal');
        const openBtn = document.getElementById('openApplyModal');
        const closeBtn = document.getElementById('closeApplyModal');
        const cancelBtn = document.getElementById('cancelApply');
        const messageBox = document.getElementById('messageBox');
        const charCount = document.getElementById('charCount');

        function openModal() {
            if(modal) {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            }
        }
        function closeModal() {
            if(modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        }

        if(openBtn) openBtn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal();
        });

        if(closeBtn) closeBtn.addEventListener('click', closeModal);
        if(cancelBtn) cancelBtn.addEventListener('click', closeModal);

        // Close on click outside
        if(modal) {
            modal.addEventListener('click', function(e) {
                if(e.target === modal) closeModal();
            });
        }

        // Character Counter
        if(messageBox && charCount) {
            messageBox.addEventListener('input', function() {
                charCount.textContent = this.value.length;
            });
        }

        // Close on Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal && modal.style.display === 'flex') {
                closeModal();
            }
        });
    });
</script>
@endsection



