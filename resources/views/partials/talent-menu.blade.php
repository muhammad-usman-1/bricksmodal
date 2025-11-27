<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">

    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="{{ route('talent.dashboard') }}">
            <img src="{{ asset('images/bricks_logo.png') }}" alt="Logo" style="height: 27px; width: auto;">
        </a>
    </div>

    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('talent.dashboard') }}" class="c-sidebar-nav-link {{ request()->routeIs('talent.dashboard') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ Route::has('talent.projects.index') ? route('talent.projects.index') : '#' }}" class="c-sidebar-nav-link {{ request()->routeIs('talent.projects.*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-briefcase"></i>
                {{ trans('global.projects_dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ Route::has('talent.payments.index') ? route('talent.payments.index') : '#' }}" class="c-sidebar-nav-link {{ request()->routeIs('talent.payments.*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-wallet"></i>
                {{ trans('global.payment_dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ Route::has('talent.settings.index') ? route('talent.settings.index') : '#' }}" class="c-sidebar-nav-link {{ request()->routeIs('talent.settings.*') ? 'c-active' : '' }}">
                <i class="c-sidebar-nav-icon fas fa-fw fa-cog"></i>
                {{ trans('global.settings') }}
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('talent-logout-form').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>

</div>
