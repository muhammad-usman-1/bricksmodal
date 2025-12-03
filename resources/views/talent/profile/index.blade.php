@extends('layouts.talent')

@section('styles')
    <style>
        .form-group--labels {
            display: flex;
            flex-direction: column;
        }

        .tag-picker-wrapper {
            position: relative;
        }

        .tag-picker-trigger {
            width: 100%;
            min-height: 42px;
            border: 1px solid #e9d3d1;
            border-radius: 10px;
            background: #f7efee;
            padding: 10px 40px 10px 12px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            align-items: center;
            cursor: pointer;
        }

        .tag-picker-trigger:focus {
            border-color: #d9bebc;
            box-shadow: 0 0 0 3px rgba(212, 169, 165, .18);
            outline: none;
        }

        .tag-picker-values {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }

        .tag-picker-placeholder {
            color: #a58885;
            font-weight: 600;
        }

        .tag-picker-badge {
            background: #f6e6e4;
            color: #8a6561;
            border-radius: 999px;
            padding: 2px 10px;
            font-size: 12px;
            font-weight: 700;
        }

        .tag-picker-caret {
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #8a6561;
            pointer-events: none;
        }

        .tag-picker-dropdown {
            position: absolute;
            inset-inline-start: 0;
            width: 100%;
            margin-top: 6px;
            background: #fff;
            border: 1px solid #e9d3d1;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, .08);
            max-height: 220px;
            overflow-y: auto;
            display: none;
            z-index: 30;
        }

        .tag-picker-dropdown.is-open {
            display: block;
        }

        .tag-picker-option {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #f1e7e6;
            font-weight: 600;
            color: #6c5a58;
        }

        .tag-picker-option:last-child {
            border-bottom: none;
        }

        .tag-picker-option:hover {
            background: #fff9f8;
        }
    </style>
@endsection

@section('scripts')
    <script>
        (function () {
            const pickers = document.querySelectorAll('[data-label-picker]');
            if (!pickers.length) {
                return;
            }

            pickers.forEach((picker) => {
                const trigger = picker.querySelector('[data-picker-trigger]');
                const dropdown = picker.querySelector('[data-picker-dropdown]');
                const select = picker.querySelector('[data-picker-select]');
                const valuesWrap = picker.querySelector('[data-picker-values]');
                const placeholder = picker.querySelector('[data-picker-placeholder]');
                const checkboxes = picker.querySelectorAll('[data-label-option]');

                const closeDropdown = () => dropdown.classList.remove('is-open');

                const refresh = () => {
                    const selected = [];
                    checkboxes.forEach((checkbox) => {
                        const option = select.querySelector(`option[value="${checkbox.value}"]`);
                        if (option) {
                            option.selected = checkbox.checked;
                        }
                        if (checkbox.checked) {
                            selected.push(checkbox.getAttribute('data-label-name') || checkbox.value);
                        }
                    });

                    valuesWrap.innerHTML = '';
                    if (!selected.length) {
                        placeholder.style.display = 'inline';
                    } else {
                        placeholder.style.display = 'none';
                        selected.forEach((label) => {
                            const badge = document.createElement('span');
                            badge.className = 'tag-picker-badge';
                            badge.textContent = label;
                            valuesWrap.appendChild(badge);
                        });
                    }
                };

                trigger.addEventListener('click', (event) => {
                    event.stopPropagation();
                    dropdown.classList.toggle('is-open');
                });

                checkboxes.forEach((checkbox) => {
                    checkbox.addEventListener('change', refresh);
                });

                document.addEventListener('click', (event) => {
                    if (!picker.contains(event.target)) {
                        closeDropdown();
                    }
                });

                refresh();
            });
        })();
    </script>
@endsection

@section('content')
<div class="content">
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white d-flex flex-column flex-md-row align-items-md-center justify-content-between">
                    <div>
                        <h4 class="mb-0 text-capitalize">{{ __('My Profile') }}</h4>
                        <small class="text-muted">{{ __('Review and update the information that admins see.') }}</small>
                    </div>
                    <ul class="nav nav-pills mt-3 mt-md-0" id="talentProfileTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="view-tab" data-toggle="tab" href="#profile-view" role="tab" aria-controls="profile-view" aria-selected="true">
                                <i class="fas fa-eye mr-1"></i>{{ __('Overview') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="edit-tab" data-toggle="tab" href="#profile-edit" role="tab" aria-controls="profile-edit" aria-selected="false">
                                <i class="fas fa-pen mr-1"></i>{{ __('Update Profile') }}
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body tab-content" id="talentProfileTabsContent">
                    <div class="tab-pane fade show active" id="profile-view" role="tabpanel" aria-labelledby="view-tab">
                        <div class="row">
                            <div class="col-lg-6">
                                <h5 class="mb-3">{{ trans('global.profile_information') }}</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.id') }}</th>
                                            <td>{{ $profile->id }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.legal_name') }}</th>
                                            <td>{{ $profile->legal_name }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                                            <td>{{ $profile->display_name ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('global.email_address') }}</th>
                                            <td>{{ auth('talent')->user()->email ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('global.date_of_birth') }}</th>
                                            <td>{{ optional($profile->date_of_birth)->format(config('panel.date_format')) ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('global.gender') }}</th>
                                            <td>{{ trans('global.gender_display.' . ($profile->gender ?? '')) ?: trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.language') }}</th>
                                            <td>
                                                @forelse($profile->languages as $language)
                                                    <span class="badge badge-info mr-1">{{ $language->title }}</span>
                                                @empty
                                                    <span class="text-muted">{{ trans('global.not_set') }}</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.labels') }}</th>
                                            <td>
                                                @forelse($profile->labels as $label)
                                                    <span class="badge badge-secondary mr-1">{{ $label->name }}</span>
                                                @empty
                                                    <span class="text-muted">{{ trans('global.not_set') }}</span>
                                                @endforelse
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.bio') }}</th>
                                            <td>{{ $profile->bio ?: trans('global.not_set') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-3">{{ trans('global.account_information') }}</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.verification_status') }}</th>
                                            <td>{{ $statusOptions[$profile->verification_status] ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.verification_notes') }}</th>
                                            <td>{{ $profile->verification_notes ?: trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('global.onboarding_completed_at') }}</th>
                                            <td>{{ optional($profile->onboarding_completed_at)->format(config('panel.date_format') . ' ' . config('panel.time_format')) ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.daily_rate') }}</th>
                                            <td>{{ $profile->daily_rate }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.hourly_rate') }}</th>
                                            <td>{{ $profile->hourly_rate ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('global.whatsapp_number') }}</th>
                                            <td>{{ $profile->whatsapp_number ? '+' . $profile->whatsapp_number : trans('global.not_set') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-lg-6">
                                <h5 class="mb-3">{{ trans('global.measurements') }}</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.height') }}</th>
                                            <td>{{ $profile->height ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.weight') }}</th>
                                            <td>{{ $profile->weight ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.chest') }}</th>
                                            <td>{{ $profile->chest ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.waist') }}</th>
                                            <td>{{ $profile->waist ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.hips') }}</th>
                                            <td>{{ $profile->hips ?? trans('global.not_set') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-lg-6">
                                <h5 class="mb-3">{{ trans('global.appearance_details') }}</h5>
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.skin_tone') }}</th>
                                            <td>{{ $skinToneOptions[$profile->skin_tone] ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.hair_color') }}</th>
                                            <td>{{ $profile->hair_color ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.eye_color') }}</th>
                                            <td>{{ $profile->eye_color ?? trans('global.not_set') }}</td>
                                        </tr>
                                        <tr>
                                            <th>{{ trans('cruds.talentProfile.fields.shoe_size') }}</th>
                                            <td>{{ $profile->shoe_size ?? trans('global.not_set') }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        @php
                            $documentImages = [
                                'id_front_path' => trans('global.id_front'),
                                'id_back_path' => trans('global.id_back'),
                            ];
                            $headshots = [
                                'headshot_center_path' => trans('global.headshot_center'),
                                'headshot_left_path' => trans('global.headshot_left'),
                                'headshot_right_path' => trans('global.headshot_right'),
                            ];
                            $fullBody = [
                                'full_body_front_path' => trans('global.full_body_front'),
                                'full_body_right_path' => trans('global.full_body_right'),
                                'full_body_back_path' => trans('global.full_body_back'),
                            ];
                        @endphp

                        <div class="mt-4">
                            <h5 class="mb-3">{{ trans('global.id_documents') }}</h5>
                            <div class="row">
                                @foreach($documentImages as $field => $label)
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-2 text-center h-100">
                                            <small class="d-block text-muted mb-2">{{ $label }}</small>
                                            @if($profile->{$field})
                                                <a href="{{ $profile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                                <img src="{{ $profile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                            @else
                                                <span class="text-muted">{{ trans('global.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">{{ trans('global.headshots') }}</h5>
                            <div class="row">
                                @foreach($headshots as $field => $label)
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-2 text-center h-100">
                                            <small class="d-block text-muted mb-2">{{ $label }}</small>
                                            @if($profile->{$field})
                                                <a href="{{ $profile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                                <img src="{{ $profile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                            @else
                                                <span class="text-muted">{{ trans('global.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="mt-4">
                            <h5 class="mb-3">{{ trans('global.full_body_photos') }}</h5>
                            <div class="row">
                                @foreach($fullBody as $field => $label)
                                    <div class="col-md-4 mb-3">
                                        <div class="border rounded p-2 text-center h-100">
                                            <small class="d-block text-muted mb-2">{{ $label }}</small>
                                            @if($profile->{$field})
                                                <a href="{{ $profile->{$field} }}" target="_blank" rel="noopener" class="d-block mb-2">{{ trans('global.view_full_image') }}</a>
                                                <img src="{{ $profile->{$field} }}" alt="{{ $label }}" class="img-fluid rounded">
                                            @else
                                                <span class="text-muted">{{ trans('global.not_set') }}</span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="profile-edit" role="tabpanel" aria-labelledby="edit-tab">
                        <form method="POST" action="{{ route('talent.profile.update') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="legal_name">{{ trans('cruds.talentProfile.fields.legal_name') }}</label>
                                        <input type="text" class="form-control @error('legal_name') is-invalid @enderror" id="legal_name" name="legal_name" value="{{ old('legal_name', $profile->legal_name) }}" required>
                                        @error('legal_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="display_name">{{ trans('cruds.talentProfile.fields.display_name') }}</label>
                                        <input type="text" class="form-control @error('display_name') is-invalid @enderror" id="display_name" name="display_name" value="{{ old('display_name', $profile->display_name) }}">
                                        @error('display_name') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email">{{ trans('global.email_address') }}</label>
                                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', auth('talent')->user()->email) }}" required>
                                        @error('email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="whatsapp_number">{{ trans('global.whatsapp_number') }}</label>
                                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror" id="whatsapp_number" name="whatsapp_number" value="{{ old('whatsapp_number', $profile->whatsapp_number ? '+' . $profile->whatsapp_number : '') }}">
                                        @error('whatsapp_number') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="date_of_birth">{{ trans('global.date_of_birth') }}</label>
                                        <input type="date" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ old('date_of_birth', optional($profile->date_of_birth)->format('Y-m-d')) }}">
                                        @error('date_of_birth') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="gender">{{ trans('global.gender') }}</label>
                                        <select class="form-control @error('gender') is-invalid @enderror" id="gender" name="gender">
                                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                                            @foreach(['male' => trans('global.gender_male'), 'female' => trans('global.gender_female')] as $value => $label)
                                                <option value="{{ $value }}" {{ old('gender', $profile->gender) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('gender') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="bio">{{ trans('cruds.talentProfile.fields.bio') }}</label>
                                <textarea class="form-control @error('bio') is-invalid @enderror" id="bio" name="bio" rows="3">{{ old('bio', $profile->bio) }}</textarea>
                                @error('bio') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group">
                                <label for="languages">{{ trans('cruds.talentProfile.fields.language') }}</label>
                                <select class="form-control @error('languages') is-invalid @enderror" id="languages" name="languages[]" multiple>
                                    @php $selectedLanguages = collect(old('languages', $profile->languages->pluck('id')->all())); @endphp
                                    @foreach($languages as $language)
                                        <option value="{{ $language->id }}" {{ $selectedLanguages->contains($language->id) ? 'selected' : '' }}>
                                            {{ $language->title }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('languages') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                @error('languages.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="form-group form-group--labels">
                                <label for="talent_labels" class="mb-2">{{ __('Select Tag') }}</label>
                                @php $selectedLabelIds = collect(old('labels', $profile->labels->pluck('id')->all())); @endphp
                                <div class="tag-picker-wrapper" data-label-picker>
                                    <div class="tag-picker-trigger" tabindex="0" data-picker-trigger>
                                        <div class="tag-picker-values" data-picker-values></div>
                                        <span class="tag-picker-placeholder" data-picker-placeholder>{{ __('Select labels') }}</span>
                                        <span class="tag-picker-caret"><i class="fas fa-chevron-down"></i></span>
                                    </div>
                                    <div class="tag-picker-dropdown" data-picker-dropdown>
                                        @foreach($availableLabels as $label)
                                            <label class="tag-picker-option">
                                                <input
                                                    type="checkbox"
                                                    value="{{ $label->id }}"
                                                    data-label-option
                                                    data-label-name="{{ $label->name }}"
                                                    {{ $selectedLabelIds->contains($label->id) ? 'checked' : '' }}
                                                >
                                                <span>{{ $label->name }}</span>
                                            </label>
                                        @endforeach
                                    </div>
                                    <select
                                        id="talent_labels"
                                        class="d-none"
                                        name="labels[]"
                                        multiple
                                        data-picker-select
                                    >
                                        @foreach($availableLabels as $label)
                                            <option value="{{ $label->id }}" {{ $selectedLabelIds->contains($label->id) ? 'selected' : '' }}>
                                                {{ $label->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="form-text text-muted">
                                        {{ __('Use the input to open the dropdown, then tap labels to multi-select.') }}
                                    </small>
                                </div>
                                @error('labels') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                @error('labels.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="daily_rate">{{ trans('cruds.talentProfile.fields.daily_rate') }}</label>
                                        <input type="number" step="0.01" class="form-control @error('daily_rate') is-invalid @enderror" id="daily_rate" name="daily_rate" value="{{ old('daily_rate', $profile->daily_rate) }}" required>
                                        @error('daily_rate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hourly_rate">{{ trans('cruds.talentProfile.fields.hourly_rate') }}</label>
                                        <input type="number" step="0.01" class="form-control @error('hourly_rate') is-invalid @enderror" id="hourly_rate" name="hourly_rate" value="{{ old('hourly_rate', $profile->hourly_rate) }}">
                                        @error('hourly_rate') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="shoe_size">{{ trans('cruds.talentProfile.fields.shoe_size') }}</label>
                                        <input type="number" class="form-control @error('shoe_size') is-invalid @enderror" id="shoe_size" name="shoe_size" value="{{ old('shoe_size', $profile->shoe_size) }}">
                                        @error('shoe_size') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="height">{{ trans('cruds.talentProfile.fields.height') }}</label>
                                        <input type="number" class="form-control @error('height') is-invalid @enderror" id="height" name="height" value="{{ old('height', $profile->height) }}">
                                        @error('height') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="weight">{{ trans('cruds.talentProfile.fields.weight') }}</label>
                                        <input type="number" class="form-control @error('weight') is-invalid @enderror" id="weight" name="weight" value="{{ old('weight', $profile->weight) }}">
                                        @error('weight') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="skin_tone">{{ trans('cruds.talentProfile.fields.skin_tone') }}</label>
                                        <select class="form-control @error('skin_tone') is-invalid @enderror" id="skin_tone" name="skin_tone">
                                            <option value="">{{ trans('global.pleaseSelect') }}</option>
                                            @foreach($skinToneOptions as $value => $label)
                                                <option value="{{ $value }}" {{ old('skin_tone', $profile->skin_tone) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('skin_tone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="chest">{{ trans('cruds.talentProfile.fields.chest') }}</label>
                                        <input type="number" class="form-control @error('chest') is-invalid @enderror" id="chest" name="chest" value="{{ old('chest', $profile->chest) }}">
                                        @error('chest') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="waist">{{ trans('cruds.talentProfile.fields.waist') }}</label>
                                        <input type="number" class="form-control @error('waist') is-invalid @enderror" id="waist" name="waist" value="{{ old('waist', $profile->waist) }}">
                                        @error('waist') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="hips">{{ trans('cruds.talentProfile.fields.hips') }}</label>
                                        <input type="number" class="form-control @error('hips') is-invalid @enderror" id="hips" name="hips" value="{{ old('hips', $profile->hips) }}">
                                        @error('hips') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="hair_color">{{ trans('cruds.talentProfile.fields.hair_color') }}</label>
                                        <input type="text" class="form-control @error('hair_color') is-invalid @enderror" id="hair_color" name="hair_color" value="{{ old('hair_color', $profile->hair_color) }}">
                                        @error('hair_color') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="eye_color">{{ trans('cruds.talentProfile.fields.eye_color') }}</label>
                                        <input type="text" class="form-control @error('eye_color') is-invalid @enderror" id="eye_color" name="eye_color" value="{{ old('eye_color', $profile->eye_color) }}">
                                        @error('eye_color') <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">{{ __('Update Documents & Photos') }}</h5>
                            <p class="text-muted">{{ __('Upload a new file to replace the existing one. Leave blank to keep the current file.') }}</p>

                            @php
                                $fileInputs = [
                                    ['field' => 'id_front', 'label' => trans('global.id_front'), 'column' => 'id_front_path'],
                                    ['field' => 'id_back', 'label' => trans('global.id_back'), 'column' => 'id_back_path'],
                                    ['field' => 'headshot_center', 'label' => trans('global.headshot_center'), 'column' => 'headshot_center_path'],
                                    ['field' => 'headshot_left', 'label' => trans('global.headshot_left'), 'column' => 'headshot_left_path'],
                                    ['field' => 'headshot_right', 'label' => trans('global.headshot_right'), 'column' => 'headshot_right_path'],
                                    ['field' => 'full_body_front', 'label' => trans('global.full_body_front'), 'column' => 'full_body_front_path'],
                                    ['field' => 'full_body_right', 'label' => trans('global.full_body_right'), 'column' => 'full_body_right_path'],
                                    ['field' => 'full_body_back', 'label' => trans('global.full_body_back'), 'column' => 'full_body_back_path'],
                                ];
                            @endphp

                            <div class="row">
                                @foreach($fileInputs as $input)
                                    @php($field = $input['field'])
                                    <div class="col-md-4 mb-3">
                                        <div class="form-group">
                                            <label for="{{ $field }}">{{ $input['label'] }}</label>
                                            <input type="file" class="form-control-file @error($field) is-invalid @enderror" id="{{ $field }}" name="{{ $field }}">
                                            @error($field) <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                                            @php($column = $input['column'])
                                            @if($profile->{$column})
                                                <small class="d-block mt-2">
                                                    <a href="{{ $profile->{$column} }}" target="_blank">{{ __('Current file') }}</a>
                                                </small>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="text-right mt-4">
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="fas fa-save mr-1"></i>{{ __('Save changes') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

