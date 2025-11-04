@extends('layouts.admin')

@php
    use Illuminate\Support\Str;

    $tabs = [
        'all' => 'All',
        'pending' => 'Pending',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'available' => 'Available',
    ];

    $placeholderSvg = <<<'SVG'
    <svg xmlns="http://www.w3.org/2000/svg" width="160" height="160" viewBox="0 0 160 160"><defs><linearGradient id="g" x1="50%" x2="50%" y1="0%" y2="100%"><stop offset="0%" stop-color="#edf1f8"/><stop offset="100%" stop-color="#dee5f1"/></linearGradient></defs><rect width="160" height="160" rx="16" fill="url(#g)"/><circle cx="80" cy="60" r="34" fill="#cbd5e1"/><path d="M80 96c-30 0-54 20-54 44v12h108v-12c0-24-24-44-54-44z" fill="#d2dae7"/></svg>
    SVG;
    $fallbackAvatar = 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($placeholderSvg);
@endphp

@section('content')
    <div class="talents-page">
        <div class="page-head d-flex align-items-center justify-content-between">
            <h2 class="mb-0" style="color: #111">Talent</h2>
        </div>

        <!-- Top tabs -->
        <div class="tabs-wrap">
            <ul class="nav nav-underline status-tabs" id="statusTabs">
                @foreach ($tabs as $key => $label)
                    <li class="nav-item">
                        <a class="nav-link{{ $loop->first ? ' active' : '' }}" data-status="{{ $key }}" href="#">
                            {{ $label }}
                            <span class="nav-count">{{ $stats[$key] ?? 0 }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>

        <!-- Search -->
        <div class="search-wrap">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="tableSearch" placeholder="Search">
            </div>
        </div>

        <!-- Talents Table card -->
        <div class="card talents-card" id="talentsView">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="talentsTable">
                        <thead>
                            <tr>
                                <th style="width:20%">{{ __('Talent') }}</th>
                                <th style="width:12%">{{ __('Gender') }}</th>
                                <th style="width:12%">{{ __('Height') }}</th>
                                <th style="width:12%">{{ __('Weight') }}</th>
                                <th style="width:12%">{{ __('Status') }}</th>
                                <th style="width:15%">{{ __('Date Joined') }}</th>
                                <th style="width:17%">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($talents as $talent)
                                @php
                                    $name = $talent->display_name ?: $talent->legal_name;
                                    $languages = $talent->languages->pluck('title')->filter()->values();
                                    $languageLabel = $languages->isNotEmpty()
                                        ? $languages->take(2)->implode(', ')
                                        : trans('global.not_set');
                                    $genderKey = $talent->gender ?? null;
                                    $genderLabel = $genderKey ? trans('global.gender_display.' . $genderKey) : null;
                                    if ($genderLabel && $genderLabel === 'global.gender_display.' . $genderKey) {
                                        $genderLabel = ucfirst($genderKey);
                                    }
                                    $heightLabel =
                                        $talent->height !== null
                                            ? rtrim(rtrim(number_format($talent->height, 1), '0'), '.') . ' cm'
                                            : '—';
                                    $weightLabel =
                                        $talent->weight !== null
                                            ? rtrim(rtrim(number_format($talent->weight, 1), '0'), '.') . ' kg'
                                            : '—';
                                    $photoUrl =
                                        collect([
                                            $talent->headshot_center_path,
                                            $talent->headshot_left_path,
                                            $talent->headshot_right_path,
                                            $talent->full_body_front_path,
                                            $talent->full_body_right_path,
                                            $talent->full_body_back_path,
                                        ])
                                            ->filter()
                                            ->first() ?? $fallbackAvatar;

                                    // Ensure proper photo URL
                                    if ($photoUrl && $photoUrl !== $fallbackAvatar) {
                                        if (!str_starts_with($photoUrl, 'http') && !str_starts_with($photoUrl, '/storage/')) {
                                            $photoUrl = \Storage::url($photoUrl);
                                        }
                                    }

                                    $status = $talent->verification_status ?? 'pending';
                                    $statusLabel =
                                        \App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$status] ?? ucfirst($status);
                                    $joinedAt = optional($talent->created_at)->format('d/m/Y') ?? '—';
                                    $keywords = Str::lower(
                                        trim(
                                            implode(' ', [
                                                $name,
                                                $languageLabel,
                                                $genderLabel,
                                                $status,
                                                $talent->user->name ?? '',
                                                optional($talent->user)->email ?? '',
                                            ]),
                                        ),
                                    );
                                @endphp
                                <tr data-status="{{ $status }}" data-search="{{ $keywords }}">
                                    <td>
                                        <div class="talent-info">
                                            <img src="{{ $photoUrl }}" alt="{{ $name }}" class="talent-photo" />
                                            <span class="talent-name">{{ $name }}
                                                @if ($status === 'approved')
                                                    <i class="fas fa-check-circle text-success ms-1" title="Verified"></i>
                                                @endif
                                            </span>
                                        </div>
                                    </td>
                                    <td>{{ $genderLabel ?? '—' }}</td>
                                    <td>{{ $heightLabel }}</td>
                                    <td>{{ $weightLabel }}</td>
                                    <td>
                                        <span class="badge status-badge {{ $status === 'approved' ? 'status-approved' : ($status === 'rejected' ? 'status-rejected' : 'status-pending') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>{{ $joinedAt }}</td>
                                    <td>
                                        <div class="action-icons">
                                            <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="action-btn view-btn" title="View Profile">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if($status === 'pending')
                                                <button class="action-btn approve-btn" title="Approve Talent" data-talent-id="{{ $talent->id }}" data-talent-name="{{ $name }}">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                                <button class="action-btn reject-btn" title="Reject Talent" data-talent-id="{{ $talent->id }}" data-talent-name="{{ $name }}">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">{{ trans('global.no_talents_found') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Talents Grid View (for Available section) -->
        <div class="talent-grid-container" id="talentGridView" style="display: none;">
            <div class="talent-grid">
                @foreach($talents as $talent)
                    @php
                        $name = $talent->display_name ?: $talent->legal_name;
                        $languages = $talent->languages->pluck('title')->filter()->values();
                        $languageLabel = $languages->isNotEmpty()
                            ? $languages->take(2)->implode(', ')
                            : trans('global.not_set');
                        $genderKey = $talent->gender ?? null;
                        $genderLabel = $genderKey ? trans('global.gender_display.' . $genderKey) : null;
                        if ($genderLabel && $genderLabel === 'global.gender_display.' . $genderKey) {
                            $genderLabel = ucfirst($genderKey);
                        }
                        $heightLabel =
                            $talent->height !== null
                                ? rtrim(rtrim(number_format($talent->height, 1), '0'), '.') . ' cm'
                                : '—';
                        $weightLabel =
                            $talent->weight !== null
                                ? rtrim(rtrim(number_format($talent->weight, 1), '0'), '.') . ' kg'
                                : '—';
                        $photoUrl =
                            collect([
                                $talent->headshot_center_path,
                                $talent->headshot_left_path,
                                $talent->headshot_right_path,
                                $talent->full_body_front_path,
                                $talent->full_body_right_path,
                                $talent->full_body_back_path,
                            ])
                                ->filter()
                                ->first() ?? $fallbackAvatar;

                        // Ensure proper photo URL
                        if ($photoUrl && $photoUrl !== $fallbackAvatar) {
                            if (!str_starts_with($photoUrl, 'http') && !str_starts_with($photoUrl, '/storage/')) {
                                $photoUrl = \Storage::url($photoUrl);
                            }
                        }

                        $status = $talent->verification_status ?? 'pending';
                        $keywords = Str::lower(
                            trim(
                                implode(' ', [
                                    $name,
                                    $languageLabel,
                                    $genderLabel,
                                    $status,
                                    $talent->user->name ?? '',
                                    optional($talent->user)->email ?? '',
                                ]),
                            ),
                        );
                    @endphp
                    <div class="talent-card" data-status="{{ $status }}" data-search="{{ $keywords }}">
                        <div class="talent-card-photo">
                            <img src="{{ $photoUrl }}" alt="{{ $name }}">
                        </div>
                        <div class="talent-card-content">
                            <h4 class="talent-card-name">{{ $name }}</h4>
                            <p class="talent-card-gender">{{ $genderLabel ?? 'Not specified' }}</p>
                            <div class="talent-card-stats">
                                <span class="stat">{{ $heightLabel }}</span>
                                <span class="stat">{{ $weightLabel }}</span>
                            </div>
                        </div>
                    </div>
                @endforeach
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
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .status-tabs .nav-link .nav-count {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 20px;
            padding: 0 8px;
            border-radius: 999px;
            background: #f1f4fa;
            color: #6c7a94;
            font-size: 12px;
        }

        .status-tabs .nav-link.active {
            color: #111;
            border-bottom: 2px solid #111;
        }

        .status-tabs .nav-link.active .nav-count {
            background: #111;
            color: #fff;
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

        .talents-card {
            border-radius: 12px;
            overflow: hidden;
        }

        .table thead th {
            background: #fff;
            border-bottom: 1px solid #edf0f2;
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 15px;
        }

        .table tbody td {
            padding: 15px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        .talent-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .talent-photo {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            object-fit: cover;
            border: 2px solid #f0f0f0;
        }

        .talent-name {
            font-weight: 600;
            color: #333;
        }

        .status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.status-approved {
            background-color: #d4edda;
            color: #155724;
        }

        .status-badge.status-rejected {
            background-color: #f8d7da;
            color: #721c24;
        }

        .status-badge.status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .action-icons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }

        .action-btn.view-btn {
            background-color: #e3f2fd;
            color: #1976d2;
        }

        .action-btn.view-btn:hover {
            background-color: #bbdefb;
            color: #1976d2;
        }

        .action-btn.approve-btn {
            background-color: #e8f5e8;
            color: #2e7d32;
        }

        .action-btn.approve-btn:hover {
            background-color: #c8e6c9;
        }

        .action-btn.reject-btn {
            background-color: #ffebee;
            color: #c62828;
        }

                .action-btn.reject-btn:hover {
            background-color: #ffcdd2;
        }

        /* Grid View Styles */
        .talent-grid-container {
            margin-top: 20px;
        }

        .talent-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 20px;
            padding: 0;
        }

        .talent-card {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            cursor: pointer;
        }

        .talent-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }

        .talent-card-photo {
            position: relative;
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .talent-card-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .talent-card-content {
            padding: 15px;
        }

        .talent-card-name {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .talent-card-gender {
            font-size: 14px;
            color: #666;
            margin: 0 0 10px 0;
        }

        .talent-card-stats {
            display: flex;
            gap: 10px;
        }

        .talent-card-stats .stat {
            font-size: 12px;
            color: #888;
            background: #f5f5f5;
            padding: 4px 8px;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .talent-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
                gap: 15px;
            }
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = Array.from(document.querySelectorAll('#statusTabs .nav-link'));
            const rows = Array.from(document.querySelectorAll('#talentsTable tbody tr'));
            const cards = Array.from(document.querySelectorAll('.talent-card'));
            const searchInput = document.getElementById('tableSearch');
            const tableView = document.getElementById('talentsView');
            const gridView = document.getElementById('talentGridView');

            let activeFilter = 'all';

            function applyFilters() {
                const q = (searchInput?.value || '').trim().toLowerCase();
                const isAvailableTab = activeFilter === 'available';

                // Toggle between table and grid view
                if (isAvailableTab) {
                    tableView.style.display = 'none';
                    gridView.style.display = 'block';
                } else {
                    tableView.style.display = 'block';
                    gridView.style.display = 'none';
                }

                // Filter table rows
                rows.forEach(tr => {
                    const textMatch = q === '' || tr.getAttribute('data-search').includes(q);
                    const status = tr.getAttribute('data-status');
                    const statusMatch = activeFilter === 'all' || status === activeFilter;

                    tr.style.display = (textMatch && statusMatch && !isAvailableTab) ? '' : 'none';
                });

                // Filter grid cards (only show approved talents in available section)
                cards.forEach(card => {
                    const textMatch = q === '' || card.getAttribute('data-search').includes(q);
                    const status = card.getAttribute('data-status');
                    const showInGrid = isAvailableTab && status === 'approved' && textMatch;

                    card.style.display = showInGrid ? 'block' : 'none';
                });
            }            // Tab functionality
            tabs.forEach(tab => {
                tab.addEventListener('click', (e) => {
                    e.preventDefault();
                    tabs.forEach(t => t.classList.remove('active'));
                    tab.classList.add('active');
                    activeFilter = tab.dataset.status || 'all';
                    applyFilters();
                });
            });

            // Search functionality
            if (searchInput) {
                searchInput.addEventListener('input', applyFilters);
            }

            applyFilters();

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

            // --- Approve/Reject functionality ---
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('approve-btn') || e.target.closest('.approve-btn')) {
                    const btn = e.target.classList.contains('approve-btn') ? e.target : e.target.closest('.approve-btn');
                    updateTalentStatus(btn, 'approve', 'Approve Talent');
                }

                if (e.target.classList.contains('reject-btn') || e.target.closest('.reject-btn')) {
                    const btn = e.target.classList.contains('reject-btn') ? e.target : e.target.closest('.reject-btn');
                    updateTalentStatus(btn, 'reject', 'Reject Talent');
                }
            });

            function updateTalentStatus(btn, action, actionTitle) {
                const talentId = btn.dataset.talentId;
                const talentName = btn.dataset.talentName;
                const isApprove = action === 'approve';

                // Show input dialog for admin notes
                Swal.fire({
                    title: actionTitle,
                    html: `
                        <p>Are you sure you want to ${action} <strong>${talentName}</strong>?</p>
                        <div class="mt-3">
                            <label class="form-label fw-bold">Notes for talent ${isApprove ? '(optional)' : '(required)'}:</label>
                            <textarea id="adminNotes" class="form-control" rows="3" placeholder="Add any additional notes or feedback for the talent..."></textarea>
                            <small class="text-muted">These notes will be included in the email and WhatsApp notification to the talent.</small>
                        </div>
                    `,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: isApprove ? '#28a745' : '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: `Yes, ${isApprove ? 'Approve' : 'Reject'}!`,
                    cancelButtonText: 'Cancel',
                    preConfirm: () => {
                        const notes = document.getElementById('adminNotes').value;
                        if (!isApprove && !notes.trim()) {
                            Swal.showValidationMessage('Notes are required for rejection');
                            return false;
                        }
                        return notes;
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Create and submit form
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = `/admin/talent-profiles/${talentId}/${action}`;

                        const tokenInput = document.createElement('input');
                        tokenInput.type = 'hidden';
                        tokenInput.name = '_token';
                        tokenInput.value = '{{ csrf_token() }}';

                        const notesInput = document.createElement('input');
                        notesInput.type = 'hidden';
                        notesInput.name = 'notes';
                        notesInput.value = result.value || '';

                        form.appendChild(tokenInput);
                        form.appendChild(notesInput);
                        document.body.appendChild(form);
                        form.submit();
                    }
                });
            }
        });
    </script>
@endsection
