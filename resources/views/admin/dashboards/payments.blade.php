@extends('layouts.admin')

@section('content')
    <div class="payments-page">
        <div class="page-head d-flex align-items-center justify-content-between">
            <h2 class="mb-0" style="color: #111">Payments</h2>
        </div>

        <!-- Top tabs -->
        <div class="tabs-wrap">
            <ul class="nav nav-underline status-tabs" id="statusTabs">
                <li class="nav-item">
                    <a class="nav-link active" data-status="all" href="#">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-status="pending" href="#">Pending</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-status="released" href="#">Released</a>
                </li>
            </ul>
        </div>

        <!-- Search -->
        <div class="search-wrap">
            <div class="search-input">
                <i class="fas fa-search"></i>
                <input type="text" id="tableSearch" placeholder="Search">
            </div>
        </div>
        <!-- Payments Table card -->
        <div class="card payments-card" id="paymentsView">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="paymentsTable">
                        <thead>
                            <tr>
                                <th style="width:15%">Project</th>
                                <th style="width:20%">Talent</th>
                                <th style="width:15%">Amount</th>
                                <th style="width:15%">History</th>
                                <th style="width:15%">Status</th>
                                <th style="width:20%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($payments ?? [] as $payment)
                                @php
                                    $talent = $payment->talent_profile ?? null;
                                    $project = $payment->casting_requirement ?? null;
                                    $talentName = $talent ? ($talent->display_name ?: $talent->legal_name) : 'Unknown Talent';
                                    $projectName = $project ? $project->project_name : 'Unknown Project';

                                    // Get talent photo
                                    $talentPhoto = null;
                                    if ($talent) {
                                        $photoPath = collect([
                                            $talent->headshot_center_path,
                                            $talent->headshot_left_path,
                                            $talent->headshot_right_path,
                                            $talent->full_body_front_path,
                                        ])->filter()->first();

                                        if ($photoPath) {
                                            $talentPhoto = (str_starts_with($photoPath, 'http') || str_starts_with($photoPath, '/storage/'))
                                                ? $photoPath
                                                : \Storage::url($photoPath);
                                        }
                                    }

                                    if (!$talentPhoto) {
                                        $talentPhoto = 'https://ui-avatars.com/api/?name=' . urlencode($talentName) . '&background=f0f0f0&color=666&size=40';
                                    }

                                    // Determine payment amount
                                    $amount = $payment->rate_offered ?? $payment->rate ?? 0;
                                    $formattedAmount = '$' . number_format($amount, 0);

                    // Determine payment status
                    $paymentStatus = $payment->payment_processed ?? 'pending';

                    // Handle null, empty, or 'n/a' values
                    if (empty($paymentStatus) || $paymentStatus === 'n/a' || is_null($paymentStatus)) {
                        $paymentStatus = 'pending';
                    }

                    // Normalize status for filtering
                    $filterStatus = $paymentStatus;
                    if (in_array($paymentStatus, ['no', 'pending', 'n/a', null, ''])) {
                        $filterStatus = 'pending';
                    } elseif (in_array($paymentStatus, ['requested'])) {
                        $filterStatus = 'pending'; // Show requested items in pending tab
                    } elseif (in_array($paymentStatus, ['yes', 'released'])) {
                        $filterStatus = 'released';
                    }                                    // Create search data
                                    $searchData = strtolower($talentName . ' ' . $projectName . ' ' . $formattedAmount . ' ' . $paymentStatus);
                                @endphp
                                <tr data-status="{{ $filterStatus }}" data-search="{{ $searchData }}" data-original-status="{{ $paymentStatus }}">
                                    <td>
                                        <span class="project-name">{{ $projectName }}</span>
                                    </td>
                                    <td>
                                        <div class="talent-info">
                                            <img src="{{ $talentPhoto }}" alt="{{ $talentName }}" class="talent-photo" />
                                            <span class="talent-name">{{ $talentName }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="amount">{{ $formattedAmount }}</span>
                                    </td>
                                    <td>
                                        <span class="history-text">
                                            {{ $payment->created_at ? $payment->created_at->format('M d, Y') : '-' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge status-badge status-{{ $paymentStatus }}">
                                            {{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            @if(auth()->user()->is_super_admin)
                                                @if($paymentStatus === 'pending' || $paymentStatus === 'no')
                                                    <button class="btn btn-primary btn-sm request-payment-btn"
                                                            data-payment-id="{{ $payment->id }}"
                                                            data-talent-name="{{ $talentName }}"
                                                            data-amount="{{ $formattedAmount }}">
                                                        Process Payment
                                                    </button>
                                                @elseif($paymentStatus === 'requested')
                                                    <button class="btn btn-success btn-sm approve-payment-btn"
                                                            data-payment-id="{{ $payment->id }}"
                                                            data-talent-name="{{ $talentName }}"
                                                            data-amount="{{ $formattedAmount }}">
                                                        Approve Payment
                                                    </button>
                                                @elseif($paymentStatus === 'yes' || $paymentStatus === 'released')
                                                    <span class="text-success">Released</span>
                                                @else
                                                    <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}</span>
                                                @endif
                                            @else
                                                @if($paymentStatus === 'pending' || $paymentStatus === 'no')
                                                    <span class="text-info">Contact Super Admin for Payment Processing</span>
                                                @elseif($paymentStatus === 'requested')
                                                    <span class="text-warning">Awaiting Super Admin Approval</span>
                                                @elseif($paymentStatus === 'yes' || $paymentStatus === 'released')
                                                    <span class="text-success">Released</span>
                                                @else
                                                    <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $paymentStatus)) }}</span>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No payments found</td>
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

        .payments-card {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

        .project-name {
            font-weight: 500;
            color: #333;
        }

        .amount {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .history-text {
            color: #666;
            font-size: 14px;
        }

        .status-badge {
            font-size: 11px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 12px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .status-badge.status-pending {
            background-color: #fff3cd;
            color: #856404;
        }

        .status-badge.status-requested {
            background-color: #cce5ff;
            color: #0066cc;
        }

        .status-badge.status-released {
            background-color: #d4edda;
            color: #155724;
        }

        .action-buttons {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .request-payment-btn {
            padding: 6px 12px;
            font-size: 12px;
            border-radius: 6px;
            border: none;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .request-payment-btn:hover {
            background-color: #0056b3;
        }

        .text-muted {
            color: #6c757d !important;
            font-size: 14px;
        }

        .text-success {
            color: #28a745 !important;
            font-weight: 600;
            font-size: 14px;
        }
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = Array.from(document.querySelectorAll('#statusTabs .nav-link'));
            const rows = Array.from(document.querySelectorAll('#paymentsTable tbody tr'));
            const searchInput = document.getElementById('tableSearch');

            let activeFilter = 'all';

            function applyFilters() {
                const q = (searchInput?.value || '').trim().toLowerCase();

                rows.forEach(tr => {
                    // Skip empty rows (like "No payments found")
                    if (!tr.getAttribute('data-status')) {
                        return;
                    }

                    const textMatch = q === '' || tr.getAttribute('data-search').includes(q);
                    const status = tr.getAttribute('data-status');
                    let statusMatch = false;

                    // Debug logging (temporary)
                    if (activeFilter !== 'all') {
                        console.log(`Row status: "${status}", Active filter: "${activeFilter}", Match: ${status === activeFilter}`);
                    }

                    if (activeFilter === 'all') {
                        statusMatch = true;
                    } else if (activeFilter === 'pending') {
                        statusMatch = status === 'pending';
                    } else if (activeFilter === 'released') {
                        statusMatch = status === 'released';
                    }

                    tr.style.display = (textMatch && statusMatch) ? '' : 'none';
                });
            }

            // Tab functionality
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

            // Payment functionality
            document.addEventListener('click', function(e) {
                // Handle payment processing for Super Admin
                if (e.target.classList.contains('request-payment-btn')) {
                    const btn = e.target;
                    const paymentId = btn.dataset.paymentId;
                    const talentName = btn.dataset.talentName;
                    const amount = btn.dataset.amount;

                    Swal.fire({
                        title: 'Process Payment',
                        html: `
                            <p>Are you sure you want to process payment for <strong>${talentName}</strong>?</p>
                            <div class="mt-3">
                                <div class="mb-2"><strong>Amount:</strong> ${amount}</div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Processing Notes (optional):</label>
                                    <textarea id="paymentNotes" class="form-control" rows="3" placeholder="Add any notes for payment processing..."></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#007bff',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Process Payment!',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            return document.getElementById('paymentNotes').value;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processPayment(paymentId, result.value, btn, talentName, 'requested');
                        }
                    });
                }

                // Handle payment approval for Super Admin
                if (e.target.classList.contains('approve-payment-btn')) {
                    const btn = e.target;
                    const paymentId = btn.dataset.paymentId;
                    const talentName = btn.dataset.talentName;
                    const amount = btn.dataset.amount;

                    Swal.fire({
                        title: 'Approve Payment',
                        html: `
                            <p>Are you sure you want to approve and release payment for <strong>${talentName}</strong>?</p>
                            <div class="mt-3">
                                <div class="mb-2"><strong>Amount:</strong> ${amount}</div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Approval Notes (optional):</label>
                                    <textarea id="paymentNotes" class="form-control" rows="3" placeholder="Add any notes for payment approval..."></textarea>
                                </div>
                            </div>
                        `,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Approve & Release!',
                        cancelButtonText: 'Cancel',
                        preConfirm: () => {
                            return document.getElementById('paymentNotes').value;
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            processPayment(paymentId, result.value, btn, talentName, 'released');
                        }
                    });
                }
            });

            function processPayment(paymentId, notes, btn, talentName, newStatus) {
                fetch('{{ route('admin.payments.request') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        payment_id: paymentId,
                        notes: notes,
                        action: newStatus
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update button state for better UX
                        const row = btn.closest('tr');

                        if (newStatus === 'released') {
                            btn.outerHTML = '<span class="text-success">Released</span>';
                            row.setAttribute('data-status', 'released');
                            row.setAttribute('data-original-status', 'released');

                            // Update status badge
                            const statusBadge = row.querySelector('.status-badge');
                            statusBadge.className = 'badge status-badge status-released';
                            statusBadge.textContent = 'Released';
                        } else {
                            btn.textContent = 'Requested';
                            btn.className = 'btn btn-success btn-sm approve-payment-btn';
                            btn.setAttribute('data-payment-id', paymentId);
                            btn.setAttribute('data-talent-name', talentName);
                            row.setAttribute('data-status', 'pending');
                            row.setAttribute('data-original-status', 'requested');

                            // Update status badge
                            const statusBadge = row.querySelector('.status-badge');
                            statusBadge.className = 'badge status-badge status-requested';
                            statusBadge.textContent = 'Requested';
                        }

                        const successTitle = newStatus === 'released' ? 'Payment Released!' : 'Payment Requested!';
                        const successMessage = newStatus === 'released' ?
                            `Payment for ${talentName} has been approved and released.` :
                            `Payment request for ${talentName} has been submitted.`;

                        Swal.fire({
                            icon: 'success',
                            title: successTitle,
                            text: data.message || successMessage,
                            confirmButtonColor: '#007bff',
                            timer: 3000,
                            timerProgressBar: true
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Operation Failed!',
                            text: data.message || 'Failed to process payment. Please try again.',
                            confirmButtonColor: '#dc3545'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Network Error!',
                        text: 'Failed to process payment. Please check your connection and try again.',
                        confirmButtonColor: '#dc3545'
                    });
                });
            }

            applyFilters();
        });
    </script>
@endsection
