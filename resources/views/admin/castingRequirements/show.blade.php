@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f5f7fa;
        --card: #ffffff;
        --ink-900: #0f172a;
        --ink-700: #334155;
        --ink-500: #64748b;
        --ink-400: #94a3b8;
        --border: #e2e8f0;
        --primary: #2563eb;
        --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
        --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
        --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        --badge-blue: #dbeafe;
        --badge-blue-text: #1e40af;
        --radius: 12px;
    }

    body { background: var(--bg); font-family: 'Inter', system-ui, -apple-system, sans-serif; }

    .show-container {
        
        margin: 0 auto;
        padding: 24px 10px 60px;
    }

    /* Header Styling */
    .show-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 32px;
    }

    .header-title h1 {
        font-size: 28px;
        font-weight: 800;
        color: var(--ink-900);
        margin: 0;
        letter-spacing: -0.02em;
    }

    .header-title p {
        color: var(--ink-500);
        margin: 4px 0 0;
        font-size: 15px;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: #fff;
        border: 1px solid var(--border);
        padding: 10px 18px;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 600;
        color: var(--ink-700);
        text-decoration: none;
        transition: all 0.2s ease;
        box-shadow: var(--shadow-sm);
    }

    .btn-back:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        color: var(--ink-900);
        transform: translateY(-1px);
        box-shadow: var(--shadow);
        text-decoration: none;
    }

    /* Section Styling */
    .section-card {
        background: var(--card);
        border-radius: var(--radius);
        border: 1px solid var(--border);
        box-shadow: var(--shadow);
        margin-bottom: 32px;
        overflow: hidden;
        transition: box-shadow 0.3s ease;
    }

    .section-card:hover {
        box-shadow: var(--shadow-md);
    }

    .section-header {
        padding: 20px 28px;
        border-bottom: 1px solid var(--border);
        background: #fafafa;
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .section-header i {
        color: var(--primary);
        font-size: 18px;
    }

    .section-header h2 {
        font-size: 18px;
        font-weight: 700;
        color: var(--ink-900);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.05em;
         font-family: 'Arimo', sans-serif;
    }

    .section-body {
        padding: 28px;
    }

    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 24px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .info-label {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-500);
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .info-value {
        font-size: 15px;
        font-weight: 600;
        color: var(--ink-900);
        line-height: 1.4;
    }

    .info-value.empty { color: var(--ink-400); font-weight: 400; font-style: italic; }

    .status-pill {
        display: inline-flex;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .status-advertised { background: #dcfce7; color: #166534; }
    .status-processing { background: #fef9c3; color: #854d0e; }
    .status-completed { background: #f1f5f9; color: #475569; }

    /* Model Card Specifics */
    .model-requirement-card {
        border: 1px solid var(--border);
        border-radius: 12px;
        margin-bottom: 24px;
        background: #fff;
    }

    .model-requirement-card:last-child { margin-bottom: 0; }

    .model-card-header {
        padding: 16px 24px;
       
        border-bottom: 1px dotted var(--border);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .model-index {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink-900);
    }

    .model-card-body {
        padding: 24px;
    }

    .model-tags {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }

    .tag {
        background: #f1f5f9;
        color: #475569;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
    }

    /* Outfits Grid */
    .outfits-container {
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .outfits-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
        gap: 16px;
        margin-top: 12px;
    }

    .outfit-card {
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 12px;
        text-align: center;
        background: #fafafa;
    }

    .outfit-image-wrapper {
        aspect-ratio: 3/4;
        border-radius: 8px;
        overflow: hidden;
        background: #f1f5f9;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .outfit-image-wrapper img {
        width: 100%;
        height: 100%;
        object-fit: contain;
    }

    .outfit-name {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink-900);
        margin-bottom: 2px;
    }

    .outfit-sub {
        font-size: 11px;
        color: var(--ink-500);
        text-transform: uppercase;
        font-weight: 600;
    }

    /* Reference Photos */
    .ref-photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 24px;
    }

    .ref-photo {
        aspect-ratio: 1;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: transform 0.2s ease;
    }

    .ref-photo:hover { transform: scale(1.05); }

    .ref-photo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Brief Box */
    .brief-content {
        
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 14px;
        font-size: 15px;
        line-height: 1.8;
        color: var(--ink-700);
       
    }

    @media (max-width: 992px) {
        .info-grid { grid-template-columns: repeat(2, 1fr); }
    }

    @media (max-width: 640px) {
        .info-grid { grid-template-columns: 1fr; }
        .header-title h1 { font-size: 24px; }
    }
</style>

<div class="show-container">
    <div class="show-header">
        <div class="header-title">
            <h1>{{ $castingRequirement->project_name }}</h1>
            <p>Ref: CR-{{ $castingRequirement->id }} · Created on {{ $castingRequirement->created_at->format('M d, Y') }}</p>
        </div>
        <a href="{{ route('admin.casting-requirements.index') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    {{-- SECTION 1: Basic Information --}}
    <div class="section-card">
        <div class="section-header">
             
            <h2>Section 1: Basic Information</h2>
        </div>
        <div class="section-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Shoot Title</span>
                    <span class="info-value">{{ $castingRequirement->project_name }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Location</span>
                    <span class="info-value">{{ $castingRequirement->location ?: 'Not specified' }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Shoot Date</span>
                    <span class="info-value">
                        @if($castingRequirement->getRawOriginal('shoot_date_time'))
                            {{ \Carbon\Carbon::parse($castingRequirement->getRawOriginal('shoot_date_time'))->format('l, M d, Y') }}
                        @else
                            <span class="empty">Not set</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Start Time</span>
                    <span class="info-value">
                        @if($castingRequirement->getRawOriginal('shoot_date_time'))
                            {{ \Carbon\Carbon::parse($castingRequirement->getRawOriginal('shoot_date_time'))->format('h:i A') }}
                        @else
                            <span class="empty">Not set</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Duration</span>
                    <span class="info-value">
                        @if($castingRequirement->duration)
                            {{ preg_replace('/[^0-9.]/', '', $castingRequirement->duration) }} Hours
                        @else
                            <span class="empty">Not set</span>
                        @endif
                    </span>
                </div>
                <div class="info-item">
                    <span class="info-label">Status</span>
                    <div class="info-value">
                        <span class="status-pill status-{{ $castingRequirement->status }}">
                            {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? $castingRequirement->status }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Talents Required</span>
                    <span class="info-value">{{ $castingRequirement->count }}</span>
                </div>
                <div class="info-item">
                    <span class="info-label">Created By</span>
                    <span class="info-value">{{ $castingRequirement->user->name ?? 'Admin' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- SECTION 2: Model Specifications --}}
    <div class="section-card">
        <div class="section-header">
           
            <h2>Section 2: Model Specifications</h2>
        </div>
        <div class="section-body">
            @forelse($castingRequirement->modelRequirements as $model)
                <div class="model-requirement-card">
                    <div class="model-card-header">
                        <span class="model-index">Requirement #{{ $loop->iteration }} - {{ $model->title ?: 'Model' }}</span>
                        <div class="tag" style="background: var(--ink-900); color: #fff;">{{ $model->quantity }} {{ Str::plural('Talent', $model->quantity) }}</div>
                    </div>
                    <div class="model-card-body">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Gender</span>
                                <span class="info-value">{{ ucfirst($model->gender ?: 'Any') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Age Range</span>
                                <span class="info-value">
                                    @if($model->age_range_key && isset(App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]))
                                        {{ App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]['label'] }}
                                    @else
                                        Not specified
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Hours Needed</span>
                                <span class="info-value">{{ $model->model_hours ?: '0' }} Hours</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Time Slot</span>
                                <span class="info-value">
                                    @if($model->time_slot)
                                        <span style="color: var(--primary);">{{ $model->time_slot }}</span>
                                    @else
                                        <span class="empty">Not selected</span>
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Rate</span>
                                <span class="info-value">
                                    @if(($model->rate_decision ?? 'talent_decide') === 'admin_decide')
                                        ${{ number_format($model->rate, 2) }}
                                    @else
                                        Talent Decides
                                    @endif
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Physical Stats</span>
                                <div class="info-value">
                                    @php $stats = []; @endphp
                                    @if($model->height_range) @php $stats[] = "H: " . $model->height_range . "cm"; @endphp @endif
                                    @if($model->weight_range) @php $stats[] = "W: " . $model->weight_range . "kg"; @endphp @endif
                                    @if(empty($stats))
                                        <span class="empty">Not specified</span>
                                    @else
                                        {{ implode(' · ', $stats) }}
                                    @endif
                                </div>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Complexion</span>
                                <div class="info-value">
                                    @php $comp = []; @endphp
                                    @if($model->skin_color) @php $comp[] = "Skin: " . ucfirst($model->skin_color); @endphp @endif
                                    @if($model->eye_color) @php $comp[] = "Eyes: " . ucfirst($model->eye_color); @endphp @endif
                                    @if(empty($comp))
                                        <span class="empty">Not specified</span>
                                    @else
                                        {{ implode(' · ', $comp) }}
                                    @endif
                                </div>
                            </div>
                        </div>

                        @if($model->labels->isNotEmpty())
                            <div class="model-tags">
                                @foreach($model->labels as $label)
                                    <span class="tag">#{{ $label->name }}</span>
                                @endforeach
                            </div>
                        @endif

                        {{-- Reference Photos --}}
                        @php $mediaCount = $model->getMedia('reference_photo')->count(); @endphp
                        @if($mediaCount > 0)
                            <div class="outfits-container">
                                <span class="info-label">Reference Photos ({{ $mediaCount }})</span>
                                <div class="ref-photos-grid">
                                    @foreach($model->getMedia('reference_photo') as $media)
                                        <a href="{{ $media->getUrl() }}" target="_blank" class="ref-photo">
                                            <img src="{{ $media->getUrl() }}" alt="Reference">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- Outfit Display Logic --}}
                        @php
                            $selectedOutfits = [];
                            $isTraditional = ($model->traditional_mode === 'true');

                            if ($isTraditional && $model->male_traditional_id) {
                                $o = \App\Models\Outfit::find($model->male_traditional_id);
                                if ($o) $selectedOutfits[] = ['obj' => $o, 'type' => 'Traditional'];
                            } elseif (!$isTraditional) {
                                // Male
                                if ($model->gender === 'male' || $model->gender === 'boy') {
                                    if ($model->male_top_id) {
                                        $o = \App\Models\Outfit::find($model->male_top_id);
                                        if ($o) $selectedOutfits[] = ['obj' => $o, 'type' => 'Top'];
                                    }
                                    if ($model->male_bottom_id) {
                                        $o = \App\Models\Outfit::find($model->male_bottom_id);
                                        if ($o) $selectedOutfits[] = ['obj' => $o, 'type' => 'Bottom'];
                                    }
                                }
                                // Female
                                if ($model->gender === 'female' || $model->gender === 'girl') {
                                    if ($model->female_top_id) {
                                        $o = \App\Models\Outfit::find($model->female_top_id);
                                        if ($o) $selectedOutfits[] = ['obj' => $o, 'type' => 'Top'];
                                    }
                                    if ($model->female_bottom_id) {
                                        $o = \App\Models\Outfit::find($model->female_bottom_id);
                                        if ($o) $selectedOutfits[] = ['obj' => $o, 'type' => 'Bottom'];
                                    }
                                }
                            }
                        @endphp

                        @if(!empty($selectedOutfits))
                            <div class="outfits-container">
                                <span class="info-label">Selected Outfits</span>
                                <div class="outfits-grid">
                                    @foreach($selectedOutfits as $item)
                                        <div class="outfit-card">
                                            <div class="outfit-image-wrapper">
                                                @if($item['obj']->image)
                                                    <img src="{{ $item['obj']->image }}" alt="{{ $item['obj']->name }}">
                                                @else
                                                    <i class="fas fa-image" style="font-size: 24px; color: var(--ink-400);"></i>
                                                @endif
                                            </div>
                                            <div class="outfit-name">{{ $item['obj']->name }}</div>
                                            <div class="outfit-sub">{{ $item['type'] }}</div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-5 text-muted">
                    No model requirements added.
                </div>
            @endforelse
        </div>
    </div>

    {{-- SECTION 3: Shoot Brief --}}
    <div class="section-card">
        <div class="section-header">
           
            <h2>Section 3: Shoot Brief</h2>
        </div>
        <div class="section-body">
            @if($castingRequirement->notes)
                <div class="brief-content">
                    {{ $castingRequirement->notes }}
                </div>
            @else
                <div class="text-center py-4 text-muted">
                    <i class="fas fa-pencil-alt d-block mb-2"></i>
                    No specific shoot brief provided.
                </div>
            @endif

            @php $generalRefs = $castingRequirement->getMedia('reference'); @endphp
            @if($generalRefs->isNotEmpty())
               <div style="margin-top: 32px;">
                    <span class="info-label" style="display: block; margin-bottom: 12px;">General Reference Files</span>
                    <div style="display: flex; flex-wrap: wrap; gap: 12px;">
                        @foreach($generalRefs as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="tag" style="padding: 8px 16px; border: 1px solid var(--border); background: #fff; display: flex; align-items: center; gap: 8px; text-decoration: none;">
                                <i class="fas fa-file-pdf text-danger"></i>
                                {{ $media->name }}
                                <i class="fas fa-download small text-muted"></i>
                            </a>
                        @endforeach
                    </div>
               </div>
            @endif
        </div>
    </div>
</div>
@endsection
