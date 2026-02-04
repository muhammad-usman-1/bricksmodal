@extends('layouts.admin')

@section('styles')
<style>
    .shoot-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .shoot-title1 { color: #000; font-family: 'Arimo', sans-serif; font-size: 24px; font-weight: 400; line-height: 36px; margin: 0; margin-top:10px;}
    .shoot-subtitle { color: #6c7280; font-size: 13px; margin-top: 2px; }
    .shoot-back { border: 1px solid #d6d8de; background: #fff; color: #3b4150; border-radius: 8px; padding: 10px 16px; font-size: 12px; font-weight: 600; text-decoration: none !important; transition: all 0.2s ease; display: inline-flex; align-items: center; }
    .shoot-back:hover { background: #f8f9fa; color: #3b4150; border-color: #c0c4cc; text-decoration: none !important; }
    .shoot-back i { margin-right: 8px; font-size: 10px; }

    .btn-dark { background-color: #000 !important; border-color: #000 !important; }
    .form-control:focus { border-color: #000; box-shadow: none !important; }
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before { background-color: #000 !important; border-color: #000 !important; }
</style>
@endsection

@section('content')
<div class="content">
    <div class="shoot-header">
        <div>
            <h2 class="shoot-title1">Create Notification Template</h2>
            <p class="shoot-subtitle">Define a new system notification with bilingual content</p>
        </div>
        <a href="{{ route('admin.notification-templates.index') }}" class="shoot-back">
            <i class="fas fa-chevron-left"></i> Back to List
        </a>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body bg-white p-4">
            <form method="POST" action="{{ route('admin.notification-templates.store') }}" id="templateForm">
                @csrf
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1" for="key">Unique Key (e.g., talent_signup)</label>
                            <input class="form-control border-dark @error('key') is-invalid @enderror" type="text" name="key" id="key" value="{{ old('key', '') }}" required placeholder="Enter unique identifier">
                            @error('key')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1" for="name">Friendly Name</label>
                            <input class="form-control border-dark @error('name') is-invalid @enderror" type="text" name="name" id="name" value="{{ old('name', '') }}" required placeholder="e.g., Talent Signup Welcome">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-dark font-weight-bold mb-3">English Content</h5>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1" for="title_en">Title (English)</label>
                            <input class="form-control border-dark" type="text" name="title_en" id="title_en" value="{{ old('title_en', '') }}" placeholder="Subject line in English">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1" for="content_en">Message Content (English)</label>
                            <textarea class="form-control border-dark" name="content_en" id="content_en" rows="5" placeholder="Notification body in English. Use {placeholders} for dynamic data.">{{ old('content_en') }}</textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-dark font-weight-bold mb-3 text-right" style="direction: rtl;">المحتوى العربي</h5>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1 d-block text-right" for="title_ar">العنوان (العربية)</label>
                            <input class="form-control border-dark" type="text" name="title_ar" id="title_ar" value="{{ old('title_ar', '') }}" style="direction: rtl; text-align: right;" placeholder="موضوع الإشعار بالعربية">
                        </div>
                        <div class="form-group mb-3">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1 d-block text-right" for="content_ar">محتوى الرسالة (العربية)</label>
                            <textarea class="form-control border-dark" name="content_ar" id="content_ar" rows="5" style="direction: rtl; text-align: right;" placeholder="محتوى الإشعار بالعربية. استخدم الرموز لبيانات متغيرة.">{{ old('content_ar') }}</textarea>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group mb-4">
                            <label class="font-weight-bold text-dark text-uppercase text-xs mb-1">Language Preference</label>
                            <select class="form-control border-dark" name="language_preference" required>
                                <option value="both" {{ old('language_preference') == 'both' ? 'selected' : '' }}>Both English & Arabic</option>
                                <option value="en" {{ old('language_preference') == 'en' ? 'selected' : '' }}>English Only</option>
                                <option value="ar" {{ old('language_preference') == 'ar' ? 'selected' : '' }}>Arabic Only</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 d-flex align-items-center mb-4">
                        <div class="custom-control custom-switch">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" {{ old('is_active', 1) == 1 ? 'checked' : '' }}>
                            <label class="custom-control-label font-weight-bold text-dark text-uppercase text-xs" for="is_active">Active Status</label>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4 pt-3 border-top">
                    <a href="{{ route('admin.notification-templates.index') }}" class="btn btn-outline-secondary px-4 py-2 mr-2">Cancel</a>
                    <button class="btn btn-dark px-5 py-2" type="submit">
                        Create Template
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    $(function() {
        $('#templateForm').on('submit', function(e) {
            e.preventDefault();
            let form = this;
            
            Swal.fire({
                title: 'Confirm Save',
                text: "Are you sure you want to save this notification template?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#000000',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '{{ trans('global.yes') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
