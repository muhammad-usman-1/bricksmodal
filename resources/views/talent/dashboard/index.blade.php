@extends('layouts.talent')

@section('content')
@php
    $talent = auth('talent')->user();
    $profile = $talent?->talentProfile;

    // Calculate total earnings from selected applications
    $totalEarnings = 0;
    $shootsCompleted = 0;
    if ($profile) {
        $selectedApplications = \App\Models\CastingApplication::where('talent_profile_id', $profile->id)
            ->where('status', 'selected')
            ->get();

        $totalEarnings = $selectedApplications->sum(function($app) {
            return $app->rate_offered ?? $app->rate ?? 0;
        });

        $shootsCompleted = $selectedApplications->count();
    }

    // Profile views - placeholder (can be replaced with actual tracking later)
    $profileViews = 842; // This would come from a tracking system

    // Get user display name
    $displayName = $talent->name ?? ($profile->display_name ?? $profile->legal_name ?? 'Talent');

    // Calculate percentage changes (placeholders for now)
    $earningsChange = 12;
    $viewsChange = 24;
@endphp

<style>
    .dashboard-header {
            margin-top: 10px;
        background: black;
        border-radius: 12px;
        padding: 32px 40px;
        margin-bottom: 32px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
    }

    .dashboard-header-content h1 {
        color: #fff;
        font-size: 28px;
        font-weight: 700;
        margin: 0 0 8px 0;
        line-height: 1.2;
        font-family: 'Arimo', sans-serif;
    }

    .dashboard-header-content p {
        color: #d1d5db;
        font-size: 15px;
        margin: 0;
        line-height: 1.5;
        font-family: 'Arimo', sans-serif;
    }

    .dashboard-header-actions {
        display: flex;
        gap: 12px;
    }

    .btn-header {
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s ease;
        border: none;
        cursor: pointer;
    }

    .btn-header-dark {
        background: #374151;
        color: #fff;
    }

    .btn-header-dark:hover {
        background: #4b5563;
        color: #fff;
        text-decoration: none;
    }

    .btn-header-light {
        background: #fff;
        color: #111827;
    }

    .btn-header-light:hover {
        background: #f3f4f6;
        color: #111827;
        text-decoration: none;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
        margin-bottom: 40px;
    }

    .stat-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;

        position: relative;
        overflow: hidden;
    }

    .stat-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 16px;
    }

    .stat-card-title {
        color: #6b7280;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
         font-family: 'Arimo', sans-serif;
    }

    .stat-card-icon {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: #f3f4f6;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6b7280;
        font-size: 18px;
    }

    .stat-card-value {
        color: #111827;
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
        line-height: 1.2;
         font-family: 'Arimo', sans-serif;
    }

    .stat-card-footer {
        color: #6b7280;
        font-size: 13px;
        margin: 0;
    }

    .stat-card-change {
        color: #6b7280;
        font-size: 13px;
        margin: 0;
    }

    .stat-card-change.positive {
        color: #059669;
    }

    .casting-calls-section {
        margin-top: 40px;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .section-title {
        color: black;
        font-size: 20px;
        font-weight: 700;
        margin: 0;
         font-family: 'Arimo', sans-serif;
    }

    .section-link {
        color: #6b7280;
        font-size: 14px;
        text-decoration: none;
        transition: color 0.2s ease;
        font-weight: 400;
    }

    .section-link:hover {
        color: #111827;
        text-decoration: none;
    }

    .casting-calls-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    .casting-call-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        position: relative;
    }

    .casting-call-badge {
        position: absolute;
        top: 20px;
        right: 20px;
        background: black;
        color: #fff;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
         font-family: 'Arimo', sans-serif;
    }

    .casting-call-title {
        color: black;
        font-size: 18px;
        font-weight: 700;
        margin: 0 0 8px 0;
         font-family: 'Arimo', sans-serif;
    }

    .casting-call-company {
        color: #6b7280;
        font-size: 14px;
        margin: 0 0 16px 0;
    }

    .casting-call-meta {
        display: flex;
        flex-direction: column;
        gap: 8px;
        margin-bottom: 20px;
    }

    .casting-call-meta-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #6b7280;
        font-size: 13px;
    }

    .casting-call-meta-item i {
        width: 16px;
        text-align: center;
        color: #9ca3af;
    }

    .casting-call-button {
        width: 100%;
        background: black;
        color: #fff;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: background 0.2s ease;
        text-decoration: none;
        display: block;
        text-align: center;
         font-family: 'Arimo', sans-serif;
    }

    .casting-call-button:hover {

        color: #fff;
        text-decoration: none;
    }

    @media (max-width: 768px) {
        .dashboard-header {
            flex-direction: column;
            gap: 20px;
        }

        .dashboard-header-actions {
            width: 100%;
            flex-direction: column;
        }

        .btn-header {
            width: 100%;
        }

        .stats-grid {
            grid-template-columns: 1fr;
        }

        .casting-calls-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="content">
    <!-- Header Banner -->
    <div class="dashboard-header">
        <div class="dashboard-header-content">
            <h1>Welcome back, {{ $displayName }}!</h1>
            <p>Your profile is looking great. There are new casting calls matching your stats today.</p>
        </div>
        <div class="dashboard-header-actions">
            <a href="{{ route('talent.profile.show') }}" class="btn-header btn-header-dark">Edit Profile</a>
            <a href="" class="btn-header btn-header-light">My Applications</a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Total Earnings</p>
                <div class="stat-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <h2 class="stat-card-value">${{ number_format($totalEarnings, 2) }}</h2>
            <p class="stat-card-change positive">↑ +{{ $earningsChange }}% vs last month</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Shoots Completed</p>
                <div class="stat-card-icon">
                    <i class="fas fa-camera"></i>
                </div>
            </div>
            <h2 class="stat-card-value">{{ number_format($shootsCompleted) }}</h2>
            <p class="stat-card-footer">life time</p>
        </div>

        <div class="stat-card">
            <div class="stat-card-header">
                <p class="stat-card-title">Profile Views</p>
                <div class="stat-card-icon">
                    <i class="fas fa-eye"></i>
                </div>
            </div>
            <h2 class="stat-card-value">{{ number_format($profileViews) }}</h2>
            <p class="stat-card-change positive">↑ +{{ $viewsChange }}% vs last month</p>
        </div>
    </div>

    <!-- New Casting Calls Section -->
    <div class="casting-calls-section">
        <div class="section-header">
            <h2 class="section-title">New Casting Calls</h2>
            <a href="" class="section-link">Explore All →</a>
        </div>

        <div class="casting-calls-grid">
            @forelse($openProjects->take(2) as $project)
                @php
                    $shootDate = $project->shoot_date_display ?? ($project->shoot_date ? \Carbon\Carbon::parse($project->shoot_date)->format('F d, Y') : 'TBD');
                    $location = $project->location ?? 'Location TBD';
                    $clientName = $project->client_name ?? 'Client';
                @endphp
                <div class="casting-call-card">
                    <span class="casting-call-badge">NEW</span>
                    <h3 class="casting-call-title">{{ $project->project_name }}</h3>
                    <p class="casting-call-company">{{ $clientName }}</p>
                    <div class="casting-call-meta">
                        <div class="casting-call-meta-item">
                            <i class="far fa-calendar"></i>
                            <span>{{ $shootDate }}</span>
                        </div>
                        <div class="casting-call-meta-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $location }}</span>
                        </div>
                    </div>
                    <a href="" class="casting-call-button">View Details</a>
                </div>
            @empty
                <div class="casting-call-card">
                    <h3 class="casting-call-title">No New Casting Calls</h3>
                    <p class="casting-call-company">Check back later for new opportunities</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
