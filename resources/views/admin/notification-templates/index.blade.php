@extends('layouts.admin')

@section('styles')
<style>
    :root {
        --notif-primary: #111827;
        --notif-accent: #3b82f6;
        --notif-success: #10b981;
        --notif-warning: #f59e0b;
        --notif-gray: #6b7280;
        --notif-bg: #f9fafb;
        --notif-card-bg: #ffffff;
        --notif-border: #f1f5f9;
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 20px;
        padding-bottom: 8px;
        border-bottom: 2px solid var(--notif-border);
    }

    .section-header h3 {
        font-size: 18px;
        font-weight: 700;
        color: var(--notif-primary);
        margin: 0;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .role-badge {
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }

    .role-talent { background: rgba(59, 130, 246, 0.1); color: #3b82f6; }
    .role-admin { background: rgba(16, 185, 129, 0.1); color: #10b981; }
    .role-creative { background: rgba(139, 92, 246, 0.1); color: #8b5cf6; }

    .template-card {
        background: var(--notif-card-bg);
        border: 1px solid var(--notif-border);
        border-radius: 12px;
        transition: all 0.2s ease;
        overflow: hidden;
    }

    .template-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        border-color: var(--notif-accent);
    }

    .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--notif-primary) !important;
        border-color: var(--notif-primary) !important;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        transition: all 0.2s;
        border: 1px solid var(--notif-border);
        background: #fff;
        color: var(--notif-gray);
    }

    .btn-action:hover {
        background: var(--notif-primary);
        color: #fff;
        border-color: var(--notif-primary);
    }

    .btn-action.edit:hover { background: var(--notif-warning); border-color: var(--notif-warning); }
    .btn-action.delete:hover { background: #ef4444; border-color: #ef4444; }

    .table thead th {
        background: #000000 !important;
        border-bottom: none;
        font-size: 12px;
        font-weight: 800;
        text-transform: uppercase;
        color: #ffffff !important;
        padding: 16px;
        letter-spacing: 0.05em;
    }

    .table td {
        padding: 16px;
        vertical-align: middle;
        border-top: 1px solid #f3f4f6;
    }

    .badge-status {
        padding: 6px 16px;
        border-radius: 50px;
        font-size: 11px;
        font-weight: 700;
    }

    .badge-active { background-color: #2fb367; color: #fff; }
    .badge-inactive { background-color: #6b7280; color: #fff; }

    .badge-lang {
        padding: 4px 10px;
        background: #f1f5f9;
        color: #64748b;
        font-weight: 700;
        font-size: 10px;
        border-radius: 4px;
        text-transform: uppercase;
    }

    .btn-action-custom {
        font-size: 16px;
        background: none;
        border: none;
        padding: 4px;
        transition: opacity 0.2s;
    }
    .btn-action-custom:hover { opacity: 0.7; }
    .btn-action-custom.edit { color: #f59e0b; }
    .btn-action-custom.delete { color: #dc2626; }

    .status-toggle { cursor: pointer; }
</style>
@endsection

@section('content')
<div class="content">
    <div class="d-flex justify-content-between align-items-center mb-4 mt-4">
        <div>
            <h2 style="font-family: 'Arimo', sans-serif; font-size: 24px; font-weight: 700; color: #000;">Manage Notifications</h2>
            <p class="text-muted mb-0">Central control for all system-generated messages across roles.</p>
        </div>
        <a class="btn btn-dark px-4 py-2" href="{{ route('admin.notification-templates.create') }}" style="border-radius: 10px; font-weight: 600; background-color: #000; border-color: #000;">
            <i class="fas fa-plus mr-2"></i> NEW NOTIFICATION
        </a>
    </div>

    @if(session('message'))
        <div class="alert alert-success border-0 shadow-sm mb-4">
            {{ session('message') }}
        </div>
    @endif

    @foreach($notificationTemplates as $role => $templates)
        <div class="mb-5">
            <div class="section-header">
                <span class="role-badge role-{{ $role }}">{{ $role }}</span>
                <h3>{{ ucfirst($role) }} Notifications</h3>
            </div>

            <div class="card border-0 shadow-sm" style="border-radius: 16px; overflow: hidden;">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>KEY / NAME</th>
                                <th>ENGLISH CONTENT</th>
                                <th class="text-right">المحتوى العربي</th>
                                <th class="text-center">LANGUAGE PREF</th>
                                <th class="text-center">STATUS</th>
                                <th class="text-center">ACTIONS</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($templates as $template)
                                <tr>
                                    <td>
                                        <div class="text-dark" style="font-weight: 500;">{{ $template->name }}</div>
                                        <div class="small text-muted">{{ $template->key }}</div>
                                    </td>
                                    <td>
                                        <div class="text-dark" style="font-weight: 500;">{{ $template->title_en }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 300px;">{{ $template->content_en }}</div>
                                    </td>
                                    <td class="text-right">
                                        <div class="text-dark" style="font-weight: 500; direction: rtl;">{{ $template->title_ar }}</div>
                                        <div class="small text-muted text-truncate" style="max-width: 300px; direction: rtl;">{{ $template->content_ar }}</div>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-lang">{{ $template->language_preference }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge-status {{ $template->is_active ? 'badge-active' : 'badge-inactive' }} toggle-active-status"
                                              data-id="{{ $template->id }}" style="cursor: pointer;">
                                            {{ $template->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center" style="gap: 12px;">
                                            <a href="{{ route('admin.notification-templates.edit', $template->id) }}" class="btn-action-custom edit" title="Edit">
                                                <i class="far fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.notification-templates.destroy', $template->id) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn-action-custom delete btn-delete-template" title="Delete">
                                                    <i class="far fa-trash-alt"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-5">
                                        <div class="text-muted">No {{ $role }} notifications configured yet.</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
</div>
@endsection

@section('scripts')
@parent
<script>
    $(function() {
        // Toggle Active Status via Badge
        $('.toggle-active-status').on('click', function() {
            const badge = $(this);
            const templateId = badge.data('id');

            $.ajax({
                url: `/admin/notification-templates/${templateId}/toggle-active`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        if (response.is_active) {
                            badge.removeClass('badge-inactive').addClass('badge-active').text('Active');
                        } else {
                            badge.removeClass('badge-active').addClass('badge-inactive').text('Inactive');
                        }
                        Toast.fire({
                            icon: 'success',
                            title: `Notification ${response.is_active ? 'enabled' : 'disabled'} successfully.`
                        });
                    }
                },
                error: function() {
                    Toast.fire({
                        icon: 'error',
                        title: 'Failed to update status.'
                    });
                }
            });
        });

        // Delete Confirmation
        $('.btn-delete-template').on('click', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');

            Swal.fire({
                title: 'Delete Notification?',
                text: "This action cannot be undone. System might fail to send messages if mandatory keys are missing.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6c7280',
                confirmButtonText: 'Yes, Delete it'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });
    });
</script>
@endsection
