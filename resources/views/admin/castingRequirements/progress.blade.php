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
        
        max-width: 1200px;
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
    .sh-filter-btn {
        background: #fff;
        border: 1px solid #e2e8f0;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        color: var(--text-sub);
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
    }

    /* Talent Grid */
    .sh-talent-grid {
        margin-bottom: 10px;
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 24px;
    }
    .sh-talent-card {
        background: #fff;
        border-radius: 16px;
        overflow: hidden;
        position: relative;
        aspect-ratio: 4/5;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }
    .sh-talent-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .sh-talent-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
        color: #fff;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    .sh-talent-status {
        position: absolute;
        top: 16px;
        left: 16px;
        background: rgba(255, 255, 255, 0.9);
        color: #22c55e;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .sh-talent-status::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        background: #22c55e;
        border-radius: 50%;
    }
    .sh-talent-name {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 2px;
    }
    .sh-talent-meta {
        font-size: 11px;
        opacity: 0.8;
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .sh-talent-flag {
        position: absolute;
        bottom: 60px;
        left: 20px;
        width: 24px;
        height: 16px;
    }
    .sh-view-btn {
        font-size: 11px;
        color: rgba(255,255,255,0.8);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 4px;
        position: absolute;
        right: 20px;
        bottom: 20px;
    }
    .sh-view-btn:hover { color: #fff; }

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
        $duration = $project->duration ?? '';
        
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
            <button class="sh-filter-btn"><i class="fas fa-filter"></i> Filter <i class="fas fa-chevron-down" style="font-size:10px; margin-left:4px;"></i></button>
        </div>

        <div class="sh-talent-grid">
            <!-- Mocking 3 cards for design compliance -->
            @foreach([1, 2, 3] as $i)
            <div class="sh-talent-card">
                <div style="width:100%; height:100%; background:#d1d5db; position:relative;">
                    <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400&h=500&fit=crop" class="sh-talent-img" alt="Talent">
                </div>
                <div class="sh-talent-status">Active</div>
                <div class="sh-talent-overlay">
                    <div style="margin-bottom:8px; font-size:16px;">🇦🇪</div> 
                    <div class="sh-talent-meta">Male • 32 Years</div>
                    <div class="sh-talent-name">Maxie Bogalech</div>
                    <div style="font-size:10px; opacity:0.7; margin-top:2px;">Joined 29 Nov 2025</div>
                    <a href="#" class="sh-view-btn">View details <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
                </div>
            </div>
            @endforeach
        </div>
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
@endsection
