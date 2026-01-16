@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --muted: #a0a3aa;
        --border: #e6e7eb;
        --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        --badge-orange: #fbe7cf;
        --badge-orange-text: #c26f13;
        --badge-green: #c9f2d8;
        --badge-green-text: #2b9a50;
        --badge-gray: #e6e7eb;
        --badge-gray-text: #4b5563;
    }

    body { background: var(--bg); }

    .shoot-detail-shell {
        background: var(--bg);
        padding: 8px 0 22px;
    }

    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .title-block h5 {
        color: #101828;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: 36px;
        margin-bottom: 0;
    }

    .title-block .sub {
        margin: 2px 0 0;
        color: var(--ink-500);
        font-size: 13px;
    }

    .back-link {
        color: var(--ink-700);
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-weight: 600;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
    }

    .back-link:hover {
        background: #f9fafb;
        color: var(--ink-900);
        text-decoration: none;
        border-color: #cbd5e1;
    }

    .detail-card {
        background: var(--card);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 20px 24px;
        border: 1px solid var(--border);
        margin-bottom: 16px;
    }

    .detail-section-title {
        color: #101828;
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .detail-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 16px;
    }

    .detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 700;
        color: var(--ink-500);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 14px;
        font-weight: 600;
        color: var(--ink-900);
    }

    .detail-value a {
        color: #2C2C2E;
        text-decoration: none;
    }

    .detail-value a:hover {
        text-decoration: underline;
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-advertised { background: var(--badge-green); color: var(--badge-green-text); }
    .status-processing { background: var(--badge-orange); color: var(--badge-orange-text); }
    .status-completed { background: var(--badge-gray); color: var(--badge-gray-text); }

    .model-card {
        background: #fff;
        border: 1px solid var(--border);
        border-left: 4px solid #2C2C2E;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 16px;
        box-shadow: 0 2px 8px rgba(15, 23, 42, 0.04);
    }

    .model-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid var(--border);
    }

    .model-title {
        font-size: 16px;
        font-weight: 700;
        color: var(--ink-900);
    }

    .model-quantity {
        background: #2C2C2E;
        color: #fff;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
    }

    .model-details-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 12px;
        margin-bottom: 14px;
    }

    .model-detail-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .model-detail-label {
        font-size: 11px;
        font-weight: 600;
        color: var(--ink-500);
        text-transform: uppercase;
    }

    .model-detail-value {
        font-size: 13px;
        font-weight: 600;
        color: var(--ink-900);
    }

    .label-badge {
        display: inline-block;
        background: #f3f4f6;
        color: var(--ink-700);
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        margin-right: 6px;
        margin-bottom: 6px;
    }

    .outfit-section {
        margin-top: 16px;
        padding-top: 16px;
        border-top: 1px solid var(--border);
    }

    .outfit-section-title {
        font-size: 13px;
        font-weight: 700;
        color: var(--ink-700);
        margin-bottom: 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .outfit-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
        gap: 12px;
    }

    .outfit-item {
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 10px;
        text-align: center;
        transition: all 0.2s ease;
    }

    .outfit-item:hover {
        border-color: #2C2C2E;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .outfit-image {
        width: 100%;
        height: 120px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 8px;
        background: #f2f4f7;
    }

    .outfit-name {
        font-size: 12px;
        font-weight: 600;
        color: var(--ink-900);
        margin-bottom: 4px;
    }

    .outfit-category {
        font-size: 11px;
        color: var(--ink-500);
        text-transform: capitalize;
    }

    .reference-photos-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .reference-photo-item {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
        border: 1px solid var(--border);
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .reference-photo-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.12);
    }

    .reference-photo-item img {
        width: 100%;
        height: 120px;
        object-fit: cover;
    }

    .reference-files-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 12px;
        margin-top: 12px;
    }

    .reference-file-item {
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 12px;
        display: flex;
        align-items: center;
        gap: 10px;
        text-decoration: none;
        color: var(--ink-900);
        transition: all 0.2s ease;
    }

    .reference-file-item:hover {
        background: #f3f4f6;
        border-color: #2C2C2E;
        text-decoration: none;
        color: var(--ink-900);
    }

    .reference-file-icon {
        width: 40px;
        height: 40px;
        background: #2C2C2E;
        color: #fff;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
    }

    .reference-file-name {
        flex: 1;
        font-size: 13px;
        font-weight: 600;
    }

    .notes-box {
        background: #f9fafb;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 14px;
        margin-top: 12px;
        font-size: 13px;
        color: var(--ink-700);
        line-height: 1.6;
    }

    .empty-state {
        text-align: center;
        padding: 24px;
        color: var(--ink-500);
        font-size: 13px;
    }

    @media (max-width: 768px) {
        .detail-grid {
            grid-template-columns: 1fr;
        }
        .model-details-grid {
            grid-template-columns: 1fr;
        }
        .outfit-grid {
            grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        }
    }
</style>

<div class="shoot-detail-shell">
    <div class="top-row">
        <div class="title-block">
            <h5>{{ $castingRequirement->project_name }}</h5>
            <div class="sub">Shoot Details & Requirements</div>
        </div>
        <a class="back-link" href="{{ route('admin.casting-requirements.index') }}">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <!-- Basic Details -->
    <div class="detail-card">
        <div class="detail-section-title">Basic Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Client / Brand</div>
                <div class="detail-value">{{ $castingRequirement->client_name ?? 'Not set' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Location</div>
                <div class="detail-value">{{ $castingRequirement->location ?? 'Not set' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Description</div>
                <div class="detail-value">{{ $castingRequirement->description ?? 'Not set' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Shoot Date & Time</div>
                <div class="detail-value">{{ $castingRequirement->shoot_date_display ?? 'Not set' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Duration</div>
                <div class="detail-value">
                    @if($castingRequirement->duration)
                        {{ preg_replace('/[^0-9]/', '', $castingRequirement->duration) }} Hours
                    @else
                        Not set
                    @endif
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Status</div>
                <div class="detail-value">
                    <span class="status-badge status-{{ $castingRequirement->status }}">
                        {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? $castingRequirement->status }}
                    </span>
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Total Talents Required</div>
                <div class="detail-value">{{ $castingRequirement->count }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Created By</div>
                <div class="detail-value">{{ $castingRequirement->user->name ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    <!-- Model Requirements -->
    @php
        $modelRequirements = $castingRequirement->modelRequirements;
    @endphp
    @if($modelRequirements->isNotEmpty())
        <div class="detail-card">
            <div class="detail-section-title">Model Requirements ({{ $modelRequirements->count() }})</div>
            @foreach($modelRequirements as $model)
                <div class="model-card">
                    <div class="model-header">
                        <div class="model-title">{{ $model->title ?? 'Model ' . $loop->iteration }}</div>
                        <div class="model-quantity">{{ $model->quantity }} {{ $model->quantity == 1 ? 'Talent' : 'Talents' }}</div>
                    </div>

                    <div class="model-details-grid">
                        <div class="model-detail-item">
                            <div class="model-detail-label">Gender</div>
                            <div class="model-detail-value">{{ App\Models\CastingRequirement::GENDER_SELECT[$model->gender] ?? 'Any' }}</div>
                        </div>
                        <div class="model-detail-item">
                            <div class="model-detail-label">Age Range</div>
                            <div class="model-detail-value">
                                @if($model->age_range_key && isset(App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]))
                                    {{ App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]['label'] }}
                                @else
                                    Any
                                @endif
                            </div>
                        </div>
                        <div class="model-detail-item">
                            <div class="model-detail-label">Hair Color</div>
                            <div class="model-detail-value">{{ $model->hair_color ?: 'Any' }}</div>
                        </div>
                        @if($model->height_range)
                        <div class="model-detail-item">
                            <div class="model-detail-label">Height Range</div>
                            <div class="model-detail-value">{{ $model->height_range }}</div>
                        </div>
                        @endif
                        @if($model->weight_range)
                        <div class="model-detail-item">
                            <div class="model-detail-label">Weight Range</div>
                            <div class="model-detail-value">{{ $model->weight_range }}</div>
                        </div>
                        @endif
                        @if($model->skin_color)
                        <div class="model-detail-item">
                            <div class="model-detail-label">Skin Color</div>
                            <div class="model-detail-value">{{ ucfirst($model->skin_color) }}</div>
                        </div>
                        @endif
                        @if($model->eye_color)
                        <div class="model-detail-item">
                            <div class="model-detail-label">Eye Color</div>
                            <div class="model-detail-value">{{ ucfirst($model->eye_color) }}</div>
                        </div>
                        @endif
                        <div class="model-detail-item">
                            <div class="model-detail-label">Rate</div>
                            <div class="model-detail-value">
                                @if(($model->rate_decision ?? 'talent_decide') === 'admin_decide')
                                    @if($model->rate !== null)
                                        ${{ number_format($model->rate, 2) }}
                                    @else
                                        Not set
                                    @endif
                                @else
                                    Talent Decide
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($model->labels->isNotEmpty())
                        <div style="margin-bottom: 14px;">
                            <div class="model-detail-label" style="margin-bottom: 8px;">Labels</div>
                            <div>
                                @foreach($model->labels as $label)
                                    <span class="label-badge">{{ $label->name }}</span>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Outfit Selection for this Model -->
                    @php
                        $outfits = [];
                        if ($model->male_traditional_id) {
                            $outfit = \App\Models\Outfit::find($model->male_traditional_id);
                            if ($outfit) $outfits[] = ['outfit' => $outfit, 'type' => 'Traditional'];
                        }
                        if ($model->male_top_id) {
                            $outfit = \App\Models\Outfit::find($model->male_top_id);
                            if ($outfit) $outfits[] = ['outfit' => $outfit, 'type' => 'Top'];
                        }
                        if ($model->male_bottom_id) {
                            $outfit = \App\Models\Outfit::find($model->male_bottom_id);
                            if ($outfit) $outfits[] = ['outfit' => $outfit, 'type' => 'Bottom'];
                        }
                        if ($model->female_top_id) {
                            $outfit = \App\Models\Outfit::find($model->female_top_id);
                            if ($outfit) $outfits[] = ['outfit' => $outfit, 'type' => 'Top'];
                        }
                        if ($model->female_bottom_id) {
                            $outfit = \App\Models\Outfit::find($model->female_bottom_id);
                            if ($outfit) $outfits[] = ['outfit' => $outfit, 'type' => 'Bottom'];
                        }
                    @endphp
                    @if(!empty($outfits))
                        <div class="outfit-section">
                            <div class="outfit-section-title">Selected Outfits</div>
                            <div class="outfit-grid">
                                @foreach($outfits as $item)
                                    <div class="outfit-item">
                                        @if($item['outfit']->image)
                                            <img src="{{ $item['outfit']->image }}" alt="{{ $item['outfit']->name }}" class="outfit-image">
                                        @else
                                            <div class="outfit-image" style="display: flex; align-items: center; justify-content: center; color: var(--ink-500); font-size: 11px;">No Image</div>
                                        @endif
                                        <div class="outfit-name">{{ $item['outfit']->name }}</div>
                                        <div class="outfit-category">{{ $item['type'] }} · {{ ucfirst($item['outfit']->category) }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Reference Photos for this Model -->
                    @php $modelRefs = $model->getMedia('reference_photo'); @endphp
                    @if($modelRefs->isNotEmpty())
                        <div class="outfit-section">
                            <div class="outfit-section-title">Reference Photos</div>
                            <div class="reference-photos-grid">
                                @foreach($modelRefs as $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank" class="reference-photo-item">
                                        <img src="{{ $media->getUrl() }}" alt="Reference Photo">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
    @endif

    <!-- Notes & References -->
    <div class="detail-card">
        <div class="detail-section-title">Shoot Brief</div>

        @if($castingRequirement->notes)
            <div>
                <div class="model-detail-label" style="margin-bottom: 8px;">Shoot Brief</div>
                <div class="notes-box">{{ $castingRequirement->notes }}</div>
            </div>
        @endif

        @php $referenceFiles = $castingRequirement->reference; @endphp
        @if($referenceFiles->isNotEmpty())
            <div style="margin-top: 20px;">
                <div class="model-detail-label" style="margin-bottom: 12px;">Reference Files</div>
                <div class="reference-files-grid">
                    @foreach($referenceFiles as $media)
                        <a href="{{ $media->getUrl() }}" target="_blank" class="reference-file-item">
                            <div class="reference-file-icon">
                                <i class="fas fa-file-image"></i>
                            </div>
                            <div class="reference-file-name">{{ $media->name }}</div>
                            <i class="fas fa-external-link-alt" style="color: var(--ink-500);"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
