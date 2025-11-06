@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="text-value">{{ $stats['total'] }}</div>
                    <div>{{ trans('global.talents_total') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="text-value">{{ $stats['approved'] }}</div>
                    <div>{{ trans('global.talents_approved') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="text-value">{{ $stats['pending'] }}</div>
                    <div>{{ trans('global.talents_pending') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="text-value">{{ $stats['rejected'] }}</div>
                    <div>{{ trans('global.talents_rejected') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ trans('global.recent_talents') }}</span>
            <a href="{{ route('admin.talent-profiles.index') }}" class="btn btn-sm btn-outline-primary">
                {{ trans('global.manage_talents') }}
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                            <th>{{ trans('cruds.talentProfile.fields.verification_status') }}</th>
                            <th>{{ trans('cruds.talentProfile.fields.language') }}</th>
                            <th>{{ trans('cruds.talentProfile.fields.user') }}</th>
                            <th>{{ trans('cruds.talentProfile.fields.daily_rate') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talents as $talent)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.talent-profiles.show', $talent->id) }}">
                                        {{ $talent->display_name ?? $talent->legal_name }}
                                    </a>
                                </td>
                                <td>{{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talent->verification_status] ?? trans('global.not_set') }}</td>
                                <td>
                                    @if($talent->languages->isEmpty())
                                        <span class="text-muted">{{ trans('global.not_set') }}</span>
                                    @else
                                        {{ $talent->languages->pluck('name')->join(', ') }}
                                    @endif
                                </td>
                                <td>{{ optional($talent->user)->name ?? trans('global.not_set') }}</td>
                                <td>{{ $talent->daily_rate ? number_format($talent->daily_rate, 2) : trans('global.not_set') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    {{ trans('global.no_talents_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
