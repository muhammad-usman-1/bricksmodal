<form method="POST" action="{{ route('talent.onboarding.store', $currentStep) }}" enctype="multipart/form-data">
    @csrf
    <p class="text-muted">{{ $instructions }}</p>

    <div class="form-group">
        <label for="photo">{{ $label }}</label>
        <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo" accept="image/*" required>
        @error('photo')
            <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
        @enderror
    </div>

    <div class="d-flex justify-content-between">
        <a href="{{ route('talent.onboarding.show', $previousStep) }}" class="btn btn-link">{{ trans('global.back') }}</a>
        <button type="submit" class="btn btn-primary">{{ trans('global.next_step') }}</button>
    </div>
</form>
