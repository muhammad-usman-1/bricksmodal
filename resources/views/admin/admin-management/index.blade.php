@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Admin Management</h5>
            <a href="{{ route('admin.admin-management.create') }}" class="btn btn-success btn-sm">
                <i class="fas fa-plus"></i> Add New User
            </a>
        </div>
    </div>

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('message') }}
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

        @php $impersonating = session('impersonate.original_admin_id'); @endphp
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover">
                <thead>
                    <tr>
                        <th width="5%">ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Role Permissions</th>

                        <th width="15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->id }}</td>
                            <td>
                                {{ $admin->name }}
                                @if($admin->is_super_admin)
                                    <span class="badge badge-danger">Super Admin</span>
                                @endif
                            </td>
                            <td>{{ $admin->email }}</td>
                            <td>
                                @foreach($admin->roles as $role)
                                    <span class="badge badge-info">{{ ucfirst($role->title) }}</span>
                                @endforeach
                            </td>
                            <td>
                                @if($admin->isSuperAdmin())
                                    <span class="badge badge-success">All Permissions</span>
                                @else
                                    @php $role = $admin->roles->first(); @endphp
                                    @if($role && $role->permissions && $role->permissions->count() > 0)
                                        @foreach($role->permissions as $permission)
                                            <span class="badge badge-primary badge-sm">{{ ucwords(str_replace(['_', 'management', 'access'], [' ', '', ''], $permission->title)) }}</span>
                                        @endforeach
                                    @else
                                        <span class="badge badge-secondary">No Permissions</span>
                                    @endif
                                @endif
                            </td>
                           
                            <td>
                                @if(!$admin->is_super_admin)
                                    <a href="{{ route('admin.admin-management.edit', $admin) }}" class="btn btn-xs btn-info">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST" style="display: inline-block;" data-swal-confirm="Are you sure you want to delete this admin?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-xs btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                    @can('impersonate_user')
                                        @if(!$impersonating && auth('admin')->id() !== $admin->id)
                                            <form action="{{ route('admin.impersonate.start', $admin) }}" method="POST" style="display:inline-block" data-swal-confirm="{{ trans('global.impersonate_confirm', ['name' => $admin->name]) }}">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-warning">
                                                    <i class="fas fa-user-secret"></i> {{ trans('global.impersonate') }}
                                                </button>
                                            </form>
                                        @endif
                                    @endcan
                                @else
                                    <span class="text-muted">Protected</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No admins found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
