@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            @isset($progress)
                <div class="card-header bg-white border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-0">{{ trans('global.onboarding_title') }}</h4>
                            <small class="text-muted">{{ trans('global.onboarding_subtitle') }}</small>
                        </div>
                        <div class="text-right">
                            <span class="font-weight-bold">{{ $progress['current'] }}/{{ $progress['total'] }}</span>
                            <div class="progress mt-1" style="height:6px; width:160px;">
                                <div class="progress-bar" role="progressbar" style="width: {{ $progress['percent'] }}%;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            @endisset
            <div class="card-body">
                @yield('onboarding-content')
            </div>
        </div>
    </div>
</div>
@endsection
