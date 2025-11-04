@extends('layouts.admin')
@section('content')
    <div class="dashboard-page">
        <!-- Header -->
        <div class="page-head d-flex align-items-center justify-content-between">
            <h2 class="mb-0" style="color: #111; font-weight: 700;">Dashboard</h2>
            <div class="search-wrap">
                <div class="search-input">
                    <i class="fas fa-search"></i>
                    <input type="text" id="talentSearch" placeholder="Search talents...">
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row stats-cards mb-4">
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Total Models</div>
                        <div class="stat-value">{{ number_format($stats['total'] ?? 1250) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Pending Verification</div>
                        <div class="stat-value">{{ number_format($stats['pending_verification'] ?? 35) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Recent Sign-ups</div>
                        <div class="stat-value">{{ number_format($stats['recent_signups'] ?? 120) }}</div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-3">
                <div class="stat-card">
                    <div class="stat-content">
                        <div class="stat-label">Active Campaigns</div>
                        <div class="stat-value">{{ number_format($stats['active_campaigns'] ?? 5) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Talents Table -->
        <div class="card talents-table-card">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="talentsTable">
                        <thead>
                            <tr>
                                <th style="width:25%">Talent</th>
                                <th style="width:12%">Gender</th>
                                <th style="width:12%">Height</th>
                                <th style="width:12%">Weight</th>
                                <th style="width:15%">Status</th>
                                <th style="width:15%">Date Joined</th>
                                <th style="width:9%"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($talents ?? [] as $talent)
                                @php
                                    $name =
                                        optional($talent->user)->name ??
                                        ($talent->display_name ?? ($talent->legal_name ?? 'Unknown'));

                                    // Get talent photo
                                    $talentPhoto = null;
                                    if ($talent) {
                                        $photoPath = collect([
                                            $talent->headshot_center_path,
                                            $talent->headshot_left_path,
                                            $talent->headshot_right_path,
                                            $talent->full_body_front_path,
                                        ])
                                            ->filter()
                                            ->first();

                                        if ($photoPath) {
                                            $talentPhoto =
                                                str_starts_with($photoPath, 'http') ||
                                                str_starts_with($photoPath, '/storage/')
                                                    ? $photoPath
                                                    : \Storage::url($photoPath);
                                        }
                                    }

                                    if (!$talentPhoto) {
                                        $talentPhoto =
                                            'https://ui-avatars.com/api/?name=' .
                                            urlencode($name) .
                                            '&background=f0f0f0&color=666&size=40';
                                    }

                                    $status = $talent->verification_status ?? 'pending';
                                    $isVerified = in_array($status, ['approved', 'verified']);

                                    // Create searchable data string
                                    $userEmail = optional($talent->user)->email ?? '';
                                    $gender = optional($talent)->gender ?? '';
                                    $height = optional($talent)->height ?? '';
                                    $weight = optional($talent)->weight ?? '';
                                    $dateJoined = $talent && $talent->created_at ? $talent->created_at->format('d/m/Y') : '';

                                    $searchData = strtolower(trim($name . ' ' . $userEmail . ' ' . $gender . ' ' . $height . ' ' . $weight . ' ' . ($isVerified ? 'verified' : 'pending') . ' ' . $dateJoined));
                                @endphp
                                <tr data-search="{{ $searchData }}" class="talent-row">
                                    <td>
                                        <div class="talent-info">
                                            <img src="{{ $talentPhoto }}" alt="{{ $name }}" class="talent-photo" />
                                            <div class="talent-details">
                                                <div class="talent-name">
                                                    {{ $name }}
                                                    @if ($isVerified)
                                                        <i class="fas fa-check-circle verified-icon" title="Verified"></i>
                                                    @endif
                                                </div>
                                                <div class="talent-email">{{ optional($talent->user)->email ?? '' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ ucfirst($talent->gender ?? '—') }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted">{{ $talent->height ? $talent->height . ' cm' : '—' }}</span>
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted">{{ $talent->weight ? $talent->weight . ' kg' : '—' }}</span>
                                    </td>
                                    <td>
                                        @if ($isVerified)
                                            <span class="badge status-badge status-verified">Verified</span>
                                        @else
                                            <span class="badge status-badge status-pending">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span
                                            class="text-muted">{{ $talent->created_at ? $talent->created_at->format('d/m/Y') : '—' }}</span>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-link p-0 text-muted" type="button"
                                                data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fas fa-eye me-2"></i>View Profile</a></li>
                                                <li><a class="dropdown-item" href="#"><i
                                                            class="fas fa-edit me-2"></i>Edit</a></li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li><a class="dropdown-item text-danger" href="#"><i
                                                            class="fas fa-trash me-2"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No recent talents found</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    @parent
    <style>
        .page-head {
            margin-bottom: 24px;
        }

        .page-head h2 {
            font-weight: 700;
            color: #111;
            margin: 0;
        }

        /* Search Input */
        .search-wrap {
            position: relative;
            min-width: 280px;
        }

        .search-input {
            position: relative;
            background: #fff;
            border: 1px solid #e1e5e9;
            border-radius: 8px;
            padding: 8px 12px 8px 40px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .search-input i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #9aa0a6;
            font-size: 14px;
        }

        .search-input input {
            border: none;
            outline: none;
            width: 100%;
            background: transparent;
            font-size: 14px;
            color: #333;
        }

        .search-input input::placeholder {
            color: #9aa0a6;
        }

        .search-input:focus-within {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        /* Search highlighting */
        .talent-row.searching {
            opacity: 0.5;
            transition: opacity 0.2s ease;
        }

        .talent-row:not([style*="display: none"]) {
            opacity: 1;
        }

        /* Stats Cards */
        .stats-cards {
            margin-bottom: 24px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
            height: 100%;
        }

        .stat-content {
            text-align: left;
        }

        .stat-label {
            font-size: 13px;
            color: #6c757d;
            font-weight: 500;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 28px;
            font-weight: 700;
            color: #111;
            line-height: 1;
        }

        /* Table Card */
        .talents-table-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            border: 1px solid #f0f0f0;
            background: #fff;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #fff;
            border-bottom: 1px solid #edf0f2;
            font-weight: 600;
            color: #6c757d;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            padding: 16px 20px;
            border-top: none;
        }

        .table tbody td {
            padding: 16px 20px;
            vertical-align: middle;
            border-bottom: 1px solid #f8f9fa;
            border-top: none;
        }

        .table tbody tr:last-child td {
            border-bottom: none;
        }

        .table-hover tbody tr:hover {
            background-color: #f8f9fa;
        }

        /* Talent Info */
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

        .talent-details {
            flex: 1;
        }

        .talent-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
            margin-bottom: 2px;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .talent-email {
            font-size: 12px;
            color: #9aa0a6;
        }

        .verified-icon {
            color: #007bff;
            font-size: 14px;
        }

        /* Status Badges */
        .status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.status-verified {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-badge.status-pending {
            background-color: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }

        /* Text Utilities */
        .text-muted {
            color: #6c757d !important;
            font-size: 14px;
        }

        /* Dropdown Menu */
        .dropdown-toggle::after {
            display: none;
        }

        .btn-link {
            text-decoration: none;
            border: none;
            background: none;
            padding: 8px;
        }

        .btn-link:hover {
            color: #007bff;
        }

        .dropdown-menu {
            border: 1px solid #e1e5e9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            padding: 8px 0;
            min-width: 150px;
        }

        .dropdown-item {
            padding: 8px 16px;
            font-size: 13px;
            display: flex;
            align-items: center;
        }

        .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .dropdown-item i {
            width: 16px;
            text-align: center;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .page-head {
                flex-direction: column;
                gap: 16px;
                align-items: flex-start !important;
            }

            .search-wrap {
                width: 100%;
                min-width: auto;
            }

            .stats-cards {
                margin-bottom: 20px;
            }

            .stat-card {
                padding: 16px;
            }

            .table thead th,
            .table tbody td {
                padding: 12px 16px;
            }
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tooltips
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            const tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });

            // Search functionality
            const searchInput = document.getElementById('talentSearch');
            const tableRows = document.querySelectorAll('.talent-row');
            const noResultsRow = document.querySelector('#talentsTable tbody tr:not(.talent-row)');

            if (searchInput && tableRows.length > 0) {
                searchInput.addEventListener('input', function() {
                    const searchTerm = this.value.toLowerCase().trim();
                    let visibleRowsCount = 0;

                    tableRows.forEach(function(row) {
                        const searchData = row.getAttribute('data-search');

                        if (searchTerm === '' || searchData.includes(searchTerm)) {
                            row.style.display = '';
                            visibleRowsCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Show/hide no results message
                    if (noResultsRow) {
                        if (visibleRowsCount === 0 && searchTerm !== '') {
                            // Create and show "No results found" row if it doesn't exist
                            let noSearchResultsRow = document.querySelector('.no-search-results');
                            if (!noSearchResultsRow) {
                                noSearchResultsRow = document.createElement('tr');
                                noSearchResultsRow.className = 'no-search-results';
                                noSearchResultsRow.innerHTML = '<td colspan="7" class="text-center text-muted py-4">No talents found matching your search criteria</td>';
                                document.querySelector('#talentsTable tbody').appendChild(noSearchResultsRow);
                            }
                            noSearchResultsRow.style.display = '';
                            if (noResultsRow) noResultsRow.style.display = 'none';
                        } else {
                            // Hide search results message and show original no results if needed
                            const noSearchResultsRow = document.querySelector('.no-search-results');
                            if (noSearchResultsRow) {
                                noSearchResultsRow.style.display = 'none';
                            }

                            if (tableRows.length === 0 && searchTerm === '') {
                                noResultsRow.style.display = '';
                            } else {
                                noResultsRow.style.display = 'none';
                            }
                        }
                    }
                });

                // Clear search on escape key
                searchInput.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape') {
                        this.value = '';
                        this.dispatchEvent(new Event('input'));
                        this.blur();
                    }
                });
            }
        });
    </script>
@endsection
