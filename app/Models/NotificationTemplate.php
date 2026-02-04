<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
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
}
