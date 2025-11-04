@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $placeholderSvg = <<<'SVG'
<svg xmlns="http://www.w3.org/2000/svg" width="120" height="120" viewBox="0 0 120 120"><defs><linearGradient id="g" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#f2f4f8"/><stop offset="100%" stop-color="#e2e8f0"/></linearGradient></defs><rect width="120" height="120" rx="20" fill="url(#g)"/><circle cx="60" cy="48" r="26" fill="#cbd5e1"/><path d="M60 72c-24 0-42 16-42 36v8h84v-8c0-20-18-36-42-36z" fill="#d9e2ef"/></svg>
SVG;
    $avatarFallback = 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($placeholderSvg);
@endphp

@section('content')
    <div class="projects-page approvals-page">
        <div class="page-head d-flex align-items-start align-items-md-center justify-content-between flex-column flex-md-row gap-3">
            <div>
                <h2 class="mb-1">Approvals</h2>
                <p class="page-subtitle mb-0">Applicants for {{ $castingRequirement->project_name }}</p>
            </div>
            <a href="{{ route('admin.casting-requirements.index') }}" class="btn btn-outline-dark btn-sm px-3">
                <i class="fas fa-arrow-left mr-1"></i> Back to projects
            </a>
        </div>

        {{--  <div class="tabs-wrap">
            <ul class="nav nav-underline status-tabs" id="applicationStatusTabs">
                <li class="nav-item"><a class="nav-link active" data-status="all" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link" data-status="selected" href="#">Open Project</a></li>
                <li class="nav-item"><a class="nav-link" data-status="rejected" href="#">Closed Project</a></li>
            </ul>
        </div>

        <div class="search-wrap">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="applicantsSearch" placeholder="Search">
            </div>
        </div>  --}}

        

        @if ($applications->isEmpty())
            <div class="approvals-empty">
                <p class="mb-1">{{ trans('global.no_applicants_found') }}</p>
                <span class="text-muted">Applicants will appear here once talent submits interest.</span>
            </div>
        @else
            <div class="card approvals-card">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table approvals-table mb-0" id="approvalsTable">
                            <thead>
                                <tr>
                                    <th>Talent</th>
                                    <th>Project</th>
                                    <th>Status</th>
                                    <th class="text-right">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($applications as $application)
                                    @php
                                        $talent = $application->talent_profile;
                                        $name = $talent?->display_name ?? $talent?->legal_name ?? trans('global.not_set');
                                        $statusKey = $application->status ?? 'pending';
                                        $statusLabel = \App\Models\CastingApplication::STATUS_SELECT[$statusKey] ?? ucfirst($statusKey);
                                        $photoUrl = collect([
                                            optional($talent)->headshot_center_path,
                                            optional($talent)->headshot_left_path,
                                            optional($talent)->headshot_right_path,
                                            optional($talent)->full_body_front_path,
                                            optional($talent)->full_body_right_path,
                                            optional($talent)->full_body_back_path,
                                        ])->filter()->first() ?? $avatarFallback;

                                        $genderLabel = null;
                                        if ($talent?->gender) {
                                            $genderLabel = trans('global.gender_display.' . $talent->gender);
                                            if ($genderLabel === 'global.gender_display.' . $talent->gender) {
                                                $genderLabel = ucfirst($talent->gender);
                                            }
                                        }

                                        $talentMeta = implode(' · ', array_filter([
                                            $genderLabel,
                                            $talent?->height ? rtrim(rtrim(number_format($talent->height, 1), '0'), '.') . ' cm' : null,
                                            $talent?->weight ? rtrim(rtrim(number_format($talent->weight, 1), '0'), '.') . ' kg' : null,
                                        ]));

                                        $keywords = Str::lower(trim(implode(' ', [
                                            $name,
                                            $talentMeta,
                                            $statusLabel,
                                            $castingRequirement->project_name,
                                            $application->talent_notes ?? '',
                                            $application->admin_notes ?? '',
                                        ])));
                                    @endphp
                                    <tr data-role="application-row"
                                        data-status="{{ $statusKey }}"
                                        data-keywords="{{ $keywords }}">
                                        <td>
                                            <div class="talent-cell">
                                                <span class="talent-avatar">
                                                    <img src="{{ $photoUrl }}" alt="{{ $name }}">
                                                </span>
                                                <span class="talent-meta">
                                                    <span class="talent-name">{{ $name }}</span>
                                                    <span class="talent-sub">{{ $talentMeta ?: trans('global.not_set') }}</span>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="project-name">{{ $castingRequirement->project_name }}</span>
                                        </td>
                                        <td>
                                            <span class="status-pill status-pill--{{ $statusKey }}">
                                                {{ $statusLabel }}
                                            </span>
                                        </td>
                                        <td class="approvals-actions text-right">
                                            <div class="actions-stack">
                                                @if ($application->status !== 'selected')
                                                    <button class="action-btn action-btn--approve"
                                                            type="button"
                                                            title="{{ trans('global.approve') }}"
                                                            data-toggle="modal"
                                                            data-target="#approveApplicationModal"
                                                            data-route="{{ route('admin.casting-applications.approve', $application) }}"
                                                            data-name="{{ $name }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if ($application->status !== 'rejected')
                                                    <button class="action-btn action-btn--reject"
                                                            type="button"
                                                            title="{{ trans('global.reject') }}"
                                                            data-toggle="modal"
                                                            data-target="#rejectApplicationModal"
                                                            data-route="{{ route('admin.casting-applications.reject', $application) }}"
                                                            data-name="{{ $name }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                                @if ($application->status === 'selected' && $application->payment_processed !== 'paid')
                                                    <form method="POST"
                                                          action="{{ route('admin.casting-applications.pay', $application) }}"
                                                          class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                                class="action-btn action-btn--pay"
                                                                title="{{ trans('global.pay_now') }}">
                                                            <i class="fas fa-dollar-sign"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                                @if (optional($talent)->whatsapp_number)
                                                    <button class="action-btn action-btn--whatsapp application-whatsapp-button"
                                                            type="button"
                                                            title="{{ trans('notifications.send_whatsapp') }}"
                                                            data-toggle="modal"
                                                            data-target="#applicationWhatsAppModal"
                                                            data-number="{{ $talent->whatsapp_number }}"
                                                            data-name="{{ $name }}"
                                                            data-project="{{ $castingRequirement->project_name }}"
                                                            data-status="{{ $application->status }}">
                                                        <i class="fab fa-whatsapp"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="table-empty-info d-none" id="approvalsEmptyNotice">
                        <p class="mb-0">{{ trans('global.no_applicants_found') }}</p>
                    </div>
                </div>
            </div>
        @endif
    </div>

    @include('admin.castingRequirements.partials.application-modals')
@endsection

@section('styles')
    @parent
    <style>
        .approvals-page {
            padding: 8px 10px 20px;
        }

        .approvals-page h2 {
            font-weight: 700;
            color: #111;
        }

        .page-subtitle {
            color: #6c757d;
            font-size: 14px;
        }

        .status-tabs {
            margin-top: 8px;
            border-bottom: 1px solid #e9ecef;
            gap: 6px;
        }

        .status-tabs .nav-link {
            color: #6c757d;
            padding: 6px 10px;
            font-weight: 600;
            border: 0;
        }

        .status-tabs .nav-link.active {
            color: #111;
            border-bottom: 2px solid #111;
        }

        .search-wrap {
            margin: 10px 0 6px;
        }

        .search-input {
            position: relative;
            background: #f3f5f7;
            border-radius: 10px;
            padding: 8px 12px 8px 36px;
        }

        .search-input i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
        }

        .search-input input {
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
        }

        .chip-tabs {
            display: flex;
            gap: 8px;
            align-items: center;
        }

        .chip {
            display: inline-flex;
            align-items: center;
            padding: 6px 14px;
            background-color: #f2f2f2;
            border-radius: 20px;
            font-size: 14px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
        }

        .chip.active {
            background-color: #111;
            color: #fff;
        }

        .approvals-card {
            border: 1px solid #eef1f6;
            border-radius: 16px;
            box-shadow: 0 24px 60px -36px rgba(15, 23, 42, 0.18);
        }

        .approvals-table thead th {
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-size: 13px;
            font-weight: 700;
            color: #9aa3b2;
            border-bottom: 1px solid #eef2f8;
            padding: 16px 20px;
        }

        .approvals-table tbody td {
            padding: 18px 20px;
            vertical-align: middle;
            border-top: 1px solid #f1f4f9;
            font-size: 14px;
            color: #3f4552;
        }

        .talent-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .talent-avatar {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            overflow: hidden;
            background: #eef2f8;
            border: 1px solid #e2e8f0;
            flex-shrink: 0;
        }

        .talent-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .talent-meta {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }

        .talent-name {
            font-weight: 600;
            color: #111827;
        }

        .talent-sub {
            font-size: 12px;
            color: #9aa3b2;
        }

        .project-name {
            font-weight: 500;
            color: #111827;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 6px 16px;
            border-radius: 999px;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .status-pill--selected {
            background: rgba(34, 197, 94, 0.15);
            color: #15803d;
        }

        .status-pill--rejected {
            background: rgba(239, 68, 68, 0.15);
            color: #b91c1c;
        }

        .status-pill--pending,
        .status-pill--applied,
        .status-pill--shortlisted {
            background: rgba(234, 179, 8, 0.18);
            color: #92400e;
        }

        .status-pill--hired {
            background: rgba(59, 130, 246, 0.16);
            color: #1d4ed8;
        }

        .actions-stack {
            display: inline-flex;
            gap: 6px;
        }

        .action-btn {
            width: 34px;
            height: 34px;
            border-radius: 10px;
            border: none;
            background: #f3f5f7;
            color: #4b5563;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: background 0.18s ease, color 0.18s ease;
        }

        .action-btn:hover {
            background: #111;
            color: #fff;
        }

        .action-btn--approve {
            color: #16a34a;
        }

        .action-btn--approve:hover {
            background: #16a34a;
            color: #fff;
        }

        .action-btn--reject {
            color: #dc2626;
        }

        .action-btn--reject:hover {
            background: #dc2626;
            color: #fff;
        }

        .action-btn--pay {
            color: #2563eb;
        }

        .action-btn--pay:hover {
            background: #2563eb;
            color: #fff;
        }

        .action-btn--whatsapp {
            color: #22c55e;
        }

        .action-btn--whatsapp:hover {
            background: #22c55e;
            color: #fff;
        }

        .approvals-empty,
        .table-empty-info {
            border: 1px dashed #dbe0eb;
            border-radius: 16px;
            padding: 28px 16px;
            text-align: center;
            background: #f9fbfd;
            color: #6c7280;
        }

        .approvals-empty p {
            font-weight: 600;
            color: #3f4552;
        }

        @media (max-width: 768px) {
            .approvals-table {
                min-width: 640px;
            }

            .actions-stack {
                gap: 4px;
            }
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const tabs = Array.from(document.querySelectorAll('#applicationStatusTabs .nav-link'));
            const rows = Array.from(document.querySelectorAll('[data-role="application-row"]'));
            const searchInput = document.getElementById('applicantsSearch');
            const emptyNotice = document.getElementById('approvalsEmptyNotice');

            let activeFilter = tabs.find(tab => tab.classList.contains('active'))?.dataset.status || 'all';

            function applyFilters() {
                const term = (searchInput?.value || '').trim().toLowerCase();
                let visibleCount = 0;

                rows.forEach(row => {
                    const status = row.dataset.status || '';
                    const haystack = row.dataset.keywords || '';

                    const matchesStatus = activeFilter === 'all' || status === activeFilter;
                    const matchesSearch = !term || haystack.includes(term);

                    if (matchesStatus && matchesSearch) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });

                if (emptyNotice) {
                    emptyNotice.classList.toggle('d-none', visibleCount !== 0);
                }
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', function (event) {
                    event.preventDefault();
                    if (tab.dataset.status === activeFilter) {
                        return;
                    }

                    activeFilter = tab.dataset.status || 'all';
                    tabs.forEach(btn => btn.classList.toggle('active', btn === tab));
                    applyFilters();
                });
            });

            if (searchInput) {
                searchInput.addEventListener('input', () => {
                    clearTimeout(searchInput._approvalsDebounce);
                    searchInput._approvalsDebounce = setTimeout(applyFilters, 120);
                });
            }

            applyFilters();
        });

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
            if (!message.trim()) {
                alert('{{ trans('notifications.whatsapp_message_required') }}')
                return
            }

            var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message)
            window.open(url, '_blank')
        })
    </script>
@endsection
