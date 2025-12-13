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
    .tab-link { font-size: 13px; color: var(--ink-700); padding: 6px 0; text-decoration: none; position: relative; }
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

    @media (max-width: 640px) {
        .top-actions { flex-direction: column; align-items: flex-start; gap: 8px; }
        .tabs { flex-wrap: wrap; }
        .info-grid { grid-template-columns: 1fr; }
    }
</style>

@php
    $idDocs = [
        'id_front_path' => trans('global.id_front'),
        'id_back_path' => trans('global.id_back'),
    ];
    $headshots = [
        'headshot_center_path' => trans('global.headshot_center'),
        'headshot_left_path' => trans('global.headshot_left'),
        'headshot_right_path' => trans('global.headshot_right'),
    ];
    $fullBody = [
        'full_body_front_path' => trans('global.full_body_front'),
        'full_body_right_path' => trans('global.full_body_right'),
        'full_body_back_path' => trans('global.full_body_back'),
    ];
    $placeholderSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="260"><rect width="300" height="260" rx="14" fill="#f1f5f9" stroke="#d0d7e2" stroke-dasharray="6 6"/><path d="M150 120c20 0 36-16 36-36s-16-36-36-36-36 16-36 36 16 36 36 36Zm0 18c-30 0-58 12-68 38-1 3 1 6 4 6h128c3 0 5-3 4-6-10-26-38-38-68-38Z" fill="#cbd5e1"/></svg>');
    $profileInfo = [
        'ID' => $talentProfile->id,
        'Legal Name' => $talentProfile->legal_name ?? trans('global.not_set'),
        'Display Name' => $talentProfile->display_name ?? trans('global.not_set'),
        'Email address' => optional($talentProfile->user)->email ?? trans('global.not_set'),
        'Age' => optional($talentProfile->date_of_birth)->age ?? trans('global.not_set'),
        'Date of Birth' => optional($talentProfile->date_of_birth)->format('m/d/Y') ?? trans('global.not_set'),
        'Gender' => $talentProfile->gender ? ucfirst($talentProfile->gender) : trans('global.not_set'),
        'Languages' => $talentProfile->languages->pluck('title')->join(', ') ?: trans('global.not_set'),
    ];
    $accountInfo = [
        'Verification Status' => \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talentProfile->verification_status] ?? trans('global.not_set'),
        'Verification Notes' => $talentProfile->verification_notes ?? trans('global.not_set'),
        'Current onboarding step' => $talentProfile->onboarding_step ? str_replace('-', ' ', $talentProfile->onboarding_step) : trans('global.not_set'),
        'Onboarding completed at' => optional($talentProfile->onboarding_completed_at)->format('m/d/Y') ?? trans('global.not_set'),
        'Daily Rate' => $talentProfile->daily_rate ?? trans('global.not_set'),
        'Hourly Rate' => $talentProfile->hourly_rate ?? trans('global.not_set'),
        'User' => optional($talentProfile->user)->name ?? trans('global.not_set'),
        'Last Login' => optional(optional($talentProfile->user)->last_login_at)->diffForHumans() ?? trans('global.not_set'),
    ];
    $measurements = [
        'Height' => $talentProfile->height ?? trans('global.not_set'),
        'Weight' => $talentProfile->weight ?? trans('global.not_set'),
        'Chest' => $talentProfile->chest ?? trans('global.not_set'),
        'Waist' => $talentProfile->waist ?? trans('global.not_set'),
        'Hips' => $talentProfile->hips ?? trans('global.not_set'),
    ];
    $appearance = [
        'Skin Tone' => \App\Models\TalentProfile::SKIN_TONE_SELECT[$talentProfile->skin_tone] ?? trans('global.not_set'),
        'Hair Color' => $talentProfile->hair_color ?? trans('global.not_set'),
        'Eye Color' => $talentProfile->eye_color ?? trans('global.not_set'),
        'Shoe Size' => $talentProfile->shoe_size ?? trans('global.not_set'),
        'WhatsApp Number' => $talentProfile->whatsapp_number ? '+' . $talentProfile->whatsapp_number : trans('global.not_set'),
    ];
@endphp

<div class="talent-shell">
    <div class="top-actions">
        <a class="back-link" href="{{ route('admin.talents.dashboard') }}"><i class="fas fa-chevron-left" style="font-size:11px;"></i> Back to List</a>
        <a class="edit-btn" href="{{ route('admin.talent-profiles.edit', $talentProfile) }}">Edit</a>
    </div>

    <div class="tabs">
        <a class="tab-link active" href="#">Profile Details</a>
        <a class="tab-link" href="#">Reviews & Feedback</a>
        <a class="tab-link" href="#">Shoot History</a>
    </div>

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
                        <td class="{{ $val === trans('global.not_set') ? 'not-set' : '' }}">{{ $val }}</td>
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
                        <td class="{{ $val === trans('global.not_set') ? 'not-set' : '' }}">{{ $val }}</td>
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
                        <td class="{{ $val === trans('global.not_set') ? 'not-set' : '' }}">{{ $val }}</td>
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
                        <td class="{{ $val === trans('global.not_set') ? 'not-set' : '' }}">{{ $val }}</td>
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
@endsection
