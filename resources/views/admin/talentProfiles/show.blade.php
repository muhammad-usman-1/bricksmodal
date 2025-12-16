@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e6e7eb;
        --shadow: 0 12px 32px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .talent-shell { padding: 8px 0 22px; }
    .top-actions { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .back-link { color: var(--ink-700); font-size: 13px; text-decoration: none; display: inline-flex; align-items: center; gap: 8px; }
    .edit-btn { background: #0f1524; color: #fff; border: none; border-radius: 8px; padding: 8px 14px; font-size: 13px; text-decoration: none; box-shadow: 0 10px 20px rgba(0,0,0,0.12); }

    .tabs { display: flex; gap: 14px; align-items: center; margin-bottom: 14px; border-bottom: 1px solid var(--border); padding-bottom: 8px; }
    .tab-link { font-size: 13px; color: var(--ink-700); padding: 6px 0; text-decoration: none; position: relative; cursor: pointer; }
    .tab-link.active { color: var(--ink-900); font-weight: 700; }
    .tab-link.active::after { content: ''; position: absolute; left: 0; right: 0; bottom: -9px; height: 2px; background: #0f1524; }

    .section-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow); padding: 14px; margin-bottom: 14px; }
    .section-title { font-weight: 600; color: var(--ink-900); font-size: 14px; margin-bottom: 12px; }

    .upload-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; }
    .upload-tile { background: #f9fafb; border: 1px dashed #cbd5e1; border-radius: 12px; height: 220px; display: grid; place-items: center; color: var(--ink-500); text-align: center; padding: 12px; position: relative; overflow: hidden; }
    .upload-tile img { width: 100%; height: 100%; object-fit: cover; border-radius: 12px; }
    .upload-placeholder { display: grid; place-items: center; gap: 8px; }
    .upload-placeholder i { font-size: 22px; color: #9ca3af; }
    .upload-support { font-size: 10px; color: #9ca3af; }

    .info-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .info-table { width: 100%; font-size: 12px; color: var(--ink-700); }
    .info-table td { padding: 6px 0; }
    .info-table td:first-child { color: var(--ink-500); width: 46%; }
    .info-table td:last-child { color: var(--ink-700); }
    .info-table .not-set { color: #9ca3af; }

    .action-bar { margin-top: 12px; display: flex; justify-content: flex-end; gap: 10px; }
    .btn-reject { background: #f6f7fb; color: #b91c1c; border: 1px solid #f4c7c7; border-radius: 6px; padding: 8px 12px; font-size: 12px; }
    .btn-approve { background: #10B981; color: #fff; border: none; border-radius: 6px; padding: 8px 14px; font-size: 12px; }

    .tab-panel { display: none; }
    .tab-panel.active { display: block; }
    .reviews-wrap { margin: 12px 0 18px; }
    .reviews-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 12px 28px rgba(15,23,42,0.08); padding: 14px 16px; }
    .overview-card { display: flex; align-items: center; gap: 10px; background: #f9fafb; border: 1px solid #edf0f3; border-radius: 12px; padding: 12px 14px; margin-bottom: 12px; }
    .overview-icon { width: 36px; height: 36px; border-radius: 12px; background: #0f1724; display: grid; place-items: center; color: #fff; }
    .overview-title { margin: 0; font-weight: 700; color: #0f172a; font-size: 14px; }
    .overview-sub { margin: 0; color: #6b7280; font-size: 12px; }
    .review-list { display: flex; flex-direction: column; gap: 10px; }
    .review-item { border: 1px solid #edf0f3; border-radius: 12px; padding: 12px 14px; background: #fff; display: flex; justify-content: space-between; align-items: center; gap: 12px; }
    .review-text { display: flex; flex-direction: column; gap: 6px; }
    .review-title { margin: 0; color: #0f172a; font-weight: 700; font-size: 14px; }
    .star-row { display: flex; gap: 2px; }
    .star { color: #d1d5db; font-size: 14px; }
    .star.filled { color: #f59e0b; }
    .review-meta { display: flex; align-items: center; gap: 10px; color: #6b7280; font-size: 12px; }
    .status-dot { width: 8px; height: 8px; border-radius: 50%; display: inline-block; margin-right: 6px; }
    .status-pill { display: inline-flex; align-items: center; gap: 6px; padding: 2px 10px; border-radius: 999px; font-size: 12px; font-weight: 600; }
    .status-reviewed { background: #ecfdf3; color: #15803d; }
    .status-reviewed .status-dot { background: #16a34a; }
    .status-pending { background: #fff7ed; color: #f97316; }
    .status-pending .status-dot { background: #fb923c; }
    .review-action { display: inline-flex; align-items: center; justify-content: center; border: 1px solid #d1d5db; background: #fff; border-radius: 8px; padding: 8px 14px; font-size: 12px; color: #0f172a; text-decoration: none; min-width: 96px; }
    .review-action:hover { text-decoration: none; background: #f3f4f6; }

    /* Shoot & billing */
    .shoots-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 24px rgba(15,23,42,0.06); padding: 14px 16px; margin-bottom: 16px; }
    .shoot-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
    .shoot-title { display: flex; align-items: center; gap: 10px; color: #0f172a; font-weight: 700; font-size: 14px; margin: 0; }
    .shoot-title i { width: 32px; height: 32px; border-radius: 10px; display: grid; place-items: center; background: #0f1724; color: #fff; }
    .shoot-sub { color: #6b7280; font-size: 12px; margin: 0; }
    .count-box { text-align: right; }
    .count-label { color: #9ca3af; font-size: 11px; margin: 0; }
    .count-value { color: #0f172a; font-weight: 700; font-size: 16px; margin: 0; }
    .shoot-list { display: flex; flex-direction: column; gap: 10px; }
    .shoot-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 12px; display: grid; grid-template-columns: 1fr auto; gap: 10px; background: #fff; }
    .shoot-main { display: flex; flex-direction: column; gap: 6px; }
    .shoot-top { display: flex; align-items: center; gap: 8px; }
    .shoot-name { margin: 0; color: #0f172a; font-weight: 700; font-size: 14px; }
    .pill { display: inline-flex; align-items: center; gap: 6px; padding: 2px 10px; border-radius: 999px; font-size: 11px; font-weight: 600; }
    .pill-success { background: #ecfdf3; color: #15803d; }
    .pill-warning { background: #fff7ed; color: #f97316; }
    .pill-muted { background: #f3f4f6; color: #6b7280; }
    .shoot-meta { display: flex; flex-wrap: wrap; gap: 10px; color: #6b7280; font-size: 12px; }
    .meta-dot { width: 4px; height: 4px; border-radius: 50%; background: #d1d5db; display: inline-block; }
    .shoot-role { color: #6b7280; font-size: 12px; }
    .shoot-rating { display: inline-flex; align-items: center; gap: 4px; color: #f59e0b; font-size: 12px; }
    .shoot-amount { text-align: right; display: flex; flex-direction: column; gap: 6px; justify-content: center; }
    .amount-value { margin: 0; color: #0f172a; font-weight: 700; font-size: 14px; }
    .amount-role { margin: 0; color: #6b7280; font-size: 12px; text-align: right; }

    .billing-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 14px; box-shadow: 0 10px 24px rgba(15,23,42,0.06); padding: 14px 16px; }
    .billing-header { display: flex; justify-content: space-between; align-items: center; gap: 10px; margin-bottom: 10px; }
    .billing-title { display: flex; align-items: center; gap: 8px; margin: 0; color: #0f172a; font-weight: 700; font-size: 14px; }
    .billing-title i { width: 28px; height: 28px; border-radius: 8px; display: grid; place-items: center; background: #0f1724; color: #fff; font-size: 12px; }
    .badge-blue { background: #e0e7ff; color: #1d4ed8; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 600; }
    .billing-total { text-align: right; }
    .billing-label { color: #9ca3af; font-size: 11px; margin: 0; }
    .billing-value { color: #0f172a; font-weight: 700; font-size: 16px; margin: 0; }
    .billing-list { display: flex; flex-direction: column; gap: 8px; }
    .billing-item { border: 1px solid #e5e7eb; border-radius: 12px; padding: 10px 12px; display: grid; grid-template-columns: 1fr auto; gap: 8px; background: #fff; }
    .billing-main { display: flex; flex-direction: column; gap: 4px; }
    .billing-name { margin: 0; color: #0f172a; font-weight: 700; font-size: 13px; }
    .billing-meta { color: #6b7280; font-size: 12px; display: flex; gap: 8px; align-items: center; flex-wrap: wrap; }
    .badge-paid { background: #ecfdf3; color: #15803d; border-radius: 999px; padding: 2px 8px; font-size: 11px; font-weight: 600; }
    .billing-amount { text-align: right; color: #0f172a; font-weight: 700; font-size: 14px; align-self: center; }

    @media (max-width: 640px) {
        .top-actions { flex-direction: column; align-items: flex-start; gap: 8px; }
        .tabs { flex-wrap: wrap; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $notSet = trans('global.not_set');

    $headshots = [
        'headshot_center_path' => 'Headshot (Center)',
        'headshot_left_path'   => 'Headshot (Left)',
        'headshot_right_path'  => 'Headshot (Right)',
    ];

    $fullBody = [
        'full_body_front_path' => 'Full Body (Front)',
        'full_body_right_path' => 'Full Body (Right)',
        'full_body_back_path'  => 'Full Body (Back)',
    ];

    $idDocs = [
        'id_front_path' => 'ID Front',
        'id_back_path'  => 'ID Back',
    ];

    $profileInfo = [
        'Legal name'    => $talentProfile->legal_name ?? $notSet,
        'Display name'  => $talentProfile->display_name ?? $notSet,
        'First name'    => $talentProfile->first_name ?? $notSet,
        'Last name'     => $talentProfile->last_name ?? $notSet,
        'Nationality'   => $talentProfile->nationality ?? $notSet,
        'Date of birth' => optional($talentProfile->date_of_birth)->format('Y-m-d') ?? $notSet,
        'Languages'     => $talentProfile->languages->pluck('title')->filter()->implode(', ') ?: $notSet,
        'Labels'        => $talentProfile->labels->pluck('name')->filter()->implode(', ') ?: $notSet,
        'Bio'           => $talentProfile->bio ?? $notSet,
    ];

    $accountInfo = [
        'WhatsApp number'      => $talentProfile->whatsapp_number ?? $notSet,
        'Country code'         => $talentProfile->country_code ?? $notSet,
        'Mobile number'        => $talentProfile->mobile_number ?? $notSet,
        'Daily rate'           => $talentProfile->daily_rate ?? $notSet,
        'Hourly rate'          => $talentProfile->hourly_rate ?? $notSet,
        'Verification status'  => \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talentProfile->verification_status] ?? $notSet,
        'Verification notes'   => $talentProfile->verification_notes ?? $notSet,
        'Card holder name'     => $talentProfile->card_holder_name ?? $notSet,
        'Card on file'         => $talentProfile->getMaskedCardNumber() ?? $notSet,
    ];

    $measurements = [
        'Height'    => $talentProfile->height ?? $notSet,
        'Weight'    => $talentProfile->weight ?? $notSet,
        'Chest'     => $talentProfile->chest ?? $notSet,
        'Waist'     => $talentProfile->waist ?? $notSet,
        'Hips'      => $talentProfile->hips ?? $notSet,
        'Shoe size' => $talentProfile->shoe_size ?? $notSet,
    ];

    $appearance = [
        'Skin tone'        => \App\Models\TalentProfile::SKIN_TONE_SELECT[$talentProfile->skin_tone] ?? $notSet,
        'Hair color'       => $talentProfile->hair_color ?? $notSet,
        'Eye color'        => $talentProfile->eye_color ?? $notSet,
        'Hijab preference' => $talentProfile->hijab_preference ?? $notSet,
        'Visible tattoos'  => is_null($talentProfile->has_visible_tattoos) ? $notSet : ($talentProfile->has_visible_tattoos ? 'Yes' : 'No'),
        'Piercings'        => is_null($talentProfile->has_piercings) ? $notSet : ($talentProfile->has_piercings ? 'Yes' : 'No'),
    ];

    $shoots = $reviews;
    $totalShoots = $shoots->count();

    $statusPills = [
        'selected'   => ['label' => 'Completed', 'class' => 'pill-success'],
        'approved'   => ['label' => 'Completed', 'class' => 'pill-success'],
        'received'   => ['label' => 'Paid', 'class' => 'pill-success'],
        'released'   => ['label' => 'Paid', 'class' => 'pill-success'],
        'requested'  => ['label' => 'Requested', 'class' => 'pill-warning'],
        'pending'    => ['label' => 'Pending', 'class' => 'pill-warning'],
        'applied'    => ['label' => 'Applied', 'class' => 'pill-muted'],
        'shortlisted'=> ['label' => 'Shortlisted', 'class' => 'pill-muted'],
        'rejected'   => ['label' => 'Rejected', 'class' => 'pill-muted'],
    ];

    $billingEntries = $shoots->filter(function ($application) {
        return in_array($application->payment_status, ['released', 'received', 'paid', 'approved']);
    });

    $totalPaid = $billingEntries->sum(function ($application) {
        return $application->getPaymentAmount();
    });
@endphp

<div class="talent-shell container-fluid">
    <div class="top-actions">
        <a class="back-link" href="{{ route('admin.talents.dashboard') }}"><i class="fas fa-arrow-left"></i> Back to list</a>
        <a class="edit-btn" href="{{ route('admin.talent-profiles.edit', $talentProfile) }}">Edit profile</a>
    </div>

    <div class="tabs">
        <a class="tab-link active" data-tab="profile">Profile</a>
        <a class="tab-link" data-tab="reviews">Reviews & Feedback</a>
        <a class="tab-link" data-tab="shoots">Shoot History</a>
    </div>

    <div id="tab-profile" class="tab-panel active">
        <div class="section-card">
            <div class="section-title">Headshots</div>
            <div class="upload-grid">
                @foreach($headshots as $field => $label)
                    @php $img = $talentProfile->{$field} ?: null; @endphp
                    <div class="upload-tile">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}">
                        @else
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">Full-Body Shots</div>
            <div class="upload-grid">
                @foreach($fullBody as $field => $label)
                    @php $img = $talentProfile->{$field} ?: null; @endphp
                    <div class="upload-tile">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}">
                        @else
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="section-card">
            <div class="section-title">ID Documents</div>
            <div class="upload-grid">
                @foreach($idDocs as $field => $label)
                    @php $img = $talentProfile->{$field} ?: null; @endphp
                    <div class="upload-tile">
                        @if($img)
                            <img src="{{ $img }}" alt="{{ $label }}">
                        @else
                            <div class="upload-placeholder">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div style="font-size:12px;">Drop files here to upload</div>
                                <div class="upload-support">Supports .jpg, .png, .pdf up to 10MB</div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <div class="info-grid">
            <div class="section-card">
                <div class="section-title">Profile information</div>
                <table class="info-table">
                    @foreach($profileInfo as $key => $val)
                        <tr>
                            <td>{{ $key }}</td>
                            <td class="{{ $val === $notSet ? 'not-set' : '' }}">{{ $val }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="section-card">
                <div class="section-title">Account information</div>
                <table class="info-table">
                    @foreach($accountInfo as $key => $val)
                        <tr>
                            <td>{{ $key }}</td>
                            <td class="{{ $val === $notSet ? 'not-set' : '' }}">{{ $val }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="section-card">
                <div class="section-title">Measurements</div>
                <table class="info-table">
                    @foreach($measurements as $key => $val)
                        <tr>
                            <td>{{ $key }}</td>
                            <td class="{{ $val === $notSet ? 'not-set' : '' }}">{{ $val }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
            <div class="section-card">
                <div class="section-title">Appearance details</div>
                <table class="info-table">
                    @foreach($appearance as $key => $val)
                        <tr>
                            <td>{{ $key }}</td>
                            <td class="{{ $val === $notSet ? 'not-set' : '' }}">{{ $val }}</td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

        <div class="action-bar">
            <form action="{{ route('admin.talent-profiles.reject', $talentProfile) }}" method="POST" style="margin:0;">
                @csrf
                <button type="submit" class="btn-reject">Reject</button>
            </form>
            @if(($talentProfile->verification_status ?? '') !== 'approved')
                <form action="{{ route('admin.talent-profiles.approve', $talentProfile) }}" method="POST" style="margin:0;">
                    @csrf
                    <button type="submit" class="btn-approve">Accept</button>
                </form>
            @endif
        </div>
    </div>

    <div id="tab-reviews" class="tab-panel">
        <div class="reviews-wrap">
            <div class="reviews-card">
                <div class="overview-card">
                    <div class="overview-icon"><i class="fas fa-chart-line"></i></div>
                    <div>
                        <p class="overview-title">Performance Overview</p>
                        <p class="overview-sub">Overall ratings based on the talent key performance criteria</p>
                    </div>
                </div>

                <div class="review-list">
                    @forelse($reviews as $application)
                        @php
                            $project = optional($application->casting_requirement)->project_name ?? 'Untitled Project';
                            $client = optional($application->casting_requirement)->client_name ?? 'Client';
                            $date = optional($application->created_at)->format('Y-m-d') ?? '';
                            $rating = (int) ($application->rating ?? 0);
                            $isReviewed = $rating > 0;
                            $statusLabel = $isReviewed ? 'Reviewed' : 'Pending Review';
                            $actionLabel = $isReviewed ? 'View Review' : 'Add Review';
                            $statusClass = $isReviewed ? 'status-reviewed' : 'status-pending';
                        @endphp
                        <div class="review-item">
                            <div class="review-text">
                                <p class="review-title">{{ $project }}</p>
                                <div class="star-row">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="fas fa-star star {{ $i <= $rating ? 'filled' : '' }}"></i>
                                    @endfor
                                </div>
                                <div class="review-meta">
                                    <span>{{ $client }}</span>
                                    @if($date)
                                        <span>&bull;</span>
                                        <span>{{ $date }}</span>
                                    @endif
                                    <span class="status-pill {{ $statusClass }}"><span class="status-dot"></span>{{ $statusLabel }}</span>
                                </div>
                            </div>
                            <a class="review-action" href="{{ route('admin.casting-applications.show', $application) }}">{{ $actionLabel }}</a>
                        </div>
                    @empty
                        <div class="review-item" style="justify-content:center; text-align:center;">
                            <div class="review-text">
                                <p class="review-title" style="text-align:center;">No reviews found</p>
                                <div class="overview-sub">Once reviews are added, they will appear here.</div>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div id="tab-shoots" class="tab-panel">
        <div class="shoots-card">
            <div class="shoot-header">
                <div>
                    <p class="shoot-title"><i class="fas fa-clipboard-list"></i>Shoot History</p>
                    <p class="shoot-sub">Previous shoots this talent has participated in</p>
                </div>
                <div class="count-box">
                    <p class="count-label">Total Shoots</p>
                    <p class="count-value">{{ $totalShoots }}</p>
                </div>
            </div>

            <div class="shoot-list">
                @forelse($shoots as $application)
                    @php
                        $req = optional($application->casting_requirement);
                        $project = $req->project_name ?? 'Untitled Project';
                        $client = $req->client_name ?? 'Client';
                        $amount = $application->getPaymentAmount();
                        $role = $req->role ?? ($application->status === 'selected' ? 'Lead Model' : 'Model');
                        $statusKey = $application->payment_status ?? $application->status ?? '';
                        $pill = $statusPills[$statusKey] ?? ['label' => 'In Progress', 'class' => 'pill-muted'];
                        $date = optional($application->created_at)->format('M d, Y') ?? '';
                        $projectNumber = $req->id ? '#'.$req->id : '#'.$application->id;
                        $ratingValue = $application->rating ?? null;
                    @endphp
                    <div class="shoot-item">
                        <div class="shoot-main">
                            <div class="shoot-top">
                                <p class="shoot-name">{{ $project }}</p>
                                <span class="pill {{ $pill['class'] }}">{{ $pill['label'] }}</span>
                            </div>
                            <div class="shoot-meta">
                                @if($client)
                                    <span>{{ $client }}</span>
                                @endif
                                @if($date)
                                    <span class="meta-dot"></span>
                                    <span>{{ $date }}</span>
                                @endif
                                <span class="meta-dot"></span>
                                <span>{{ $projectNumber }}</span>
                                @if(!is_null($ratingValue))
                                    <span class="meta-dot"></span>
                                    <span class="shoot-rating"><i class="fas fa-star"></i>{{ number_format($ratingValue, 1) }}</span>
                                @endif
                            </div>
                            <p class="shoot-role">{{ $role }}</p>
                        </div>
                        <div class="shoot-amount">
                            <p class="amount-value">${{ number_format($amount, 0) }}</p>
                            @if($ratingValue === null)
                                <p class="amount-role">&nbsp;</p>
                            @else
                                <p class="amount-role">{{ $pill['label'] }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="shoot-item" style="grid-template-columns: 1fr; text-align:center;">
                        <p class="shoot-name" style="margin:0;">No shoot history available yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div class="billing-card">
            <div class="billing-header">
                <div>
                    <p class="billing-title"><i class="fas fa-dollar-sign"></i>Billing History <span class="badge-blue">Admin Only</span></p>
                    <p class="shoot-sub">Payment records for previous shoots</p>
                </div>
                <div class="billing-total">
                    <p class="billing-label">Total Paid</p>
                    <p class="billing-value">${{ number_format($totalPaid, 0) }}</p>
                </div>
            </div>

            <div class="billing-list">
                @forelse($billingEntries as $application)
                    @php
                        $req = optional($application->casting_requirement);
                        $project = $req->project_name ?? 'Untitled Project';
                        $date = optional($application->payment_released_at ?? $application->payment_received_at ?? $application->created_at)->format('M d, Y') ?? '';
                        $method = 'Bank Transfer';
                        $amount = $application->getPaymentAmount();
                        $status = $application->payment_status ?? 'paid';
                        $statusLabel = $status === 'received' || $status === 'released' ? 'Paid' : ucfirst($status);
                    @endphp
                    <div class="billing-item">
                        <div class="billing-main">
                            <p class="billing-name">{{ $project }} - Payment</p>
                            <div class="billing-meta">
                                @if($date)
                                    <span>{{ $date }}</span>
                                @endif
                                <span>{{ $method }}</span>
                                <span class="badge-paid">{{ $statusLabel }}</span>
                            </div>
                        </div>
                        <div class="billing-amount">${{ number_format($amount, 0) }}</div>
                    </div>
                @empty
                    <div class="billing-item" style="grid-template-columns:1fr; text-align:center;">
                        <p class="billing-name" style="margin:0;">No billing history available.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.tab-link');
        const panels = document.querySelectorAll('.tab-panel');

        const setActive = (name) => {
            tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
            panels.forEach(p => p.classList.toggle('active', p.id === `tab-${name}`));
        };

        tabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const name = tab.dataset.tab;
                if (name) setActive(name);
            });
        });

        setActive('profile');
    });
</script>
@endsection
