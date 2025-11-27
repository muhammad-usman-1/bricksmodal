@extends('talent.onboarding.layout', ['progress' => $progress ?? null])


<style>
    /* ===== Theme (soft rose / mauve) ===== */
    :root {
        --rose-10: #fff9f8;
        --rose-50: #fdeeee;
        --rose-100: #f6e6e4;
        --rose-200: #e9d3d1;
        --rose-300: #d9bebc;
        --rose-500: #b48b87;
        /* labels & accents */
        --rose-700: #8a6561;
        /* primary button bg */
        --text-900: #5b4a48;
        /* headings */
        --text-700: #6c5a58;
        /* labels */
        --input-bg: #f7efee;
        --input-border: #d9c9c7;
        --white: #fff;
        --shadow: 0 8px 24px rgba(116, 84, 81, .08);
        --radius: 12px;
    }

    .logo-container {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }

    .logo-container img {
        height: 25px;
        width: 100px;
    }

    /* ===== Layout frame ===== */
    .ps-wrap {
        max-width: 1200px;
        margin: 0 auto;
        background: var(--white);
        padding: clamp(16px, 3vw, 28px);
    }

    .ps-card {
        background: var(--white);
        border-radius: 20px;

        padding: clamp(16px, 4vw, 40px);
    }

    /* ===== Header (back + title) ===== */
    .ps-head {
        display: flex;
        align-items: center;
        gap: 12px;
        justify-content: center;
        position: relative;
        margin-bottom: 22px;
    }

    .ps-back {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid var(--input-border);
        background: var(--rose-10);
        text-decoration: none;
        color: var(--text-900);
    }

    .ps-title {
        font-weight: 700;
        letter-spacing: .3px;
        color: var(--text-900);
        font-size: clamp(18px, 2.6vw, 20px);
    }

    /* ===== Form look ===== */
    .profile-setup label {
        color: var(--rose-500);
        font-weight: 600;
        font-size: 13px;
        margin-bottom: 8px;
    }

    .profile-setup .form-control,
    .profile-setup select.form-control {
        background: var(--input-bg);
        border: 1px solid var(--input-border);
        color: var(--text-900);
        border-radius: var(--radius);
        padding: 12px 14px;
        height: 42px;
        outline: none;
        box-shadow: none;
    }

    .profile-setup .form-control:focus {
        border-color: var(--rose-300);
        box-shadow: 0 0 0 4px rgba(212, 169, 165, .18);
        background: #fff;
    }

    .invalid-feedback {
        display: block;
    }

    .form-text.text-muted {
        color: #8c7b79 !important;
    }

    /* grid rows with gap */
    .profile-setup .form-row {
        display: grid;
        gap: 14px;
        grid-template-columns: 1fr 1fr;
        margin-bottom: 12px;
    }

    .profile-setup .form-group {
        margin-bottom: 14px;
    }

    .profile-setup .col-md-6,
    .profile-setup .col-md-4 {
        width: auto;
    }

    /* >>> Ensure DoB is one full-width line and Gender is a separate line <<< */
    .profile-setup .form-row.form-row--dob-gender {
        grid-template-columns: 1fr;
        /* override the 2-col default */
        row-gap: 16px;
        margin-top: 6px;
        margin-bottom: 6px;
    }

    /* Date with icon */
    .ps-date-wrap {
        position: relative;
    }

    .ps-date-ico {
        position: absolute;
        right: 12px;
        top: 50%;
        transform: translateY(-50%);
        width: 18px;
        height: 18px;
        opacity: .6;
        pointer-events: none;
    }

    .ps-date-wrap input[type="date"] {
        padding-right: 40px;
    }

    /* Gender chip buttons (binds to existing select) */
    .ps-gender {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /* one row, three equal chips */
        gap: 14px;
        margin-top: 6px;
    }

    .ps-chip {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 10px 12px;
        border-radius: 12px;
        border: 1px solid var(--input-border);
        background: var(--input-bg);
        color: var(--text-900);
        cursor: pointer;
        user-select: none;
        transition: .15s ease;
        min-height: 42px;
        font-weight: 600;
    }

    .ps-chip.active {
        background: #fff;
        border-color: var(--rose-300);
        box-shadow: 0 0 0 4px rgba(212, 169, 165, .18) inset;
    }

    .ps-chip:hover {
        background: #fff;
    }

    /* Hide the raw select but keep it (do not remove) */
    .ps-gender-select {
        position: absolute;
        left: -9999px;
        width: 1px;
        height: 1px;
    }

    /* Footer button */
    .ps-actions {
        display: flex;
        justify-content: center;
        margin-top: 8px;
    }

    .ps-next {
        min-width: 180px;
        background: var(--rose-700);
        border: none;
        color: #fff;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 700;
        box-shadow: var(--shadow);
    }

    .ps-next:hover {
        filter: brightness(.96);
    }

    /* Responsive tweaks */
    @media (max-width: 768px) {
        .profile-setup .form-row {
            grid-template-columns: 1fr;
        }

        .ps-gender {
            grid-template-columns: 1fr;
        }

        /* stack chips on small screens */
    }
</style>

<!-- Logo -->
<div class="logo-container">
    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
</div>

<div class="ps-wrap">
    <div class="ps-card profile-setup">
        <div class="ps-head">
            {{-- Back (uses browser history; replace href if you have a route) --}}
            <a href="javascript:history.back()" class="ps-back" aria-label="Go back">
                {{-- left arrow --}}
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
            <div class="ps-title">{{ __('Profile Setup') }}</div>
        </div>

        {{-- ====== ORIGINAL FORM (all fields preserved) ====== --}}
        <form method="POST" action="{{ route('talent.onboarding.store', 'profile') }}">
            @csrf

            <div class="form-row">
                <div class="form-group">
                    <label for="full_name">{{ trans('global.full_name') }}</label>
                    <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name"
                        name="full_name" value="{{ old('full_name', $profile->legal_name) }}" required>
                    @error('full_name')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">{{ trans('global.login_email') }}</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', auth('talent')->user()->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="height">{{ trans('cruds.talentProfile.fields.height') }} <small>(cm)</small></label>
                    <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror"
                        id="height" name="height" value="{{ old('height', $profile->height) }}"
                        placeholder="{{ trans('global.height_placeholder') }}">
                    @error('height')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="weight">{{ trans('cruds.talentProfile.fields.weight') }} <small>(kg)</small></label>
                    <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight"
                        name="weight" value="{{ old('weight', $profile->weight) }}" placeholder="kg">
                    @error('weight')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="daily_rate">{{ trans('cruds.talentProfile.fields.daily_rate') }}</label>
                    <input type="number" step="0.01" class="form-control @error('daily_rate') is-invalid @enderror"
                        id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $profile->daily_rate) }}"
                        required>
                    @error('daily_rate')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="hourly_rate">{{ trans('cruds.talentProfile.fields.hourly_rate') }}</label>
                    <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror"
                        id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}">
                    @error('hourly_rate')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="chest">{{ trans('cruds.talentProfile.fields.chest') }}</label>
                    <input type="number" class="form-control @error('chest') is-invalid @enderror" id="chest"
                        name="chest" value="{{ old('chest', $profile->chest) }}">
                    @error('chest')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="waist">{{ trans('cruds.talentProfile.fields.waist') }}</label>
                    <input type="number" class="form-control @error('waist') is-invalid @enderror" id="waist"
                        name="waist" value="{{ old('waist', $profile->waist) }}">
                    @error('waist')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="hips">{{ trans('cruds.talentProfile.fields.hips') }}</label>
                    <input type="number" class="form-control @error('hips') is-invalid @enderror" id="hips"
                        name="hips" value="{{ old('hips', $profile->hips) }}">
                    @error('hips')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            {{-- Date of Birth (one full-width line) then Gender (separate row) --}}
            <div class="form-row form-row--dob-gender">
                <div class="form-group">
                    <label for="date_of_birth">{{ trans('global.date_of_birth') }}</label>
                    <div class="ps-date-wrap">
                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror"
                            id="date_of_birth" name="date_of_birth"
                            value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}"
                            required>
                        <svg class="ps-date-ico" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                            <path
                                d="M7 2v3M17 2v3M3 10h18M5 6h14a2 2 0 012 2v12a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2z"
                                stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    @error('date_of_birth')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                {{-- Keep original select; visually replaced with chips in one row --}}
                <div class="form-group">
                    <label for="gender">{{ trans('global.gender') }}</label>
                    <select class="form-control ps-gender-select @error('gender') is-invalid @enderror"
                        id="gender" name="gender" required>
                        <option value="" disabled {{ old('gender', $profile->gender) ? '' : 'selected' }}>
                            {{ trans('global.pleaseSelect') }}</option>
                        @foreach (['male' => trans('global.gender_male'), 'female' => trans('global.gender_female'), 'non_binary' => trans('global.gender_non_binary')] as $value => $label)
                            <option value="{{ $value }}"
                                {{ old('gender', $profile->gender) === $value ? 'selected' : '' }}>{{ $label }}
                            </option>
                        @endforeach
                    </select>

                    <!-- Chip UI bound to the select above -->
                    <div class="ps-gender" data-gender-chips>
                        <div class="ps-chip" data-value="male">{{ trans('global.gender_male') }}</div>
                        <div class="ps-chip" data-value="female">{{ trans('global.gender_female') }}</div>
                        <div class="ps-chip" data-value="non_binary">{{ trans('global.gender_non_binary') }}</div>
                    </div>

                    @error('gender')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="whatsapp_number">{{ trans('global.whatsapp_number') }}</label>
                <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                    id="whatsapp_number" name="whatsapp_number"
                    value="{{ old('whatsapp_number', $profile->whatsapp_number ? '+' . $profile->whatsapp_number : '') }}"
                    placeholder="{{ trans('global.whatsapp_number_placeholder') }}" required>
                @error('whatsapp_number')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
                <small class="form-text text-muted">{{ trans('global.whatsapp_number_helper') }}</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="skin_tone">{{ trans('cruds.talentProfile.fields.skin_tone') }}</label>
                    <select class="form-control @error('skin_tone') is-invalid @enderror" id="skin_tone"
                        name="skin_tone">
                        <option value="">{{ trans('global.pleaseSelect') }}</option>
                        @foreach (App\Models\TalentProfile::SKIN_TONE_SELECT as $key => $label)
                            <option value="{{ $key }}"
                                {{ old('skin_tone', $profile->skin_tone) === $key ? 'selected' : '' }}>
                                {{ $label }}</option>
                        @endforeach
                    </select>
                    @error('skin_tone')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="hair_color">{{ trans('cruds.talentProfile.fields.hair_color') }}</label>
                    <input type="text" class="form-control @error('hair_color') is-invalid @enderror"
                        id="hair_color" name="hair_color" value="{{ old('hair_color', $profile->hair_color) }}">
                    @error('hair_color')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="eye_color">{{ trans('cruds.talentProfile.fields.eye_color') }}</label>
                    <input type="text" class="form-control @error('eye_color') is-invalid @enderror"
                        id="eye_color" name="eye_color" value="{{ old('eye_color', $profile->eye_color) }}">
                    @error('eye_color')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="shoe_size">{{ trans('cruds.talentProfile.fields.shoe_size') }}</label>
                    <input type="number" class="form-control @error('shoe_size') is-invalid @enderror"
                        id="shoe_size" name="shoe_size" value="{{ old('shoe_size', $profile->shoe_size) }}">
                    @error('shoe_size')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>
            </div>

            <div class="ps-actions">
                <button type="submit" class="ps-next">{{ trans('global.next_step') }}</button>
            </div>
        </form>
        {{-- ====== /Original form ====== --}}
    </div>
</div>

<script>
    /* Gender chip binding — keeps your original <select name="gender"> intact */
    (function() {
        const wrap = document.querySelector('[data-gender-chips]');
        if (!wrap) return;
        const select = document.getElementById('gender');
        const chips = Array.from(wrap.querySelectorAll('.ps-chip'));

        function activate(val) {
            chips.forEach(c => c.classList.toggle('active', c.dataset.value === val));
            if (select && select.value !== val) {
                select.value = val || '';
            }
        }
        chips.forEach(chip => chip.addEventListener('click', () => activate(chip.dataset.value)));
        // initialize from old() / model
        activate(select?.value || '');
    })();
</script>
