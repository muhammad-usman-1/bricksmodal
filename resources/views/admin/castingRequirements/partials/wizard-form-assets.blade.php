@php
    $isEdit = $isEdit ?? false;
@endphp
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
    .grid-span-2 {
        grid-column: span 2;
    }
    .model-card {
        border: 1px solid #e2e8f0;
        border-radius: 16px;
        padding: 20px;
        background: #fff;
        box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06);
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

    .label-multiselect {
        position: relative;
        width: 100%;
    }

    .label-multiselect__trigger {
        width: 100%;
        min-height: 42px;
        border: 1px solid #ced4da;
        border-radius: 8px;
        background: #fff;
        padding: 8px 40px 8px 12px;
        display: flex;
        align-items: center;
        gap: 6px;
        text-align: left;
    }

    .label-multiselect__trigger:focus {
        outline: none;
        border-color: #7c3aed;
        box-shadow: 0 0 0 3px rgba(124, 58, 237, 0.15);
    }

    .label-multiselect__tags {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }

    .label-multiselect__tag {
        background: #ede9fe;
        color: #5b21b6;
        padding: 2px 8px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
    }

    .label-multiselect__placeholder {
        color: #6b7280;
        font-weight: 600;
    }

    .label-multiselect__caret {
        margin-left: auto;
        color: #6b7280;
    }

    .label-multiselect__dropdown {
        position: absolute;
        top: calc(100% + 4px);
        left: 0;
        width: 100%;
        max-height: 220px;
        overflow-y: auto;
        border: 1px solid #e4e4e7;
        border-radius: 10px;
        background: #fff;
        box-shadow: 0 15px 30px rgba(15, 23, 42, 0.15);
        z-index: 30;
        display: none;
    }

    .label-multiselect__dropdown.is-open {
        display: block;
    }

    .label-multiselect__option {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 8px 12px;
        cursor: pointer;
    }

    .label-multiselect__option:hover {
        background: #f4f4f5;
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
        const defaultStatus = form.dataset.defaultStatus;
        if (defaultStatus && !form.querySelector('input[name="status"]')) {
            form.insertAdjacentHTML('beforeend', '<input type="hidden" name="status" value="' + defaultStatus + '">');
        }
    }, { once: true });

    showStep(currentStep);
});

document.addEventListener('DOMContentLoaded', function () {
    const modelsContainer = document.querySelector('[data-model-requirements]');
    const template = document.getElementById('modelRequirementTemplate');
    const addModelBtn = document.querySelector('[data-add-model]');

    if (!modelsContainer || !template || !addModelBtn) {
        return;
    }

    let nextIndex = parseInt(modelsContainer.getAttribute('data-next-index'), 10) || modelsContainer.querySelectorAll('[data-model-card]').length;

    const createModelCard = (index) => {
        const wrapper = document.createElement('div');
        wrapper.innerHTML = template.innerHTML.replace(/__INDEX__/g, index).trim();
        return wrapper.firstElementChild;
    };

    addModelBtn.addEventListener('click', () => {
        const card = createModelCard(nextIndex);
        modelsContainer.appendChild(card);
        nextIndex++;
        if (window.__initLabelMultiselect) {
            window.__initLabelMultiselect(card);
        }
    });

    modelsContainer.addEventListener('click', (event) => {
        const trigger = event.target.closest('[data-remove-model]');
        if (!trigger) {
            return;
        }
        const cards = modelsContainer.querySelectorAll('[data-model-card]');
        if (cards.length <= 1) {
            alert('At least one model requirement is required.');
            return;
        }
        trigger.closest('[data-model-card]').remove();
    });

    if (window.__initLabelMultiselect) {
        window.__initLabelMultiselect(document);
    }
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

(function () {
    const init = (scope = document) => {
        scope.querySelectorAll('[data-label-select]').forEach(wrapper => {
            if (wrapper.dataset.initialized === 'true') {
                return;
            }
            wrapper.dataset.initialized = 'true';

            const trigger = wrapper.querySelector('[data-label-trigger]');
            const dropdown = wrapper.querySelector('[data-label-dropdown]');
            const placeholder = wrapper.querySelector('[data-label-placeholder]');
            const tagsWrap = wrapper.querySelector('[data-label-tags]');
            const select = wrapper.querySelector('[data-label-target]');
            const checkboxes = wrapper.querySelectorAll('[data-label-option]');

            const closeDropdown = () => dropdown.classList.remove('is-open');
            const openDropdown = () => dropdown.classList.add('is-open');

            const syncSelect = () => {
                const selectedValues = [];
                checkboxes.forEach(checkbox => {
                    const value = checkbox.value;
                    const option = select.querySelector(`option[value="${value}"]`);
                    if (option) {
                        option.selected = checkbox.checked;
                    }
                    if (checkbox.checked) {
                        selectedValues.push({
                            value,
                            label: checkbox.nextElementSibling?.textContent?.trim() || value,
                        });
                    }
                });

                tagsWrap.innerHTML = '';
                if (selectedValues.length === 0) {
                    placeholder?.classList.remove('d-none');
                } else {
                    placeholder?.classList.add('d-none');
                    selectedValues.slice(0, 3).forEach(item => {
                        const tag = document.createElement('span');
                        tag.className = 'label-multiselect__tag';
                        tag.textContent = item.label;
                        tagsWrap.appendChild(tag);
                    });
                    if (selectedValues.length > 3) {
                        const more = document.createElement('span');
                        more.className = 'label-multiselect__tag';
                        more.textContent = `+${selectedValues.length - 3}`;
                        tagsWrap.appendChild(more);
                    }
                }
            };

            trigger.addEventListener('click', (event) => {
                event.stopPropagation();
                dropdown.classList.toggle('is-open');
            });

            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', syncSelect);
            });

            document.addEventListener('click', (event) => {
                if (!wrapper.contains(event.target)) {
                    closeDropdown();
                }
            });

            syncSelect();
        });
    };

    window.__initLabelMultiselect = init;
    document.addEventListener('DOMContentLoaded', () => init());
})();
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

