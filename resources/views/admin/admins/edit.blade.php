@extends('layouts.admin')

@section('content')
    <div class="admin-wrap">
        <h2 class="admin-title">Edit Admin</h2>

        <div class="admin-card">
            <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}">
                @csrf
                @method('PUT')

                <!-- TWO-COLUMN GRID -->
                <div class="grid-2">

                    <!-- LEFT COLUMN -->
                    <div class="col">
                        {{-- Admin Name --}}
                        <div class="fgroup">
                            <label class="required" for="name">Admin Name</label>
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                type="text" name="name" id="name" value="{{ old('name', $admin->name) }}"
                                required placeholder="Enter admin name">
                            @if ($errors->has('name'))
                                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        {{-- Email --}}
                        <div class="fgroup">
                            <label class="required" for="email">Email</label>
                            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                                name="email" id="email" value="{{ old('email', $admin->email) }}"
                                placeholder="Enter Email" required>
                            @if ($errors->has('email'))
                                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                            @endif
                        </div>

                        {{-- Password --}}
                        <div class="fgroup">
                            <label for="password">New Password</label>
                            <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password"
                                name="password" id="password" placeholder="Enter new password">
                            @if ($errors->has('password'))
                                <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                            @endif
                            <small class="help-text">Leave blank to keep current password</small>
                        </div>

                        {{-- Confirm Password --}}
                        <div class="fgroup">
                            <label for="password_confirmation">Confirm New Password</label>
                            <input class="form-control" type="password"
                                name="password_confirmation" id="password_confirmation" placeholder="Confirm new password">
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col">
                        {{-- Role --}}
                        <div class="fgroup">
                            <label class="required" for="role_id">Role</label>
                            <select class="form-control {{ $errors->has('role_id') ? 'is-invalid' : '' }}" name="role_id"
                                id="role_id" required>
                                <option value="" disabled>Enter admin role</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}" {{ (old('role_id') ?? $admin->roles->first()->id ?? '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->title }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('role_id'))
                                <div class="invalid-feedback">{{ $errors->first('role_id') }}</div>
                            @endif
                        </div>

                        {{-- Mobile Number --}}
                        <div class="fgroup">
                            <label for="mobile_number">Mobile Number</label>
                            <input class="form-control {{ $errors->has('mobile_number') ? 'is-invalid' : '' }}" type="text"
                                name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $admin->phone_number) }}"
                                placeholder="Enter mobile number">
                            @if ($errors->has('mobile_number'))
                                <div class="invalid-feedback">{{ $errors->first('mobile_number') }}</div>
                            @endif
                        </div>


                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="submit-row">
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary me-2">Cancel</a>
                    <button class="btn btn-primary" type="submit">Update Admin</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('styles')
    @parent
    <style>
        /* Page wrap */
        .admin-wrap {
            max-width: none;
            width: 100%;
            margin: 0;
        }

        .admin-title {
            margin: 0 0 16px 0;
            padding: 0 4px;
            color: #111;
            font-weight: 700;
        }

        .admin-card {
            width: 100%;
            margin: 0;
            border-radius: 12px;
            box-shadow: none;
            border: 1px solid #eee;
            padding: 20px;
            background-color: white;
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 28px;
        }

        .grid-2 .col {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Field group */
        .fgroup label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            color: #374151;
        }

        .fgroup label.required::after {
            content: " *";
            color: #dc3545;
        }

        .fgroup .help-text {
            display: block;
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
        }

        .form-control:disabled,
        .form-control[readonly] {
            background-color: #efeff5;
            opacity: 1;
        }

        /* Inputs */
        .form-control {
            height: 40px;
            border: 1px solid #e4e6eb;
            border-radius: 8px;
            box-shadow: none !important;
            font-size: 14px;
            padding: 12px 16px;
            background: #ffffff;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control::placeholder {
            color: #9ca3af;
        }

        /* Permissions list */
        .permissions-list {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 8px;
        }

        .permission-item {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-check-input {
            margin: 0;
            width: 16px;
            height: 16px;
        }

        .permission-label {
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            user-select: none;
            margin: 0;
        }

        /* Submit row */
        .submit-row {
            display: flex;
            justify-content: flex-end;
            gap: 8px;
            margin-top: 24px;
        }

        .btn-primary {
            background: #111;
            border-color: #111;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            background: #000;
            border-color: #000;
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #6c757d;
            border-color: #6c757d;
            border-radius: 10px;
            padding: 10px 20px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            background: #5a6268;
            border-color: #5a6268;
            transform: translateY(-1px);
        }

        /* Error states */
        .invalid-feedback {
            display: block;
            font-size: 12px;
            color: #dc3545;
            margin-top: 4px;
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .form-control.is-invalid:focus {
            border-color: #dc3545;
            box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .submit-row {
                justify-content: center;
                flex-direction: column;
            }

            .btn-primary, .btn-secondary {
                width: 100%;
            }
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Simple form validation and enhancement
            const form = document.querySelector('form');
            const inputs = form.querySelectorAll('input[required]');

            // Basic client-side validation feedback
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('is-invalid');
                    } else {
                        this.classList.remove('is-invalid');
                    }
                });
            });

            // Auto-check some permissions based on role selection
            const roleSelect = document.getElementById('role_id');
            if (roleSelect) {
                roleSelect.addEventListener('change', function() {
                    const selectedRole = this.options[this.selectedIndex].text.toLowerCase();
                    const checkboxes = document.querySelectorAll('input[name="permissions[]"]');

                    // Auto-select appropriate permissions based on role
                    checkboxes.forEach(checkbox => {
                        if (selectedRole.includes('manager')) {
                            checkbox.checked = true;
                        } else if (selectedRole.includes('admin')) {
                            checkbox.checked = checkbox.value === 'admins_view';
                        }
                    });
                });
            }
        });
    </script>
@endsection
