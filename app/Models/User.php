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
use Illuminate\Support\Facades\Log;
use PragmaRX\Google2FA\Google2FA;
use Illuminate\Support\Collection;

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
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
        'phone_country_code',
        'phone_number',
        'otp',
        'otp_expires_at',
        'otp_attempts',
        'otp_consumed',
        'otp_channel',
        'type',
        'is_super_admin',
        'first_name',
        'last_name',
        'location',
        'website',
        'bio',
        'role_title',
        'member_since',
        'profile_photo_path',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $casts = [
        'is_super_admin' => 'boolean',
        'two_factor_confirmed_at' => 'datetime',
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
        return $this->roles()->where('title', 'superadmin')->exists();
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->roles()->whereIn('title', ['admin', 'superadmin', 'creative'])->exists();
    }

    /**
     * Check if user is creative
     */
    public function isCreative(): bool
    {
        return $this->roles()->where('title', 'creative')->exists();
    }

    /**
     * Check if admin has specific module permission
     */
    public function hasModulePermission(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has all permissions
        }

        if (!$this->isAdmin()) {
            return false;
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($module) {
            $query->where('title', $module . '_access');
        })->exists();
    }

    /**
     * Check if admin can make payments
     */
    public function canMakePayments(): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->hasModulePermission('payment_management');
    }

    /**
     * Check if user has specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true; // Super admin has all permissions
        }

        return $this->roles()->whereHas('permissions', function ($query) use ($permission) {
            $query->where('title', $permission);
        })->exists();
    }

    /**
     * Assign role to user and inherit permissions
     */
    public function assignRole($role)
    {
        Log::info('assignRole called with role: ' . $role . ' (type: ' . gettype($role) . ')');

        // Check if role is numeric (ID) or string (title)
        if (is_numeric($role)) {
            $role = Role::find((int) $role);
            Log::info('Role found by ID: ' . ($role ? $role->id : 'null'));
        } elseif (is_string($role)) {
            $role = Role::where('title', $role)->first();
            Log::info('Role found by title: ' . ($role ? $role->id : 'null'));
        }

        if ($role) {
            $this->roles()->sync([$role->id]);
            Log::info('Role assigned to user ' . $this->id . ': ' . $role->id);
            // Permissions are automatically inherited through the role relationship
        } else {
            Log::error('Role not found for assignment: ' . $role);
        }
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

    /**
     * Determine if two factor authentication is enabled.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Get the two factor recovery codes for the user.
     */
    public function recoveryCodes(): array
    {
        if (is_null($this->two_factor_recovery_codes)) {
            return [];
        }

        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    /**
     * Replace the given recovery code with a new one in the user's stored codes.
     */
    public function replaceRecoveryCode(string $code): void
    {
        $codes = $this->recoveryCodes();
        $this->two_factor_recovery_codes = encrypt(json_encode(array_values(array_diff($codes, [$code]))));
        $this->save();
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = $this->generateRecoveryCode();
        }

        $this->two_factor_recovery_codes = encrypt(json_encode($codes));
        $this->save();

        return $codes;
    }

    /**
     * Generate a single recovery code.
     */
    protected function generateRecoveryCode(): string
    {
        return sprintf('%s-%s-%s-%s',
            str()->random(4),
            str()->random(4),
            str()->random(4),
            str()->random(4)
        );
    }

    /**
     * Verify the two-factor authentication code.
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (is_null($this->two_factor_secret)) {
            return false;
        }

        $google2fa = new Google2FA();
        $secret = decrypt($this->two_factor_secret);

        // Check if it's a recovery code first (recovery codes contain dashes or are longer)
        $recoveryCodes = $this->recoveryCodes();
        if (!empty($recoveryCodes)) {
            // Check exact match first
            if (in_array($code, $recoveryCodes)) {
                $this->replaceRecoveryCode($code);
                return true;
            }
            // Also check without dashes/spaces
            $codeNormalized = preg_replace('/[\s-]/', '', $code);
            foreach ($recoveryCodes as $recoveryCode) {
                if (preg_replace('/[\s-]/', '', $recoveryCode) === $codeNormalized) {
                    $this->replaceRecoveryCode($recoveryCode);
                    return true;
                }
            }
        }

        // For TOTP codes, ensure we have only digits
        $totpCode = preg_replace('/\D/', '', $code);
        
        // TOTP codes are typically 6 digits, but some apps might show 5-8 digits
        // Pad with leading zeros if less than 6 digits, or use as-is if 6+ digits
        if (strlen($totpCode) < 6) {
            $totpCode = str_pad($totpCode, 6, '0', STR_PAD_LEFT);
        } elseif (strlen($totpCode) > 6) {
            // If longer than 6, take the last 6 digits (some apps show 7-8 digits)
            $totpCode = substr($totpCode, -6);
        }

        // Verify TOTP code
        return $google2fa->verifyKey($secret, $totpCode, 2); // 2 = 2 time windows tolerance
    }

}
