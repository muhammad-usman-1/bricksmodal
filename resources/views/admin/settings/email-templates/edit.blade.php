@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('notifications.edit_template') }}: <code>{{ $emailTemplate->key }}</code></span>
        <a href="{{ route('admin.email-templates.index') }}" class="btn btn-sm btn-secondary">{{ trans('global.back') }}</a>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route('admin.email-templates.update', $emailTemplate) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="subject">{{ trans('notifications.template_subject') }}</label>
                <input type="text" id="subject" name="subject" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject', $emailTemplate->subject) }}" required>
                @error('subject')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-group">
                <label for="body">{{ trans('notifications.template_body') }}</label>
                <textarea id="body" name="body" rows="10" class="form-control @error('body') is-invalid @enderror" required>{{ old('body', $emailTemplate->body) }}</textarea>
                @error('body')
                    <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                @enderror
            </div>

            <div class="form-text text-muted mb-3">
                {{ trans('notifications.placeholder_intro') }}:
                <code>{{ '{' }}{name}}</code>,
                <code>{{ '{' }}{status}}</code>,
                <code>{{ '{' }}{notes}}</code>,
                <code>{{ '{' }}{app_name}}</code>
            </div>

            <button type="submit" class="btn btn-primary">{{ trans('global.save') }}</button>
        </form>
    </div>
</div>

@endsection
