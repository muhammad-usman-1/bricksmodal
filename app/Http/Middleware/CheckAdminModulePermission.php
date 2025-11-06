<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAdminModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $module
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, string $module)
    {
        $user = auth('admin')->user();

        if (!$user) {
            abort(403, 'Not authenticated as admin.');
        }

        // Super admin has access to everything
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Load admin permissions if not already loaded
        if (!$user->relationLoaded('adminPermissions')) {
            $user->load('adminPermissions');
        }

        // Check if admin has the required module permission
        if ($user->hasModulePermission($module)) {
            return $next($request);
        }

        // Redirect with error message if no permission
        abort(403, 'You do not have permission to access this module. Required: ' . $module);
    }
}
