<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CastingApplication extends Model
{
    use SoftDeletes, HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($application) {
            $admins = User::whereHas('roles', function ($query) {
                $query->where('id', 1); // Admin role ID
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\CastingApplicationSubmitted($application));
            }
        });
    }

    public $table = 'casting_applications';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
        'payment_requested_at',
        'payment_approved_at',
        'payment_released_at',
        'payment_received_at',
    ];

    protected $casts = [
        'payment_requested_at' => 'datetime',
        'payment_approved_at' => 'datetime',
        'payment_released_at' => 'datetime',
        'payment_received_at' => 'datetime',
    ];

    public const PAYMENT_PROCESSED_SELECT = [
        'n/a'     => 'Not Applicable',
        'pending' => 'Pending',
        'paid'    => 'Paid',
    ];

    public const PAYMENT_STATUS_SELECT = [
        'pending'   => 'Pending',
        'requested' => 'Requested',
        'approved'  => 'Approved',
        'released'  => 'Released',
        'received'  => 'Received',
        'rejected'  => 'Rejected',
    ];

    public const STATUS_SELECT = [
        'applied'      => 'Applied',
        'shortlisted'  => 'Shortlisted',
        'rejected'     => 'Rejected',
        'selected'     => 'Selected',
        'did_not_show' => 'Didn\'t Show Up',
    ];

    protected $fillable = [
        'casting_requirement_id',
        'talent_profile_id',
        'rate',
        'rate_offered',
        'talent_notes',
        'admin_notes',
        'status',
        'rating',
        'reviews',
        'payment_status',
        'payment_requested_by_admin_id',
        'payment_requested_at',
        'payment_approved_by_super_admin_id',
        'payment_approved_at',
        'payment_released_at',
        'payment_received_at',
        'payment_rejection_reason',
        'stripe_session_id',
        'stripe_payment_intent',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function casting_requirement()
    {
        return $this->belongsTo(CastingRequirement::class, 'casting_requirement_id');
    }

    public function talent_profile()
    {
        return $this->belongsTo(TalentProfile::class, 'talent_profile_id');
    }

    public function requestedByAdmin()
    {
        return $this->belongsTo(User::class, 'payment_requested_by_admin_id');
    }

    public function approvedBySuperAdmin()
    {
        return $this->belongsTo(User::class, 'payment_approved_by_super_admin_id');
    }

    /**
     * Check if talent can request payment
     */
    public function canRequestPayment(): bool
    {
        return $this->status === 'selected'
            && in_array($this->payment_status, ['pending', 'rejected']);
    }

    /**
     * Check if payment can be approved by super admin
     */
    public function canBeApproved(): bool
    {
        return $this->payment_status === 'requested';
    }

    /**
     * Check if payment can be released
     */
    public function canBeReleased(): bool
    {
        return $this->payment_status === 'approved';
    }

    /**
     * Get payment amount
     */
    public function getPaymentAmount(): float
    {
        return $this->rate_offered ?? $this->rate ?? 0.0;
    }

    /**
     * Get payment status badge class
     */
    public function getPaymentStatusBadgeClass(): string
    {
        return match($this->payment_status) {
            'pending' => 'badge-secondary',
            'requested' => 'badge-info',
            'approved' => 'badge-primary',
            'released' => 'badge-warning',
            'received' => 'badge-success',
            'rejected' => 'badge-danger',
            default => 'badge-secondary',
        };
    }
}
