@extends('layouts.talent')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <h4 class="card-title mb-3">{{ trans('global.dashboard') }}</h4>
                    <p class="text-muted mb-0">{{ trans('global.talent_dashboard_welcome') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
