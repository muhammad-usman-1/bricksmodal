@extends('layouts.admin')

@section('content')
    <div class="admins-page">
        <div class="page-head d-flex align-items-center justify-content-between">
            <h2 class="mb-0" style="color: #111">Admin</h2>
        </div>

        <!-- Top tabs -->
        <div class="tabs-wrap">
            <ul class="nav nav-underline status-tabs" id="statusTabs">
                <li class="nav-item"><a class="nav-link active" data-status="all" href="#">All</a></li>
                <li class="nav-item"><a class="nav-link" data-status="Admin" href="#">Admin</a></li>
                <li class="nav-item"><a class="nav-link" data-status="Super Admin" href="#">Super Admin</a></li>
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
            <a href="#" class="chip active" data-view="admins">Admin</a>
            <a href="{{ route('admin.admins.create') }}" class="chip">+ Add Admin</a>
        </div>

        <!-- Admins Table card -->
        <div class="card admins-card" id="adminsView">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0" id="adminsTable">
                        <thead>
                            <tr>
                                <th style="width:20%">Name</th>
                                <th style="width:25%">Email</th>
                                <th style="width:15%">Role</th>
                                <th style="width:15%">Status</th>
                                <th style="width:15%">Joined</th>
                                <th style="width:10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($admins as $admin)
                                @php
                                    $role = $admin->roles->first();
                                    $roleName = $role ? $role->title : 'No Role';
                                    $joinedDate = $admin->created_at ? $admin->created_at->format('d/m/Y') : '—';
                                    $isActive = $admin->deleted_at === null;
                                @endphp
                                <tr data-status="{{ $roleName }}"
                                    data-search="{{ strtolower(trim($admin->name . ' ' . $admin->email . ' ' . $roleName)) }}">
                                    <td class="admin-name">
                                        <span class="text-dark fw-600">{{ $admin->name }}</span>
                                    </td>
                                    <td>{{ $admin->email }}</td>
                                    <td>
                                        <span class="badge status-badge {{ $roleName === 'Super Admin' ? 'status-super-admin' : 'status-admin' }}">
                                            {{ $roleName }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($isActive)
                                            <span class="badge status-badge status-active">Active</span>
                                        @else
                                            <span class="badge status-badge status-inactive">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ $joinedDate }}</td>
                                    <td class="text-end">
                                        <div class="menu-wrap">
                                            <button class="kebab-btn" type="button" aria-haspopup="true"
                                                aria-expanded="false">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </button>
                                            <div class="menu">
                                                <a href="{{ route('admin.admins.show', $admin->id) }}"
                                                    class="menu-item">{{ trans('global.view') }}</a>
                                                <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                                    class="menu-item">{{ trans('global.edit') }}</a>
                                                @if($admin->id !== auth()->id())
                                                    <button type="button" class="menu-item text-danger w-100 text-left delete-btn"
                                                        data-id="{{ $admin->id }}"
                                                        data-name="{{ $admin->name }}">
                                                        {{ trans('global.delete') }}
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">No admins found</td>
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

        .admins-card {
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

        .admin-name {
            font-weight: 600;
        }

        .status-badge {
            border-radius: 999px;
            padding: 6px 12px;
            text-transform: lowercase;
            font-weight: 700;
        }

        .status-badge.status-super-admin {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f1aeb5;
        }

        .status-badge.status-admin {
            background: #cff4fc;
            color: #055160;
            border: 1px solid #9eeaf9;
        }

        .status-badge.status-active {
            background: #d1e7dd;
            color: #0f5132;
            border: 1px solid #a3cfbb;
        }

        .status-badge.status-inactive {
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
    </style>
@endsection

@section('scripts')
    @parent
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        (function () {
            const search = document.getElementById('tableSearch');
            const rows = Array.from(document.querySelectorAll('#adminsTable tbody tr'));
            const body = document.body;

            // --- Show SweetAlert for session messages ---
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
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
                            form.action = '{{ route('admin.admins.index') }}/' + id;

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
                    if (!tr.getAttribute('data-search')) return;
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

            applyFilters();
        })();
    </script>
@endsection
