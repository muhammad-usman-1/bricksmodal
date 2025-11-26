@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Role Permission Management</h5>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-success btn-sm">+ Add Role</a>
    </div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-light">
                    <tr>
                        <th style="width:60px">ID</th>

                        <th style="width:160px">Role</th>
                        <th>Role Permissions</th>
                        <th style="width:120px">Can Make Payments</th>
                        <th style="width:120px">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            
                            <td>
                                <span class="badge badge-primary" style="background:#28a745;color:#fff;">{{ ucfirst($role->title) }}</span>
                            </td>
                            <td>
                                @if($role->permissions && $role->permissions->count())
                                    @foreach($role->permissions as $perm)
                                        <span class="badge" style="background:#5a32e0;color:#fff;margin-right:6px;padding:6px 8px;font-size:12px;">{{ ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title)) }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No permissions</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @php
                                    $canPayment = $role->permissions && $role->permissions->contains('payment_management_access');
                                @endphp
                                @if($canPayment || strtolower($role->title) === 'superadmin')
                                    <span class="badge badge-success">Yes</span>
                                @else
                                    <span class="badge badge-secondary">No</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="{{ route('admin.role-permissions.edit', $role->id) }}" class="btn btn-sm btn-primary" title="Edit Permissions"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this role?');">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" title="Delete Role"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
