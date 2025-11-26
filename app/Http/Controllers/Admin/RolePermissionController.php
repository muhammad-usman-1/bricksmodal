<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionController extends Controller
{
    public function index()
    {
        // Only superadmin can access this
        $adminUser = auth('admin')->user();
        $isSuper = false;
        if ($adminUser) {
            if (isset($adminUser->is_super_admin) && $adminUser->is_super_admin) {
                $isSuper = true;
            } elseif (method_exists($adminUser, 'isSuperAdmin') && $adminUser->isSuperAdmin()) {
                $isSuper = true;
            }
        }
        abort_if(!$isSuper, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $roles = Role::with('permissions')->get();
        $permissions = Permission::all()->groupBy(function($permission) {
            // Group permissions by module
            if (str_contains($permission->title, 'user_')) return 'User Management';
            if (str_contains($permission->title, 'role_')) return 'Role Management';
            if (str_contains($permission->title, 'permission_')) return 'Permission Management';
            if (str_contains($permission->title, 'project_') || str_contains($permission->title, 'casting_')) return 'Project Management';
            if (str_contains($permission->title, 'talent_')) return 'Talent Management';
            if (str_contains($permission->title, 'payment_') || str_contains($permission->title, 'bank_')) return 'Payment Management';
            if (str_contains($permission->title, 'content_') || str_contains($permission->title, 'language_') || str_contains($permission->title, 'outfit_') || str_contains($permission->title, 'email_template_')) return 'Content Management';
            if (str_contains($permission->title, 'system_') || str_contains($permission->title, 'profile_')) return 'System Settings';
            return 'Other';
        });

        return view('admin.role-permissions.index', compact('roles', 'permissions'));
    }

    public function update(Request $request)
    {
        // Only superadmin can access this
        $adminUser = auth('admin')->user();
        $isSuper = false;
        if ($adminUser) {
            if (isset($adminUser->is_super_admin) && $adminUser->is_super_admin) {
                $isSuper = true;
            } elseif (method_exists($adminUser, 'isSuperAdmin') && $adminUser->isSuperAdmin()) {
                $isSuper = true;
            }
        }
        abort_if(!$isSuper, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'array',
            'permissions.*.*' => 'exists:permissions,id'
        ]);

        // Update permissions for all roles
        foreach ($request->input('permissions', []) as $roleId => $permissionIds) {
            $role = Role::find($roleId);
            if ($role) {
                $role->permissions()->sync($permissionIds ?? []);
            }
        }

        return redirect()->route('admin.role-permissions.index')->with('success', 'Permissions updated successfully for all roles.');
    }

    /**
     * Show edit form for a single role's permissions
     */
    public function edit(Role $role)
    {
        // Only superadmin can access this
        $adminUser = auth('admin')->user();
        $isSuper = false;
        if ($adminUser) {
            if (isset($adminUser->is_super_admin) && $adminUser->is_super_admin) {
                $isSuper = true;
            } elseif (method_exists($adminUser, 'isSuperAdmin') && $adminUser->isSuperAdmin()) {
                $isSuper = true;
            }
        }
        abort_if(!$isSuper, Response::HTTP_FORBIDDEN, '403 Forbidden');

        $permissions = Permission::all()->groupBy(function($permission) {
            if (str_contains($permission->title, 'user_')) return 'User Management';
            if (str_contains($permission->title, 'role_')) return 'Role Management';
            if (str_contains($permission->title, 'permission_')) return 'Permission Management';
            if (str_contains($permission->title, 'project_') || str_contains($permission->title, 'casting_')) return 'Project Management';
            if (str_contains($permission->title, 'talent_')) return 'Talent Management';
            if (str_contains($permission->title, 'payment_') || str_contains($permission->title, 'bank_')) return 'Payment Management';
            if (str_contains($permission->title, 'content_') || str_contains($permission->title, 'language_') || str_contains($permission->title, 'outfit_') || str_contains($permission->title, 'email_template_')) return 'Content Management';
            if (str_contains($permission->title, 'system_') || str_contains($permission->title, 'profile_')) return 'System Settings';
            return 'Other';
        });

        $role->load('permissions');

        return view('admin.role-permissions.edit', compact('role', 'permissions'));
    }
}
