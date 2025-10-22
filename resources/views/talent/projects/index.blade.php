@php
    use Illuminate\Support\Str;
@endphp
@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="mb-1">{{ trans('global.projects_dashboard') }}</h2>
            <p class="text-muted mb-0">{{ trans('global.projects_listing_intro') }}</p>
        </div>
    </div>

    <div class="row">
        @forelse($projects as $project)
            <div class="col-md-6 col-lg-4 mb-4">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $project->project_name }}</h5>
                        <p class="text-muted mb-2">
                            <i class="fas fa-map-marker-alt mr-1"></i> {{ $project->location ?? trans('global.not_set') }}
                        </p>
                        <p class="flex-grow-1">{{ Str::limit($project->notes, 120) }}</p>
                        <div class="mt-auto d-flex justify-content-between align-items-center">
                            <span class="badge badge-pill badge-info text-capitalize">{{ App\Models\CastingRequirement::STATUS_SELECT[$project->status] ?? $project->status }}</span>
                            <a href="{{ route('talent.projects.show', $project) }}" class="btn btn-outline-primary btn-sm">{{ trans('global.view') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="alert alert-info text-center">{{ trans('global.no_projects_available') }}</div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center">
        {{ $projects->withQueryString()->links() }}
    </div>
</div>
@endsection
