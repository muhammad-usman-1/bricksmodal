@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header text-center font-weight-bold">Access Denied</div>

            <div class="card-body text-center">
                <h4>You are not authorized to access this system.</h4>
                <p>Your Google account is not registered as an admin user.</p>
                <p>Please contact the super admin to request access.</p>
                <a href="{{ route('admin.login') }}" class="btn btn-primary">Back to Login</a>
            </div>
        </div>
    </div>
</div>
@endsection
