<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    // Middleware is handled at route level

    /**
     * Display a listing of admin users.
     */
    public function index()
    {
        // Get all admin users with standard admin roles
        $admins = User::where('type', User::TYPE_ADMIN)
            ->whereHas('roles', function($query) {
                $query->whereIn('title', ['Admin', 'Super Admin', 'Manager', 'Production Manager', 'Editor', 'Content Creator', 'Finance Manager']);
            })
            ->with('roles')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.admins.index', compact('admins'));
    }

    /**
     * Show the form for creating a new admin.
     */
    public function create()
    {
        $roles = Role::whereIn('title', ['Admin', 'Super Admin', 'Manager', 'Production Manager', 'Editor', 'Content Creator', 'Finance Manager'])->get();
        $permissions = Permission::all();
        return view('admin.admins.create', compact('roles', 'permissions'));
    }

    /**
     * Store a newly created admin in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Use database transaction to ensure all operations complete before redirect
        return DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->mobile_number,
                'type' => User::TYPE_ADMIN,
                'email_verified_at' => now(),
            ]);

            // Always assign the selected standard role first
            $user->roles()->attach($request->role_id);

            // If custom permissions are specified, create additional permissions for this user
            // Note: We could enhance this further by creating a user-specific permission system
            // For now, the selected role (Admin, Manager, Super Admin) will be the primary role displayed

            // Log the successful creation
            Log::info('Admin user created successfully', [
                'admin_id' => $user->id,
                'admin_name' => $user->name,
                'admin_email' => $user->email,
                'created_by' => auth()->id(),
                'roles' => $user->fresh()->roles()->pluck('title')->toArray()
            ]);

            return redirect()->route('admin.admins.index')
                             ->with('success', 'Admin created successfully.');
        });
    }

    /**
     * Display the specified admin.
     */
    public function show(User $admin)
    {
        // Ensure the user is an admin role
        $adminRoles = ['Admin', 'Super Admin', 'Manager', 'Production Manager', 'Editor', 'Content Creator', 'Finance Manager'];
        $hasAdminRole = false;

        foreach ($adminRoles as $role) {
            if ($admin->hasRole($role)) {
                $hasAdminRole = true;
                break;
            }
        }

        if (!$hasAdminRole) {
            abort(404);
        }

        return view('admin.admins.show', compact('admin'));
    }

    /**
     * Show the form for editing the specified admin.
     */
    public function edit(User $admin)
    {
        // Ensure the user is an admin role
        $adminRoles = ['Admin', 'Super Admin', 'Manager', 'Production Manager', 'Editor', 'Content Creator', 'Finance Manager'];
        $hasAdminRole = false;

        foreach ($adminRoles as $role) {
            if ($admin->hasRole($role)) {
                $hasAdminRole = true;
                break;
            }
        }

        if (!$hasAdminRole) {
            abort(404);
        }

        $roles = Role::whereIn('title', $adminRoles)->get();
        $permissions = Permission::all();
        return view('admin.admins.edit', compact('admin', 'roles', 'permissions'));
    }

    /**
     * Update the specified admin in storage.
     */
    public function update(Request $request, User $admin)
    {
        // Ensure the user is an admin role
        $adminRoles = ['Admin', 'Super Admin', 'Manager', 'Production Manager', 'Editor', 'Content Creator', 'Finance Manager'];
        $hasAdminRole = false;

        foreach ($adminRoles as $role) {
            if ($admin->hasRole($role)) {
                $hasAdminRole = true;
                break;
            }
        }

        if (!$hasAdminRole) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($admin->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role_id' => 'required|exists:roles,id',
            'mobile_number' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->mobile_number,
        ]);

        if ($request->filled('password')) {
            $admin->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // Remove old custom roles (clean up any existing custom roles)
        $oldCustomRoles = $admin->roles()->where('title', 'LIKE', 'Custom_%')->pluck('id')->toArray();
        if (!empty($oldCustomRoles)) {
            $admin->roles()->detach($oldCustomRoles);
            // Delete the old custom roles completely
            Role::whereIn('id', $oldCustomRoles)->delete();
        }

        // Always assign the selected standard role
        $admin->roles()->sync([$request->role_id]);

        // Note: Custom permissions functionality can be enhanced in the future
        // For now, we use the standard role system

        return redirect()->route('admin.admins.index')
                         ->with('success', 'Admin updated successfully.');
    }

    /**
     * Remove the specified admin from storage.
     */
    public function destroy(User $admin)
    {
        // Ensure the user is an admin
        if (!$admin->hasRole('Admin') && !$admin->hasRole('Super Admin')) {
            abort(404);
        }

        // Prevent deleting yourself
        if ($admin->id === auth()->id()) {
            return back()->with('error', 'You cannot delete yourself.');
        }

        // Prevent deleting the last super admin
        if ($admin->hasRole('Super Admin')) {
            $superAdminCount = User::whereHas('roles', function($query) {
                $query->where('title', 'Super Admin');
            })->count();

            if ($superAdminCount <= 1) {
                return back()->with('error', 'Cannot delete the last Super Admin.');
            }
        }

        $admin->delete();

        return redirect()->route('admin.admins.index')
                         ->with('success', 'Admin deleted successfully.');
    }
}
