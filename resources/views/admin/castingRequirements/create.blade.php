@extends('layouts.admin')
@section('content')

<div class="shoot-builder">
    <div class="shoot-builder__head">
        <div>
            <h2>Add New Shoot</h2>
            <p>Create a shoot in guided steps. Status defaults to Advertised.</p>
        </div>
        <a href="{{ route('admin.projects.dashboard') }}" class="btn btn-outline-secondary btn-sm">Back to Shoots</a>
    </div>

    @php
        $shootDateValue = old('shoot_date');
        $shootTimeValue = old('shoot_time');
        $durationValue = old('duration');
        $oldShootDateTimeValue = old('shoot_date_time');

        if ((! $shootDateValue || ! $shootTimeValue) && $oldShootDateTimeValue) {
            try {
                $parsedDate = \Carbon\Carbon::createFromFormat(config('panel.date_format') . ' ' . config('panel.time_format'), $oldShootDateTimeValue);
                $shootDateValue = $shootDateValue ?: $parsedDate->format('Y-m-d');
                $shootTimeValue = $shootTimeValue ?: $parsedDate->format('H:i');
            } catch (\Exception $exception) {
                report($exception);
            }
        }
    @endphp
    <form method="POST" action="{{ route('admin.casting-requirements.store') }}" enctype="multipart/form-data" id="shootWizard">
        @csrf

        <div class="shoot-steps" data-current-step="1">
            <div class="shoot-step" data-step="1">
                <h4>Basic Details</h4>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="required" for="project_name">Shoot Name</label>
                        <input class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}" type="text" name="project_name" id="project_name" value="{{ old('project_name', '') }}" required>
                        @if($errors->has('project_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('project_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="location">{{ trans('cruds.castingRequirement.fields.location') }}</label>
                        <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', '') }}" autocomplete="off" placeholder="Search location">
                        @if($errors->has('location'))
                            <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="shoot_date">{{ trans('cruds.castingRequirement.fields.shoot_date') }}</label>
                        <input class="form-control {{ $errors->has('shoot_date') ? 'is-invalid' : '' }}" type="date" name="shoot_date" id="shoot_date" value="{{ $shootDateValue }}">
                        @if($errors->has('shoot_date'))
                            <div class="invalid-feedback">{{ $errors->first('shoot_date') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="shoot_time">{{ trans('cruds.castingRequirement.fields.shoot_time') }}</label>
                        <input class="form-control {{ $errors->has('shoot_time') ? 'is-invalid' : '' }}" type="time" name="shoot_time" id="shoot_time" value="{{ $shootTimeValue }}">
                        @if($errors->has('shoot_time'))
                            <div class="invalid-feedback">{{ $errors->first('shoot_time') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="duration">{{ trans('cruds.castingRequirement.fields.duration') }}</label>
                        <input class="form-control {{ $errors->has('duration') ? 'is-invalid' : '' }}" type="text" name="duration" id="duration" value="{{ $durationValue }}" placeholder="e.g. 3 hours">
                        @if($errors->has('duration'))
                            <div class="invalid-feedback">{{ $errors->first('duration') }}</div>
                        @endif
                    </div>
                    @if($errors->has('shoot_date_time'))
                        <div class="text-danger small">{{ $errors->first('shoot_date_time') }}</div>
                    @endif
                    <div class="form-group">
                        <label for="hair_color">{{ trans('cruds.castingRequirement.fields.hair_color') }}</label>
                        <input class="form-control {{ $errors->has('hair_color') ? 'is-invalid' : '' }}" type="text" name="hair_color" id="hair_color" value="{{ old('hair_color', '') }}">
                        @if($errors->has('hair_color'))
                            <div class="invalid-feedback">{{ $errors->first('hair_color') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="age_range">{{ trans('cruds.castingRequirement.fields.age_range') }}</label>
                        <input class="form-control {{ $errors->has('age_range') ? 'is-invalid' : '' }}" type="text" name="age_range" id="age_range" value="{{ old('age_range', '') }}">
                        @if($errors->has('age_range'))
                            <div class="invalid-feedback">{{ $errors->first('age_range') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label>{{ trans('cruds.castingRequirement.fields.gender') }}</label>
                        <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                            <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('gender', 'any') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('gender'))
                            <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="shoot-step" data-step="2">
                <h4>Requirements & References</h4>
                <div class="form-group">
                    <label>{{ trans('cruds.castingRequirement.fields.outfit') }}</label>
                    <p class="text-muted small">Select one or multiple outfits</p>

                    @foreach($outfits as $category => $categoryOutfits)
                        <div class="outfit-category mb-4">
                            <h6 class="text-capitalize font-weight-bold mb-3">{{ ucfirst($category) }} Outfits</h6>
                            <div class="row">
                                @foreach($categoryOutfits as $outfit)
                                    <div class="col-md-2 col-sm-4 col-6 mb-3">
                                        <div class="outfit-item">
                                            <input type="checkbox"
                                                   name="outfit[]"
                                                   value="{{ $outfit->id }}"
                                                   id="outfit_{{ $outfit->id }}"
                                                   class="outfit-checkbox"
                                                   {{ in_array($outfit->id, old('outfit', [])) ? 'checked' : '' }}>
                                            <label for="outfit_{{ $outfit->id }}" class="outfit-label">
                                                @if($outfit->image)
                                                    <img src="{{ asset($outfit->image) }}" alt="{{ $outfit->name }}" class="outfit-image">
                                                @else
                                                    <div class="outfit-placeholder">
                                                        <i class="fas fa-tshirt fa-3x"></i>
                                                    </div>
                                                @endif
                                                <div class="outfit-name">{{ $outfit->name }}</div>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach

                    @if($errors->has('outfit'))
                        <div class="text-danger">{{ $errors->first('outfit') }}</div>
                    @endif
                </div>

                <div class="form-group">
                    <label for="reference">{{ trans('cruds.castingRequirement.fields.reference') }}</label>
                    <div class="needsclick dropzone {{ $errors->has('reference') ? 'is-invalid' : '' }}" id="reference-dropzone"></div>
                    @if($errors->has('reference'))
                        <div class="invalid-feedback">{{ $errors->first('reference') }}</div>
                    @endif
                </div>
            </div>

            <div class="shoot-step" data-step="3">
                <h4>Talent & Budget</h4>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="required" for="count">{{ trans('cruds.castingRequirement.fields.count') }}</label>
                        <input class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}" type="number" name="count" id="count" value="{{ old('count', '1') }}" min="1" required>
                        @if($errors->has('count'))
                            <div class="invalid-feedback">{{ $errors->first('count') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="rate_per_model">{{ trans('cruds.castingRequirement.fields.rate_per_model') }}</label>
                        <input class="form-control {{ $errors->has('rate_per_model') ? 'is-invalid' : '' }}" type="number" name="rate_per_model" id="rate_per_model" value="{{ old('rate_per_model', '') }}" step="0.01" required>
                        @if($errors->has('rate_per_model'))
                            <div class="invalid-feedback">{{ $errors->first('rate_per_model') }}</div>
                        @endif
                    </div>
                </div>
                <div class="form-group">
                    <label for="notes">{{ trans('cruds.castingRequirement.fields.notes') }}</label>
                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes" rows="4">{{ old('notes') }}</textarea>
                    @if($errors->has('notes'))
                        <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="shoot-builder__footer">
            <div class="step-status">
                Step <span data-step-indicator>1</span> of 3
            </div>
            <div>
                <button type="button" class="btn btn-outline-secondary" data-prev-step disabled>Back</button>
                <button type="button" class="btn btn-primary" data-next-step>Next</button>
                <button type="submit" class="btn btn-success d-none" data-submit-form>Save Shoot</button>
            </div>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<style>
    .shoot-builder {
        background: #fff;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(15, 23, 42, 0.08);
    }
    .shoot-builder__head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }
    .shoot-steps {
        position: relative;
    }
    .shoot-step {
        display: none;
        animation: fadeIn .25s ease;
    }
    .shoot-step.active {
        display: block;
    }
    .grid {
        display: grid;
        grid-gap: 16px;
    }
    .grid-2 {
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    }
    .shoot-builder__footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1px solid #e2e8f0;
        padding-top: 16px;
        margin-top: 24px;
    }
    .step-status {
        font-weight: 600;
        color: #64748b;
    }
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(6px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .outfit-item {
        position: relative;
        border: 2px solid #e0e0e0;
        border-radius: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
        overflow: hidden;
    }

    .outfit-item:hover {
        border-color: #007bff;
        box-shadow: 0 4px 8px rgba(0,123,255,0.2);
    }

    .outfit-checkbox {
        position: absolute;
        top: 10px;
        right: 10px;
        width: 20px;
        height: 20px;
        z-index: 10;
        cursor: pointer;
    }

    .outfit-label {
        display: block;
        cursor: pointer;
        margin: 0;
        padding: 0;
    }

    .outfit-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        display: block;
    }

    .outfit-placeholder {
        width: 100%;
        height: 150px;
        background: #f8f9fa;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
    }

    .outfit-name {
        padding: 10px;
        text-align: center;
        font-size: 13px;
        font-weight: 500;
        background: #fff;
        border-top: 1px solid #e0e0e0;
    }

    .outfit-checkbox:checked + .outfit-label {
        background: #e7f3ff;
    }

    .outfit-checkbox:checked ~ .outfit-label .outfit-name {
        background: #007bff;
        color: white;
    }

    .outfit-category h6 {
        color: #495057;
        padding-bottom: 10px;
        border-bottom: 2px solid #dee2e6;
    }

    /* Full width datetime picker */
    .form-group .datetime {
        width: 100% !important;
    }
</style>
<script>
    var uploadedReferenceMap = {}
Dropzone.options.referenceDropzone = {
    url: '{{ route('admin.casting-requirements.storeMedia') }}',
    maxFilesize: 10, // MB
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 10
    },
    success: function (file, response) {
      $('form').append('<input type="hidden" name="reference[]" value="' + response.name + '">')
      uploadedReferenceMap[file.name] = response.name
    },
    removedfile: function (file) {
      file.previewElement.remove()
      var name = ''
      if (typeof file.file_name !== 'undefined') {
        name = file.file_name
      } else {
        name = uploadedReferenceMap[file.name]
      }
      $('form').find('input[name="reference[]"][value="' + name + '"]').remove()
    },
    init: function () {
@if(isset($castingRequirement) && $castingRequirement->reference)
          var files =
            {!! json_encode($castingRequirement->reference) !!}
              for (var i in files) {
              var file = files[i]
              this.options.addedfile.call(this, file)
              file.previewElement.classList.add('dz-complete')
              $('form').append('<input type="hidden" name="reference[]" value="' + file.file_name + '">')
            }
@endif
    },
     error: function (file, response) {
         if ($.type(response) === 'string') {
             var message = response //dropzone sends it's own error messages in string
         } else {
             var message = response.errors.file
         }
         file.previewElement.classList.add('dz-error')
         _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
         _results = []
         for (_i = 0, _len = _ref.length; _i < _len; _i++) {
             node = _ref[_i]
             _results.push(node.textContent = message)
         }

         return _results
     }
}

document.addEventListener('DOMContentLoaded', function () {
    const steps = Array.from(document.querySelectorAll('.shoot-step'));
    const nextBtn = document.querySelector('[data-next-step]');
    const prevBtn = document.querySelector('[data-prev-step]');
    const submitBtn = document.querySelector('[data-submit-form]');
    const indicator = document.querySelector('[data-step-indicator]');
    const form = document.getElementById('shootWizard');

    if (!steps.length || !nextBtn || !prevBtn || !indicator) {
        return;
    }

    let currentStep = 0;

    const showStep = (index) => {
        steps.forEach((step, idx) => {
            step.classList.toggle('active', idx === index);
        });
        indicator.textContent = index + 1;
        prevBtn.disabled = index === 0;
        nextBtn.classList.toggle('d-none', index === steps.length - 1);
        submitBtn.classList.toggle('d-none', index !== steps.length - 1);
    };

    const isStepValid = (index) => {
        const stepFields = steps[index].querySelectorAll('input, select, textarea');
        let valid = true;
        stepFields.forEach(field => {
            if (field.required && !field.value) {
                field.classList.add('is-invalid');
                valid = false;
            } else {
                field.classList.remove('is-invalid');
            }
        });
        return valid;
    };

    nextBtn.addEventListener('click', function () {
        if (!isStepValid(currentStep)) {
            return;
        }
        currentStep = Math.min(currentStep + 1, steps.length - 1);
        showStep(currentStep);
    });

    prevBtn.addEventListener('click', function () {
        currentStep = Math.max(currentStep - 1, 0);
        showStep(currentStep);
    });

    form.addEventListener('submit', function () {
        form.insertAdjacentHTML('beforeend', '<input type="hidden" name="status" value="advertised">');
    }, { once: true });

    showStep(currentStep);
});

window.initShootLocationAutocomplete = function () {
    var input = document.getElementById('location');
    if (!input) {
        return;
    }

    if (!window.google || !google.maps || !google.maps.places) {
        console.warn('Google Places library not available. Ensure API script is loaded.');
        return;
    }

    var autocomplete = new google.maps.places.Autocomplete(input, {
        componentRestrictions: { country: ['kw'] },
        fields: ['formatted_address', 'name', 'geometry'],
        types: ['geocode']
    });

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (place && place.formatted_address) {
            input.value = place.formatted_address;
        } else if (place && place.name) {
            input.value = place.name;
        }
    });
};

if (window.google && google.maps && google.maps.places) {
    window.initShootLocationAutocomplete();
}
</script>
@php
    $googlePlacesKey = config('services.google.places_api_key');
@endphp
@if ($googlePlacesKey)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googlePlacesKey }}&libraries=places&callback=initShootLocationAutocomplete" async defer></script>
@else
    <script>
        console.warn('Google Places API key is not configured. Location autocomplete is disabled.');
    </script>
@endif
@endsection
