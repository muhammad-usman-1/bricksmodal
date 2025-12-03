@extends('layouts.admin')
@section('content')
    @include('admin.castingRequirements.partials.wizard-form', [
        'formAction' => route('admin.casting-requirements.update', $castingRequirement),
        'isEdit' => true,
        'castingRequirement' => $castingRequirement,
        'outfits' => $outfits,
        'labels' => $labels,
        'ageRanges' => $ageRanges,
    ])
@endsection

@section('scripts')
    @include('admin.castingRequirements.partials.wizard-form-assets', [
        'castingRequirement' => $castingRequirement,
        'isEdit' => true,
    ])
@endsection

