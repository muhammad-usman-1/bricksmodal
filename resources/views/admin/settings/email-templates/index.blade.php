@extends('layouts.admin')
@section('content')

@if(session('message'))
    <div class="alert alert-success">{{ session('message') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('notifications.template_heading') }}</span>
    </div>

    <div class="card-body p-0">
        <table class="table table-striped mb-0">
            <thead>
                <tr>
                    <th>{{ trans('notifications.template_key') }}</th>
                    <th>{{ trans('notifications.template_subject') }}</th>
                    <th>{{ trans('global.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($templates as $template)
                    <tr>
                        <td><code>{{ $template->key }}</code></td>
                        <td>{{ $template->subject }}</td>
                        <td>
                            <a href="{{ route('admin.email-templates.edit', $template) }}" class="btn btn-sm btn-primary">{{ trans('global.edit') }}</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    <h5>{{ trans('notifications.placeholder_heading') }}</h5>
    <p class="text-muted">{{ trans('notifications.placeholder_intro') }}</p>
    <ul>
        <li><code>{{ '{' }}{name}}</code> – {{ trans('notifications.placeholder_name') }}</li>
        <li><code>{{ '{' }}{status}}</code> – {{ trans('notifications.placeholder_status') }}</li>
        <li><code>{{ '{' }}{notes}}</code> – {{ trans('notifications.placeholder_notes') }}</li>
        <li><code>{{ '{' }}{app_name}}</code> – {{ trans('notifications.placeholder_app_name') }}</li>
    </ul>
</div>

@endsection
