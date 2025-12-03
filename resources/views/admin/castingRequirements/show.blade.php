@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.castingRequirement.title') }}
    </div>

    <div class="card-body">
        <div class="wizard-view">
            <div class="wizard-header">
                <div>
                    <h2>{{ $castingRequirement->project_name }}</h2>
                    <p class="text-muted">Shoot overview</p>
                </div>
                <a class="btn btn-outline-secondary btn-sm" href="{{ route('admin.casting-requirements.index') }}">{{ trans('global.back_to_list') }}</a>
            </div>

            <div class="wizard-section">
                <h5>Basic Details</h5>
                <div class="wizard-grid">
                    <div><strong>Client / Brand</strong><span>{{ $castingRequirement->client_name ?? trans('global.not_set') }}</span></div>
                    <div><strong>Location</strong><span>{{ $castingRequirement->location ?? trans('global.not_set') }}</span></div>
                    <div><strong>Shoot Date</strong><span>{{ $castingRequirement->shoot_date_display ?? trans('global.not_set') }}</span></div>
                    <div><strong>Duration</strong><span>{{ $castingRequirement->duration ?? trans('global.not_set') }}</span></div>
                    <div><strong>Status</strong><span>{{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? trans('global.not_set') }}</span></div>
                    <div><strong>Total Talents</strong><span>{{ $castingRequirement->count }}</span></div>
                </div>
            </div>

            @php
                $modelRequirements = $castingRequirement->modelRequirements;
            @endphp
            @if($modelRequirements->isNotEmpty())
                <div class="wizard-section">
                    <h5>Model Requirements</h5>
                    <div class="wizard-model-grid">
                        @foreach($modelRequirements as $model)
                            <div class="wizard-model-card">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <strong>{{ $model->title ?? __('Model #:number', ['number' => $loop->iteration]) }}</strong>
                                    <span class="badge badge-pill badge-primary">{{ $model->quantity }} talents</span>
                                </div>
                                <ul class="list-unstyled small text-muted mb-2">
                                    <li><strong>Rate:</strong> {{ $model->rate ? '$' . number_format($model->rate, 2) : __('N/A') }}</li>
                                    <li><strong>Gender:</strong> {{ App\Models\CastingRequirement::GENDER_SELECT[$model->gender] ?? trans('global.not_set') }}</li>
                                    <li><strong>Age:</strong>
                                        @if($model->age_range_key && isset(App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]))
                                            {{ App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$model->age_range_key]['label'] }}
                                        @else
                                            {{ __('Any') }}
                                        @endif
                                    </li>
                                    <li><strong>Hair:</strong> {{ $model->hair_color ?: __('Any') }}</li>
                                </ul>
                                @if($model->labels->isNotEmpty())
                                    <div class="badge-group">
                                        @foreach($model->labels as $label)
                                            <span class="badge badge-outline-secondary">{{ $label->name }}</span>
                                        @endforeach
                                    </div>
                                @else
                                    <span class="text-muted small">{{ __('No specific labels required') }}</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <div class="wizard-section">
                <h5>Notes & References</h5>
                <div class="mb-3">
                    <strong>Outfits</strong>
                    @php $selectedOutfits = $castingRequirement->getSelectedOutfits(); @endphp
                    @if($selectedOutfits->isNotEmpty())
                        <div class="badge-group">
                            @foreach($selectedOutfits as $outfit)
                                <span class="badge badge-outline-primary">{{ $outfit->name }} · {{ ucfirst($outfit->category) }}</span>
                            @endforeach
                        </div>
                    @else
                        <span class="text-muted">{{ trans('global.not_set') }}</span>
                    @endif
                </div>
                <div>
                    <strong>Reference Files</strong>
                    <div class="reference-list">
                        @forelse($castingRequirement->reference as $media)
                            <a href="{{ $media->getUrl() }}" target="_blank" class="btn btn-sm btn-outline-info">{{ trans('global.view_file') }}</a>
                        @empty
                            <span class="text-muted">{{ trans('global.not_set') }}</span>
                        @endforelse
                    </div>
                </div>
                <div class="mt-3">
                    <strong>Notes</strong>
                    <p class="mb-0">{{ $castingRequirement->notes ?? trans('global.not_set') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
