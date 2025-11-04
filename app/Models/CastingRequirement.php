<?php

namespace App\Models;

use App\Support\OutfitOptions;
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
        'outfit_summary',
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
        if (! extension_loaded('gd') && ! extension_loaded('imagick')) {
            return;
        }
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function getShootDateTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('panel.date_format') . ' ' . config('panel.time_format')) : null;
    }

    public function setShootDateTimeAttribute($value)
    {
        if (!$value) {
            $this->attributes['shoot_date_time'] = null;
            return;
        }

        try {
            // Try parsing with the configured format first
            $date = Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $value);
        } catch (\Exception $e) {
            // If that fails, try flexible parsing
            try {
                $date = Carbon::parse($value);
            } catch (\Exception $e) {
                // If all parsing fails, set to null
                $this->attributes['shoot_date_time'] = null;
                return;
            }
        }

        $this->attributes['shoot_date_time'] = $date->format('Y-m-d H:i:s');
    }

    public function getReferenceAttribute()
    {
        return $this->getMedia('reference');
    }

    public function getOutfitAttribute($value)
    {
        if ($value === null) {
            return OutfitOptions::emptySelection();
        }

        if (is_array($value)) {
            return OutfitOptions::normalize($value);
        }

        $decoded = json_decode($value, true);

        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            return OutfitOptions::normalize($decoded);
        }

        return $value;
    }

    public function setOutfitAttribute($value): void
    {
        if (is_string($value)) {
            $decoded = json_decode($value, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $value = $decoded;
            } else {
                $this->attributes['outfit'] = $value;

                return;
            }
        }

        if (is_array($value)) {
            $this->attributes['outfit'] = json_encode(OutfitOptions::normalize($value));

            return;
        }

        $this->attributes['outfit'] = null;
    }

    public function getOutfitSummaryAttribute(): string
    {
        return OutfitOptions::summarize($this->outfit);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function castingApplications()
    {
        return $this->hasMany(CastingApplication::class, 'casting_requirement_id');
    }
}
