@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.label.title_singular') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <a class="btn btn-secondary mb-3" href="{{ route('admin.labels.index') }}">
                {{ trans('global.back_to_list') }}
            </a>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.label.fields.id') }}
                        </th>
                        <td>
                            {{ $label->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.label.fields.name') }}
                        </th>
                        <td>
                            {{ $label->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('global.created_at') }}
                        </th>
                        <td>
                            {{ $label->created_at?->format('d M Y H:i') }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('global.updated_at') }}
                        </th>
                        <td>
                            {{ $label->updated_at?->format('d M Y H:i') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

