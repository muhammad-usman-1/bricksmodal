<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.talent-menu')
    <div class="c-wrapper">
        <header class="c-header c-header-fixed talent-header px-3">
            <style>
                :root {
                    --rose-10: #fff9f8;
                    --rose-100: #f6e6e4;
                    --rose-200: #e9d3d1;
                    --rose-700: #8a6561;
                    --text-900: #5b4a48;
                }

                .talent-header {
                    background: #fff;
                    border-bottom: 1px solid #efe3e1;
                }

                .talent-header .icon-btn {
                    width: 36px;
                    height: 36px;
                    border-radius: 999px;
                    border: 1px solid var(--rose-200);
                    background: var(--rose-10);
                    color: var(--rose-700);
                    display: inline-flex;
                    align-items: center;
                    justify-content: center;
                    margin-right: 10px;
                }

                .talent-header .avatar {
                    width: 42px;
                    height: 42px;
                    border-radius: 999px;
                    overflow: hidden;
                    border: 1px solid var(--rose-200);
                    background: #f1e8e7;
                }

                .talent-header .avatar img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .talent-header .brand-xs {
                    color: var(--text-900);
                    font-weight: 800;
                }
            </style>

            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar"
                data-class="c-sidebar-show" aria-label="Toggle sidebar">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <a class="c-header-brand d-lg-none brand-xs" href="{{ route('talent.dashboard') }}">
                {{ trans('panel.site_title') }}
            </a>

            @php
                $talentUser = auth('talent')->user();
                $profile = $talentUser?->talentProfile;

                // Original sources (may be strings OR arrays depending on your code)
                $avatarRaw =
                    $profile?->headshot_center_path ??
                    ($profile?->headshot_left_path ?? $profile?->headshot_right_path);

                // Normalize to a string
                if (is_array($avatarRaw)) {
                    // common shapes: ['url'=>...], ['path'=>...], ['0'=>...]
                    $avatar =
                        $avatarRaw['url'] ?? ($avatarRaw['path'] ?? (isset($avatarRaw[0]) ? $avatarRaw[0] : null));
                } else {
                    $avatar = $avatarRaw;
                }

                // Tiny inline SVG fallback (never 404s)
                $fallbackSvg =
                    'data:image/svg+xml;utf8,' .
                    rawurlencode('
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 96 96">
              <rect width="96" height="96" rx="48" fill="#eddcda"/>
              <circle cx="48" cy="38" r="18" fill="#c8adab"/>
              <rect x="20" y="60" width="56" height="22" rx="11" fill="#d8c1bf"/>
            </svg>');
                $avatarSrc = $avatar ?: $fallbackSvg;
            @endphp

            <ul class="c-header-nav ml-auto align-items-center">
                <li class="c-header-nav-item">
                    <a href="{{ Route::has('talent.settings.index') ? route('talent.settings.index') : '#' }}"
                        class="icon-btn" aria-label="{{ __('Settings') }}"><i class="fas fa-sliders-h"></i></a>
                </li>
                <li class="c-header-nav-item">
                    <a href="{{ Route::has('talent.notifications') ? route('talent.notifications') : '#' }}"
                        class="icon-btn" aria-label=" "><i class="far fa-bell"></i></a>
                </li>
                <li class="c-header-nav-item d-flex align-items-center">
                    <span class="avatar">
                        <img src="{{ $avatarSrc }}" alt="{{ $talentUser?->name ?? 'Profile' }}">
                    </span>
                </li>
            </ul>
        </header>


        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    @if (session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if ($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <form id="talent-logout-form" action="{{ route('talent.logout') }}" method="POST" style="display: none;">
        @csrf
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.2/dist/js/coreui.min.js"></script>
    @yield('scripts')
</body>

</html>
