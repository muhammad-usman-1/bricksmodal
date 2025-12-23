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
        'male_top_id' => null,
        'male_bottom_id' => null,
        'male_traditional_id' => null,
        'female_top_id' => null,
        'female_bottom_id' => null,
        'child_top_id' => null,
        'child_bottom_id' => null,
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
                    'male_top_id' => $model->male_top_id,
                    'male_bottom_id' => $model->male_bottom_id,
                    'male_traditional_id' => $model->male_traditional_id,
                    'female_top_id' => $model->female_top_id,
                    'female_bottom_id' => $model->female_bottom_id,
                    'child_top_id' => $model->child_top_id,
                    'child_bottom_id' => $model->child_bottom_id,
                    'height_range' => $model->height_range,
                    'weight_range' => $model->weight_range,
                    'skin_color' => $model->skin_color,
                    'eye_color' => $model->eye_color,
                ];
            })->toArray();
        }

        if (empty($modelInputs)) {
            $modelInputs = [$defaultModel];
        }
    }
    
    if (!function_exists('numberToWord')) {
        function numberToWord($num) {
            $words = [0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten'];
            return $words[$num] ?? $num;
        }
    }


    $outfitImageMap = [];
    foreach($outfits as $cat => $items) {
        foreach($items as $item) {
            $outfitImageMap[$item->id] = $item->image;
        }
    }
@endphp

<script>
    window.OUTFIT_IMAGE_MAP = @json($outfitImageMap);
</script>

<div class="shoot-page">
    <div class="shoot-header">
        <div>
            <div class="shoot-title1">Create New Shoot</div>
            <div class="shoot-subtitle">Configure details and model requirements.</div>
        </div>
        <a href="{{ route('admin.projects.dashboard') }}" class="shoot-back">Back to Shoots</a>
    </div>

    <div class="shoot-stepper" data-stepper>
        <div class="stepper-node active" data-stepper-node="1"><span>1</span></div>
        <div class="stepper-line active" data-stepper-line="1"></div>
        <div class="stepper-node" data-stepper-node="2"><span>2</span></div>
        <div class="stepper-line" data-stepper-line="2"></div>
        <div class="stepper-node" data-stepper-node="3"><span>3</span></div>
    </div>

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" id="shootWizard" data-default-status="{{ $isEdit ? '' : 'advertised' }}">
        @csrf
        @if($isEdit)
            @method('PUT')
        @endif

        <div class="shoot-steps" data-current-step="1">
            <div class="shoot-step" data-step="1">
                <div class="shoot-step-card">
                    <div class="field-block">
                        <label class="required" for="project_name">Shoot Title</label>
                        <div class="dark-input">
                            <input class="{{ $errors->has('project_name') ? 'is-invalid' : '' }}" type="text" name="project_name" id="project_name" value="{{ old('project_name', $castingRequirement->project_name ?? '') }}" placeholder="e.g. Summer Collection 2024" required>
                        </div>
                        @if($errors->has('project_name'))
                            <div class="invalid-feedback d-block">{{ $errors->first('project_name') }}</div>
                        @endif
                    </div>

                    <div class="grid grid-2 condensed">
                        <div class="field-block">
                            <label for="location">Location</label>
                            <div class="dark-input has-icon">
                                <span class="input-icon"><i class="fas fa-search"></i></span>
                                <input class="{{ $errors->has('location') ? 'is-invalid' : '' }}" type="text" name="location" id="location" value="{{ old('location', $castingRequirement->location ?? '') }}" autocomplete="off" placeholder="Search Google Maps...">
                            </div>
                            @if($errors->has('location'))
                                <div class="invalid-feedback d-block">{{ $errors->first('location') }}</div>
                            @endif
                        </div>

                        <div class="field-block">
                            <label for="instagram_url">Instagram url (Brand link)</label>
                            <div class="dark-input has-pill">
                                <input type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $castingRequirement->instagram_url ?? '') }}" placeholder="https://www.instagram.com/lowclub.studio/">

                            </div>
                        </div>
                    </div>

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label for="shoot_date">Date</label>
                            <div class="dark-input has-picker" id="datePickerTrigger">
                                <input class="{{ $errors->has('shoot_date') ? 'is-invalid' : '' }}" type="text" name="shoot_date" id="shoot_date" value="{{ $shootDateValue }}" readonly placeholder="Select Date">
                                <span class="picker-icon"><i class="fas fa-calendar-alt"></i></span>
                            </div>
                            <div class="custom-picker-dropdown" id="calendarDropdown">
                                <div class="picker-top-header">
                                    <span class="picker-title">Select Date</span>
                                    <button type="button" class="btn-close-picker"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="calendar-header">
                                    <button type="button" class="btn-prev-month"><i class="fas fa-chevron-left"></i></button>
                                    <div class="month-year-label">December 2025</div>
                                    <button type="button" class="btn-next-month"><i class="fas fa-chevron-right"></i></button>
                                </div>
                                <div class="calendar-weekdays">
                                    <span>Su</span><span>Mo</span><span>Tu</span><span>We</span><span>Th</span><span>Fr</span><span>Sa</span>
                                </div>
                                <div class="calendar-days" id="calendarDays"></div>
                                <div class="calendar-footer">
                                    <button type="button" class="btn-clear-date">Clear</button>
                                </div>
                            </div>
                            @if($errors->has('shoot_date'))
                                <div class="invalid-feedback d-block">{{ $errors->first('shoot_date') }}</div>
                            @endif
                        </div>

                        <div class="field-block">
                            <label for="shoot_time">Start Time</label>
                            <div class="dark-input has-picker" id="timePickerTrigger">
                                <input class="{{ $errors->has('shoot_time') ? 'is-invalid' : '' }}" type="text" name="shoot_time" id="shoot_time" value="{{ $shootTimeValue }}" readonly placeholder="Select Time">
                                <span class="picker-icon"><i class="fas fa-clock"></i></span>
                            </div>
                            <div class="custom-picker-dropdown" id="timeDropdown">
                                <div class="picker-top-header">
                                    <span class="picker-title" id="selectedTimeHeader">Select Time</span>
                                    <button type="button" class="btn-close-picker"><i class="fas fa-times"></i></button>
                                </div>
                                <div class="time-list-container" id="timeList">
                                    <!-- Times injected via JS -->
                                </div>
                            </div>
                            @if($errors->has('shoot_time'))
                                <div class="invalid-feedback d-block">{{ $errors->first('shoot_time') }}</div>
                            @endif
                        </div>

                        <div class="field-block">
                            <label for="duration">Duration</label>
                            <div class="duration-input">
                                <input
                                    class="duration-value {{ $errors->has('duration') ? 'is-invalid' : '' }}"
                                    type="number"
                                    name="duration"
                                    id="duration"
                                    value="{{ $durationValue }}"
                                    placeholder="2"
                                    min="0"
                                    step="1"
                                    inputmode="numeric"
                                    pattern="[0-9]*"
                                >
                                <span class="duration-unit">hours</span>
                            </div>
                            @if($errors->has('duration'))
                                <div class="invalid-feedback d-block">{{ $errors->first('duration') }}</div>
                            @endif
                        </div>
                    </div>

                    @if($errors->has('shoot_date_time'))
                        <div class="text-danger small">{{ $errors->first('shoot_date_time') }}</div>
                    @endif
                </div>
            </div>

            <div class="shoot-step" data-step="2">
                <div class="step2-head">
                    <div>
                        <div class="shoot-title">Model Specifications</div>
                        <div class="shoot-subtitle">Define the models needed for this shoot.</div>
                    </div>
                    <button type="button" class="add-model-btn" data-add-model><i class="fas fa-plus"></i> Add New Model</button>
                </div>

                <div data-model-requirements data-next-index="{{ count($modelInputs) }}">
                    @foreach($modelInputs as $index => $model)
                        @php 
                            $wordIndex = numberToWord($loop->iteration);
                            $modelLabel = $model['title'] ?: 'Model ' . $wordIndex; 
                        @endphp
                        <div class="model-spec-card" data-model-card>
                            <div class="model-card-head">
                                <div class="model-name">{{ $modelLabel }}</div>
                                <div class="model-actions">
                                    <button type="button" class="icon-btn" data-duplicate-model title="Duplicate"><i class="fas fa-copy"></i></button>
                                    <button type="button" class="icon-btn danger" data-remove-model {{ $loop->count === 1 ? 'disabled' : '' }} title="Remove"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>

                            <input type="hidden" name="models[{{ $index }}][title]" value="{{ $modelLabel }}">
                            <input type="hidden" name="models[{{ $index }}][quantity]" value="{{ $model['quantity'] ?? 1 }}">

                            <div class="grid grid-3 condensed">
                                <div class="field-block">
                                    <label class="required">Gender</label>
                                    <select name="models[{{ $index }}][gender]" class="pill-select @error('models.' . $index . '.gender') is-invalid @enderror" required>
                                        @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                            @if($key !== 'any')
                                                <option value="{{ $key }}" {{ ($model['gender'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('models.' . $index . '.gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label class="required">Age Range</label>
                                    <select name="models[{{ $index }}][age_range_key]" class="pill-select @error('models.' . $index . '.age_range_key') is-invalid @enderror" required>
                                        <option value="" disabled {{ ($model['age_range_key'] ?? '') === '' ? 'selected' : '' }}>Choose age range</option>
                                        @foreach($ageRanges as $key => $range)
                                            <option value="{{ $key }}" {{ ($model['age_range_key'] ?? array_key_first($ageRanges)) === $key ? 'selected' : '' }}>{{ $range['label'] }}</option>
                                        @endforeach
                                    </select>
                                    @error('models.' . $index . '.age_range_key')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label>Height Range</label>
                                    <select name="models[{{ $index }}][height_range]" class="pill-select">
                                        <option value="" {{ ($model['height_range'] ?? '') === '' ? 'selected' : '' }}>Choose height range</option>
                                        <option value="150-160" {{ ($model['height_range'] ?? '') === '150-160' ? 'selected' : '' }}>150 - 160 cm</option>
                                        <option value="161-170" {{ ($model['height_range'] ?? '') === '161-170' ? 'selected' : '' }}>161 - 170 cm</option>
                                        <option value="171-180" {{ ($model['height_range'] ?? '') === '171-180' ? 'selected' : '' }}>171 - 180 cm</option>
                                        <option value="180+" {{ ($model['height_range'] ?? '') === '180+' ? 'selected' : '' }}>180+ cm</option>
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-3 condensed">
                                <div class="field-block">
                                    <label>Weight Range</label>
                                    <select name="models[{{ $index }}][weight_range]" class="pill-select">
                                        <option value="" {{ ($model['weight_range'] ?? '') === '' ? 'selected' : '' }}>Choose weight range</option>
                                        <option value="40-50" {{ ($model['weight_range'] ?? '') === '40-50' ? 'selected' : '' }}>40 - 50 kg</option>
                                        <option value="51-60" {{ ($model['weight_range'] ?? '') === '51-60' ? 'selected' : '' }}>51 - 60 kg</option>
                                        <option value="61-70" {{ ($model['weight_range'] ?? '') === '61-70' ? 'selected' : '' }}>61 - 70 kg</option>
                                        <option value="71+" {{ ($model['weight_range'] ?? '') === '71+' ? 'selected' : '' }}>71+ kg</option>
                                    </select>
                                </div>

                                <div class="field-block">
                                    <label>Others</label>
                                    <input type="text" name="models[{{ $index }}][hair_color]" class="pill-input @error('models.' . $index . '.hair_color') is-invalid @enderror" placeholder="other details" value="{{ $model['hair_color'] ?? '' }}">
                                    @error('models.' . $index . '.hair_color')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label>Skin Color</label>
                                    <div class="swatch-row" data-swatch-group>
                                        @php $skin = $model['skin_color'] ?? ''; @endphp
                                        <button type="button" class="swatch {{ $skin === 'tan' ? 'active' : '' }}" data-swatch-value="tan" style="background:#e6bd8d;"></button>
                                        <button type="button" class="swatch {{ $skin === 'golden' ? 'active' : '' }}" data-swatch-value="golden" style="background:#d7a86e;"></button>
                                        <button type="button" class="swatch {{ $skin === 'amber' ? 'active' : '' }}" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                        <button type="button" class="swatch {{ $skin === 'brown' ? 'active' : '' }}" data-swatch-value="brown" style="background:#8b5a2b;"></button>
                                    </div>
                                    <input type="hidden" name="models[{{ $index }}][skin_color]" value="{{ $skin }}" data-swatch-input>
                                </div>
                            </div>

                            <div class="grid grid-2 condensed">
                                <div class="field-block">
                                    <label>Eye Color</label>
                                    <div class="swatch-row" data-swatch-group>
                                        @php $eye = $model['eye_color'] ?? ''; @endphp
                                        <button type="button" class="swatch {{ $eye === 'amber' ? 'active' : '' }}" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                        <button type="button" class="swatch {{ $eye === 'hazel' ? 'active' : '' }}" data-swatch-value="hazel" style="background:#c9a063;"></button>
                                        <button type="button" class="swatch {{ $eye === 'brown' ? 'active' : '' }}" data-swatch-value="brown" style="background:#7a5230;"></button>
                                        <button type="button" class="swatch {{ $eye === 'black' ? 'active' : '' }}" data-swatch-value="black" style="background:#1b1b1d;"></button>
                                    </div>
                                    <input type="hidden" name="models[{{ $index }}][eye_color]" value="{{ $eye }}" data-swatch-input>
                                </div>
                            </div>

                            <div class="field-block">
                                <label>Any Reference Photo</label>
                                <label class="dropbox" data-file-drop>
                                    <input type="file" class="d-none" name="models[{{ $index }}][reference_photo][]" data-file-input multiple>
                                    <div class="dropbox-inner">
                                        <i class="fas fa-upload"></i>
                                        <div class="drop-title" data-file-label>Upload Reference Photos</div>
                                        <div class="drop-sub">Drag and drop or click to browse</div>
                                    </div>
                                </label>
                            </div>

                            <div class="outfit-selection-container">
                                <div class="outfit-section-title">Outfit Selection</div>
                                <div class="outfit-section-subtitle">Select one or multiple outfits</div>

                                <div class="outfit-selection-grid">
                                    <!-- Male Outfits -->
                                    <div class="outfit-type-card">
                                        <div class="outfit-type-header">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>Male Outfits</span>
                                        </div>
                                        <div class="outfit-item-group">
                                            <button type="button" class="traditional-dress-btn">Traditional dress</button>
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for tops</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                    <select name="models[{{ $index }}][male_top_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['male'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'top')
                                                                <option value="{{ $outfit->id }}" {{ ($model['male_top_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for bottoms</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                    <select name="models[{{ $index }}][male_bottom_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['male'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'bottom')
                                                                <option value="{{ $outfit->id }}" {{ ($model['male_bottom_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Female Outfits -->
                                    <div class="outfit-type-card">
                                        <div class="outfit-type-header">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>Female Outfits</span>
                                        </div>
                                        <div class="outfit-item-group">
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for tops</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                    <select name="models[{{ $index }}][female_top_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['female'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'top')
                                                                <option value="{{ $outfit->id }}" {{ ($model['female_top_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for bottoms</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                    <select name="models[{{ $index }}][female_bottom_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['female'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'bottom')
                                                                <option value="{{ $outfit->id }}" {{ ($model['female_bottom_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Child Outfits -->
                                    <div class="outfit-type-card">
                                        <div class="outfit-type-header">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>Child Outfits</span>
                                        </div>
                                        <div class="outfit-item-group">
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for tops</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                    <select name="models[{{ $index }}][child_top_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['child'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'top')
                                                                <option value="{{ $outfit->id }}" {{ ($model['child_top_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="outfit-sub-item">
                                                <div class="item-image-box" data-image-target>Image for bottoms</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                    <select name="models[{{ $index }}][child_bottom_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['child'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'bottom')
                                                                <option value="{{ $outfit->id }}" {{ ($model['child_bottom_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <template id="modelRequirementTemplate">
                <div class="model-spec-card" data-model-card>
                    <div class="model-card-head">
                        <div class="model-name">Model __INDEX_DISPLAY__</div>
                        <div class="model-actions">
                            <button type="button" class="icon-btn" data-duplicate-model title="Duplicate"><i class="fas fa-copy"></i></button>
                            <button type="button" class="icon-btn danger" data-remove-model title="Remove"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>

                    <input type="hidden" name="models[__INDEX__][title]" value="Model __INDEX_DISPLAY__">
                    <input type="hidden" name="models[__INDEX__][quantity]" value="1">

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label class="required">Gender</label>
                            <select name="models[__INDEX__][gender]" class="pill-select" required>
                                @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                    @if($key !== 'any')
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="field-block">
                            <label class="required">Age Range</label>
                            <select name="models[__INDEX__][age_range_key]" class="pill-select" required>
                                <option value="" disabled selected>Choose age range</option>
                                @foreach($ageRanges as $key => $range)
                                    <option value="{{ $key }}">{{ $range['label'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field-block">
                            <label>Height Range</label>
                            <select name="models[__INDEX__][height_range]" class="pill-select">
                                <option value="" selected>Choose height range</option>
                                <option value="150-160">150 - 160 cm</option>
                                <option value="161-170">161 - 170 cm</option>
                                <option value="171-180">171 - 180 cm</option>
                                <option value="180+">180+ cm</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label>Weight Range</label>
                            <select name="models[__INDEX__][weight_range]" class="pill-select">
                                <option value="" selected>Choose weight range</option>
                                <option value="40-50">40 - 50 kg</option>
                                <option value="51-60">51 - 60 kg</option>
                                <option value="61-70">61 - 70 kg</option>
                                <option value="71+">71+ kg</option>
                            </select>
                        </div>
                        <div class="field-block">
                            <label>Others</label>
                            <input type="text" name="models[__INDEX__][hair_color]" class="pill-input" placeholder="other details">
                        </div>
                        <div class="field-block">
                            <label>Skin Color</label>
                            <div class="swatch-row" data-swatch-group>
                                <button type="button" class="swatch" data-swatch-value="tan" style="background:#e6bd8d;"></button>
                                <button type="button" class="swatch" data-swatch-value="golden" style="background:#d7a86e;"></button>
                                <button type="button" class="swatch" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                <button type="button" class="swatch" data-swatch-value="brown" style="background:#8b5a2b;"></button>
                            </div>
                            <input type="hidden" name="models[__INDEX__][skin_color]" value="" data-swatch-input>
                        </div>
                    </div>

                    <div class="grid grid-2 condensed">
                        <div class="field-block">
                            <label>Eye Color</label>
                            <div class="swatch-row" data-swatch-group>
                                <button type="button" class="swatch" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                <button type="button" class="swatch" data-swatch-value="hazel" style="background:#c9a063;"></button>
                                <button type="button" class="swatch" data-swatch-value="brown" style="background:#7a5230;"></button>
                                <button type="button" class="swatch" data-swatch-value="black" style="background:#1b1b1d;"></button>
                            </div>
                            <input type="hidden" name="models[__INDEX__][eye_color]" value="" data-swatch-input>
                        </div>
                    </div>

                    <div class="field-block">
                        <label>Any Reference Photo</label>
                        <label class="dropbox" data-file-drop>
                            <input type="file" class="d-none" name="models[__INDEX__][reference_photo][]" data-file-input multiple>
                            <div class="dropbox-inner">
                                <i class="fas fa-upload"></i>
                                <div class="drop-title" data-file-label>Upload Reference Photos</div>
                                <div class="drop-sub">Drag and drop or click to browse</div>
                            </div>
                        </label>
                    </div>

                    <div class="outfit-selection-container">
                        <div class="outfit-section-title">Outfit Selection</div>
                        <div class="outfit-section-subtitle">Select one or multiple outfits</div>

                        <div class="outfit-selection-grid">
                            <!-- Male Outfits -->
                            <div class="outfit-type-card">
                                <div class="outfit-type-header">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Male Outfits</span>
                                </div>
                                    <div class="outfit-item-group">
                                        <button type="button" class="traditional-dress-btn">Traditional dress</button>
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for tops</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                <select name="models[__INDEX__][male_top_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['male'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'top')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for bottoms</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                <select name="models[__INDEX__][male_bottom_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['male'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'bottom')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Female Outfits -->
                                <div class="outfit-type-card">
                                    <div class="outfit-type-header">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Female Outfits</span>
                                    </div>
                                    <div class="outfit-item-group">
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for tops</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                <select name="models[__INDEX__][female_top_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['female'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'top')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for bottoms</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                <select name="models[__INDEX__][female_bottom_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['female'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'bottom')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Child Outfits -->
                                <div class="outfit-type-card">
                                    <div class="outfit-type-header">
                                        <i class="fas fa-shopping-bag"></i>
                                        <span>Child Outfits</span>
                                    </div>
                                    <div class="outfit-item-group">
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for tops</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-tshirt"></i> TOP</div>
                                                <select name="models[__INDEX__][child_top_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['child'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'top')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="outfit-sub-item">
                                            <div class="item-image-box" data-image-target>Image for bottoms</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-vial"></i> BOTTOM</div>
                                                <select name="models[__INDEX__][child_bottom_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['child'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'bottom')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
            </template>

            <div class="shoot-step" data-step="3">
                <h4>Stage 3 · Notes & References</h4>
                <p class="text-muted mb-4">Share outfits, references, and any important instructions.</p>

                <div class="form-group d-none">
                    <label>{{ trans('cruds.castingRequirement.fields.outfit') }}</label>
                    <p class="text-muted small">Select one or multiple outfits</p>
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
            <button type="button" class="footer-back" data-prev-step disabled>Back</button>
            <div class="footer-actions">
                <span class="step-status">Step <span data-step-indicator>1</span> of 3</span>
                <button type="button" class="footer-next" data-next-step>Next Step <i class="fas fa-arrow-right"></i></button>
                <button type="submit" class="footer-submit d-none" data-submit-form>{{ $isEdit ? __('Update Shoot') : __('Save Shoot') }}</button>
            </div>
        </div>
    </form>
</div>

