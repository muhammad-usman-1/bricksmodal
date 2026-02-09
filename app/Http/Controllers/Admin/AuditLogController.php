<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request): View
    {
        $query = AuditLog::query()->orderBy('created_at', 'desc');
        
        // Filter by event type
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        // Filter by user type
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }
        
        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by email or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_email', 'like', "%{$search}%")
                  ->orWhere('user_phone', 'like', "%{$search}%");
            });
        }
        
        $logs = $query->paginate(50)->withQueryString();
        
        // Get statistics
        $stats = [
            'total_logs' => AuditLog::count(),
            'login_success' => AuditLog::where('event_type', 'login_success')->count(),
            'login_failed' => AuditLog::where('event_type', 'login_failed')->count(),
            'onboarding_events' => AuditLog::whereIn('event_type', ['onboarding_step', 'onboarding_completed'])->count(),
            'super_admin_logins' => AuditLog::where('event_type', 'login_success')->where('user_type', 'super_admin')->count(),
            'admin_logins' => AuditLog::where('event_type', 'login_success')->where('user_type', 'admin')->count(),
            'creative_logins' => AuditLog::where('event_type', 'login_success')->where('user_type', 'creative')->count(),
            'talent_logins' => AuditLog::where('event_type', 'login_success')->where('user_type', 'talent')->count(),
        ];
        
        return view('admin.audit-logs.index', compact('logs', 'stats'));
    }
    
    /**
     * Show a single audit log entry
     */
    public function show(AuditLog $auditLog): View
    {
        return view('admin.audit-logs.show', compact('auditLog'));
    }
}
