<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class AuditLogController extends Controller
{
    /**
     * Display a listing of audit logs
     */
    public function index(Request $request): View
    {
        $query = AuditLog::query()->with('user')->orderBy('created_at', 'desc');
        
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
        $auditLog->load('user');
        return view('admin.audit-logs.show', compact('auditLog'));
    }

    /**
     * Export audit logs as CSV
     */
    public function export(Request $request)
    {
        $query = AuditLog::query()->with('user')->orderBy('created_at', 'desc');
        
        // Apply same filters as index
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        
        if ($request->filled('user_type')) {
            $query->where('user_type', $request->user_type);
        }
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('user_email', 'like', "%{$search}%")
                  ->orWhere('user_phone', 'like', "%{$search}%");
            });
        }
        
        // Apply limit if specified (not 'full')
        $limit = $request->input('limit', 'full');
        if ($limit !== 'full' && is_numeric($limit)) {
            $query->limit((int)$limit);
        }
        
        $logs = $query->get();
        
        $filename = 'audit_logs_' . now()->format('Y-m-d_His') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];
        
        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // CSV Headers
            fputcsv($file, [
                'Time (GMT+3)',
                'Event Type',
                'User Type',
                'User ID',
                'User Name',
                'Email',
                'Phone',
                'IP Address',
                'User Agent',
                'Onboarding Step',
                'Steps Completed',
                'Onboarding Completed',
                'Action/Details',
                'Login Successful',
                'Failure Reason',
                'Redirect To',
            ]);
            
            // CSV Data
            foreach ($logs as $log) {
                $time = $log->created_at->setTimezone('Asia/Kuwait')->format('Y-m-d h:i:s A');
                $userName = $log->user ? $log->user->name : '';
                $phone = $log->user_phone ? '+965 ' . $log->user_phone : '';
                
                fputcsv($file, [
                    $time,
                    $log->event_type,
                    $log->user_type,
                    $log->user_id ?? '',
                    $userName,
                    $log->user_email ?? '',
                    $phone,
                    $log->ip_address ?? '',
                    $log->user_agent ?? '',
                    $log->onboarding_step ?? '',
                    $log->onboarding_steps_completed ?? '',
                    $log->onboarding_completed ? 'Yes' : 'No',
                    $log->onboarding_action ?? ($log->login_failure_reason ?? ''),
                    $log->login_successful ? 'Yes' : ($log->login_successful === false ? 'No' : ''),
                    $log->login_failure_reason ?? '',
                    $log->login_redirect_to ?? '',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }
}
