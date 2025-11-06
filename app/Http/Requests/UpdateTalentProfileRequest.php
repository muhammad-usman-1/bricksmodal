<?php

namespace App\Http\Requests;

use App\Models\TalentProfile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateTalentProfileRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('talent_profile_edit');
    }

    public function rules()
    {
        return [
            'legal_name' => [
                'string',
                'max:100',
                'required',
            ],
            'display_name' => [
                'string',
                'max:100',
                'nullable',
            ],
            'languages.*' => [
                'integer',
            ],
            'languages' => [
                'required',
                'array',
            ],
            'verification_notes' => [
                'string',
                'nullable',
            ],
            'bio' => [
                'string',
                'nullable',
            ],
            'daily_rate' => [
                'numeric',
                'required',
            ],
            'hourly_rate' => [
                'numeric',
            ],
            'height' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'weight' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'chest' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'waist' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'hips' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'hair_color' => [
                'string',
                'nullable',
            ],
            'eye_color' => [
                'string',
                'nullable',
            ],
            'shoe_size' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'whatsapp_number' => [
                'required',
                'regex:/^\+?[0-9\s\-()]{7,20}$/',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
