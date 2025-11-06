@extends('talent.onboarding.layout', ['progress' => $progress ?? null])

<style>
    /* ===== Theme ===== */
    :root {
        --rose-10: #fff9f8;
        --rose-100: #f6e6e4;
        --rose-200: #e9d3d1;
        --rose-300: #d9bebc;
        --rose-500: #b48b87;
        --rose-700: #8a6561;
        /* scrollbar & primary */
        --text-900: #5b4a48;
        --muted: #8c7b79;
        --white: #fff;
        --shadow: 0 10px 30px rgba(116, 84, 81, .10);
        --radius: 14px;
    }

    .logo-container {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }

    .logo-container img {
        height: 50px;
        width: auto;
    }

    /* ===== Full-height layout: header + info + scrollable photos + footer ===== */
    .pr-wrap {
        max-width: 1000px;
        margin: 0 auto;
        padding: clamp(16px, 3vw, 28px);
        display: flex;
        flex-direction: column;
        height: 100vh;
        /* lock viewport */
        box-sizing: border-box;
        overflow: hidden;
        /* stop page scrolling */
        background: #f8fafb00;
    }

    /* Header */
    .pr-head {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 10px;
        flex: 0 0 auto;
    }

    .pr-back {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid #eadedd;
        background: var(--rose-10);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-900);
        text-decoration: none;
    }

    .pr-title {
        font-weight: 800;
        color: var(--text-900);
        letter-spacing: .2px;
        font-size: clamp(18px, 2.4vw, 20px);
    }

    /* Info rows (non-scrolling) */
    .pr-info {
        flex: 0 0 auto;
    }

    .pr-section-title {
        color: var(--text-900);
        font-weight: 700;
        margin: 6px 0 10px;
    }

    .pr-list {
        margin: 0;
        border-top: 1px solid #eee;
        border-bottom: 1px solid #eee;
        border-radius: 10px;
        background: #fff;
    }

    .pr-list .row {
        padding: 10px 12px;
        border-top: 1px solid #f0eeee;
    }

    .pr-list .row:first-child {
        border-top: none;
    }

    .pr-term {
        color: var(--muted);
        font-weight: 700;
    }

    .pr-def {
        color: #4c4040;
        text-align: right;
    }

    @media (max-width: 576px) {
        .pr-def {
            text-align: left;
            margin-top: 4px;
        }
    }

    /* ===== Scrollable photos area ===== */
    .pr-photos-box {
        flex: 1 1 auto;
        /* takes remaining height */
        min-height: 160px;
        margin-top: 12px;
        padding-right: 6px;
        /* space for scrollbar */
        overflow: auto;
        /* <-- only this area scrolls */
        border-radius: 14px;
        background: #ffffff;
        box-shadow: var(--shadow);
        border: 1px solid #f1eceb;
    }

    /* custom scrollbar (brown / mauve) */
    .pr-photos-box::-webkit-scrollbar {
        width: 10px;
    }

    .pr-photos-box::-webkit-scrollbar-track {
        background: #f6e6e4;
        border-radius: 10px;
    }

    .pr-photos-box::-webkit-scrollbar-thumb {
        background: var(--rose-700);
        border-radius: 10px;
        border: 2px solid #f6e6e4;
    }

    .pr-photos-box {
        scrollbar-color: var(--rose-700) #f6e6e4;
        scrollbar-width: thin;
    }

    /* Firefox */

    /* grid inside the scroll area */
    .pr-photos {
        display: grid;
        gap: 18px;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        padding: 16px;
    }

    @media (max-width: 640px) {
        .pr-photos {
            grid-template-columns: 1fr;
        }
    }

    .pr-photo {
        background: #faf8f8;
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 10px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-items: center;
        justify-content: center;
        min-height: 260px;
    }

    .pr-photo small {
        color: var(--muted);
        font-weight: 700;
    }

    .pr-photo img {
        width: 100%;
        height: 100%;
        max-height: 420px;
        object-fit: cover;
        border-radius: 10px;
    }

    /* Footer (non-scrolling) */
    /* confirm row */
    .pr-confirm {
        display: flex;
        justify-content: center;
        margin: 12px 0 6px;
    }

    .pr-check {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        font-weight: 700;
        color: #5b4a48;
        /* text-900 */
    }

    /* custom checkbox */
    .pr-check input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #b48b87;
        /* rose-500 */
        border-radius: 4px;
        display: inline-block;
        position: relative;
        cursor: pointer;
        background: #fff9f8;
        /* rose-10 */
    }

    .pr-check input[type="checkbox"]:checked {
        background: #8a6561;
        /* rose-700 */
        border-color: #8a6561;
    }

    .pr-check input[type="checkbox"]:checked::after {
        content: "";
        position: absolute;
        left: 4px;
        top: 0px;
        width: 5px;
        height: 10px;
        border: 2px solid #fff;
        border-top: 0;
        border-left: 0;
        transform: rotate(45deg);
    }

    .pr-actions {
        display: flex;
        justify-content: center;
        margin-top: 8px;
    }

    .pr-submit {
        min-width: 240px;
        border: none;
        background: #8a6561;
        color: #fff;
        border-radius: 10px;
        padding: 10px 22px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 10px 30px rgba(116, 84, 81, .10);
    }

    .invalid-feedback {
        display: block;
        margin-top: 6px;
        text-align: center;
    }
</style>

<!-- Logo -->
<div class="logo-container">
    <img src="{{ asset('storage/bricks_logo.png') }}" alt="BRICKS Model Logo">
</div>

<div class="pr-wrap">

    {{-- Header --}}
    <div class="pr-head">
        <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="pr-back"
            aria-label="{{ trans('global.back') }}">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </a>
        <div class="pr-title">{{ __('Profile Review') }}</div>
    </div>

    {{-- Non-scrolling info --}}
    <div class="pr-info">
        <div class="pr-section-title">{{ trans('global.profile_information') }}</div>
        <div class="pr-list">
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('global.full_name') }}</div>
                <div class="col-sm-8 pr-def">{{ $profile->legal_name }}</div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('global.login_email') }}</div>
                <div class="col-sm-8 pr-def">{{ auth('talent')->user()->email }}</div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('global.gender') }}</div>
                <div class="col-sm-8 pr-def">{{ trans('global.gender_display.' . $profile->gender) }}</div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('cruds.talentProfile.fields.height') }}</div>
                <div class="col-sm-8 pr-def">{{ $profile->height ?: trans('global.not_set') }}</div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('cruds.talentProfile.fields.weight') }}</div>
                <div class="col-sm-8 pr-def">{{ $profile->weight ?: trans('global.not_set') }}</div>
            </div>
            <div class="row align-items-center">
                <div class="col-sm-4 pr-term">{{ trans('global.date_of_birth') }}</div>
                <div class="col-sm-8 pr-def">
                    {{ optional($profile->date_of_birth)->format(config('panel.date_format')) }}</div>
            </div>
        </div>

        <div class="pr-section-title" style="margin-top:14px;">{{ trans('global.uploaded_documents') }} &amp; photos
        </div>
    </div>

    {{-- ONLY THIS BOX SCROLLS --}}
    <div class="pr-photos-box">
        <div class="pr-photos">
            @foreach ([
        'id_front_path' => trans('global.id_front'),
        'id_back_path' => trans('global.id_back'),
        'headshot_center_path' => trans('global.headshot_center'),
        'headshot_left_path' => trans('global.headshot_left'),
        'headshot_right_path' => trans('global.headshot_right'),
        'full_body_front_path' => trans('global.full_body_front'),
        'full_body_right_path' => trans('global.full_body_right'),
        'full_body_back_path' => trans('global.full_body_back'),
    ] as $field => $label)
                @if ($profile->{$field})
                    <div class="pr-photo">
                        <small>{{ $label }}</small>
                        <img src="{{ $profile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    {{-- Confirm + Submit (always visible) --}}
    <form method="POST" action="{{ route('talent.onboarding.store', 'review') }}">
        @csrf
        <div class="pr-confirm">
            <label class="pr-check" for="confirm">
                <input type="checkbox" id="confirm" name="confirm" class="@error('confirm') is-invalid @enderror"
                    required>
                <span>{{ trans('global.onboarding_confirm') }}</span>
            </label>
        </div>
        @error('confirm')
            <div class="invalid-feedback"><strong>{{ $message }}</strong></div>
        @enderror

        <div class="pr-actions">
            <button type="submit" class="pr-submit">{{ __('Submit for verification') }}</button>
        </div>
    </form>
</div>
