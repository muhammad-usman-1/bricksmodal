<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminPermission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'project_management',
        'talent_management',
        'payment_management',
        'can_make_payments',
    ];

    protected $casts = [
        'project_management' => 'boolean',
        'talent_management' => 'boolean',
        'payment_management' => 'boolean',
        'can_make_payments' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if admin has specific module permission
     */
    public function hasModulePermission(string $module): bool
    {
        return $this->{$module} ?? false;
    }

    /**
     * Check if admin can make payments
     */
    public function canMakePayments(): bool
    {
        return $this->can_make_payments && $this->payment_management;
    }
}
