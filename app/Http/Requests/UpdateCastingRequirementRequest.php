<?php

namespace App\Http\Requests;

use App\Models\CastingRequirement;
use App\Models\CastingRequirementModel;
use Carbon\Carbon;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class UpdateCastingRequirementRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $date = $this->input('shoot_date');
        $time = $this->input('shoot_time');

        if ($date) {
            $timeValue = $time ?: '00:00';

            try {
                $dateTime = Carbon::createFromFormat('Y-m-d H:i', $date . ' ' . $timeValue);
                $this->merge([
                    'shoot_date_time' => $dateTime->format(config('panel.date_format') . ' ' . config('panel.time_format')),
                ]);
            } catch (\Exception $exception) {
                $this->merge(['shoot_date_time' => null]);
            }
        }
    }

    public function authorize()
    {
        return Gate::allows('casting_requirement_edit');
    }

    public function rules()
    {
        return [
            'project_name' => [
                'string',
                'required',
            ],
            'client_name' => [
                'string',
                'nullable',
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'shoot_date_time' => [
                'date_format:' . config('panel.date_format') . ' ' . config('panel.time_format'),
                'nullable',
            ],
            'shoot_date' => [
                'nullable',
                'date_format:Y-m-d',
            ],
            'shoot_time' => [
                'nullable',
                'date_format:H:i',
            ],
            'duration' => [
                'nullable',
                'string',
                'max:255',
            ],
            'reference' => [
                'array',
                'nullable',
            ],
            'notes' => [
                'nullable',
                'string',
            ],
            'status' => [
                'required',
            ],
            'models' => [
                'required',
                'array',
                'min:1',
            ],
            'models.*.id' => [
                'nullable',
                'integer',
                'exists:casting_requirement_models,id',
            ],
            'models.*.title' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
            'models.*.rate_decision' => [
                'required',
                Rule::in(array_keys(CastingRequirementModel::RATE_DECISION_OPTIONS)),
            ],
            'models.*.rate' => [
                'required_if:models.*.rate_decision,admin_decide',
                'numeric',
                'min:0',
            ],
            'models.*.gender' => [
                'required',
                Rule::in(array_keys(CastingRequirement::GENDER_SELECT)),
            ],
            'models.*.hair_color' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.age_range_key' => [
                'required',
                Rule::in(array_keys(CastingRequirementModel::AGE_RANGE_OPTIONS)),
            ],
            'models.*.labels' => [
                'array',
                'nullable',
            ],
            'models.*.labels.*' => [
                'integer',
                'exists:labels,id',
            ],
            'models.*.height_range' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.weight_range' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.skin_color' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.eye_color' => [
                'nullable',
                'string',
                'max:120',
            ],
            'models.*.time_slot' => [
                'nullable',
                'string',
                'max:255',
            ],
            'models.*.model_hours' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'models.*.traditional_mode' => [
                'nullable',
                'string',
                Rule::in(['true', 'false']),
            ],
            'models.*.male_top_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.male_bottom_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.male_traditional_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.female_top_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.female_bottom_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.child_top_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.child_bottom_id' => [
                'nullable',
                'integer',
                'exists:outfits,id',
            ],
            'models.*.reference_photo' => [
                'array',
                'nullable',
            ],
            'models.*.reference_photo.*' => [
                'file',
                'image',
                'max:10240',
            ],
        ];
    }
}
