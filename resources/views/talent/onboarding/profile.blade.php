@extends('talent.onboarding.layout', ['progress' => $progress ?? null])

@section('onboarding-content')
<form method="POST" action="{{ route('talent.onboarding.store', 'profile') }}">
    @csrf
    <div class="form-group">
        <label for="full_name">{{ trans('global.full_name') }}</label>
        <input type="text" class="form-control @error('full_name') is-invalid @enderror" id="full_name" name="full_name" value="{{ old('full_name', $profile->legal_name) }}" required>
        @error('full_name')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">{{ trans('global.login_email') }}</label>
        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth('talent')->user()->email) }}" required>
        @error('email')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="height">{{ trans('cruds.talentProfile.fields.height') }}</label>
            <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $profile->height) }}" placeholder="{{ trans('global.height_placeholder') }}">
            @error('height')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="weight">{{ trans('cruds.talentProfile.fields.weight') }}</label>
            <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $profile->weight) }}" placeholder="kg">
            @error('weight')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="daily_rate">{{ trans('cruds.talentProfile.fields.daily_rate') }}</label>
            <input type="number" step="0.01" class="form-control @error('daily_rate') is-invalid @enderror" id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $profile->daily_rate) }}" required>
            @error('daily_rate')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="hourly_rate">{{ trans('cruds.talentProfile.fields.hourly_rate') }}</label>
            <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}">
            @error('hourly_rate')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-4">
            <label for="chest">{{ trans('cruds.talentProfile.fields.chest') }}</label>
            <input type="number" class="form-control @error('chest') is-invalid @enderror" id="chest" name="chest" value="{{ old('chest', $profile->chest) }}">
            @error('chest')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="waist">{{ trans('cruds.talentProfile.fields.waist') }}</label>
            <input type="number" class="form-control @error('waist') is-invalid @enderror" id="waist" name="waist" value="{{ old('waist', $profile->waist) }}">
            @error('waist')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-4">
            <label for="hips">{{ trans('cruds.talentProfile.fields.hips') }}</label>
            <input type="number" class="form-control @error('hips') is-invalid @enderror" id="hips" name="hips" value="{{ old('hips', $profile->hips) }}">
            @error('hips')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="date_of_birth">{{ trans('global.date_of_birth') }}</label>
            <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}" required>
            @error('date_of_birth')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="gender">{{ trans('global.gender') }}</label>
            <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender" required>
                <option value="" disabled {{ old('gender', $profile->gender) ? '' : 'selected' }}>{{ trans('global.pleaseSelect') }}</option>
                @foreach(['male' => trans('global.gender_male'), 'female' => trans('global.gender_female'), 'non_binary' => trans('global.gender_non_binary')] as $value => $label)
                    <option value="{{ $value }}" {{ old('gender', $profile->gender) === $value ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('gender')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-group">
        <label for="whatsapp_number">{{ trans('global.whatsapp_number') }}</label>
        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $profile->whatsapp_number ? '+' . $profile->whatsapp_number : '') }}" placeholder="{{ trans('global.whatsapp_number_placeholder') }}" required>
        @error('whatsapp_number')
            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
        <small class="form-text text-muted">{{ trans('global.whatsapp_number_helper') }}</small>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="skin_tone">{{ trans('cruds.talentProfile.fields.skin_tone') }}</label>
            <select class="form-control @error('skin_tone') is-invalid @enderror" id="skin_tone" name="skin_tone">
                <option value="">{{ trans('global.pleaseSelect') }}</option>
                @foreach(App\Models\TalentProfile::SKIN_TONE_SELECT as $key => $label)
                    <option value="{{ $key }}" {{ old('skin_tone', $profile->skin_tone) === $key ? 'selected' : '' }}>{{ $label }}</option>
                @endforeach
            </select>
            @error('skin_tone')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="hair_color">{{ trans('cruds.talentProfile.fields.hair_color') }}</label>
            <input type="text" class="form-control @error('hair_color') is-invalid @enderror" id="hair_color" name="hair_color" value="{{ old('hair_color', $profile->hair_color) }}">
            @error('hair_color')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="eye_color">{{ trans('cruds.talentProfile.fields.eye_color') }}</label>
            <input type="text" class="form-control @error('eye_color') is-invalid @enderror" id="eye_color" name="eye_color" value="{{ old('eye_color', $profile->eye_color) }}">
            @error('eye_color')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
        <div class="form-group col-md-6">
            <label for="shoe_size">{{ trans('cruds.talentProfile.fields.shoe_size') }}</label>
            <input type="number" class="form-control @error('shoe_size') is-invalid @enderror" id="shoe_size" name="shoe_size" value="{{ old('shoe_size', $profile->shoe_size) }}">
            @error('shoe_size')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>
    </div>

    <div class="d-flex justify-content-end">
        <button type="submit" class="btn btn-primary">{{ trans('global.next_step') }}</button>
    </div>
</form>
@endsection
