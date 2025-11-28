<?php

namespace App\Http\Requests;

use App\Models\CastingApplication;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateCastingApplicationRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('casting_application_manage');
    }

    public function rules()
    {
        return [
            'casting_requirement_id' => [
                'required',
                'integer',
            ],
            'talent_profile_id' => [
                'required',
                'integer',
            ],
            'rate' => [
                'numeric',
                'required',
            ],
            'rate_offered' => [
                'numeric',
                'nullable',
            ],
            'status' => [
                'required',
            ],
            'rating' => [
                'nullable',
                'integer',
                'min:-2147483648',
                'max:2147483647',
            ],
            'payment_status' => [
                'nullable',
                'in:pending,requested,approved,released,received,rejected',
            ],
        ];
    }
}
