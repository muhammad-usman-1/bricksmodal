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
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            // Grant super admin all permissions
            foreach ($permissionsArray as $title => $roleIds) {
                Gate::define($title, function ($user) {
                    return true; // Super admin has all permissions
                });
            }
            return $next($request);
        }

        // For regular admins, check both role-based and module-based permissions
        foreach ($permissionsArray as $title => $roleIds) {
            Gate::define($title, function ($user) use ($roleIds, $title) {
                // First check role-based permissions
                $hasRolePermission = count(array_intersect($user->roles->pluck('id')->toArray(), $roleIds)) > 0;

                if ($hasRolePermission) {
                    return true;
                }

                // Then check module-based permissions for admins
                if ($user->type === 'admin' && method_exists($user, 'hasModulePermission')) {
                    // Map permission titles to module permissions
                    if (str_contains($title, 'casting_requirement') || str_contains($title, 'project')) {
                        return $user->hasModulePermission('project_management');
                    }
                    if (str_contains($title, 'talent')) {
                        return $user->hasModulePermission('talent_management');
                    }
                    if (str_contains($title, 'payment') || str_contains($title, 'casting_application')) {
                        return $user->hasModulePermission('payment_management');
                    }
                    // Special case: user_management_access is used by PaymentDashboardController
                    if ($title === 'user_management_access') {
                        return $user->hasModulePermission('payment_management');
                    }
                }

                return false;
            });
        }

        return $next($request);
    }
}
