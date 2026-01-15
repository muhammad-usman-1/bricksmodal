<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;

    protected $table = 'admin_settings';

    protected $fillable = [
        'email_notifications',
        'push_notifications',
        'talent_updates',
        'shoot_reminders',
        'payment_alerts',
        'system_updates',
        'language',
        'timezone',
        'date_format',
        'time_format',
        'appearance',
        'background_image_path',
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'talent_updates' => 'boolean',
        'shoot_reminders' => 'boolean',
        'payment_alerts' => 'boolean',
        'system_updates' => 'boolean',
    ];

    public static function singleton(): self
    {
        return static::firstOrCreate(['id' => 1]);
    }

    /**
     * Get the background image URL, falling back to default if not set.
     * Includes cache-busting timestamp for browser caching.
     */
    public function getBackgroundImageUrlAttribute(): string
    {
        $baseUrl = $this->background_image_path 
            ? asset('storage/' . $this->background_image_path)
            : asset('images/models_bg.png');
        
        // Add cache-bust parameter based on last update time
        // This allows browser caching while invalidating cache when image changes
        $timestamp = $this->updated_at ? $this->updated_at->timestamp : time();
        
        return $baseUrl . '?v=' . $timestamp;
    }
}
