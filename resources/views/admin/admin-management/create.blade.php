@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Add New Admin</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.admin-management.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="password">Password</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <small class="form-text text-muted">Minimum 8 characters</small>
            </div>

            <div class="form-group">
                <label class="required" for="password_confirmation">Confirm Password</label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation" required>
            </div>

            <div class="form-group">
                <label class="required" for="role_id">Role</label>
                <select class="form-control {{ $errors->has('role_id') ? 'is-invalid' : '' }}" name="role_id" id="role_id" required>
                    <option value="">Select a role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>
                    @endforeach
                </select>
                @if($errors->has('role_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('role_id') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="d-block mb-3"><strong>Module Permissions</strong></label>

                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" name="project_management" id="project_management" value="1" {{ old('project_management') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="project_management">
                        <i class="fas fa-project-diagram text-primary"></i> Project Management
                    </label>
                </div>

                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" name="talent_management" id="talent_management" value="1" {{ old('talent_management') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="talent_management">
                        <i class="fas fa-users text-info"></i> Talent Management
                    </label>
                </div>

                <div class="custom-control custom-checkbox mb-2">
                    <input type="checkbox" class="custom-control-input" name="payment_management" id="payment_management" value="1" {{ old('payment_management') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="payment_management">
                        <i class="fas fa-credit-card text-success"></i> Payment Management
                    </label>
                </div>
            </div>

            <div class="form-group">
                <label class="d-block mb-3"><strong>Payment Permissions</strong></label>

                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="can_make_payments" id="can_make_payments" value="1" {{ old('can_make_payments') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="can_make_payments">
                        <i class="fas fa-money-bill-wave text-warning"></i> Can Make Payments
                    </label>
                    <small class="form-text text-muted">If unchecked, admin can only request payment approval from super admin</small>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save"></i> Save Admin
                </button>
                <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
