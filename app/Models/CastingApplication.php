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
    ];

    public const PAYMENT_PROCESSED_SELECT = [
        'n/a'     => 'Not Applicable',
        'pending' => 'Pending',
        'paid'    => 'Paid',
    ];

    public const STATUS_SELECT = [
        'applied'      => 'Applied',
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
        'payment_processed',
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
}
