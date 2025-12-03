<?php

namespace App\Http\Requests;

use App\Models\Label;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLabelRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('label_edit');
    }

    public function rules()
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique((new Label())->getTable(), 'name')->ignore($this->route('label')),
            ],
        ];
    }
}

