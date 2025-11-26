<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AdminPermission;
use App\Notifications\AdminAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    public function index()
    {
        $admins = User::where('type', User::TYPE_ADMIN)
            ->where('id', '!=', auth('admin')->id()) // Exclude current user
            ->with(['roles.permissions'])
            ->orderBy('is_super_admin', 'desc')
            ->orderBy('name')
            ->get();

        return view('admin.admin-management.index', compact('admins'));
    }

    public function create()
    {
        // Only superadmin can create admin accounts
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Only superadmin can create admin accounts.');
        }

        $roles = Role::all();
        return view('admin.admin-management.create', compact('roles'));
    }

    public function store(Request $request)
    {
        // Only superadmin can create admin accounts
        if (!auth('admin')->user()->isSuperAdmin()) {
            abort(403, 'Only superadmin can create admin accounts.');
        }

        // Validate input data
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', 'min:2'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role_id' => ['required', 'integer', 'exists:roles,id'],
        ]);

        try {
            // Start database transaction for atomicity
            DB::beginTransaction();

            // Create the admin user
            $admin = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'type' => User::TYPE_ADMIN,
                'is_super_admin' => false,
            ]);

            // Assign the selected role
            $admin->assignRole($validated['role_id']);

            // Load the role with permissions for notification
            $role = Role::with('permissions')->find($validated['role_id']);

            // Commit the transaction
            DB::commit();

            // Send notification email (outside transaction to avoid rollback on email failure)
            try {
                $permissions = $role ? $role->permissions->pluck('title')->toArray() : [];
                $admin->notify(new AdminAccountCreated($validated['password'], $permissions));

                Log::info('Admin account created successfully', [
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'role_id' => $validated['role_id'],
                    'created_by' => auth('admin')->id(),
                ]);

                return redirect()->route('admin.admin-management.index')
                    ->with('success', 'Admin account created successfully. Login credentials have been sent to the admin\'s email.');
            } catch (\Exception $e) {
                Log::warning('Admin account created but email notification failed', [
                    'admin_id' => $admin->id,
                    'admin_email' => $admin->email,
                    'error' => $e->getMessage(),
                ]);

                return redirect()->route('admin.admin-management.index')
                    ->with('warning', 'Admin account created successfully, but email notification could not be sent. Please share the login credentials manually.');
            }

        } catch (\Exception $e) {
            // Rollback transaction on any error
            DB::rollBack();

            Log::error('Failed to create admin account', [
                'error' => $e->getMessage(),
                'input' => $request->only(['name', 'email', 'role_id']),
                'created_by' => auth('admin')->id(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to create admin account. Please try again.');
        }
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
        $user->load('roles');

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
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Update role - permissions are automatically inherited
        $user->assignRole($request->role_id);

        return redirect()->route('admin.admin-management.index')
            ->with('message', 'Admin updated successfully with new role assignment.');
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

        // Detach roles
        $user->roles()->detach();

        // Force delete (permanently remove from database)
        $user->forceDelete();

        return redirect()->route('admin.admin-management.index')
            ->with('message', 'Admin deleted permanently from database.');
    }
}
