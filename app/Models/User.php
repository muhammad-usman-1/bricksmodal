<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Hash;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use SoftDeletes, Notifiable, HasFactory;

    public $table = 'users';

    public const TYPE_ADMIN = 'admin';
    public const TYPE_TALENT = 'talent';

    protected $hidden = [
        'remember_token',
        'password',
    ];

    public const TYPE_SELECT = [
        'admin'  => 'Admin',
        'talent' => 'Talent',
    ];

    public const OTP_CHANNEL_SELECT = [
        'sms'      => 'SMS',
        'whatsapp' => 'Whatsapp',
        'voice'    => 'Voice',
    ];

    protected $dates = [
        'email_verified_at',
        'otp_expires_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'phone_country_code',
        'phone_number',
        'otp',
        'otp_expires_at',
        'otp_attempts',
        'otp_consumed',
        'otp_channel',
        'type',
        'is_super_admin',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_super_admin' => 'boolean',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function talentProfile()
    {
        return $this->hasOne(TalentProfile::class);
    }

    public function adminPermissions()
    {
        return $this->hasOne(AdminPermission::class);
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->type === self::TYPE_ADMIN && $this->is_super_admin;
    }

    /**
     * Check if admin has specific module permission
     */
    public function hasModulePermission(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has all permissions
        }

        if ($this->type !== self::TYPE_ADMIN) {
            return false;
        }

        return $this->adminPermissions?->hasModulePermission($module) ?? false;
    }

    /**
     * Check if admin can make payments
     */
    public function canMakePayments(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->adminPermissions?->canMakePayments() ?? false;
    }

    public function getOtpExpiresAtAttribute($value)
    {
        if (! $value) {
            $this->attributes['otp_expires_at'] = null;
            return;
        }

        // If a DateTime/Carbon instance is provided, format it directly
        if ($value instanceof DateTimeInterface) {
            $this->attributes['otp_expires_at'] = $value->format('Y-m-d H:i:s');
            return;
        }

        // Try to parse using configured panel formats, otherwise fallback to Carbon::parse
        try {
            $this->attributes['otp_expires_at'] = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s');
        } catch (\Throwable $e) {
            $this->attributes['otp_expires_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        }
    }


}
