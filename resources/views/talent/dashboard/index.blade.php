@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                    <div>
                        <h4 class="card-title mb-1">{{ trans('global.dashboard') }}</h4>
                        <p class="text-muted mb-0">{{ trans('global.talent_dashboard_welcome') }}</p>
                    </div>
                    <div class="mt-3 mt-md-0">
                        <a href="{{ route('talent.projects.index') }}" class="btn btn-outline-primary btn-sm mr-2">
                            {{ trans('global.talent_dashboard_cta_view_projects') }}
                        </a>
                        <a href="{{ route('talent.payments.index') }}" class="btn btn-primary btn-sm">
                            {{ trans('global.talent_dashboard_cta_view_payments') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        @php
            $statMeta = [
                'total_applications' => [
                    'label' => trans('global.talent_dashboard_stat_total'),
                    'helper' => trans('global.talent_dashboard_stat_total_helper'),
                ],
                'active_applications' => [
                    'label' => trans('global.talent_dashboard_stat_active'),
                    'helper' => trans('global.talent_dashboard_stat_active_helper'),
                ],
                'awarded_roles' => [
                    'label' => trans('global.talent_dashboard_stat_awarded'),
                    'helper' => trans('global.talent_dashboard_stat_awarded_helper'),
                ],
                'pending_payments' => [
                    'label' => trans('global.talent_dashboard_stat_pending_payments'),
                    'helper' => trans('global.talent_dashboard_stat_pending_helper'),
                ],
            ];
        @endphp
        @foreach ($statMeta as $key => $meta)
            <div class="col-12 col-md-6 col-xl-3 mb-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <p class="text-muted text-uppercase small mb-1">{{ $meta['label'] }}</p>
                        <h2 class="mb-2">{{ number_format($stats[$key] ?? 0) }}</h2>
                        <p class="text-muted small mb-0">{{ $meta['helper'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="row mt-2">
        <div class="col-xl-8 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ trans('global.talent_dashboard_projects_heading') }}</h5>
                        <p class="text-muted small mb-0">{{ trans('global.talent_dashboard_projects_helper') }}</p>
                    </div>
                    <a href="{{ route('talent.projects.index') }}" class="btn btn-sm btn-outline-secondary">
                        {{ trans('global.view') }}
                    </a>
                </div>
                <div class="card-body pt-0">
                    <h6 class="text-uppercase text-muted small mb-3">{{ trans('global.talent_dashboard_recent_applications') }}</h6>
                    @forelse ($recentApplications as $application)
                        @php
                            $requirement = $application->casting_requirement;
                            $projectName = $requirement->project_name ?? trans('global.projects_primary_tag');
                            $applicationStatus = \App\Models\CastingApplication::STATUS_SELECT[$application->status] ?? ucfirst($application->status ?? trans('global.not_set'));
                            $statusClasses = [
                                'selected' => 'badge-success',
                                'applied' => 'badge-secondary',
                                'rejected' => 'badge-danger',
                                'did_not_show' => 'badge-warning',
                            ];
                            $statusClass = $statusClasses[$application->status] ?? 'badge-light';
                        @endphp
                        <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                            <div class="pr-3">
                                <div class="font-weight-bold">{{ $projectName }}</div>
                                <div class="text-muted small">
                                    @if ($requirement?->shoot_date_display)
                                        <span>{{ $requirement->shoot_date_display }}</span>
                                    @endif
                                    @if ($requirement?->location)
                                        <span class="mx-2">•</span>
                                        <span>{{ $requirement->location }}</span>
                                    @endif
                                </div>
                                <div class="text-muted small">
                                    {{ trans('global.talent_dashboard_project_role_label') }}:
                                    <span class="text-dark">{{ $requirement->client_name ?? trans('global.not_set') }}</span>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge {{ $statusClass }}">{{ $applicationStatus }}</span>
                                <div>
                                    <a href="{{ $requirement ? route('talent.projects.show', $requirement) : '#' }}" class="small">
                                        {{ trans('global.talent_dashboard_project_view_link') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 border rounded">
                            {{ trans('global.talent_dashboard_recent_applications_empty') }}
                        </div>
                    @endforelse

                    <h6 class="text-uppercase text-muted small mt-4 mb-3">{{ trans('global.talent_dashboard_open_projects') }}</h6>
                    @forelse ($openProjects as $project)
                        @php
                            $projectStatus = \App\Models\CastingRequirement::STATUS_SELECT[$project->status] ?? ucfirst($project->status ?? trans('global.not_set'));
                            $projectStatusClasses = [
                                'advertised' => 'badge-info',
                                'processing' => 'badge-warning',
                                'completed' => 'badge-success',
                            ];
                            $projectStatusClass = $projectStatusClasses[$project->status] ?? 'badge-light';
                        @endphp
                        <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                            <div class="pr-3">
                                <div class="font-weight-bold">{{ $project->project_name }}</div>
                                <div class="text-muted small">
                                    @if ($project->shoot_date_display)
                                        <span>{{ $project->shoot_date_display }}</span>
                                    @endif
                                    @if ($project->location)
                                        <span class="mx-2">•</span>
                                        <span>{{ $project->location }}</span>
                                    @endif
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="badge {{ $projectStatusClass }}">{{ $projectStatus }}</span>
                                <div>
                                    <a href="{{ route('talent.projects.show', $project) }}" class="small">
                                        {{ trans('global.talent_dashboard_project_view_link') }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 border rounded">
                            {{ trans('global.talent_dashboard_open_projects_empty') }}
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-xl-4 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-1">{{ trans('global.talent_dashboard_payments_heading') }}</h5>
                        <p class="text-muted small mb-0">{{ trans('global.talent_dashboard_payments_helper') }}</p>
                    </div>
                    <a href="{{ route('talent.payments.index') }}" class="btn btn-sm btn-outline-secondary">
                        {{ trans('global.view') }}
                    </a>
                </div>
                <div class="card-body pt-0">
                    @forelse ($recentPayments as $payment)
                        @php
                            $project = $payment->casting_requirement;
                            $paymentAmount = $payment->rate_offered ?? $payment->rate ?? 0;
                            $paymentStatusText = \App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$payment->payment_status] ?? ucfirst($payment->payment_status ?? trans('global.not_set'));
                        @endphp
                        <div class="border-bottom pb-3 mb-3">
                            <div class="d-flex justify-content-between">
                                <div class="pr-3">
                                    <div class="font-weight-bold">{{ $project->project_name ?? trans('global.projects_primary_tag') }}</div>
                                    <div class="text-muted small">
                                        {{ trans('global.talent_dashboard_payment_updated', ['date' => optional($payment->updated_at)->format('d M Y')]) }}
                                    </div>
                                </div>
                                <span class="badge {{ $payment->getPaymentStatusBadgeClass() }}">{{ $paymentStatusText }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center mt-2">
                                <span class="text-muted small">{{ trans('global.amount') }}</span>
                                <span class="font-weight-bold">{{ number_format($paymentAmount, 2) }} KWD</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-4 border rounded">
                            {{ trans('global.talent_dashboard_payments_empty') }}
                        </div>
                    @endforelse
                </div>
                <div class="card-footer bg-white border-0">
                    <a href="{{ route('talent.payments.index') }}" class="btn btn-block btn-primary">
                        {{ trans('global.talent_dashboard_cta_view_payments') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

