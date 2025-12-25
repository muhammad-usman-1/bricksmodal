@extends('layouts.admin')
@section('content')
<style>
    :root {
        --bg: #f7f8fb;
        --card: #ffffff;
        --ink-900: #0f1524;
        --ink-700: #3b4150;
        --ink-500: #7b8191;
        --border: #e5e7eb;
        --shadow: 0 14px 32px rgba(15, 23, 42, 0.08);
        --pill-green: #e6f7ed;
        --pill-green-text: #15803d;
    }

    body { background: var(--bg); }

    .talents-shell { padding: 8px 0 22px; }
    .talents-head h5 { color: #101828;
font-size: 24px;
font-style: normal;
font-weight: 400;
line-height: 36px; /* 150% */}
    .talents-head .meta { margin: 4px 0 16px; color: var(--ink-500); font-size: 13px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .search-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; }
    .search-input { min-width: 240px; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--ink-700); background: #fff; }
    .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 6px; }
    .pill-btn { border: 1px solid var(--border); background: #fff; color: var(--ink-700); border-radius: 8px; padding: 7px 12px; font-size: 12px; cursor: pointer; transition: all .15s ease; }
    .pill-btn.active { background: #0f1524; color: #fff; border-color: #0f1524; }

    .talent-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; }
    .talent-card { position: relative; background: #f0f1f3; border-radius: 10px; overflow: hidden; height: 340px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid var(--border); display: flex; transition: transform 0.2s ease; }
    .talent-card:hover { transform: translateY(-4px); }
    .talent-img { width: 100%; height: 100%; object-fit: cover; }

    .badge-active {
        position: absolute; top: 15px; left: 15px;
        background: #e6f7ed; color: #15803d;
        border-radius: 20px; padding: 4px 12px;
        font-size: 11px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    .badge-active::before {
        content: ''; width: 6px; height: 6px; background: #10b981; border-radius: 50%;
    }

    .card-ellipsis { position: absolute; top: 12px; right: 15px; color: #111; font-size: 16px; cursor: pointer; z-index: 10; opacity: 0.6; }

    .card-overlay {
        position: absolute; left: 0; right: 0; bottom: 0;
        height: 50%;
        padding: 20px 18px 15px;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex; flex-direction: column;
        justify-content: flex-end;
    }

    .overlay-top { position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; margin-bottom: 4px; }
    .overlay-flag { position: absolute; left: 0; top: 0; width: 24px; height: 16px; border-radius: 2px; overflow: hidden; border: 1px solid rgba(255,255,255,0.2); }
    .overlay-meta-info { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.9); font-weight: 500; }

    .talent-name { font-weight: 600; font-size: 16px; margin: 4px 0 12px; text-align: center; }

    .card-divider { width: 100%; height: 1px; background: rgba(255,255,255,0.3); margin-bottom: 12px; }

    .overlay-bottom { display: flex; justify-content: space-between; align-items: flex-end; }
    .joined-info { display: flex; flex-direction: column; gap: 2px; }
    .joined-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7); font-weight: 700; }
    .joined-date { font-size: 12px; font-weight: 500; color: #fff; }

    .view-link { color: #fff; font-size: 12px; font-weight: 500; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; transition: opacity 0.2s; }
    .view-link:hover { opacity: 0.8; text-decoration: none; color: #fff; }

    @media (max-width: 640px) {
        .talent-card { height: 280px; }
        .card-overlay { height: 110px; }
    }

    .talents-footer {
        margin-top: 20px;
        display: flex;
        justify-content: flex-end;
        padding-bottom: 10px;
    }

    .add-talent-btn {
        background: #0f172a;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 10px 20px rgba(15,23,42,0.15);
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .add-talent-btn:hover {
        background: #1e293b;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .add-talent-btn i {
        font-size: 16px;
    }
</style>

@php
    $activeCount = $stats['approved'] ?? ($talents->where('verification_status', 'approved')->count());
    $totalTalents = $stats['total'] ?? $talents->count();
    $fallbackImg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="360"><rect width="300" height="360" rx="18" fill="#e5e7eb"/><path d="M150 170c28 0 50-22 50-50s-22-50-50-50-50 22-50 50 22 50 50 50Zm0 20c-42 0-80 19-92 56-2 6 2 12 8 12h168c6 0 10-6 8-12-12-37-50-56-92-56Z" fill="#cbd5e1"/></svg>');
@endphp

<div class="talents-shell">
    <div class="talents-head">
        <h5>Talents</h5>
        <div class="meta">
            <strong>{{ $activeCount }} active talents</strong>
            <span>•</span>
            <span>Manage and verify profiles</span>
        </div>
    </div>

    <div class="search-row">
        <input type="text" id="talentSearch" class="search-input" placeholder="Search talents...">
    </div>
    <div class="filter-pills" id="filterPills">
        <button class="pill-btn active" data-filter="all">All Talents</button>
        <button class="pill-btn" data-filter="male">Male</button>
        <button class="pill-btn" data-filter="female">Female</button>
        <button class="pill-btn" data-filter="verified">Verified</button>
        <button class="pill-btn" data-filter="pending">Pending</button>
    </div>

    @if($talents->isEmpty())
        <div class="text-muted" style="padding:20px 0;">{{ trans('global.no_talents_found') }}</div>
    @else
        <div class="talent-grid" id="talentGrid">
            @foreach($talents as $talent)
                @php
                    $displayName = $talent->display_name ?? $talent->legal_name ?? trans('global.not_set');
                    $gender = strtolower($talent->gender ?? '');
                    $status = strtolower($talent->verification_status ?? 'pending');
                    $isVerified = $status === 'approved';
                    $dob = optional($talent->date_of_birth);
                    $age = $dob ? $dob->age : null;
                    $ageText = $age ? "• $age YEARS" : '';
                    $joinedAt = optional($talent->created_at)->format('d M Y') ?? '--';
                    $flagCode = $talent->country_code ?? $talent->country ?? null;
                    $flagUrl = $flagCode && strlen($flagCode) === 2 ? 'https://flagcdn.com/24x18/' . strtolower($flagCode) . '.png' : null;
                    $avatarCandidate = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                    if (is_array($avatarCandidate)) {
                        $avatarCandidate = $avatarCandidate['url'] ?? ($avatarCandidate['path'] ?? ($avatarCandidate[0] ?? null));
                    }
                    $avatar = null;
                    if ($avatarCandidate) {
                        if (\Illuminate\Support\Str::startsWith($avatarCandidate, ['http://', 'https://', 'data:'])) {
                            $avatar = $avatarCandidate;
                        } else {
                            $normalized = ltrim($avatarCandidate, '/');
                            $storageRelative = \Illuminate\Support\Str::startsWith($normalized, 'storage/') ? substr($normalized, 8) : $normalized;
                            if (file_exists(public_path('storage/' . $storageRelative))) {
                                $avatar = asset('storage/' . $storageRelative);
                            } elseif (file_exists(public_path($normalized))) {
                                $avatar = asset($normalized);
                            } else {
                                $avatar = asset('storage/' . $storageRelative);
                            }
                        }
                    }
                    $avatar = $avatar ?: $fallbackImg;
                @endphp
                <div class="talent-card" data-gender="{{ $gender }}" data-status="{{ $status }}" data-name="{{ Str::lower($displayName) }}">
                    <img class="talent-img" src="{{ $avatar }}" alt="{{ $displayName }}">
                    <span class="badge-active">Active</span>
                    <span class="card-ellipsis"><i class="fas fa-ellipsis-v"></i></span>
                    <div class="card-overlay">
                        <div class="overlay-top">
                            <div class="overlay-flag">
                                @if($flagUrl)
                                    <img src="{{ $flagUrl }}" alt="{{ $flagCode }}" style="width:100%; height:100%; object-fit: cover;">
                                @else
                                    <div style="background:#444; color:#fff; font-size:8px; width:100%; height:100%; display:grid; place-items:center;">{{ strtoupper(substr($flagCode ?? '??',0,2)) }}</div>
                                @endif
                            </div>
                            <span class="overlay-meta-info">{{ strtoupper($gender ?: 'N/A') }} {{ $ageText }}</span>
                        </div>
                        <p class="talent-name">{{ $displayName }}</p>
                        <div class="card-divider"></div>
                        <div class="overlay-bottom">
                            <div class="joined-info">
                                <span class="joined-label">Joined</span>
                                <span class="joined-date">{{ $joinedAt }}</span>
                            </div>
                            <a class="view-link" href="{{ route('admin.talent-profiles.show', $talent->id) }}">
                                View details <i class="fas fa-chevron-right" style="font-size:10px;"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="talents-footer">
        <a href="{{ route('admin.talent-profiles.create') }}" class="add-talent-btn">
            <i class="fas fa-plus"></i>
            Add Talent
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pills = Array.from(document.querySelectorAll('#filterPills .pill-btn'));
        const searchInput = document.getElementById('talentSearch');
        const cards = Array.from(document.querySelectorAll('#talentGrid .talent-card'));

        function applyFilters() {
            const activePill = pills.find(p => p.classList.contains('active'));
            const filter = activePill ? activePill.dataset.filter : 'all';
            const term = (searchInput?.value || '').toLowerCase();

            cards.forEach(card => {
                const gender = card.dataset.gender || '';
                const status = card.dataset.status || '';
                const name = card.dataset.name || '';

                const matchesSearch = !term || name.includes(term);
                let matchesFilter = filter === 'all';
                if (filter === 'male') matchesFilter = gender === 'male';
                if (filter === 'female') matchesFilter = gender === 'female';
                if (filter === 'verified') matchesFilter = status === 'approved';
                if (filter === 'pending') matchesFilter = status === 'pending';

                card.style.display = matchesSearch && matchesFilter ? '' : 'none';
            });
        }

        pills.forEach(pill => {
            pill.addEventListener('click', () => {
                pills.forEach(p => p.classList.remove('active'));
                pill.classList.add('active');
                applyFilters();
            });
        });

        searchInput?.addEventListener('input', applyFilters);
    });
</script>
@endsection
