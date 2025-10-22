@extends('layouts.admin')
@section('styles')
@parent
<style>
    .projects-pills .nav-link {
        border-radius: 0;
        color: #6c757d;
        font-weight: 600;
        padding: .5rem 1rem;
    }
    .projects-pills .nav-link.active {
        border-bottom: 3px solid #4f46e5;
        color: #111827;
        background: transparent;
    }
    .projects-table thead th {
        text-transform: uppercase;
        font-size: .75rem;
        letter-spacing: .04em;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
    }
    .projects-table-wrapper {
        overflow: visible;
    }
    .projects-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #f3f4f6;
    }
    .chip-group .btn {
        border-radius: 999px;
        font-weight: 600;
    }
    .status-pill {
        border-radius: 999px;
        font-size: .75rem;
        text-transform: uppercase;
        padding: .25rem .75rem;
    }
</style>
@endsection
@section('content')
<div class="content">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mb-1">{{ trans('global.projects_dashboard') }}</h2>
            <p class="text-muted mb-0">{{ trans('global.projects_dashboard_subtitle') }}</p>
        </div>
        <a href="{{ route('admin.casting-requirements.create') }}" class="btn btn-primary">
            <i class="fas fa-plus mr-1"></i> {{ trans('global.projects_create_casting') }}
        </a>
    </div>

    <ul class="nav nav-pills projects-pills mb-3">
        <li class="nav-item mr-3">
            <a class="nav-link {{ $state === 'all' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['state' => 'all', 'page' => null]) }}">
                {{ trans('global.projects_tabs_all') }}
                <span class="badge badge-pill badge-light ml-2">{{ $stats['all'] }}</span>
            </a>
        </li>
        <li class="nav-item mr-3">
            <a class="nav-link {{ $state === 'open' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['state' => 'open', 'page' => null]) }}">
                {{ trans('global.projects_tabs_open') }}
                <span class="badge badge-pill badge-light ml-2">{{ $stats['open'] }}</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $state === 'closed' ? 'active' : '' }}" href="{{ request()->fullUrlWithQuery(['state' => 'closed', 'page' => null]) }}">
                {{ trans('global.projects_tabs_closed') }}
                <span class="badge badge-pill badge-light ml-2">{{ $stats['closed'] }}</span>
            </a>
        </li>
    </ul>

    <form method="GET" action="{{ route('admin.projects.dashboard') }}" class="mb-3">
        <div class="input-group">
            <input type="hidden" name="state" value="{{ $state }}">
            <div class="input-group-prepend">
                <span class="input-group-text bg-white border-right-0"><i class="fas fa-search text-muted"></i></span>
            </div>
            <input type="text" class="form-control border-left-0" placeholder="{{ trans('global.projects_search_placeholder') }}" name="search" value="{{ $search }}">
            @if($search)
                <div class="input-group-append">
                    <a class="btn btn-outline-secondary" href="{{ route('admin.projects.dashboard', ['state' => $state]) }}">{{ trans('global.clear') }}</a>
                </div>
            @endif
        </div>
    </form>

    <div class="chip-group mb-3">
        <div class="btn-group" role="group">
            <a class="btn btn-sm btn-light {{ $state === 'all' && ! $search ? 'active' : '' }}" href="{{ route('admin.projects.dashboard') }}">
                {{ trans('global.projects_primary_tag') }}
            </a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.casting-requirements.create') }}">
                {{ trans('global.projects_new_casting_request') }}
            </a>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.casting-applications.index') }}">
                {{ trans('global.projects_approvals') }}
            </a>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive projects-table-wrapper">
                <table class="table mb-0 projects-table">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.castingRequirement.fields.project_name') }}</th>
                            <th>{{ trans('global.post_date') }}</th>
                            <th>{{ trans('global.end_date') }}</th>
                            <th>{{ trans('cruds.castingRequirement.fields.location') }}</th>
                            <th>{{ trans('global.pay_range') }}</th>
                            <th>{{ trans('global.applicants') }}</th>
                            <th>{{ trans('global.status') }}</th>
                            <th class="text-right">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                            @php
                                $postDate = $project->created_at ? $project->created_at->format(config('panel.date_format')) : trans('global.not_set');
                                $rawEndDate = $project->getRawOriginal('shoot_date_time');
                                $endDate = $rawEndDate ? \Carbon\Carbon::parse($rawEndDate)->format(config('panel.date_format')) : trans('global.not_set');
                                $rate = $project->rate_per_model ? 'KWD ' . number_format($project->rate_per_model, 2) : trans('global.not_set');
                                $statusLabel = $statusDisplay[$project->status] ?? $project->status;
                                $statusClass = $statusLabel === 'open' ? 'badge-success' : ($statusLabel === 'close' ? 'badge-secondary' : 'badge-info');
                            @endphp
                            <tr>
                                <td>
                                    <strong><a href="{{ route('admin.casting-requirements.show', $project->id) }}">{{ $project->project_name }}</a></strong>
                                </td>
                                <td>{{ $postDate }}</td>
                                <td>{{ $endDate }}</td>
                                <td>{{ $project->location ?? trans('global.not_set') }}</td>
                                <td>{{ $rate }}</td>
                                <td>{{ number_format($project->casting_applications_count ?? 0) }}</td>
                                <td>
                                    <span class="badge status-pill {{ $statusClass }}">{{ $statusLabel }}</span>
                                </td>
                                <td class="text-right">
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-link text-muted dropdown-toggle" type="button" id="projectActions{{ $project->id }}" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="projectActions{{ $project->id }}">
                                            <a class="dropdown-item" href="{{ route('admin.casting-requirements.show', $project->id) }}">{{ trans('global.view') }}</a>
                                            <a class="dropdown-item" href="{{ route('admin.casting-requirements.edit', $project->id) }}">{{ trans('global.edit') }}</a>
                                            <form method="POST" action="{{ route('admin.casting-requirements.destroy', $project->id) }}" onsubmit="return confirm('{{ trans('global.areYouSure') }}');">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="dropdown-item text-danger">{{ trans('global.delete') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5 text-muted">
                                    {{ trans('global.projects_no_records') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($projects->hasPages())
            <div class="card-footer bg-white">
                {{ $projects->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
