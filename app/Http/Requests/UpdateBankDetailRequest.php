<?php

namespace App\Http\Requests;

use App\Models\BankDetail;
use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Response;

class UpdateBankDetailRequest extends FormRequest
{
    public function authorize()
    {
        return Gate::allows('bank_detail_edit');
    }

    public function rules()
    {
        return [
            'talent_profile_id' => [
                'required',
                'integer',
            ],
            'bank_name' => [
                'string',
                'nullable',
            ],
            'account_holder_name' => [
                'string',
                'nullable',
            ],
            'account_number' => [
                'string',
                'nullable',
            ],
            'iban' => [
                'string',
                'min:14',
                'max:34',
                'nullable',
            ],
            'swift_code' => [
                'string',
                'nullable',
            ],
            'branch' => [
                'string',
                'nullable',
            ],
            'status' => [
                'required',
            ],
        ];
    }
}
