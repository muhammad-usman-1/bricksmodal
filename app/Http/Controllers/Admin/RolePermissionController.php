<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RolePermissionController extends Controller
{
    private function ensureSuperAdmin(): void
    {
        $adminUser = auth('admin')->user();
        $isSuper = false;
        if ($adminUser) {
            if (isset($adminUser->is_super_admin) && $adminUser->is_super_admin) {
                $isSuper = true;
            } elseif (method_exists($adminUser, 'isSuperAdmin') && $adminUser->isSuperAdmin()) {
                $isSuper = true;
            }
        }
        abort_if(! $isSuper, Response::HTTP_FORBIDDEN, '403 Forbidden');
    }

    private function groupedPermissions()
    {
        return Permission::all()->groupBy(function ($permission) {
            if (str_contains($permission->title, 'user_')) {
                return 'User Management';
            }
            if (str_contains($permission->title, 'role_')) {
                return 'Role Management';
            }
            if (str_contains($permission->title, 'permission_')) {
                return 'Permission Management';
            }
            if (str_contains($permission->title, 'project_') || str_contains($permission->title, 'casting_')) {
                return 'Project Management';
            }
            if (str_contains($permission->title, 'talent_')) {
                return 'Talent Management';
            }
            if (str_contains($permission->title, 'payment_') || str_contains($permission->title, 'bank_')) {
                return 'Payment Management';
            }
            if (str_contains($permission->title, 'content_') || str_contains($permission->title, 'language_') || str_contains($permission->title, 'outfit_') || str_contains($permission->title, 'email_template_')) {
                return 'Content Management';
            }
            if (str_contains($permission->title, 'system_') || str_contains($permission->title, 'profile_')) {
                return 'System Settings';
            }

            return 'Other';
        });
    }

    public function index()
    {
        $this->ensureSuperAdmin();

        $roles = Role::with('permissions')->get();

        return view('admin.role-permissions.index', compact('roles'));
    }

    public function create()
    {
        $this->ensureSuperAdmin();

        $permissions = $this->groupedPermissions();

        return view('admin.role-permissions.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'title'        => 'required|string|max:120|unique:roles,title',
            'permissions'  => 'array',
            'permissions.*'=> 'exists:permissions,id',
        ]);

        $role = Role::create([
            'title' => strtolower($validated['title']),
        ]);

        $role->permissions()->sync($request->input('permissions', []));

        return redirect()->route('admin.role-permissions.index')->with('success', 'Role created successfully.');
    }

    public function update(Request $request)
    {
        $this->ensureSuperAdmin();

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
        $this->ensureSuperAdmin();

        $permissions = $this->groupedPermissions();

        $role->load('permissions');

        return view('admin.role-permissions.edit', compact('role', 'permissions'));
    }
}
