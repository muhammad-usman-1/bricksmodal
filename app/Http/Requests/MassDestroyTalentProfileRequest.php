<?php

namespace App\Http\Requests;

use App\Models\TalentProfile;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;

class MassDestroyTalentProfileRequest extends FormRequest
{
    public function authorize()
    {
        abort_if(Gate::denies('talent_profile_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return true;
    }

    public function rules()
    {
        return [
            'ids'   => 'required|array',
            'ids.*' => 'exists:talent_profiles,id',
        ];
    }
}
