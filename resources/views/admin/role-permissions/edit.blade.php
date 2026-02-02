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
                            <div class="col-md-3 mb-3">
                                <div class="custom-control custom-checkbox">
                                    <input class="custom-control-input permission-checkbox" type="checkbox" name="permissions[{{ $role->id }}][]" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" {{ $role->permissions->contains($perm->id) ? 'checked' : '' }}>
                                    <label class="custom-control-label permission-label" for="perm-{{ $perm->id }}">
                                        {{ $perm->title === 'header_label_access' ? 'Arabic Label Dropdown in Header' : ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title)) }}
                                    </label>
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

