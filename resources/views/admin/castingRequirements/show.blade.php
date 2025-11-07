@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.castingRequirement.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.casting-requirements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.id') }}
                        </th>
                        <td>
                            {{ $castingRequirement->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.project_name') }}
                        </th>
                        <td>
                            {{ $castingRequirement->project_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.client_name') }}
                        </th>
                        <td>
                            {{ $castingRequirement->client_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.location') }}
                        </th>
                        <td>
                            {{ $castingRequirement->location }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.shoot_date_time') }}
                        </th>
                        <td>
                            {{ $castingRequirement->shoot_date_time }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.hair_color') }}
                        </th>
                        <td>
                            {{ $castingRequirement->hair_color }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.age_range') }}
                        </th>
                        <td>
                            {{ $castingRequirement->age_range }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.gender') }}
                        </th>
                        <td>
                            {{ App\Models\CastingRequirement::GENDER_SELECT[$castingRequirement->gender] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.outfit') }}
                        </th>
                        <td>
                            @php
                                $selectedOutfits = $castingRequirement->getSelectedOutfits();
                            @endphp
                            @if($selectedOutfits->isNotEmpty())
                                <ul class="mb-0">
                                    @foreach($selectedOutfits as $outfit)
                                        <li>{{ $outfit->name }} ({{ ucfirst($outfit->category) }})</li>
                                    @endforeach
                                </ul>
                            @else
                                <span class="text-muted">{{ trans('global.not_set') }}</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.reference') }}
                        </th>
                        <td>
                            @foreach($castingRequirement->reference as $key => $media)
                                <a href="{{ $media->getUrl() }}" target="_blank">
                                    {{ trans('global.view_file') }}
                                </a>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.count') }}
                        </th>
                        <td>
                            {{ $castingRequirement->count }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.notes') }}
                        </th>
                        <td>
                            {{ $castingRequirement->notes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.user') }}
                        </th>
                        <td>
                            {{ $castingRequirement->user->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.rate_per_model') }}
                        </th>
                        <td>
                            {{ $castingRequirement->rate_per_model }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.casting-requirements.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
