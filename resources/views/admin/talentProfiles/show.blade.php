@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.talentProfile.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.talent-profiles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.id') }}
                        </th>
                        <td>
                            {{ $talentProfile->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.legal_name') }}
                        </th>
                        <td>
                            {{ $talentProfile->legal_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.display_name') }}
                        </th>
                        <td>
                            {{ $talentProfile->display_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.language') }}
                        </th>
                        <td>
                            @foreach($talentProfile->languages as $key => $language)
                                <span class="label label-info">{{ $language->title }}</span>
                            @endforeach
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.verification_status') }}
                        </th>
                        <td>
                            {{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talentProfile->verification_status] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.verification_notes') }}
                        </th>
                        <td>
                            {{ $talentProfile->verification_notes }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.bio') }}
                        </th>
                        <td>
                            {{ $talentProfile->bio }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.daily_rate') }}
                        </th>
                        <td>
                            {{ $talentProfile->daily_rate }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.hourly_rate') }}
                        </th>
                        <td>
                            {{ $talentProfile->hourly_rate }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.height') }}
                        </th>
                        <td>
                            {{ $talentProfile->height }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.weight') }}
                        </th>
                        <td>
                            {{ $talentProfile->weight }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.chest') }}
                        </th>
                        <td>
                            {{ $talentProfile->chest }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.waist') }}
                        </th>
                        <td>
                            {{ $talentProfile->waist }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.hips') }}
                        </th>
                        <td>
                            {{ $talentProfile->hips }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.skin_tone') }}
                        </th>
                        <td>
                            {{ App\Models\TalentProfile::SKIN_TONE_SELECT[$talentProfile->skin_tone] ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.hair_color') }}
                        </th>
                        <td>
                            {{ $talentProfile->hair_color }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.eye_color') }}
                        </th>
                        <td>
                            {{ $talentProfile->eye_color }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.shoe_size') }}
                        </th>
                        <td>
                            {{ $talentProfile->shoe_size }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.talentProfile.fields.user') }}
                        </th>
                        <td>
                            {{ $talentProfile->user->name ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.talent-profiles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection