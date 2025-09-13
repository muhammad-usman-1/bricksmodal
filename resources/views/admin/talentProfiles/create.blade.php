@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.talentProfile.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.talent-profiles.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="legal_name">{{ trans('cruds.talentProfile.fields.legal_name') }}</label>
                <input class="form-control {{ $errors->has('legal_name') ? 'is-invalid' : '' }}" type="text" name="legal_name" id="legal_name" value="{{ old('legal_name', '') }}" required>
                @if($errors->has('legal_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('legal_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.legal_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="display_name">{{ trans('cruds.talentProfile.fields.display_name') }}</label>
                <input class="form-control {{ $errors->has('display_name') ? 'is-invalid' : '' }}" type="text" name="display_name" id="display_name" value="{{ old('display_name', '') }}">
                @if($errors->has('display_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('display_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.display_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="languages">{{ trans('cruds.talentProfile.fields.language') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('languages') ? 'is-invalid' : '' }}" name="languages[]" id="languages" multiple required>
                    @foreach($languages as $id => $language)
                        <option value="{{ $id }}" {{ in_array($id, old('languages', [])) ? 'selected' : '' }}>{{ $language }}</option>
                    @endforeach
                </select>
                @if($errors->has('languages'))
                    <div class="invalid-feedback">
                        {{ $errors->first('languages') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.language_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.talentProfile.fields.verification_status') }}</label>
                <select class="form-control {{ $errors->has('verification_status') ? 'is-invalid' : '' }}" name="verification_status" id="verification_status">
                    <option value disabled {{ old('verification_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\TalentProfile::VERIFICATION_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('verification_status', 'pending') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('verification_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('verification_status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.verification_status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="verification_notes">{{ trans('cruds.talentProfile.fields.verification_notes') }}</label>
                <input class="form-control {{ $errors->has('verification_notes') ? 'is-invalid' : '' }}" type="text" name="verification_notes" id="verification_notes" value="{{ old('verification_notes', '') }}">
                @if($errors->has('verification_notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('verification_notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.verification_notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="bio">{{ trans('cruds.talentProfile.fields.bio') }}</label>
                <input class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" type="text" name="bio" id="bio" value="{{ old('bio', '') }}">
                @if($errors->has('bio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.bio_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="daily_rate">{{ trans('cruds.talentProfile.fields.daily_rate') }}</label>
                <input class="form-control {{ $errors->has('daily_rate') ? 'is-invalid' : '' }}" type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', '') }}" step="0.01" required>
                @if($errors->has('daily_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('daily_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.daily_rate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hourly_rate">{{ trans('cruds.talentProfile.fields.hourly_rate') }}</label>
                <input class="form-control {{ $errors->has('hourly_rate') ? 'is-invalid' : '' }}" type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', '') }}" step="0.01">
                @if($errors->has('hourly_rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hourly_rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.hourly_rate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="height">{{ trans('cruds.talentProfile.fields.height') }}</label>
                <input class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}" type="number" name="height" id="height" value="{{ old('height', '') }}" step="1">
                @if($errors->has('height'))
                    <div class="invalid-feedback">
                        {{ $errors->first('height') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.height_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="weight">{{ trans('cruds.talentProfile.fields.weight') }}</label>
                <input class="form-control {{ $errors->has('weight') ? 'is-invalid' : '' }}" type="number" name="weight" id="weight" value="{{ old('weight', '') }}" step="1">
                @if($errors->has('weight'))
                    <div class="invalid-feedback">
                        {{ $errors->first('weight') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.weight_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="chest">{{ trans('cruds.talentProfile.fields.chest') }}</label>
                <input class="form-control {{ $errors->has('chest') ? 'is-invalid' : '' }}" type="number" name="chest" id="chest" value="{{ old('chest', '') }}" step="1">
                @if($errors->has('chest'))
                    <div class="invalid-feedback">
                        {{ $errors->first('chest') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.chest_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="waist">{{ trans('cruds.talentProfile.fields.waist') }}</label>
                <input class="form-control {{ $errors->has('waist') ? 'is-invalid' : '' }}" type="number" name="waist" id="waist" value="{{ old('waist', '') }}" step="1">
                @if($errors->has('waist'))
                    <div class="invalid-feedback">
                        {{ $errors->first('waist') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.waist_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hips">{{ trans('cruds.talentProfile.fields.hips') }}</label>
                <input class="form-control {{ $errors->has('hips') ? 'is-invalid' : '' }}" type="number" name="hips" id="hips" value="{{ old('hips', '') }}" step="1">
                @if($errors->has('hips'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hips') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.hips_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.talentProfile.fields.skin_tone') }}</label>
                <select class="form-control {{ $errors->has('skin_tone') ? 'is-invalid' : '' }}" name="skin_tone" id="skin_tone">
                    <option value disabled {{ old('skin_tone', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\TalentProfile::SKIN_TONE_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('skin_tone', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('skin_tone'))
                    <div class="invalid-feedback">
                        {{ $errors->first('skin_tone') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.skin_tone_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hair_color">{{ trans('cruds.talentProfile.fields.hair_color') }}</label>
                <input class="form-control {{ $errors->has('hair_color') ? 'is-invalid' : '' }}" type="text" name="hair_color" id="hair_color" value="{{ old('hair_color', '') }}">
                @if($errors->has('hair_color'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hair_color') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.hair_color_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="eye_color">{{ trans('cruds.talentProfile.fields.eye_color') }}</label>
                <input class="form-control {{ $errors->has('eye_color') ? 'is-invalid' : '' }}" type="text" name="eye_color" id="eye_color" value="{{ old('eye_color', '') }}">
                @if($errors->has('eye_color'))
                    <div class="invalid-feedback">
                        {{ $errors->first('eye_color') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.eye_color_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="shoe_size">{{ trans('cruds.talentProfile.fields.shoe_size') }}</label>
                <input class="form-control {{ $errors->has('shoe_size') ? 'is-invalid' : '' }}" type="number" name="shoe_size" id="shoe_size" value="{{ old('shoe_size', '') }}" step="1">
                @if($errors->has('shoe_size'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shoe_size') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.shoe_size_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="user_id">{{ trans('cruds.talentProfile.fields.user') }}</label>
                <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id" required>
                    @foreach($users as $id => $entry)
                        <option value="{{ $id }}" {{ old('user_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('user'))
                    <div class="invalid-feedback">
                        {{ $errors->first('user') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.user_helper') }}</span>
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