<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Http\Request;

trait LogsAuditEvents
{
    /**
     * Log an audit event
     */
    protected function logAuditEvent(
        string $eventType,
        string $userType,
        ?int $userId = null,
        ?string $userEmail = null,
        ?string $userPhone = null,
        ?Request $request = null,
        ?string $action = null,
        array $metadata = []
    ): void {
        $request = $request ?? request();
        
        AuditLog::create([
            'event_type' => $eventType,
            'user_type' => $userType,
            'user_id' => $userId,
            'user_email' => $userEmail,
            'user_phone' => $userPhone,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'onboarding_action' => $action,
            'metadata' => !empty($metadata) ? $metadata : null,
            'created_at' => now(),
        ]);
    }
}
