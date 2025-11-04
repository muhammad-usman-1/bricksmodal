@extends('layouts.admin')

@section('content')
    <div class="casting-wrap">
        <h2 class="casting-title">Edit Casting Request</h2>

        <div class="casting-card">
            <form method="POST" action="{{ route('admin.casting-requirements.update', [$castingRequirement->id]) }}" enctype="multipart/form-data">
                @method('PUT')
                @csrf

                <!-- TWO-COLUMN GRID -->
                <div class="grid-2">

                    <!-- LEFT COLUMN -->
                    <div class="col">
                        {{-- Project Name --}}
                        <div class="fgroup">
                            <label class="required"
                                for="project_name">{{ trans('cruds.castingRequirement.fields.project_name') }}</label>
                            <input class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}"
                                type="text" name="project_name" id="project_name" value="{{ old('project_name', $castingRequirement->project_name) }}"
                                required placeholder="Enter project name">
                            @if ($errors->has('project_name'))
                                <div class="invalid-feedback">{{ $errors->first('project_name') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.castingRequirement.fields.project_name_helper') }}</span>
                        </div>

                        {{-- Location --}}
                        <div class="fgroup">
                            <label for="location">{{ trans('cruds.castingRequirement.fields.location') }}</label>
                            <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text"
                                name="location" id="location" value="{{ old('location', $castingRequirement->location) }}"
                                placeholder="Enter location">
                            @if ($errors->has('location'))
                                <div class="invalid-feedback">{{ $errors->first('location') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.location_helper') }}</span>
                        </div>

                        {{-- Gender --}}
                        <div class="fgroup">
                            <label>{{ trans('cruds.castingRequirement.fields.gender') }}</label>
                            <select class="form-control {{ $errors->has('gender') ? 'is-invalid' : '' }}" name="gender"
                                id="gender">
                                <option value disabled {{ old('gender', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                                @foreach (App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('gender', $castingRequirement->gender) === (string) $key ? 'selected' : '' }}>{{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @if ($errors->has('gender'))
                                <div class="invalid-feedback">{{ $errors->first('gender') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.gender_helper') }}</span>
                        </div>

                        {{-- Hair Color --}}
                        <div class="fgroup">
                            <label for="hair_color">{{ trans('cruds.castingRequirement.fields.hair_color') }}</label>
                            <input class="form-control {{ $errors->has('hair_color') ? 'is-invalid' : '' }}" type="text"
                                name="hair_color" id="hair_color" value="{{ old('hair_color', $castingRequirement->hair_color) }}"
                                placeholder="Enter preferred color">
                            @if ($errors->has('hair_color'))
                                <div class="invalid-feedback">{{ $errors->first('hair_color') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.castingRequirement.fields.hair_color_helper') }}</span>
                        </div>

                        {{-- Outfit (custom Vue) --}}
                        <div class="fgroup">
                            <label class="required"
                                for="outfit">{{ trans('cruds.castingRequirement.fields.outfit') }}</label>
                            <div id="outfit-selector">
                                <outfit-selector :options='@json(\App\Support\OutfitOptions::all())'
                                    v-model="selectedOutfits"></outfit-selector>
                            </div>
                            @if ($errors->has('outfit'))
                                <div class="invalid-feedback">{{ $errors->first('outfit') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.outfit_helper') }}</span>
                        </div>

                        {{-- Usage / Notes --}}
                        <div class="fgroup">
                            <label for="notes">{{ trans('cruds.castingRequirement.fields.notes') }}</label>
                            <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes"
                                rows="4" placeholder="Add any additional notes">{{ old('notes', $castingRequirement->notes) }}</textarea>
                            @if ($errors->has('notes'))
                                <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.notes_helper') }}</span>
                        </div>
                    </div>

                    <!-- RIGHT COLUMN -->
                    <div class="col">
                        {{-- Date & Time --}}
                        <div class="fgroup">
                            <label
                                for="shoot_date_time">{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</label>
                            <div class="input-with-icon">
                                <input class="form-control {{ $errors->has('shoot_date_time') ? 'is-invalid' : '' }}"
                                    type="text" name="shoot_date_time" id="shoot_date_time"
                                    value="{{ old('shoot_date_time', $castingRequirement->shoot_date_time) }}" placeholder="Select date and time">
                                <i class="far fa-calendar-alt"></i>
                            </div>
                            @if ($errors->has('shoot_date_time'))
                                <div class="invalid-feedback">{{ $errors->first('shoot_date_time') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.castingRequirement.fields.shoot_date_time_helper') }}</span>
                        </div>

                        {{-- Age Range --}}
                        <div class="fgroup">
                            <label for="age_range">{{ trans('cruds.castingRequirement.fields.age_range') }}</label>
                            <input class="form-control {{ $errors->has('age_range') ? 'is-invalid' : '' }}" type="text"
                                name="age_range" id="age_range" value="{{ old('age_range', $castingRequirement->age_range) }}"
                                placeholder="e.g., 18–30">
                            @if ($errors->has('age_range'))
                                <div class="invalid-feedback">{{ $errors->first('age_range') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.age_range_helper') }}</span>
                        </div>

                        {{-- Number of Models (count) --}}
                        <div class="fgroup">
                            <label class="required"
                                for="count">{{ trans('cruds.castingRequirement.fields.count') }}</label>
                            <input class="form-control {{ $errors->has('count') ? 'is-invalid' : '' }}" type="number"
                                name="count" id="count" value="{{ old('count', $castingRequirement->count) }}" step="1" required
                                placeholder="Enter number of models">
                            @if ($errors->has('count'))
                                <div class="invalid-feedback">{{ $errors->first('count') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.count_helper') }}</span>
                        </div>

                        {{-- Pay Range (rate_per_model) --}}
                        <div class="fgroup">
                            <label class="required"
                                for="rate_per_model">{{ trans('cruds.castingRequirement.fields.rate_per_model') }}</label>
                            <input class="form-control {{ $errors->has('rate_per_model') ? 'is-invalid' : '' }}"
                                type="number" name="rate_per_model" id="rate_per_model"
                                value="{{ old('rate_per_model', $castingRequirement->rate_per_model) }}" step="0.01" required
                                placeholder="Enter rate per model">
                            @if ($errors->has('rate_per_model'))
                                <div class="invalid-feedback">{{ $errors->first('rate_per_model') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.castingRequirement.fields.rate_per_model_helper') }}</span>
                        </div>

                        {{-- Status --}}
                        <div class="fgroup">
                            <label class="required"
                                for="status">{{ trans('cruds.castingRequirement.fields.status') }}</label>
                            <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status"
                                id="status" required>
                                <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>
                                    {{ trans('global.pleaseSelect') }}</option>
                                @foreach (App\Models\CastingRequirement::STATUS_SELECT as $key => $label)
                                    <option value="{{ $key }}"
                                        {{ old('status', $castingRequirement->status) === (string) $key ? 'selected' : '' }}>
                                        {{ $label }}</option>
                                @endforeach
                            </select>
                            @if ($errors->has('status'))
                                <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                            @endif
                            <span class="help-block">{{ trans('cruds.castingRequirement.fields.status_helper') }}</span>
                        </div>

                        {{-- Upload Reference (Dropzone) --}}
                        <div class="fgroup">
                            <label for="reference">{{ trans('cruds.castingRequirement.fields.reference') }}</label>
                            <div class="upload-box needsclick dropzone {{ $errors->has('reference') ? 'is-invalid' : '' }}"
                                id="reference-dropzone">
                                <div class="dz-message">
                                    <strong>Upload Reference</strong>
                                    <p>Drag and drop images here or click to browse</p>
                                    <button type="button" class="btn btn-light btn-sm">Upload</button>
                                </div>
                            </div>
                            @if ($errors->has('reference'))
                                <div class="invalid-feedback">{{ $errors->first('reference') }}</div>
                            @endif
                            <span
                                class="help-block">{{ trans('cruds.castingRequirement.fields.reference_helper') }}</span>
                        </div>
                    </div>
                </div>

                <!-- SUBMIT -->
                <div class="submit-row">
                    <a href="{{ route('admin.casting-requirements.index') }}" class="btn btn-secondary mr-2">Cancel</a>
                    <button class="btn btn-primary" type="submit">{{ trans('global.save') }}</button>
                </div>
            </form>

        </div>
    </div>
@endsection

@section('styles')
    <link href="{{ asset('css/outfit-selector.css') }}" rel="stylesheet">
    <link href="{{ asset('css/form-improvements.css') }}" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>


    <style>
        /* Page wrap */
        .casting-wrap {
            max-width: none;
            /* remove fixed width */
            width: 100%;
            margin: 0;
            /* no centering */

        }

        .casting-title {
            margin: 0 0 16px 0;
            padding: 0 4px;
        }

        .casting-card {
            width: 100%;
            margin: 0;
            /* no outer margin */
            border-radius: 12px;
            box-shadow: none;
            /* subtler look */
            border: 1px solid #eee;
            padding: 20px;
            background-color: white
        }

        /* Grid */
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px 28px;
        }

        .grid-2 .col {
            display: flex;
            flex-direction: column;
            gap: 18px;
        }

        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
        }

        /* Field group */
        .fgroup label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
        }

        .fgroup .help-block {
            display: block;
            font-size: 11px;

            margin-top: 6px;
        }
        .form-control:disabled, .form-control[readonly] {
    background-color: #efeff5;
    opacity: 1;
}

        /* Inputs */
        .form-control {
            height: 40px;
            border: 1px solid #e4e6eb;
            border-radius: 8px;
            box-shadow: none !important;
            font-size: 14px;
        }

        textarea.form-control {
            height: auto;
            min-height: 110px;
            resize: vertical;
        }

        /* Date icon */
        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            pointer-events: none;
            color: #9aa0a6;
        }

        /* Upload box (Dropzone) */
        .upload-box {
            border: 2px dashed #e0e0e0 !important;
            border-radius: 12px;
            background: #fafafa;
            padding: 22px;
            text-align: center;
            transition: .2s ease;
            min-height: 135px;
        }

        .upload-box:hover {
            background: #f5f5f5;
        }

        .upload-box .dz-message {
            margin: 0;
        }

        .upload-box strong {
            display: block;
            margin-bottom: 6px;
        }

        .upload-box p {
            margin: 0 0 10px;
            color: #8a8f98;
            font-size: 13px;
        }

        /* Submit row */
        .submit-row {
            display: flex;
            justify-content: flex-end;
            margin-top: 10px;
        }

        .btn-primary {
            background: #111;
            border-color: #111;
            border-radius: 10px;
            padding: 10px 20px;
        }

        .btn-primary:hover {
            background: #000;
            border-color: #000;
        }

        .btn-secondary {
            border-radius: 10px;
            padding: 10px 20px;
        }
    </style>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
    <script src="{{ asset('js/outfit-selector.js') }}"></script>



    <script>
        var uploadedReferenceMap = {}

        Dropzone.options.referenceDropzone = {
            url: '{{ route('admin.casting-requirements.storeMedia') }}',
            maxFilesize: 10,
            addRemoveLinks: true,
            headers: { 'X-CSRF-TOKEN': "{{ csrf_token() }}" },
            params: { size: 10 },
            success: function(file, response) {
                $('form').append('<input type="hidden" name="reference[]" value="' + response.name + '">')
                uploadedReferenceMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = (typeof file.file_name !== 'undefined') ? file.file_name : uploadedReferenceMap[file.name]
                $('form').find('input[name="reference[]"][value="' + name + '"]').remove()
            },
            init: function () {
                @if(isset($castingRequirement) && $castingRequirement->reference)
                    var files = {!! json_encode($castingRequirement->reference) !!}
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
                    var message = response
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

        new Vue({
            el: '#outfit-selector',
            data: {
                selectedOutfits: {!! old('outfit', json_encode($castingRequirement->outfit ?? [])) !!}
            }
        });

        $(document).ready(function() {
            $('.datetime').parent().addClass('col-12');
            $('.datetimepicker').css('width', '100%');
        });

        // ✅ Initialize Flatpickr for shoot date & time
        document.addEventListener("DOMContentLoaded", function() {
            flatpickr("#shoot_date_time", {
                enableTime: true,
                dateFormat: "Y-m-d H:i",
                time_24hr: false,
                minDate: "today",
                altInput: true,
                altFormat: "F j, Y (h:i K)"
            });
        });
    </script>
@endsection
