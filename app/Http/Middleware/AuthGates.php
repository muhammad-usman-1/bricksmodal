<?php

namespace App\Http\Middleware;

use App\Models\Role;
use Closure;
use Illuminate\Support\Facades\Gate;

class AuthGates
{
    public function handle($request, Closure $next)
    {
        $user = auth()->user();

        if (! $user) {
            return $next($request);
        }

        $roles = Role::with('permissions')->get();
        $permissionsArray = [];

        foreach ($roles as $role) {
            foreach ($role->permissions as $permissions) {
                $permissionsArray[$permissions->title][] = $role->id;
            }
        }

        // Super admins bypass all permission checks
        if ($user->isSuperAdmin()) {
            // Grant super admin all permissions
            foreach ($permissionsArray as $title => $roleIds) {
                Gate::define($title, function ($user) {
                    return true; // Super admin has all permissions
                });
            }
            return $next($request);
        }

        // Define gates based on role permissions
        foreach ($permissionsArray as $title => $roleIds) {
            Gate::define($title, function ($user) use ($roleIds) {
                // Check if user has any role that has this permission
                return count(array_intersect($user->roles->pluck('id')->toArray(), $roleIds)) > 0;
            });
        }

        return $next($request);
    }
}
