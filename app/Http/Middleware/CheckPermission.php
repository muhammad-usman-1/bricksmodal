<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$permissions): Response
    {
        if (!auth('admin')->check()) {
            abort(401, 'Unauthorized');
        }

        $user = auth('admin')->user();

        // Allow all admin users to access everything (no permission restrictions)
        if ($user->type === 'admin') {
            return $next($request);
        }

        abort(403, 'Access denied. Insufficient permissions.');
    }
}
