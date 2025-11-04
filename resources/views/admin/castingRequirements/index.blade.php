@extends('layouts.admin')

@section('content')
    <div class="projects-page">
        <div class="page-head d-flex align-items-center justify-content-between">
            <h2 class="mb-0" style="color: #111">Projects</h2>
        </div>

        <!-- Top tabs -->
        <div class="tabs-wrap">
            <ul class="nav nav-underline status-tabs" id="statusTabs">
                <li class="nav-item"><a class="nav-link active" data-status="all" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link" data-status="open" href="#">Open Project</a></li>
                <li class="nav-item"><a class="nav-link" data-status="close" href="#">Closed Project</a></li>
            </ul>
        </div>

        <!-- Search -->
        <div class="search-wrap">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="tableSearch" placeholder="Search">
            </div>
        </div>

        <!-- Chip tabs under search -->
        <div class="chip-tabs mb-3">
            <a href="#" class="chip active" data-view="projects">Project</a>
            @can('casting_requirement_create')
                <a href="{{ route('admin.casting-requirements.create') }}" class="chip">New casting request</a>
            @endcan
            <a href="#" class="chip" data-view="approvals">Approvals</a>
        </div>

        <!-- Projects Table card -->
        <div class="card projects-card" id="projectsView">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="projectsTable">
                        <thead>
                            <tr>
                                <th style="width:15%">Project</th>
                                <th style="width:15%">Post Date</th>
                                <th style="width:15%">End Date</th>
                                <th style="width:15%">Location</th>
                                <th style="width:15%">Pay Range</th>
                                <th style="width:10%">Applicants</th>
                                <th style="width:10%">Status</th>
                                <th style="width:5%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($castingRequirements as $cr)
                                @php
                                    $statusKey = $cr->status;
                                    $postDate = optional($cr->created_at)->format('d/m/Y');
                                    $endDate = $cr->end_date ?? $cr->shoot_date_time;
                                    $endDateFmt = '';
                                    if ($endDate) {
                                        try {
                                            if (is_string($endDate) && preg_match('/^\d{2}\/\d{2}\/\d{4}/', $endDate)) {
                                                $endDateFmt = explode(' ', $endDate)[0];
                                            } else {
                                                $endDateFmt = \Carbon\Carbon::parse($endDate)->format('d/m/Y');
                                            }
                                        } catch (\Exception $e) {
                                            $endDateFmt = '—';
                                        }
                                    }
                                    $rate = $cr->rate_per_model ? number_format($cr->rate_per_model, 0) : '—';
                                @endphp
                                <tr data-status="{{ $statusKey }}"
                                    data-search="{{ strtolower(trim($cr->project_name . ' ' . $postDate . ' ' . $endDateFmt . ' ' . ($cr->location ?? '') . ' ' . $rate)) }}">
                                    <td class="project-title"><span class="text-dark fw-600">{{ $cr->project_name }}</span>
                                    </td>
                                    <td>{{ $postDate }}</td>
                                    <td>{{ $endDateFmt }}</td>
                                    <td>{{ $cr->location ?? '—' }}</td>
                                    <td>{{ $rate === '—' ? '—' : $rate }}</td>
                                    <td>{{ $cr->applicants_count ?? 0 }}</td>
                                    <td>
                                        @php
                                            // Use the CastingRequirement STATUS_SELECT mapping
                                            $statusMapping = \App\Models\CastingRequirement::STATUS_SELECT;
                                            $statusLabel = $statusMapping[$statusKey] ?? ucfirst($statusKey);

                                            // Status badge classes
                                            $statusClasses = [
                                                'Advertised' => 'status-advertised',
                                                'Processing' => 'status-processing',
                                                'Completed' => 'status-completed'
                                            ];

                                            $statusClass = $statusClasses[$statusLabel] ?? 'status-default';
                                        @endphp
                                        <span class="badge status-badge {{ $statusClass }}">
                                            {{ $statusLabel }}
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <div class="menu-wrap">
                                            <button class="kebab-btn" type="button" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="menu">
                                                @can('casting_requirement_show')
                                                    <a href="{{ route('admin.casting-requirements.show', $cr->id) }}"
                                                        class="menu-item">{{ trans('global.view') }}</a>
                                                @endcan
                                                @can('casting_requirement_edit')
                                                    <a href="{{ route('admin.casting-requirements.edit', $cr->id) }}"
                                                        class="menu-item">{{ trans('global.edit') }}</a>
                                                @endcan
                                                @can('casting_requirement_delete')
                                                    <button type="button" class="menu-item text-danger w-100 text-left delete-btn"
                                                        data-id="{{ $cr->id }}"
                                                        data-name="{{ $cr->project_name }}">
                                                        {{ trans('global.delete') }}
                                                    </button>
                                                @endcan
                                            </div>
                                        </div>
                                    </td>

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Approvals View -->
        <div class="card projects-card" id="approvalsView" style="display: none;">
            <div class="card-body p-0">

                <div class="table-responsive">
                    <table class="table approval-table" id="approvalsTable">
                        <thead>
                            <tr>
                                <th>Talent</th>
                                <th>Project</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($castingRequirements as $cr)
                                @foreach ($cr->castingApplications ?? [] as $application)
                                    @php
                                        $talent = $application->talent_profile;
                                        $user = $talent->user ?? null;
                                        $name = $user ? $user->name : ($talent->display_name ?? $talent->legal_name ?? 'Unknown');

                                        // Get talent photo
                                        $photoPath = collect([
                                            $talent->headshot_center_path,
                                            $talent->headshot_left_path,
                                            $talent->headshot_right_path,
                                            $talent->full_body_front_path,
                                        ])->filter()->first();

                                        if ($photoPath) {
                                            // Check if it's already a full URL or just a path
                                            $avatar = (str_starts_with($photoPath, 'http') || str_starts_with($photoPath, '/storage/'))
                                                ? $photoPath
                                                : asset('storage/' . $photoPath);
                                        } else {
                                            $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=f0f0f0&color=666&size=40';
                                        }

                                        $status = $application->status ?? 'applied';
                                        $projectStatus = $cr->status ?? 'open';
                                    @endphp

                                    <tr data-application-id="{{ $application->id }}" data-approval-status="{{ $status }}" data-project-status="{{ $projectStatus }}"
                                        data-search="{{ strtolower(trim($name . ' ' . $cr->project_name . ' ' . $status)) }}">
                                        <td>
                                            <div class="talent-info">
                                                <img src="{{ $avatar }}" alt="{{ $name }}" class="talent-photo" />
                                                <span class="talent-name">{{ $name }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="project-name">{{ $cr->project_name }}</span>
                                        </td>
                                        <td>
                                            @if($status === 'selected')
                                                <span class="status-badge status-approved">Approved</span>
                                            @elseif($status === 'rejected')
                                                <span class="status-badge status-rejected">Rejected</span>
                                            @else
                                                <span class="status-badge status-applied">Applied</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="action-icons">
                                                <button class="action-btn view-btn" title="View Profile" data-application-id="{{ $application->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                @if($status !== 'selected')
                                                    <button class="action-btn approve-btn" title="Approve" data-application-id="{{ $application->id }}">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                @endif
                                                @if($status !== 'rejected')
                                                    <button class="action-btn reject-btn" title="Reject" data-application-id="{{ $application->id }}">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <style>


        .page-head h2 {
            font-weight: 700;
        }

        .status-tabs {
            margin-top: 8px;
            border-bottom: 1px solid #e9ecef;
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

        /* Chip tabs under search */
        .chip-tabs {
            display: flex;
            gap: 8px;
        }

        .chip {
            display: inline-block;
            padding: 6px 14px;
            background-color: #f2f2f2;
            border-radius: 20px;
            font-size: 14px;
            color: #333;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .chip:hover {
            background-color: #e0e0e0;
            color: #333;
            text-decoration: none;
        }

        .chip.active {
            background-color: #e0e0e0;
            font-weight: 600;
        }

        .projects-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: #fff;
            border-bottom: 1px solid #edf0f2;
            font-weight: 700;
            color: #6b7280;
        }

        .table tbody td {
            vertical-align: middle;
        }

        .project-title {
            font-weight: 600;
        }

        .status-badge {
            border-radius: 999px;
            padding: 6px 12px;
            text-transform: lowercase;
            font-weight: 700;
        }

        .status-badge.status-advertised {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
        }

        .status-badge.status-processing {
            background: #fff3cd;
            color: #664d03;
            border: 1px solid #ffecb5;
        }

        .status-badge.status-completed {
            background: #cff4fc;
            color: #055160;
            border: 1px solid #9eeaf9;
        }

        .status-badge.status-default {
            background: #f8f9fa;
            color: #6c757d;
            border: 1px solid #dee2e6;
        }

        /* Dropdown */
        .menu-wrap {
            position: relative;
            display: inline-block;
        }

        .kebab-btn {
            border: 0;
            background: transparent;
            padding: 4px 6px;
            color: #6b7280;
            border-radius: 8px;
        }

        .kebab-btn:hover {
            color: #111;
        }

        .menu {
            min-width: 160px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            box-shadow: 0 10px 22px rgba(0, 0, 0, .08);
            display: none;
            z-index: 9999;
        }

        .menu.show {
            display: block;
        }

        .menu-item {
            display: block;
            padding: 8px 12px;
            color: #111;
            text-decoration: none;
            font-weight: 500;
            background: #fff;
            transition: background-color 0.2s;
        }

        .menu-item:hover {
            background-color: #f0f0f0;
            color: #111;
            text-decoration: none;
        }

        .menu form {
            margin: 0;
        }

        .text-left {
            text-align: left;
        }

        .w-100 {
            width: 100%;
        }

        /* Approvals Table Styles - Matching Exact Design */
        .approval-table {
            border-collapse: separate;
            border-spacing: 0;
        }

        .approval-table thead th {
            background: transparent;
            color: #6c757d;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            padding: 12px 16px;
            border-top: none;
            border-bottom: 1px solid #dee2e6;
        }

        .approval-table thead th:first-child {
            border-left: 1px solid #dee2e6;
            border-top-left-radius: 8px;
        }

        .approval-table thead th:last-child {
            border-right: 1px solid #dee2e6;
            border-top-right-radius: 8px;
        }

        .approval-table tbody td {
            padding: 12px 16px;
            vertical-align: middle;
            border-bottom: 1px solid #f1f3f4;
            background: white;
        }

        .approval-table tbody tr:hover td {
            background: #f8f9fa;
        }

        /* Talent Info */
        .talent-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .talent-photo {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #f1f3f4;
        }

        .talent-name {
            font-weight: 600;
            color: #1a1a1a;
            font-size: 14px;
        }

        .project-name {
            font-weight: 500;
            color: #1a1a1a;
            font-size: 14px;
        }

        /* Status Badges - Exact Colors from Image */
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 500;
            text-transform: capitalize;
        }

        .status-badge.status-approved {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
        }

        .status-badge.status-rejected {
            background: #f8d7da;
            color: #842029;
            border: 1px solid #f1aeb5;
        }

        .status-badge.status-applied {
            background: #cff4fc;
            color: #055160;
            border: 1px solid #9eeaf9;
        }

        /* Action Icons - Matching Image Design */
        .action-icons {
            display: flex;
            gap: 6px;
            align-items: center;
        }

        .action-btn {
            width: 24px;
            height: 24px;
            border-radius: 4px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 11px;
        }

        .action-btn.view-btn {
            background: transparent;
            color: #6c757d;
        }

        .action-btn.view-btn:hover {
            background: #f8f9fa;
            color: #495057;
        }

        .action-btn.approve-btn {
            background: transparent;
            color: #198754;
        }

        .action-btn.approve-btn:hover {
            background: #d1e7dd;
            color: #0f5132;
        }

        .action-btn.reject-btn {
            background: transparent;
            color: #dc3545;
        }

        .action-btn.reject-btn:hover {
            background: #f8d7da;
            color: #721c24;
        }

        /* View toggle styles */
        .chip[data-view] {
            cursor: pointer;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const search = document.getElementById('tableSearch');
            const rows = Array.from(document.querySelectorAll('#projectsTable tbody tr'));
            const body = document.body;

            // --- Show SweetAlert for session messages ---
            @if(session('message'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('message') }}',
                    confirmButtonColor: '#198754',
                    timer: 3000,
                    timerProgressBar: true
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc3545'
                });
            @endif

            // --- Delete with SweetAlert ---
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('delete-btn') || e.target.closest('.delete-btn')) {
                    const btn = e.target.classList.contains('delete-btn') ? e.target : e.target.closest('.delete-btn');
                    const id = btn.dataset.id;
                    const name = btn.dataset.name;

                    Swal.fire({
                        title: 'Are you sure?',
                        html: `You are about to delete <strong>${name}</strong>.<br>This action cannot be undone!`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Create and submit form
                            const form = document.createElement('form');
                            form.method = 'POST';
                            form.action = '{{ route('admin.casting-requirements.index') }}/' + id;

                            const methodInput = document.createElement('input');
                            methodInput.type = 'hidden';
                            methodInput.name = '_method';
                            methodInput.value = 'DELETE';

                            const tokenInput = document.createElement('input');
                            tokenInput.type = 'hidden';
                            tokenInput.name = '_token';
                            tokenInput.value = '{{ csrf_token() }}';

                            form.appendChild(methodInput);
                            form.appendChild(tokenInput);
                            document.body.appendChild(form);
                            form.submit();
                        }
                    });
                }
            });

            // --- Filters ---
            function applyFilters() {
                const q = (search.value || '').trim().toLowerCase();
                const activeTab = document.querySelector('#statusTabs .nav-link.active')?.dataset.status || 'all';
                rows.forEach(tr => {
                    const textMatch = tr.getAttribute('data-search').includes(q);
                    const status = tr.getAttribute('data-status');
                    const statusMatch = activeTab === 'all' ? true : (status === activeTab);
                    tr.style.display = (textMatch && statusMatch) ? '' : 'none';
                });
            }

            search.addEventListener('input', applyFilters);
            document.querySelectorAll('#statusTabs .nav-link').forEach(a => {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('#statusTabs .nav-link').forEach(x => x.classList.remove('active'));
                    a.classList.add('active');
                    applyFilters();
                });
            });

            // --- Dropdown toggle logic ---
            document.addEventListener('click', function (e) {
                const btn = e.target.closest('.kebab-btn');
                const openMenu = document.querySelector('.menu.show');

                // If clicking same button again → close menu
                if (btn && openMenu && openMenu.dataset.btnId === btn.dataset.id) {
                    openMenu.classList.remove('show');
                    return;
                }

                // Close existing open menus
                document.querySelectorAll('.menu.show').forEach(menu => {
                    menu.classList.remove('show');
                });

                if (btn) {
                    e.stopPropagation();
                    const menu = btn.parentElement.querySelector('.menu');
                    const rect = btn.getBoundingClientRect();
                    body.appendChild(menu);
                    menu.dataset.btnId = btn.dataset.id = Math.random().toString(36).substr(2, 9);
                    menu.classList.add('show');
                    menu.style.position = 'absolute';
                    menu.style.top = rect.bottom + window.scrollY + 'px';
                    menu.style.left = (rect.right - menu.offsetWidth) + window.scrollX + 'px';
                    return;
                }

                // Click outside closes all
                if (!e.target.closest('.menu')) {
                    document.querySelectorAll('.menu.show').forEach(menu => menu.classList.remove('show'));
                }
            });


            // --- View switching functionality ---
            document.addEventListener('click', function(e) {
                const viewChip = e.target.closest('[data-view]');
                if (viewChip && !viewChip.href.includes('create')) {
                    e.preventDefault();

                    // Update active chip
                    document.querySelectorAll('[data-view]').forEach(chip => {
                        chip.classList.remove('active');
                    });
                    viewChip.classList.add('active');

                    // Switch views and update page heading
                    const view = viewChip.dataset.view;
                    const pageHeading = document.querySelector('.page-head h2');

                    if (view === 'projects') {
                        document.getElementById('projectsView').style.display = 'block';
                        document.getElementById('approvalsView').style.display = 'none';
                        pageHeading.textContent = 'Projects';
                    } else if (view === 'approvals') {
                        document.getElementById('projectsView').style.display = 'none';
                        document.getElementById('approvalsView').style.display = 'block';
                        pageHeading.textContent = 'Approvals';
                    }
                }
            });

            // --- Approval filters ---
            const approvalRows = Array.from(document.querySelectorAll('#approvalsTable tbody tr'));

            function applyApprovalFilters() {
                const q = (search.value || '').trim().toLowerCase();
                const activeTab = document.querySelector('#approvalTabs .nav-link.active')?.dataset.status || 'all';

                approvalRows.forEach(tr => {
                    const textMatch = tr.getAttribute('data-search').includes(q);
                    const projectStatus = tr.getAttribute('data-project-status');
                    const statusMatch = activeTab === 'all' ? true :
                                      (activeTab === 'open' && projectStatus === 'open') ||
                                      (activeTab === 'closed' && projectStatus === 'close');
                    tr.style.display = (textMatch && statusMatch) ? '' : 'none';
                });
            }

            // Approval tabs functionality
            document.querySelectorAll('#approvalTabs .nav-link').forEach(a => {
                a.addEventListener('click', (e) => {
                    e.preventDefault();
                    document.querySelectorAll('#approvalTabs .nav-link').forEach(x => x.classList.remove('active'));
                    a.classList.add('active');
                    applyApprovalFilters();
                });
            });

            // Update search to work with approvals too
            const originalApplyFilters = applyFilters;
            applyFilters = function() {
                originalApplyFilters();
                applyApprovalFilters();
            };

            // --- Approve/Reject functionality ---
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('approve-btn') || e.target.closest('.approve-btn')) {
                    const btn = e.target.classList.contains('approve-btn') ? e.target : e.target.closest('.approve-btn');
                    updateApplicationStatus(btn, 'selected', 'Approve Application');
                }

                if (e.target.classList.contains('reject-btn') || e.target.closest('.reject-btn')) {
                    const btn = e.target.classList.contains('reject-btn') ? e.target : e.target.closest('.reject-btn');
                    updateApplicationStatus(btn, 'rejected', 'Reject Application');
                }
            });

            function updateApplicationStatus(btn, status, action) {
                const row = btn.closest('tr');
                const applicationId = btn.dataset.applicationId || row.dataset.applicationId;
                const talentName = row.querySelector('.talent-name').textContent.trim();
                const actionText = status === 'selected' ? 'approve' : 'reject';
                const confirmText = status === 'selected' ? 'Approve' : 'Reject';

                // Show input dialog for admin notes
                Swal.fire({
                    title: `${action}`,
                    html: `
                        <p>Are you sure you want to ${actionText} <strong>${talentName}</strong>?</p>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Notes for talent (optional):</label>
                            <textarea id="adminNotes" class="form-control" rows="3" placeholder="Add any additional notes or feedback for the talent..."></textarea>
                            <small class="text-muted">These notes will be included in the email and WhatsApp notification to the talent.</small>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: status === 'selected' ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Yes, ${confirmText}!`,
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        return document.getElementById('adminNotes').value;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '/admin/casting-applications/' + applicationId + '/update-status';

                        const methodInput = document.createElement('input');
                        methodInput.type = 'hidden';
                        methodInput.name = '_method';
                        methodInput.value = 'PATCH';

                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = '{{ csrf_token() }}';

                        const statusInput = document.createElement('input');
                        statusInput.type = 'hidden';
                        statusInput.name = 'status';
                        statusInput.value = status;

                        const notesInput = document.createElement('input');
                        notesInput.type = 'hidden';
                        notesInput.name = 'admin_notes';
                        notesInput.value = result.value || '';

                        form.appendChild(methodInput);
                        form.appendChild(tokenInput);
                        form.appendChild(statusInput);
                        form.appendChild(notesInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }

            applyFilters();
        })();
    </script>
@endsection
