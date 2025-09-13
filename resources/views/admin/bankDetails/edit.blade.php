@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.bankDetail.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.bank-details.update", [$bankDetail->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="talent_profile_id">{{ trans('cruds.bankDetail.fields.talent_profile') }}</label>
                <select class="form-control select2 {{ $errors->has('talent_profile') ? 'is-invalid' : '' }}" name="talent_profile_id" id="talent_profile_id" required>
                    @foreach($talent_profiles as $id => $entry)
                        <option value="{{ $id }}" {{ (old('talent_profile_id') ? old('talent_profile_id') : $bankDetail->talent_profile->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('talent_profile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('talent_profile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.talent_profile_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bank_name">{{ trans('cruds.bankDetail.fields.bank_name') }}</label>
                <input class="form-control {{ $errors->has('bank_name') ? 'is-invalid' : '' }}" type="text" name="bank_name" id="bank_name" value="{{ old('bank_name', $bankDetail->bank_name) }}">
                @if($errors->has('bank_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bank_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.bank_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="account_holder_name">{{ trans('cruds.bankDetail.fields.account_holder_name') }}</label>
                <input class="form-control {{ $errors->has('account_holder_name') ? 'is-invalid' : '' }}" type="text" name="account_holder_name" id="account_holder_name" value="{{ old('account_holder_name', $bankDetail->account_holder_name) }}">
                @if($errors->has('account_holder_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_holder_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.account_holder_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="account_number">{{ trans('cruds.bankDetail.fields.account_number') }}</label>
                <input class="form-control {{ $errors->has('account_number') ? 'is-invalid' : '' }}" type="text" name="account_number" id="account_number" value="{{ old('account_number', $bankDetail->account_number) }}">
                @if($errors->has('account_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('account_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.account_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="iban">{{ trans('cruds.bankDetail.fields.iban') }}</label>
                <input class="form-control {{ $errors->has('iban') ? 'is-invalid' : '' }}" type="text" name="iban" id="iban" value="{{ old('iban', $bankDetail->iban) }}">
                @if($errors->has('iban'))
                    <div class="invalid-feedback">
                        {{ $errors->first('iban') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.iban_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="swift_code">{{ trans('cruds.bankDetail.fields.swift_code') }}</label>
                <input class="form-control {{ $errors->has('swift_code') ? 'is-invalid' : '' }}" type="text" name="swift_code" id="swift_code" value="{{ old('swift_code', $bankDetail->swift_code) }}">
                @if($errors->has('swift_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('swift_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.swift_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="branch">{{ trans('cruds.bankDetail.fields.branch') }}</label>
                <input class="form-control {{ $errors->has('branch') ? 'is-invalid' : '' }}" type="text" name="branch" id="branch" value="{{ old('branch', $bankDetail->branch) }}">
                @if($errors->has('branch'))
                    <div class="invalid-feedback">
                        {{ $errors->first('branch') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.branch_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.bankDetail.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\BankDetail::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $bankDetail->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.bankDetail.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection