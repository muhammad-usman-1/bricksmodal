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

    #sidebarCollapseBtn {
        display: none !important;
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
width: auto;
height:auto;

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
        <button type="button" class="header-icon-link" id="sidebarCollapseBtn" aria-label="Show Sidebar" style="margin-left: 0 !important; margin-right: 12px !important; display: none;">
            <i class="fas fa-bars" style="font-size: 18px; color: #374151;"></i>
        </button>
        <form id="admin-search-box" action="{{ route('admin.search') }}" method="GET" role="search">
            <i class="fas fa-search"></i>
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by talent ID, name, or email..." aria-label="Search" id="admin-search-input" />
        </form>
        <script>
            // Handle instant redirect for numeric talent ID searches
            (function() {
                const searchInput = document.getElementById('admin-search-input');
                const searchForm = document.getElementById('admin-search-box');
                let searchTimeout;

                if (!searchInput || !searchForm) return;

                // Handle Enter key press
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        const query = this.value.trim();
                        
                        // If query is numeric, redirect directly to talent profile
                        if (query !== '' && /^\d+$/.test(query)) {
                            window.location.href = "{{ route('admin.talent-profiles.show', ':id') }}".replace(':id', query);
                            return;
                        }
                        
                        // Otherwise submit the form normally
                        searchForm.submit();
                    }
                });
            })();
        </script>
        <div id="admin-icons-group">
            <div class="dropdown" style="display: flex !important; align-items: center !important; margin-left: 18px !important;">
                <a class="header-icon-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Add New" style="margin-left: 0 !important;">
                    <img src="{{ asset('images/plus.png') }}" alt="Add">
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0" style="min-width: 180px;">
                    <a class="dropdown-item" href="{{ route('admin.talent-profiles.create') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                        <i class="fas fa-star" style="width: 16px; text-align: center;"></i>
                        <span>Add Talent</span>
                    </a>
                    <a class="dropdown-item" href="{{ route('admin.casting-requirements.create') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                        <i class="fas fa-camera" style="width: 16px; text-align: center;"></i>
                        <span>Add New Shoot</span>
                    </a>
                    @if(auth()->user()->isSuperAdmin())
                        <a class="dropdown-item" href="{{ route('admin.admin-management.create') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                            <i class="fas fa-user-circle" style="width: 16px; text-align: center;"></i>
                            <span>Add User</span>
                        </a>
                        <a class="dropdown-item" href="{{ route('admin.role-permissions.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                            <i class="fas fa-key" style="width: 16px; text-align: center;"></i>
                            <span>Add Permission</span>
                        </a>
                    @endif
                    <a class="dropdown-item" href="{{ route('admin.outfits.create') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                        <i class="fas fa-tshirt" style="width: 16px; text-align: center;"></i>
                        <span>Add Outfit</span>
                    </a>
                    @can('header_label_access')
                        <a class="dropdown-item" href="{{ route('admin.onboarding-labels.index') }}" style="display: flex; align-items: center; gap: 10px; padding: 10px 16px;">
                            <i class="fas fa-language" style="width: 16px; text-align: center;"></i>
                            <span>Add Arabic Labels</span>
                        </a>
                    @endcan
                </div>
            </div>
            @if(!auth()->user()->isCreative())
                <a href="{{ route('admin.settings.index') }}" class="header-icon-link" aria-label="Settings">
                    <img src="{{ asset('images/setting.png') }}" alt="Settings">
                </a>
            @endif
            <div class="dropdown" style="display: flex !important; align-items: center !important; margin-left: 18px !important;">
                @php
                    $adminUser = auth()->user();
                    $adminUnreadCount = $adminUser ? $adminUser->unreadNotifications->count() : 0;
                @endphp
                <a class="header-icon-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifications" style="margin-left: 0 !important; position: relative !important;">
                    <img src="{{ asset('images/noti.png') }}" alt="Notifications">
                    <span class="noti-badge {{ $adminUnreadCount > 0 ? '' : 'd-none' }}" id="notification-badge">
                        @if($adminUnreadCount > 0)
                            <span class="badge-count">{{ $adminUnreadCount }}</span>
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
                    .p-4 {
                        padding: 1.5rem !important;
                    }
                    .dropdown-menu.notification-dropdown {
                        width: 340px !important;
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
                </style>
                <div class="dropdown-menu dropdown-menu-right notification-dropdown">
                    <div class="notification-header d-flex justify-content-between align-items-center">
                        <h6>Notifications</h6>
                        @if($adminUser && $adminUnreadCount > 0)
                            <a href="javascript:void(0)" onclick="markAllAdminNotificationsAsRead()" class="mark-all-read">
                                Mark all as read
                            </a>
                        @endif
                    </div>
                    <div id="notifications-list" style="max-height: 380px; overflow-y: scroll; overflow-x: hidden; scrollbar-width: none; -ms-overflow-style: none;">
                        <style>
                            #notifications-list::-webkit-scrollbar {
                                display: none;
                            }
                        </style>
                        @if($adminUser)
                            @forelse($adminUser->notifications()->latest()->limit(5)->get() as $notification)
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
                                            <div class="d-flex flex-column">
                                                <span class="notification-title">
                                                    {{ $notification->data['title_en'] ?? ($notification->data['title'] ?? ($notification->data['subject'] ?? 'Notification')) }}
                                                </span>
                                                @if(!empty($notification->data['title_ar']))
                                                    <span class="notification-title" style="direction: rtl; font-family: 'Tajawal', sans-serif;">{{ $notification->data['title_ar'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if(!$notification->read_at)
                                            <button onclick="markAdminNotificationAsRead('{{ $notification->id }}')" class="mark-read-mini">Mark as read</button>
                                        @endif
                                    </div>
                                    <div class="notification-msg">
                                        {{ $notification->data['message_en'] ?? ($notification->data['message'] ?? ($notification->data['body'] ?? '')) }}
                                        @if(!empty($notification->data['message_ar']))
                                            <div style="direction: rtl; font-family: 'Tajawal', sans-serif; margin-top: 2px;">
                                                {{ $notification->data['message_ar'] }}
                                            </div>
                                        @endif
                                    </div>
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
                        <a class="view-all-link" href="{{ route('admin.notifications.index') }}">
                            View all notifications
                        </a>
                        @if(auth()->user()->isSuperAdmin())
                            <div class="dropdown-divider mt-2 mb-2"></div>
                            <a class="view-all-link d-block" href="{{ route('admin.notification-templates.index') }}" style="font-size: 13px;">
                                Manage Notifications
                            </a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
</header>
<script>
    // Sidebar uncollapse functionality (collapse is now handled by footer button)
    (function() {
        const sidebar = document.getElementById('sidebar');
        const collapseBtn = document.getElementById('sidebarCollapseBtn');
        const collapseIcon = collapseBtn?.querySelector('i');
        const wrapper = document.querySelector('.c-wrapper');

        if (!sidebar || !collapseBtn) return;

        // Function to adjust main content
        const adjustMainContent = (collapsed) => {
            if (wrapper) {
                if (collapsed) {
                    wrapper.style.marginLeft = '70px';
                } else {
                    wrapper.style.marginLeft = '';
                }
            }
        };

        // Function to update button visibility
        const updateButtonVisibility = (collapsed) => {
            if (collapseBtn) {
                collapseBtn.style.display = collapsed ? 'inline-flex' : 'none';
            }
        };

        const syncFromState = () => {
            const collapsed = sidebar.classList.contains('collapsed');
            if (collapsed) {
                document.body.classList.add('sidebar-collapsed');
            } else {
                document.body.classList.remove('sidebar-collapsed');
            }
            updateButtonVisibility(collapsed);
            adjustMainContent(collapsed);
        };

        // Check localStorage for saved state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
        } else {
            sidebar.classList.remove('collapsed');
        }
        syncFromState();

        // Only handle uncollapse - collapse is now handled by footer button
        collapseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Only uncollapse, never collapse
            sidebar.classList.remove('collapsed');
            syncFromState();

            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', 'false');

            // Notify other listeners of expanded state
            window.dispatchEvent(new CustomEvent('sidebar-collapsed', { detail: { collapsed: false } }));
        });

        // Listen for collapse events from footer button
        window.addEventListener('sidebar-collapsed', function(e) {
            const collapsed = e.detail.collapsed;
            if (collapsed) {
                sidebar.classList.add('collapsed');
            } else {
                sidebar.classList.remove('collapsed');
            }
            syncFromState();
        });

        // Also react to class changes triggered elsewhere
        const observer = new MutationObserver(syncFromState);
        observer.observe(sidebar, { attributes: true, attributeFilter: ['class'] });
    })();
</script>

