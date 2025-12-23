@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Edit Outfit</h5>
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.outfits.update", [$outfit->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">Name</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $outfit->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">
                        {{ $errors->first('name') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="category">Category</label>
                <select class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category" id="category" required>
                    <option value disabled {{ old('category', null) === null ? 'selected' : '' }}>Please select</option>
                    @foreach(App\Models\Outfit::CATEGORY_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $outfit->category) === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('category') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="sub_category">Sub Category (optional)</label>
                <input class="form-control {{ $errors->has('sub_category') ? 'is-invalid' : '' }}" type="text" name="sub_category" id="sub_category" value="{{ old('sub_category', $outfit->sub_category) }}" placeholder="e.g. top, bottom, traditional">
                @if($errors->has('sub_category'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sub_category') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="image">Image</label>
                <div class="mb-2">
                    @if($outfit->image)
                        <img src="{{ $outfit->image }}" alt="{{ $outfit->name }}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 4px; border: 1px solid #ddd;">
                    @else
                        <span class="text-muted">No Image</span>
                    @endif
                </div>
                <div class="custom-file">
                    <input type="file" class="custom-file-input {{ $errors->has('image') ? 'is-invalid' : '' }}" name="image" id="image">
                    <label class="custom-file-label" for="image">Choose new file to replace</label>
                </div>
                @if($errors->has('image'))
                    <div class="invalid-feedback">
                        {{ $errors->first('image') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <label for="sort_order">Sort Order</label>
                <input class="form-control {{ $errors->has('sort_order') ? 'is-invalid' : '' }}" type="number" name="sort_order" id="sort_order" value="{{ old('sort_order', $outfit->sort_order) }}" step="1">
                @if($errors->has('sort_order'))
                    <div class="invalid-feedback">
                        {{ $errors->first('sort_order') }}
                    </div>
                @endif
            </div>

            <div class="form-group">
                <div class="form-check {{ $errors->has('is_active') ? 'is-invalid' : '' }}">
                    <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input" {{ old('is_active', $outfit->is_active) == 1 ? 'checked' : '' }}>
                    <label class="form-check-label" for="is_active">Is Active</label>
                </div>
                @if($errors->has('is_active'))
                    <div class="invalid-feedback">
                        {{ $errors->first('is_active') }}
                    </div>
                @endif
            </div>

            <div class="form-group mt-4">
                <button class="btn btn-primary" type="submit">
                    Update Outfit
                </button>
                <a href="{{ route('admin.outfits.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
