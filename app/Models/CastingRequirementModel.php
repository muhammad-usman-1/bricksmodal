<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\TalentProfile;

class CastingRequirementModel extends Model
{
    use HasFactory;

    protected $table = 'casting_requirement_models';

    protected $guarded = [];

    protected $casts = [
        'rate' => 'float',
        'quantity' => 'integer',
    ];

    public const AGE_RANGE_OPTIONS = [
        '10-15' => ['label' => '10 – 15', 'min' => 10, 'max' => 15],
        '15-20' => ['label' => '15 – 20', 'min' => 15, 'max' => 20],
        '20-25' => ['label' => '20 – 25', 'min' => 20, 'max' => 25],
        '25-30' => ['label' => '25 – 30', 'min' => 25, 'max' => 30],
        '30-40' => ['label' => '30 – 40', 'min' => 30, 'max' => 40],
        '40-50' => ['label' => '40 – 50', 'min' => 40, 'max' => 50],
        '50+'   => ['label' => '50+', 'min' => 50, 'max' => null],
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    public function castingRequirement()
    {
        return $this->belongsTo(CastingRequirement::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'casting_requirement_model_label');
    }

    public function matchesTalentProfile(TalentProfile $profile): bool
    {
        $talentLabels = $profile->labels->pluck('id')->all();
        $talentAge = $profile->date_of_birth ? $profile->date_of_birth->age : null;

        if ($this->gender && $this->gender !== 'any' && $profile->gender && $profile->gender !== $this->gender) {
            return false;
        }

        if ($this->hair_color) {
            if (! $profile->hair_color || strcasecmp($profile->hair_color, $this->hair_color) !== 0) {
                return false;
            }
        }

        if ($this->min_age && ($talentAge === null || $talentAge < $this->min_age)) {
            return false;
        }

        if ($this->max_age && ($talentAge === null || $talentAge > $this->max_age)) {
            return false;
        }

        $requiredLabels = $this->labels->pluck('id')->all();
        if ($requiredLabels && count(array_diff($requiredLabels, $talentLabels)) > 0) {
            return false;
        }

        return true;
    }

    public function scopeMatchesTalent($query, TalentProfile $profile, array $labelIds = [], ?int $age = null)
    {
        $gender = $profile->gender;
        $hairColor = $profile->hair_color ? mb_strtolower($profile->hair_color) : null;

        $query->where(function ($genderQuery) use ($gender) {
            if ($gender) {
                $genderQuery->whereNull('gender')
                    ->orWhere('gender', '')
                    ->orWhere('gender', 'any')
                    ->orWhere('gender', $gender);
            } else {
                $genderQuery->whereNull('gender')->orWhere('gender', '')->orWhere('gender', 'any');
            }
        });

        $query->where(function ($hairQuery) use ($hairColor) {
            if ($hairColor) {
                $hairQuery->whereNull('hair_color')
                    ->orWhere('hair_color', '')
                    ->orWhereRaw('LOWER(hair_color) = ?', [$hairColor]);
            } else {
                $hairQuery->whereNull('hair_color')->orWhere('hair_color', '');
            }
        });

        $query->where(function ($ageQuery) use ($age) {
            if ($age === null) {
                $ageQuery->whereNull('min_age')->whereNull('max_age');
            } else {
                $ageQuery->where(function ($min) use ($age) {
                    $min->whereNull('min_age')->orWhere('min_age', '<=', $age);
                })->where(function ($max) use ($age) {
                    $max->whereNull('max_age')->orWhere('max_age', '>=', $age);
                });
            }
        });

        $query->where(function ($labelQuery) use ($labelIds) {
            if (empty($labelIds)) {
                $labelQuery->whereDoesntHave('labels');
            } else {
                $labelQuery->whereDoesntHave('labels', function ($restricted) use ($labelIds) {
                    $restricted->whereNotIn('labels.id', $labelIds);
                });
            }
        });
    }
}

