@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="mb-1">{{ trans('global.payment_dashboard') }}</h2>
            <p class="text-muted mb-0">{{ trans('global.payments_overview_intro') }}</p>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.castingRequirement.fields.project_name') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.rate') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.payment_processed') }}</th>
                            <th>{{ trans('global.details') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($applications as $application)
                            <tr>
                                <td>{{ optional($application->casting_requirement)->project_name ?? trans('global.not_set') }}</td>
                                <td>{{ $application->rate_offered ?? $application->rate ?? trans('global.not_set') }}</td>
                                <td>
                                    <span class="badge badge-pill {{ $application->payment_processed === 'paid' ? 'badge-success' : 'badge-warning' }}">
                                        {{ App\Models\CastingApplication::PAYMENT_PROCESSED_SELECT[$application->payment_processed] ?? ucfirst($application->payment_processed) }}
                                    </span>
                                </td>
                                <td>{{ $application->admin_notes ?? trans('global.not_set') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">{{ trans('global.no_payments_found') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
