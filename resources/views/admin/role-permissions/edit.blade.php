@extends('layouts.admin')
@section('styles')
@parent
<style>
    .module-card {
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        margin-bottom: 1.5rem;
    }
    .module-card .card-header {
        background-color: #f9fafb;
        border-bottom: 1px solid #e5e7eb;
        padding: 0.75rem 1.25rem;
    }
    .permission-label {
        font-size: 13px;
        color: #374151;
        cursor: pointer;
    }
    .permission-checkbox:checked + .permission-label {
        font-weight: 600;
        color: #111827;
    }
    .permission-checkbox {
        accent-color: #000;
    }
    .permission-checkbox:checked {
        background-color: #000 !important;
        border-color: #000 !important;
    }
    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #000 !important;
        border-color: #000 !important;
    }
    .custom-control-input:focus ~ .custom-control-label::before {
        box-shadow: 0 0 0 0.2rem rgba(0, 0, 0, 0.25) !important;
    }
    .custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
        border-color: #000 !important;
    }
    .permission-description {
        font-size: 15px;
        color: #6b7280;
        margin-top: 2px;
        margin-left: 28px;
        line-height: 1.4;
    }
    .role-indicator {
        background-color: #111827;
        color: white;
        padding: 4px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="content" style="margin-top: 1rem;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mb-1"  style="color: #101828; font-size: 24px; font-style: normal; font-weight: 400; line-height: 36px;">Edit Permissions</h2>
            <p class="text-muted mb-0" >Modify permissions for <span class="role-indicator ml-2">{{ $role->name ?? ucfirst($role->title) }}</span></p>
        </div>
        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-outline-dark">
            <i class="fas fa-arrow-left mr-1"></i> Back to List
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #ecfdf5; color: #065f46;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('admin.role-permissions.update') }}" method="POST" data-swal-confirm="Are you sure you want to save these permission changes?">
        @csrf
        @method('PUT')

        @foreach($permissions as $module => $modulePermissions)
            <div class="module-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0 font-weight-bold">{{ $module }}</h6>
                    <div>
                        <button type="button" class="btn btn-xs btn-link text-primary font-weight-bold p-0 mr-3" onclick="selectModule('{{ str_replace(' ', '-', strtolower($module)) }}')" style="font-size: 12px;">Select All</button>
                        <button type="button" class="btn btn-xs btn-link text-muted font-weight-bold p-0" onclick="clearModule('{{ str_replace(' ', '-', strtolower($module)) }}')" style="font-size: 12px;">Clear All</button>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row" id="module-{{ str_replace(' ', '-', strtolower($module)) }}">
                        @foreach($modulePermissions as $perm)
                            @php
                                $permissionDescriptions = [
                                    'user_management_access' => 'Access to view and manage all admin users in the system',
                                    'user_create' => 'Create new admin user accounts',
                                    'user_edit' => 'Edit existing admin user information (name, email, roles, etc.)',
                                    'user_delete' => 'Delete admin user accounts from the system',
                                    'user_view' => 'View admin user details and information',
                                    'role_management_access' => 'Access to view and manage system roles',
                                    'role_create' => 'Create new roles with custom permission sets',
                                    'role_edit' => 'Modify existing role names and permission assignments',
                                    'role_delete' => 'Remove roles from the system',
                                    'role_view' => 'View role details and assigned permissions',
                                    'permission_management_access' => 'Access to the permission management interface',
                                    'permission_assign' => 'Assign or remove permissions from roles',
                                    'permission_view' => 'View available permissions and their assignments',
                                    'project_management_access' => 'Access to the projects dashboard and casting requirements',
                                    'casting_requirement_create' => 'Create new casting requirements/projects',
                                    'casting_requirement_edit' => 'Edit existing casting requirements (details, dates, requirements)',
                                    'casting_requirement_delete' => 'Delete casting requirements from the system',
                                    'casting_requirement_view' => 'View casting requirement details and information',
                                    'casting_application_manage' => 'Manage talent applications to casting requirements (approve, reject, shortlist)',
                                    'casting_application_access' => 'Access to view casting applications',
                                    'talent_management_access' => 'Access to the talent management dashboard',
                                    'talent_profile_create' => 'Create new talent profiles manually',
                                    'talent_profile_edit' => 'Edit existing talent profile information (photos, details, measurements)',
                                    'talent_profile_delete' => 'Delete talent profiles from the system',
                                    'talent_profile_view' => 'View talent profile details and information',
                                    'talent_profile_approve' => 'Approve talent profiles for active use in the system',
                                    'talent_profile_suspend' => 'Suspend or reactivate talent profiles',
                                    'payment_management_access' => 'Access to the payment management dashboard',
                                    'payment_request_manage' => 'Manage payment requests from talents (view, process)',
                                    'payment_approve' => 'Approve payment requests for processing',
                                    'payment_release' => 'Release approved payments to talents',
                                    'bank_detail_manage' => 'Manage bank account details for payments',
                                    'content_management_access' => 'Access to content management features',
                                    'language_manage' => 'Manage system languages and translations',
                                    'outfit_manage' => 'Manage outfit categories and options',
                                    'email_template_manage' => 'Edit and manage email notification templates',
                                    'system_settings_access' => 'Access to system-wide settings and configuration',
                                    'profile_manage' => 'Manage your own admin profile settings (password, 2FA, etc.)',
                                    'label_access' => 'Access to Arabic label management interface',
                                    'label_create' => 'Create new Arabic labels for translations',
                                    'label_edit' => 'Edit existing Arabic label translations',
                                    'label_delete' => 'Delete Arabic labels from the system',
                                    'header_label_access' => 'Access to the Arabic label dropdown in the header navigation',
                                ];
                                $description = $permissionDescriptions[$perm->title] ?? 'Permission for ' . ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title));
                                $displayTitle = $perm->title === 'header_label_access' ? 'Arabic Label Dropdown in Header' : ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title));
                            @endphp
                            <div class="col-md-6 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input permission-checkbox" type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" {{ $role->permissions->contains($perm->id) ? 'checked' : '' }} style="accent-color: #000;">
                                    <label class="custom-control-label permission-label" for="perm-{{ $perm->id }}">
                                        {{ $displayTitle }}
                                    </label>
                                    <div class="permission-description">{{ $description }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach

        <div class="card shadow-sm border-0 mt-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-end align-items-center">
                    <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-link text-muted mr-3">Cancel</a>
                    <button type="submit" class="btn btn-dark px-5 py-2" style="background-color: #000; border-color: #000;">
                        Save Permissions
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
function selectModule(module) {
    $('#module-' + module + ' input.permission-checkbox').prop('checked', true);
}
function clearModule(module) {
    $('#module-' + module + ' input.permission-checkbox').prop('checked', false);
}
</script>
@endpush

