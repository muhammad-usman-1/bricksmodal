@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f8f9fc;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --muted: #a0a3aa;
        --border: #e6e7eb;
        --shadow: 0 12px 30px rgba(15, 23, 42, 0.08);
        --badge-green: #c9f2d8;
        --badge-green-text: #2b9a50;
        --badge-red: #fee2e2;
        --badge-red-text: #dc2626;
    }

    body { background: var(--bg); }

    .outfit-shell {
        background: var(--bg);
        padding: 8px 0 18px;
    }

    .top-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
    }

    .title-block h5 {
        color: #101828;
        font-size: 24px;
        font-style: normal;
        font-weight: 400;
        line-height: 36px;
        margin-bottom: 0;
    }

    .title-block .sub {
        margin: 2px 0 0;
        color: var(--ink-500);
        font-size: 13px;
    }

    .add-btn {
        background: #2C2C2E;
        color: #fff;
        border: none;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 13px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 8px 18px rgba(0,0,0,0.14);
        text-decoration: none;
        cursor: pointer;
        font-weight: 600;
    }

    .add-btn:focus, .add-btn:active, .add-btn:hover {
        color: #fff;
        background: #2C2C2E;
        outline: none;
        text-decoration: none;
    }

    .outfit-card {
        background: var(--card);
        border-radius: 10px;
        box-shadow: var(--shadow);
        position: relative;
    }

    .outfit-table {
        width: 100%;
        border-collapse: collapse;
    }

    .outfit-table thead th {
        background: #2C2C2E;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        padding: 13px 14px;
        border: none;
        white-space: nowrap;
    }

    .outfit-table thead th:first-child { border-top-left-radius: 10px; }
    .outfit-table thead th:last-child { border-top-right-radius: 10px; }

    .outfit-table tbody td {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        color: var(--ink-700);
        vertical-align: middle;
    }

    .outfit-name {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
        color: var(--ink-900);
    }

    .logo-circle {
        width: 38px;
        height: 38px;
        border-radius: 12px;
        background: linear-gradient(135deg, #23313f, #0f1524);
        color: #fff;
        display: grid;
        place-items: center;
        font-weight: 700;
        font-size: 14px;
    }

    .outfit-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid var(--border);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-active { background: var(--badge-green); color: var(--badge-green-text); }
    .status-inactive { background: var(--badge-red); color: var(--badge-red-text); }

    .category-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: #f3f4f6;
        color: var(--ink-700);
        text-transform: capitalize;
    }

    .sub-category-badge {
        display: inline-flex;
        align-items: center;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        background: #e5e7eb;
        color: var(--ink-900);
        text-transform: capitalize;
    }

    .action-menu {
        position: relative;
        display: inline-block;
    }

    .action-toggle {
        border: 1.5px solid var(--border);
        background: #fff;
        border-radius: 10px;
        width: 38px;
        height: 38px;
        display: grid;
        place-items: center;
        color: var(--ink-700);
        cursor: pointer;
        margin-left: auto;
        transition: all 0.2s ease;
    }

    .action-toggle:hover {
        background: #fdfdfd;
        border-color: var(--ink-900);
        color: var(--ink-900);
    }

    .action-list {
        position: absolute;
        right: 0;
        top: calc(100% + 8px);
        min-width: 170px;
        background: #fff;
        border: 1px solid rgba(15, 23, 42, 0.08);
        border-radius: 14px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12), 0 0 1px rgba(0, 0, 0, 0.1);
        padding: 8px;
        display: none;
        z-index: 1000;
        transform-origin: top right;
        animation: dropFade 0.2s ease-out;
    }

    @keyframes dropFade {
        from { opacity: 0; transform: translateY(-8px) scale(0.95); }
        to { opacity: 1; transform: translateY(0) scale(1); }
    }

    .action-list.show { display: block; }

    .action-item {
        padding: 10px 12px;
        font-size: 13px;
        font-weight: 500;
        color: var(--ink-700);
        text-decoration: none !important;
        display: flex !important;
        align-items: center;
        gap: 12px;
        border-radius: 8px;
        transition: all 0.15s ease;
        border: none !important;
    }

    .action-item:hover {
        background: #f8f9fc;
        color: #000;
        transform: translateX(2px);
    }

    .action-item i {
        width: 18px;
        font-size: 14px;
        color: var(--ink-500);
        text-align: center;
    }

    .action-item:hover i {
        color: #000;
    }

    .action-item.delete-item:hover {
        background: #fff1f1;
        color: #e11d48;
    }

    .action-item.delete-item:hover i {
        color: #e11d48;
    }

    .actions-cell {
        text-align: right;
        position: relative;
        white-space: nowrap;
    }
    .action-item button { border: none; background: none; padding: 0; width: 100%; text-align: left; color: inherit; }

    .table-foot {
        padding: 12px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--muted);
        font-size: 12px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
        background: #fff;
    }

    @media (max-width: 768px) {
        .top-row { flex-direction: column; align-items: flex-start; }
        .outfit-table thead { display: none; }
        .outfit-table tbody tr { display: block; margin-bottom: 14px; border: 1px solid var(--border); border-radius: 8px; padding: 10px; }
        .outfit-table tbody td { display: flex; justify-content: space-between; border: none; padding: 8px 0; }
        .outfit-table tbody td::before { content: attr(data-label); font-weight: 700; color: var(--ink-900); }
    }
</style>

<div class="outfit-shell">
    <div class="top-row">
        <div class="title-block">
            <h5>Outfit Management</h5>
            <div class="sub">Manage all outfits from here.</div>
        </div>
        <a class="add-btn" href="{{ route('admin.outfits.create') }}">
            <i class="fas fa-plus"></i> Add New Outfit
        </a>
    </div>

    <div class="outfit-card">
        <table class="outfit-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Sub Category</th>
                    <th>Image</th>
                    <th>Status</th>
                    <th>Sort Order</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($outfits as $outfit)
                    @php
                        $initials = strtoupper(mb_substr($outfit->name, 0, 1));
                    @endphp
                    <tr>
                        <td data-label="Name">
                            <div class="outfit-name">
                                <div class="logo-circle">{{ $initials }}</div>
                                <span>{{ $outfit->name }}</span>
                            </div>
                        </td>
                        <td data-label="Category">
                            <span class="category-badge">{{ ucfirst($outfit->category) }}</span>
                        </td>
                        <td data-label="Sub Category">
                            <span class="sub-category-badge">{{ ucfirst($outfit->sub_category ?? 'N/A') }}</span>
                        </td>
                        <td data-label="Image">
                            @if($outfit->image)
                                <img src="{{ $outfit->image }}" alt="{{ $outfit->name }}" class="outfit-image">
                            @else
                                <span style="color: var(--muted); font-size: 12px;">No Image</span>
                            @endif
                        </td>
                        <td data-label="Status">
                            <span class="status-badge {{ $outfit->is_active ? 'status-active' : 'status-inactive' }}">
                                {{ $outfit->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td data-label="Sort Order">{{ $outfit->sort_order ?? 0 }}</td>
                        <td data-label="Actions" class="actions-cell">
                            <div class="action-menu">
                                <button class="action-toggle" type="button" aria-label="Actions">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="action-list">
                                    <a class="action-item" href="{{ route('admin.outfits.edit', $outfit->id) }}">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <form action="{{ route('admin.outfits.destroy', $outfit->id) }}" method="POST" class="w-100" onsubmit="return confirm('Are you sure?');">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="action-item delete-item w-100">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:18px; color: var(--ink-500);">No outfits found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-foot">
            <div>Showing 1 to {{ $outfits->count() }} of {{ $outfits->count() }} entries</div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.action-toggle').forEach(toggle => {
            toggle.addEventListener('click', function (e) {
                e.stopPropagation();
                const list = this.nextElementSibling;
                document.querySelectorAll('.action-list').forEach(l => l.classList.remove('show'));
                list.classList.toggle('show');
            });
        });

        document.addEventListener('click', function () {
            document.querySelectorAll('.action-list').forEach(l => l.classList.remove('show'));
        });
    });
</script>
@endsection
