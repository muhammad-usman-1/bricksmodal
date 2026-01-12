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

        /* Keep footer visible when collapsed so the expand button lives there */
        #sidebar.collapsed .bm-footer {
            display: block !important;
        }

        /* Collapse footer content to just the toggle button */
        #sidebar.collapsed .bm-footer-user,
        #sidebar.collapsed .bm-footer-dropdown {
            display: none !important;
        }

        #sidebar.collapsed .bm-footer-card {
            justify-content: center !important;
            padding: 0px 0 !important;
        }

        #sidebar.collapsed .bm-footer-arrow {
            margin: 0 !important;
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
                <a href="{{ route('talent.dashboard') }}" class="bm-link {{ request()->routeIs('talent.dashboard') ? 'c-active' : '' }}">
                    <img src="{{ asset('images/dashboard.png') }}" alt="Dashboard" style="width: 16px; height: 16px; object-fit: contain;">
                    Dashboard
            </a>
        </li>
        <li class="c-sidebar-nav-item">
                <a href="{{ Route::has('talent.projects.index') ? route('talent.projects.index') : '#' }}" class="bm-link {{ request()->routeIs('talent.projects.*') ? 'c-active' : '' }}">
                    <img src="{{ asset('images/camera.png') }}" alt="My Shoots" style="width: 16px; height: 16px; object-fit: contain;">
                    My Shoots
            </a>
        </li>
        <li class="c-sidebar-nav-item">
                <a href="{{ route('talent.profile.show') }}" class="bm-link {{ request()->routeIs('talent.profile.*') ? 'c-active' : '' }}">
                    <img src="{{ asset('images/user.png') }}" alt="My Profile" style="width: 16px; height: 16px; object-fit: contain;">
                    My Profile
            </a>
        </li>
        <li class="c-sidebar-nav-item">
                <a href="{{ Route::has('talent.payments.index') ? route('talent.payments.index') : '#' }}" class="bm-link {{ request()->routeIs('talent.payments.*') ? 'c-active' : '' }}">
                    <img src="{{ asset('images/payment.png') }}" alt="My Payment" style="width: 16px; height: 16px; object-fit: contain;">
                    My Payment
            </a>
        </li>
    </ul>

        @php
            $talentUser = auth('talent')->user();
            if ($talentUser) {
                $name = $talentUser->name ?? 'Talent User';
                $profile = $talentUser->talentProfile;
                $roleLabel = $profile ? 'Talent' : 'Talent';
                $initials = collect(explode(' ', $name))->map(fn($p) => substr($p,0,1))->implode('');
            }
        @endphp

        @if($talentUser)
            <div class="bm-footer" style="position: relative;">
                <div class="bm-footer-card">
                    <div class="bm-footer-user" id="bm-footer-user" style="cursor: pointer;">
                        <div class="bm-footer-avatar">{{ $initials }}</div>
                        <div class="bm-footer-meta">
                            <p class="bm-footer-name">{{ $name }}</p>
                            <p class="bm-footer-role">{{ ucfirst($roleLabel) }}</p>
                        </div>
                    </div>
                    <button type="button" class="bm-footer-arrow" id="bm-sidebar-toggle" aria-label="Collapse sidebar">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                </div>

                <div class="bm-footer-dropdown" id="bm-footer-dropdown">
                    <a href="{{ route('talent.profile.show') }}"><i class="fas fa-user"></i> Profile</a>
                    <a href="{{ Route::has('talent.settings.index') ? route('talent.settings.index') : '#' }}"><i class="fas fa-cog"></i> Settings</a>
                    <button type="button" onclick="event.preventDefault(); document.getElementById('talent-logout-form').submit();"><i class="fas fa-sign-out-alt"></i> Logout</button>
                </div>
            </div>
        @endif
    </div>

</div>

<script>
    (function() {
        const toggleBtn = document.getElementById('bm-sidebar-toggle');
        const sidebar = document.getElementById('sidebar');
        const footerUser = document.getElementById('bm-footer-user');
        const footerDropdown = document.getElementById('bm-footer-dropdown');

        const wrapper = document.querySelector('.c-wrapper');
        if (!toggleBtn || !sidebar) return;

        const toggleIcon = toggleBtn.querySelector('i');

        const syncToggleUi = (collapsed) => {
            if (toggleIcon) {
                toggleIcon.classList.toggle('fa-chevron-right', collapsed);
                toggleIcon.classList.toggle('fa-chevron-left', !collapsed);
            }
            toggleBtn.setAttribute('aria-label', collapsed ? 'Expand sidebar' : 'Collapse sidebar');
        };

        const setCollapsed = (collapsed) => {
            sidebar.classList.toggle('collapsed', collapsed);
            if (wrapper) wrapper.style.marginLeft = collapsed ? '70px' : '';
            localStorage.setItem('sidebarCollapsed', collapsed ? 'true' : 'false');
            syncToggleUi(collapsed);
            window.dispatchEvent(new CustomEvent('sidebar-collapsed', { detail: { collapsed } }));
        };

        // initial state
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        setCollapsed(isCollapsed);

        toggleBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const collapsed = sidebar.classList.contains('collapsed');
            setCollapsed(!collapsed);
        });

        if (footerUser && footerDropdown) {
            const closeDropdown = () => footerDropdown.classList.remove('show');
            const toggleDropdown = (e) => {
                e.stopPropagation();
                footerDropdown.classList.toggle('show');
            };

            footerUser.addEventListener('click', toggleDropdown);

            document.addEventListener('click', (e) => {
                if (!footerDropdown.contains(e.target) && !footerUser.contains(e.target)) {
                    closeDropdown();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') closeDropdown();
            });
        }
    })();

    function toggleBmDropdown(id) {
        const el = document.getElementById(id);
        if (el) {
            el.classList.toggle('show');
        }
    }

</script>
