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

        // Ensure roles are loaded with permissions
        if (!$user->relationLoaded('roles')) {
            $user->load('roles.permissions');
        }

        // Check if user has the required module permission
        if ($user->hasModulePermission($module)) {
            return $next($request);
        }

        // Redirect with error message if no permission
        abort(403, 'You do not have permission to access this module. Required: ' . $module);
    }
}
