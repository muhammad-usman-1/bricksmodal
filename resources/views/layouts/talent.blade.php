<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}?v=1.1">
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}?v=1.1">
    <link rel="apple-touch-icon" href="{{ asset('images/favicon.png') }}?v=1.1">
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
                <form id="admin-search-box" action="" method="GET" role="search">
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
                            <span class="noti-badge {{ $unreadCount > 0 ? '' : 'd-none' }}" id="notification-badge">
                                @if($unreadCount > 0)
                                    <span class="badge-count">{{ $unreadCount }}</span>
                                @endif
                            </span>
                        </a>
                        <style>
                            .noti-badge {
                                position: absolute !important;
                                top: -5px !important;
                                right: -5px !important;
                                width: auto !important;
                                height: auto !important;
                                background: transparent !important;
                                border: none !important;
                                display: flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                font-size: 11px !important;
                                color: #000 !important;
                                font-weight: 800 !important;
                                z-index: 10 !important;
                                padding: 0 !important;
                            }
                            .dropdown-menu.notification-dropdown {
                                width: 340px !important;
                                border: 1px solid #eee !important;
                                border-radius: 12px !important;
                                box-shadow: 0 10px 25px rgba(0,0,0,0.1) !important;
                                padding: 0 !important;
                                border: none !important;
                                margin-top: 10px !important;
                            }
                            .notification-header {
                                padding: 16px 20px !important;
                                background-color: #f8f9fa !important;
                                border-bottom: 1px solid #f1f1f1 !important;
                                border-top-left-radius: 12px !important;
                                border-top-right-radius: 12px !important;
                            }
                            .notification-header h6 {
                                margin: 0 !important;
                                font-size: 15px !important;
                                font-weight: 700 !important;
                                color: #1a1a1a !important;
                            }
                            .mark-all-read {
                                font-size: 12px !important;
                                color: #6c757d !important;
                                text-decoration: none !important;
                            }
                            .mark-all-read:hover {
                                color: #000 !important;
                            }
                            .notification-item {
                                padding: 16px 20px !important;
                                border-bottom: 1px solid #f8f9fa !important;
                                transition: background-color 0.2s !important;
                                display: block !important;
                                text-decoration: none !important;
                                color: inherit !important;
                            }
                            .notification-item:hover {
                                background-color: #fcfcfc !important;
                                text-decoration: none !important;
                                color: inherit !important;
                            }
                            .notification-item.unread {
                                background-color: #fff !important;
                            }
                            .notification-item-top {
                                display: flex !important;
                                align-items: center !important;
                                justify-content: space-between !important;
                                margin-bottom: 4px !important;
                            }
                            .notification-icon-title {
                                display: flex !important;
                                align-items: center !important;
                                gap: 12px !important;
                            }
                            .notification-title {
                                font-size: 14px !important;
                                font-weight: 700 !important;
                                color: #000 !important;
                            }
                            .mark-read-mini {
                                font-size: 11px !important;
                                color: #6c757d !important;
                                border: 1px solid #eee !important;
                                padding: 2px 8px !important;
                                border-radius: 4px !important;
                                background: #fff !important;
                                cursor: pointer !important;
                                transition: all 0.2s !important;
                            }
                            .mark-read-mini:hover {
                                background: #000 !important;
                                color: #fff !important;
                                border-color: #000 !important;
                            }
                            .notification-msg {
                                font-size: 13px !important;
                                color: #6c757d !important;
                                margin-left: 28px !important;
                                margin-bottom: 4px !important;
                                line-height: 1.4 !important;
                            }
                            .notification-time {
                                font-size: 11px !important;
                                color: #adb5bd !important;
                                margin-left: 28px !important;
                            }
                            .view-all-footer {
                                padding: 14px !important;
                                text-align: left !important;
                                border-top: 1px solid #f1f1f1 !important;
                            }
                            .view-all-link {
                                font-size: 14px !important;
                                font-weight: 700 !important;
                                color: #000 !important;
                                text-decoration: none !important;
                            }
                            @media (max-width: 576px) {
                                .dropdown-menu.notification-dropdown {
                                    width: 300px !important;
                                    position: fixed !important;
                                    right: 10px !important;
                                    left: auto !important;
                                }
                            }
                        </style>
                        <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                            <div class="notification-header d-flex justify-content-between align-items-center">
                                <h6>Notifications</h6>
                                @if($talentUser && $unreadCount > 0)
                                    <a href="javascript:void(0)" onclick="markAllNotificationsAsRead()" class="mark-all-read">
                                        Mark all as read
                                    </a>
                                @endif
                            </div>
                            <div id="notifications-list" style="max-height: 380px; overflow-y: auto;">
                                @if($talentUser)
                                    @forelse($talentUser->notifications()->latest()->limit(5)->get() as $notification)
                                        <div class="notification-item {{ $notification->read_at ? '' : 'unread' }}" id="notification-{{ $notification->id }}">
                                            <div class="notification-item-top">
                                                <div class="notification-icon-title">
                                                    @php
                                                        $type = $notification->data['type'] ?? '';
                                                    @endphp
                                                    @if(str_contains($type, 'talent_profile') || str_contains($type, 'talent_signup'))
                                                        <i class="fas fa-user text-dark" style="font-size: 14px;"></i>
                                                    @elseif(str_contains($type, 'shoot'))
                                                        <i class="fas fa-video text-dark" style="font-size: 14px;"></i>
                                                    @elseif(str_contains($type, 'payment'))
                                                        <i class="fas fa-credit-card text-dark" style="font-size: 14px;"></i>
                                                    @elseif(str_contains($type, 'feedback'))
                                                        <i class="fas fa-star text-dark" style="font-size: 14px;"></i>
                                                    @else
                                                        <i class="fas fa-bell text-dark" style="font-size: 14px;"></i>
                                                    @endif
                                                    <span class="notification-title">{{ $notification->data['title'] ?? ($notification->data['subject'] ?? 'Notification') }}</span>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <button onclick="markNotificationAsRead('{{ $notification->id }}')" class="mark-read-mini">Mark as read</button>
                                                @endif
                                            </div>
                                            <div class="notification-msg">{{ $notification->data['message'] ?? ($notification->data['body'] ?? '') }}</div>
                                            <div class="notification-time">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    @empty
                                        <div class="p-4 text-center text-muted" style="font-size: 13px;">
                                            No notifications yet
                                        </div>
                                    @endforelse
                                @else
                                    <div class="p-4 text-center text-muted" style="font-size: 13px;">
                                        Please login to view notifications
                                    </div>
                                @endif
                            </div>
                            <div class="view-all-footer">
                                <a class="view-all-link" href="{{ route('talent.notifications.index') }}">
                                    View all notifications
                                </a>
                            </div>
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

    <script>
        function markNotificationAsRead(id) {
            $.ajax({
                url: '/talent/notifications/' + id + '/mark-as-read',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('#notification-' + id).removeClass('unread');
                        $('#notification-' + id + ' .mark-read-btn').remove();
                        updateBadgeCount();
                    }
                }
            });
        }

        function markAllNotificationsAsRead() {
            $.ajax({
                url: '{{ route('talent.notifications.mark-all-read') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        $('.dropdown-item.unread').removeClass('unread');
                        $('.mark-read-btn').remove();
                        $('#notification-badge').addClass('d-none');
                        $('.dropdown-header .text-muted').remove();
                    }
                }
            });
        }

        function updateBadgeCount() {
            let count = parseInt($('.badge-count').text()) - 1;
            if (count > 0) {
                $('.badge-count').text(count);
            } else {
                $('#notification-badge').addClass('d-none');
                $('.dropdown-header .text-muted').remove();
            }
        }
    </script>

    @yield('scripts')
</body>

</html>
