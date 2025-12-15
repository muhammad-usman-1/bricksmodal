@extends('layouts.admin')
@section('content')

<style>
    .modal-shell { padding: 10px 0; }
    .modal-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 14px 30px rgba(15,23,42,0.12); max-width: 960px; margin: 0 auto; }
    .modal-head { display: flex; justify-content: space-between; align-items: center; padding: 14px 16px; border-bottom: 1px solid #eef0f3; }
    .modal-title { margin: 0; font-weight: 700; font-size: 16px; color: #111827; }
    .modal-sub { margin: 0; color: #6b7280; font-size: 12px; }
    .modal-body { padding: 16px; display: flex; flex-direction: column; gap: 16px; }
    .section-title { font-weight: 700; color: #111827; margin: 0; font-size: 14px; }
    .label { font-size: 12px; color: #6b7280; margin-bottom: 6px; }
    .input { width: 100%; border: 1px solid #e5e7eb; border-radius: 10px; padding: 11px 12px; font-size: 13px; color: #111827; background: #fff; }
    .select { width: 100%; border: 1px solid #e5e7eb; border-radius: 10px; padding: 11px 12px; font-size: 13px; color: #111827; background: #fff; appearance: none; }
    .row-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(260px, 1fr)); gap: 12px; }
    .footer { display: flex; justify-content: flex-end; gap: 10px; padding: 0 16px 16px; }
    .btn-secondary-lite { border: 1px solid #e5e7eb; background: #fff; color: #374151; border-radius: 10px; padding: 10px 14px; font-size: 13px; cursor: pointer; text-decoration: none; }
    .btn-secondary-lite:hover, .btn-secondary-lite:focus { color: #374151; text-decoration: none; }
    .btn-primary-dark { border: none; background: linear-gradient(90deg, #0f0f11, #1f2024); color: #fff; border-radius: 10px; padding: 10px 16px; font-size: 13px; box-shadow: 0 10px 22px rgba(0,0,0,0.16); }
    .perm-row { display: flex; justify-content: space-between; align-items: center; color: #374151; font-size: 13px; }
    .helper { color: #9ca3af; font-size: 11px; }
</style>

<div class="modal-shell">
    <div class="modal-card">
        <div class="modal-head">
            <div>
                <p class="modal-title">Edit User Permissions</p>
                <p class="modal-sub">Manage user details and permissions</p>
            </div>
            <a href="{{ route('admin.admin-management.index') }}" style="color:#6b7280; font-size:16px;"><i class="fas fa-times"></i></a>
        </div>

        <form action="{{ route('admin.admin-management.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div>
                    <p class="section-title">User Details</p>
                    <div class="row-2">
                        <div>
                            <label class="label required" for="name">Name</label>
                            <input class="input {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" placeholder="Admin" required>
                            @if($errors->has('name'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('name') }}</div>
                            @endif
                        </div>
                        <div>
                            <label class="label required" for="email">Email</label>
                            <input class="input {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" placeholder="admin@admin.com" required>
                            @if($errors->has('email'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('email') }}</div>
                            @endif
                        </div>
                    </div>
                    <div class="row-2">
                        <div>
                            <label class="label required" for="role_id">Role</label>
                            <select class="select {{ $errors->has('role_id') ? 'is-invalid' : '' }}" name="role_id" id="role_id" required>
                                <option value="">Choose Role</option>
                                @foreach($roles as $role)
                                    @php $permCount = $role->permissions->count(); @endphp
                                    <option value="{{ $role->id }}" data-perm-count="{{ $permCount }}" {{ old('role_id', $user->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>{{ ucfirst($role->title) }}</option>
                                @endforeach
                            </select>
                            @if($errors->has('role_id'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('role_id') }}</div>
                            @endif
                        </div>
                        <div>
                            <label class="label" for="password">Password</label>
                            <input class="input {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" placeholder="Leave blank to keep current">
                            @if($errors->has('password'))
                                <div class="invalid-feedback" style="display:block;">{{ $errors->first('password') }}</div>
                            @endif
                            <p class="helper">Leave blank to keep current password. Minimum 8 characters if changing.</p>
                        </div>
                    </div>
                    <div class="row-2">
                        <div></div>
                        <div>
                            <label class="label" for="password_confirmation">Confirm Password</label>
                            <input class="input" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm password">
                        </div>
                    </div>
                </div>

                <div>
                    <div class="perm-row">
                        <span class="section-title" style="font-size:13px;">Permissions</span>
                        <span id="perm-count" class="helper">0 selected</span>
                    </div>
                    <p class="helper">Permissions are auto-assigned based on the selected role.</p>
                </div>
            </div>

            <div class="footer">

                <button type="submit" class="btn-primary-dark">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
    (function() {
        const roleSelect = document.getElementById('role_id');
        const permCount = document.getElementById('perm-count');
        if (roleSelect && permCount) {
            const updateCount = () => {
                const opt = roleSelect.selectedOptions[0];
                const count = opt ? opt.dataset.permCount || 0 : 0;
                permCount.textContent = `${count} selected`;
            };
            roleSelect.addEventListener('change', updateCount);
            updateCount();
        }
    })();
</script>

@endsection

