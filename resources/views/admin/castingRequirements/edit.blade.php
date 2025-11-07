@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.castingRequirement.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.casting-requirements.update", [$castingRequirement->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="project_name">{{ trans('cruds.castingRequirement.fields.project_name') }}</label>
                <input class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}" type="text" name="project_name" id="project_name" value="{{ old('project_name', $castingRequirement->project_name) }}" required>
                @if($errors->has('project_name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('project_name') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.project_name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.castingRequirement.fields.location') }}</label>
                <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $castingRequirement->location) }}">
                @if($errors->has('location'))
                    <div class="invalid-feedback">
                        {{ $errors->first('location') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.location_helper') }}</span>
            </div>
            <div class="form-group" style="width: 100%;">
                <label for="shoot_date_time">{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</label>
                <input class="form-control datetime {{ $errors->has('shoot_date_time') ? 'is-invalid' : '' }}" type="text" name="shoot_date_time" id="shoot_date_time" value="{{ old('shoot_date_time', $castingRequirement->shoot_date_time) }}" style="width: 100%;">
                @if($errors->has('shoot_date_time'))
                    <div class="invalid-feedback">
                        {{ $errors->first('shoot_date_time') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.shoot_date_time_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="hair_color">{{ trans('cruds.castingRequirement.fields.hair_color') }}</label>
                <input class="form-control {{ $errors->has('hair_color') ? 'is-invalid' : '' }}" type="text" name="hair_color" id="hair_color" value="{{ old('hair_color', $castingRequirement->hair_color) }}">
                @if($errors->has('hair_color'))
                    <div class="invalid-feedback">
                        {{ $errors->first('hair_color') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.hair_color_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="age_range">{{ trans('cruds.castingRequirement.fields.age_range') }}</label>
                <input class="form-control {{ $errors->has('age_range') ? 'is-invalid' : '' }}" type="text" name="age_range" id="age_range" value="{{ old('age_range', $castingRequirement->age_range) }}">
                @if($errors->has('age_range'))
                    <div class="invalid-feedback">
                        {{ $errors->first('age_range') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.age_range_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.castingRequirement.fields.gender') }}</label>
                <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender" id="gender">
                    <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('gender', $castingRequirement->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('gender'))
                    <div class="invalid-feedback">
                        {{ $errors->first('gender') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.gender_helper') }}</span>
            </div>
            <div class="form-group">
                <label>{{ trans('cruds.castingRequirement.fields.outfit') }}</label>
                <p class="text-muted small">Select one or multiple outfits</p>

                @php
                    $selectedOutfits = old('outfit', $castingRequirement->outfit ?? []);
                @endphp

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
                                               {{ in_array($outfit->id, $selectedOutfits) ? 'checked' : '' }}>
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
                    <div class="text-danger">
                        {{ $errors->first('outfit') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.outfit_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="reference">{{ trans('cruds.castingRequirement.fields.reference') }}</label>
                <div class="needsclick dropzone {{ $errors->has('reference') ? 'is-invalid' : '' }}" id="reference-dropzone">
                </div>
                @if($errors->has('reference'))
                    <div class="invalid-feedback">
                        {{ $errors->first('reference') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.reference_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="count">{{ trans('cruds.castingRequirement.fields.count') }}</label>
                <input class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}" type="number" name="count" id="count" value="{{ old('count', $castingRequirement->count) }}" step="1" required>
                @if($errors->has('count'))
                    <div class="invalid-feedback">
                        {{ $errors->first('count') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.count_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.castingRequirement.fields.notes') }}</label>
                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes">{{ old('notes', $castingRequirement->notes) }}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">
                        {{ $errors->first('notes') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.notes_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="rate_per_model">{{ trans('cruds.castingRequirement.fields.rate_per_model') }}</label>
                <input class="form-control {{ $errors->has('rate_per_model') ? 'is-invalid' : '' }}" type="number" name="rate_per_model" id="rate_per_model" value="{{ old('rate_per_model', $castingRequirement->rate_per_model) }}" step="0.01" required>
                @if($errors->has('rate_per_model'))
                    <div class="invalid-feedback">
                        {{ $errors->first('rate_per_model') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.rate_per_model_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required">{{ trans('cruds.castingRequirement.fields.status') }}</label>
                <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                    <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\CastingRequirement::STATUS_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('status', $castingRequirement->status) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('status'))
                    <div class="invalid-feedback">
                        {{ $errors->first('status') }}
                    </div>
                @endif
                <span class="help-block">{{ trans('cruds.castingRequirement.fields.status_helper') }}</span>
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

@section('scripts')
<style>
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
</script>
@endsection
