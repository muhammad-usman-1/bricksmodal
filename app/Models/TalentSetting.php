<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TalentSetting extends Model
{
    use HasFactory;

    protected $table = 'talent_settings';

    protected $fillable = [
        'talent_profile_id',
        'email_notifications',
        'push_notifications',
        'shows_updates',
        'show_reminders',
        'payment_alerts',
        'system_updates',
        'language',
        'timezone',
        'date_format',
        'time_format',
        'appearance',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'shows_updates' => 'boolean',
        'show_reminders' => 'boolean',
        'payment_alerts' => 'boolean',
        'system_updates' => 'boolean',
    ];

    /**
     * Get the talent profile that owns the settings.
     */
    public function talentProfile()
    {
        return $this->belongsTo(TalentProfile::class);
    }

    /**
     * Get or create settings for a talent profile.
     */
    public static function getOrCreateForProfile(int $talentProfileId): self
    {
        return static::firstOrCreate(
            ['talent_profile_id' => $talentProfileId],
            [
                'email_notifications' => true,
                'push_notifications' => true,
                'shows_updates' => true,
                'show_reminders' => true,
                'payment_alerts' => false,
                'system_updates' => false,
                'language' => 'English',
                'timezone' => 'UTC',
                'date_format' => 'MM/DD/YYYY',
                'time_format' => '12-hour',
                'appearance' => 'light',
            ]
        );
    }
}

