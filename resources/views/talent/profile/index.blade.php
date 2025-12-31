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
</style>

<div class="profile-dashboard">

    <!-- Top Card: Header -->
    <div class="dash-card profile-header">
        <div class="profile-info-wrap">
            <div class="profile-avatar" style="{{ $profile->headshot_center_path ? 'background-image: url('.$profile->headshot_center_path.')' : '' }}">
                @if(!$profile->headshot_center_path)
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
        <!-- Currently keeping view-only. You may need to create a dedicated route for editing if 'index' is view-only. -->
        <a href="#" class="btn-edit-profile" onclick="alert('Edit functionality to be migrated to separate view or modal.'); return false;">
            <i class="fas fa-pen"></i> Edit
        </a>
    </div>

    <!-- Stats Row -->
    <div class="stats-grid">
        <!-- 1. Requested -->
        <div class="stat-card">
            <div class="stat-header">
                <i class="fas fa-camera"></i> Screenshots requested
            </div>
            <div class="stat-value">47</div>
            <div class="stat-trend trend-up">+4 this week</div>
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
                        <img src="{{ $profile->headshot_center_path }}" alt="Center Headshot">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Left -->
                <div class="photo-item">
                    @if($profile->headshot_left_path)
                        <img src="{{ $profile->headshot_left_path }}" alt="Left Headshot">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Right -->
                <div class="photo-item">
                    @if($profile->headshot_right_path)
                        <img src="{{ $profile->headshot_right_path }}" alt="Right Headshot">
                    @else
                         <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Full Body -->
        <div class="portfolio-section" style="margin-bottom:0;">
            <div class="portfolio-header">
                <h3>Full Body</h3>
                <a href="#" class="view-all-link">View all <i class="fas fa-chevron-right" style="font-size:10px;"></i></a>
            </div>
            <div class="photo-grid">
                 <!-- Front -->
                 <div class="photo-item">
                    @if($profile->full_body_front_path)
                        <img src="{{ $profile->full_body_front_path }}" alt="Full Body Front">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Right -->
                 <div class="photo-item">
                    @if($profile->full_body_right_path)
                        <img src="{{ $profile->full_body_right_path }}" alt="Full Body Right">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
                <!-- Back -->
                 <div class="photo-item">
                    @if($profile->full_body_back_path)
                        <img src="{{ $profile->full_body_back_path }}" alt="Full Body Back">
                    @else
                        <div style="width:100%; height:100%; display:flex; align-items:center; justify-content:center; color:#d1d5db; font-size:12px;">No Image</div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer / Rates -->
        <div class="rates-container">
            <div class="rate-block">
                <span class="rate-label">Hourly Rate</span>
                <span class="rate-amount">${{ $profile->hourly_rate ? number_format($profile->hourly_rate, 2) : '0.00' }}/hr</span>
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
