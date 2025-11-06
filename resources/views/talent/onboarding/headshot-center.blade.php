@extends('talent.onboarding.layout', ['progress' => $progress ?? null])


    @include('talent.onboarding.partials.photo-step', [
        'label' => trans('global.upload_headshot_center'),
        'instructions' => trans('global.photo_step_instruction'),
    ])
@extends('layouts.app')
