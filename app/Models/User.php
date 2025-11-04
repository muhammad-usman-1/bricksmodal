<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Support\Facades\Hash;
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
        'stripe_customer_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function getIsAdminAttribute()
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->roles()->where('id', 3)->exists();
    }

    public function hasRole($roleTitle)
    {
        return $this->roles()->where('title', $roleTitle)->exists();
    }

    public function hasPermission($permissionTitle)
    {
        return $this->roles()->whereHas('permissions', function($query) use ($permissionTitle) {
            $query->where('title', $permissionTitle);
        })->exists();
    }

    public function getAllPermissions()
    {
        return $this->roles()->with('permissions')->get()->pluck('permissions')->flatten()->unique('id');
    }

    public function getEmailVerifiedAtAttribute($value)
    {
        if (!$value) return null;
        try {
            return Carbon::parse($value)->format(config('panel.date_format', 'Y-m-d') . ' ' . config('panel.time_format', 'H:i:s'));
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        if (!$value) {
            $this->attributes['email_verified_at'] = null;
            return;
        }
        
        try {
            $this->attributes['email_verified_at'] = Carbon::parse($value)->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            $this->attributes['email_verified_at'] = now()->format('Y-m-d H:i:s');
        }
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

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
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
