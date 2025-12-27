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
        color: #9ca3af !important;
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

    .header-icon-link:first-child {
        margin-left: 0 !important;
    }

    .header-icon-link:hover {
        background: #f3f4f6 !important;
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
</style>

<header class="c-header c-header-fixed admin-header" id="admin-main-header">
    <div id="admin-topbar-container">
        <button type="button" class="header-icon-link" id="sidebarCollapseBtn" aria-label="Toggle Sidebar" style="margin-left: 0 !important; margin-right: 12px !important;">
            <i class="fas fa-bars" style="font-size: 18px; color: #374151;"></i>
        </button>
        <div id="admin-search-box">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Search talents, shoots, or campaigns..." aria-label="Search" />
        </div>
        <div id="admin-icons-group">
            <a href="{{ route('admin.outfits.index') }}" class="header-icon-link" aria-label="Add New">
                <img src="{{ asset('images/plus.png') }}" alt="Add">
            </a>
            <a href="{{ route('admin.settings.index') }}" class="header-icon-link" aria-label="Settings">
                <img src="{{ asset('images/setting.png') }}" alt="Settings">
            </a>
            <div class="dropdown" style="display: flex !important; align-items: center !important; margin-left: 18px !important;">
                <a class="header-icon-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifications" style="margin-left: 0 !important; position: relative !important;">
                    <img src="{{ asset('images/noti.png') }}" alt="Notifications">
                    <span class="noti-badge {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'd-none' }}"></span>
                </a>
                <div class="dropdown-menu dropdown-menu-right pt-0" style="max-height: 400px; overflow-y: auto;">
                    <div class="dropdown-header bg-light py-2">
                        <strong>Notifications</strong>
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <a href="{{ route('admin.notifications.mark-all-read') }}" class="float-right text-muted" style="font-size: 0.8em;">
                                Mark all as read
                            </a>
                        @endif
                    </div>
                    @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $notification)
                        <a class="dropdown-item {{ $notification->read_at ? 'text-muted' : 'font-weight-bold' }}"
                           href="{{ route('admin.notifications.show', $notification->id) }}">
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
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item text-center" href="{{ route('admin.notifications.index') }}">
                        View all notifications
                    </a>
                </div>
            </div>
        </div>
    </div>
</header>

<script>
    // Sidebar collapse functionality
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
                    wrapper.style.marginLeft = '0';
                } else {
                    wrapper.style.marginLeft = '';
                }
            }
        };

        // Check localStorage for saved state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            adjustMainContent(true);
            // Set initial icon state
            if (collapseIcon) {
                collapseIcon.classList.remove('fa-bars');
                collapseIcon.classList.add('fa-chevron-right');
            }
        }

        collapseBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            sidebar.classList.toggle('collapsed');
            const collapsed = sidebar.classList.contains('collapsed');

            // Adjust main content
            adjustMainContent(collapsed);

            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');

            // Update icon
            if (collapseIcon) {
                if (collapsed) {
                    collapseIcon.classList.remove('fa-bars');
                    collapseIcon.classList.add('fa-chevron-right');
                } else {
                    collapseIcon.classList.remove('fa-chevron-right');
                    collapseIcon.classList.add('fa-bars');
                }
            }

            // Update aria-label
            collapseBtn.setAttribute('aria-label', collapsed ? 'Show Sidebar' : 'Hide Sidebar');
        });
    })();
</script>
