@extends('layouts.app')

@section('styles')
<style>
    .pending-wrapper {
        min-height: 80vh;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .pending-card {
        max-width: 520px;
        border-radius: 20px;
    }

    .pending-card .alert {
        border-radius: 999px;
    }
</style>
@endsection

@section('content')
<div class="pending-wrapper">
    <div class="card shadow pending-card w-100 border-0">
        <div class="card-body text-center py-5 px-4">
            <h2 class="mb-3">{{ trans('global.pending_request_title') }}</h2>
            <p class="text-muted mb-4">{{ trans('global.pending_request_body') }}</p>

            <div class="alert alert-info d-inline-block px-4 py-3 font-weight-semibold">
                {{ trans('global.pending_request_status') }}: {{ \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$profile->verification_status] ?? trans('global.pending_request_waiting') }}
            </div>

            @if($profile->verification_notes)
                <div class="mt-3">
                    <h5 class="font-weight-semibold">{{ trans('cruds.talentProfile.fields.verification_notes') }}</h5>
                    <p class="text-muted mb-0">{{ $profile->verification_notes }}</p>
                </div>
            @endif

            <a href="{{ route('talent.pending') }}" class="btn btn-outline-primary mt-4">{{ trans('global.refresh_status') }}</a>
        </div>
    </div>
</div>
@endsection
