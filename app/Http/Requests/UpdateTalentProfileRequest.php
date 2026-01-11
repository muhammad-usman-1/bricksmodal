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
                'nullable',
            ],
            'display_name' => [
                'string',
                'max:100',
                'nullable',
            ],
            'verification_notes' => [
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
            'first_name' => [
                'string',
                'nullable',
            ],
            'last_name' => [
                'string',
                'nullable',
            ],
            'nationality' => [
                'string',
                'nullable',
            ],
            'date_of_birth' => [
                'date',
                'nullable',
            ],
            'country_code' => [
                'string',
                'nullable',
            ],
            'mobile_number' => [
                'string',
                'nullable',
            ],
            'verification_status' => [
                'string',
                'nullable',
            ],
            'card_holder_name' => [
                'string',
                'nullable',
            ],
            'skin_tone' => [
                'string',
                'nullable',
            ],
            'hijab_preference' => [
                'string',
                'nullable',
            ],
            'has_visible_tattoos' => [
                'boolean',
                'nullable',
            ],
            'has_piercings' => [
                'boolean',
                'nullable',
            ],
            'labels.*' => [
                'integer',
            ],
            'labels' => [
                'array',
                'nullable',
            ],
            'headshot_center_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'headshot_left_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'headshot_right_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'full_body_front_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'full_body_right_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'full_body_back_path' => [
                'nullable',
                'file',
                'image',
                'max:10240',
            ],
            'id_front_path' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
            'id_back_path' => [
                'nullable',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:10240',
            ],
            'user_id' => [
                'required',
                'integer',
            ],
        ];
    }
}
