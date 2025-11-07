@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.castingApplication.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.casting-applications.update", [$castingApplication->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="casting_requirement_id">{{ trans('cruds.castingApplication.fields.casting_requirement') }}</label>
                <select class="form-control select2 {{ $errors->has('casting_requirement') ? 'is-invalid' : '' }}" name="casting_requirement_id" id="casting_requirement_id" required>
                    @foreach($casting_requirements as $id => $entry)
                        <option value="{{ $id }}" {{ (old('casting_requirement_id') ? old('casting_requirement_id') : $castingApplication->casting_requirement->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('casting_requirement'))
                    <div class="invalid-feedback">
                        {{ $errors->first('casting_requirement') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.casting_requirement_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="talent_profile_id">{{ trans('cruds.castingApplication.fields.talent_profile') }}</label>
                <select class="form-control select2 {{ $errors->has('talent_profile') ? 'is-invalid' : '' }}" name="talent_profile_id" id="talent_profile_id" required>
                    @foreach($talent_profiles as $id => $entry)
                        <option value="{{ $id }}" {{ (old('talent_profile_id') ? old('talent_profile_id') : $castingApplication->talent_profile->id ?? '') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('talent_profile'))
                    <div class="invalid-feedback">
                        {{ $errors->first('talent_profile') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.talent_profile_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="rate">{{ trans('cruds.castingApplication.fields.rate') }}</label>
                <input class="form-control {{ $errors->has('rate') ? 'is-invalid' : '' }}" type="number" name="rate" id="rate" value="{{ old('rate', $castingApplication->rate) }}" step="0.01" required>
                @if($errors->has('rate'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rate') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.rate_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rate_offered">{{ trans('cruds.castingApplication.fields.rate_offered') }}</label>
                <input class="form-control {{ $errors->has('rate_offered') ? 'is-invalid' : '' }}" type="number" name="rate_offered" id="rate_offered" value="{{ old('rate_offered', $castingApplication->rate_offered) }}" step="0.01">
                @if($errors->has('rate_offered'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rate_offered') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.rate_offered_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="talent_notes">{{ trans('cruds.castingApplication.fields.talent_notes') }}</label>
                <textarea class="form-control {{ $errors->has('talent_notes') ? 'is-invalid' : '' }}" name="talent_notes" id="talent_notes">{{ old('talent_notes', $castingApplication->talent_notes) }}</textarea>
                @if($errors->has('talent_notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('talent_notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.talent_notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="admin_notes">{{ trans('cruds.castingApplication.fields.admin_notes') }}</label>
                <textarea class="form-control {{ $errors->has('admin_notes') ? 'is-invalid' : '' }}" name="admin_notes" id="admin_notes">{{ old('admin_notes', $castingApplication->admin_notes) }}</textarea>
                @if($errors->has('admin_notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('admin_notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.admin_notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.castingApplication.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CastingApplication::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $castingApplication->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.status_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="rating">{{ trans('cruds.castingApplication.fields.rating') }}</label>
                <input class="form-control {{ $errors->has('rating') ? 'is-invalid' : '' }}" type="number" name="rating" id="rating" value="{{ old('rating', $castingApplication->rating) }}" step="1">
                @if($errors->has('rating'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rating') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.rating_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reviews">{{ trans('cruds.castingApplication.fields.reviews') }}</label>
                <textarea class="form-control {{ $errors->has('reviews') ? 'is-invalid' : '' }}" name="reviews" id="reviews">{{ old('reviews', $castingApplication->reviews) }}</textarea>
                @if($errors->has('reviews'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reviews') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingApplication.fields.reviews_helper') }}</span>
            </div>
            <div class="form-group">
                <label>Payment Status</label>
                <select class="form-control {{ $errors->has('payment_status') ? 'is-invalid' : '' }}" name="payment_status" id="payment_status">
                    <option value disabled {{ old('payment_status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CastingApplication::PAYMENT_STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('payment_status', $castingApplication->payment_status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('payment_status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('payment_status') }}
                    </div>
                @endif
                <span class="help-block">Current payment workflow status</span>
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
