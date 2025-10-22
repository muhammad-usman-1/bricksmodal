@extends('talent.onboarding.layout', ['progress' => $progress ?? null])

@section('onboarding-content')
    @include('talent.onboarding.partials.photo-step', [
        'label' => trans('global.upload_headshot_left'),
        'instructions' => trans('global.photo_step_instruction'),
    ])
@endsection
