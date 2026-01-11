<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show" style="background:#f9fafb; border-right:1px solid #edf0f3; box-shadow: 6px 0 18px rgba(15,23,42,0.05); transition: background 0.3s ease, border-color 0.3s ease;">

    <style>
        .bm-sidebar {
            padding: 18px 14px;
            background: #f9fafb;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .bm-brand {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px 0 14px;
            border-bottom: 1px solid #e8ebef;
            margin-bottom: 12px;
            gap: 4px;
        }
        .bm-brand img { height: 36px; width: auto; transition: opacity 0.3s ease; }
        
        /* Collapsed state - show only icons */
        #sidebar.collapsed {
            width: 70px !important;
            transition: width 0.3s ease;
        }
        
        #sidebar.collapsed .bm-sidebar {
            padding: 18px 8px;
            align-items: center;
        }
        
        /* Hide brand/logo section completely */
        #sidebar.collapsed .bm-brand {
            display: none !important;
        }
        
        /* Hide footer completely */
        #sidebar.collapsed .bm-footer {
            display: none !important;
        }
        
        /* Style regular links - show only icons */
        #sidebar.collapsed .bm-link {
            justify-content: center !important;
            align-items: center !important;
            padding: 10px !important;
            position: relative;
            gap: 0 !important;
            min-width: 54px !important;
            width: 54px !important;
            font-size: 0 !important;
            line-height: 0 !important;
            margin: 0 auto !important;
            display: flex !important;
        }
        
        /* Hide all text and non-icon elements in links */
        #sidebar.collapsed .bm-link > *:not(img):not(i) {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
            font-size: 0 !important;
        }
        
        /* Restore font size for icons */
        #sidebar.collapsed .bm-link img,
        #sidebar.collapsed .bm-link i {
            font-size: initial;
        }
        
        /* Show and style icons/images */
        #sidebar.collapsed .bm-link img,
        #sidebar.collapsed .bm-link i {
            margin: 0 !important;
            flex-shrink: 0;
            display: block !important;
        }
        
        #sidebar.collapsed .bm-link img {
            width: 20px !important;
            height: 20px !important;
            object-fit: contain;
        }
        
        #sidebar.collapsed .bm-link i {
            font-size: 18px !important;
        }
        
        /* Style dropdown toggles - show only icons */
        #sidebar.collapsed .bm-link-dropdown-toggle {
            justify-content: center !important;
            align-items: center !important;
            padding: 10px !important;
            gap: 0 !important;
            min-width: 54px !important;
            width: 54px !important;
            font-size: 0 !important;
            line-height: 0 !important;
            margin: 0 auto !important;
        }
        
        /* Hide dropdown arrow and other non-icon elements */
        #sidebar.collapsed .bm-link-dropdown-toggle > *:not(span) {
            display: none !important;
        }
        
        #sidebar.collapsed .bm-link-dropdown-toggle > span {
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }
        
        /* Hide text in span, show only image */
        #sidebar.collapsed .bm-link-dropdown-toggle > span > *:not(img) {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            width: 0 !important;
            height: 0 !important;
            overflow: hidden !important;
        }
        
        /* Restore font size and style for icons in dropdown */
        #sidebar.collapsed .bm-link-dropdown-toggle > span > img {
            margin: 0 !important;
            width: 20px !important;
            height: 20px !important;
            object-fit: contain !important;
            display: block !important;
            flex-shrink: 0 !important;
            font-size: initial !important;
        }
        
        /* Hide dropdown arrow */
        #sidebar.collapsed .bm-dropdown-arrow {
            display: none !important;
        }
        
        /* Hide dropdown items */
        #sidebar.collapsed .bm-dropdown-items {
            display: none !important;
        }
        
        /* Hide sub-links */
        #sidebar.collapsed .bm-sub-link {
            display: none !important;
        }
        
        /* Center nav items */
        #sidebar.collapsed .c-sidebar-nav-item {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%;
            margin: 0;
        }
        
        /* Ensure nav list is centered */
        #sidebar.collapsed .bm-nav {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        
        /* Align dropdown container */
        #sidebar.collapsed .bm-nav-dropdown {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Ensure all links have consistent alignment */
        #sidebar.collapsed .bm-link,
        #sidebar.collapsed .bm-link-dropdown-toggle {
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Adjust main content when sidebar is collapsed */
        @media (min-width: 992px) {
            body:has(#sidebar.collapsed) .c-wrapper,
            #sidebar.collapsed ~ .c-wrapper {
                margin-left: 70px !important;
            }
        }
        .bm-nav { list-style: none; padding: 0; margin: 0; flex: 1; }
        .bm-item { margin-bottom: 6px; }
        .bm-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .bm-link:hover { background: #eef1f6; color: #0f172a;   text-decoration: none;}
        .bm-link i { color: #374151; width: 16px; text-align: center; }
        .bm-link.c-active {
            background: #11141a;
            color: #fff;
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }
        .bm-link.c-active i { color: #fff; }
        .bm-link.c-active img {
            filter: brightness(0) invert(1);
        }
        .c-sidebar-nav-item{
            margin-top:10px;
        }

        .bm-footer {
            margin-top: auto;
            padding-top: 14px;
            border-top: 1px solid #e8ebef;
        }

        .bm-footer-card {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #fff;
            border: 1px solid #edf0f3;
            border-radius: 12px;
            padding: 10px 12px;
            box-shadow: 0 8px 18px rgba(15,23,42,0.06);
        }

        .bm-footer-user {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .bm-footer-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: #0f0f11;
            color: #fff;
            display: grid;
            place-items: center;
            font-weight: 700;
            font-size: 13px;
        }

        .bm-footer-meta {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .bm-footer-name {
            color: #0f172a;
            font-weight: 700;
            font-size: 13px;
            margin: 0;
        }

        .bm-footer-role {
            color: #6b7280;
            font-size: 11px;
            margin: 0;
        }

        .bm-footer-arrow {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            background: #f3f4f6;
            display: grid;
            place-items: center;
            color: #4b5563;
            cursor: pointer;
            transition: transform 0.15s ease;
        }

        .bm-footer-dropdown {
            position: absolute;
            right: 10px;
            bottom: 70px;
            width: 180px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 18px 32px rgba(15,23,42,0.12);
            padding: 8px 0;
            display: none;
            z-index: 20;
        }

        .bm-footer-dropdown.show { display: block; }

        .bm-footer-dropdown a,
        .bm-footer-dropdown button {
            width: 100%;
            border: none;
            background: transparent;
            padding: 10px 14px;
            display: flex;
            align-items: center;
            gap: 10px;
            color: #111827;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
        }

        .bm-footer-dropdown a:hover,
        .bm-footer-dropdown button:hover {
            background: #f3f4f6;
            text-decoration: none;
        }

        .bm-footer-dropdown i {
            width: 16px;
            text-align: center;
            color: #6b7280;
        }

        /* Dark theme styles for sidebar */
        html[data-theme="dark"] #sidebar {
            background: #1a1d23 !important;
            border-right-color: #2d3138 !important;
        }

        html[data-theme="dark"] .bm-sidebar {
            background: #1a1d23;
        }

        html[data-theme="dark"] .bm-brand {
            border-bottom-color: #2d3138;
        }

        html[data-theme="dark"] .bm-brand div {
            color: #9ca3af;
        }

        html[data-theme="dark"] .bm-link {
            color: #d1d5db;
        }

        html[data-theme="dark"] .bm-link:hover {
            background: #252932;
            color: #f3f4f6;
            text-decoration: none;
        }

        html[data-theme="dark"] .bm-link i {
            color: #9ca3af;
        }

        html[data-theme="dark"] .bm-link.c-active {
            background: #3b82f6;
            color: #fff;
            box-shadow: 0 6px 14px rgba(59, 130, 246, 0.3);
        }

        html[data-theme="dark"] .bm-link.c-active i {
            color: #fff;
        }

        /* Shoots Dropdown Styles */
        .bm-link-dropdown-toggle {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 10px;
            color: #0f172a;
            font-weight: 600;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            width: 100%;
            border: none;
            transition: all 0.2s ease;
        }
        .bm-link-dropdown-toggle:hover { background: #eef1f6; color: #0f172a; }
        
        .bm-link-dropdown-toggle.c-active {
            background: #11141a;
            color: #fff;
            box-shadow: 0 6px 14px rgba(0,0,0,0.12);
        }
        .bm-link-dropdown-toggle.c-active img { filter: brightness(0) invert(1); }
        .bm-link-dropdown-toggle.c-active:hover { background: #11141a; color: #fff; }

        .bm-dropdown-arrow { transition: transform 0.2s; font-size: 10px; }
        .bm-nav-dropdown.show .bm-dropdown-arrow { transform: rotate(180deg); }

        .bm-dropdown-items {
            display: none;
            list-style: none;
            padding: 5px;
            margin: 8px 0 0 0;
            background: #fff;
            border: 1px solid #eef0f3;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .bm-nav-dropdown.show .bm-dropdown-items { display: block; }
        
        .bm-sub-link {
            display: block;
            padding: 8px 12px;
            color: #111827;
            font-size: 13px;
            text-decoration: underline;
            text-decoration-color: #d1d5db;
            text-underline-offset: 4px;
            font-weight: 500;
        }
        .bm-sub-link:hover { color: #000; text-decoration-color: #000; }

       

    </style>

    <div class="bm-sidebar">
        <div class="bm-brand">
            <a href="{{ route('talent.dashboard') }}" style="text-align:center; display:block;">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Studio">
            </a>
            <div style="color: #6A7282;
            margin-top: 10px;
font-size: 14px;
font-style: normal;
font-weight: 400;
line-height: 18px;
letter-spacing: 1.4px;">STUDIO</div>
        </div>

        <ul class="bm-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}" class="bm-link {{ request()->is('admin') ? 'c-active' : '' }}">
                <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard" style="width: 16px; height: 16px; object-fit: contain;">
                Dashboard
            </a>
        </li>
        {{--  <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.notifications.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/notifications*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-bell"></i>
                {{ trans('global.notifications') ?? 'Notifications' }}
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-danger ml-auto" style="margin-left:8px">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
        </li>  --}}
        @php
            $adminUser = auth('admin')->user();
            if ($adminUser && !$adminUser->relationLoaded('roles')) {
                $adminUser->load('roles.permissions');
            }
        @endphp

        {{--  @if($adminUser)
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.profile.show') }}" class="bm-link {{ request()->is('admin/my-profile') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-user"></i>
                    My Profile
                </a>
            </li>
        @endif  --}}
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('talent_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.talents.dashboard') }}" class="bm-link {{ (request()->is('admin/talents*') || request()->is('admin/talent-profiles*') || request()->routeIs('admin.talents.*') || request()->routeIs('admin.talent-profiles.*')) ? 'c-active' : '' }}">
                    <img src="{{ asset('images/talent.png') }}" alt="Talents" style="width: 16px; height: 16px; object-fit: contain;">
                    {{ trans('global.talents_dashboard') }}
                </a>
            </li>
        @endif
             @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('project_management')))
            <li class="c-sidebar-nav-item">
                <div class="bm-nav-dropdown {{ (request()->is('admin/projects*') || request()->is('admin/casting-requirements*') || request()->routeIs('admin.projects.*') || request()->routeIs('admin.casting-requirements.*')) ? 'show' : '' }}">
                    <a href="{{ route('admin.projects.dashboard') }}" class="bm-link-dropdown-toggle {{ (request()->is('admin/projects*') || request()->is('admin/casting-requirements*') || request()->routeIs('admin.projects.*') || request()->routeIs('admin.casting-requirements.*')) ? 'c-active' : '' }}" style="text-decoration:none;">
                        <span style="display:flex; align-items:center; gap:10px;">
                            <img src="{{ asset('images/camera.png') }}" alt="Shoots" style="width: 16px; height: 16px; object-fit: contain;">
                            Shoots
                        </span>
                        <i class="fas fa-chevron-down bm-dropdown-arrow" onclick="event.preventDefault(); this.closest('.bm-nav-dropdown').classList.toggle('show');" style="cursor:pointer; padding:6px;"></i>
                    </a>
                    <ul class="bm-dropdown-items">
                        <li>
                            <a href="{{ route('admin.projects.progress') }}" class="bm-sub-link {{ request()->routeIs('admin.projects.progress') ? 'active-sub' : '' }}">
                                Shoots Progress
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        @endif
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('payment_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.payments.dashboard') }}" class="bm-link {{ (request()->is('admin/payments*') || request()->is('admin/payment-requests*') || request()->routeIs('admin.payments.*') || request()->routeIs('admin.payment-requests.*')) ? 'c-active' : '' }}">
                    <img src="{{ asset('images/payment.png') }}" alt="Payments" style="width: 16px; height: 16px; object-fit: contain;">
                    Payments
                </a>
            </li>
        @endif
        @if($adminUser && $adminUser->isSuperAdmin())

            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.admin-management.index') }}" class="bm-link {{ request()->is('admin/admin-management*') ? 'c-active' : '' }}">
                    <img src="{{ asset('images/user.png') }}" alt="User Management" style="width: 16px; height: 16px; object-fit: contain;">
                    User Management
                </a>
            </li>
            {{--  <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.role-permissions.index') }}" class="bm-link {{ request()->is('admin/role-permissions*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-key"></i>
                    Role Permissions
                </a>
            </li>  --}}
        @endif
        {{--  @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasPermission('label_access')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.labels.index') }}" class="bm-link {{ request()->is('admin/labels*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-tags"></i>
                    {{ __('Labels') }}
                </a>
            </li>
        @endif  --}}
        {{--  @can('user_management_access')
            <li class="c-sidebar-nav-dropdown {{ request()->is('admin/permissions*') ? 'c-show' : '' }} {{ request()->is('admin/roles*') ? 'c-show' : '' }} {{ request()->is('admin/users*') ? 'c-show' : '' }}">
                <a class="c-sidebar-nav-dropdown-toggle" href="#">
                    <i class="fa-fw fas fa-users c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.userManagement.title') }}
                </a>
                <ul class="c-sidebar-nav-dropdown-items">
                    @can('permission_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.permissions.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/permissions') || request()->is('admin/permissions/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-unlock-alt c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.permission.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('role_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.roles.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/roles') || request()->is('admin/roles/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-briefcase c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.role.title') }}
                            </a>
                        </li>
                    @endcan
                    @can('user_access')
                        <li class="c-sidebar-nav-item">
                            <a href="{{ route('admin.users.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/users') || request()->is('admin/users/*') ? 'c-active' : '' }}">
                                <i class="fa-fw fas fa-user c-sidebar-nav-icon">

                                </i>
                                {{ trans('cruds.user.title') }}
                            </a>
                        </li>
                    @endcan
                </ul>
            </li>
        @endcan  --}}
        {{--  @can('talent_management_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.talent-profiles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/talent-profiles") || request()->is("admin/talent-profiles/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.talentProfile.title') }}
                </a>
            </li>
        @endcan  --}}
        {{--  @can('language_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.languages.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/languages") || request()->is("admin/languages/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.language.title') }}
                </a>
            </li>
        @endcan  --}}
        {{--  @can('casting_requirement_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.casting-requirements.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/casting-requirements") || request()->is("admin/casting-requirements/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.castingRequirement.title') }}
                </a>
            </li>
        @endcan  --}}
        {{--  @can('casting_application_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.casting-applications.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/casting-applications") || request()->is("admin/casting-applications/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.castingApplication.title') }}
                </a>
            </li>
        @endcan  --}}
        {{--  @can('bank_detail_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.bank-details.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/bank-details") || request()->is("admin/bank-details/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.bankDetail.title') }}
                </a>
            </li>
        @endcan  --}}
        {{--  @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
            @can('profile_password_edit')
                <li class="c-sidebar-nav-item">
                    <a class="c-sidebar-nav-link {{ request()->is('profile/password') || request()->is('profile/password/*') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                        <i class="fa-fw fas fa-key c-sidebar-nav-icon">
                        </i>
                        {{ trans('global.change_password') }}
                    </a>
                </li>
            @endcan
        @endif  --}}
        {{--  <li class="c-sidebar-nav-item">
            <a href="#" class="bm-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>  --}}
        </ul>

        @if($adminUser)
            @php
                $name = $adminUser->name ?? 'Admin User';
                $roleLabel = $adminUser->roles->first()->title ?? 'Admin';
                $initials = collect(explode(' ', $name))->map(fn($p) => substr($p,0,1))->implode('');
            @endphp
            <div class="bm-footer" style="position: relative;">
                <div class="bm-footer-card">
                    <div class="bm-footer-user" style="cursor: pointer;" onclick="window.location.href='{{ route('admin.profile.show') }}'">
                        <div class="bm-footer-avatar">{{ $initials }}</div>
                        <div class="bm-footer-meta">
                            <p class="bm-footer-name">{{ $name }}</p>
                            <p class="bm-footer-role">{{ ucfirst($roleLabel) }}</p>
                        </div>
                    </div>
                    <button type="button" class="bm-footer-arrow" id="bm-sidebar-collapse">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>


            </div>
        @endif
    </div>

</div>

<script>
    (function() {
        const collapseBtn = document.getElementById('bm-sidebar-collapse');
        const sidebar = document.getElementById('sidebar');
        if (!collapseBtn || !sidebar) return;

        collapseBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            sidebar.classList.add('collapsed');
            
            // Adjust main content
            const wrapper = document.querySelector('.c-wrapper');
            if (wrapper) {
                wrapper.style.marginLeft = '70px';
            }
            
            // Save state to localStorage
            localStorage.setItem('sidebarCollapsed', 'true');
            
            // Notify header to update button visibility
            window.dispatchEvent(new CustomEvent('sidebar-collapsed', { detail: { collapsed: true } }));
        });
    })();

    function toggleBmDropdown(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('show');
        }
    }

</script>
