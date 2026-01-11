@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.talentProfile.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.talent-profiles.update", [$talentProfile->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="legal_name">{{ trans('cruds.talentProfile.fields.legal_name') }}</label>
                <input class="form-control {{ $errors->has('legal_name') ? 'is-invalid' : '' }}" type="text" name="legal_name" id="legal_name" value="{{ old('legal_name', $talentProfile->legal_name) }}">
                @if($errors->has('legal_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('legal_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.legal_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="display_name">{{ trans('cruds.talentProfile.fields.display_name') }}</label>
                <input class="form-control {{ $errors->has('display_name') ? 'is-invalid' : '' }}" type="text" name="display_name" id="display_name" value="{{ old('display_name', $talentProfile->display_name) }}">
                @if($errors->has('display_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('display_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.display_name_helper') }}</span>
            </div>
            
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="whatsapp_number">{{ trans('cruds.talentProfile.fields.whatsapp_number') }}</label>
                        <input class="form-control {{ $errors->has('whatsapp_number') ? 'is-invalid' : '' }}" type="text" name="whatsapp_number" id="whatsapp_number" value="{{ old('whatsapp_number', $talentProfile->whatsapp_number) }}" required>
                        @if($errors->has('whatsapp_number'))
                            <div class="invalid-feedback">
                                {{ $errors->first('whatsapp_number') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="user_id">{{ trans('cruds.talentProfile.fields.user') }}</label>
                        <select class="form-control select2 {{ $errors->has('user') ? 'is-invalid' : '' }}" name="user_id" id="user_id">
                            @foreach($users as $id => $entry)
                                <option value="{{ $id }}" {{ (old('user_id') ? old('user_id') : $talentProfile->user->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('user'))
                            <div class="invalid-feedback">
                                {{ $errors->first('user') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.talentProfile.fields.user_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="required" for="languages">{{ trans('cruds.talentProfile.fields.language') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('languages') ? 'is-invalid' : '' }}" name="languages[]" id="languages" multiple required>
                    @foreach($languages as $id => $language)
                        <option value="{{ $id }}" {{ (in_array($id, old('languages', [])) || $talentProfile->languages->contains($id)) ? 'selected' : '' }}>{{ $language }}</option>
                    @endforeach
                </select>
                @if($errors->has('languages'))
                    <div class="invalid-feedback">
                        {{ $errors->first('languages') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.language_helper') }}</span>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label class="required" for="daily_rate">{{ trans('cruds.talentProfile.fields.daily_rate') }}</label>
                        <input class="form-control {{ $errors->has('daily_rate') ? 'is-invalid' : '' }}" type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', $talentProfile->daily_rate) }}" step="0.01" required>
                        @if($errors->has('daily_rate'))
                            <div class="invalid-feedback">
                                {{ $errors->first('daily_rate') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.talentProfile.fields.daily_rate_helper') }}</span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="hourly_rate">{{ trans('cruds.talentProfile.fields.hourly_rate') }}</label>
                        <input class="form-control {{ $errors->has('hourly_rate') ? 'is-invalid' : '' }}" type="number" name="hourly_rate" id="hourly_rate" value="{{ old('hourly_rate', $talentProfile->hourly_rate) }}" step="0.01">
                        @if($errors->has('hourly_rate'))
                            <div class="invalid-feedback">
                                {{ $errors->first('hourly_rate') }}
                            </div>
                        @endif
                        <span class="help-block">{{ trans('cruds.talentProfile.fields.hourly_rate_helper') }}</span>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="bio">{{ trans('cruds.talentProfile.fields.bio') }}</label>
                <textarea class="form-control {{ $errors->has('bio') ? 'is-invalid' : '' }}" name="bio" id="bio">{{ old('bio', $talentProfile->bio) }}</textarea>
                @if($errors->has('bio'))
                    <div class="invalid-feedback">
                        {{ $errors->first('bio') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.talentProfile.fields.bio_helper') }}</span>
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