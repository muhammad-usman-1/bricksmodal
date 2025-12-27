@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e6e7eb;
        --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .outfit-form-shell {
        background: var(--bg);
        padding: 8px 0 22px;
    }

    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 18px;
    }

    .title-block h5 {
        color: #101828;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: 36px;
        margin-bottom: 0;
    }

    .title-block .sub {
        margin: 2px 0 0;
        color: var(--ink-500);
        font-size: 13px;
    }

    .back-link {
        color: var(--ink-700);
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        font-weight: 600;
        border: 1px solid var(--border);
        border-radius: 8px;
        background: #fff;
    }

    .back-link:hover {
        background: #f9fafb;
        color: var(--ink-900);
        text-decoration: none;
        border-color: #cbd5e1;
    }

    .form-card {
        background: var(--card);
        border-radius: 12px;
        box-shadow: var(--shadow);
        padding: 20px 24px;
        border: 1px solid var(--border);
    }

    .field-block {
        display: flex;
        flex-direction: column;
        gap: 6px;
        margin-bottom: 18px;
    }

    .field-block label {
        font-size: 12px;
        color: #101828;
        font-weight: 700;
        margin: 0;
    }

    .field-block label.required::after {
        content: ' *';
        color: #dc2626;
    }

    .category-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
        margin-bottom: 18px;
    }

    .dark-input {
        background: #0f0f11;
        border-radius: 8px;
        padding: 8px 10px;
        display: flex;
        align-items: center;
        gap: 8px;
        position: relative;
        border: 1px solid transparent;
        transition: border-color 0.15s ease;
    }

    .dark-input:focus-within {
        border-color: #cdd4e3;
        box-shadow: 0 0 0 3px rgba(56, 115, 255, 0.12);
    }

    .dark-input input,
    .dark-input select {
        background: transparent;
        border: none;
        color: #f7f7f7;
        width: 100%;
        font-size: 13px;
        padding: 4px 0;
        outline: none;
        appearance: none;
    }

    .dark-input select {
        cursor: pointer;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%23a8adb5" d="M5 6L0 0h10z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 10px 6px;
        padding-right: 32px;
    }

    .dark-input input::placeholder {
        color: #a8adb5;
    }

    .dark-input input:-webkit-autofill,
    .dark-input input:-webkit-autofill:hover,
    .dark-input input:-webkit-autofill:focus {
        background: transparent !important;
        -webkit-text-fill-color: #f7f7f7;
        box-shadow: 0 0 0px 1000px #0f0f11 inset !important;
        caret-color: #f7f7f7;
    }

    .file-upload-wrapper {
        position: relative;
    }

    .file-upload-label {
        display: block;
        width: 100%;
        padding: 12px;
        background: #f6f7fb;
        border: 1px solid var(--border);
        border-radius: 8px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--ink-700);
        font-size: 13px;
        font-weight: 600;
    }

    .file-upload-label:hover {
        background: #eef1f7;
        border-color: #cbd5e1;
    }

    .file-upload-input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .file-preview {
        margin-top: 10px;
    }

    .file-preview img {
        max-width: 200px;
        max-height: 200px;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .current-image {
        margin-bottom: 10px;
    }

    .checkbox-wrapper {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 12px;
        background: #f6f7fb;
        border: 1px solid var(--border);
        border-radius: 8px;
    }

    .checkbox-wrapper input[type="checkbox"] {
        width: 18px;
        height: 18px;
        cursor: pointer;
        accent-color: #2C2C2E;
    }

    .checkbox-wrapper label {
        margin: 0;
        cursor: pointer;
        font-weight: 600;
        color: var(--ink-700);
        font-size: 13px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 12px;
        margin-top: 24px;
        padding-top: 20px;
        border-top: 1px solid var(--border);
    }

    .btn-save {
        background: #2C2C2E;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        box-shadow: 0 8px 18px rgba(0,0,0,0.14);
        transition: all 0.2s ease;
    }

    .btn-save:hover {
        background: #1a1a1c;
        color: #fff;
    }

    .btn-cancel {
        background: #fff;
        color: var(--ink-700);
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: all 0.2s ease;
    }

    .btn-cancel:hover {
        background: #f9fafb;
        color: var(--ink-900);
        border-color: #cbd5e1;
        text-decoration: none;
    }

    .invalid-feedback {
        display: block;
        color: #dc2626;
        font-size: 12px;
        margin-top: 4px;
    }

    .dark-input.is-invalid {
        border-color: #dc2626;
    }

    .dark-input.is-invalid:focus-within {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    /* Light dropdown styling */
    .light-select {
        background: #f7f8fb;
        border: 1px solid var(--border);
        border-radius: 8px;
        padding: 10px 32px 10px 12px;
        width: 100%;
        font-size: 13px;
        color: var(--ink-900);
        outline: none;
        appearance: none;
        cursor: pointer;
        transition: all 0.15s ease;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%237b8191" d="M5 6L0 0h10z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 12px center;
        background-size: 10px 6px;
    }

    .light-select:focus {
        border-color: #cbd5e1;
        box-shadow: 0 0 0 3px rgba(203, 213, 225, 0.2);
        background-color: #f7f8fb;
    }

    .light-select:hover {
        border-color: #cbd5e1;
        background-color: #f9fafb;
    }

    .light-select option {
        background: #fff;
        color: var(--ink-900);
        padding: 8px;
    }

    /* Remove blue hover color from select options */
    .light-select option:hover,
    .light-select option:focus,
    .light-select option:checked {
        background: #f7f8fb !important;
        color: var(--ink-900) !important;
    }

    /* Prevent default browser blue selection */
    .light-select::-moz-focus-inner {
        border: 0;
    }

    .light-select:focus {
        outline: none;
    }

    /* Style for selected option in dropdown */
    .light-select option:checked {
        background-color: #f7f8fb !important;
        color: var(--ink-900) !important;
    }

    .light-select.is-invalid {
        border-color: #dc2626;
    }

    .light-select.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.12);
    }

    @media (max-width: 768px) {
        .category-row {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="outfit-form-shell">
    <div class="top-row">
        <div class="title-block">
            <h5>Edit Outfit</h5>
            <div class="sub">Update outfit details.</div>
        </div>
        <a class="back-link" href="{{ route('admin.outfits.index') }}">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
    </div>

    <div class="form-card">
        <form method="POST" action="{{ route("admin.outfits.update", [$outfit->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="field-block">
                <label class="required" for="name">Name</label>
                <div class="dark-input {{ $errors->has('name') ? 'is-invalid' : '' }}">
                    <input type="text" name="name" id="name" value="{{ old('name', $outfit->name) }}" placeholder="Enter outfit name" required>
                </div>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="category-row">
                <div class="field-block">
                    <label class="required" for="category">Category</label>
                    <select name="category" id="category" class="light-select {{ $errors->has('category') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled {{ old('category', null) === null ? 'selected' : '' }}>Please select</option>
                        @foreach(App\Models\Outfit::CATEGORY_SELECT as $key => $label)
                            @if($key !== 'child')
                                <option value="{{ $key }}" {{ old('category', $outfit->category) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                            @endif
                        @endforeach
                    </select>
                    @if($errors->has('category'))
                        <div class="invalid-feedback">
                            {{ $errors->first('category') }}
                        </div>
                    @endif
                </div>

                <div class="field-block">
                    <label class="required" for="sub_category">Sub Category</label>
                    <select name="sub_category" id="sub_category" class="light-select {{ $errors->has('sub_category') ? 'is-invalid' : '' }}" required>
                        <option value="" disabled {{ old('sub_category', $outfit->sub_category) === null ? 'selected' : '' }}>Please select</option>
                        @foreach(App\Models\Outfit::SUB_CATEGORY_SELECT as $key => $label)
                            <option value="{{ $key }}" {{ old('sub_category', $outfit->sub_category) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @if($errors->has('sub_category'))
                        <div class="invalid-feedback">
                            {{ $errors->first('sub_category') }}
                        </div>
                    @endif
                </div>
            </div>

            <div class="field-block">
                <label for="image">Image</label>
                @if($outfit->image)
                    <div class="current-image">
                        <img src="{{ $outfit->image }}" alt="{{ $outfit->name }}" style="max-width: 200px; max-height: 200px; border-radius: 8px; border: 1px solid var(--border);">
                    </div>
                @endif
                <div class="file-upload-wrapper">
                    <input type="file" class="file-upload-input" name="image" id="image" accept="image/*" onchange="previewFile(this)">
                    <label for="image" class="file-upload-label">
                        <i class="fas fa-upload"></i> {{ $outfit->image ? 'Choose new file to replace' : 'Choose file' }}
                    </label>
                    <div class="file-preview" id="filePreview" style="display: none;">
                        <img id="previewImage" src="" alt="Preview">
                    </div>
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
            </div>

            <div class="field-block">
                <label for="sort_order">Sort Order</label>
                <div class="dark-input {{ $errors->has('sort_order') ? 'is-invalid' : '' }}">
                    <input type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $outfit->sort_order) }}" placeholder="0" step="1">
                </div>
                @if($errors->has('sort_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sort_order') }}
                    </div>
                @endif
            </div>

            <div class="field-block">
                <div class="checkbox-wrapper">
                    <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $outfit->is_active) == 1 ? 'checked' : '' }}>
                    <label for="is_active">Is Active</label>
                </div>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
            </div>

            <div class="form-actions">
                <a href="{{ route('admin.outfits.index') }}" class="btn-cancel">
                    Cancel
                </a>
                <button class="btn-save" type="submit">
                    Update Outfit
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(input) {
        const preview = document.getElementById('filePreview');
        const previewImage = document.getElementById('previewImage');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }
</script>
@endsection
