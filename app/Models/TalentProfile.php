<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\CastingApplication;

class TalentProfile extends Model
{
    use SoftDeletes, HasFactory;

    protected static function boot()
    {
        parent::boot();

        static::created(function ($talentProfile) {
            $admins = User::whereHas('roles', function ($query) {
                $query->where('id', 1); // Admin role ID
            })->get();

            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\TalentProfileSubmitted($talentProfile));
            }
        });
    }

    public $table = 'talent_profiles';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const VERIFICATION_STATUS_SELECT = [
        'pending'      => 'Pending',
        'approved'     => 'Approved',
        'rejected'     => 'Rejected',
        'under_review' => 'Under Review',
    ];

    public const SKIN_TONE_SELECT = [
        'very_fair'  => 'Very Fair',
        'fair'       => 'Fair',
        'light'      => 'Light',
        'medium'     => 'Medium',
        'olive'      => 'Olive',
        'tan'        => 'Tan',
        'brown'      => 'Brown',
        'dark_brown' => 'Dark Brown',
        'deep'       => 'Deep',
    ];

    protected $fillable = [
        'legal_name',
        'display_name',
        'verification_status',
        'verification_notes',
        'bio',
        'daily_rate',
        'hourly_rate',
        'date_of_birth',
        'gender',
        'height',
        'weight',
        'chest',
        'waist',
        'hips',
        'skin_tone',
        'hair_color',
        'eye_color',
        'shoe_size',
        'whatsapp_number',
        'card_number',
        'card_holder_name',
        'user_id',
        'id_front_path',
        'id_back_path',
        'headshot_center_path',
        'headshot_left_path',
        'headshot_right_path',
        'full_body_front_path',
        'full_body_right_path',
        'full_body_back_path',
        'onboarding_step',
        'onboarding_completed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = [
        'card_number', // Hide card number from JSON responses
    ];

    protected $casts = [
        'date_of_birth'           => 'date',
        'onboarding_completed_at' => 'datetime',
        'height'                  => 'float',
        'weight'                  => 'float',
        'chest'                   => 'float',
        'waist'                   => 'float',
        'hips'                    => 'float',
        'shoe_size'               => 'float',
        'daily_rate'              => 'float',
        'hourly_rate'             => 'float',
    ];

    public function hasCompletedOnboarding(): bool
    {
        return ! is_null($this->onboarding_completed_at);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function languages()
    {
        return $this->belongsToMany(Language::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function castingApplications()
    {
        return $this->hasMany(CastingApplication::class, 'talent_profile_id');
    }

    /**
     * Check if talent has card details stored
     */
    public function hasCardDetails(): bool
    {
        return !empty($this->card_number);
    }

    /**
     * Get masked card number (show only last 4 digits)
     */
    public function getMaskedCardNumber(): string
    {
        if (empty($this->card_number)) {
            return 'No card on file';
        }

        $cardNumber = $this->card_number;
        $length = strlen($cardNumber);

        if ($length <= 4) {
            return str_repeat('*', $length);
        }

        return str_repeat('*', $length - 4) . substr($cardNumber, -4);
    }

    /**
     * Set card number with basic sanitization
     */
    public function setCardNumberAttribute($value)
    {
        // Remove spaces and dashes for storage
        $this->attributes['card_number'] = $value ? preg_replace('/[\s\-]/', '', $value) : null;
    }
}
