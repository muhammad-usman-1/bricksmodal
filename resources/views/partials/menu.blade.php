<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

     <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="{{ route('talent.dashboard') }}">
            <img src="{{ asset('storage/bricks_logo.png') }}" alt="Logo" style="height: 27px; width: auto;">
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.home') }}" class="c-sidebar-nav-link {{ request()->is('admin') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt">

                </i>
                {{ trans('global.admin_dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.notifications.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/notifications*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-bell"></i>
                {{ trans('global.notifications') ?? 'Notifications' }}
                @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
                    <span class="badge badge-danger ml-auto" style="margin-left:8px">{{ auth()->user()->unreadNotifications->count() }}</span>
                @endif
            </a>
        </li>
        @if(auth('admin')->check() && (auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->hasModulePermission('project_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.projects.dashboard') }}" class="c-sidebar-nav-link {{ request()->is('admin/projects') ? 'c-active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-project-diagram">

                    </i>
                    {{ trans('global.projects_dashboard') }}
                </a>
            </li>
        @endif
        @if(auth('admin')->check() && (auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->hasModulePermission('talent_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.talents.dashboard') }}" class="c-sidebar-nav-link {{ request()->is('admin/talents') ? 'c-active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user-friends">

                    </i>
                    {{ trans('global.talents_dashboard') }}
                </a>
            </li>
        @endif
        @if(auth('admin')->check() && (auth('admin')->user()->isSuperAdmin() || auth('admin')->user()->hasModulePermission('payment_management')))
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.payments.dashboard') }}" class="c-sidebar-nav-link {{ request()->is('admin/payments') ? 'c-active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-wallet">

                    </i>
                    {{ trans('global.payment_dashboard') }}
                </a>
            </li>
        @endif
        @if(auth('admin')->check() && auth('admin')->user()->isSuperAdmin())
            <li class="c-sidebar-nav-item">
                <a href="{{ route('admin.admin-management.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/admin-management*') ? 'c-active' : '' }}">
                    <i class="c-sidebar-nav-icon fas fa-fw fa-user-shield">

                    </i>
                    Admin Management
                </a>
            </li>
        @endif
        <li class="c-sidebar-nav-item">
            <a href="{{ route('admin.settings.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/settings') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-cog">

                </i>
                {{ trans('global.settings') }}
            </a>
        </li>
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
        @can('talent_profile_access')
            <li class="c-sidebar-nav-item">
                <a href="{{ route("admin.talent-profiles.index") }}" class="c-sidebar-nav-link {{ request()->is("admin/talent-profiles") || request()->is("admin/talent-profiles/*") ? "c-active" : "" }}">
                    <i class="fa-fw fas fa-cogs c-sidebar-nav-icon">

                    </i>
                    {{ trans('cruds.talentProfile.title') }}
                </a>
            </li>
        @endcan
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
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt">

                </i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
