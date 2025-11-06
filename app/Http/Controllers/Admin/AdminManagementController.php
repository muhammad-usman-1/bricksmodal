<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminPermission;
use App\Notifications\AdminAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('type', User::TYPE_ADMIN)
            ->where('id', '!=', auth('admin')->id()) // Exclude current user
            ->with(['adminPermissions', 'roles'])
            ->orderBy('is_super_admin', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.admin-management.index', compact('admins'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.admin-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'project_management' => ['nullable', 'boolean'],
            'talent_management' => ['nullable', 'boolean'],
            'payment_management' => ['nullable', 'boolean'],
            'can_make_payments' => ['nullable', 'boolean'],
        ]);

        // Store plain password for email notification
        $plainPassword = $request->password;

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($plainPassword),
            'type' => User::TYPE_ADMIN,
            'is_super_admin' => false,
        ]);

        // Assign role
        $admin->roles()->attach($request->role_id);

        // Create admin permissions
        $permissions = [
            'project_management' => $request->boolean('project_management'),
            'talent_management' => $request->boolean('talent_management'),
            'payment_management' => $request->boolean('payment_management'),
            'can_make_payments' => $request->boolean('can_make_payments'),
        ];

        AdminPermission::create(array_merge(['user_id' => $admin->id], $permissions));

        // Send email notification to the new admin
        try {
            $admin->notify(new AdminAccountCreated($plainPassword, $permissions));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin account creation email', [
                'admin_id' => $admin->id,
                'email' => $admin->email,
                'error' => $e->getMessage(),
            ]);
        }

        return redirect()->route('admin.admin-management.index')
            ->with('message', 'Admin created successfully and notification email sent.');
    }

    public function edit(User $user)
    {
        if ($user->type !== User::TYPE_ADMIN) {
            abort(404);
        }

        if ($user->isSuperAdmin()) {
            return redirect()->route('admin.admin-management.index')
                ->with('error', 'Cannot edit super admin.');
        }

        $roles = Role::all();
        $user->load('adminPermissions', 'roles');

        return view('admin.admin-management.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->type !== User::TYPE_ADMIN || $user->isSuperAdmin()) {
            abort(403);
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'exists:roles,id'],
            'project_management' => ['nullable', 'boolean'],
            'talent_management' => ['nullable', 'boolean'],
            'payment_management' => ['nullable', 'boolean'],
            'can_make_payments' => ['nullable', 'boolean'],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update role
        $user->roles()->sync([$request->role_id]);

        // Update permissions
        $user->adminPermissions()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'project_management' => $request->boolean('project_management'),
                'talent_management' => $request->boolean('talent_management'),
                'payment_management' => $request->boolean('payment_management'),
                'can_make_payments' => $request->boolean('can_make_payments'),
            ]
        );

        return redirect()->route('admin.admin-management.index')
            ->with('message', 'Admin updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->type !== User::TYPE_ADMIN || $user->isSuperAdmin()) {
            abort(403);
        }

        if ($user->id === auth('admin')->id()) {
            return redirect()->route('admin.admin-management.index')
                ->with('error', 'Cannot delete yourself.');
        }

        // Delete admin permissions first
        $user->adminPermissions()->delete();

        // Detach roles
        $user->roles()->detach();

        // Force delete (permanently remove from database)
        $user->forceDelete();

        return redirect()->route('admin.admin-management.index')
            ->with('message', 'Admin deleted permanently from database.');
    }
}
