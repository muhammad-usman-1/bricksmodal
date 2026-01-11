@extends('layouts.talent')

@section('content')
<style>
    .my-shoots-page {

    }

    .my-shoots-container {

        margin: 0 auto;
        
    }

    .my-shoots-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 32px;
    }

    .my-shoots-title {
        font-size: 32px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .my-shoots-subtitle {
        font-size: 15px;
        color: #6b7280;
        margin: 0;
    }

    .shoots-tabs {
        display: inline-flex;
        align-items: center;
        background: #f6f7fb;
        border-radius: 14px;
        padding: 4px;
        gap: 6px;
    }

    .shoot-tab {
        padding: 10px 18px;
        border-radius: 10px;
        background: transparent;
        color: #6b7280;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        border: none;
        transition: all 0.15s ease;
    }

    .shoot-tab.active {
        background: #ffffff;
        color: #111827;
        box-shadow: 0 4px 10px rgba(0,0,0,0.06);
    }

    .shoot-tab:hover {
        text-decoration: none;
        color: #111827;
    }

    .shoots-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .shoot-card {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        display: flex;
        align-items: center;
        gap: 20px;
        border: 1px solid #e5e7eb;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
        color: inherit;
    }

    .shoot-card:hover {
        text-decoration: none;
        color: inherit;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        border-color: #d1d5db;
    }

    .shoot-icon-container {
        width: 64px;
        height: 64px;
        background: #f3f4f6;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        overflow: hidden;
    }

    .shoot-icon-container img {
        width: 28px;
        height: 28px;
        object-fit: contain;
    }

    .shoot-details {
        flex: 1;
        min-width: 0;
    }

    .shoot-title {
        font-size: 18px;
        font-weight: 700;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .shoot-info {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        align-items: center;
    }

    .shoot-info-separator {
        color: #9ca3af;
    }

    .shoot-right {
        display: flex;
        align-items: center;
        gap: 16px;
        flex-shrink: 0;
    }

    .shoot-status-tag {
        padding: 6px 12px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        white-space: nowrap;
    }

    .shoot-status-applied {
        background: #dbeafe;
        color: #1e40af;
    }

    .shoot-status-shortlisted {
        background: #fef3c7;
        color: #92400e;
    }

    .shoot-status-selected {
        background: #d1fae5;
        color: #065f46;
    }

    .shoot-status-rejected {
        background: #fee2e2;
        color: #991b1b;
    }

    .shoot-arrow {
        color: #374151;
        font-size: 18px;
    }

    .empty-state {
        background: #fff;
        border-radius: 12px;
        padding: 48px 24px;
        text-align: center;
        color: #6b7280;
    }

    .empty-state-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin: 0 0 8px 0;
    }

    .empty-state-text {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }

    @media (max-width: 768px) {
        .my-shoots-header {
            flex-direction: column;
            gap: 16px;
        }

        .shoots-tabs {
            width: 100%;
        }

        .shoot-tab {
            flex: 1;
            text-align: center;
        }

        .shoot-card {
            flex-direction: column;
            align-items: flex-start;
        }

        .shoot-right {
            width: 100%;
            justify-content: space-between;
        }
    }
</style>

<div class="my-shoots-page">
    <div class="my-shoots-container">
        <div class="my-shoots-header">
            <div>
                <h1 class="my-shoots-title">My Shoots</h1>
                <p class="my-shoots-subtitle">Track your current casting calls and past shoots.</p>
            </div>
            <div class="shoots-tabs">
                <a href="{{ route('talent.projects.index', ['tab' => 'active']) }}"
                   class="shoot-tab {{ request('tab', 'active') === 'active' ? 'active' : '' }}">
                    Active
                </a>
                <a href="{{ route('talent.projects.index', ['tab' => 'history']) }}"
                   class="shoot-tab {{ request('tab') === 'history' ? 'active' : '' }}">
                    History
                </a>
            </div>
        </div>

        <div class="shoots-list">
            @php
                $currentTab = request('tab', 'active');
                $filteredProjects = collect();

                foreach ($projects as $project) {
                    // Filter based on project status, not application status
                    if ($currentTab === 'active') {
                        // Show only shoots that are NOT completed (advertised, processing)
                        if ($project->status !== 'completed') {
                            $filteredProjects->push($project);
                        }
                    } else {
                        // Show only completed shoots
                        if ($project->status === 'completed') {
                            $filteredProjects->push($project);
                        }
                    }
                }
            @endphp

            @forelse($filteredProjects as $project)
                @php
                    $application = $applicationsByProject->get($project->id);
                    $applicationStatus = $application->status ?? null;
                    $applicationStatusLabel = $applicationStatus ? (\App\Models\CastingApplication::STATUS_SELECT[$applicationStatus] ?? ucfirst($applicationStatus)) : 'Not Applied';

                    // Format shoot date
                    $shootDate = null;
                    if ($project->shoot_date_display) {
                        $shootDate = $project->shoot_date_display;
                    } elseif ($project->shoot_date) {
                        try {
                            $shootDate = \Carbon\Carbon::parse($project->shoot_date)->format('F d, Y');
                        } catch (\Exception $e) {
                            $shootDate = $project->shoot_date;
                        }
                    }

                    // Format application date
                    $applicationDate = null;
                    if ($application && $application->created_at) {
                        $applicationDate = $application->created_at->format('F d, Y');
                    }

                    $location = $project->location ?? null;
                @endphp

                <a href="{{ route('talent.projects.show', $project) }}" class="shoot-card">
                    <div class="shoot-icon-container">
                        <img src="{{ asset('images/camera.png') }}" alt="Shoot">
                    </div>
                    <div class="shoot-details">
                        <h3 class="shoot-title">{{ $project->project_name }}</h3>
                        <p class="shoot-info">
                            @if($shootDate)
                                <span>{{ $shootDate }}</span>
                            @endif
                            @if($location)
                                @if($shootDate)
                                    <span class="shoot-info-separator">•</span>
                                @endif
                                <span>{{ $location }}</span>
                            @endif
                            @if($applicationDate)
                                @if($shootDate || $location)
                                    <span class="shoot-info-separator">•</span>
                                @endif
                                <span>Applied: {{ $applicationDate }}</span>
                            @endif
                        </p>
                    </div>
                    <div class="shoot-right">
                        @if($applicationStatus)
                            <span class="shoot-status-tag shoot-status-{{ $applicationStatus }}">
                                {{ $applicationStatusLabel }}
                            </span>
                        @else
                            <span class="shoot-status-tag shoot-status-applied" style="background: #e5e7eb; color: #6b7280;">
                                Not Applied
                            </span>
                        @endif
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <h3 class="empty-state-title">No shoots found</h3>
                    <p class="empty-state-text">
                        @if($currentTab === 'active')
                            You don't have any active shoots at the moment.
                        @else
                            You don't have any past shoots in your history.
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        @if($filteredProjects->count() > 0 && method_exists($projects, 'links'))
            <div class="d-flex justify-content-start mt-4">
                {{ $projects->onEachSide(1)->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
