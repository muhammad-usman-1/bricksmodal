@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e5e7eb;
        --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        --pill-blue: #e5f1ff;
        --pill-blue-text: #1d4ed8;
        --pill-green: #e6f7ed;
        --pill-green-text: #15803d;
    }

    body { background: var(--bg); }

    .admin-shell { padding: 8px 0 18px; }
    .admin-head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 12px; }
    .admin-title { color: #101828;
font-size: 24px;
font-style: normal;
font-weight: 400;
line-height: 32px; /* 133.333% */ }
    .admin-sub { margin: 2px 0 0; color: var(--ink-500); font-size: 13px; }
    .add-btn { background: #0f1524; color: #fff; border: none; border-radius: 8px; padding: 9px 14px; font-size: 13px; display: inline-flex; align-items: center; gap: 8px; text-decoration: none; box-shadow: 0 10px 20px rgba(0,0,0,0.12); }
    .add-btn:hover { color: #fff; opacity: 0.9; }

    .admin-card { background: var(--card); border: 1px solid var(--border); border-radius: 12px; box-shadow: var(--shadow); overflow: hidden; }
    .table-wrap { overflow-x: auto; }
    .admin-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .admin-table thead th { background: #f9fafb; color: var(--ink-700); text-transform: uppercase; letter-spacing: 0.4px; font-size: 11px; font-weight: 700; padding: 11px 12px; border-bottom: 1px solid var(--border); white-space: nowrap; }
    .admin-table tbody td { padding: 12px; border-bottom: 1px solid #f0f2f5; color: var(--ink-700); vertical-align: middle; }
    .admin-table tbody tr:last-child td { border-bottom: none; }
    .pill { display: inline-flex; align-items: center; justify-content: center; min-width: 68px; padding: 6px 10px; border-radius: 999px; font-size: 11px; font-weight: 700; border: 1px solid transparent; }
    .pill-blue { background: var(--pill-blue); color: var(--pill-blue-text); border-color: #cce3ff; }
    .pill-green { background: var(--pill-green); color: var(--pill-green-text); border-color: #c7e8d7; }
    .pill-gray { background: #eef1f5; color: #6b7280; border-color: #e1e3e6; }
    .actions { display: inline-flex; gap: 12px; align-items: center; }
    .action-icon { color: #9ca3af; font-size: 14px; text-decoration: none; }
    .action-icon:hover { color: #111827; }

    @media (max-width: 768px) {
        .admin-head { flex-direction: column; align-items: flex-start; }
        .admin-table thead { display: none; }
        .admin-table tbody tr { display: block; margin-bottom: 12px; border: 1px solid var(--border); border-radius: 8px; padding: 10px; background: #fff; }
        .admin-table tbody td { display: flex; justify-content: space-between; border: none; padding: 8px 0; }
        .admin-table tbody td::before { content: attr(data-label); font-weight: 700; color: var(--ink-900); padding-right: 10px; }
    }
</style>

<div class="admin-shell">
    <div class="admin-head">
        <div>
            <h5 class="admin-title">Admin Management</h5>
            <div class="admin-sub">Manage admin users and their permissions.</div>
        </div>
        <a href="{{ route('admin.admin-management.create') }}" class="add-btn"><i class="fas fa-plus"></i> Add New User</a>
    </div>

    <div class="admin-card">
        <div class="table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Permissions</th>
                        <th style="text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($admins as $admin)
                        @php
                            $role = $admin->roles->first();
                            $roleTitle = $role ? strtoupper($role->title) : 'N/A';
                            $permissionCount = $role && $role->permissions ? $role->permissions->count() : 0;
                            $pillClass = $roleTitle === 'ADMIN' ? 'pill-blue' : ($roleTitle === 'CREATIVE' ? 'pill-green' : 'pill-gray');
                        @endphp
                        <tr>
                            <td data-label="ID">{{ $admin->id }}</td>
                            <td data-label="Name">{{ $admin->name }}</td>
                            <td data-label="Email">{{ $admin->email }}</td>
                            <td data-label="Role"><span class="pill {{ $pillClass }}">{{ $roleTitle }}</span></td>
                            <td data-label="Permissions">{{ $permissionCount }} permissions</td>
                            <td data-label="Actions" style="text-align:right;">
                                <div class="actions">
                                    <a class="action-icon" href="{{ route('admin.admin-management.show', $admin) }}" title="View"><i class="far fa-eye"></i></a>
                                    <a class="action-icon" href="{{ route('admin.admin-management.edit', $admin) }}" title="Edit"><i class="far fa-edit"></i></a>
                                    <form action="{{ route('admin.admin-management.destroy', $admin) }}" method="POST" style="display:inline-block;" data-swal-confirm="Are you sure you want to delete this admin?">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="action-icon" title="Delete" style="border:none; background:none; padding:0;">
                                            <i class="far fa-trash-alt"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align:center; padding:18px; color: var(--ink-500);">No admins found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
