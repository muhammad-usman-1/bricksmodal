@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ trans('global.account_settings') }}</h5>
                    <p class="card-text text-muted">{{ trans('global.account_settings_help') }}</p>
                    <div class="mt-auto">
                        <a href="{{ route('profile.password.edit') }}" class="btn btn-primary">
                            {{ trans('global.manage_account') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ trans('notifications.template_heading') }}</h5>
                    <p class="card-text text-muted">{{ trans('notifications.placeholder_intro') }}</p>
                    <div class="mt-auto">
                        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-primary">{{ trans('notifications.manage_templates_button') }}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ trans('global.system_preferences') }}</h5>
                    <p class="card-text text-muted">{{ trans('global.system_preferences_help') }}</p>
                    <div class="mt-auto">
                        <span class="badge badge-secondary">{{ trans('global.coming_soon') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
