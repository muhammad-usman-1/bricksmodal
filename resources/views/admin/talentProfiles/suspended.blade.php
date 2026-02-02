@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f6f7fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e6e9f0;
        --shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
    }

    body { background: var(--bg); }

    .dash-shell { padding-top: 20px; font-family: 'Inter', sans-serif; }
    
    .page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 24px;
    }
    .page-header h5 { color: #000; font-size: 24px; font-weight: 400; margin: 0; }
    .back-link {
        color: var(--ink-700);
        font-size: 13px;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        transition: color 0.2s;
    }
    .back-link:hover { color: var(--ink-900); text-decoration: none; }

    .panel {
        background: var(--card);
        border: 1px solid var(--border);
        border-radius: 16px;
        box-shadow: var(--shadow);
        overflow: hidden;
    }

    .panel-head {
        padding: 20px 24px;
        border-bottom: 1px solid #eef1f5;
    }
    .panel-title { margin: 0; color: var(--ink-900); font-weight: 600; font-size: 18px; }

    .table-wrap { overflow-x: auto; }
    table.talent-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    table.talent-table th, table.talent-table td { padding: 16px 24px; border-bottom: 1px solid #eef1f5; color: var(--ink-700); text-align: left; }
    table.talent-table th { text-transform: uppercase; letter-spacing: 0.05em; font-size: 11px; color: var(--ink-500); font-weight: 700; background: #fafbfc; }

    .talent-cell { display: flex; align-items: center; gap: 12px; }
    .avatar { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; background: #e5e7eb; }
    .talent-name { font-weight: 700; color: var(--ink-900); text-decoration: none; transition: color 0.2s; }
    .talent-name:hover { color: #3b82f6; text-decoration: underline; }
    .talent-email { margin: 0; color: var(--ink-500); font-size: 12px; }

    .badge-status {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        padding: 4px 12px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
    }
    .badge-suspended { background: #f3f4f6; color: #6b7280; border: 1px solid #e5e7eb; }

    .action-btn {
        background: #0f1524;
        color: #fff;
        border: none;
        border-radius: 8px;
        padding: 8px 16px;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .action-btn:hover { background: #1f2937; }
</style>

<div class="dash-shell">
    <div class="page-header">
        <div>
            <a class="back-link" href="{{ route('admin.talents.dashboard') }}">
                <i class="fas fa-arrow-left"></i> Back to Talents
            </a>
            
        </div>
    </div>

    <div class="panel">
        <div class="panel-head">
            <h6 class="panel-title">Manage Suspended Profiles</h6>
        </div>
        <div class="panel-body">
            <div class="table-wrap">
                <table class="talent-table datatable">
                    <thead>
                        <tr>
                            <th>Talent</th>
                            <th>Gender</th>
                            <th>Status</th>
                            <th>Suspended Date</th>
                            <th style="width: 150px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talentProfiles as $talent)
                            @php
                                $name = optional($talent->user)->name ?? ($talent->display_name ?? $talent->legal_name ?? '—');
                                $user = $talent->user ?? null;
                                        $phoneNumber = '—';
                                if ($user && ($user->phone_number || $user->mobile_number)) {
                                    $countryCode = $user->phone_country_code ?? '';
                                    $phone = $user->phone_number ?? $user->mobile_number;
                                    $phoneNumber = $countryCode ? '+' . ltrim($countryCode, '+') . ' ' . $phone : $phone;
                                } elseif ($talent->whatsapp_number || $talent->mobile_number) {
                                    $phoneNumber = $talent->whatsapp_number ?? $talent->mobile_number;
                                }
                                
                                // Image logic from home.blade.php
                                $storageDisk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'));
                                $avatar = null;

                                $mediaPhoto = $talent->media ? $talent->media->first() : null;
                                $candidatePath = $mediaPhoto ? $mediaPhoto->file_path : ($talent->headshot_center_path ?? null);

                                if (!empty($candidatePath)) {
                                    if (\Illuminate\Support\Str::startsWith($candidatePath, ['http://', 'https://', 'data:'])) {
                                        $avatar = $candidatePath;
                                    } else {
                                        $cleanPath = ltrim($candidatePath, '/');
                                        try {
                                            $avatar = $storageDisk->url($cleanPath);
                                        } catch (\Exception $e) {
                                            $avatar = asset('storage/' . $cleanPath);
                                        }
                                    }
                                }

                                if (empty($avatar)) {
                                    $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=eff2f7&color=0f1524&rounded=true&size=64';
                                }
                            @endphp
                            <tr>
                                <td>
                                    <div class="talent-cell">
                                        <img class="avatar" src="{{ $avatar }}" alt="{{ $name }}" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=eff2f7&color=0f1524&rounded=true&size=64'">
                                        <div>
                                            <a href="{{ route('admin.talent-profiles.show', $talent) }}" class="talent-name">{{ $name }}</a>
                                            <p class="talent-email">{{ $phoneNumber }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ ucfirst($talent->gender ?? '—') }}</td>
                                <td>
                                    <span class="badge-status badge-suspended">Suspended</span>
                                </td>
                                <td>{{ $talent->updated_at->format('M d, Y') }}</td>
                                <td style="text-align:right;">
                                    <form action="{{ route('admin.talent-profiles.unsuspend', $talent) }}" method="POST" style="display:inline-block;" class="unsuspend-form">
                                        @csrf
                                        <button type="submit" class="action-btn">
                                            <i class="fas fa-play"></i> Unsuspend
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; padding:40px; color: var(--ink-500);">No suspended talents found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(function () {
        $('.datatable').DataTable({
            retrieve: true,
            aaSorting: [],
            pageLength: 100,
            searching: false,
            paging: false,
            info: false,
            select: false,
            columnDefs: [{
                orderable: false,
                targets: -1
            }]
        });

        $('.unsuspend-form').on('submit', function(e) {
            e.preventDefault();
            const form = this;
            Swal.fire({
                title: 'Unsuspend Talent?',
                text: "This talent will be able to apply for projects again.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f1524',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Unsuspend'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
