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
    $timeSlots = \App\Models\CastingRequirementModel::TIME_SLOT_OPTIONS ?? [
        '01:00-05:00' => '01:00 - 05:00',
        '05:00-09:00' => '05:00 - 09:00',
        '09:00-13:00' => '09:00 - 13:00',
        '13:00-17:00' => '13:00 - 17:00',
        '17:00-21:00' => '17:00 - 21:00',
        '21:00-01:00' => '21:00 - 01:00',
    ];

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
        'rate' => 0,
        'rate_decision' => 'talent_decide',
        'gender' => 'male',
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
        'time_slot' => null,
        'reference_photos' => [],
    ];

    $modelInputs = old('models');
    if (! is_array($modelInputs) || empty($modelInputs)) {
        if ($isEdit && $castingRequirement) {
            $modelInputs = $castingRequirement->modelRequirements->map(function ($model) use ($ageRanges) {
                return [
                    'id' => $model->id,
                    'title' => $model->title,
                    'quantity' => $model->quantity,
                    'rate' => $model->rate,
                    'rate_decision' => $model->rate_decision ?? (($model->rate ?? 0) > 0 ? 'admin_decide' : 'talent_decide'),
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
                    'time_slot' => $model->time_slot,
                    'reference_photos' => $model->getMedia('reference_photo')->map->getUrl()->all(),
                ];
            })->toArray();
        }

        if (empty($modelInputs)) {
            $modelInputs = [$defaultModel];
        }
    }

    $modelInputs = array_map(function ($model) use ($defaultModel) {
        return array_merge($defaultModel, $model);
    }, $modelInputs);

    if (!function_exists('numberToWord')) {
        function numberToWord($num) {
            $words = [0 => 'Zero', 1 => 'One', 2 => 'Two', 3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six', 7 => 'Seven', 8 => 'Eight', 9 => 'Nine', 10 => 'Ten'];
            return $words[$num] ?? $num;
        }
    }

    if (!function_exists('humanizeModelError')) {
        function humanizeModelError(string $message): string {
            // Strip array notation like models.0.
            $message = preg_replace('/models\.\d+\./', '', $message);

            // Required field messages
            $message = preg_replace_callback('/The\s+([^.]+?)\s+field is required\./i', function ($m) {
                $field = ucwords(str_replace(['_', '.'], ' ', $m[1]));
                return "{$field} is required.";
            }, $message);

            // Invalid selection messages
            $message = preg_replace_callback('/The selected\s+([^.]+?)\s+is invalid\./i', function ($m) {
                $field = ucwords(str_replace(['_', '.'], ' ', $m[1]));
                return "{$field} is invalid.";
            }, $message);

            return trim($message);
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

    <form method="POST" action="{{ $formAction }}" enctype="multipart/form-data" id="shootWizard" data-default-status="{{ $isEdit ? '' : 'advertised' }}" novalidate>
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
                            <label for="instagram_url">Instagram URL</label>
                            <div class="dark-input">
                                <input class="{{ $errors->has('instagram_url') ? 'is-invalid' : '' }}" type="url" name="instagram_url" id="instagram_url" value="{{ old('instagram_url', $castingRequirement->instagram_url ?? '') }}" placeholder="https://instagram.com/username">
                            </div>
                            @if($errors->has('instagram_url'))
                                <div class="invalid-feedback d-block">{{ $errors->first('instagram_url') }}</div>
                            @endif
                        </div>
                    </div>

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label for="shoot_date">Date</label>
                            <div class="dark-input has-picker" id="datePickerTrigger">
                                <input class="{{ $errors->has('shoot_date') ? 'is-invalid' : '' }}" type="text" name="shoot_date" id="shoot_date" value="{{ $shootDateValue }}" readonly placeholder="mm/dd/yy">
                                <span class="picker-icon"><i class="far fa-calendar-alt"></i></span>
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
                                <input class="{{ $errors->has('shoot_time') ? 'is-invalid' : '' }}" type="text" name="shoot_time" id="shoot_time" value="{{ $shootTimeValue }}" readonly placeholder="--/--/--">
                                <span class="picker-icon"><i class="far fa-clock"></i></span>
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
                            <div style="display: flex; gap: 5px; align-items: center;">
                                <div class="dark-input" style="width: 80px;">
                                    <input
                                        class="{{ $errors->has('duration') ? 'is-invalid' : '' }}"
                                        type="number"
                                        name="duration"
                                        id="duration"
                                        value="{{ $durationValue }}"
                                        placeholder="0"
                                        min="0"
                                        step="1"
                                        style="text-align: center; color: #868c99;"
                                    >
                                </div>
                                <div class="dark-input" style="width: auto; border: none; background: transparent; padding-left: 0; box-shadow: none;">
                                    <input type="text" value="hours" readonly style="color: #6b7280; background: transparent; cursor: default; padding: 0; width: auto; font-weight: 500; text-transform: lowercase;">
                                </div>
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

                            <input type="hidden" name="models[{{ $index }}][id]" value="{{ $model['id'] ?? '' }}">
                            <input type="hidden" name="models[{{ $index }}][title]" value="{{ $modelLabel }}">
                            <input type="hidden" name="models[{{ $index }}][quantity]" value="{{ $model['quantity'] ?? 1 }}">

                            <div class="grid grid-3 condensed">
                                <div class="field-block">
                                    <label class="required">Gender</label>
                                    <select name="models[{{ $index }}][gender]" class="pill-select @error('models.' . $index . '.gender') is-invalid @enderror" data-gender-select required>
                                        @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                            @if($key !== 'any')
                                                <option value="{{ $key }}" {{ ($model['gender'] ?? '') === $key ? 'selected' : '' }}>{{ $label }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('models.' . $index . '.gender')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
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
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label class="required">Time Slot Start</label>
                                    <input
                                        type="time"
                                        name="models[{{ $index }}][time_slot]"
                                        class="pill-input time-slot-input @error('models.' . $index . '.time_slot') is-invalid @enderror"
                                        value="{{ $model['time_slot'] ?? '' }}"
                                        required
                                        style="width: 100%;"
                                    >
                                    <small style="color: #667085; font-size: 11px; margin-top: 4px; display: block;">Time must be within the shoot window (cannot be before start time, cannot extend beyond shoot duration). Must be added manually.</small>
                                    @error('models.' . $index . '.time_slot')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label class="required">Hours Needed</label>
                                    <div style="display: flex; gap: 10px; align-items: center;">
                                        <input
                                            class="pill-input"
                                            type="number"
                                            name="models[{{ $index }}][model_hours]"
                                            value="{{ $model['model_hours'] ?? '' }}"
                                            placeholder="4"
                                            min="1"
                                            required
                                            style="width: 80px; text-align: center;"
                                        >
                                        <span style="color: #4b5563; font-weight: 500;">hours</span>
                                    </div>
                                    <small style="color: #667085; font-size: 11px; margin-top: 4px; display: block;">Cannot exceed the total shoot duration</small>
                                    @error('models.' . $index . '.model_hours')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                            </div>
                            @php
                                $rateDecision = $model['rate_decision'] ?? 'admin_decide';
                            @endphp
                            <div class="grid grid-2 condensed" data-rate-container>
                                <div class="field-block" data-rate-choice-group>
                                    <label class="required">Rate?</label>
                                    <div class="rate-options">
                                        <label class="rate-option">
                                            <input type="radio" name="models[{{ $index }}][rate_decision]" value="admin_decide" {{ $rateDecision === 'admin_decide' ? 'checked' : '' }}>
                                            <span>Predefined</span>
                                        </label>
                                        <label class="rate-option">
                                            <input type="radio" name="models[{{ $index }}][rate_decision]" value="talent_decide" {{ $rateDecision !== 'admin_decide' ? 'checked' : '' }}>
                                            <span>Talent Decides</span>
                                        </label>
                                    </div>
                                    @error('models.' . $index . '.rate_decision')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                                <div class="field-block" data-rate-input-wrapper style="{{ $rateDecision === 'admin_decide' ? '' : 'display:none;' }}">
                                    <label class="required">Rate Amount (KWD)</label>
                                    <input
                                        class="pill-input @error('models.' . $index . '.rate') is-invalid @enderror"
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        name="models[{{ $index }}][rate]"
                                        value="{{ $model['rate'] ?? '' }}"
                                        placeholder="Enter rate (KWD)"
                                        data-rate-input
                                    >
                                    @error('models.' . $index . '.rate')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-3 condensed">
                                <div class="field-block">
                                    <label class="required">Height Range</label>
                                    <select name="models[{{ $index }}][height_range]" class="pill-select" required>
                                        <option value="" disabled {{ ($model['height_range'] ?? '') === '' ? 'selected' : '' }}>Choose height range</option>
                                        <option value="150-160" {{ ($model['height_range'] ?? '') === '150-160' ? 'selected' : '' }}>150 - 160 cm</option>
                                        <option value="161-170" {{ ($model['height_range'] ?? '') === '161-170' ? 'selected' : '' }}>161 - 170 cm</option>
                                        <option value="171-180" {{ ($model['height_range'] ?? '') === '171-180' ? 'selected' : '' }}>171 - 180 cm</option>
                                        <option value="180+" {{ ($model['height_range'] ?? '') === '180+' ? 'selected' : '' }}>180+ cm</option>
                                    </select>
                                    @error('models.' . $index . '.height_range')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>

                                <div class="field-block">
                                    <label class="required">Weight Range</label>
                                    <select name="models[{{ $index }}][weight_range]" class="pill-select" required>
                                        <option value="" disabled {{ ($model['weight_range'] ?? '') === '' ? 'selected' : '' }}>Choose weight range</option>
                                        <option value="40-50" {{ ($model['weight_range'] ?? '') === '40-50' ? 'selected' : '' }}>40 - 50 kg</option>
                                        <option value="51-60" {{ ($model['weight_range'] ?? '') === '51-60' ? 'selected' : '' }}>51 - 60 kg</option>
                                        <option value="61-70" {{ ($model['weight_range'] ?? '') === '61-70' ? 'selected' : '' }}>61 - 70 kg</option>
                                        <option value="71+" {{ ($model['weight_range'] ?? '') === '71+' ? 'selected' : '' }}>71+ kg</option>
                                    </select>
                                    @error('models.' . $index . '.weight_range')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="grid grid-2 condensed">
                                <div class="field-block">
                                    <label class="required">Skin Color</label>
                                    <div class="swatch-row" data-swatch-group>
                                        @php $skin = $model['skin_color'] ?? ''; @endphp
                                        <button type="button" class="swatch {{ $skin === 'tan' ? 'active' : '' }}" data-swatch-value="tan" style="background:#e6bd8d;"></button>
                                        <button type="button" class="swatch {{ $skin === 'golden' ? 'active' : '' }}" data-swatch-value="golden" style="background:#d7a86e;"></button>
                                        <button type="button" class="swatch {{ $skin === 'amber' ? 'active' : '' }}" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                        <button type="button" class="swatch {{ $skin === 'brown' ? 'active' : '' }}" data-swatch-value="brown" style="background:#8b5a2b;"></button>
                                        <button type="button" class="swatch swatch-any {{ $skin === '' ? 'active' : '' }}" data-swatch-value="" aria-label="Any skin color">
                                            <span class="phi-icon">Φ</span>
                                        </button>
                                    </div>
                                    <input type="hidden" name="models[{{ $index }}][skin_color]" value="{{ $skin }}" data-swatch-input>
                                    @error('models.' . $index . '.skin_color')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                                <div class="field-block">
                                    <label class="required">Eye Color</label>
                                    <div class="swatch-row" data-swatch-group>
                                        @php $eye = $model['eye_color'] ?? ''; @endphp
                                        <button type="button" class="swatch {{ $eye === 'amber' ? 'active' : '' }}" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                        <button type="button" class="swatch {{ $eye === 'hazel' ? 'active' : '' }}" data-swatch-value="hazel" style="background:#c9a063;"></button>
                                        <button type="button" class="swatch {{ $eye === 'brown' ? 'active' : '' }}" data-swatch-value="brown" style="background:#7a5230;"></button>
                                        <button type="button" class="swatch {{ $eye === 'black' ? 'active' : '' }}" data-swatch-value="black" style="background:#1b1b1d;"></button>
                                        <button type="button" class="swatch swatch-any {{ $eye === '' ? 'active' : '' }}" data-swatch-value="" aria-label="Any eye color">
                                            <span class="phi-icon">Φ</span>
                                        </button>
                                    </div>
                                    <input type="hidden" name="models[{{ $index }}][eye_color]" value="{{ $eye }}" data-swatch-input>
                                    @error('models.' . $index . '.eye_color')
                                        <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="reference-upload" data-reference-block>
                                <div class="reference-upload-title">Reference Photos</div>
                                <div class="reference-upload-sub">Optional. Share example poses or looks for this model.</div>
                                <label class="dropbox" data-file-drop>
                                    <div class="dropbox-inner">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <div class="drop-title" data-file-label>Upload Reference Photos</div>
                                        <div class="drop-sub">JPG or PNG · Max 10 MB each</div>
                                    </div>
                                    <input
                                        type="file"
                                        name="models[{{ $index }}][reference_photo][]"
                                        class="d-none"
                                        data-file-input
                                        accept="image/*"
                                        multiple
                                    >
                                </label>
                                @error('models.' . $index . '.reference_photo')
                                    <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                @enderror
                                @error('models.' . $index . '.reference_photo.*')
                                    <div class="invalid-feedback d-block">{{ humanizeModelError($message) }}</div>
                                @enderror

                                @if(!empty($model['reference_photos']))
                                    <div class="reference-preview-grid">
                                        @foreach($model['reference_photos'] as $photoUrl)
                                            <div class="reference-preview-item">
                                                <img src="{{ $photoUrl }}" alt="Reference photo">
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>

                            <div class="outfit-selection-container">
                                <div class="outfit-section-title">Outfit Selection</div>
                                <div class="outfit-section-subtitle">Select one or multiple outfits</div>

                                <div class="outfit-selection-grid">
                                    <!-- Male Outfits -->
                                    <div class="outfit-type-card" data-outfit-gender="male">
                                        <div class="outfit-type-header">
                                            <i class="fas fa-shopping-bag"></i>
                                            <span>Male Outfits</span>
                                            <button type="button" class="traditional-outfit-btn" data-toggle-traditional>Switch to Traditional Outfit</button>
                                        </div>
                                        <div class="outfit-item-group">
                                            <div class="outfit-sub-item" data-outfit-type="traditional" style="display: none;">
                                                <div class="item-image-box" data-image-target>Image for traditional</div>
                                                <div class="item-details">
                                                    <div class="item-label"><i class="fas fa-crown"></i> TRADITIONAL</div>
                                                    <select name="models[{{ $index }}][male_traditional_id]" class="item-select" data-outfit-select>
                                                        <option value="">Chose any</option>
                                                        @foreach($outfits['male'] ?? [] as $outfit)
                                                            @if($outfit->sub_category === 'traditional')
                                                                <option value="{{ $outfit->id }}" {{ ($model['male_traditional_id'] ?? '') == $outfit->id ? 'selected' : '' }}>{{ $outfit->name }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="outfit-sub-item" data-outfit-type="top">
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
                                            <div class="outfit-sub-item" data-outfit-type="bottom">
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
                                    <div class="outfit-type-card" data-outfit-gender="female">
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

                    <input type="hidden" name="models[__INDEX__][id]" value="">
                    <input type="hidden" name="models[__INDEX__][title]" value="Model __INDEX_DISPLAY__">
                    <input type="hidden" name="models[__INDEX__][quantity]" value="1">

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label class="required">Gender</label>
                            <select name="models[__INDEX__][gender]" class="pill-select" data-gender-select required>
                                @foreach(App\Models\CastingRequirement::GENDER_SELECT as $key => $label)
                                    @if($key !== 'any')
                                        <option value="{{ $key }}" {{ $key === 'male' ? 'selected' : '' }}>{{ $label }}</option>
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
                            <label class="required">Hours Needed</label>
                            <div style="display: flex; gap: 10px; align-items: center;">
                                <input
                                    class="pill-input"
                                    type="number"
                                    name="models[__INDEX__][model_hours]"
                                    placeholder="4"
                                    value=""
                                    min="1"
                                    required
                                    style="width: 80px; text-align: center;"
                                >
                                <span style="color: #4b5563; font-weight: 500;">Hours</span>
                            </div>
                        </div>
                                <div class="field-block">
                                    <label class="required">Time Slot Start (4 hrs)</label>
                                    <input
                                        type="time"
                                        name="models[__INDEX__][time_slot]"
                                        class="pill-input time-slot-input"
                                        value=""
                                        required
                                        style="width: 100%;"
                                    >
                                    <small style="color: #667085; font-size: 11px; margin-top: 4px; display: block;">Select start time (automatically 4-hour duration)</small>
                                </div>
                        </div>
                    <div class="grid grid-2 condensed" data-rate-container>
                        <div class="field-block" data-rate-choice-group>
                            <label class="required">Rate?</label>
                            <div class="rate-options">
                                <label class="rate-option">
                                    <input type="radio" name="models[__INDEX__][rate_decision]" value="admin_decide">
                                    <span>Predefined</span>
                                </label>
                                <label class="rate-option">
                                    <input type="radio" name="models[__INDEX__][rate_decision]" value="talent_decide" checked>
                                    <span>Talent Decides</span>
                                </label>
                            </div>
                        </div>
                        <div class="field-block" data-rate-input-wrapper style="display:none;">
                            <label class="required">Rate Amount (KWD)</label>
                            <input
                                class="pill-input"
                                type="number"
                                step="0.01"
                                min="0"
                                name="models[__INDEX__][rate]"
                                value="0"
                                placeholder="Enter rate (KWD)"
                                data-rate-input
                            >
                        </div>
                    </div>

                    <div class="grid grid-3 condensed">
                        <div class="field-block">
                            <label class="required">Height Range</label>
                            <select name="models[__INDEX__][height_range]" class="pill-select" required>
                                <option value="" disabled selected>Choose height range</option>
                                <option value="150-160">150 - 160 cm</option>
                                <option value="161-170">161 - 170 cm</option>
                                <option value="171-180">171 - 180 cm</option>
                                <option value="180+">180+ cm</option>
                            </select>
                        </div>
                        <div class="field-block">
                            <label class="required">Weight Range</label>
                            <select name="models[__INDEX__][weight_range]" class="pill-select" required>
                                <option value="" disabled selected>Choose weight range</option>
                                <option value="40-50">40 - 50 kg</option>
                                <option value="51-60">51 - 60 kg</option>
                                <option value="61-70">61 - 70 kg</option>
                                <option value="71+">71+ kg</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-2 condensed">
                         <div class="field-block">
                            <label class="required">Skin Color</label>
                            <div class="swatch-row" data-swatch-group>
                                <button type="button" class="swatch" data-swatch-value="tan" style="background:#e6bd8d;"></button>
                                <button type="button" class="swatch" data-swatch-value="golden" style="background:#d7a86e;"></button>
                                <button type="button" class="swatch" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                <button type="button" class="swatch" data-swatch-value="brown" style="background:#8b5a2b;"></button>
                                <button type="button" class="swatch swatch-any active" data-swatch-value="" aria-label="Any skin color"><span class="phi-icon">Φ</span></button>
                            </div>
                            <input type="hidden" name="models[__INDEX__][skin_color]" value="" data-swatch-input>
                        </div>
                        <div class="field-block">
                            <label class="required">Eye Color</label>
                            <div class="swatch-row" data-swatch-group>
                                <button type="button" class="swatch" data-swatch-value="amber" style="background:#c48b5a;"></button>
                                <button type="button" class="swatch" data-swatch-value="hazel" style="background:#c9a063;"></button>
                                <button type="button" class="swatch" data-swatch-value="brown" style="background:#7a5230;"></button>
                                <button type="button" class="swatch" data-swatch-value="black" style="background:#1b1b1d;"></button>
                                <button type="button" class="swatch swatch-any active" data-swatch-value="" aria-label="Any eye color"><span class="phi-icon">Φ</span></button>
                            </div>
                            <input type="hidden" name="models[__INDEX__][eye_color]" value="" data-swatch-input>
                        </div>
                    </div>
                    <div class="reference-upload" data-reference-block>
                        <div class="reference-upload-title">Reference Photos</div>
                        <div class="reference-upload-sub">Optional. Share example poses or looks for this model.</div>
                        <label class="dropbox" data-file-drop>
                            <div class="dropbox-inner">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <div class="drop-title" data-file-label>Upload Reference Photos</div>
                                <div class="drop-sub">JPG or PNG · Max 10 MB each</div>
                            </div>
                            <input
                                type="file"
                                name="models[__INDEX__][reference_photo][]"
                                class="d-none"
                                data-file-input
                                accept="image/*"
                                multiple
                            >
                        </label>
                    </div>

                    <div class="outfit-selection-container">
                        <div class="outfit-section-title">Outfit Selection</div>
                        <div class="outfit-section-subtitle">Select one or multiple outfits</div>

                        <div class="outfit-selection-grid">
                            <!-- Male Outfits -->
                            <div class="outfit-type-card" data-outfit-gender="male">
                                <div class="outfit-type-header">
                                    <i class="fas fa-shopping-bag"></i>
                                    <span>Male Outfits</span>
                                    <button type="button" class="traditional-outfit-btn" data-toggle-traditional>Switch to Traditional Outfit</button>
                                </div>
                                    <div class="outfit-item-group">
                                        <div class="outfit-sub-item" data-outfit-type="traditional" style="display: none;">
                                            <div class="item-image-box" data-image-target>Image for traditional</div>
                                            <div class="item-details">
                                                <div class="item-label"><i class="fas fa-crown"></i> TRADITIONAL</div>
                                                <select name="models[__INDEX__][male_traditional_id]" class="item-select" data-outfit-select>
                                                    <option value="">Chose any</option>
                                                    @foreach($outfits['male'] ?? [] as $outfit)
                                                        @if($outfit->sub_category === 'traditional')
                                                            <option value="{{ $outfit->id }}">{{ $outfit->name }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="outfit-sub-item" data-outfit-type="top">
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
                                        <div class="outfit-sub-item" data-outfit-type="bottom">
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
                                <div class="outfit-type-card" data-outfit-gender="female">
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
                        </div>
                    </div>
                </div>
            </template>

            <div class="shoot-step" data-step="3">
                <div class="shoot-step-card">
                    <h4>Stage 3 · Shoot Brief </h4>
                    <p class="text-muted mb-4">Share complete detail, and any important instructions.</p>

                    <div class="form-group d-none">
                        <label>{{ trans('cruds.castingRequirement.fields.outfit') }}</label>
                        <p class="text-muted small">Select one or multiple outfits</p>
                    </div>

                    <!-- Reference Photo Removed from Step 3 -->

                    <div class="field-block">
                        <label for="notes">Shoot Brief</label>
                        <div class="dark-input has-textarea">
                             <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes" rows="4" placeholder="Enter shoot brief...">{{ old('notes', $castingRequirement->notes ?? '') }}</textarea>
                        </div>
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

