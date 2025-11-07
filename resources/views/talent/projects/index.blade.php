@extends('layouts.talent')

@section('content')
    <style>
        :root {
            --rose-10: #fff9f8;
            --rose-100: #f6e6e4;
            --rose-200: #e9d3d1;
            --rose-700: #8a6561;
            --text-900: #5b4a48;
            --muted: #8c7b79;
            --white: #fff;
        }

        /* === Layout: full-width, left aligned (no centering) === */
        .content,
        .projects-wrap {
            max-width: none !important;
            width: 100% !important;
        }

        

        /* Search: no shadow */
        .proj-search {
            width: 100%;
            border: 1px solid #e9d3d1;
            background: #fff;
            border-radius: 999px;
            padding: 10px 14px 10px 40px;
            color: var(--text-900);
            outline: none;
            box-shadow: none !important;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='18' height='18' viewBox='0 0 24 24' fill='none'%3E%3Ccircle cx='11' cy='11' r='7' stroke='%238a6561' stroke-width='1.6'/%3E%3Cpath d='M20 20l-3.2-3.2' stroke='%238a6561' stroke-width='1.6' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: 12px 50%;
        }

        .proj-search::placeholder {
            color: #b49a97
        }

        /* Cards: no shadow, clean borders, grid for stable button alignment */
        .card-job {
            border: 1px solid #eee;
            background: #fff;
            border-radius: 12px;
            padding: 14px 16px;
            margin: 14px 0;
            display: grid;
            grid-template-columns: 1fr 110px;
            gap: 16px;
            box-shadow: none !important;
        }

        .job-left {
            min-width: 0;
        }

        .job-title {
            margin: 0;
            color: var(--text-900);
            font-weight: 800;
            line-height: 1.2;
        }

        .job-sub {
            margin: 2px 0 8px;
            color: var(--muted);
            font-weight: 700;
        }

        .job-meta {
            color: #9a807d;
            font-size: 13px;
            margin-bottom: 8px;
        }

        .chips {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 8px;
        }

        .chip {
            background: #f6e6e4;
            border: 1px solid #e9d3d1;
            border-radius: 999px;
            padding: 4px 10px;
            color: var(--rose-700);
            font-weight: 800;
            font-size: 12px;
        }

        .rate {
            font-weight: 900;
            color: var(--text-900);
        }

        .rate .amount {
            font-size: 20px;
            color: #7b5a57;
        }

        .rate small {
            color: #8b7572
        }

        /* Right column: avatar on top, button under it (no shadow) */
        .job-aside {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
        }

        .avatar {
            width: 68px;
            height: 68px;
            border-radius: 10px;
            background: #f7efee;
            border: 1px solid #e9d3d1;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 10px;
            box-shadow: none !important;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .btn-pill {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            background: #8a6561;
            color: #fff;
            border-radius: 999px;
            padding: 8px 12px;
            font-weight: 800;
            box-shadow: none !important;
            text-decoration: none;
        }

        .btn-pill:hover {
            filter: brightness(.97);
        }

        /* Empty state */
        .empty {
            background: #fff;
            border-radius: 12px;
            border: 1px dashed #e9d3d1;
            padding: 18px;
            text-align: center;
            color: #8c7b79;
        }

        /* Mobile: stack nicely */
        @media (max-width:600px) {
            .card-job {
                grid-template-columns: 1fr;
            }

            .job-aside {
                flex-direction: row;
                gap: 12px;
                justify-content: flex-start;
            }

            .btn-pill {
                width: auto;
                padding: 8px 14px;
            }
        }
    </style>

    <div class="projects-wrap">
        {{-- Search --}}
        <form method="GET" action="{{ route('talent.projects.index') }}" class="mb-3">
            <input type="text" name="q" value="{{ request('q') }}" class="proj-search"
                placeholder="{{ __('Search') }}">
        </form>

        {{-- Cards --}}
        @forelse($projects as $project)
            @php
                $statusText = \App\Models\CastingRequirement::STATUS_SELECT[$project->status] ?? $project->status;
                $when = $project->shoot_date_time
                    ? \Illuminate\Support\Str::of($project->shoot_date_time)->limit(60)
                    : null;
                $dayRate = $project->daily_rate ?? ($project->rate ?? null);
                $avatar =
                    $project->avatar_url ??
                    'data:image/svg+xml;utf8,' .
                        rawurlencode(
                            '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 80 80"><rect width="80" height="80" rx="12" fill="#efe4e3"/><circle cx="40" cy="32" r="14" fill="#c8adab"/><rect x="18" y="50" width="44" height="18" rx="9" fill="#dcc5c3"/></svg>',
                        );
            @endphp

            <div class="card-job">
                <div class="job-left">
                    <h5 class="job-title">{{ $project->project_name }}</h5>
                    <div class="job-sub">{{ $project->role_name ?? ($project->sub_title ?? __('Project')) }}</div>

                    <div class="job-meta">
                        @if ($when)
                            <span>{{ $when }}</span>
                        @endif
                        @if ($project->location)
                            &nbsp;·&nbsp; <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $project->location }}</span>
                        @endif
                    </div>

                    <div class="chips">
                        @if ($statusText)
                            <span class="chip">{{ $statusText }}</span>
                        @endif
                        @if ($project->gender)
                            <span class="chip text-capitalize">{{ $project->gender }}</span>
                        @endif
                        @if ($project->duration)
                            <span class="chip">{{ $project->duration }}</span>
                        @endif
                    </div>

                    <div class="rate">
                        {{ __('Rate:') }} <span
                            class="amount">{{ $dayRate ? $dayRate : __('N/A') }}</span><small>/day</small>
                    </div>
                </div>

                <div class="job-aside">
                    <div class="avatar">
                        <img src="{{ $avatar }}" alt="project">
                    </div>
                    <a href="{{ route('talent.projects.show', $project) }}" class="btn-pill">{{ __('View Details') }}</a>
                </div>
            </div>
        @empty
            <div class="empty">{{ trans('global.no_projects_available') }}</div>
        @endforelse

        {{-- Pagination left-aligned --}}
        <div class="d-flex justify-content-start mt-3">
            {{ $projects->withQueryString()->links() }}
        </div>
    </div>
@endsection
