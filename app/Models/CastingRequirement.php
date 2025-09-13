<?php

namespace App\Models;

use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CastingRequirement extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia, HasFactory;

    protected $appends = [
        'reference',
    ];

    public $table = 'casting_requirements';

    protected $dates = [
        'shoot_date_time',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public const GENDER_SELECT = [
        'male'   => 'Male',
        'female' => 'Female',
        'any'    => 'Any',
    ];

    public const STATUS_SELECT = [
        'advertised' => 'Advertised',
        'processing' => 'Processing',
        'completed'  => 'Completed',
    ];

    protected $fillable = [
        'project_name',
        'client_name',
        'location',
        'shoot_date_time',
        'hair_color',
        'age_range',
        'gender',
        'outfit',
        'count',
        'notes',
        'user_id',
        'rate_per_model',
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getShootDateTimeAttribute($value)
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setShootDateTimeAttribute($value)
    {
        $this->attributes['shoot_date_time'] = $value ? Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function getReferenceAttribute()
    {
        return $this->getMedia('reference');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
