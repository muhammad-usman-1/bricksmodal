@php
    $label = $label ?? __('Headshot (Front)');
    $instructions = $instructions ?? __('Follow these steps. Use natural light. No makeup. Neutral background.');
    // Inline SVG placeholder (never 404s)
    $avatarSVG =
        'data:image/svg+xml;utf8,' .
        rawurlencode('
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 420">
      <defs>
        <linearGradient id="g" x1="0" x2="0" y1="0" y2="1">
          <stop offset="0" stop-color="#3f3836"/>
          <stop offset="1" stop-color="#2f2a28"/>
        </linearGradient>
      </defs>
      <rect width="320" height="420" fill="url(#g)"/>
      <g transform="translate(0,28)">
        <ellipse cx="160" cy="180" rx="110" ry="140" fill="#4b4341"/>
        <ellipse cx="160" cy="180" rx="108" ry="138" fill="none" stroke="#ffffff" stroke-opacity=".7" stroke-dasharray="6 6" stroke-width="2"/>
      </g>
      <g transform="translate(80,120)">
        <circle cx="80" cy="60" r="54" fill="#e9d3d1"/>
        <rect x="0" y="145" width="160" height="90" rx="20" fill="#3d3533"/>
        <rect x="40" y="150" width="80" height="28" rx="14" fill="#2f2a28"/>
      </g>
      <text x="160" y="24" text-anchor="middle" fill="#b9aaaa" font-size="14" font-family="sans-serif">headshot preview</text>
    </svg>');
@endphp

<style>
    :root {
        --rose-10: #fff9f8;
        --rose-100: #f6e6e4;
        --rose-200: #e9d3d1;
        --rose-300: #d9bebc;
        --rose-500: #b48b87;
        --rose-700: #8a6561;
        --text-900: #5b4a48;
        --muted: #8c7b79;
        --shadow: 0 10px 30px rgba(116, 84, 81, .12);
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

    .hs-wrap {
        max-width: 760px;
        margin: 0 auto;
        padding: clamp(16px, 3vw, 28px);
        text-align: center;
    }

    .hs-head {
        position: relative;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-bottom: 6px;
    }

    .hs-back {
        position: absolute;
        left: 0;
        top: 50%;
        transform: translateY(-50%);
        width: 36px;
        height: 36px;
        border-radius: 999px;
        border: 1px solid #e6d7d5;
        background: #fff9f8;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        color: #5b4a48;
        text-decoration: none;
    }

    .hs-title {
        font-weight: 800;
        color: #5b4a48;
        letter-spacing: .2px;
        font-size: clamp(18px, 2.6vw, 22px);
        margin: 0;
    }

    .hs-instruction {
        color: #8c7b79;
        font-weight: 600;
        margin: 8px 0 18px;
        font-size: 14px;
    }

    .hs-stage {
        display: flex;
        justify-content: center;
        margin: 8px 0 18px;
    }

    .hs-frame {
        width: min(68vw, 340px);
        height: min(92vw, 440px);
        max-height: 480px;
        border-radius: 12px;
        box-shadow: var(--shadow);
        position: relative;
        overflow: hidden;
        background: #3f3836;
    }

    .hs-img {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
    }

    .hs-oval {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        width: 72%;
        height: 68%;
        border: 2px dashed rgba(255, 255, 255, .7);
        border-radius: 50% / 48%;
        box-shadow: 0 0 0 160px rgba(0, 0, 0, .35) inset;
        pointer-events: none;
    }

    .hs-capture {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 74px;
        height: 74px;
        margin: 8px auto 8px;
        border-radius: 999px;
        background: #fff;
        box-shadow: var(--shadow);
        border: 1px solid #eadedd;
        cursor: pointer;
    }

    .hs-capture svg {
        width: 28px;
        height: 28px;
        color: #8a6561;
    }

    .hs-input {
        position: absolute;
        width: 1px;
        height: 1px;
        overflow: hidden;
        clip: rect(0 0 0 0);
        white-space: nowrap;
        clip-path: inset(50%);
    }

    .hs-actions {
        display: flex;
        justify-content: center;
    }

    .hs-next {
        min-width: 200px;
        padding: 10px 22px;
        border: none;
        border-radius: 10px;
        background: #8a6561;
        color: #fff;
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: var(--shadow);
    }

    .invalid-feedback {
        display: block;
        color: #b05757;
        margin-top: 8px;
    }

    .capture-error {
        display: none;
        color: #b05757;
        font-weight: 700;
        margin-top: 6px;
    }

    .hs-capture.error {
        box-shadow: 0 0 0 3px rgba(176, 87, 87, .25), var(--shadow);
        border-color: #b05757;
    }

    @media (max-width:560px) {
        .hs-frame {
            width: 82vw;
            height: 116vw;
        }
    }
</style>

<!-- Logo -->
<div class="logo-container">
    <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Model Logo">
</div>

<div class="hs-wrap">
    <div class="hs-head">
        @isset($previousStep)
            <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="hs-back" aria-label="{{ __('Back') }}">
                <svg viewBox="0 0 24 24" fill="none">
                    <path d="M15 18l-6-6 6-6" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </a>
        @endisset
        <h2 class="hs-title">{{ $label }}</h2>
    </div>

    <p class="hs-instruction">{{ $instructions }}</p>

    <form id="hsForm" method="POST" action="{{ route('talent.onboarding.store', $currentStep) }}"
        enctype="multipart/form-data" novalidate>
        @csrf

        <div class="hs-stage">
            <div class="hs-frame">
                <img id="hsPreview" class="hs-img" src="{{ $avatarSVG }}" alt="headshot preview"
                    onerror="this.src='{{ $avatarSVG }}'">
                <div class="hs-oval"></div>
            </div>
        </div>

        <label class="hs-capture" for="photo" id="captureBtn">
            <input id="photo" name="photo" type="file" accept="image/*" capture="user"
                class="hs-input @error('photo') is-invalid @enderror" required>
            <svg viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M4 7h3l1.2-2h7.6L17 7h3a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V9a2 2 0 012-2Z"
                    stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" />
                <circle cx="12" cy="13" r="3.8" stroke="currentColor" stroke-width="1.7" />
            </svg>
        </label>
        <span id="photoError" class="capture-error">{{ __('Please select a headshot to continue.') }}</span>

        @error('photo')
            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
        @enderror

        <div class="hs-actions">
            <button type="submit" class="hs-next">{{ trans('global.next_step') }}</button>
        </div>
    </form>
@once
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endonce

<script>
    (function() {
        const input = document.getElementById('photo');
        const preview = document.getElementById('hsPreview');
        const placeholder = "{{ $avatarSVG }}";
        const form = document.getElementById('hsForm');
        const err = document.getElementById('photoError');
        const captureBtn = document.getElementById('captureBtn');
        const MAX_MB = 5;
        const MAX_BYTES = MAX_MB * 1024 * 1024;

        function showSizeAlert() {
            const message = `Please upload a single image under ${MAX_MB} MB to continue.`;
            if (window.Swal && typeof window.Swal.fire === 'function') {
                Swal.fire({
                    icon: 'error',
                    title: 'File too large',
                    text: message,
                    confirmButtonColor: '#8a6561',
                });
            } else {
                alert(message);
            }
        }

        if (input && preview) {
            input.addEventListener('change', function() {
                const f = this.files && this.files[0];
                if (!f) {
                    preview.src = placeholder;
                    err.style.display = 'block';
                    captureBtn.classList.add('error');
                    return;
                }
                if (!f.type.startsWith('image/')) {
                    this.value = '';
                    preview.src = placeholder;
                    err.style.display = 'block';
                    captureBtn.classList.add('error');
                    return;
                }
                if (f.size > MAX_BYTES) {
                    this.value = '';
                    preview.src = placeholder;
                    err.style.display = 'block';
                    captureBtn.classList.add('error');
                    showSizeAlert();
                    return;
                }
                const url = URL.createObjectURL(f);
                preview.src = url;
                err.style.display = 'none';
                captureBtn.classList.remove('error');
                setTimeout(() => URL.revokeObjectURL(url), 3000);
            });
        }

        // Block submit if no image selected
        if (form) {
            form.addEventListener('submit', function(e) {
                if (!input.files || !input.files.length) {
                    e.preventDefault();
                    err.style.display = 'block';
                    captureBtn.classList.add('error');
                    // bring the capture button into view for clarity
                    captureBtn.scrollIntoView({
                        behavior: 'smooth',
                        block: 'center'
                    });
                }
            });
        }
    })();
</script>
