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
            gap: 10px;
        }

        .avatar {
            width: 72px;
            height: 72px;
            border-radius: 12px;
            border: 1px solid var(--rose-200);
            background: #f3e7e6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .apply-pill {
            width: 100%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: none;
            border-radius: 999px;
            background: var(--rose-700);
            color: #fff;
            padding: 8px 12px;
            font-weight: 800;
            text-decoration: none;
        }

        .apply-pill[disabled] {
            opacity: .6;
            cursor: not-allowed
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

        /* Requirements chips */
        .req-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 10px
        }

        .req-item {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 8px;
            background: var(--rose-100);
            border: 1px solid var(--rose-200);
            border-radius: 10px;
            font-weight: 800;
            color: var(--text-900)
        }

        @media (max-width:900px) {
            .head-band {
                grid-template-columns: 1fr
            }

            .req-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr))
            }
        }

        @media (max-width:540px) {
            .req-grid {
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
                <h2 class="title">{{ $castingRequirement->project_name }}</h2>
                <div class="sub">{{ __('Posted by') }} {{ $castingRequirement->posted_by ?? 'Admin' }}</div>
                <div class="meta">
                    @if ($castingRequirement->shoot_date_time)
                        <span><i class="far fa-calendar mr-1"></i>{{ $castingRequirement->shoot_date_time }}</span>
                    @endif
                    @if ($castingRequirement->location)
                        <span><i class="fas fa-map-marker-alt mr-1"></i>{{ $castingRequirement->location }}</span>
                    @endif
                </div>
            </div>
            <div class="head-right">
                @php
                    $avatar =
                        $castingRequirement->avatar_url ??
                        'data:image/svg+xml;utf8,' .
                            rawurlencode(
                                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96"><rect width="96" height="96" rx="12" fill="#eddcda"/><circle cx="48" cy="36" r="16" fill="#c8adab"/><rect x="20" y="58" width="56" height="22" rx="11" fill="#d8c1bf"/></svg>',
                            );
                @endphp
                <div class="avatar"><img src="{{ $avatar }}" alt="avatar"></div>

                @if (isset($existingApplication) && $existingApplication)
                    <button class="apply-pill" disabled>{{ __('Applied') }}</button>
                @else
                    <button id="openApplyModal" class="apply-pill">{{ __('Apply Now') }}</button>
                @endif
            </div>
        </div>

        {{-- Description --}}
        <div class="section">
            <h6>{{ __('Job Description') }}</h6>
            <div class="body">
                {{ $castingRequirement->description ?? ($castingRequirement->notes ?? __('No description provided.')) }}
            </div>
        </div>

        {{-- Requirements --}}
        @php
            $chips = [];
            if ($castingRequirement->gender) {
                $chips[] = ucfirst($castingRequirement->gender);
            }
            if ($castingRequirement->age_range) {
                $chips[] = $castingRequirement->age_range;
            }
            if ($castingRequirement->height) {
                $chips[] = $castingRequirement->height;
            }
        @endphp
        @if (count($chips))
            <div class="section">
                <h6>{{ __('Requirements') }}</h6>
                <div class="body">
                    <div class="req-grid">
                        @foreach ($chips as $c)
                            <div class="req-item">{{ $c }}</div>
                        @endforeach
                    </div>
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
    </script>
@endsection
