@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Role Permission Management</h5>
            <div>
                <small class="text-muted">Manage permissions for each role</small>
            </div>
        </div>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        @endif

        <p class="text-muted">Manage permissions for each role. Only Super Admins can modify role permissions.</p>

        <form action="{{ route('admin.role-permissions.update') }}" method="POST" id="permissions-form">
            @csrf
            @method('PUT')

            @foreach($permissions as $module => $modulePermissions)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-folder"></i> {{ $module }}
                        <small class="text-muted">({{ $modulePermissions->count() }} permissions)</small>
                    </h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllModule('{{ str_replace(' ', '-', strtolower($module)) }}')">
                            <i class="fas fa-check-square"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllModule('{{ str_replace(' ', '-', strtolower($module)) }}')">
                            <i class="fas fa-square"></i> Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="module-{{ str_replace(' ', '-', strtolower($module)) }}">
                            <thead class="table-light">
                                <tr>
                                    <th width="200">Role</th>
                                    @foreach($modulePermissions as $permission)
                                    <th class="text-center" width="150">
                                        {{ ucwords(str_replace(['_', 'management', 'access'], [' ', '', ''], $permission->title)) }}
                                    </th>
                                    @endforeach
                                    <th class="text-center" width="100">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst($role->title) }}</strong>
                                    </td>
                                    @foreach($modulePermissions as $permission)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox"
                                                   class="form-check-input permission-checkbox"
                                                   name="permissions[{{ $role->id }}][]"
                                                   value="{{ $permission->id }}"
                                                   id="perm-{{ $role->id }}-{{ $permission->id }}"
                                                   {{ $role->permissions && $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    @endforeach
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input role-select-all" data-role="{{ $role->id }}" data-module="{{ str_replace(' ', '-', strtolower($module)) }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save All Permission Changes
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Reset Changes
                            </button>
                        </div>
                        <div class="text-muted">
                            <small>Changes will be applied to all roles and permissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

        <form action="{{ route('admin.role-permissions.update') }}" method="POST" id="permissions-form">
            @csrf
            @method('PUT')

            @foreach($permissions as $module => $modulePermissions)
            <div class="card mt-4">
                <div class="card-header">
                    <h4 class="card-title">
                        <i class="fas fa-folder"></i> {{ $module }}
                        <small class="text-muted">({{ $modulePermissions->count() }} permissions)</small>
                    </h4>
                    <div class="card-tools">
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectAllModule('{{ str_replace(' ', '-', strtolower($module)) }}')">
                            <i class="fas fa-check-square"></i> Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearAllModule('{{ str_replace(' ', '-', strtolower($module)) }}')">
                            <i class="fas fa-square"></i> Clear All
                        </button>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover mb-0" id="module-{{ str_replace(' ', '-', strtolower($module)) }}">
                            <thead class="table-light">
                                <tr>
                                    <th width="200">Role</th>
                                    @foreach($modulePermissions as $permission)
                                    <th class="text-center" width="150">
                                        {{ ucwords(str_replace(['_', 'management', 'access'], [' ', '', ''], $permission->title)) }}
                                    </th>
                                    @endforeach
                                    <th class="text-center" width="100">All</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roles as $role)
                                <tr>
                                    <td>
                                        <strong>{{ ucfirst($role->title) }}</strong>
                                    </td>
                                    @foreach($modulePermissions as $permission)
                                    <td class="text-center">
                                        <div class="form-check d-flex justify-content-center">
                                            <input type="checkbox"
                                                   class="form-check-input permission-checkbox"
                                                   name="permissions[{{ $role->id }}][]"
                                                   value="{{ $permission->id }}"
                                                   id="perm-{{ $role->id }}-{{ $permission->id }}"
                                                   {{ $role->permissions && $role->permissions->contains($permission->id) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    @endforeach
                                    <td class="text-center">
                                        <input type="checkbox" class="form-check-input role-select-all" data-role="{{ $role->id }}" data-module="{{ str_replace(' ', '-', strtolower($module)) }}">
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endforeach

            <div class="card mt-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-save"></i> Save All Permission Changes
                            </button>
                            <button type="button" class="btn btn-secondary btn-lg" onclick="resetForm()">
                                <i class="fas fa-undo"></i> Reset Changes
                            </button>
                        </div>
                        <div class="text-muted">
                            <small>Changes will be applied to all roles and permissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle individual permission checkbox changes
    $('.permission-checkbox').change(function() {
        updateRoleSelectAll($(this));
    });

    // Handle role select all checkbox
    $('.role-select-all').change(function() {
        var roleId = $(this).data('role');
        var module = $(this).data('module');
        var isChecked = $(this).is(':checked');

        // Find all permission checkboxes for this role in this module
        $('#module-' + module + ' input[name="permissions[' + roleId + '][]"]').prop('checked', isChecked);
    });

    // Initialize role select all checkboxes
    $('.role-select-all').each(function() {
        updateRoleSelectAll($(this));
    });
});

function updateRoleSelectAll(checkbox) {
    var roleId = checkbox.attr('name').match(/permissions\[(\d+)\]/)[1];
    var module = checkbox.closest('table').attr('id').replace('module-', '');

    var roleSelectAll = $('#module-' + module + ' .role-select-all[data-role="' + roleId + '"]');
    var totalPermissions = $('#module-' + module + ' input[name="permissions[' + roleId + '][]"]').length;
    var checkedPermissions = $('#module-' + module + ' input[name="permissions[' + roleId + '][]"]:checked').length;

    roleSelectAll.prop('checked', totalPermissions === checkedPermissions);
}

function selectAllModule(module) {
    $('#module-' + module + ' .permission-checkbox').prop('checked', true);
    $('#module-' + module + ' .role-select-all').prop('checked', true);
}

function clearAllModule(module) {
    $('#module-' + module + ' .permission-checkbox').prop('checked', false);
    $('#module-' + module + ' .role-select-all').prop('checked', false);
}

function resetForm() {
    if (confirm('Are you sure you want to reset all changes? This will reload the page.')) {
        location.reload();
    }
}
</script>
@endsection
