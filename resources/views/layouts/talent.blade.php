<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <header class="c-header c-header-fixed px-3">
            <button class="c-header-toggler c-class-toggler d-lg-none mfe-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
                <i class="fas fa-fw fa-bars"></i>
            </button>

            <a class="c-header-brand d-lg-none" href="{{ route('talent.dashboard') }}">{{ trans('panel.site_title') }}</a>

            @php
                $talentUser = auth('talent')->user();
                $profile = $talentUser?->talentProfile;
                $avatar = $profile?->headshot_center_path ?? $profile?->headshot_left_path ?? $profile?->headshot_right_path;
            @endphp

            <ul class="c-header-nav ml-auto align-items-center">
                <li class="c-header-nav-item mr-3 d-flex align-items-center text-muted">
                     <span>{{ $talentUser?->name ?? '' }}</span>
                    @if($avatar)
                        <span class="rounded-circle overflow-hidden d-inline-block mr-2" style="width:42px;height:42px;">
                            <img src="{{ $avatar }}" alt="{{ $talentUser?->name }}" class="img-fluid" style="object-fit:cover;width:100%;height:100%;">
                        </span>
                    @else
                        <span class="rounded-circle bg-secondary d-inline-flex justify-content-center align-items-center mr-2" style="width:42px;height:42px;color:#fff;">
                            <i class="fas fa-user"></i>
                        </span>
                    @endif

                </li>
            </ul>
        </header>

        <div class="c-body">
            <main class="c-main">
                <div class="container-fluid">
                    @if(session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled mb-0">
                                @foreach($errors->all() as $error)
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
