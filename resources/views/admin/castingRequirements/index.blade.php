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
        --badge-orange: #fbe7cf;
        --badge-orange-text: #c26f13;
        --badge-green: #c9f2d8;
        --badge-green-text: #2b9a50;
        --table-head: #1f2228;
    }

    body { background: var(--bg); }

    .casting-shell {
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
line-height: 36px; /* 150% */
    }

    .title-block .sub {
        margin: 2px 0 0;
        color: var(--ink-500);
        font-size: 13px;
    }

    .filters {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .filter-select {
        border: 1px solid var(--border);
        background: #fff;
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 13px;
        color: var(--ink-700);
        min-width: 160px;
        appearance: none;
        background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="10" height="6" viewBox="0 0 10 6"><path fill="%237b8191" d="M5 6L0 0h10z"/></svg>');
        background-repeat: no-repeat;
        background-position: right 10px center;
        background-size: 10px 6px;
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
    }

    .add-btn:focus, .add-btn:active, .add-btn:hover {
        color: #fff;
        background: #2C2C2E;
        outline: none;
        text-decoration: none;
    }

    .shoot-card {
        background: var(--card);
        border-radius: 10px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .shoot-table {
        width: 100%;
        border-collapse: collapse;
    }

    .shoot-table thead th {
        background: #2C2C2E;
        color: #fff;
        font-weight: 600;
        font-size: 12px;
        padding: 13px 14px;
        border: none;
        white-space: nowrap;
    }

    .shoot-table tbody td {
        padding: 14px;
        border-bottom: 1px solid var(--border);
        font-size: 13px;
        color: var(--ink-700);
        vertical-align: middle;
    }

    .shoot-name {
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

    .location-cell,
    .date-cell { color: var(--ink-700); }

    .date-cell small { display: block; color: var(--muted); margin-top: 4px; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 6px 10px;
        border-radius: 12px;
        font-weight: 600;
        font-size: 12px;
    }

    .status-orange { background: var(--badge-orange); color: var(--badge-orange-text); }
    .status-green { background: var(--badge-green); color: var(--badge-green-text); }
    .status-gray { background: #e6e7eb; color: #4b5563; }

    .applicants-btn {
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-900);
        border-radius: 8px;
        padding: 8px 12px;
        font-size: 12px;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .applicants-count {
        display: block;
        color: var(--muted);
        font-size: 11px;
        margin-top: 4px;
    }

    .required-pill {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        border: 1px solid var(--border);
        border-radius: 10px;
        padding: 8px 10px;
        color: var(--ink-700);
        font-weight: 600;
    }

    .required-pill .icon {
        width: 14px;
        height: 14px;
        display: grid;
        place-items: center;
        color: var(--ink-500);
    }

    .table-foot {
        padding: 10px 14px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        color: var(--muted);
        font-size: 12px;
    }

    .pager {
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .pager .btn-page {
        width: 26px;
        height: 26px;
        border-radius: 6px;
        border: 1px solid var(--border);
        background: #fff;
        color: var(--ink-700);
        display: grid;
        place-items: center;
        font-size: 12px;
    }

    .pager .btn-page.active {
        background: #000;
        color: #fff;
        border-color: #000;
    }

    .action-menu {
        position: relative;
        display: inline-block;
    }

    .action-toggle {
        border: 1px solid var(--border);
        background: #fff;
        border-radius: 8px;
        width: 32px;
        height: 32px;
        display: grid;
        place-items: center;
        color: var(--ink-700);
        cursor: pointer;
        margin-left: auto;
    }

    .action-list {
        position: absolute;
        right: 0;
        top: 36px;
        min-width: 140px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 10px;
        box-shadow: var(--shadow);
        padding: 8px 0;
        display: none;
        z-index: 10;
    }

    .action-list.show { display: block; }

    .action-item {
        padding: 8px 14px;
        font-size: 13px;
        color: var(--ink-700);
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-item:hover { background: #f5f6f8; color: var(--ink-900); }

    .actions-cell {
        text-align: right;
        position: relative;
        white-space: nowrap;
    }
    .action-item button { border: none; background: none; padding: 0; width: 100%; text-align: left; color: inherit; }

    @media (max-width: 768px) {
        .top-row { flex-direction: column; align-items: flex-start; }
        .filters { width: 100%; }
        .shoot-table thead { display: none; }
        .shoot-table tbody tr { display: block; margin-bottom: 14px; border: 1px solid var(--border); border-radius: 8px; padding: 10px; }
        .shoot-table tbody td { display: flex; justify-content: space-between; border: none; padding: 8px 0; }
        .shoot-table tbody td::before { content: attr(data-label); font-weight: 700; color: var(--ink-900); }
    }
</style>

<div class="casting-shell">
    @php
        $statusOptions = collect($castingRequirements)->pluck('status')->filter()->unique()->values();
        $locationOptions = collect($castingRequirements)->pluck('location')->filter()->unique()->values();
    @endphp
    <div class="top-row">
        <div class="title-block">
            <h5>Casting Requirement List</h5>
            <div class="sub">Manage all shoots from here.</div>
        </div>
        <div class="filters">
            <select class="filter-select" id="statusFilter" aria-label="Filter by status">
                <option value="">Status</option>
                @foreach($statusOptions as $statusOption)
                    <option value="{{ $statusOption }}">{{ App\Models\CastingRequirement::STATUS_SELECT[$statusOption] ?? ucfirst($statusOption) }}</option>
                @endforeach
            </select>
            <select class="filter-select" id="locationFilter" aria-label="Filter by location">
                <option value="">Location</option>
                @foreach($locationOptions as $locationOption)
                    <option value="{{ $locationOption }}">{{ $locationOption }}</option>
                @endforeach
            </select>
            @can('casting_requirement_create')
                <button class="add-btn" type="button" id="addNewShootBtn"><i class="fas fa-plus"></i> Add New Shoot</button>
            @endcan
        </div>
    </div>

    <div class="shoot-card">
        <table class="shoot-table">
            <thead>
                <tr>
                    <th>Shoot Name <i class="fas fa-chevron-down ml-1" style="font-size:10px;"></i></th>
                    <th>Location</th>
                    <th>Shoot Date-Time <i class="fas fa-chevron-down ml-1" style="font-size:10px;"></i></th>
                    <th>Status <i class="fas fa-chevron-down ml-1" style="font-size:10px;"></i></th>
                    <th>Applicants <i class="fas fa-chevron-down ml-1" style="font-size:10px;"></i></th>
                    <th>Required</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($castingRequirements as $castingRequirement)
                    @php
                        $projectName = $castingRequirement->project_name ?? 'Untitled';
                        $initials = strtoupper(mb_substr($projectName, 0, 1));
                        $location = $castingRequirement->location ?? trans('global.not_set');
                        $dateText = $castingRequirement->shoot_date_display ?? ($castingRequirement->shoot_date_time ?? trans('global.not_set'));
                        $statusKey = $castingRequirement->status ?? 'pending';
                        $statusLabel = App\Models\CastingRequirement::STATUS_SELECT[$statusKey] ?? ucfirst($statusKey);
                        $statusClass = 'status-gray';
                        if (in_array($statusKey, ['currently_shooting', 'in_progress', 'shooting'])) { $statusClass = 'status-orange'; }
                        if (in_array($statusKey, ['advertised', 'open', 'active'])) { $statusClass = 'status-green'; }
                        $duration = $castingRequirement->duration ?? null;
                        $applicantsCount = $castingRequirement->applications_count
                            ?? optional($castingRequirement->applications)->count()
                            ?? optional($castingRequirement->castingApplications)->count()
                            ?? 0;
                        $requiredCount = $castingRequirement->required_talent_count
                            ?? $castingRequirement->required_talents
                            ?? $castingRequirement->talents_needed
                            ?? $castingRequirement->required
                            ?? $castingRequirement->number_of_talents
                            ?? 0;
                    @endphp
                    <tr data-status="{{ $statusKey }}" data-location="{{ $location }}">
                        <td data-label="Shoot Name">
                            <div class="shoot-name">
                                <div class="logo-circle">{{ $initials }}</div>
                                <span>{{ $projectName }}</span>
                            </div>
                        </td>
                        <td data-label="Location" class="location-cell">{{ $location }}</td>
                        <td data-label="Shoot Date-Time" class="date-cell">{{ $dateText }}@if($duration)<small>{{ $duration }}</small>@endif</td>
                        <td data-label="Status">
                            <span class="status-badge {{ $statusClass }}">{{ $statusLabel }}</span>
                        </td>
                        <td data-label="Applicants">
                            <a class="applicants-btn" href="{{ route('admin.casting-requirements.applicants', $castingRequirement->id) }}">View Applicants</a>
                            <span class="applicants-count">{{ $applicantsCount }} applied</span>
                        </td>
                        <td data-label="Required">
                            <span class="required-pill"><span class="icon"><i class="fas fa-users"></i></span>{{ $requiredCount }}</span>
                        </td>
                        <td data-label="Actions" class="actions-cell">
                            <div class="action-menu">
                                <button class="action-toggle" type="button" aria-label="Actions">
                                    <i class="fas fa-ellipsis-v"></i>
                                </button>
                                <div class="action-list">
                                    <a class="action-item" href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}">Shoot</a>
                                    @can('casting_requirement_show')
                                        <a class="action-item" href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}">View</a>
                                    @endcan
                                    @can('casting_requirement_edit')
                                        <a class="action-item" href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}">Edit</a>
                                    @endcan
                                    @can('casting_requirement_delete')
                                        <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" class="action-item p-0" data-swal-confirm="{{ trans('global.areYouSure') }}">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" style="border:none; background:none; padding:0; text-align:left; width:100%; color:inherit;">Delete</button>
                                        </form>
                                    @endcan
                                    <button class="action-item share-project-btn" type="button"
                                        data-toggle="modal"
                                        data-target="#shareProjectModal"
                                        data-name="{{ $castingRequirement->project_name }}"
                                        data-location="{{ $location }}"
                                        data-date="{{ $dateText }}"
                                        data-url="{{ route('talent.projects.show', $castingRequirement) }}">
                                        Share
                                    </button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align:center; padding:18px; color: var(--ink-500);">No casting requirements found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
        <div class="table-foot">
            <div>Showing 1 to {{ $castingRequirements->count() }} of {{ $castingRequirements->count() }} entries</div>
            <div class="pager">
                <button class="btn-page" type="button">&lt;</button>
                <button class="btn-page active" type="button">1</button>
                <button class="btn-page" type="button">&gt;</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="shareProjectModal" tabindex="-1" role="dialog" aria-labelledby="shareProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="shareProjectModalLabel">{{ trans('global.share') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('global.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold mb-1">{{ trans('global.link') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareProjectLink" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="copyShareLink">
                                {{ trans('global.copy_link') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="font-weight-bold mb-1">{{ trans('global.message') }}</label>
                    <textarea id="shareProjectMessage" rows="4" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                <button type="button" class="btn btn-success" id="shareProjectWhatsApp">
                    <i class="fab fa-whatsapp mr-1"></i> {{ trans('notifications.send_whatsapp') }}
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const statusFilter = document.getElementById('statusFilter');
        const locationFilter = document.getElementById('locationFilter');
        const rows = Array.from(document.querySelectorAll('.shoot-table tbody tr'));

        function applyFilters() {
            const statusVal = statusFilter.value.toLowerCase();
            const locationVal = locationFilter.value.toLowerCase();
            rows.forEach(row => {
                const rowStatus = (row.dataset.status || '').toLowerCase();
                const rowLocation = (row.dataset.location || '').toLowerCase();
                const statusMatch = !statusVal || rowStatus === statusVal;
                const locationMatch = !locationVal || rowLocation === locationVal;
                row.style.display = statusMatch && locationMatch ? '' : 'none';
            });
        }

        statusFilter?.addEventListener('change', applyFilters);
        locationFilter?.addEventListener('change', applyFilters);

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

        const shareLinkInput = document.getElementById('shareProjectLink');
        const shareMessageInput = document.getElementById('shareProjectMessage');
        const shareWhatsappBtn = document.getElementById('shareProjectWhatsApp');

        document.querySelectorAll('.share-project-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                const projectName = this.dataset.name || '{{ trans('global.this_project') }}';
                const location = this.dataset.location || '{{ trans('global.not_set') }}';
                const date = this.dataset.date || '{{ trans('global.not_set') }}';
                const url = this.dataset.url || '';
                shareLinkInput.value = url;
                shareMessageInput.value = `Hi! Check out "${projectName}" happening on ${date} at ${location}. Apply here: ${url}`;
            });
        });

        document.getElementById('copyShareLink')?.addEventListener('click', function () {
            const text = shareLinkInput.value;
            if (!text) return;
            const notify = () => {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'success', title: '{{ trans('global.copied') }}', text: '{{ trans('global.link_copied') }}', timer: 1500, showConfirmButton: false });
                }
            };
            if (navigator.clipboard?.writeText) {
                navigator.clipboard.writeText(text).then(notify).catch(() => { shareLinkInput.select(); document.execCommand('copy'); notify(); });
            } else {
                shareLinkInput.select();
                document.execCommand('copy');
                notify();
            }
        });

        shareWhatsappBtn?.addEventListener('click', function () {
            const message = shareMessageInput.value || '';
            if (!message.trim()) {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({ icon: 'error', title: '{{ trans('global.error') }}', text: '{{ trans('notifications.whatsapp_message_required') }}' });
                }
                return;
            }
            window.open('https://wa.me/?text=' + encodeURIComponent(message), '_blank');
        });

        const addBtn = document.getElementById('addNewShootBtn');
        if (addBtn) {
            addBtn.addEventListener('click', function () {
                window.location.href = '{{ route('admin.casting-requirements.create') }}';
            });
        }
    });
</script>
@endsection
