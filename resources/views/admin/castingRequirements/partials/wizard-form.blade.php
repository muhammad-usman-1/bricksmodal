@php
    $castingRequirement = $castingRequirement ?? null;
    $isEdit = $isEdit ?? false;
    $formAction = $formAction ?? route('admin.casting-requirements.store');
    $formMethod = $isEdit ? 'PUT' : 'POST';
    $rawShootDateTime = $castingRequirement?->getRawOriginal('shoot_date_time');
    $shootDateValue = old('shoot_date');
    $shootTimeValue = old('shoot_time');
    $durationValue = old('duration', $castingRequirement->duration ?? null);
    $ageRanges = $ageRanges ?? \App\Models\CastingRequirementModel::AGE_RANGE_OPTIONS;
    $labels = $labels ?? \App\Models\Label::orderBy('name')->get();

    if ((! $shootDateValue || ! $shootTimeValue) && $rawShootDateTime) {
        try {
            $parsedDate = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rawShootDateTime);
            $shootDateValue = $shootDateValue ?: $parsedDate->format('Y-m-d');
            $shootTimeValue = $shootTimeValue ?: $parsedDate->format('H:i');
        } catch (\Exception $exception) {
            report($exception);
        }
    }

    $defaultModel = [
        'title' => '',
        'quantity' => 1,
        'gender' => 'any',
        'age_range_key' => array_key_first($ageRanges),
        'hair_color' => '',
        'labels' => [],
    ];

    $modelInputs = old('models');
    if (! is_array($modelInputs) || empty($modelInputs)) {
        if ($isEdit && $castingRequirement) {
            $modelInputs = $castingRequirement->modelRequirements->map(function ($model) use ($ageRanges) {
                return [
                    'title' => $model->title,
                    'quantity' => $model->quantity,
                    'gender' => $model->gender ?? 'any',
                    'age_range_key' => $model->age_range_key ?? array_key_first($ageRanges),
                    'hair_color' => $model->hair_color,
                    'labels' => $model->labels->pluck('id')->all(),
                ];
            })->toArray();
        }

        if (empty($modelInputs)) {
            $modelInputs = [$defaultModel];
        }
    }
@endphp

<div class="shoot-builder">
    <div class="shoot-builder__head">
        <div>
            <h2>{{ $isEdit ? __('Update Shoot') : __('Add New Shoot') }}</h2>
            <p>{{ $isEdit ? __('Modify the shoot details below.') : __('Create a shoot in guided steps. Status defaults to Advertised.') }}</p>
        </div>
        <a href="{{ route('admin.projects.dashboard') }}" class="btn btn-outline-secondary btn-sm">{{ __('Back to Shoots') }}</a>
    </div>

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" id="shootWizard" data-default-status="{{ $isEdit ? '' : 'advertised' }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="shoot-steps" data-current-step="1">
            <div class="shoot-step" data-step="1">
                <h4>Stage 1 · Shoot Basics</h4>
                <p class="text-muted mb-4">Set up the core details for this shoot.</p>
                <div class="grid grid-2">
                    <div class="form-group">
                        <label class="required" for="project_name">Shoot Name</label>
                        <input class="form-control {{ $errors->has('project_name') ? 'is-invalid' : '' }}" type="text" name="project_name" id="project_name" value="{{ old('project_name', $castingRequirement->project_name ?? '') }}" required>
                        @if($errors->has('project_name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('project_name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="client_name">Client / Brand</label>
                        <input class="form-control {{ $errors->has('client_name') ? 'is-invalid' : '' }}" type="text" name="client_name" id="client_name" value="{{ old('client_name', $castingRequirement->client_name ?? '') }}">
                        @if($errors->has('client_name'))
                            <div class="invalid-feedback">{{ $errors->first('client_name') }}</div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="location">{{ trans('cruds.castingRequirement.fields.location') }}</label>
                        <input class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $castingRequirement->location ?? '') }}" autocomplete="off" placeholder="Search location">
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
                        <div class="text-danger small col-12">{{ $errors->first('shoot_date_time') }}</div>
                    @endif
                </div>
            </div>

            <div class="shoot-step" data-step="2">
                <h4>Stage 2 · Model Requirements</h4>
                <p class="text-muted mb-4">Define each role you need. Add multiple models for the same shoot.</p>

                <div data-model-requirements data-next-index="{{ count($modelInputs) }}">
                    @foreach($modelInputs as $index => $model)
                        <div class="model-card mb-4" data-model-card>
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h5 class="mb-0">Model #{{ $loop->iteration }}</h5>
                                    <small class="text-muted">Describe this requirement</small>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger" data-remove-model {{ $loop->count === 1 ? 'disabled' : '' }}>
                                    <i class="fas fa-times mr-1"></i>Remove
                                </button>
                            </div>
                            <div class="grid grid-2">
                                <div class="form-group">
                                    <label>Role / Title</label>
                                    <input type="text" name="models[{{ $index }}][title]" class="form-control @error('models.' . $index . '.title') is-invalid @enderror" value="{{ $model['title'] }}">
                                    @error('models.' . $index . '.title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required">Talents Needed</label>
                                    <input type="number" name="models[{{ $index }}][quantity]" min="1" class="form-control @error('models.' . $index . '.quantity') is-invalid @enderror" value="{{ $model['quantity'] }}">
                                    @error('models.' . $index . '.quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required">Gender</label>
                                    <select name="models[{{ $index }}][gender]" class="form-control @error('models.' . $index . '.gender') is-invalid @enderror">
                                        @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                            <option value="{{ $key }}" {{ ($model['gender'] ?? 'any') === $key ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('models.' . $index . '.gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label class="required">Age Range</label>
                                    <select name="models[{{ $index }}][age_range_key]" class="form-control @error('models.' . $index . '.age_range_key') is-invalid @enderror">
                                        @foreach($ageRanges as $key => $range)
                                            <option value="{{ $key }}" {{ ($model['age_range_key'] ?? array_key_first($ageRanges)) === $key ? 'selected' : '' }}>
                                                {{ $range['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('models.' . $index . '.age_range_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label>Hair Color</label>
                                    <input type="text" name="models[{{ $index }}][hair_color]" class="form-control @error('models.' . $index . '.hair_color') is-invalid @enderror" value="{{ $model['hair_color'] }}">
                                    @error('models.' . $index . '.hair_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group grid-span-2">
                                    <label>Required Labels</label>
                                    <div class="label-multiselect @error('models.' . $index . '.labels') is-invalid @enderror" data-label-select>
                                        <button type="button" class="label-multiselect__trigger" data-label-trigger>
                                            <span data-label-placeholder>{{ __('Select labels') }}</span>
                                            <div class="label-multiselect__tags" data-label-tags></div>
                                            <span class="label-multiselect__caret"><i class="fas fa-chevron-down"></i></span>
                                        </button>
                                        <div class="label-multiselect__dropdown" data-label-dropdown>
                                            @foreach($labels as $label)
                                                <label class="label-multiselect__option">
                                                    <input
                                                        type="checkbox"
                                                        value="{{ $label->id }}"
                                                        data-label-option
                                                        {{ in_array($label->id, $model['labels'] ?? []) ? 'checked' : '' }}
                                                    >
                                                    <span>{{ $label->name }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <select name="models[{{ $index }}][labels][]" multiple class="d-none" data-label-target>
                                            @foreach($labels as $label)
                                                <option value="{{ $label->id }}" {{ in_array($label->id, $model['labels'] ?? []) ? 'selected' : '' }}>
                                                    {{ $label->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <small class="text-muted">Only talents with these labels will see this shoot.</small>
                                    @error('models.' . $index . '.labels')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <button type="button" class="btn btn-outline-primary" data-add-model>
                    <i class="fas fa-plus mr-1"></i>Add New Model
                </button>
            </div>

            <template id="modelRequirementTemplate">
                <div class="model-card mb-4" data-model-card>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h5 class="mb-0">New Model</h5>
                            <small class="text-muted">Describe this requirement</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-link text-danger" data-remove-model>
                            <i class="fas fa-times mr-1"></i>Remove
                        </button>
                    </div>
                    <div class="grid grid-2">
                        <div class="form-group">
                            <label>Role / Title</label>
                            <input type="text" name="models[__INDEX__][title]" class="form-control">
                        </div>
                        <div class="form-group">
                            <label class="required">Talents Needed</label>
                            <input type="number" name="models[__INDEX__][quantity]" min="1" class="form-control" value="1">
                        </div>
                        <div class="form-group">
                            <label class="required">Gender</label>
                            <select name="models[__INDEX__][gender]" class="form-control">
                                @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label class="required">Age Range</label>
                            <select name="models[__INDEX__][age_range_key]" class="form-control">
                                @foreach($ageRanges as $key => $range)
                                    <option value="{{ $key }}">{{ $range['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Hair Color</label>
                            <input type="text" name="models[__INDEX__][hair_color]" class="form-control">
                        </div>
                        <div class="form-group grid-span-2">
                            <label>Required Labels</label>
                            <div class="label-multiselect" data-label-select>
                                <button type="button" class="label-multiselect__trigger" data-label-trigger>
                                    <span data-label-placeholder>{{ __('Select labels') }}</span>
                                    <div class="label-multiselect__tags" data-label-tags></div>
                                    <span class="label-multiselect__caret"><i class="fas fa-chevron-down"></i></span>
                                </button>
                                <div class="label-multiselect__dropdown" data-label-dropdown>
                                    @foreach($labels as $label)
                                        <label class="label-multiselect__option">
                                            <input type="checkbox" value="{{ $label->id }}" data-label-option>
                                            <span>{{ $label->name }}</span>
                                        </label>
                                    @endforeach
                                </div>
                                <select name="models[__INDEX__][labels][]" multiple class="d-none" data-label-target>
                                    @foreach($labels as $label)
                                        <option value="{{ $label->id }}">{{ $label->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="text-muted">Only talents with these labels will see this shoot.</small>
                        </div>
                    </div>
                </div>
            </template>

            <div class="shoot-step" data-step="3">
                <h4>Stage 3 · Notes & References</h4>
                <p class="text-muted mb-4">Share outfits, references, and any important instructions.</p>

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

                <div class="form-group">
                    <label for="notes">{{ trans('cruds.castingRequirement.fields.notes') }}</label>
                    <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes" rows="4">{{ old('notes', $castingRequirement->notes ?? '') }}</textarea>
                    @if($errors->has('notes'))
                        <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                    @endif
                </div>

                @if($isEdit)
                    <div class="form-group">
                        <label class="required" for="status">{{ trans('cruds.castingRequirement.fields.status') }}</label>
                        <select class="form-control {{ $errors->has('status') ? 'is-invalid' : '' }}" name="status" id="status" required>
                            <option value disabled {{ old('status', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                            @foreach(App\Models\CastingRequirement::STATUS_SELECT as $key => $label)
                                <option value="{{ $key }}" {{ old('status', $castingRequirement->status) === $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('status'))
                            <div class="invalid-feedback">{{ $errors->first('status') }}</div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

        <div class="shoot-builder__footer">
            <div class="step-status">
                Step <span data-step-indicator>1</span> of 3
            </div>
            <div>
                <button type="button" class="btn btn-outline-secondary" data-prev-step disabled>Back</button>
                <button type="button" class="btn btn-primary" data-next-step>Next</button>
                <button type="submit" class="btn btn-success d-none" data-submit-form>{{ $isEdit ? __('Update Shoot') : __('Save Shoot') }}</button>
            </div>
        </div>
    </form>
</div>

