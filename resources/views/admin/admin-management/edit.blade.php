@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Admin</h5>
    </div>

    <div class="card-body">
        <form action="{{ route('admin.admin-management.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="email">Email</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <small class="form-text text-muted">Leave blank to keep current password. Minimum 8 characters if changing.</small>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation">
            </div>

            <div class="form-group">
                <label class="required" for="role_id">Role</label>
                <select class="form-control {{ $errors->has('role_id') ? 'is-invalid' : '' }}" name="role_id" id="role_id" required>
                    <option value="">Select a role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->title) }}</option>
                    @endforeach
                </select>
                @if($errors->has('role_id'))
                    <div class="invalid-feedback">
                        {{ $errors->first('role_id') }}
                    </div>
                @endif
                <small class="form-text text-muted">
                    The selected role will automatically grant the appropriate permissions and menu access.
                    @php $currentRole = $user->roles->first(); @endphp
                    @if($currentRole)
                        <strong>Current role permissions:</strong>
                        @if($currentRole->permissions && $currentRole->permissions->count() > 0)
                            {{ $currentRole->permissions->pluck('title')->map(function($perm) { return ucwords(str_replace(['_', 'management', 'access'], [' ', '', ''], $perm)); })->join(', ') }}
                        @else
                            No permissions assigned to this role
                        @endif
                    @endif
                </small>
            </div>

            <div class="form-group">
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save"></i> Update Admin
                </button>
                <a href="{{ route('admin.admin-management.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection

