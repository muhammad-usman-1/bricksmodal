@extends('layouts.admin')
@section('content')
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
                                                    <span class="verified-badge" title="Verified">
                                                        <i class="fas fa-check-circle text-primary"></i>
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