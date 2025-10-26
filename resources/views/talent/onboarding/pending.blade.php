@extends('layouts.app')

@section('styles')
    <style>
        /* ===== Soft rose / mauve theme (same as other screens) ===== */
        :root {
            --rose-10: #fff9f8;
            --rose-100: #f6e6e4;
            --rose-200: #e9d3d1;
            --rose-300: #d9bebc;
            --rose-500: #b48b87;
            --rose-700: #8a6561;
            --text-900: #5b4a48;
            --muted: #8c7b79;
            --white: #ffffff;
            --shadow: 0 12px 32px rgba(116, 84, 81, .12);
            --radius: 20px;
        }

        /* Page frame */
        .pending-wrap {
            min-height: 86vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: clamp(16px, 3vw, 28px);
            background: linear-gradient(180deg, #fff, #fff9f8);
        }

        /* Card */
        .pending-card {
            width: 100%;
            max-width: 560px;
            border: 1px solid #f0e6e5;
            border-radius: var(--radius);
            background: #fff;
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .pending-head {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            padding: 26px 24px 10px;
        }

        .pending-icon {
            width: 72px;
            height: 72px;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--rose-10);
            border: 1px solid var(--rose-200);
            box-shadow: 0 6px 16px rgba(116, 84, 81, .10);
            color: var(--rose-700);
        }

        .pending-title {
            margin: 0;
            color: var(--text-900);
            font-weight: 800;
            letter-spacing: .2px;
            font-size: clamp(20px, 2.4vw, 22px);
        }

        .pending-sub {
            color: var(--muted);
            font-weight: 600;
            margin: 0 0 6px;
            text-align: center;
        }

        .pending-body {
            padding: 8px 24px 24px;
            text-align: center;
        }

        /* Status pill */
        .pending-status {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 14px;
            border-radius: 999px;
            font-weight: 800;
            background: #f6e6e4;
            color: var(--text-900);
            border: 1px solid var(--rose-200);
        }

        .pending-dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--rose-700);
            box-shadow: 0 0 0 3px #f1d9d7 inset;
        }

        /* Notes box (optional) */
        .pending-notes {
            margin-top: 14px;
            text-align: left;
            background: #fff9f8;
            border: 1px solid var(--rose-200);
            border-radius: 14px;
            padding: 12px 14px;
        }

        .pending-notes h5 {
            color: var(--text-900);
            font-weight: 800;
            margin: 0 0 6px;
        }

        .pending-notes p {
            color: #6e5c5a;
            margin: 0;
        }

        /* Button */
        .pending-actions {
            display: flex;
            justify-content: center;
            margin-top: 18px;
        }

        .btn-refresh {
            border: none;
            background: var(--rose-700);
            color: #fff;
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 800;
            letter-spacing: .2px;
            box-shadow: var(--shadow);
        }

        .btn-refresh:hover {
            filter: brightness(.97);
        }
    </style>
@endsection

<div class="pending-wrap">
    <div class="pending-card">
        <div class="pending-head">
            <div class="pending-icon" aria-hidden="true">
                {{-- clock/check style icon --}}
                <svg width="30" height="30" viewBox="0 0 24 24" fill="none">
                    <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.8" />
                    <path d="M12 7v5l3 2" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </div>
            <h2 class="pending-title">{{ trans('global.pending_request_title') }}</h2>
            <p class="pending-sub">{{ trans('global.pending_request_body') }}</p>
        </div>

        <div class="pending-body">
            <div class="pending-status">
                <span class="pending-dot"></span>
                <span>
                    {{ trans('global.pending_request_status') }}:
                    {{ \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$profile->verification_status] ?? trans('global.pending_request_waiting') }}
                </span>
            </div>

            @if ($profile->verification_notes)
                <div class="pending-notes">
                    <h5>{{ trans('cruds.talentProfile.fields.verification_notes') }}</h5>
                    <p>{{ $profile->verification_notes }}</p>
                </div>
            @endif

            <div class="pending-actions">
                <a href="{{ route('talent.pending') }}" class="btn btn-refresh">
                    {{ trans('global.refresh_status') }}
                </a>
            </div>
        </div>
    </div>
</div>
