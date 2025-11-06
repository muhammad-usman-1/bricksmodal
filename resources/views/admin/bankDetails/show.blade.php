@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.bankDetail.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bank-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.id') }}
                        </th>
                        <td>
                            {{ $bankDetail->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.talent_profile') }}
                        </th>
                        <td>
                            {{ $bankDetail->talent_profile->legal_name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.bank_name') }}
                        </th>
                        <td>
                            {{ $bankDetail->bank_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.account_holder_name') }}
                        </th>
                        <td>
                            {{ $bankDetail->account_holder_name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.account_number') }}
                        </th>
                        <td>
                            {{ $bankDetail->account_number }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.iban') }}
                        </th>
                        <td>
                            {{ $bankDetail->iban }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.swift_code') }}
                        </th>
                        <td>
                            {{ $bankDetail->swift_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.branch') }}
                        </th>
                        <td>
                            {{ $bankDetail->branch }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.bankDetail.fields.status') }}
                        </th>
                        <td>
                            {{ App\Models\BankDetail::STATUS_SELECT[$bankDetail->status] ?? '' }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.bank-details.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection