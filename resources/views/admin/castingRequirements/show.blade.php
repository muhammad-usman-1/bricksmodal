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
                    <div><strong>Location</strong><span>{{ $castingRequirement->location ?? trans('global.not_set') }}</span></div>
                    <div><strong>Shoot Date</strong><span>{{ $castingRequirement->shoot_date_time ?? trans('global.not_set') }}</span></div>
                    <div><strong>Hair Color</strong><span>{{ $castingRequirement->hair_color ?? trans('global.not_set') }}</span></div>
                    <div><strong>Age Range</strong><span>{{ $castingRequirement->age_range ?? trans('global.not_set') }}</span></div>
                    <div><strong>Gender</strong><span>{{ App\Models\CastingRequirement::GENDER_SELECT[$castingRequirement->gender] ?? trans('global.not_set') }}</span></div>
                    <div><strong>Status</strong><span>{{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? trans('global.not_set') }}</span></div>
                </div>
            </div>

            <div class="wizard-section">
                <h5>Requirements & References</h5>
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
            </div>

            <div class="wizard-section">
                <h5>Talent & Budget</h5>
                <div class="wizard-grid">
                    <div><strong>Count</strong><span>{{ $castingRequirement->count }}</span></div>
                    <div><strong>Rate per Model</strong><span>{{ $castingRequirement->rate_per_model }}</span></div>
                    <div class="full"><strong>Notes</strong><p class="mb-0">{{ $castingRequirement->notes ?? trans('global.not_set') }}</p></div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection
