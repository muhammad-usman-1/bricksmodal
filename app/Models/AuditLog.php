<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;

    protected $table = 'audit_logs';

    public $timestamps = false; // We only use created_at

    protected $fillable = [
        'event_type',
        'user_type',
        'user_id',
        'user_email',
        'user_phone',
        'ip_address',
        'user_agent',
        'onboarding_step',
        'onboarding_steps_completed',
        'onboarding_completed',
        'onboarding_action',
        'login_successful',
        'login_failure_reason',
        'metadata',
        'created_at',
    ];

    protected $casts = [
        'onboarding_completed' => 'boolean',
        'login_successful' => 'boolean',
        'onboarding_steps_completed' => 'integer',
        'metadata' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the user associated with this log
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope for login events
     */
    public function scopeLoginEvents($query)
    {
        return $query->whereIn('event_type', ['login_success', 'login_failed', 'logout']);
    }

    /**
     * Scope for onboarding events
     */
    public function scopeOnboardingEvents($query)
    {
        return $query->whereIn('event_type', ['onboarding_step', 'onboarding_completed', 'signup']);
    }

    /**
     * Scope for user type
     */
    public function scopeForUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }
}
