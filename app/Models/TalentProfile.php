<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalentProfile extends Model
{
    use SoftDeletes, HasFactory;

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
        'height',
        'weight',
        'chest',
        'waist',
        'hips',
        'skin_tone',
        'hair_color',
        'eye_color',
        'shoe_size',
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

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
}
