<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckSuperAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('admin')->user();

        // Accept either explicit boolean flag or role-based check
        $isSuper = false;
        if ($user) {
            if (isset($user->is_super_admin) && $user->is_super_admin) {
                $isSuper = true;
            } elseif (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
                $isSuper = true;
            }
        }

        if (!$user || !$isSuper) {
            abort(403, 'This action is only available to Super Admins.');
        }

        return $next($request);
    }
}
