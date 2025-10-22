@extends('talent.onboarding.layout', ['progress' => $progress ?? null])

@section('onboarding-content')
<form method="POST" action="{{ route('talent.onboarding.store', 'id-documents') }}" enctype="multipart/form-data">
    @csrf
    <p class="text-muted">{{ trans('global.onboarding_id_instructions') }}</p>

    <div class="form-group">
        <label for="id_front">{{ trans('global.id_front_upload') }}</label>
        <input type="file" class="form-control-file @error('id_front') is-invalid @enderror" id="id_front" name="id_front" accept="image/*" required>
        @error('id_front')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <label for="id_back">{{ trans('global.id_back_upload') }}</label>
        <input type="file" class="form-control-file @error('id_back') is-invalid @enderror" id="id_back" name="id_back" accept="image/*" required>
        @error('id_back')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="btn btn-link">{{ trans('global.back') }}</a>
        <button type="submit" class="btn btn-primary">{{ trans('global.next_step') }}</button>
    </div>
</form>
@endsection
