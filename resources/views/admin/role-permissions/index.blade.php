@extends('layouts.admin')
@section('styles')
@parent
<style>
    .role-table thead th {
        text-transform: uppercase;
        font-size: .75rem;
        letter-spacing: .04em;
        color: #6b7280;
        border-bottom: 1px solid #e5e7eb;
        background-color: #f9fafb;
    }
    .role-table tbody td {
        vertical-align: middle;
        border-top: 1px solid #f3f4f6;
        color: #111827;
        padding: 1rem .75rem;
    }
    .permission-badge {
        background-color: #f3f4f6;
        color: #374151;
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 4px 10px;
        font-size: 11px;
        font-weight: 500;
        margin-right: 4px;
        margin-bottom: 4px;
        display: inline-block;
    }
    .role-badge {
        background-color: black;
        color: #ffffff;
        border-radius: 6px;
        padding: 4px 12px;
        font-size: 12px;
        font-weight: 600;
    }
    .payment-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 999px;
    }
    .payment-badge-yes {
        background-color: #ecfdf5;
        color: #059669;
    }
    .payment-badge-no {
        background-color: #fef2f2;
        color: #dc2626;
    }
    .actions { display: inline-flex; gap: 12px; align-items: center; }
    .action-icon {
        font-size: 14px;
        text-decoration: none !important;
        transition: none;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: none !important;
        padding: 0;
    }
    .action-icon:hover, .action-icon:focus, .action-icon:active {
        opacity: 1 !important;
        filter: none !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .action-icon.edit { color: #f59e0b; }
    .action-icon.delete { color: #dc2626; }
</style>
@endsection

@section('content')
<div class="content" style="margin-top: 1rem;">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mb-1" style="color: #101828; font-size: 24px; font-style: normal; font-weight: 400; line-height: 36px;">Role Permission Management</h2>
            <p class="text-muted mb-0">Manage system roles and their assigned permissions</p>
        </div>
        <a href="{{ route('admin.role-permissions.create') }}" class="btn btn-dark" style="background-color: #000; border-color: #000;" data-swal-confirm="Proceed to create a new role?">
            <i class="fas fa-plus mr-1"></i> Add Role
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #ecfdf5; color: #065f46;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 role-table">
                    <thead>
                        <tr>
                            <th style="width: 80px;" class="pl-4">ID</th>
                            <th style="width: 180px;">Role</th>
                            <th>Permissions</th>
                            <th style="width: 150px;" class="text-center">Can Pay</th>
                            <th style="width: 100px;" class="text-right pr-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td class="pl-4 text-muted">#{{ $role->id }}</td>
                                <td>
                                    <span class="role-badge">{{ ucfirst($role->title) }}</span>
                                </td>
                                <td>
                                    @if($role->permissions && $role->permissions->count())
                                        <div class="d-flex flex-wrap">
                                            @foreach($role->permissions as $perm)
                                                <span class="permission-badge">
                                                    {{ ucwords(str_replace(['_','access','management'], [' ','',''],$perm->title)) }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <span class="text-muted small italic">No permissions assigned</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @php
                                        $canPayment = $role->permissions && $role->permissions->contains('title', 'payment_management_access');
                                        $isSuperAdmin = strtolower($role->title) === 'superadmin';
                                    @endphp
                                    @if($canPayment || $isSuperAdmin)
                                        <span class="payment-badge payment-badge-yes">YES</span>
                                    @else
                                        <span class="payment-badge payment-badge-no">NO</span>
                                    @endif
                                </td>
                                <td class="text-right pr-4">
                                    <div class="actions">
                                        <a class="action-icon edit" title="Edit" href="{{ route('admin.role-permissions.edit', $role->id) }}" data-swal-confirm="Edit permissions for this role?">
                                            <i class="far fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST" style="display:inline-block;" data-swal-confirm="Are you sure you want to delete this role?">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="action-icon delete" title="Delete" style="cursor:pointer;">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

