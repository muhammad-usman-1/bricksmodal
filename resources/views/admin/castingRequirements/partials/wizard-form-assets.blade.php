@php
    $isEdit = $isEdit ?? false;
@endphp
<style>
    .shoot-page { background: #f7f8fc; padding: 10px 0 22px; }
    .shoot-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .shoot-title { color: #101828; font-size: 16px; font-weight: 600; line-height: 1.4; font-size: 25px; }
    .shoot-subtitle { color: #6c7280; font-size: 12px; margin-top: 2px; }
    .shoot-back { border: 1px solid #d6d8de; background: #fff; color: #3b4150; border-radius: 8px; padding: 10px 16px; font-size: 12px; font-weight: 600; text-decoration: none !important; transition: all 0.2s ease; display: inline-flex; align-items: center; }
    .shoot-back:hover { background: #f8f9fa; color: #3b4150; border-color: #c0c4cc; text-decoration: none !important; }

    .shoot-stepper { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 24px; padding: 10px 0; }
    .stepper-node { width: 44px; height: 44px; border-radius: 50%; border: 2.5px solid #2C2C2E; background: #2C2C2E; color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 15px; position: relative; transition: all 0.3s ease; }
    .stepper-node.done { background: #2C2C2E; border-color: #2C2C2E; color: #fff; }
    .stepper-node.done span { display: none; }
    .stepper-node.done::after { content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 900; font-size: 18px; color: #fff; }
    .stepper-node.active { background: #2C2C2E; border-color: #2C2C2E; color: #fff; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
    .stepper-node:not(.active):not(.done) { background: #2C2C2E; border-color: #2C2C2E; color: #fff; opacity: 0.9; }
    .stepper-line { width: 100px; height: 2.5px; background: #2C2C2E; flex: none; border-radius: 4px; }
    .stepper-line:not(.active):not(.done) { opacity: 0.8; }

    .shoot-builder { background: transparent; padding: 0; border-radius: 0; box-shadow: none; }
    .shoot-steps { position: relative; }
    .shoot-step { display: none; animation: fadeIn .25s ease; }
    .shoot-step.active { display: block; }
    .shoot-step-card { background: #fff; border: 1px solid #e4e7ed; border-radius: 12px; box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06); padding: 14px 16px 16px; }

    .grid { display: grid; grid-gap: 16px; }
    .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    .grid-3 { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
    .grid-span-2 { grid-column: span 2; }
    .condensed { grid-gap: 12px; }

    .field-block { display: flex; flex-direction: column; gap: 6px; }
    .field-block label { font-size: 12px; color: #6d7280; margin: 0; }
    .dark-input { background: #0f0f11; border-radius: 8px; padding: 8px 10px; display: flex; align-items: center; gap: 8px; position: relative; }
    .dark-input input { background: transparent; border: none; color: #f7f7f7; width: 100%; font-size: 12px; padding: 4px 0; outline: none; }
    .dark-input input::placeholder { color: #a8adb5; }
    .dark-input.has-icon .input-icon { color: #a8adb5; font-size: 12px; display: inline-flex; align-items: center; justify-content: center; }
    .dark-input.has-pill { padding-right: 42px; }
    .input-pill { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 28px; height: 28px; border-radius: 999px; background: #0f9f4f; color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 12px; }
    .dark-input.has-suffix { padding-right: 48px; }
    .input-suffix { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #a8adb5; font-size: 11px; }

    .step2-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .add-model-btn { background: #0f1014; color: #fff; border: none; border-radius: 8px; padding: 10px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 10px 20px rgba(0,0,0,0.12); }

    .model-spec-card { background: #fff; border: 1px solid #e4e7ed; border-radius: 12px; padding: 14px 16px; box-shadow: 0 16px 32px rgba(15, 23, 42, 0.06); margin-bottom: 12px; }
    .model-card-head { display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px; }
    .model-name { font-weight: 600; color: #0f1524; font-size: 13px; }
    .model-actions { display: inline-flex; align-items: center; gap: 8px; }
    .icon-btn { width: 26px; height: 26px; border-radius: 6px; border: 1px solid #e1e3e8; background: #fff; color: #5f6470; display: grid; place-items: center; font-size: 12px; padding: 0; }
    .icon-btn.danger { color: #c53030; }
    .icon-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    .pill-select, .pill-input { width: 100%; background: #f7f8fb; border: 1px solid #e3e6ec; border-radius: 6px; padding: 10px 12px; font-size: 12px; color: #4c5160; outline: none; }
    .pill-select:focus, .pill-input:focus { border-color: #0f1014; box-shadow: 0 0 0 3px rgba(15,16,20,0.08); }

    .swatch-row { display: inline-flex; align-items: center; gap: 8px; }
    .swatch { width: 34px; height: 18px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.25); cursor: pointer; }
    .swatch.active { outline: 2px solid #0f1014; outline-offset: 2px; }

    .dropbox { width: 100%; border: 1px solid #e3e6ec; border-radius: 10px; padding: 18px; display: block; background: #f9fafc; text-align: center; cursor: pointer; color: #6d7280; }
    .dropbox-inner { display: grid; place-items: center; gap: 6px; }
    .dropbox i { color: #6d7280; font-size: 16px; }
    .drop-title { font-size: 12px; color: #3b4150; font-weight: 600; }
    .drop-sub { font-size: 11px; color: #8a8f9b; }

    .model-card { border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; background: #fff; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06); }

    .shoot-builder__footer { margin-top: 24px; display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #eef0f5; }
    .footer-back { border: 1.5px solid #d6d8de; background: #fff; color: #3b4150; border-radius: 10px; padding: 10px 20px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; }
    .footer-back:hover:not(:disabled) { background: #f8f9fa; border-color: #c0c4cc; }
    .footer-back:disabled { opacity: 0.5; cursor: not-allowed; }
    .footer-actions { display: inline-flex; align-items: center; gap: 10px; }
    .step-status { font-weight: 600; color: #9aa0ac; font-size: 12px; }
    .footer-next, .footer-submit { background: #0f1014; color: #fff; border: none; border-radius: 6px; padding: 10px 16px; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(6px); }
        to { opacity: 1; transform: translateY(0); }
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
    .form-group .datetime { width: 100% !important; }

    /* Prevent autofill and focus from tinting the dark inputs */
    .dark-input input,
    .dark-input input:-webkit-autofill,
    .dark-input input:-webkit-autofill:hover,
    .dark-input input:-webkit-autofill:focus { background: transparent !important; -webkit-text-fill-color: #f7f7f7; box-shadow: 0 0 0px 1000px #0f0f11 inset !important; caret-color: #f7f7f7; }

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
    /* Outfit Selection UI enhancements */
    .outfit-section-container { margin-top: 24px; padding-top: 20px; border-top: 1px solid #EAECF0; }
    .outfit-section-title { font-size: 15px; font-weight: 600; color: #101828; margin-bottom: 2px; }
    .outfit-section-subtitle { font-size: 13px; color: #667085; margin-bottom: 18px; }

    .outfit-selection-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; }

    .outfit-type-card { background: #fff; border: 1px solid #EAECF0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 16px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05); }

    .outfit-type-header { display: flex; align-items: center; gap: 10px; color: #344054; font-weight: 600; font-size: 14px; margin-bottom: 8px; }
    .outfit-type-header i { color: #475467; font-size: 14px; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #F9FAFB; border-radius: 6px; border: 1px solid #EAECF0; }

    .traditional-dress-btn { width: 100%; padding: 12px; background: #fff; border: 1px solid #EAECF0; border-radius: 8px; color: #475467; font-size: 13px; text-align: center; cursor: pointer; transition: all 0.2s; margin-bottom: 4px; }
    .traditional-dress-btn:hover { background: #F9FAFB; border-color: #D0D5DD; }

    .outfit-item-group { display: flex; flex-direction: column; gap: 10px; }
    .outfit-sub-item { display: flex; align-items: flex-start; gap: 14px; background: #F9FAFB; padding: 14px; border-radius: 12px; border: 1px solid #F2F4F7; }

    .item-image-box { width: 110px; height: 110px; background: #F2F4F7; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #667085; text-align: center; padding: 10px; flex-shrink: 0; line-height: 1.4; }
    .item-image-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }

    .item-details { flex: 1; display: flex; flex-direction: column; gap: 10px; align-self: center; }
    .item-label { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase; letter-spacing: 0.8px; }
    .item-label i { font-size: 13px; color: #98A2B3; }

    .item-select { width: 100%; background: #fff; border: 1px solid #D0D5DD; border-radius: 8px; padding: 10px 32px 10px 14px; font-size: 13px; color: #101828; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%23667085" d="M5 6L0 0h10z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 10px 6px; }
    .item-select:focus { border-color: #98A2B3; outline: none; }

    @media (max-width: 992px) {
        .outfit-selection-grid { grid-template-columns: 1fr; }
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

        const nodes = document.querySelectorAll('[data-stepper-node]');
        const lines = document.querySelectorAll('[data-stepper-line]');
        nodes.forEach(node => {
            const step = parseInt(node.dataset.stepperNode, 10);
            node.classList.toggle('active', step === index + 1);
            node.classList.toggle('done', step < index + 1);
        });
        lines.forEach(line => {
            const step = parseInt(line.dataset.stepperLine, 10);
            const complete = index + 1 > step;
            line.classList.toggle('active', complete);
            line.classList.toggle('done', complete);
        });
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

const initModelCard = (scope) => {
    scope.querySelectorAll('[data-swatch-group]').forEach(group => {
        if (group.dataset.swatchBound === 'true') return;
        group.dataset.swatchBound = 'true';
        const hidden = group.parentElement.querySelector('[data-swatch-input]');
        const setActive = (value) => {
            group.querySelectorAll('.swatch').forEach(b => {
                b.classList.toggle('active', b.dataset.swatchValue === value);
            });
        };
        group.querySelectorAll('[data-swatch-value]').forEach(btn => {
            btn.addEventListener('click', () => {
                const value = btn.dataset.swatchValue || '';
                setActive(value);
                if (hidden) hidden.value = value;
            });
        });
        if (hidden && hidden.value) {
            setActive(hidden.value);
        }
    });

    scope.querySelectorAll('[data-file-drop]').forEach(drop => {
        if (drop.dataset.fileBound === 'true') return;
        drop.dataset.fileBound = 'true';
        const input = drop.querySelector('[data-file-input]');
        const label = drop.querySelector('[data-file-label]');
        drop.addEventListener('click', (event) => {
            if (event.target === input) return;
            input?.click();
        });
        input?.addEventListener('change', () => {
            const names = Array.from(input.files || []).map(f => f.name).join(', ');
            if (label) {
                label.textContent = names || 'Upload Reference Photos';
            }
        });
    });

    // Initialize outfit image previews
    function updateOutfitImage(selectElement) {
        const outfitId = $(selectElement).val();
        const container = $(selectElement).closest('.outfit-sub-item');
        const target = container.find('[data-image-target]');
        const isTop = container.find('.item-label').text().includes('TOP');

        if (outfitId && window.OUTFIT_IMAGE_MAP && window.OUTFIT_IMAGE_MAP[outfitId]) {
            const imageUrl = window.OUTFIT_IMAGE_MAP[outfitId];
            target.html(`<img src="${imageUrl}" style="width: 100%; height: 100%; object-fit: contain;" alt="Outfit">`);
        } else {
            target.html(isTop ? 'Image for tops' : 'Image for bottoms');
        }
    }

    $(scope).find('[data-outfit-select]').each(function() {
        if ($(this).val()) {
            updateOutfitImage(this);
        }
    });

    $(scope).on('change', '[data-outfit-select]', function() {
        updateOutfitImage(this);
    });

    // Initialize custom label multiselects if the init function is available
    if (window.__initLabelMultiselect) {
        window.__initLabelMultiselect(scope);
    }
};

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
        const displayIndex = index + 1;
        wrapper.innerHTML = template.innerHTML
            .replace(/__INDEX__/g, index)
            .replace(/__INDEX_DISPLAY__/g, displayIndex)
            .trim();
        return wrapper.firstElementChild;
    };

    addModelBtn.addEventListener('click', () => {
        const card = createModelCard(nextIndex);
        modelsContainer.appendChild(card);
        nextIndex++;
        initModelCard(card);
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

    modelsContainer.addEventListener('click', (event) => {
        const duplicateTrigger = event.target.closest('[data-duplicate-model]');
        if (!duplicateTrigger) {
            return;
        }
        const sourceCard = duplicateTrigger.closest('[data-model-card]');
        const newIndex = nextIndex;
        const card = createModelCard(newIndex);
        const sourceInputs = sourceCard.querySelectorAll('select, input[type="text"], input[type="hidden"], input[type="file"]');
        sourceInputs.forEach((input) => {
            const name = input.getAttribute('name');
            if (!name) return;
            const match = name.match(/models\[(\d+)\]\[(.+)]/);
            if (!match) return;
            const field = match[2];
            const selector = `[name="models[${newIndex}][${field}]"]`;
            const targets = card.querySelectorAll(selector);
            
            if (targets.length > 0) {
                if (input.tagName === 'SELECT' && input.multiple) {
                    const selectedValues = Array.from(input.selectedOptions).map(opt => opt.value);
                    const targetSelect = targets[0];
                    Array.from(targetSelect.options).forEach(option => {
                        option.selected = selectedValues.includes(option.value);
                    });
                } else if (targets.length === 1) {
                    const target = targets[0];
                    target.value = input.value;
                    if (target.tagName === 'SELECT') {
                        Array.from(target.options).forEach(option => {
                            option.selected = option.value === input.value;
                        });
                    }
                }
            }
        });
        modelsContainer.appendChild(card);
        nextIndex++;
        initModelCard(card);
    });

    initModelCard(document);
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

