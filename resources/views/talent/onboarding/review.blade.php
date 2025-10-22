@extends('talent.onboarding.layout', ['progress' => $progress ?? null])

@section('onboarding-content')
<div class="mb-4">
    <h5>{{ trans('global.profile_information') }}</h5>
    <dl class="row">
        <dt class="col-sm-4">{{ trans('global.full_name') }}</dt>
        <dd class="col-sm-8">{{ $profile->legal_name }}</dd>

        <dt class="col-sm-4">{{ trans('global.login_email') }}</dt>
        <dd class="col-sm-8">{{ auth('talent')->user()->email }}</dd>

        <dt class="col-sm-4">{{ trans('global.date_of_birth') }}</dt>
        <dd class="col-sm-8">{{ optional($profile->date_of_birth)->format(config('panel.date_format')) }}</dd>

        <dt class="col-sm-4">{{ trans('global.gender') }}</dt>
        <dd class="col-sm-8">{{ trans('global.gender_display.' . $profile->gender) }}</dd>
    </dl>
</div>

<div class="mb-4">
    <h5>{{ trans('global.uploaded_documents') }}</h5>
    <div class="row">
        @foreach([
            'id_front_path' => trans('global.id_front'),
            'id_back_path' => trans('global.id_back'),
            'headshot_center_path' => trans('global.headshot_center'),
            'headshot_left_path' => trans('global.headshot_left'),
            'headshot_right_path' => trans('global.headshot_right'),
            'full_body_front_path' => trans('global.full_body_front'),
            'full_body_right_path' => trans('global.full_body_right'),
            'full_body_back_path' => trans('global.full_body_back'),
        ] as $field => $label)
            <div class="col-md-4 mb-3">
                <div class="border rounded p-2 text-center">
                    <small class="d-block text-muted mb-2">{{ $label }}</small>
                    @if($profile->{$field})
                        <img src="{{ $profile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                    @else
                        <span class="text-muted">{{ trans('global.not_set') }}</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<form method="POST" action="{{ route('talent.onboarding.store', 'review') }}">
    @csrf
    <div class="form-group form-check">
        <input type="checkbox" class="form-check-input @error('confirm') is-invalid @enderror" id="confirm" name="confirm" required>
        <label class="form-check-label" for="confirm">{{ trans('global.onboarding_confirm') }}</label>
        @error('confirm')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="btn btn-link">{{ trans('global.back') }}</a>
        <button type="submit" class="btn btn-success">{{ trans('global.complete_setup') }}</button>
    </div>
</form>
@endsection
