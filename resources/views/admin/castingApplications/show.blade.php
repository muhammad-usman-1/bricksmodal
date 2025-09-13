@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.castingApplication.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.casting-applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.id') }}
                        </th>
                        <td>
                            {{ $castingApplication->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.casting_requirement') }}
                        </th>
                        <td>
                            {{ $castingApplication->casting_requirement->project_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.talent_profile') }}
                        </th>
                        <td>
                            {{ $castingApplication->talent_profile->legal_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.rate') }}
                        </th>
                        <td>
                            {{ $castingApplication->rate }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.rate_offered') }}
                        </th>
                        <td>
                            {{ $castingApplication->rate_offered }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.talent_notes') }}
                        </th>
                        <td>
                            {{ $castingApplication->talent_notes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.admin_notes') }}
                        </th>
                        <td>
                            {{ $castingApplication->admin_notes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CastingApplication::STATUS_SELECT[$castingApplication->status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.rating') }}
                        </th>
                        <td>
                            {{ $castingApplication->rating }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.reviews') }}
                        </th>
                        <td>
                            {{ $castingApplication->reviews }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingApplication.fields.payment_processed') }}
                        </th>
                        <td>
                            {{ App\Models\CastingApplication::PAYMENT_PROCESSED_SELECT[$castingApplication->payment_processed] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.casting-applications.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection