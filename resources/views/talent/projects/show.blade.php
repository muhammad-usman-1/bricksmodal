@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row mb-3">
        <div class="col-lg-8">
            <a href="{{ route('talent.projects.index') }}" class="btn btn-link px-0"><i class="fas fa-chevron-left mr-1"></i> {{ trans('global.back') }}</a>
            <h2 class="mb-1">{{ $castingRequirement->project_name }}</h2>
            <p class="text-muted mb-0">{{ $castingRequirement->location ?? trans('global.not_set') }}</p>
        </div>
    </div>

    @if(session('message'))
        <div class="alert alert-success">{{ session('message') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="font-weight-semibold mb-3">{{ trans('global.details') }}</h5>
                    <dl class="row mb-0">
                        <dt class="col-md-4">{{ trans('cruds.castingRequirement.fields.location') }}</dt>
                        <dd class="col-md-8">{{ $castingRequirement->location ?? trans('global.not_set') }}</dd>

                        <dt class="col-md-4">{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</dt>
                        <dd class="col-md-8">{{ $castingRequirement->shoot_date_time ?? trans('global.not_set') }}</dd>

                        <dt class="col-md-4">{{ trans('cruds.castingRequirement.fields.notes') }}</dt>
                        <dd class="col-md-8">{{ $castingRequirement->notes ?? trans('global.not_set') }}</dd>
                    </dl>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="font-weight-semibold mb-3">{{ trans('global.apply_to_project') }}</h5>

                    @if($existingApplication)
                        <div class="alert alert-info">
                            {{ trans('global.application_already_submitted') }}
                        </div>
                        <p class="text-muted mb-0">{{ trans('global.application_status') }}: {{ App\Models\CastingApplication::STATUS_SELECT[$existingApplication->status] ?? $existingApplication->status }}</p>
                    @else
                        <form method="POST" action="{{ route('talent.projects.apply', $castingRequirement) }}" class="d-flex flex-column flex-grow-1">
                            @csrf

                            <div class="form-group">
                                <label for="rate">{{ trans('cruds.castingApplication.fields.rate') }}</label>
                                <input type="number" step="0.01" name="rate" id="rate" class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate') }}">
                                @error('rate')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="form-group flex-grow-1">
                                <label for="talent_notes">{{ trans('cruds.castingApplication.fields.talent_notes') }}</label>
                                <textarea name="talent_notes" id="talent_notes" rows="4" class="form-control @error('talent_notes') is-invalid @enderror">{{ old('talent_notes') }}</textarea>
                                @error('talent_notes')
                                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary mt-3">{{ trans('global.apply_now') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
