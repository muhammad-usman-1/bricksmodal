@extends('layouts.admin')
@section('content')
    @include('admin.castingRequirements.partials.wizard-form', [
        'formAction' => route('admin.casting-requirements.store'),
        'isEdit' => false,
        'castingRequirement' => null,
        'outfits' => $outfits,
        'labels' => $labels,
        'ageRanges' => $ageRanges,
    ])
@endsection

@section('scripts')
    @include('admin.castingRequirements.partials.wizard-form-assets', [
        'castingRequirement' => null,
        'isEdit' => false,
    ])
@endsection

