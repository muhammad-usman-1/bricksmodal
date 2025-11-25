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
                        <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->title) }}</option>
                    @endforeach
                </select>
                @if($errors->has('role_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('role_id') }}
                    </div>
                @endif
                <small class="form-text text-muted">
                    The selected role will automatically grant the appropriate permissions and menu access.
                    @if($role ?? false)
                        <strong>Selected role permissions:</strong>
                        @if($role->permissions && $role->permissions->count() > 0)
                            {{ $role->permissions->pluck('title')->map(function($perm) { return ucwords(str_replace(['_', 'management', 'access'], [' ', '', ''], $perm)); })->join(', ') }}
                        @else
                            No permissions assigned to this role
                        @endif
                    @endif
                </small>
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
