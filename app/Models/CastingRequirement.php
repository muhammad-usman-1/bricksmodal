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
        'shoot_date_display',
    ];

    public $table = 'casting_requirements';

    protected $dates = [
        'shoot_date_time',
        'duration',
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

    protected $casts = [
        'outfit' => 'array',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            return;
        }
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

    public function getShootDateDisplayAttribute(): ?string
    {
        $raw = $this->getRawOriginal('shoot_date_time');

        if (! $raw) {
            return null;
        }

        try {
            return Carbon::createFromFormat('Y-m-d H:i:s', $raw)->format('d M Y | h:i A');
        } catch (\Exception $exception) {
            report($exception);

            return $this->shoot_date_time;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function castingApplications()
    {
        return $this->hasMany(CastingApplication::class, 'casting_requirement_id');
    }

    public function getSelectedOutfits()
    {
        if (empty($this->outfit) || !is_array($this->outfit)) {
            return collect();
        }

        return Outfit::whereIn('id', $this->outfit)->orderBy('sort_order')->get();
    }
}
