@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="text-value">{{ $applicationsStats['total'] }}</div>
                    <div>{{ trans('global.payments_total_applications') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="text-value">{{ $applicationsStats['paid'] }}</div>
                    <div>{{ trans('global.payments_paid_applications') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="text-value">{{ $applicationsStats['pending'] }}</div>
                    <div>{{ trans('global.payments_pending_applications') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="text-value">{{ $applicationsStats['selected'] }}</div>
                    <div>{{ trans('global.payments_selected_talents') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ trans('global.payments_requested_amount') }}</h5>
                    <p class="card-text display-4">
                        {{ number_format($financials['total_requested'], 2) }}
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ trans('global.payments_offered_amount') }}</h5>
                    <p class="card-text display-4">
                        {{ number_format($financials['total_offered'], 2) }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ trans('global.recent_payments') }}</span>
            <a href="{{ route('admin.casting-applications.index') }}" class="btn btn-sm btn-outline-primary">
                {{ trans('global.manage_payments') }}
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.castingApplication.fields.talent_profile') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.casting_requirement') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.rate') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.rate_offered') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.payment_processed') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentPayments as $application)
                            <tr>
                                <td>{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name ?? trans('global.not_set') }}</td>
                                <td>{{ optional($application->casting_requirement)->project_name ?? trans('global.not_set') }}</td>
                                <td>{{ $application->rate ? number_format($application->rate, 2) : trans('global.not_set') }}</td>
                                <td>{{ $application->rate_offered ? number_format($application->rate_offered, 2) : trans('global.not_set') }}</td>
                                <td>{{ App\Models\CastingApplication::PAYMENT_PROCESSED_SELECT[$application->payment_processed] ?? trans('global.not_set') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    {{ trans('global.no_payments_found') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>{{ trans('global.recent_bank_accounts') }}</span>
            <a href="{{ route('admin.bank-details.index') }}" class="btn btn-sm btn-outline-primary">
                {{ trans('global.manage_bank_details') }}
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.bankDetail.fields.talent_profile') }}</th>
                            <th>{{ trans('cruds.bankDetail.fields.bank_name') }}</th>
                            <th>{{ trans('cruds.bankDetail.fields.account_holder_name') }}</th>
                            <th>{{ trans('cruds.bankDetail.fields.status') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentBankAccounts as $bankDetail)
                            <tr>
                                <td>{{ optional($bankDetail->talent_profile)->display_name ?? optional($bankDetail->talent_profile)->legal_name ?? trans('global.not_set') }}</td>
                                <td>{{ $bankDetail->bank_name ?? trans('global.not_set') }}</td>
                                <td>{{ $bankDetail->account_holder_name ?? trans('global.not_set') }}</td>
                                <td>{{ App\Models\BankDetail::STATUS_SELECT[$bankDetail->status] ?? trans('global.not_set') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    {{ trans('global.no_bank_details_found') }}
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
