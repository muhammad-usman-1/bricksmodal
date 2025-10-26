@extends('talent.onboarding.layout', ['progress' => $progress ?? null])


@include('talent.onboarding.partials.photo-step', [
    'label' => trans('global.upload_full_body_right'),
    'instructions' => trans('global.photo_step_instruction'),
])
