@extends('layouts.admin')
@section('content')
<style>
    .verified-badge {
        display: inline-flex;
        width: 16px;
        height: 12px;
        margin-left: 6px;
    }
    .verified-badge svg {
        width: 100%;
        height: 100%;
        display: block;
    }
</style>
<div class="admin-dashboard container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="mb-0">Dashboard</h4>
            </div>
        </div>
    </div>

    <div class="row dashboard-cards mb-4">
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="stat-value">{{ $stats['total'] ?? 0 }}</div>
                <div class="stat-label">Total Models</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="stat-value">{{ $stats['pending_verification'] ?? 0 }}</div>
                <div class="stat-label">Pending Verification</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="stat-value">{{ $stats['recent_signups'] ?? 0 }}</div>
                <div class="stat-label">Recent Sign-ups</div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="stat-card bg-white shadow-sm">
                <div class="stat-value">{{ $stats['active_campaigns'] ?? 0 }}</div>
                <div class="stat-label">Active Campaigns</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 talent-table">
                    <thead>
                        <tr>
                            <th>Talent</th>
                            <th>Gender</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Status</th>
                            <th>Date Joined</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talents as $talent)
                            <tr>
                                <td class="align-middle">
                                    <div class="d-flex align-items-center">
                                        @php
                                            $name = optional($talent->user)->name ?? ($talent->display_name ?? $talent->legal_name ?? '—');
                                            $avatar = null;

                                            if (!empty($talent->headshot_center_path)) {
                                                $publicPath = public_path('storage/' . ltrim($talent->headshot_center_path, '/'));
                                                if (file_exists($publicPath)) {
                                                    $avatar = asset('storage/' . ltrim($talent->headshot_center_path, '/'));
                                                } else {
                                                    $avatar = $talent->headshot_center_path;
                                                }
                                            }

                                            if (empty($avatar)) {
                                                $avatar = 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&background=ffffff&color=5a5a5a&rounded=true&size=64';
                                            }
                                        @endphp
                                        <img src="{{ $avatar }}" onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ urlencode($name) }}&background=ffffff&color=5a5a5a&rounded=true&size=64'" alt="{{ $name }}" class="rounded-circle mr-3 avatar-sm" />
                                        <div>
                                            <div class="font-weight-bold">{{ $name }}
                                                @if($talent->verification_status === 'approved')
                                                    <span class="verified-badge" title="Verified" aria-label="Verified">
                                                        <svg viewBox="0 0 60 60" role="img" aria-hidden="true">
                                                            <path fill="#1DA1F2" d="M32 2c-2 0-4 .7-5.6 2l-4.3 3.5-5.5-1.3A9 9 0 0 0 6.1 12l-1.1 5.6-5.2 2.9A9 9 0 0 0 0 30.6l2.2 5.2L0 41a9 9 0 0 0 1.9 10.1l5.2 2.9 1.1 5.6a9 9 0 0 0 10.5 6.9l5.5-1.3 4.3 3.5a9 9 0 0 0 11.2 0l4.3-3.5 5.5 1.3a9 9 0 0 0 10.5-6.9l1.1-5.6 5.2-2.9A9 9 0 0 0 64 41l-2.2-5.2L64 30.6a9 9 0 0 0-1.9-10l-5.2-3-1.1-5.6a9 9 0 0 0-10.5-6.9l-5.5 1.3-4.3-3.5A9 9 0 0 0 32 2Z"/>
                                                            <polyline fill="none" stroke="#FFFFFF" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" points="20 34 29.5 43.5 46 23"/>
                                                        </svg>
                                                    </span>
                                                @endif
                                            </div>
                                            <div class="small text-muted">{{ optional($talent->user)->email ?? '' }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="align-middle">{{ ucfirst($talent->gender ?? '—') }}</td>
                                <td class="align-middle">{{ $talent->height ? $talent->height . ' cm' : '—' }}</td>
                                <td class="align-middle">{{ $talent->weight ? $talent->weight . ' kg' : '—' }}</td>
                                <td class="align-middle">
                                    @php
                                        $status = $talent->verification_status ?? 'pending';
                                    @endphp
                                    @if($status === 'approved' || $status === 'verified')
                                        <span class="badge badge-success">Verified</span>
                                    @elseif($status === 'pending')
                                        <span class="badge badge-secondary">Pending</span>
                                    @else
                                        <span class="badge badge-light">{{ ucfirst($status) }}</span>
                                    @endif
                                </td>
                                <td class="align-middle">{{ $talent->created_at ? $talent->created_at->format('d/m/Y') : '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">No recent talents found</td>
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

@endsection