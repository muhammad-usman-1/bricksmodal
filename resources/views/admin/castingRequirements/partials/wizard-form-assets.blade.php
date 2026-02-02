@php
    $isEdit = $isEdit ?? false;
@endphp
@once
@push('styles')
<style>
    .shoot-page {  padding: 10px 0 22px; }
    .shoot-header { padding-right:44px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .shoot-title1 { color: #101828; font-size: 24px; font-weight: 400; line-height: 30px; }
    .shoot-title { color: #101828; font-size: 20px; font-weight: 400; line-height: 30px; }
    .shoot-subtitle { color: #6c7280; font-size: 12px; margin-top: 2px; }
    .shoot-back { border: 1px solid #d6d8de; background: #fff; color: #3b4150; border-radius: 8px; padding: 10px 16px; font-size: 12px; font-weight: 600; text-decoration: none !important; transition: all 0.2s ease; display: inline-flex; align-items: center; }
    .shoot-back:hover { background: #f8f9fa; color: #3b4150; border-color: #c0c4cc; text-decoration: none !important; }

    .shoot-stepper { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 24px; padding: 10px 0; }
    .stepper-node { width: 44px; height: 44px; border-radius: 50%; border: 2.5px solid #e9ecef; background: #e9ecef; color: #495057; display: grid; place-items: center; font-weight: 700; font-size: 15px; position: relative; transition: all 0.3s ease; }
    .stepper-node.done { background: #0f1014; border-color: #0f1014; color: #fff; }
    .stepper-node.done span { display: none; }
    .stepper-node.done::after { content: "\f00c"; font-family: "Font Awesome 5 Free"; font-weight: 900; font-size: 18px; color: #fff; }
    .stepper-node.active { background: #0f1014; border-color: #0f1014; color: #fff; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
    .stepper-node:not(.active):not(.done) { background: #e9ecef; border-color: #e9ecef; color: #495057; opacity: 1; }
    .stepper-line { width: 100px; height: 2.5px; background: #e9ecef; flex: none; border-radius: 4px; }
    .stepper-line.active, .stepper-line.done { background: #0f1014; }

    .shoot-builder { background: transparent; padding: 0; border-radius: 0; box-shadow: none; }
    .shoot-steps { position: relative; }
    .shoot-step { display: none; animation: fadeIn .25s ease; }
    .shoot-step.active { display: block; }
    .shoot-step-card { background: #fff; border: 1px solid #e4e7ed; border-radius: 12px;  padding: 14px 16px 16px; margin: 0 40px; }

    .grid { display: grid; grid-gap: 16px; }
    .grid-2 { grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); }
    .grid-3 { grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); }
    .grid-span-2 { grid-column: span 2; }
    .condensed { grid-gap: 12px; }

    .field-block { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .field-block label { font-size: 13px; color: #475467; font-weight: 600; margin-bottom: 4px; }
    .shoot-page label { font-weight: 600; }
    .dark-input { background: #fff; border: 1px solid #EAECF0; border-radius: 8px; padding: 10px 14px; display: flex; align-items: center; gap: 8px; position: relative; transition: all 0.2s; }
    .dark-input:focus-within { border-color: #0f1014; box-shadow: 0 0 0 3px rgba(15,16,20,0.08); }
    .dark-input.has-textarea { align-items: flex-start; padding: 10px; }
    .dark-input input { background: transparent; border: none !important; outline: none !important; box-shadow: none !important; color: #101828; width: 100%; font-size: 14px; padding: 0; }
    .dark-input input::placeholder { color: #98A2B3; }
    .dark-input textarea { background: transparent; border: none !important; outline: none !important; box-shadow: none !important; color: #101828; width: 100%; font-size: 14px; padding: 0; resize: vertical; min-height: 80px; font-family: inherit; }
    .dark-input textarea::placeholder { color: #98A2B3; }
    .dark-input.has-icon .input-icon { color: #98A2B3; font-size: 14px; display: inline-flex; align-items: center; justify-content: center; }
    .dark-input.has-pill { padding-right: 42px; }
    .input-pill { position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 28px; height: 28px; border-radius: 999px; background: #0f9f4f; color: #fff; display: grid; place-items: center; font-weight: 700; font-size: 12px; }
    .dark-input.has-suffix { padding-right: 48px; }
    .input-suffix { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); color: #667085; font-size: 11px; }

    .duration-input { display: flex; align-items: center; gap: 8px; width: 100%; }
    .duration-value { width: auto; flex: 1; min-width: 60px; text-align: center; color: #101828; background: #fff; border: 1px solid #EAECF0; border-radius: 8px; font-weight: 600; font-size: 14px; padding: 10px 0; -moz-appearance: textfield; }
    .duration-value::-webkit-outer-spin-button,
    .duration-value::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    .duration-value.is-invalid { border-color: #dc3545; box-shadow: 0 0 0 1px rgba(220, 53, 69, 0.25); }
    .duration-unit { background: #fff; border: 1px solid #EAECF0; border-radius: 8px; padding: 10px 16px; color: #98A2B3; font-size: 14px; font-weight: 500; min-width: 80px; text-align: center; }

    /* Step 2 Overrides */
    .duration-value.step2-style { background: #f7f8fb; border: 1px solid #e3e6ec; border-radius: 6px; font-size: 12px; color: #4c5160; font-weight: 500; }
    .duration-unit.step2-style { background: #f7f8fb; border: 1px solid #e3e6ec; border-radius: 6px; font-size: 12px; color: #4c5160; min-width: 70px; }

    .step2-head {padding-right: 44px; margin-left: 40px;display: flex; justify-content: space-between; align-items: center; margin-bottom: 12px; }
    .add-model-btn { background: #0f1014; color: #fff; border: none; border-radius: 8px; padding: 10px 14px; font-size: 12px; display: inline-flex; align-items: center; gap: 8px; box-shadow: 0 10px 20px rgba(0,0,0,0.12); }

    .model-spec-card {margin-left: 40px; margin-right: 40px; background: #fff; border: 1px solid #e4e7ed; border-left: 4px solid #000; border-radius: 12px; padding: 14px 16px; margin-bottom: 12px; position: relative; }
    .model-name { margin-bottom: 15px; font-weight: 600; color: #0f1524; font-size: 13px; text-transform: capitalize; }
    .model-actions { position: absolute; top: 14px; right: 16px; display: inline-flex; align-items: center; gap: 8px; }
    .icon-btn { width: 26px; height: 26px; border-radius: 6px; border: 1px solid #e1e3e8; background: #fff; color: #5f6470; display: grid; place-items: center; font-size: 12px; padding: 0; }
    .icon-btn.danger { color: #c53030; }
    .icon-btn:disabled { opacity: 0.4; cursor: not-allowed; }

    .pill-select, .pill-input { width: 100%; background: #fff; border: 1px solid #e3e6ec; border-radius: 6px; padding: 10px 12px; font-size: 12px; color: #4c5160; outline: none; }
    .pill-select:focus, .pill-input:focus { border-color: #0f1014; box-shadow: 0 0 0 3px rgba(15,16,20,0.08); }
    .pill-input::placeholder,
    .duration-value::placeholder {
        color: #9aa0ac;
    }

    .swatch-row { display: inline-flex; align-items: center; gap: 8px; }
    .swatch { width: 34px; height: 18px; border-radius: 6px; border: 1px solid rgba(0,0,0,0.1); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.25); cursor: pointer; display: inline-flex; align-items: center; justify-content: center; }
    .swatch.active { outline: 2px solid #0f1014; outline-offset: 2px; }
    .swatch-any { background: transparent !important; border: none !important; box-shadow: none !important; color: #0f1014; font-weight: 700; font-size: 14px; }
    .swatch-any .phi-icon { font-size: 14px; line-height: 1; opacity: 0.9; display: inline-flex; align-items: center; justify-content: center; width: 100%; }

    .rate-options { display: flex; gap: 150px; align-items: center; flex-wrap: wrap; }
    .rate-option { display: inline-flex; align-items: center; gap: 6px; font-size: 13px; color: #475467; font-weight: 600; }

    .dropbox { width: 100%; border: 1px solid #e3e6ec; border-radius: 10px; padding: 18px; display: block; background: #fff; text-align: center; cursor: pointer; color: #6d7280; }
    .dropbox-inner { display: grid; place-items: center; gap: 6px; }
    .dropbox i { color: #6d7280; font-size: 16px; }
    .drop-title { font-size: 12px; color: #3b4150; font-weight: 600; }
    .drop-sub { font-size: 11px; color: #8a8f9b; }
    .dropbox.is-dragover { border-color: #0f1014; background: #eef2ff; }

    .reference-upload { margin-top: 10px; padding: 14px; border: 1px dashed #e5e7eb; border-radius: 10px; background: #fdfdfd; }
    .reference-upload-title { font-size: 13px; font-weight: 700; color: #101828; margin-bottom: 4px; }
    .reference-upload-sub { font-size: 12px; color: #667085; margin-bottom: 10px; }
    .reference-preview-grid { margin-top: 12px; display: grid; grid-template-columns: repeat(auto-fill, minmax(90px, 1fr)); gap: 10px; }
    .reference-preview-item { border: 1px solid #e4e7ed; border-radius: 8px; overflow: hidden; background: #fff; height: 96px; display: flex; align-items: center; justify-content: center; }
    .reference-preview-item img { width: 100%; height: 100%; object-fit: cover; }

    .model-card {  border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; background: #fff; box-shadow: 0 12px 24px rgba(15, 23, 42, 0.06); }

    .shoot-builder__footer {padding-right:44px; margin-top: 24px; display: flex; justify-content: space-between; align-items: center; padding-top: 16px; border-top: 1px solid #eef0f5; }
    .footer-back { border: 1.5px solid #d6d8de; background: #fff; color: #3b4150; border-radius: 10px; padding: 10px 20px; font-size: 13px; font-weight: 600; display: inline-flex; align-items: center; gap: 8px; cursor: pointer; transition: all 0.2s ease; }
    .footer-back:hover:not(:disabled) { background: #fff; border-color: #d6d8de; color: #3b4150; }
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

    /* Prevent autofill and focus from tinting the inputs */
    .dark-input input,
    .dark-input input:-webkit-autofill,
    .dark-input input:-webkit-autofill:hover,
    .dark-input input:-webkit-autofill:focus { background: transparent !important; -webkit-text-fill-color: #101828; box-shadow: 0 0 0px 1000px #fff inset !important; caret-color: #101828; }
    .dark-input textarea { caret-color: #101828; }

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

    .outfit-selection-grid { display: flex; flex-direction: column; gap: 20px; }

    .outfit-type-card { background: #fff; border: 1px solid #EAECF0; border-radius: 12px; padding: 16px; display: flex; flex-direction: column; gap: 16px; transition: all 0.2s; box-shadow: 0 1px 3px rgba(16, 24, 40, 0.05); }
    .outfit-type-card[data-outfit-gender] { display: flex; }

    .outfit-type-header { display: flex; align-items: center; gap: 10px; color: #344054; font-weight: 600; font-size: 14px; margin-bottom: 12px; }
    .outfit-type-header i { color: #475467; font-size: 14px; display: flex; align-items: center; justify-content: center; width: 28px; height: 28px; background: #F9FAFB; border-radius: 6px; border: 1px solid #EAECF0; }
    .traditional-outfit-btn { background: #0f1014; border: 1px solid #0f1014; border-radius: 6px; padding: 6px 12px; font-size: 12px; font-weight: 600; color: #fff; cursor: pointer; transition: all 0.2s; margin-left: auto; }
    .traditional-outfit-btn:hover { background: #0f1014; border-color: #0f1014; opacity: 0.9; }

    .traditional-dress-btn { width: 100%; padding: 12px; background: #fff; border: 1px solid #EAECF0; border-radius: 8px; color: #475467; font-size: 13px; text-align: center; cursor: pointer; transition: all 0.2s; margin-bottom: 4px; }
    .traditional-dress-btn:hover { background: #F9FAFB; border-color: #D0D5DD; }

    /* Male outfits: row layout with 2 equal sections by default (like female) */
    .outfit-type-card[data-outfit-gender="male"] .outfit-item-group {
        display: flex;
        flex-direction: row;
        gap: 12px;
    }
    .outfit-type-card[data-outfit-gender="male"] .outfit-sub-item {
        flex: 1;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 14px;
        background: #fff;
        padding: 14px;
        border-radius: 12px;
        border: 1px solid #F2F4F7;
    }
    /* When traditional mode is active, show only traditional */
    .outfit-type-card[data-outfit-gender="male"][data-traditional-mode="true"] .outfit-sub-item[data-outfit-type="traditional"] {
        display: flex !important;
    }
    .outfit-type-card[data-outfit-gender="male"][data-traditional-mode="true"] .outfit-sub-item[data-outfit-type="top"],
    .outfit-type-card[data-outfit-gender="male"][data-traditional-mode="true"] .outfit-sub-item[data-outfit-type="bottom"] {
        display: none !important;
    }
    /* By default, hide traditional and show top/bottom */
    .outfit-type-card[data-outfit-gender="male"] .outfit-sub-item[data-outfit-type="traditional"] {
        display: none;
    }
    .outfit-type-card[data-outfit-gender="male"] .outfit-sub-item[data-outfit-type="top"],
    .outfit-type-card[data-outfit-gender="male"] .outfit-sub-item[data-outfit-type="bottom"] {
        display: flex;
    }

    /* Female outfits: row layout with 2 equal sections */
    .outfit-type-card[data-outfit-gender="female"] .outfit-item-group {
        display: flex;
        flex-direction: row;
        gap: 12px;
    }
    .outfit-type-card[data-outfit-gender="female"] .outfit-sub-item {
        flex: 1;
        display: flex;
        flex-direction: row;
        align-items: flex-start;
        gap: 14px;
        background: #F9FAFB;
        padding: 14px;
        border-radius: 12px;
        border: 1px solid #F2F4F7;
    }

    /* Default for other cases */
    .outfit-item-group { display: flex; flex-direction: column; gap: 10px; }
    .outfit-sub-item { display: flex; align-items: flex-start; gap: 14px; background: #F9FAFB; padding: 14px; border-radius: 12px; border: 1px solid #F2F4F7; }

    /* Image box for row layout - fixed width, side by side with details */
    .outfit-type-card[data-outfit-gender] .item-image-box {
        width: 110px;
        height: 110px;
        background: #F2F4F7;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        color: #667085;
        text-align: center;
        padding: 10px;
        flex-shrink: 0;
        line-height: 1.4;
    }
    .outfit-type-card[data-outfit-gender] .item-image-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 8px;
    }

    /* Default image box for column layout */
    .item-image-box { width: 110px; height: 110px; background: #F2F4F7; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #667085; text-align: center; padding: 10px; flex-shrink: 0; line-height: 1.4; }
    .item-image-box img { width: 100%; height: 100%; object-fit: cover; border-radius: 8px; }

    /* Item details for row layout - next to image */
    .outfit-type-card[data-outfit-gender] .item-details {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 10px;
        align-self: center;
    }

    /* Default item details */
    .item-details { flex: 1; display: flex; flex-direction: column; gap: 10px; align-self: center; }
    .item-label { display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: #475467; text-transform: uppercase; letter-spacing: 0.8px; }
    .item-label i { font-size: 13px; color: #98A2B3; }

    .item-select { width: 100%; background: #fff; border: 1px solid #D0D5DD; border-radius: 8px; padding: 10px 32px 10px 14px; font-size: 13px; color: #101828; appearance: none; background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%23667085" d="M5 6L0 0h10z"/></svg>'); background-repeat: no-repeat; background-position: right 12px center; background-size: 10px 6px; }
    .item-select:focus { border-color: #98A2B3; outline: none; }

    @media (max-width: 992px) {
        .outfit-type-card[data-outfit-gender] .outfit-item-group {
            flex-direction: column;
        }
        .outfit-type-card[data-outfit-gender] .outfit-sub-item {
            flex: none;
        }
    }
    /* Custom Picker Dropdown */
    .field-block { position: relative; }
    .custom-picker-dropdown {
        position: absolute;
        top: 100%;
        left: 0;
        z-index: 1000;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        margin-top: 8px;
        display: none;
        width: 320px;
        padding: 16px;
        border: 1px solid #eee;
    }
    .custom-picker-dropdown.show { display: block; }

    /* Picker Top Header */
    .picker-top-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 12px;
        margin-bottom: 12px;
        border-bottom: 1px solid #eee;
    }
    .picker-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a1a1a;
    }
    .btn-close-picker {
        background: none;
        border: none;
        color: #999;
        font-size: 18px;
        cursor: pointer;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s;
    }
    .btn-close-picker:hover {
        color: #333;
    }

    /* Calendar Styles */
    .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .calendar-header .month-year-label { font-weight: 600; color: #101828; font-size: 14px; }
    .calendar-header button { background: none; border: none; color: #667085; cursor: pointer; padding: 4px; border-radius: 4px; transition: all 0.2s; }
    .calendar-header button:hover { background: #f9fafb; color: #101828; }

    .calendar-weekdays { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; margin-bottom: 8px; }
    .calendar-weekdays span { font-size: 12px; color: #667085; font-weight: 500; }

    .calendar-days { display: grid; grid-template-columns: repeat(7, 1fr); text-align: center; }
    .calendar-day {
        height: 38px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        color: #475467;
        cursor: pointer;
        border-radius: 50%;
        transition: all 0.2s;
        margin: 2px;
    }
    .calendar-day:hover:not(.out-of-month) { background: #f4f4f5; }
    .calendar-day.selected { background: #1a1a1a; color: #fff; font-weight: 600; }
    .calendar-day.today { color: #101828; font-weight: 700; border: 1px solid #eee; }
    .calendar-day.out-of-month { color: #d0d5dd; cursor: default; }
    .calendar-day.disabled-day {
        color: #d0d5dd;
        cursor: not-allowed;
        pointer-events: none;
        background: #fafafa;
    }

    .calendar-footer { margin-top: 16px; padding-top: 12px; border-top: 1px solid #eee; display: flex; justify-content: flex-end; }
    .btn-clear-date { background: #f2f2f2; border: none; border-radius: 8px; padding: 8px 16px; font-size: 13px; font-weight: 600; color: #333; cursor: pointer; }
    .btn-clear-date:hover { background: #e8e8e8; }

    /* Time Picker Styles */
    #timeDropdown { width: 280px; padding: 16px 0; overflow: hidden; }
    #timeDropdown .picker-top-header { padding: 0 16px 12px 20px; }
    .time-list-container {
        max-height: 300px;
        overflow-y: auto;
        scrollbar-width: none; /* Firefox */
        -ms-overflow-style: none;  /* IE and Edge */
    }
    .time-list-container::-webkit-scrollbar {
        display: none; /* Chrome, Safari, Opera */
    }
    .time-item { padding: 12px 20px; font-size: 15px; color: #344054; cursor: pointer; transition: all 0.2s; }
    .time-item:hover { background: #f4f4f5; }
    .time-item.selected { background: #f4f4f5; font-weight: 600; }

    .dark-input.has-picker { cursor: pointer; }
    .dark-input.has-picker input { cursor: pointer; }
    .picker-icon { color: #98A2B3; font-size: 13px; }

    /* Validation Error Styles */
    .validation-error {
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }
    .is-invalid {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
    }
    .dark-input.is-invalid {
        border: 1px solid #dc2626;
    }
    .duration-value.is-invalid {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 1px rgba(220, 38, 69, 0.25) !important;
    }
    .pill-select.is-invalid,
    .pill-input.is-invalid {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12) !important;
    }
    /* Keep validation text below inputs (no overlay) */
    .field-block .invalid-feedback {
        position: static;
        display: block;
        margin-top: 4px;
        font-size: 12px;
        color: #dc2626;
    }
</style>
@endpush
@endonce
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
    // Navigation Guard for "Back to Shoots" button
    document.querySelectorAll('.shoot-back').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const url = this.getAttribute('href');

            if (typeof Swal === 'undefined') {
                window.location.href = url;
                return;
            }

            Swal.fire({
                title: 'Do you want to stay or go back?',
                text: "You haven't saved your shoot yet.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Go Back',
                cancelButtonText: 'Stay',
                confirmButtonColor: '#000000',
                cancelButtonColor: '#fff',
                customClass: {
                    cancelButton: 'btn btn-outline-secondary',
                    confirmButton: 'btn btn-dark'
                },
                buttonsStyling: true,
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "Form data will be lost forever!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Clear & Go Back',
                        cancelButtonText: 'Cancel',
                        confirmButtonColor: '#000000',
                        cancelButtonColor: '#fff',
                        customClass: {
                            cancelButton: 'btn btn-outline-secondary',
                            confirmButton: 'btn btn-dark'
                        },
                        buttonsStyling: true,
                        reverseButtons: true
                    }).then((secondResult) => {
                        if (secondResult.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                }
            });
        });
    });

    const steps = Array.from(document.querySelectorAll('.shoot-step'));
    const nextBtn = document.querySelector('[data-next-step]');
    const prevBtn = document.querySelector('[data-prev-step]');
    const submitBtn = document.querySelector('[data-submit-form]');
    const indicator = document.querySelector('[data-step-indicator]');
    const form = document.getElementById('shootWizard');
    const durationInput = document.getElementById('duration');

    if (!steps.length || !nextBtn || !prevBtn || !indicator) {
        return;
    }

        if (durationInput) {
            durationInput.addEventListener('input', () => {
                const cleaned = durationInput.value.replace(/[^0-9]/g, '');
                if (cleaned !== durationInput.value) {
                    durationInput.value = cleaned;
                }
                applyTimeSlotBounds();
                applyHoursMax();
            });
        }

        const shootTimeInput = document.getElementById('shoot_time');
        if (shootTimeInput) {
            shootTimeInput.addEventListener('change', () => {
                // Time slots are now handled via selects, no need for applyTimeSlotBounds here
                if (window.updateAllTimeSlotSelects) window.updateAllTimeSlotSelects();
            });
        }

    let currentStep = 0;

    // Helpers for shoot window calculations
    const parseTimeToMinutes = (timeStr) => {
        if (!timeStr) return null;
        const [h, m] = timeStr.split(':').map(Number);
        if (Number.isNaN(h) || Number.isNaN(m)) return null;
        return h * 60 + m;
    };

    const minutesToTimeString = (mins) => {
        const total = Math.max(0, Math.min(24 * 60 - 1, Math.floor(mins)));
        const h = Math.floor(total / 60) % 24;
        const m = total % 60;
        return `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
    };

    const getShootWindow = () => {
        const startInput = steps[0].querySelector('#shoot_time');
        const durationInputEl = steps[0].querySelector('#duration');
        const startVal = startInput?.value;
        const durationVal = durationInputEl?.value;
        const durationHours = durationVal ? parseFloat(durationVal) : null;
        if (!startVal || Number.isNaN(durationHours) || durationHours <= 0) return null;
        const startMins = parseTimeToMinutes(startVal);
        if (startMins === null) return null;
        const endMins = startMins + durationHours * 60;
        return { startMins, endMins };
    };


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

        // Keep Step 2 inputs constrained when moving between steps
        if (window.updateAllTimeSlotSelects) window.updateAllTimeSlotSelects();
        if (window.applyHoursMax) window.applyHoursMax();
    };

    // Helper: find the right place to render errors (below the input box, not inside it)
    const getFieldBlock = (field) => field?.closest?.('.field-block') || field?.parentElement;

    const findErrorDiv = (host, key) => {
        if (!host) return null;
        const items = Array.from(host.querySelectorAll('.validation-error, .invalid-feedback') || []);
        return items.find(el => (el.dataset?.for || '') === key || el.classList.contains('invalid-feedback')) || null;
    };

    const getFriendlyLabelForField = (field) => {
        // Prefer explicit label text from DOM
        const labelEl = field?.closest?.('.field-block')?.querySelector?.('label');
        const labelText = labelEl?.textContent?.trim();
        if (labelText) {
            // Remove any trailing '*' / "required" markers if present
            return labelText.replace(/\s*\*?\s*$/g, '').trim();
        }

        const name = field?.getAttribute?.('name') || '';

        // models[0][eye_color] -> Eye color
        const modelMatch = name.match(/^models\[\d+\]\[([^\]]+)\]$/);
        if (modelMatch) {
            const key = modelMatch[1];
            const map = {
                age_range_key: 'Model age',
                model_hours: 'Hours needed',
                rate_decision: 'Rate',
                rate: 'Rate amount',
                height_range: 'Height range',
                weight_range: 'Weight range',
                skin_color: 'Skin color',
                eye_color: 'Eye color',
                gender: 'Gender',
                labels: 'Labels',
            };
            if (map[key]) return map[key];
            return key.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
        }

        const fallback = field?.getAttribute?.('id') || 'This field';
        return fallback.replace(/_/g, ' ').replace(/\b\w/g, c => c.toUpperCase());
    };

    const requiredMessageForField = (field) => `${getFriendlyLabelForField(field)} is required.`;

    // Helper function to show validation error (below the input wrapper)
    const showFieldError = (field, message) => {
        if (!field) return;

        field.classList.add('is-invalid');
        const wrapper = field.closest?.('.dark-input');
        wrapper?.classList?.add('is-invalid');

        const host = getFieldBlock(field);
        if (!host) return;

        const key = field.getAttribute('name') || field.id || '';
        let errorDiv = findErrorDiv(host, key);
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'validation-error';
            errorDiv.dataset.for = key;
            host.appendChild(errorDiv);
        }
        errorDiv.textContent = message;
    };

    // Helper function to clear validation error
    const clearFieldError = (field) => {
        if (!field) return;

        field.classList.remove('is-invalid');
        const wrapper = field.closest?.('.dark-input');
        wrapper?.classList?.remove('is-invalid');

        const host = getFieldBlock(field);
        if (!host) return;

        const key = field.getAttribute('name') || field.id || '';
        const errorDiv = findErrorDiv(host, key);
        if (errorDiv) errorDiv.remove();
    };

    // Clear all validation errors in a step
    const clearStepErrors = (stepElement) => {
        stepElement.querySelectorAll('.is-invalid').forEach(field => {
            clearFieldError(field);
        });
    };

    const isStepValid = (index) => {
        const stepElement = steps[index];
        clearStepErrors(stepElement);
        let valid = true;
        const errors = [];

        // Step 1: Basic Information
        if (index === 0) {
            // Validate Shoot Title (required)
            const projectName = stepElement.querySelector('#project_name');
            if (projectName && projectName.hasAttribute('required')) {
                if (!projectName.value || projectName.value.trim() === '') {
                    showFieldError(projectName, 'Shoot title is required.');
                    valid = false;
                } else {
                    clearFieldError(projectName);
                }
            }

            // Validate Description (only text - letters and spaces, no numbers or special characters)
            const description = stepElement.querySelector('#description');
            if (description) {
                if (description.value && description.value.trim()) {
                    // Only allow letters (a-z, A-Z) and spaces
                    const textOnlyPattern = /^[a-zA-Z\s]+$/;

                    if (!textOnlyPattern.test(description.value.trim())) {
                        showFieldError(description, 'Description must contain only text (letters and spaces). Numbers and special characters are not allowed.');
                        valid = false;
                    } else {
                        clearFieldError(description);
                    }
                }
                // Description is optional, so we don't require it, but if it has a value, it must be text only
            }

            // Validate Shoot Date (required and cannot be earlier than today)
            const shootDate = stepElement.querySelector('#shoot_date');
            if (shootDate) {
                if (!shootDate.value || shootDate.value.trim() === '') {
                    showFieldError(shootDate, 'Shoot date is required.');
                    valid = false;
                } else {
                    const selectedDate = new Date(shootDate.value + 'T00:00:00');
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);

                    if (selectedDate < today) {
                        showFieldError(shootDate, 'Shoot date cannot be earlier than today.');
                        valid = false;
                    } else {
                        clearFieldError(shootDate);
                    }
                }
            }

            // Validate Shoot Time (required)
            const shootTime = stepElement.querySelector('#shoot_time');
            if (shootTime) {
                if (!shootTime.value || shootTime.value.trim() === '') {
                    showFieldError(shootTime, 'Start time is required.');
                    valid = false;
                } else {
                    clearFieldError(shootTime);
                }
            }

            // Validate Duration (cannot be 0 and is required)
            const duration = stepElement.querySelector('#duration');
            if (duration) {
                if (!duration.value || duration.value.trim() === '') {
                    showFieldError(duration, 'Duration is required.');
                    valid = false;
                } else {
                    const durationValue = parseFloat(duration.value);
                    if (isNaN(durationValue) || durationValue <= 0) {
                        showFieldError(duration, 'Duration must be greater than 0.');
                        valid = false;
                    } else {
                        clearFieldError(duration);
                    }
                }
            }
        }

        // Step 2: Model Specifications
        if (index === 1) {
            const modelCards = stepElement.querySelectorAll('[data-model-card]');
            if (modelCards.length === 0) {
                alert('At least one model requirement is required.');
                valid = false;
            } else {
                // Get shoot window from step 1 for validation
                const shootWindow = getShootWindow();

                modelCards.forEach((card, cardIndex) => {
                    // Validate Gender (required)
                    const genderSelect = card.querySelector('select[name*="[gender]"]');
                    if (genderSelect && genderSelect.hasAttribute('required')) {
                        if (!genderSelect.value || genderSelect.value === '') {
                            showFieldError(genderSelect, 'Gender is required.');
                            valid = false;
                        } else {
                            clearFieldError(genderSelect);
                        }
                    }

                    // Validate Age Range (required)
                    const ageRangeSelect = card.querySelector('select[name*="[age_range_key]"]');
                    if (ageRangeSelect && ageRangeSelect.hasAttribute('required')) {
                        if (!ageRangeSelect.value || ageRangeSelect.value === '') {
                            showFieldError(ageRangeSelect, 'Age range is required.');
                            valid = false;
                        } else {
                            clearFieldError(ageRangeSelect);
                        }
                    }

                    // Validate Time Slot (must be within shoot window)
                    const timeSlotSelect = card.querySelector('.time-slot-select');
                    if (timeSlotSelect) {
                        const timeSlotValue = timeSlotSelect.value;
                        if (!timeSlotValue || timeSlotValue.trim() === '') {
                            showFieldError(timeSlotSelect, 'Time slot is required.');
                            valid = false;
                        } else {
                            clearFieldError(timeSlotSelect);
                        }
                    }

                    // Validate Hours Needed (must not exceed shoot duration from step 1)
                    const hoursNeededInput = card.querySelector('input[name*="[model_hours]"]');
                    const durationFromStep1 = steps[0].querySelector('#duration');
                    if (hoursNeededInput) {
                        const hoursValue = hoursNeededInput.value;
                        if (!hoursValue || hoursValue.trim() === '') {
                            showFieldError(hoursNeededInput, 'Hours needed is required.');
                            valid = false;
                        } else if (!durationFromStep1 || !durationFromStep1.value) {
                            showFieldError(hoursNeededInput, 'Please set the shoot duration in step 1 first.');
                            valid = false;
                        } else {
                            const hoursNeeded = parseFloat(hoursValue);
                            const shootDuration = parseFloat(durationFromStep1.value);

                            if (isNaN(hoursNeeded) || hoursNeeded <= 0) {
                                showFieldError(hoursNeededInput, 'Hours needed must be a valid number greater than 0.');
                                valid = false;
                            } else if (hoursNeeded > shootDuration) {
                                showFieldError(hoursNeededInput, `Hours needed (${hoursNeeded}h) cannot exceed shoot duration (${shootDuration}h).`);
                                valid = false;
                            } else {
                                clearFieldError(hoursNeededInput);
                            }
                        }
                    }

                    const rateGroup = card.querySelector('[data-rate-choice-group]');
                    const rateInput = card.querySelector('[data-rate-input]');
                    if (rateGroup && rateInput) {
                        const selected = rateGroup.querySelector('input[type="radio"]:checked');
                        const decision = selected?.value || '';
                        if (decision === 'admin_decide') {
                            const numericRate = parseFloat(rateInput.value);
                            if (rateInput.value === '' || Number.isNaN(numericRate) || numericRate < 0) {
                                showFieldError(rateInput, 'Enter a valid rate for predefined models.');
                                valid = false;
                            } else {
                                clearFieldError(rateInput);
                            }
                        } else {
                            rateInput.value = 0;
                            clearFieldError(rateInput);
                        }
                    }
                });
            }
        }

        // Step 3: Notes & References (optional validations if needed)
        if (index === 2) {
            // Step 3 is mostly optional, but we can add validations if needed
            // For now, no specific validations required
        }

        // General required field validation for all steps
        const stepFields = stepElement.querySelectorAll('input[required], select[required], textarea[required]');
        stepFields.forEach(field => {
            if (!field.value || (field.type === 'text' && field.value.trim() === '')) {
                if (!field.classList.contains('is-invalid')) {
                    showFieldError(field, requiredMessageForField(field));
                }
                valid = false;
            } else {
                clearFieldError(field);
            }
        });

        if (!valid) {
            // Scroll to first error
            const firstError = stepElement.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstError.focus();
            }
        }

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

        // If "any" is selected for swatches, drop the field so it persists as null server-side
        form.querySelectorAll('[data-swatch-input]').forEach(input => {
            if (!input.value) {
                input.disabled = true;
            }
        });
    }, { once: true });

    showStep(currentStep);

    // Clear validation errors when user types/changes values
    form.addEventListener('input', function(e) {
        clearFieldError(e.target);

        // Real-time validation for description field - only allow letters and spaces
        if (e.target.id === 'description') {
            const textOnlyPattern = /^[a-zA-Z\s]*$/;
            let value = e.target.value;

            // Remove any characters that are not letters or spaces
            if (!textOnlyPattern.test(value)) {
                // Filter out non-letter, non-space characters
                const cleaned = value.replace(/[^a-zA-Z\s]/g, '');
                e.target.value = cleaned;

                // Show error if invalid characters were removed
                if (cleaned.length < originalLength) {
                    showFieldError(e.target, 'Description must contain only text (letters and spaces). Numbers and special characters are not allowed.');
                }
            } else {
                clearFieldError(e.target);
            }
        }
    });

    form.addEventListener('change', function(e) {
        clearFieldError(e.target);

        // Real-time validation for time slot fields (must stay within shoot window)
        if (e.target.classList.contains('time-slot-select')) {
            // Dropdown selection is generally safe, but we can clear error if it was there
            clearFieldError(e.target);
        }

        // Real-time enforcement for Hours Needed
        if (e.target.name && e.target.name.includes('[model_hours]')) {
            const durationInputEl = steps[0].querySelector('#duration');
            const durationVal = durationInputEl?.value;
            const durationHours = durationVal ? parseFloat(durationVal) : null;
            const hoursVal = e.target.value ? parseFloat(e.target.value) : null;
            if (!Number.isNaN(durationHours) && durationHours > 0 && !Number.isNaN(hoursVal)) {
                if (hoursVal > durationHours) {
                    showFieldError(e.target, `Hours needed (${hoursVal}h) cannot exceed shoot duration (${durationHours}h).`);
                } else if (hoursVal <= 0) {
                    showFieldError(e.target, 'Hours needed must be greater than 0.');
                } else {
                    clearFieldError(e.target);
                }
            }
        }
    });

    // Prevent typing invalid characters in description field (only letters and spaces)
    const descriptionField = document.getElementById('description');
    if (descriptionField) {
        descriptionField.addEventListener('keypress', function(e) {
            // Allow: letters (a-z, A-Z), space, backspace, delete, tab, arrow keys
            const char = String.fromCharCode(e.which || e.keyCode);
            const textOnlyPattern = /^[a-zA-Z\s]$/;

            // Allow control keys (backspace, delete, tab, arrow keys, etc.)
            if (e.which === 0 || e.which === 8 || e.which === 9 || e.which === 46 ||
                (e.which >= 35 && e.which <= 40)) {
                return true;
            }

            // Block if not a letter or space
            if (!textOnlyPattern.test(char)) {
                e.preventDefault();
                showFieldError(this, 'Description must contain only text (letters and spaces). Numbers and special characters are not allowed.');
                return false;
            }
        });

        // Prevent paste of invalid characters in description field
        descriptionField.addEventListener('paste', function(e) {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text');
            const cleaned = pastedText.replace(/[^a-zA-Z\s]/g, '');

            // Insert cleaned text at cursor position
            const start = this.selectionStart;
            const end = this.selectionEnd;
            const currentValue = this.value;
            this.value = currentValue.substring(0, start) + cleaned + currentValue.substring(end);

            // Set cursor position
            this.setSelectionRange(start + cleaned.length, start + cleaned.length);

            // Show error if some characters were removed
            if (cleaned.length < pastedText.length) {
                showFieldError(this, 'Description must contain only text (letters and spaces). Numbers and special characters are not allowed.');
            } else {
                clearFieldError(this);
            }
        });
    }
});

const initModelCard = (scope) => {
    scope.querySelectorAll('[data-swatch-group]').forEach(group => {
        if (group.dataset.swatchBound === 'true') return;
        group.dataset.swatchBound = 'true';
        const hidden = group.parentElement.querySelector('[data-swatch-input]');
        const customBtn = group.querySelector('[data-color-picker]');
        const colorInput = group.querySelector('[data-color-input]');
        const normalize = (val) => (val || '').toLowerCase();
        const setActive = (value) => {
            group.querySelectorAll('.swatch').forEach(b => {
                b.classList.toggle('active', normalize(b.dataset.swatchValue) === normalize(value));
            });
        };
        const applyCustomColor = (value) => {
            if (!customBtn) return;
            const colorVal = value || '#c48b5a';
            customBtn.dataset.swatchValue = colorVal;
            customBtn.style.background = colorVal;
            customBtn.classList.add('custom');
        };
        if (customBtn && colorInput) {
            customBtn.addEventListener('click', () => colorInput.click());
            colorInput.addEventListener('change', () => {
                if (!colorInput.value) return;
                applyCustomColor(colorInput.value);
                setActive(colorInput.value);
                if (hidden) hidden.value = colorInput.value;
            });
        }
        group.querySelectorAll('[data-swatch-value]').forEach(btn => {
            btn.addEventListener('click', () => {
                const value = btn.dataset.swatchValue || '';
                setActive(value);
                if (hidden) hidden.value = value;
            });
        });
        if (hidden && hidden.value) {
            if (hidden.value.startsWith('#')) {
                applyCustomColor(hidden.value);
            }
            setActive(hidden.value);
        }
    });

    scope.querySelectorAll('[data-rate-choice-group]').forEach(group => {
        if (group.dataset.rateBound === 'true') return;
        group.dataset.rateBound = 'true';

        const container = group.closest('[data-rate-container]');
        const wrapper = container?.querySelector('[data-rate-input-wrapper]');
        const input = container?.querySelector('[data-rate-input]');
        const radios = Array.from(group.querySelectorAll('input[type="radio"]'));

        const syncRateFields = () => {
            if (!wrapper || !input) return;
            const selected = radios.find(r => r.checked);
            const show = selected?.value === 'admin_decide';
            wrapper.style.display = show ? '' : 'none';
            if (show) {
                input.removeAttribute('disabled');
                input.setAttribute('required', 'required');
            } else {
                input.setAttribute('disabled', 'disabled');
                input.removeAttribute('required');
            }
        };

        radios.forEach(radio => radio.addEventListener('change', syncRateFields));
        syncRateFields();
    });

    scope.querySelectorAll('[data-file-drop]').forEach(drop => {
        if (drop.dataset.fileBound === 'true') return;
        drop.dataset.fileBound = 'true';
        const input = drop.querySelector('[data-file-input]');
        const label = drop.querySelector('[data-file-label]');

        const renderPreviews = (files) => {
            const names = files.map(f => f.name).join(', ');
            if (label) {
                label.textContent = files.length ? `${files.length} file(s) selected` : 'Upload Reference Photos';
            }

            const referenceBlock = drop.closest('[data-reference-block]');
            if (!referenceBlock) return;

            let previewGrid = referenceBlock.querySelector('.reference-preview-grid');
            if (!previewGrid) {
                previewGrid = document.createElement('div');
                previewGrid.className = 'reference-preview-grid';
                // Place previews right after the dropbox so they are clearly visible
                drop.insertAdjacentElement('afterend', previewGrid);
            }

            previewGrid.innerHTML = '';
            files.forEach(file => {
                if (!file || !(file instanceof File)) return;
                if (file.type && !file.type.startsWith('image/')) return;
                const url = URL.createObjectURL(file);
                const item = document.createElement('div');
                item.className = 'reference-preview-item';
                const img = document.createElement('img');
                img.src = url;
                img.alt = 'Reference photo';
                img.onload = () => URL.revokeObjectURL(url);
                item.appendChild(img);
                previewGrid.appendChild(item);
            });
        };
        drop.addEventListener('click', (event) => {
            if (event.target === input) return;
            input?.click();
        });
        input?.addEventListener('change', () => {
            const files = Array.from(input.files || []);
            renderPreviews(files);
        });

        // Drag & Drop support
        ;['dragenter', 'dragover'].forEach(evt => {
            drop.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                drop.classList.add('is-dragover');
            });
        });
        ;['dragleave', 'dragend'].forEach(evt => {
            drop.addEventListener(evt, (e) => {
                e.preventDefault();
                e.stopPropagation();
                drop.classList.remove('is-dragover');
            });
        });
        drop.addEventListener('drop', (e) => {
            e.preventDefault();
            e.stopPropagation();
            drop.classList.remove('is-dragover');
            const dropped = Array.from(e.dataTransfer?.files || []).filter(f => f && f.type && f.type.startsWith('image/'));
            if (!dropped.length) return;

            if (input && typeof DataTransfer !== 'undefined') {
                const dt = new DataTransfer();
                dropped.forEach(f => dt.items.add(f));
                input.files = dt.files;
                input.dispatchEvent(new Event('change'));
            } else {
                // Fallback: render previews without altering input
                renderPreviews(dropped);
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

    // Handle gender-based outfit visibility
    function toggleOutfitsByGender(genderSelect, modelCard) {
        const gender = genderSelect.value;
        const outfitContainer = modelCard.querySelector('.outfit-selection-grid');
        if (!outfitContainer) return;

        const maleOutfits = outfitContainer.querySelector('[data-outfit-gender="male"]');
        const femaleOutfits = outfitContainer.querySelector('[data-outfit-gender="female"]');

        if (gender === 'male') {
            if (maleOutfits) {
                maleOutfits.style.display = 'flex';
                maleOutfits.style.flexDirection = 'column';
            }
            if (femaleOutfits) femaleOutfits.style.display = 'none';
        } else if (gender === 'female') {
            if (maleOutfits) maleOutfits.style.display = 'none';
            if (femaleOutfits) {
                femaleOutfits.style.display = 'flex';
                femaleOutfits.style.flexDirection = 'column';
            }
        } else {
            // If gender is 'any' or other, show both
            if (maleOutfits) {
                maleOutfits.style.display = 'flex';
                maleOutfits.style.flexDirection = 'column';
            }
            if (femaleOutfits) {
                femaleOutfits.style.display = 'flex';
                femaleOutfits.style.flexDirection = 'column';
            }
        }
    }

    // Initialize gender-based outfit visibility for existing selects
    scope.querySelectorAll('[data-gender-select]').forEach(select => {
        const modelCard = select.closest('[data-model-card]');
        if (modelCard) {
            // Set initial state
            toggleOutfitsByGender(select, modelCard);

            // Listen for changes
            select.addEventListener('change', () => {
                toggleOutfitsByGender(select, modelCard);
            });
        }
    });

    // Initialize custom label multiselects if the init function is available
    if (window.__initLabelMultiselect) {
        window.__initLabelMultiselect(scope);
    }

    // Initialize traditional outfit toggle for male outfits
    scope.querySelectorAll('[data-toggle-traditional]').forEach(btn => {
        if (btn.dataset.toggleBound === 'true') return;
        btn.dataset.toggleBound = 'true';

        const outfitCard = btn.closest('[data-outfit-gender="male"]');
        const modelCard = btn.closest('[data-model-card]');
        const hiddenInput = modelCard?.querySelector('[data-traditional-mode-input]');

        // Set initial state based on hidden input
        if (outfitCard && hiddenInput) {
            const isTraditional = hiddenInput.value === 'true';
            outfitCard.dataset.traditionalMode = isTraditional ? 'true' : 'false';
            btn.textContent = isTraditional ? 'Switch to Casual Outfit' : 'Switch to Traditional Outfit';
        }

        btn.addEventListener('click', function() {
            if (!outfitCard || !hiddenInput) return;

            const isTraditionalMode = outfitCard.dataset.traditionalMode === 'true';

            if (isTraditionalMode) {
                // Switch to top/bottom mode
                outfitCard.dataset.traditionalMode = 'false';
                hiddenInput.value = 'false';
                this.textContent = 'Switch to Traditional Outfit';
            } else {
                // Switch to traditional mode
                outfitCard.dataset.traditionalMode = 'true';
                hiddenInput.value = 'true';
                this.textContent = 'Switch to Casual Outfit';
            }
        });
    });

    // Add event listeners to Hours Needed inputs to regenerate time slots
    scope.querySelectorAll('.model-hours-input').forEach(input => {
        if (input.dataset.hoursListenerBound === 'true') return;
        input.dataset.hoursListenerBound = 'true';

        input.addEventListener('input', function() {
            const modelCard = this.closest('[data-model-card]');
            if (modelCard && window.updateTimeSlotForCard) {
                window.updateTimeSlotForCard(modelCard);
            }
        });

        input.addEventListener('change', function() {
            const modelCard = this.closest('[data-model-card]');
            if (modelCard && window.updateTimeSlotForCard) {
                window.updateTimeSlotForCard(modelCard);
            }
        });
    });

    // Constrain time slot inputs and hours-needed to the shoot window
    if (window.applyTimeSlotBounds) window.applyTimeSlotBounds(scope);
    if (window.applyHoursMax) window.applyHoursMax(scope);
};

document.addEventListener('DOMContentLoaded', function () {
    const modelsContainer = document.querySelector('[data-model-requirements]');
    const template = document.getElementById('modelRequirementTemplate');
    const addModelBtn = document.querySelector('[data-add-model]');

    if (!modelsContainer || !template || !addModelBtn) {
        return;
    }

    let nextIndex = parseInt(modelsContainer.getAttribute('data-next-index'), 10) || modelsContainer.querySelectorAll('[data-model-card]').length;

    const numberToWord = (num) => {
        const words = ['Zero', 'One', 'Two', 'Three', 'Four', 'Five', 'Six', 'Seven', 'Eight', 'Nine', 'Ten', 'Eleven', 'Twelve'];
        return words[num] || num;
    };

    const createModelCard = (index) => {
        const wrapper = document.createElement('div');
        const displayIndex = index + 1;
        const displayWord = numberToWord(displayIndex);

        // Use regex to replace Model __INDEX_DISPLAY__ with Model One/Two etc
        let html = template.innerHTML
            .replace(/__INDEX__/g, index)
            .replace(/Model __INDEX_DISPLAY__/g, `Model ${displayWord}`)
            // Fallback for strict numbers if needed elsewhere
            .replace(/__INDEX_DISPLAY__/g, displayIndex)
            .trim();

        wrapper.innerHTML = html;
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
        const sourceInputs = sourceCard.querySelectorAll('select, input[type="text"], input[type="hidden"], input[type="file"], input[type="number"]');
        sourceInputs.forEach((input) => {
            const name = input.getAttribute('name');
            if (!name) return;
            const match = name.match(/models\[(\d+)\]\[(.+)]/);
            if (!match) return;
            const field = match[2];
            if (field === 'id') return; // Skip ID when duplicating
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

    // Initialize traditional outfit toggle for all existing male outfit cards
    document.querySelectorAll('[data-outfit-gender="male"]').forEach(card => {
        card.dataset.traditionalMode = 'false'; // Default to top/bottom mode
    });
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

// --- Custom Date & Time Pickers ---
document.addEventListener('DOMContentLoaded', function() {
    const dateTrigger = document.getElementById('datePickerTrigger');
    const calendarDropdown = document.getElementById('calendarDropdown');
    const shootDateInput = document.getElementById('shoot_date');
    const calendarDays = document.getElementById('calendarDays');
    const monthYearLabel = calendarDropdown?.querySelector('.month-year-label');
    const btnPrevMonth = calendarDropdown?.querySelector('.btn-prev-month');
    const btnNextMonth = calendarDropdown?.querySelector('.btn-next-month');
    const btnClearDate = calendarDropdown?.querySelector('.btn-clear-date');

    const timeTrigger = document.getElementById('timePickerTrigger');
    const timeDropdown = document.getElementById('timeDropdown');
    const shootTimeInput = document.getElementById('shoot_time');
    const timeList = document.getElementById('timeList');

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    if (shootDateInput) {
        shootDateInput.setAttribute('min', today.toISOString().split('T')[0]);
    }

    let currentCalDate = new Date();
    let selectedDate = shootDateInput?.value ? new Date(shootDateInput.value) : null;

    const renderCalendar = () => {
        if (!calendarDays || !monthYearLabel) return;

        calendarDays.innerHTML = '';
        const year = currentCalDate.getFullYear();
        const month = currentCalDate.getMonth();

        const monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
        monthYearLabel.textContent = `${monthNames[month]} ${year}`;

        // Disable going to past months entirely
        const prevMonthLastDay = new Date(year, month, 0);
        if (btnPrevMonth) {
            const disablePrev = prevMonthLastDay < today;
            btnPrevMonth.disabled = disablePrev;
            btnPrevMonth.style.opacity = disablePrev ? '0.4' : '1';
            btnPrevMonth.style.cursor = disablePrev ? 'not-allowed' : 'pointer';
        }

        const firstDay = new Date(year, month, 1).getDay();
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevDaysInMonth = new Date(year, month, 0).getDate();

        // Prev month days
        for (let i = firstDay - 1; i >= 0; i--) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day out-of-month';
            dayDiv.textContent = prevDaysInMonth - i;
            calendarDays.appendChild(dayDiv);
        }

        // Current month days
        for (let i = 1; i <= daysInMonth; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day';
            dayDiv.textContent = i;

            const thisDate = new Date(year, month, i);
            if (selectedDate && thisDate.toDateString() === selectedDate.toDateString()) {
                dayDiv.classList.add('selected');
            }
            if (thisDate.toDateString() === new Date().toDateString()) {
                dayDiv.classList.add('today');
            }

            const isPast = thisDate < today;
            if (isPast) {
                dayDiv.classList.add('disabled-day');
            } else {
                dayDiv.addEventListener('click', () => {
                    selectedDate = thisDate;
                    const yyyy = selectedDate.getFullYear();
                    const mm = String(selectedDate.getMonth() + 1).padStart(2, '0');
                    const dd = String(selectedDate.getDate()).padStart(2, '0');
                    shootDateInput.value = `${yyyy}-${mm}-${dd}`;
                    // Trigger input event to clear validation errors
                    shootDateInput.dispatchEvent(new Event('input', { bubbles: true }));
                    calendarDropdown.classList.remove('show');
                    renderCalendar();
                });
            }

            calendarDays.appendChild(dayDiv);
        }

        // Next month days
        const totalCells = 42;
        const currentCells = firstDay + daysInMonth;
        for (let i = 1; i <= totalCells - currentCells; i++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day out-of-month';
            dayDiv.textContent = i;
            calendarDays.appendChild(dayDiv);
        }
    };

    const renderTimeList = () => {
        if (!timeList) return;
        timeList.innerHTML = '';
        const times = [];

        // Start from 9:00 AM and go through to 8:00 AM next day
        // 9 AM to 11:30 PM (same day)
        for (let h = 9; h <= 23; h++) {
            for (let m = 0; m < 60; m += 30) {
                const h12 = h % 12 || 12;
                const ampm = h >= 12 ? 'PM' : 'AM';
                const timeStr = `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
                const valueStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                times.push({ label: timeStr, value: valueStr });
            }
        }

        // 12:00 AM to 8:00 AM (next day)
        for (let h = 0; h <= 8; h++) {
            for (let m = 0; m < 60; m += 30) {
                if (h === 8 && m > 0) continue; // stop at 8:00 AM
                const h12 = h % 12 || 12;
                const ampm = h >= 12 ? 'PM' : 'AM';
                const timeStr = `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
                const valueStr = `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`;
                times.push({ label: timeStr, value: valueStr });
            }
        }

        times.forEach(t => {
            const item = document.createElement('div');
            item.className = 'time-item';
            if (shootTimeInput.value === t.value) {
                item.classList.add('selected');
                const headerText = document.getElementById('selectedTimeHeader');
                if (headerText) headerText.textContent = t.label;
            }
            item.textContent = t.label;
            item.addEventListener('click', () => {
                const shootTimeValue = document.getElementById('shoot_time_value');
                if (shootTimeInput) {
                    shootTimeInput.value = t.label;
                    // Trigger input event to clear validation errors
                    shootTimeInput.dispatchEvent(new Event('input', { bubbles: true }));
                }
                if (shootTimeValue) {
                    shootTimeValue.value = t.value;
                    shootTimeValue.dispatchEvent(new Event('input', { bubbles: true }));
                }
                shootTimeInput?.setAttribute('data-time-24h', t.value);
                const headerText = document.getElementById('selectedTimeHeader');
                if (headerText) headerText.textContent = t.label;
                timeDropdown.classList.remove('show');
                renderTimeList();
            });
            timeList.appendChild(item);
        });
    };

    // Close buttons logic
    document.querySelectorAll('.btn-close-picker').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            calendarDropdown?.classList.remove('show');
            timeDropdown?.classList.remove('show');
        });
    });

    dateTrigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        timeDropdown?.classList.remove('show');
        calendarDropdown?.classList.toggle('show');
        if (calendarDropdown?.classList.contains('show')) {
            renderCalendar();
        }
    });

    timeTrigger?.addEventListener('click', (e) => {
        e.stopPropagation();
        calendarDropdown?.classList.remove('show');
        timeDropdown?.classList.toggle('show');
        if (timeDropdown?.classList.contains('show')) {
            renderTimeList();
        }
    });

    btnPrevMonth?.addEventListener('click', (e) => {
        e.stopPropagation();
        currentCalDate.setMonth(currentCalDate.getMonth() - 1);
        renderCalendar();
    });

    btnNextMonth?.addEventListener('click', (e) => {
        e.stopPropagation();
        currentCalDate.setMonth(currentCalDate.getMonth() + 1);
        renderCalendar();
    });

    btnClearDate?.addEventListener('click', (e) => {
        e.stopPropagation();
        selectedDate = null;
        shootDateInput.value = '';
        calendarDropdown.classList.remove('show');
        renderCalendar();
    });

    document.addEventListener('click', (e) => {
        if (calendarDropdown && !calendarDropdown.contains(e.target) && !dateTrigger.contains(e.target)) {
            calendarDropdown.classList.remove('show');
        }
        if (timeDropdown && !timeDropdown.contains(e.target) && !timeTrigger.contains(e.target)) {
            timeDropdown.classList.remove('show');
        }
    });

    // Initial render
    renderCalendar();
    renderTimeList();

    // Ensure input shows time in 12h AM/PM format
    (function syncTimeDisplayFromValue() {
        const shootTimeInput = document.getElementById('shoot_time');
        const shootTimeValue = document.getElementById('shoot_time_value');
        if (!shootTimeInput || !shootTimeValue.value) return;
        // Convert 24h time to 12h format with AM/PM
        const [hStr, mStr] = shootTimeValue.value.split(':');
        const h = parseInt(hStr, 10);
        const m = parseInt(mStr, 10);
        if (!isNaN(h) && !isNaN(m)) {
            const h12 = (h % 12) || 12;
            const ampm = h >= 12 ? 'PM' : 'AM';
            shootTimeInput.value = `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
            shootTimeInput.setAttribute('data-time-24h', `${String(h).padStart(2, '0')}:${String(m).padStart(2, '0')}`);
        }
    })();
});

// --- Time Slot Generation for Model Cards ---
function generateTimeSlots(startTimeStr, hoursNeeded) {
    if (!startTimeStr || !hoursNeeded) {
        return [];
    }

    const slots = [];
    const [startH, startM] = startTimeStr.split(':').map(Number);

    if (isNaN(startH) || isNaN(startM)) {
        return [];
    }

    // Start from the given time and generate slots until 8:00 AM next day
    let currentH = startH;
    let currentM = startM;
    let dayOffset = 0; // 0 = same day, 1 = next day

    // Generate slots
    while (true) {
        // End time of this slot
        let endH = currentH + Math.floor(hoursNeeded);
        let endM = currentM + ((hoursNeeded % 1) * 60);

        // Handle minute overflow for end time
        if (endM >= 60) {
            endH += Math.floor(endM / 60);
            endM = endM % 60;
        }

        // Handle hour overflow to next day for end time
        let endDayOffset = dayOffset;
        if (endH >= 24) {
            endDayOffset += Math.floor(endH / 24);
            endH = endH % 24;
        }

        // Check if the END time has passed 8:00 AM on the next day
        // (If strictly "end at 8am next day", we stop if end time > 8:00 AM next day)
        if (endDayOffset > 1 || (endDayOffset === 1 && (endH > 8 || (endH === 8 && endM > 0)))) {
            // Stop generating slots
            break;
        }

        // Format time display with AM/PM
        const startDisplay = formatTimeWithAMPM(currentH, currentM);
        const endDisplay = formatTimeWithAMPM(endH, endM);
        const slotLabel = `${startDisplay} – ${endDisplay}`;

        // Store value as the exact label string
        const slotValue = slotLabel;

        slots.push({
            label: slotLabel,
            value: slotValue,
            startH: currentH,
            startM: currentM,
            endH: endH,
            endM: endM,
            endDayOffset: endDayOffset
        });

        // Move to next slot start time (Increment by 30 minutes)
        currentM += 30;
        if (currentM >= 60) {
            currentH += Math.floor(currentM / 60);
            currentM = currentM % 60;
        }
        
        // Handle hour overflow for start time
        if (currentH >= 24) {
            dayOffset += Math.floor(currentH / 24);
            currentH = currentH % 24;
        }
    }

    return slots;
}

function formatTimeWithAMPM(h, m) {
    const h12 = h % 12 || 12;
    const ampm = h >= 12 ? 'PM' : 'AM';
    return `${h12}:${String(m).padStart(2, '0')} ${ampm}`;
}

function populateTimeSlotSelect(selectElement, slots, currentValue) {
    selectElement.innerHTML = '<option value="">Select a time slot</option>';

    slots.forEach(slot => {
        const option = document.createElement('option');
        option.value = slot.value;
        option.textContent = slot.label;
        if (currentValue === slot.value) {
            option.selected = true;
        }
        selectElement.appendChild(option);
    });
}

function updateTimeSlotForCard(modelCard) {
    const shootTimeValue = document.getElementById('shoot_time_value');
    if (!shootTimeValue || !shootTimeValue.value) {
        return;
    }

    const startTime = shootTimeValue.value; // Read from hidden input with 24h format
    
    // Find the Hours Needed input for this specific model card
    const hoursInput = modelCard.querySelector('.model-hours-input');
    if (!hoursInput || !hoursInput.value) {
        return;
    }

    const hoursNeeded = parseFloat(hoursInput.value);
    if (isNaN(hoursNeeded) || hoursNeeded <= 0) {
        return;
    }

    // Generate slots based on this model's hours needed
    const slots = generateTimeSlots(startTime, hoursNeeded);

    // Update the time slot select for this model card
    const timeSlotSelect = modelCard.querySelector('.time-slot-select');
    if (timeSlotSelect) {
        const currentValue = timeSlotSelect.value;
        populateTimeSlotSelect(timeSlotSelect, slots, currentValue);
    }
}

function updateAllTimeSlotSelects() {
    // Update time slots for all existing model cards
    document.querySelectorAll('[data-model-card]').forEach(card => {
        updateTimeSlotForCard(card);
    });
}

function applyHoursMax(scope = document) {
    const durationInputEl = document.getElementById('duration');
    const durationVal = durationInputEl?.value;
    const durationHours = durationVal ? parseFloat(durationVal) : null;
    scope.querySelectorAll('input[name*="[model_hours]"]').forEach(input => {
        if (!Number.isNaN(durationHours) && durationHours > 0) {
            input.max = durationHours;
        } else {
            input.removeAttribute('max');
        }
    });
}

// Expose functions globally
window.updateTimeSlotForCard = updateTimeSlotForCard;
window.updateAllTimeSlotSelects = updateAllTimeSlotSelects;
window.applyHoursMax = applyHoursMax;

// Wire up Step 1 changes to regenerate time slots for all cards
document.addEventListener('DOMContentLoaded', function() {
    const shootTimeValue = document.getElementById('shoot_time_value');

    if (shootTimeValue) {
        shootTimeValue.addEventListener('change', updateAllTimeSlotSelects);
    }

    // Listen for changes on any model-hours-input (using delegation)
    const form = document.getElementById('shootWizard');
    if (form) {
        form.addEventListener('input', function(e) {
            if (e.target.classList.contains('model-hours-input')) {
                const card = e.target.closest('[data-model-card]');
                if (card) {
                    updateTimeSlotForCard(card);
                }
            }
        });
    }

    // Initial population on page load
    setTimeout(updateAllTimeSlotSelects, 100);
});

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