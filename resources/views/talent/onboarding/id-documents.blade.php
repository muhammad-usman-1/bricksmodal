@extends('talent.onboarding.layout', ['progress' => $progress ?? null])


<style>
    /* ===== Theme (soft rose / mauve) ===== */
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
        --shadow: 0 8px 24px rgba(116, 84, 81, .08);
        --radius: 14px;
        --dash: 4px;
    }

    .logo-container {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 1000;
    }
 .logo-container img {
        height: 30px;
        width: 130px;
    }

    /* ===== Page frame (no cards, just clean spacing) ===== */
    .v-wrap {
        max-width: 920px;
        margin: 0 auto;
        padding: clamp(16px, 3vw, 28px);
    }

    .v-head {
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        margin-bottom: 18px;
    }

    .v-back {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid #e6d7d5;
        background: var(--rose-10);
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: var(--text-900);
        text-decoration: none;
    }

    .v-title {
        font-weight: 800;
        color: var(--text-900);
        letter-spacing: .2px;
        font-size: clamp(18px, 2.4vw, 20px);
    }

    .v-instruction {
        color: var(--muted);
        margin: 10px 0 16px 6px;
        font-weight: 600;
    }

    /* ===== Upload panels (unchanged) ===== */
    .v-upload {
        border: var(--dash) dashed var(--rose-300);
        background: #f7efee;
        border-radius: 16px;
        padding: 22px;
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 120px;
        text-align: center;
        transition: .15s ease;
    }

    .v-upload:hover {
        background: #fff;
        box-shadow: var(--shadow);
    }

    .v-upload-inner {
        display: flex;
        flex-direction: column;
        gap: 6px;
        align-items: center;
    }

    .v-upload .cam {
        width: 32px;
        height: 32px;
        opacity: .7;
    }

    .v-upload .ttl {
        color: var(--text-900);
        font-weight: 700;
    }

    .v-upload .sub {
        color: #b19794;
        font-size: 13px;
    }

    /* hide native input, keep DOM */
    .v-input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
        clip-path: inset(50%);
    }

    /* ===== NEW: tiny preview chip BELOW the panel ===== */
    .v-chips {
        margin: 8px 2px 2px;
        display: none;
    }

    .v-chips.show {
        display: block;
    }

    .v-chip {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #fff;
        border: 1px solid var(--rose-200);
        border-radius: 12px;
        padding: 8px 10px;
        box-shadow: var(--shadow);
    }

    .v-thumb {
        width: 36px;
        height: 28px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--rose-200);
        background: #fff;
    }

    .v-name {
        font-weight: 700;
        color: var(--text-900);
        font-size: 13px;
        max-width: 48ch;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .v-size {
        color: #a18986;
        font-size: 12px;
        margin-left: 6px;
    }

    .v-remove {
        margin-left: auto;
        border: 1px solid var(--rose-300);
        background: #fff;
        border-radius: 10px;
        padding: 6px 10px;
        font-size: 12px;
        color: var(--text-900);
        cursor: pointer;
    }

    .v-remove:hover {
        background: #fdf9f8;
    }

    /* Info note & CTA (unchanged) */
    .v-note {
        display: flex;
        align-items: center;
        gap: 10px;
        background: #f3ebea;
        border-radius: 10px;
        padding: 12px 14px;
        color: var(--text-900);
        border: 1px solid #eadedd;
        margin-top: 14px;
    }

    .v-note svg {
        width: 18px;
        height: 18px;
        color: #b19794;
    }

    .v-actions {
        display: flex;
        justify-content: center;
        gap: 12px;
        margin-top: 18px;
    }

    .v-next {
        min-width: 180px;
        border: none;
        background: var(--rose-700);
        color: #fff;
        border-radius: 10px;
        padding: 10px 20px;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: var(--shadow);
    }

    /* tiny toast */
    .v-toast {
        position: fixed;
        right: 16px;
        top: 16px;
        z-index: 50;
        background: #2e7d32;
        color: #fff;
        padding: 10px 14px;
        border-radius: 10px;
        box-shadow: var(--shadow);
        font-weight: 700;
        display: none;
    }

    /* Errors */
    .invalid-feedback {
        display: block;
        color: #b05757;
        margin-top: 8px;
    }

    @media (max-width: 640px) {
        .v-upload {
            min-height: 110px;
            padding: 18px;
        }

        .v-name {
            max-width: 50vw;
        }
    }
</style>

<!-- Logo -->
<div class="logo-container">
    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
</div>

<div class="v-toast" id="vToast">Selected ✔</div>

<div class="v-wrap">
    <div class="v-head">
        <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="v-back"
            aria-label="{{ trans('global.back') }}">
            <svg viewBox="0 0 24 24" fill="none">
                <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </a>
        <div class="v-title">{{ __('Verification') }}</div>
    </div>

    <p class="v-instruction">{{ trans('global.onboarding_id_instructions') }}</p>

    <form method="POST" action="{{ route('talent.onboarding.store', 'id-documents') }}" enctype="multipart/form-data">
        @csrf

        {{-- Front of ID (panel unchanged) --}}
        <label class="v-upload" for="id_front">
            <input type="file" class="v-input form-control-file @error('id_front') is-invalid @enderror"
                id="id_front" name="id_front" accept="image/*" required>
            <div class="v-upload-inner">
                <svg class="cam" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h3l1.2-2h7.6L17 7h3a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2Z"
                        stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="12" cy="13" r="3.5" stroke="currentColor" stroke-width="1.6" />
                </svg>
                <div class="ttl">{{ __('Front of ID') }}</div>
                <div class="sub">{{ __('Upload from gallery') }}</div>
            </div>
        </label>
        {{-- NEW: minimal preview chip (hidden until chosen) --}}
        <div class="v-chips" id="chips_front">
            <div class="v-chip">
                <img class="v-thumb" alt="Front preview">
                <div class="v-name"></div>
                <div class="v-size"></div>
                <button type="button" class="v-remove" data-clear="id_front">{{ __('Remove') }}</button>
            </div>
        </div>
        @error('id_front')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror

        {{-- Back of ID (panel unchanged) --}}
        <label class="v-upload" for="id_back" style="margin-top:14px;">
            <input type="file" class="v-input form-control-file @error('id_back') is-invalid @enderror"
                id="id_back" name="id_back" accept="image/*" required>
            <div class="v-upload-inner">
                <svg class="cam" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <path d="M4 7h3l1.2-2h7.6L17 7h3a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2Z"
                        stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                    <circle cx="12" cy="13" r="3.5" stroke="currentColor" stroke-width="1.6" />
                </svg>
                <div class="ttl">{{ __('Back of ID') }}</div>
                <div class="sub">{{ __('Upload from gallery') }}</div>
            </div>
        </label>
        {{-- NEW: minimal preview chip (hidden until chosen) --}}
        <div class="v-chips" id="chips_back">
            <div class="v-chip">
                <img class="v-thumb" alt="Back preview">
                <div class="v-name"></div>
                <div class="v-size"></div>
                <button type="button" class="v-remove" data-clear="id_back">{{ __('Remove') }}</button>
            </div>
        </div>
        @error('id_back')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror

        <div class="v-note">
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <circle cx="12" cy="12" r="9" stroke="currentColor" stroke-width="1.6" />
                <path d="M12 8v.01M12 11v5" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
            </svg>
            <span>{{ __('Make sure all details are clear and readable.') }}</span>
        </div>

        <div class="v-actions">
            <button type="submit" class="v-next">{{ trans('global.next_step') }}</button>
        </div>
    </form>
</div>

<script>
    /* Minimal JS: show tiny preview chip below panel; allow remove; keep layout untouched */
    (function() {
        const MAX_MB = 10;
        const toast = document.createElement('div');
        toast.className = 'v-toast';
        toast.id = 'vToast';
        toast.textContent = 'Selected ✔';
        document.body.appendChild(toast);

        function showToast(msg) {
            toast.textContent = msg || 'Selected ✔';
            toast.style.display = 'block';
            clearTimeout(showToast.tid);
            showToast.tid = setTimeout(() => toast.style.display = 'none', 1200);
        }

        function sizeStr(bytes) {
            const mb = bytes / 1048576;
            return mb >= 1 ? mb.toFixed(1) + ' MB' : Math.round(bytes / 1024) + ' KB';
        }

        function wire(inputId, chipsId, nextEl) {
            const input = document.getElementById(inputId);
            const chips = document.getElementById(chipsId);
            if (!input || !chips) return;

            const thumb = chips.querySelector('.v-thumb');
            const nameEl = chips.querySelector('.v-name');
            const sizeEl = chips.querySelector('.v-size');
            const removeBtn = chips.querySelector('.v-remove');

            input.addEventListener('change', function() {
                const f = this.files && this.files[0];
                if (!f) {
                    chips.classList.remove('show');
                    return;
                }
                if (!f.type.startsWith('image/')) {
                    this.value = '';
                    return;
                }
                if (f.size > MAX_MB * 1048576) {
                    /* optionally warn; not blocking */ }

                const url = URL.createObjectURL(f);
                thumb.src = url;
                nameEl.textContent = f.name;
                sizeEl.textContent = '· ' + sizeStr(f.size);
                chips.classList.add('show');
                showToast();

                (nextEl || chips).scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest'
                });
                setTimeout(() => URL.revokeObjectURL(url), 3000);
            });

            removeBtn.addEventListener('click', function(e) {
                e.preventDefault();
                input.value = '';
                chips.classList.remove('show');
                showToast('Removed');
            });
        }

        wire('id_front', 'chips_front', document.querySelector('[for="id_back"]'));
        wire('id_back', 'chips_back', document.querySelector('.v-note'));
    })();
</script>
