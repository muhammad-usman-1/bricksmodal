@extends('layouts.talent')

@section('content')
    <style>
        /* ===== Theme ===== */
        :root {
            --rose-10: #fff9f8;
            --rose-100: #f6e6e4;
            --rose-200: #e9d3d1;
            --rose-300: #d9bebc;
            --rose-700: #8a6561;
            --text-900: #5b4a48;
            --muted: #8c7b79;
            --white: #fff;
        }

        /* ===== Layout: full-width (no centered card) ===== */
        .page-wrap {

            width: 100%;

        }

        /* ===== Header band ===== */
        .head-band {
            display: grid;
            grid-template-columns: 1fr 110px;
            gap: 16px;
            align-items: start;
            border: 1px solid #efe0df;
            border-radius: 14px;
            background: var(--rose-10);
        }

        .head-left {
            padding: 14px;
        }

        .head-eyebrow {
            display: flex;
            gap: 8px;
            align-items: center;
            color: #9a8582;
            font-weight: 800;
            font-size: 12px;
        }

        .chip {
            background: var(--rose-100);
            border: 1px solid var(--rose-200);
            border-radius: 999px;
            padding: 4px 10px;
            color: var(--rose-700);
            font-weight: 800;
            font-size: 12px;
        }

        .title {
            margin: 2px 0;
            color: var(--text-900);
            font-weight: 900;
            letter-spacing: .2px;
        }

        .sub {
            color: var(--muted);
            font-weight: 700;
            margin: 0 0 6px;
        }

        .meta {
            display: flex;
            gap: 14px;
            color: #a08885;
            font-size: 12px;
        }

        .head-right {
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
        }

        .apply-pill {
            margin:12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            background: var(--rose-700);
            color: #fff;
            padding: 8px 16px;
            font-weight: 800;
            text-decoration: none;
            white-space: nowrap;
        }

        .head-right .apply-pill {
            width: 100%;
        }

        .apply-pill:hover {
            color: #fff;
            text-decoration: none;
            opacity: .9;
        }

        .apply-pill[disabled] {
            opacity: .6;
            cursor: not-allowed
        }

        .title-row {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
        }

        /* ===== Sections ===== */
        .section {
            border: 1px solid #efe0df;
            border-radius: 14px;
            background: #fff;
            margin-top: 12px;
        }

        .section h6 {
            margin: 0;
            color: var(--text-900);
            font-weight: 900;
            padding: 12px 14px;
            border-bottom: 1px solid #f1e7e6;
        }

        .section .body {
            padding: 12px 14px;
            color: #5f4f4d;
            line-height: 1.55
        }

        .model-req-list {
            display: grid;
            gap: 12px;
        }

        .model-req-card {
            border: 1px solid #f3e3e2;
            border-radius: 12px;
            padding: 12px 14px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
        }

        .label-pills {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .label-pill {
            display: inline-flex;
            align-items: center;
            border-radius: 999px;
            padding: 2px 10px;
            background: var(--rose-100);
            color: var(--rose-700);
            font-weight: 700;
            font-size: 12px;
        }

        /* Timeline */
        .timeline {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 16px;
            flex-wrap: wrap;
        }

        .timeline-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            min-width: 80px;
            flex: 1;
        }

        .timeline-node {
            width: 34px;
            height: 34px;
            border-radius: 999px;
            border: 2px solid var(--rose-200);
            color: var(--rose-200);
            font-weight: 800;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--white);
            transition: all .2s ease;
        }

        .timeline-step.completed .timeline-node,
        .timeline-step.active .timeline-node {
            background: var(--rose-700);
            border-color: var(--rose-700);
            color: #fff;
        }

        .timeline-label {
            margin-top: 6px;
            font-size: 12px;
            font-weight: 800;
            color: var(--text-900);
        }

        .timeline-line {
            flex: 1;
            height: 2px;
            background: var(--rose-200);
        }

        .timeline-line.active {
            background: var(--rose-700);
        }

        .location-link {
            color: var(--rose-700);
            font-weight: 700;
            text-decoration: underline;
            background: none;
            border: none;
            padding: 0;
            cursor: pointer;
        }

        .location-link:hover {
            text-decoration: none;
        }
        .map-embed {
            margin-top: 10px;
            border: 1px solid #f0e3e1;
            border-radius: 12px;
            overflow: hidden;
        }

        .map-embed iframe {
            width: 100%;
            height: 300px;
            border: 0;
        }

        @media (max-width:900px) {
            .head-band {
                grid-template-columns: 1fr
            }
        }

        /* ===== Modal ===== */
        .modal-mask {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .28);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1050;
        }

        .modal-mask.show {
            display: flex
        }

        .modal-card {
            width: min(520px, 92vw);
            background: #fff;
            border: 1px solid #efe0df;
            border-radius: 14px;
            overflow: hidden
        }

        .modal-head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 14px;
            background: var(--rose-10);
            border-bottom: 1px solid #f1e7e6
        }

        .modal-head h5 {
            margin: 0;
            color: var(--text-900);
            font-weight: 900
        }

        .modal-close {
            border: none;
            background: transparent;
            font-size: 18px;
            line-height: 1;
            color: #8a6561
        }

        .modal-body {
            padding: 14px
        }

        .form-label {
            font-weight: 800;
            color: #6e5c5a
        }

        .form-control {
            border: 1px solid var(--rose-200);
            border-radius: 10px
        }

        .modal-foot {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            padding: 12px 14px;
            border-top: 1px solid #f1e7e6
        }

        .btn-rose {
            background: var(--rose-700);
            border: none;
            color: #fff;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 800
        }

        .btn-ghost {
            background: #f6f1f0;
            border: 1px solid var(--rose-200);
            color: #5b4a48;
            border-radius: 10px;
            padding: 8px 14px;
            font-weight: 800
        }

        .alert-info {
            background: #f6e6e4;
            border-color: #e9d3d1;
            color: #5b4a48
        }
    </style>

    <div class="page-wrap">
        @php
            $timezone = config('app.timezone', 'UTC');
            $shootStart = null;
            $rawShootDates = array_filter([
                $castingRequirement->shoot_date_time ?? null,
                $castingRequirement->shoot_date_display ?? null,
            ]);
            $manualFormats = [
                'd/m/Y H:i:s',
                'd/m/Y H:i',
                'd-m-Y H:i:s',
                'd-m-Y H:i',
                'Y-m-d H:i:s',
                'Y-m-d\TH:i:sP',
                'Y-m-d',
            ];

            foreach ($rawShootDates as $rawShootDate) {
                try {
                    $shootStart = \Illuminate\Support\Carbon::parse($rawShootDate, $timezone);
                } catch (\Throwable $e) {
                    foreach ($manualFormats as $format) {
                        try {
                            $shootStart = \Illuminate\Support\Carbon::createFromFormat($format, $rawShootDate, $timezone);
                            break;
                        } catch (\Throwable $inner) {
                            continue;
                        }
                    }
                }

                if ($shootStart) {
                    break;
                }
            }

            $formattedShootDate = null;
            if ($shootStart) {
                $formattedShootDate = $shootStart->copy()->timezone($timezone)->format('d M Y | h:i A');
            } elseif (!empty($castingRequirement->shoot_date_display)) {
                $formattedShootDate = $castingRequirement->shoot_date_display;
            }

            $currentStage = 1;
            if (isset($existingApplication) && $existingApplication) {
                $currentStage = 2;

                if (in_array($existingApplication->status, ['shortlisted', 'selected'])) {
                    $currentStage = 3;
                }

                if ($existingApplication->status === 'selected') {
                    $currentStage = 4;
                }

                if (in_array($existingApplication->payment_status, ['released', 'received'])) {
                    $currentStage = 5;
                }
            }

            $timelineStages = [
                ['label' => __('Advertised')],
                ['label' => __('Applied')],
                ['label' => __('Short Listed')],
                ['label' => __('Selected')],
                ['label' => __('Done')],
            ];

            foreach ($timelineStages as $index => &$stage) {
                $position = $index + 1;
                if ($position < $currentStage) {
                    $stage['state'] = 'completed';
                } elseif ($position === $currentStage) {
                    $stage['state'] = 'active';
                } else {
                    $stage['state'] = 'inactive';
                }
            }
            unset($stage);

            $locationQuery = $castingRequirement->location
                ? $castingRequirement->location . ', Kuwait'
                : null;

            $mapSrc = $locationQuery
                ? 'https://www.google.com/maps?q=' . rawurlencode($locationQuery) . '&t=&z=15&ie=UTF8&iwloc=B&output=embed'
                : null;

            $googleCalendarUrl = null;
            if ($shootStart) {
                try {
                    $shootEnd = (clone $shootStart)->addHours(2);

                    $startUtc = $shootStart->copy()->timezone('UTC')->format('Ymd\THis\Z');
                    $endUtc = $shootEnd->copy()->timezone('UTC')->format('Ymd\THis\Z');

                    $details = trim(implode("\n", array_filter([
                        $castingRequirement->description ?? ($castingRequirement->notes ?? null),
                        $castingRequirement->posted_by ? __('Posted by: :name', ['name' => $castingRequirement->posted_by]) : null,
                        request()->fullUrl(),
                    ])));

                    $query = array_filter([
                        'action' => 'TEMPLATE',
                        'text' => $castingRequirement->project_name ?? __('Project Shoot'),
                        'dates' => "{$startUtc}/{$endUtc}",
                        'details' => $details ?: null,
                        'location' => $castingRequirement->location ?? null,
                    ]);

                    $googleCalendarUrl = 'https://calendar.google.com/calendar/render?' . http_build_query($query, '', '&', PHP_QUERY_RFC3986);
                } catch (\Throwable $e) {
                    $googleCalendarUrl = null;
                }
            }
        @endphp
        <div class="mb-2">
            <a href="{{ route('talent.projects.index') }}" class="text-decoration-none" style="color:#8a6561;font-weight:800;">
                <i class="fas fa-chevron-left mr-1"></i>{{ __('Back') }}
            </a>
        </div>

        {{-- Header band (full width) --}}
        <div class="head-band">
            <div class="head-left">
                <div class="head-eyebrow">
                    <span>{{ __('Project Commercial Shoot') }}</span>
                    @php $statusText = \App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status ?? ''] ?? $castingRequirement->status; @endphp
                    @if ($statusText)
                        <span class="chip">{{ $statusText }}</span>
                    @endif
                </div>
                <div class="title-row">
                    <h2 class="title">{{ $castingRequirement->project_name }}</h2>
                    @if ($googleCalendarUrl)
                        <a
                            class="apply-pill"
                            href="{{ $googleCalendarUrl }}"
                            target="_blank"
                            rel="noopener"
                            title="{{ __('Add this shoot to your Google Calendar') }}"
                        >
                            <i class="far fa-calendar-plus mr-1"></i>{{ __('Add to Calendar') }}
                        </a>
                    @endif
                </div>
                <div class="sub">{{ __('Posted by') }} {{ $castingRequirement->posted_by ?? 'Admin' }}</div>
                <div class="meta">
                    @if ($formattedShootDate)
                        <span><i class="far fa-calendar mr-1"></i>{{ $formattedShootDate }}</span>
                    @endif
                    @if ($castingRequirement->location)
                        <span>
                            <i class="fas fa-map-marker-alt mr-1"></i>
                            @if ($locationQuery)
                                <button type="button"
                                    class="location-link"
                                    data-map-toggle="project-detail-map">
                                    {{ $castingRequirement->location }}
                                </button>
                            @else
                                {{ $castingRequirement->location }}
                            @endif
                        </span>
                    @endif
                    @if ($castingRequirement->duration)
                        <span><i class="far fa-clock mr-1"></i>{{ $castingRequirement->duration }}</span>
                    @endif
                </div>
                <div class="timeline">
                    @foreach ($timelineStages as $stage)
                        <div class="timeline-step {{ $stage['state'] }}">
                            <div class="timeline-node">{{ $loop->iteration }}</div>
                            <span class="timeline-label">{{ $stage['label'] }}</span>
                        </div>
                        @if (! $loop->last)
                            <div class="timeline-line {{ ($loop->index + 2) <= $currentStage ? 'active' : '' }}"></div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="head-right">
                @if (isset($existingApplication) && $existingApplication)
                    <button class="apply-pill" disabled>{{ __('Applied') }}</button>
                @else
                    <button id="openApplyModal" class="apply-pill">{{ __('Apply Now') }}</button>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="section">
            <h6>{{ __('Description') }}</h6>
            <div class="body">
                {{ $castingRequirement->description ?? ($castingRequirement->notes ?? __('No description provided.')) }}
            </div>
            @if ($mapSrc)
                <div class="map-embed"
                    id="project-detail-map"
                    data-map-src="{{ $mapSrc }}"
                    style="display: block;">
                    <iframe
                        src="{{ $mapSrc }}"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade">
                    </iframe>
                </div>
            @endif
        </div>

        @if($castingRequirement->modelRequirements->isNotEmpty())
            <div class="section">
                <h6>{{ __('Model Requirements') }}</h6>
                <div class="body model-req-list">
                    @foreach($castingRequirement->modelRequirements as $modelRequirement)
                        <div class="model-req-card">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <strong>{{ $modelRequirement->title ?? __('Model #:number', ['number' => $loop->iteration]) }}</strong>
                                <span class="badge badge-pill badge-secondary">{{ $modelRequirement->quantity }} {{ __('slots') }}</span>
                            </div>
                            <ul class="list-unstyled small text-muted mb-2">
                                <li><strong>{{ __('Rate') }}:</strong> {{ $modelRequirement->rate ? '$' . number_format($modelRequirement->rate, 2) : __('Not specified') }}</li>
                                <li><strong>{{ __('Gender') }}:</strong> {{ \App\Models\CastingRequirement::GENDER_SELECT[$modelRequirement->gender] ?? __('Any') }}</li>
                                <li><strong>{{ __('Age Range') }}:</strong>
                                    @php $rangeOption = \App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS[$modelRequirement->age_range_key] ?? null; @endphp
                                    {{ $rangeOption['label'] ?? __('Any age') }}
                                </li>
                                <li><strong>{{ __('Hair') }}:</strong> {{ $modelRequirement->hair_color ?: __('Any') }}</li>
                            </ul>
                            @if($modelRequirement->labels->isNotEmpty())
                                <div class="label-pills">
                                    @foreach($modelRequirement->labels as $label)
                                        <span class="label-pill">{{ $label->name }}</span>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-muted small">{{ __('No specific labels required') }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Outfit & Nails --}}
        @if (!empty($castingRequirement->outfit) || !empty($castingRequirement->nails))
            <div class="section">
                <h6>{{ __('Outfit & Nails') }}</h6>
                <div class="body">
                    @if(!empty($castingRequirement->outfit))
                        @php
                            $selectedOutfits = $castingRequirement->getSelectedOutfits();
                        @endphp
                        @if($selectedOutfits->isNotEmpty())
                            <strong>{{ __('Required Outfits:') }}</strong><br>
                            <ul class="mb-2">
                                @foreach($selectedOutfits as $outfit)
                                    <li>{{ $outfit->name }}</li>
                                @endforeach
                            </ul>
                        @endif
                    @endif
                    @if(!empty($castingRequirement->nails))
                        {!! nl2br(e($castingRequirement->nails)) !!}
                    @endif
                </div>
            </div>
        @endif

        {{-- Usage Rights --}}
        @if (!empty($castingRequirement->usage_rights))
            <div class="section">
                <h6>{{ __('Usage Rights') }}</h6>
                <div class="body">{{ $castingRequirement->usage_rights }}</div>
            </div>
        @endif

        {{-- Notes --}}
        @if (!empty($castingRequirement->extra_notes))
            <div class="section">
                <h6>{{ __('Notes') }}</h6>
                <div class="body">{{ $castingRequirement->extra_notes }}</div>
            </div>
        @endif
    </div>

    {{-- ===== Apply Modal ===== --}}
    @if (!isset($existingApplication) || !$existingApplication)
        <div id="applyModal" class="modal-mask" aria-hidden="true">
            <div class="modal-card" role="dialog" aria-modal="true" aria-labelledby="applyTitle">
                <div class="modal-head">
                    <h5 id="applyTitle">{{ __('Apply to this project') }}</h5>
                    <button type="button" class="modal-close" aria-label="{{ __('Close') }}" data-close>×</button>
                </div>
                <div class="modal-body">
                    @if ($errors->has('rate') || $errors->has('talent_notes'))
                        <div class="alert alert-info mb-2">{{ __('Please fix the errors below.') }}</div>
                    @endif
                    <form id="applyForm" method="POST" action="{{ route('talent.projects.apply', $castingRequirement) }}">
                        @csrf
                        <div class="form-group">
                            <label class="form-label"
                                for="rate">{{ trans('cruds.castingApplication.fields.rate') }}</label>
                            <input type="number" step="0.01" name="rate" id="rate"
                                class="form-control @error('rate') is-invalid @enderror" value="{{ old('rate') }}">
                            @error('rate')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <label class="form-label"
                                for="talent_notes">{{ trans('cruds.castingApplication.fields.talent_notes') }}</label>
                            <textarea name="talent_notes" id="talent_notes" rows="4"
                                class="form-control @error('talent_notes') is-invalid @enderror">{{ old('talent_notes') }}</textarea>
                            @error('talent_notes')
                                <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-foot">
                    <button class="btn-ghost" type="button" data-close>{{ __('Cancel') }}</button>
                    <button class="btn-rose" type="submit" form="applyForm">{{ __('Submit Application') }}</button>
                </div>
            </div>
        </div>
    @endif

    <script>
        (function() {
            const modal = document.getElementById('applyModal');
            const openBtn = document.getElementById('openApplyModal');
            const closers = document.querySelectorAll('[data-close]');

            function open() {
                if (modal) {
                    modal.classList.add('show');
                    modal.setAttribute('aria-hidden', 'false');
                }
            }

            function close() {
                if (modal) {
                    modal.classList.remove('show');
                    modal.setAttribute('aria-hidden', 'true');
                }
            }

            if (openBtn) openBtn.addEventListener('click', open);
            closers.forEach(b => b.addEventListener('click', close));
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) close();
                });
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') close();
                });
            }

            // Re-open modal if validation failed
            @if ($errors->has('rate') || $errors->has('talent_notes'))
                open();
            @endif
        })();

        document.addEventListener('click', function (event) {
            const toggle = event.target.closest('[data-map-toggle]')
            if (!toggle) {
                return
            }
            const targetId = toggle.getAttribute('data-map-toggle')
            const map = document.getElementById(targetId)
            if (!map) {
                return
            }
            const iframe = map.querySelector('iframe')
            if (iframe && !iframe.getAttribute('src')) {
                const mapSrc = map.getAttribute('data-map-src')
                if (mapSrc) {
                    iframe.setAttribute('src', mapSrc)
                }
            }
            map.style.display = map.style.display === 'block' ? 'none' : 'block'
        })
    </script>
@endsection
