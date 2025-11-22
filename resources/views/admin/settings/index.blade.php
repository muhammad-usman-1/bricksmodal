@extends('layouts.admin_design')
@section('content')

<div class="col-span-1 lg:col-span-10 px-4 md:px-5">
    <div class="mb-4">
        <h1 class="text-2xl md:text-4xl font-bold">{{ trans('global.settings') }}</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white border border-slate-300 rounded-lg shadow-sm p-4 flex flex-col">
            <div>
                <h5 class="font-semibold text-lg">{{ trans('global.account_settings') }}</h5>
                <p class="text-sm text-gray-500 mt-2">{{ trans('global.account_settings_help') }}</p>
            </div>
            <div class="mt-auto">
                <a href="{{ route('profile.password.edit') }}" class="inline-block bg-[#F1F2F4] hover:bg-slate-400 hover:text-white py-1 px-3 rounded-md border border-[#000000]">
                    {{ trans('global.manage_account') }}
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-300 rounded-lg shadow-sm p-4 flex flex-col">
            <div>
                <h5 class="font-semibold text-lg">{{ trans('notifications.template_heading') }}</h5>
                <p class="text-sm text-gray-500 mt-2">{{ trans('notifications.placeholder_intro') }}</p>
            </div>
            <div class="mt-auto">
                <a href="{{ route('admin.email-templates.index') }}" class="inline-block bg-[#F1F2F4] hover:bg-slate-400 hover:text-white py-1 px-3 rounded-md border border-[#000000]">
                    {{ trans('notifications.manage_templates_button') }}
                </a>
            </div>
        </div>

        <div class="bg-white border border-slate-300 rounded-lg shadow-sm p-4 flex flex-col">
            <div>
                <h5 class="font-semibold text-lg">{{ trans('global.system_preferences') }}</h5>
                <p class="text-sm text-gray-500 mt-2">{{ trans('global.system_preferences_help') }}</p>
            </div>
            <div class="mt-auto">
                <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded-full text-sm">{{ trans('global.coming_soon') }}</span>
            </div>
        </div>
    </div>

</div>

@endsection
