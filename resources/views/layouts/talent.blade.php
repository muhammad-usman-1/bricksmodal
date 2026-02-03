<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="icon" href="{{ asset('images/favicon.png') }}" type="image/png">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.talent-menu')
    <div class="c-wrapper">
            <style>
            #admin-main-header {
                background-color: #ffffff !important;
                border-bottom: 1px solid #edf0f2 !important;
                box-shadow: none !important;
                height: 64px !important;
                display: flex !important;
                align-items: center !important;
                padding: 0 24px !important;
                width: 100% !important;
                z-index: 1030 !important;
            }

            #admin-topbar-container {
                display: flex !important;
                align-items: center !important;
                justify-content: space-between !important;
                width: 100% !important;
                height: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
                }

            #admin-search-box {
                display: flex !important;
                align-items: center !important;
                gap: 8px !important;
                background: #f7f9fb !important;
                border: 1px solid #e5e7eb !important;
                border-radius: 10px !important;
                padding: 8px 15px !important;

                width: 100% !important;

                flex-shrink: 1 !important;
            }

            #admin-search-box i {
                color: #a3a9b3 !important;
                font-size: 14px !important;
            }

            #admin-search-box input {
                border: none !important;
                outline: none !important;
                background: transparent !important;
                width: 100% !important;
                font-size: 13px !important;
                color: #111827 !important;
            }

            #admin-icons-group {
                display: flex !important;
                align-items: center !important;
                justify-content: flex-end !important;
                flex-shrink: 0 !important;
                margin-left: 20px !important;
                }

            .header-icon-link {
                margin-left: 18px !important;
                display: inline-flex !important;
                align-items: center !important;
                justify-content: center !important;
                width: 38px !important;
                height: 38px !important;
                border-radius: 10px !important;
                transition: background 0.2s !important;
                text-decoration: none !important;
                border: none !important;
                background: transparent !important;
            }
            /* Sidebar expand button no longer lives in the header */
            #sidebarCollapseBtn { display: none !important; }

            .header-icon-link:first-child {
                margin-left: 0 !important;
            }

            .header-icon-link:hover {
                background: #f3f4f6 !important;
            }

            /* Collapse button with border */
            #sidebarCollapseBtn {
                border: 1px solid #e5e7eb !important;
                background: #ffffff !important;
                }

            #sidebarCollapseBtn:hover {
                background: #f9fafb !important;
                border-color: #d1d5db !important;
            }

            .header-icon-link img {
                width: 20px !important;
                height: 20px !important;
                display: block !important;
            }

            .noti-badge {
                position: absolute !important;
                top: 8px !important;
                right: 8px !important;
                width: 8px !important;
                height: 8px !important;
                background: #ef4444 !important;
                border-radius: 50% !important;
                border: 2px solid #fff !important;
            }

            /* Dark Mode Header Styles */
            html[data-theme="dark"] #admin-main-header {
                background-color: #1a1d23 !important;
                border-bottom-color: #2d3138 !important;
            }

            html[data-theme="dark"] #admin-search-box {
                background: #252932 !important;
                border-color: #2d3138 !important;
                }

            html[data-theme="dark"] #admin-search-box input {
                color: #e5e7eb !important;
            }

            html[data-theme="dark"] .header-icon-link:hover {
                background: #252932 !important;
            }

            /* Keep dropdown items black on click/hover */
            .dropdown-menu .dropdown-item {
                color: #000000 !important;
            }
            .dropdown-menu .dropdown-item:hover,
            .dropdown-menu .dropdown-item:focus,
            .dropdown-menu .dropdown-item:active {
                color: #000000 !important;
                background-color: #f3f4f6 !important;
            }
            .dropdown-menu .dropdown-item i {
                color: #000000 !important;
                }
            </style>

        <header class="c-header c-header-fixed admin-header" id="admin-main-header">
            <div id="admin-topbar-container">
                <form id="admin-search-box" action="{{ route('talent.projects.index') }}" method="GET" role="search">
                    <i class="fas fa-search"></i>
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Search shoots or locations..." aria-label="Search" />
                </form>
                <div id="admin-icons-group">
                    <a class="header-icon-link" href="#" aria-label="Add New" style="margin-left: 18px !important;">
                        <img src="{{ asset('images/plus.png') }}" alt="Add">
                    </a>
                    <a href="{{ Route::has('talent.settings.index') ? route('talent.settings.index') : '#' }}" class="header-icon-link" aria-label="Settings">
                        <img src="{{ asset('images/setting.png') }}" alt="Settings">
                    </a>
                    <div class="dropdown" style="display: flex !important; align-items: center !important; margin-left: 18px !important;">
            @php
                $talentUser = auth('talent')->user();
                            $unreadCount = $talentUser ? $talentUser->unreadNotifications->count() : 0;
                        @endphp
                        <a class="header-icon-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifications" style="margin-left: 0 !important; position: relative !important;">
                            <img src="{{ asset('images/noti.png') }}" alt="Notifications">
                            <span class="noti-badge {{ $unreadCount > 0 ? '' : 'd-none' }}"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pt-0" style="max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header bg-light py-2">
                                <strong>Notifications</strong>
                                @if($talentUser && $unreadCount > 0)
                                    <a href="#" class="float-right text-muted" style="font-size: 0.8em;">
                                        Mark all as read
                                    </a>
                                @endif
                            </div>
                            @if($talentUser)
                                @forelse($talentUser->notifications()->latest()->limit(10)->get() as $notification)
                                    <a class="dropdown-item {{ $notification->read_at ? 'text-muted' : 'font-weight-bold' }}"
                                       href="#">
                                        @if(isset($notification->data['type']) && $notification->data['type'] === 'talent_profile')
                                            <i class="fas fa-user text-info"></i>
                                        @elseif(isset($notification->data['type']) && $notification->data['type'] === 'casting_application')
                                            <i class="fas fa-video text-warning"></i>
                                        @else
                                            <i class="fas fa-bell text-secondary"></i>
                                        @endif
                                        {{ $notification->data['title'] ?? 'Notification' }}
                                        @if(isset($notification->data['message']))
                                            <div class="small text-muted">{{ $notification->data['message'] }}</div>
                                        @endif
                                        <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                    </a>
                                @empty
                                    <div class="dropdown-item text-center text-muted">
                                        No notifications
                                    </div>
                                @endforelse
                            @else
                                <div class="dropdown-item text-center text-muted">
                                    No notifications
                                </div>
                            @endif
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="#">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    @yield('scripts')
</body>

</html>
