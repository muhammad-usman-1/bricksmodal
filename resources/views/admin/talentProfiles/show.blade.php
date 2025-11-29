@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.talentProfile.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.talents.dashboard') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>

            <div class="row">
                <div class="col-lg-6">
                    <h5 class="mb-3">{{ trans('global.profile_information') }}</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.id') }}</th>
                                <td>{{ $talentProfile->id }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.legal_name') }}</th>
                                <td>{{ $talentProfile->legal_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                                <td>{{ $talentProfile->display_name ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('global.email_address') }}</th>
                                <td>{{ optional($talentProfile->user)->email ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('global.date_of_birth') }}</th>
                                <td>{{ optional($talentProfile->date_of_birth)->format(config('panel.date_format')) ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('global.gender') }}</th>
                                <td>{{ trans('global.gender_display.' . ($talentProfile->gender ?? '')) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.language') }}</th>
                                <td>
                                    @forelse($talentProfile->languages as $language)
                                        <span class="badge badge-info mr-1">{{ $language->title }}</span>
                                    @empty
                                        <span class="text-muted">{{ trans('global.not_set') }}</span>
                                    @endforelse
                                </td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.bio') }}</th>
                                <td>{{ $talentProfile->bio ?: trans('global.not_set') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-3">{{ trans('global.account_information') }}</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.verification_status') }}</th>
                                <td>{{ \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talentProfile->verification_status] ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.verification_notes') }}</th>
                                <td>{{ $talentProfile->verification_notes ?: trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('global.onboarding_step') }}</th>
                                <td>{{ ucfirst(str_replace('-', ' ', $talentProfile->onboarding_step ?? 'profile')) }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('global.onboarding_completed_at') }}</th>
                                <td>{{ optional($talentProfile->onboarding_completed_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.daily_rate') }}</th>
                                <td>{{ $talentProfile->daily_rate }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.hourly_rate') }}</th>
                                <td>{{ $talentProfile->hourly_rate }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.user') }}</th>
                                <td>{{ optional($talentProfile->user)->name ?? trans('global.not_set') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-4">
                <div class="col-lg-6">
                    <h5 class="mb-3">{{ trans('global.measurements') }}</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.height') }}</th>
                                <td>{{ $talentProfile->height ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.weight') }}</th>
                                <td>{{ $talentProfile->weight ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.chest') }}</th>
                                <td>{{ $talentProfile->chest ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.waist') }}</th>
                                <td>{{ $talentProfile->waist ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.hips') }}</th>
                                <td>{{ $talentProfile->hips ?? trans('global.not_set') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-lg-6">
                    <h5 class="mb-3">{{ trans('global.appearance_details') }}</h5>
                    <table class="table table-bordered table-striped">
                        <tbody>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.skin_tone') }}</th>
                                <td>{{ \App\Models\TalentProfile::SKIN_TONE_SELECT[$talentProfile->skin_tone] ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.hair_color') }}</th>
                                <td>{{ $talentProfile->hair_color ?? trans('global.not_set') }}</td>
                            </tr>
                            <tr>
                                <th>{{ trans('cruds.talentProfile.fields.eye_color') }}</th>
                                <td>{{ $talentProfile->eye_color ?? trans('global.not_set') }}</td>
                            </tr>
                    <tr>
                        <th>{{ trans('cruds.talentProfile.fields.shoe_size') }}</th>
                        <td>{{ $talentProfile->shoe_size ?? trans('global.not_set') }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('global.whatsapp_number') }}</th>
                        <td>{{ $talentProfile->whatsapp_number ? '+' . $talentProfile->whatsapp_number : trans('global.not_set') }}</td>
                    </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            @php
                $documentImages = [
                    'id_front_path' => trans('global.id_front'),
                    'id_back_path' => trans('global.id_back'),
                ];
                $headshots = [
                    'headshot_center_path' => trans('global.headshot_center'),
                    'headshot_left_path' => trans('global.headshot_left'),
                    'headshot_right_path' => trans('global.headshot_right'),
                ];
                $fullBody = [
                    'full_body_front_path' => trans('global.full_body_front'),
                    'full_body_right_path' => trans('global.full_body_right'),
                    'full_body_back_path' => trans('global.full_body_back'),
                ];
            @endphp

            <div class="mt-4">
                <h5 class="mb-3">{{ trans('global.id_documents') }}</h5>
                <div class="row">
                    @foreach($documentImages as $field => $label)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-2 text-center h-100">
                                <small class="d-block text-muted mb-2">{{ $label }}</small>
                                @if($talentProfile->{$field})
                                    <a href="{{ $talentProfile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                    <img src="{{ $talentProfile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                @else
                                    <span class="text-muted">{{ trans('global.not_set') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                <h5 class="mb-3">{{ trans('global.headshots') }}</h5>
                <div class="row">
                    @foreach($headshots as $field => $label)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-2 text-center h-100">
                                <small class="d-block text-muted mb-2">{{ $label }}</small>
                                @if($talentProfile->{$field})
                                    <a href="{{ $talentProfile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                    <img src="{{ $talentProfile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                @else
                                    <span class="text-muted">{{ trans('global.not_set') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mt-4">
                <h5 class="mb-3">{{ trans('global.full_body_photos') }}</h5>
                <div class="row">
                    @foreach($fullBody as $field => $label)
                        <div class="col-md-4 mb-3">
                            <div class="border rounded p-2 text-center h-100">
                                <small class="d-block text-muted mb-2">{{ $label }}</small>
                                @if($talentProfile->{$field})
                                    <a href="{{ $talentProfile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                    <img src="{{ $talentProfile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                @else
                                    <span class="text-muted">{{ trans('global.not_set') }}</span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group mt-4">
                <a class="btn btn-default" href="{{ route('admin.talent-profiles.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
