<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show" style="background:#f9fafb; border-right:1px solid #edf0f3; box-shadow: 6px 0 18px rgba(15,23,42,0.05); transition: background 0.3s ease, border-color 0.3s ease;">

    <style>
        .bm-sidebar {
            padding: 18px 14px;
            background: #f9fafb;
        }
        .bm-brand {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 10px 0 14px;
            border-bottom: 1px solid #e8ebef;
            margin-bottom: 12px;
        }
        .bm-brand img { height: 36px; width: auto; }
        .bm-nav { list-style: none; padding: 0; margin: 0; }
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
        .c-sidebar-nav-item{
            margin-top:10px;
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
    </style>

    <div class="bm-sidebar">
        <div class="bm-brand">
            <a href="{{ route('talent.dashboard') }}" style="text-align:center; display:block;">
                <img src="{{ asset('images/bricks_logo.png') }}" alt="BRICKS Studio">
                <div style="color: #6A7282;
font-size: 14px;
font-style: normal;
font-weight: 400;
line-height: 30px; /* 142.857% */
letter-spacing: 1.4px;">STUDIO</div>
            </a>
        </div>

        <ul class="bm-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}" class="bm-link {{ request()->is('admin') ? 'c-active' : '' }}">
                <i class="fas fa-fw fa-th-large"></i>
                {{ trans('global.admin_dashboard') }}
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
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('project_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.projects.dashboard') }}" class="bm-link {{ request()->is('admin/projects') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-camera"></i>
                   Shoots
                </a>
            </li>
        @endif
        @if($adminUser)
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.profile.show') }}" class="bm-link {{ request()->is('admin/my-profile') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-user"></i>
                    My Profile
                </a>
            </li>
        @endif
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('talent_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.talents.dashboard') }}" class="bm-link {{ request()->is('admin/talents') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-users"></i>
                    {{ trans('global.talents_dashboard') }}
                </a>
            </li>
        @endif
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasModulePermission('payment_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.payments.dashboard') }}" class="bm-link {{ request()->is('admin/payments') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-credit-card"></i>
                    {{ trans('global.payment_dashboard') }}
                </a>
            </li>
        @endif
        @if($adminUser && $adminUser->isSuperAdmin())
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.payment-requests.index') }}" class="bm-link {{ request()->is('admin/payment-requests*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-hand-holding-usd"></i>
                    Payment Requests
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.admin-management.index') }}" class="bm-link {{ request()->is('admin/admin-management*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-user-shield"></i>
                    User Management
                </a>
            </li>
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.role-permissions.index') }}" class="bm-link {{ request()->is('admin/role-permissions*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-key"></i>
                    Role Permissions
                </a>
            </li>
        @endif
        @if($adminUser && ($adminUser->isSuperAdmin() || $adminUser->hasPermission('label_access')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.labels.index') }}" class="bm-link {{ request()->is('admin/labels*') ? 'c-active' : '' }}">
                    <i class="fas fa-fw fa-tags"></i>
                    {{ __('Labels') }}
                </a>
            </li>
        @endif
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
        <li class="c-sidebar-nav-item">
            <a href="#" class="bm-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
        </ul>
    </div>

</div>
