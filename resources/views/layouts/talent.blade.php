<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
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
                            <span class="noti-badge {{ $unreadCount > 0 ? '' : 'd-none' }}" id="notification-badge">
                                @if($unreadCount > 0)
                                    <span class="badge-count">{{ $unreadCount }}</span>
                                @endif
                            </span>
                        </a>
                        <style>
                            .noti-badge {
                                position: absolute !important;
                                top: -2px !important;
                                right: -2px !important;
                                width: 18px !important;
                                height: 18px !important;
                                background: #ef4444 !important;
                                border-radius: 50% !important;
                                border: 2px solid #fff !important;
                                display: flex !important;
                                align-items: center !important;
                                justify-content: center !important;
                                font-size: 10px !important;
                                color: white !important;
                                font-weight: bold !important;
                            }
                            .dropdown-item.unread { background-color: #f9fafb; }
                            .mark-read-btn { 
                                padding: 2px 6px; 
                                font-size: 10px; 
                                border-radius: 4px; 
                                border: 1px solid #e5e7eb;
                                background: #fff;
                                color: #6b7280;
                                cursor: pointer;
                            }
                            .mark-read-btn:hover { background: #f3f4f6; }
                        </style>
                        <div class="dropdown-menu dropdown-menu-right pt-0" style="width: 320px; max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header bg-light d-flex justify-content-between align-items-center py-2">
                                <strong>Notifications</strong>
                                @if($talentUser && $unreadCount > 0)
                                    <a href="javascript:void(0)" onclick="markAllNotificationsAsRead()" class="text-muted" style="font-size: 0.8em;">
                                        Mark all as read
                                    </a>
                                @endif
                            </div>
                            <div id="notifications-list">
                                @if($talentUser)
                                    @forelse($talentUser->notifications()->latest()->limit(10)->get() as $notification)
                                        <div class="dropdown-item d-flex flex-column p-3 {{ $notification->read_at ? '' : 'unread' }}" id="notification-{{ $notification->id }}">
                                            <div class="d-flex justify-content-between align-items-start mb-1">
                                                <div class="d-flex align-items-center">
                                                    @if(isset($notification->data['type']) && $notification->data['type'] === 'talent_profile')
                                                        <i class="fas fa-user text-dark mr-2"></i>
                                                    @elseif(isset($notification->data['type']) && $notification->data['type'] === 'casting_application')
                                                        <i class="fas fa-video text-dark mr-2"></i>
                                                    @else
                                                        <i class="fas fa-bell text-dark mr-2"></i>
                                                    @endif
                                                    <span class="font-weight-bold" style="font-size: 13px;">{{ $notification->data['title'] ?? 'Notification' }}</span>
                                                </div>
                                                @if(!$notification->read_at)
                                                    <button onclick="markNotificationAsRead('{{ $notification->id }}')" class="mark-read-btn">Mark as read</button>
                                                @endif
                                            </div>
                                            <div class="small text-muted mb-1">{{ $notification->data['message'] ?? '' }}</div>
                                            <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                                        </div>
                                    @empty
                                        <div class="dropdown-item text-center text-muted py-3">
                                            No notifications
                                        </div>
                                    @endforelse
                                @else
                                    <div class="dropdown-item text-center text-muted py-3">
                                        No notifications
                                    </div>
                                @endif
                            </div>
                            <div class="dropdown-divider m-0"></div>
                            <a class="dropdown-item text-center py-2 font-weight-bold" href="{{ route('talent.notifications.index') }}" style="font-size: 12px; color: #000 !important;">
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
