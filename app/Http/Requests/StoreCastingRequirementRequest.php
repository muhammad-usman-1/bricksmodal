<?php

namespace App\Http\Requests;

use App\Models\CastingRequirement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class StoreCastingRequirementRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('casting_requirement_create');
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
            'hair_color' => [
                'string',
                'nullable',
            ],
            'age_range' => [
                'string',
                'nullable',
            ],
            'reference' => [
                'array',
            ],
            'count' => [
                'required',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
            'rate_per_model' => [
                'numeric',
                'required',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
