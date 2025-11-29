@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Create Role & Assign Permissions</h5>
        <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary btn-sm">Back to list</a>
    </div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('admin.role-permissions.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title">Role Name</label>
                <input type="text" id="title" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. Creative Manager" required>
                <small class="text-muted">Use a descriptive name for this role.</small>
            </div>

            @foreach($permissions as $module => $modulePermissions)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>{{ $module }}</strong>
                        <div>
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="selectModule('{{ str_replace(' ', '-', strtolower($module)) }}')">Select All</button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" onclick="clearModule('{{ str_replace(' ', '-', strtolower($module)) }}')">Clear</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row" id="module-{{ str_replace(' ', '-', strtolower($module)) }}">
                            @foreach($modulePermissions as $perm)
                                <div class="col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input permission-checkbox" type="checkbox" name="permissions[]" value="{{ $perm->id }}" id="perm-{{ $perm->id }}" {{ in_array($perm->id, old('permissions', [])) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="perm-{{ $perm->id }}">{{ ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title)) }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endforeach

            <div class="d-flex justify-content-between align-items-center mt-3">
                <div>
                    <button type="submit" class="btn btn-success">Create Role</button>
                    <a href="{{ route('admin.role-permissions.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
                <div class="text-muted">You can edit these permissions later.</div>
            </div>
        </form>
    </div>
</div>

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

@endsection

