<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;
    
    public const ROLE_TALENT = 'talent';
    public const ROLE_ADMIN = 'admin';

    protected $fillable = [
        'key',
        'name',
        'role',
        'category',
        'title_en',
        'title_ar',
        'content_en',
        'content_ar',
        'language_preference',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function scopeForRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
