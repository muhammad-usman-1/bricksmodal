<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TalentMedia extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'talent_media';

    protected $fillable = [
        'talent_profile_id',
        'file_path',
        'type',
    ];

    public function talentProfile()
    {
        return $this->belongsTo(TalentProfile::class);
    }
}
