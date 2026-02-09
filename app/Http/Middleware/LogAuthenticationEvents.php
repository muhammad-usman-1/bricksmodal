<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class LogAuthenticationEvents
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // This middleware will be used to log logout events
        // Login events will be logged directly in the login controllers
        
        $response = $next($request);
        
        // Log logout if user was authenticated
        if ($request->is('admin/logout') || $request->is('talent/logout')) {
            $user = Auth::guard('admin')->user() ?? Auth::guard('talent')->user();
            
            if ($user) {
                $this->logLogout($user, $request);
            }
        }
        
        return $response;
    }
    
    /**
     * Log logout event
     */
    private function logLogout($user, Request $request)
    {
        $userType = $user->type === \App\Models\User::TYPE_ADMIN ? 'admin' : 'talent';
        
        // Check if user has creative role
        if ($userType === 'admin' && $user->isCreative() && !$user->isSuperAdmin()) {
            $userType = 'creative';
        }
        
        AuditLog::create([
            'event_type' => 'logout',
            'user_type' => $userType,
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_phone' => $user->phone_number,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'login_successful' => true,
            'created_at' => now(),
        ]);
    }
}
