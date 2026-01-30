@extends('layouts.admin')
@section('content')
<style>
    .applicants-page {
        background: #f9fafb;
        min-height: 100vh;
margin-top:20px;
margin-bottom:10px;
    }

    .page-header {
        background: #fff;
        border-radius: 12px;
        padding: 20px 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .header-top {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
    }

    .header-info {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
    }

    .info-item {
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .info-item i {
        color: #6b7280;
        font-size: 18px;
        margin-top: 2px;
    }

    .info-content {
        display: flex;
        flex-direction: column;
    }

    .info-label {
        font-size: 12px;
        color: #6b7280;
        font-weight: 500;
        margin-bottom: 2px;
    }

    .info-value {
        font-size: 14px;
        color: #111827;
        font-weight: 500;
    }

    .export-btn {
        background: #000;
        color: #fff;
        border: none;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .export-btn:hover {
        background: #1f2937;
        transform: translateY(-1px);
    }

    .applicants-section {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .section-title {
        font-size: 18px;
        font-weight: 600;
        color: #111827;
        margin-bottom: 20px;
    }

    .applicants-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
    }

    .applicant-card {
        display: flex;
        align-items: center;
        padding: 20px;
        border: 1px solid #e5e7eb;
        border-radius: 12px;
        background: #fff;
        transition: all 0.2s;
        gap: 16px;
    }

    .applicant-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        border-color: #d1d5db;
    }

    .applicant-avatar {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .applicant-info {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .applicant-name-row {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .applicant-name {
        font-size: 15px;
        font-weight: 600;
        color: #111827;
    }

    .star-rating {
        display: inline-flex;
        align-items: center;
        gap: 2px;
    }

    .star-rating i {
        color: #fbbf24;
        font-size: 13px;
    }

    .rating-value {
        font-size: 14px;
        font-weight: 600;
        color: #111827;
        margin-left: 4px;
    }

    .applicant-details {
        font-size: 13px;
        color: #6b7280;
        line-height: 1.5;
    }

    .detail-separator {
        margin: 0 4px;
    }

    .applicant-applied {
        font-size: 13px;
        color: #6b7280;
    }

    .applicant-right {
        display: flex;
        align-items: center;
        gap: 12px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 16px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .status-pending {
        background: #fef3c7;
        color: #d97706;
    }

    .status-accepted {
        background: #d1fae5;
        color: #065f46;
    }

    .status-rejected {
        background: #fee2e2;
        color: #dc2626;
    }

    .status-shortlisted {
        background: #fef3c7;
        color: #d97706;
    }

    .applicant-actions {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    .action-btn {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 500;
        border: 1px solid;
        cursor: pointer;
        transition: all 0.2s;
        background: #fff;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .action-btn.accept-btn {
        color: #059669;
        border-color: #059669;
    }

    .action-btn.accept-btn:hover {
        background: #d1fae5;
    }

    .action-btn.reject-btn {
        color: #dc2626;
        border-color: #dc2626;
    }

    .action-btn.reject-btn:hover {
        background: #fee2e2;
    }

    .more-actions-btn {
        padding: 8px;
        background: transparent;
        border: none;
        cursor: pointer;
        color: #6b7280;
        font-size: 16px;
        transition: all 0.2s;
        position: relative;
    }

    .more-actions-btn:hover {
        color: #111827;
    }

    .actions-dropdown-container {
        position: relative;
        display: inline-block;
    }

    .actions-dropdown-menu {
        position: absolute;
        right: 0;
        top: calc(100% + 4px);
        background: #fff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        min-width: 180px;
        display: none;
        overflow: hidden;
    }

    .actions-dropdown-menu.active {
        display: block;
        animation: dropdownFade 0.2s ease;
    }

    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .actions-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: #374151;
        text-decoration: none;
        transition: background 0.15s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }

    .actions-dropdown-item:hover {
        background: #f9fafb;
        color: #111827;
    }

    .actions-dropdown-item.text-danger {
        color: #dc2626;
    }

    .actions-dropdown-item.text-danger:hover {
        background: #fef2f2;
    }

    .actions-dropdown-item.text-success {
        color: #059669;
    }

    .actions-dropdown-item.text-success:hover {
        background: #d1fae5;
    }

    .actions-dropdown-item.text-primary {
        color: #2563eb;
    }

    .actions-dropdown-item.text-primary:hover {
        background: #eff6ff;
    }
</style>

@php
    $resolveMediaUrl = function ($path) {
        if (! $path) {
            return null;
        }

        if (is_array($path)) {
            $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
        }

        if (! $path) {
            return null;
        }

        $isAbsolute = \Illuminate\Support\Str::startsWith($path, ['http://', 'https://', '//']);
        $awsUrl = rtrim((string) env('AWS_URL'), '/');
        $storage = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'));

        if ($isAbsolute) {
            if ($awsUrl && \Illuminate\Support\Str::startsWith($path, $awsUrl)) {
                $relative = ltrim(\Illuminate\Support\Str::after($path, $awsUrl), '/');
                try {
                    return $storage->temporaryUrl($relative, now()->addMinutes(60));
                } catch (\Exception $e) {
                    try {
                        return $storage->url($relative);
                    } catch (\Exception $e2) {
                        return $path;
                    }
                }
            }

            return $path;
        }

        $clean = ltrim($path, '/');
        try {
            return $storage->url($clean);
        } catch (\Exception $e) {
            try {
                return $storage->temporaryUrl($clean, now()->addMinutes(60));
            } catch (\Exception $e2) {
                return null;
            }
        }
    };
@endphp

<div class="applicants-page">
    <div class="page-header">
        <div class="header-top">
            <div class="header-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <div class="info-content">
                        <span class="info-label">Location</span>
                        <span class="info-value">{{ $castingRequirement->location ?? 'Not specified' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-calendar-alt"></i>
                    <div class="info-content">
                        <span class="info-label">Date</span>
                        <span class="info-value">{{ $castingRequirement->shoot_date ? $castingRequirement->shoot_date->format('d M Y') : 'Not specified' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <div class="info-content">
                        <span class="info-label">Time & Duration</span>
                        <span class="info-value">{{ $castingRequirement->shoot_time ?? '00:00' }} • {{ $castingRequirement->duration ?? 'N/A' }}</span>
                    </div>
                </div>
                <div class="info-item">
                    <i class="fas fa-users"></i>
                    <div class="info-content">
                        <span class="info-label">Applicants</span>
                        <span class="info-value">{{ $applications->count() }} Application{{ $applications->count() !== 1 ? 's' : '' }}</span>
                    </div>
                </div>
            </div>
            <button class="export-btn" onclick="exportApplicants()">
                <i class="fas fa-download"></i>
                Export All
            </button>
        </div>
    </div>

    @if(session('message'))
        <div class="alert alert-success mb-3">{{ session('message') }}</div>
    @endif

    <div class="applicants-section">
        @if($applications->isEmpty())
            <div class="alert alert-info">{{ trans('global.no_applicants_found') }}</div>
        @else
            <div class="section-title">Applicants ({{ $applications->count() }})</div>

            <div class="applicants-list">
                @foreach($applications as $application)
                @php
                    $profile = $application->talent_profile;
                    $user = $profile?->user;
                    $displayName = $profile?->display_name ?? $profile?->legal_name ?? $user?->name ?? trans('global.not_set');

                    $avatarFallback = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><rect width="120" height="120" rx="60" fill="#e5e7eb"/><circle cx="60" cy="50" r="26" fill="#9ca3af"/><rect x="24" y="82" width="72" height="22" rx="11" fill="#d1d5db"/></svg>');

                    $mediaPhoto = $profile?->media ? $profile->media->first() : null;
                    $avatarRaw = $mediaPhoto ? $mediaPhoto->file_path : ($profile?->headshot_center_path ?? ($profile?->headshot_left_path ?? $profile?->headshot_right_path));
                    $avatarSrc = $resolveMediaUrl($avatarRaw) ?: $avatarFallback;

                    // Status Logic
                    $statusKey = $application->status;
                    $statusLabel = App\Models\CastingApplication::STATUS_SELECT[$statusKey] ?? ucfirst($statusKey);

                    if ($statusKey === 'did_not_show') {
                        $statusLabel = 'Pending';
                    }

                    // Map status to classes
                    $badgeClass = 'status-pending';
                    if ($statusKey === 'selected') $badgeClass = 'status-accepted';
                    if ($statusKey === 'rejected') $badgeClass = 'status-rejected';
                    if ($statusKey === 'shortlisted') $badgeClass = 'status-shortlisted';

                    // Get applicant details
                    $age = $profile?->date_of_birth ? $profile->date_of_birth->age . ' years' : 'N/A';
                    $height = $profile?->height ? $profile->height . '"' : 'N/A';
                    $experience = $profile?->experience_years ? $profile->experience_years . ' years experience' : 'No experience listed';

                    // Applied date
                    $appliedDate = $application->created_at ? $application->created_at->format('d M Y') : 'N/A';


                @endphp

                <div class="applicant-card">
                    <img src="{{ $avatarSrc }}" alt="{{ $displayName }}" class="applicant-avatar">

                    <div class="applicant-info">
                        <div class="applicant-name-row">
                            <span class="applicant-name">{{ $displayName }}</span>

                        </div>

                        <div class="applicant-details">
                            {{ $age }}<span class="detail-separator">•</span>{{ $height }}<span class="detail-separator">•</span>{{ $experience }}
                        </div>

                        <div class="applicant-applied">
                            Applied on {{ $appliedDate }}
                        </div>
                    </div>

                    <div class="applicant-right">
                        <span class="status-badge {{ $badgeClass }}">{{ $statusLabel }}</span>

                        <div class="applicant-actions">
                            @if($application->status !== 'selected')
                                <button class="action-btn accept-btn"
                                    data-toggle="modal"
                                    data-target="#approveApplicationModal"
                                    data-route="{{ route('admin.casting-applications.approve', $application) }}"
                                    data-name="{{ $displayName }}">
                                    <i class="fas fa-check"></i> Accept
                                </button>
                            @endif

                            @if($application->status !== 'rejected')
                                <button class="action-btn reject-btn"
                                    data-toggle="modal"
                                    data-target="#rejectApplicationModal"
                                    data-route="{{ route('admin.casting-applications.reject', $application) }}"
                                    data-name="{{ $displayName }}">
                                    <i class="fas fa-times"></i> Reject
                                </button>
                            @endif

                            <div class="actions-dropdown-container">
                                <button class="more-actions-btn dropdown-toggle-btn">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="actions-dropdown-menu">
                                    <a href="{{ route('admin.talent-profiles.show', $profile->id) }}" class="actions-dropdown-item">
                                        <i class="fas fa-eye"></i> View Profile
                                    </a>

                                    @if($application->status !== 'shortlisted' && $application->status !== 'selected')
                                        <form action="{{ route('admin.casting-applications.shortlist', $application) }}" method="POST" style="margin:0;">
                                            @csrf
                                            <button type="submit" class="actions-dropdown-item text-primary">
                                                <i class="fas fa-list"></i> Shortlist
                                            </button>
                                        </form>
                                    @endif

                                    @if($application->status === 'selected')
                                        @php
                                            $paymentPending = in_array($application->payment_status, ['pending', 'rejected', 'requested']);
                                            $isRequest = $application->payment_status === 'requested';
                                            $byAdmin = isset($application->requestedByAdmin);
                                            $isSuper = auth('admin')->user()->isSuperAdmin();
                                            $showPayment = $paymentPending && !($isRequest && $byAdmin && !$isSuper);
                                        @endphp
                                        @if($showPayment)
                                            <button class="actions-dropdown-item"
                                                data-toggle="modal"
                                                data-target="#requestPaymentModal"
                                                data-route="{{ route('admin.casting-applications.request-payment', $application) }}"
                                                data-name="{{ $displayName }}"
                                                data-rating="{{ is_numeric($application->rating) ? (int) $application->rating : 5 }}">
                                                <i class="fas fa-dollar-sign"></i> Request Payment
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
            </div>
        @endif
    </div>
</div>

@include('admin.castingRequirements.partials.application-modals')
@endsection

@section('scripts')
@parent
<script>
    function exportApplicants() {
        // Create CSV content
        const applications = @json($applications);
        let csv = 'Name,Age,Height,Experience,Applied On,Status\n';

        applications.forEach(app => {
            const profile = app.talent_profile;
            const name = profile?.display_name || profile?.legal_name || 'N/A';
            const age = profile?.date_of_birth ? calculateAge(profile.date_of_birth) : 'N/A';
            const height = profile?.height || 'N/A';
            const experience = profile?.experience_years ? profile.experience_years + ' years' : 'N/A';
            const appliedOn = app.created_at ? new Date(app.created_at).toLocaleDateString() : 'N/A';
            const status = app.status || 'N/A';

            csv += `"${name}","${age}","${height}","${experience}","${appliedOn}","${status}"\n`;
        });

        // Download CSV
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'applicants_export_' + new Date().getTime() + '.csv';
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

    function calculateAge(birthDate) {
        const birth = new Date(birthDate);
        const today = new Date();
        let age = today.getFullYear() - birth.getFullYear();
        const monthDiff = today.getMonth() - birth.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        return age + ' years';
    }

    document.addEventListener('DOMContentLoaded', function () {
        // Dropdown toggle logic
        const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');
        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const container = this.closest('.actions-dropdown-container');
                const menu = container.querySelector('.actions-dropdown-menu');

                // Close other menus
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });

                menu.classList.toggle('active');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.actions-dropdown-container')) {
                document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
                    menu.classList.remove('active');
                });
            }
        });

        // Modal Scripts
        const applicationWhatsappTemplates = {
            selected: @json(trans('notifications.whatsapp_template_application_selected', ['name' => ':name', 'project' => ':project'])),
            rejected: @json(trans('notifications.whatsapp_template_application_rejected', ['name' => ':name', 'project' => ':project'])),
        };

        $('#approveApplicationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var route = button.data('route')
            var name = button.data('name')
            var modal = $(this)
            modal.find('form').attr('action', route)
            modal.find('textarea[name="admin_notes"]').val('')
            modal.find('.modal-title').text(name ? name + ' {{ trans('notifications.approval_modal_title') }}' : '{{ trans('notifications.approval_modal_default_title') }}')
        })

        $('#rejectApplicationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var route = button.data('route')
            var name = button.data('name')
            var modal = $(this)
            modal.find('form').attr('action', route)
            modal.find('textarea[name="admin_notes"]').val('')
            modal.find('.modal-title').text(name ? name + ' {{ trans('notifications.rejection_modal_title') }}' : '{{ trans('notifications.rejection_modal_default_title') }}')
        })

        $(document).on('show.bs.modal', '#applicationWhatsAppModal', function (event) {
            var button = $(event.relatedTarget)
            var number = button.data('number') || ''
            var name = button.data('name') || ''
            var project = button.data('project') || ''
            var status = button.data('status') || 'selected'
            var template = applicationWhatsappTemplates[status] || applicationWhatsappTemplates['selected']
            var message = template.replace(':name', name).replace(':project', project)
            $(this).find('#application_whatsapp_message').val(message)
            $('#sendApplicationWhatsApp').data('phone', number)
        })

        $(document).on('click', '#sendApplicationWhatsApp', function () {
            var phone = $(this).data('phone')
            if (!phone) {
                alert('{{ trans('notifications.whatsapp_number_missing') }}')
                return
            }
            var message = $('#application_whatsapp_message').val() || ''
            var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message)
            window.open(url, '_blank')
        })

        const updateStarClasses = function(container, value) {
            container.find('label').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= value)
            })
        }

        $('#requestPaymentModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget)
            const route = button.data('route')
            const name = button.data('name') || ''
            const rating = button.data('rating') || 5
            const modal = $(this)
            const starContainer = modal.find('[data-star-rating]')
            modal.find('form').attr('action', route)
            starContainer.find('input').prop('checked', false)
            starContainer.find(`input[value="${rating}"]`).prop('checked', true)
            updateStarClasses(starContainer, rating)
            modal.find('.modal-title').text(name ? `{{ __('Request Payment') }} — ${name}` : '{{ __('Request Payment') }}')
        })

        $(document).on('click', '[data-star-rating] label', function () {
            const label = $(this)
            const container = label.closest('[data-star-rating]')
            const value = label.data('value')
            container.find(`input[value="${value}"]`).prop('checked', true).trigger('change')
            updateStarClasses(container, value)
        })
    });
</script>
@endsection
