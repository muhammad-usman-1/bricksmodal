@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.users.update", [$user->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.user.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">
                        {{ $errors->first('email') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="password">{{ trans('cruds.user.fields.password') }}</label>
                <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password">
                @if($errors->has('password'))
                    <div class="invalid-feedback">
                        {{ $errors->first('password') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.password_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="roles">{{ trans('cruds.user.fields.roles') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('roles') ? 'is-invalid' : '' }}" name="roles[]" id="roles" multiple required>
                    @foreach($roles as $id => $role)
                        <option value="{{ $id }}" {{ (in_array($id, old('roles', [])) || $user->roles->contains($id)) ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </select>
                @if($errors->has('roles'))
                    <div class="invalid-feedback">
                        {{ $errors->first('roles') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.roles_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_country_code">{{ trans('cruds.user.fields.phone_country_code') }}</label>
                <input class="form-control {{ $errors->has('phone_country_code') ? 'is-invalid' : '' }}" type="text" name="phone_country_code" id="phone_country_code" value="{{ old('phone_country_code', $user->phone_country_code) }}" required>
                @if($errors->has('phone_country_code'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_country_code') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.phone_country_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_number">{{ trans('cruds.user.fields.phone_number') }}</label>
                <input class="form-control {{ $errors->has('phone_number') ? 'is-invalid' : '' }}" type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" required>
                @if($errors->has('phone_number'))
                    <div class="invalid-feedback">
                        {{ $errors->first('phone_number') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.phone_number_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="otp">{{ trans('cruds.user.fields.otp') }}</label>
                <input class="form-control {{ $errors->has('otp') ? 'is-invalid' : '' }}" type="text" name="otp" id="otp" value="{{ old('otp', $user->otp) }}">
                @if($errors->has('otp'))
                    <div class="invalid-feedback">
                        {{ $errors->first('otp') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.otp_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="otp_expires_at">{{ trans('cruds.user.fields.otp_expires_at') }}</label>
                <input class="form-control datetime {{ $errors->has('otp_expires_at') ? 'is-invalid' : '' }}" type="text" name="otp_expires_at" id="otp_expires_at" value="{{ old('otp_expires_at', $user->otp_expires_at) }}">
                @if($errors->has('otp_expires_at'))
                    <div class="invalid-feedback">
                        {{ $errors->first('otp_expires_at') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.otp_expires_at_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="otp_attempts">{{ trans('cruds.user.fields.otp_attempts') }}</label>
                <input class="form-control {{ $errors->has('otp_attempts') ? 'is-invalid' : '' }}" type="number" name="otp_attempts" id="otp_attempts" value="{{ old('otp_attempts', $user->otp_attempts) }}" step="1">
                @if($errors->has('otp_attempts'))
                    <div class="invalid-feedback">
                        {{ $errors->first('otp_attempts') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.otp_attempts_helper') }}</span>
            </div>
            <div class="form-group">
                <div class="form-check {{ $errors->has('otp_consumed') ? 'is-invalid' : '' }}">
                    <input type="hidden" name="otp_consumed" value="0">
                    <input class="form-check-input" type="checkbox" name="otp_consumed" id="otp_consumed" value="1" {{ $user->otp_consumed || old('otp_consumed', 0) === 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="otp_consumed">{{ trans('cruds.user.fields.otp_consumed') }}</label>
                </div>
                @if($errors->has('otp_consumed'))
                    <div class="invalid-feedback">
                        {{ $errors->first('otp_consumed') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.otp_consumed_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.otp_channel') }}</label>
                <select class="form-control {{ $errors->has('otp_channel') ? 'is-invalid' : '' }}" name="otp_channel" id="otp_channel">
                    <option value disabled {{ old('otp_channel', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\User::OTP_CHANNEL_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('otp_channel', $user->otp_channel) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('otp_channel'))
                    <div class="invalid-feedback">
                        {{ $errors->first('otp_channel') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.otp_channel_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.user.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type">
                    <option value disabled {{ old('type', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\User::TYPE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('type', $user->type) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">
                        {{ $errors->first('type') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.user.fields.type_helper') }}</span>
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