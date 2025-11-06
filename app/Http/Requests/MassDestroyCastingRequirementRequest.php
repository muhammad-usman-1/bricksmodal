<?php

namespace App\Http\Requests;

use App\Models\CastingRequirement;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyCastingRequirementRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('casting_requirement_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:casting_requirements,id',
        ];
    }
}
