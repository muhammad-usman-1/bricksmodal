<?php

namespace App\Http\Requests;

use App\Models\CastingRequirement;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Gate;

class StoreCastingRequirementRequest extends FormRequest
{
    public function messages()
    {
        return [
            'project_name.required' => 'The project name is required.',
            'shoot_date_time.after' => 'The shoot date must be a future date.',
            'shoot_date_time.date_format' => 'The shoot date must be in the correct format.',
            'outfit.required' => 'Please select at least one outfit option.',
            'outfit.json' => 'Invalid outfit selection format.',
            'count.required' => 'Please specify the number of models needed.',
            'count.min' => 'The number of models must be at least 1.',
            'rate_per_model.required' => 'Please specify the rate per model.',
            'rate_per_model.numeric' => 'The rate must be a valid number.',
            'status.required' => 'Please select a status for this requirement.',
        ];
    }

    public function authorize()
    {
        return Gate::allows('casting_requirement_create');
    }

    protected function prepareForValidation(): void
    {
        if (!$this->filled('shoot_date_time')) {
            return;
        }

        try {
            $normalized = Carbon::parse($this->input('shoot_date_time'))
                ->format(config('panel.date_format') . ' ' . config('panel.time_format'));

            $this->merge([
                'shoot_date_time' => $normalized,
            ]);
        } catch (\Exception $exception) {
            // Leave the original value so the validator can flag the formatting issue.
        }
    }

    public function rules()
    {
        $dateFormat = config('panel.date_format') . ' ' . config('panel.time_format');

        return [
            'project_name' => [
                'string',
                'required',
            ],
            'location' => [
                'string',
                'nullable',
            ],
            'shoot_date_time' => [
                'nullable',
                "date_format:$dateFormat",
                'after:today',
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
            'outfit' => [
                'required',
                'json',
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
