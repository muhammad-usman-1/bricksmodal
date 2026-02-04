@extends('layouts.admin')

@section('styles')
<style>
    .custom-switch .custom-control-input:checked ~ .custom-control-label::before {
        background-color: #000 !important;
        border-color: #000 !important;
    }
    .btn-dark {
        background-color: #000 !important;
        border-color: #000 !important;
        color: #fff !important;
    }
    .btn:focus, .btn:active, .form-control:focus, .custom-control-input:focus {
        outline: none !important;
        box-shadow: none !important;
    }
    .btn-outline-dark:hover {
        background-color: #000 !important;
        color: #fff !important;
    }
    .table thead.bg-dark th {
        color: #fff !important;
        background-color: #000 !important;
        border: none !important;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.05em;
        font-weight: 700;
        padding: 1rem .75rem;
    }
    .table tbody td {
        vertical-align: middle;
        border-top: 1px solid #f3f4f6;
        color: #111827;
        padding: 1rem .75rem;
    }
    .actions { display: inline-flex; gap: 12px; align-items: center; justify-content: center; width: 100%; }
    .action-icon { 
        font-size: 14px; 
        text-decoration: none !important; 
        transition: none; 
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
        background: none !important;
        padding: 0;
    }
    .action-icon:hover, .action-icon:focus, .action-icon:active {
        opacity: 0.8 !important;
        text-decoration: none !important;
        border: none !important;
        outline: none !important;
        box-shadow: none !important;
    }
    .action-icon.edit { color: #f59e0b; }
    .action-icon.delete { color: #dc2626; }
    
    .badge-soft {
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-soft-dark { background-color: #111827; color: #fff; }
    .badge-soft-light { background-color: #f3f4f6; color: #374151; border: 1px solid #e5e7eb; }
</style>
@endsection

@section('content')
<div class="content">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
        <div>
            <h2 class="mt-2" style="color: black;
           font-family: 'Arimo', sans-serif; color: black; font-size: 24px;  font-weight: 400; line-height: 36px;">Notification Templates</h2>
            <p class="text-muted">Manage system-generated automated messages</p>
        </div>
        <div>
            <a class="btn btn-dark px-4 py-2" href="{{ route('admin.notification-templates.create') }}" style="background-color: #000; border-color: #000;">
                <i class="fas fa-plus mr-1"></i> Create New Template
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm mb-4" style="background-color: #ecfdf5; color: #065f46;">
            {{ session('success') }}
        </div>
    @endif

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead class="bg-dark">
                        <tr>
                            <th class="pl-4">Key / Name</th>
                            <th>English Content</th>
                            <th class="text-right" style="direction: rtl;">المحتوى العربي</th>
                            <th class="text-center">Language Pref</th>
                            <th class="text-center">Status</th>
                            <th class="pr-4 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($notificationTemplates as $template)
                            <tr class="border-bottom">
                                <td class="pl-4 align-middle">
                                    <span class="text-dark d-block">{{ $template->name }}</span>
                                    <small class="text-muted">{{ $template->key }}</small>
                                </td>
                                <td class="align-middle">
                                    <div class="text-dark" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $template->title_en }}">
                                        {{ $template->title_en ?: 'No English Title' }}
                                    </div>
                                    <div class="text-muted small" style="max-width: 250px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $template->content_en ?: 'No content' }}
                                    </div>
                                </td>
                                <td class="align-middle text-right" style="direction: rtl;">
                                    <div class="text-dark" style="max-width: 250px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;" title="{{ $template->title_ar }}">
                                        {{ $template->title_ar ?: 'لا يوجد عنوان' }}
                                    </div>
                                    <div class="text-muted small" style="max-width: 250px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $template->content_ar ?: 'لا يوجد محتوى' }}
                                    </div>
                                </td>
                                <td class="align-middle text-center">
                                    <span class="badge badge-light border text-uppercase">{{ $template->language_preference }}</span>
                                </td>
                                <td class="align-middle text-center">
                                    @if($template->is_active)
                                        <span class="badge badge-success px-3" style="border-radius: 20px;">Active</span>
                                    @else
                                        <span class="badge badge-secondary px-3" style="border-radius: 20px;">Inactive</span>
                                    @endif
                                </td>
                                <td class="pr-4 align-middle text-center">
                                    <div class="actions">
                                        <a class="action-icon edit" title="Edit" href="{{ route('admin.notification-templates.edit', $template->id) }}">
                                            <i class="far fa-edit"></i>
                                        </a>

                                        <form action="{{ route('admin.notification-templates.destroy', $template->id) }}" method="POST" class="delete-form d-inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="action-icon delete btn-delete" title="Delete">
                                                <i class="far fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">No notification templates found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function () {
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            let form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Show success alert if flash message exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        @endif
    });
</script>
<style>
    .truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endsection
