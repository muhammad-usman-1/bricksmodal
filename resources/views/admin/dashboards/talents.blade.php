@extends('layouts.admin')
@section('content')
<link href="{{ asset('css/flag-icons.min.css') }}" rel="stylesheet">
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

    .talents-shell { position: relative; }
    .talents-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 10px 0 12px;
    }
    .talents-head h5 { color: #101828;
font-size: 24px;
font-style: normal;
font-weight: 400;
line-height: 36px; /* 150% */}
    .talents-head .meta { margin: 4px 0; color: var(--ink-500); font-size: 13px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    .search-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; margin-bottom: 12px; }
    .search-input { min-width: 240px; border: 1px solid var(--border); border-radius: 8px; padding: 9px 12px; font-size: 13px; color: var(--ink-700); background: #fff; }
    .filter-pills { display: flex; gap: 8px; flex-wrap: wrap; margin-bottom: 6px; }
    .pill-btn { border: 1px solid var(--border); background: #fff; color: var(--ink-700); border-radius: 8px; padding: 7px 12px; font-size: 12px; cursor: pointer; transition: all .15s ease; }
    .pill-btn.active { background: black; color: #fff; border-color: #0f1524; }

    .talent-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .talent-card { position: relative; background: #f0f1f3; border-radius: 10px; overflow: hidden; height: 340px; box-shadow: 0 4px 20px rgba(0,0,0,0.06); border: 1px solid var(--border); transition: transform 0.2s ease; cursor: pointer; }
    .talent-card:hover { transform: translateY(-4px); }
    .talent-img-container { position: relative; width: 100%; height: 100%; overflow: hidden; z-index: 1; }
    .talent-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0; transition: opacity 0.4s ease-in-out, transform 0.4s ease-in-out; transform: scale(1.05); z-index: 1; }
    .talent-img.active { opacity: 1; transform: scale(1); z-index: 1; }
    .talent-card:hover .talent-img:not(.active) { opacity: 0; }
    .talent-card:hover .talent-img.active { opacity: 1; transform: scale(1); }

    .badge-active {
        position: absolute; top: 15px; left: 15px;
        background: #e6f7ed; color: #15803d;
        border-radius: 20px; padding: 4px 12px;
        font-size: 11px; font-weight: 700;
        display: inline-flex; align-items: center; gap: 6px;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        z-index: 20;
        pointer-events: none;
    }
    .badge-active::before {
        content: ''; width: 6px; height: 6px; background: #10b981; border-radius: 50%;
    }

    .card-ellipsis { position: absolute; top: 12px; right: 15px; z-index: 30; }
    .dropdown-toggle-btn { color: #111; font-size: 16px; cursor: pointer; opacity: 0.6; transition: opacity 0.2s; }
    .dropdown-toggle-btn:hover { opacity: 1; }

    .actions-dropdown-container { position: relative; display: inline-block; }
    .actions-dropdown-menu {
        position: absolute;
        right: 0;
        top: 100%;
        margin-top: 8px;
        background: #fff;
        border: 1px solid var(--border);
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        z-index: 100;
        min-width: 140px;
        display: none;
        overflow: hidden;
    }
    .actions-dropdown-menu.active { display: block; animation: dropdownFade 0.2s ease; }

    @keyframes dropdownFade {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .actions-dropdown-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        font-size: 13px;
        color: var(--ink-700);
        text-decoration: none;
        transition: background 0.12s ease;
        border: none;
        background: none;
        width: 100%;
        text-align: left;
        cursor: pointer;
    }
    .actions-dropdown-item:hover { background: #f3f5f9; color: var(--ink-900); text-decoration: none; }
    .actions-dropdown-item.text-danger { color: #dc2626; }
    .actions-dropdown-item.text-danger:hover { background: #fef2f2; }

    .card-overlay {
        position: absolute; left: 0; right: 0; bottom: 0;
        height: 50%;
        padding: 20px 18px 15px;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        display: flex; flex-direction: column;
        justify-content: flex-end;
        z-index: 10;
        pointer-events: none;
        transition: none;
    }

    .overlay-top { position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; margin-bottom: 4px; transition: none; }
    .overlay-flag { position: absolute; left: 0; top: 0; width: auto; height: 22px; aspect-ratio: 4 / 3; display: inline-block; transition: none; background-size: contain; background-position: center; background-repeat: no-repeat; }
    .overlay-meta-info { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.9); font-weight: 500; transition: none; }

    .talent-name { font-weight: 600; font-size: 16px; margin: 4px 0 12px; text-align: center; transition: none; }

    .card-divider { width: 100%; height: 1px; background: rgba(255,255,255,0.3); margin-bottom: 12px; transition: none; }

    .overlay-bottom { display: flex; justify-content: space-between; align-items: flex-end; transition: none; }
    .joined-info { display: flex; flex-direction: column; gap: 2px; transition: none; }
    .joined-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7); font-weight: 700; transition: none; }
    .joined-date { font-size: 12px; font-weight: 500; color: #fff; transition: none; }

    @media (max-width: 640px) {
        .talent-card { height: 280px; }
        .card-overlay { height: 110px; }
    }

    .add-talent-btn {
        background: black;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 12px 24px;
        font-size: 14px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 8px;
       
        text-decoration: none;
        transition: all 0.2s ease;
    }

    .add-talent-btn:hover {
        background: #111111;
        color: #fff;
        text-decoration: none;
        transform: translateY(-2px);
    }

    .add-talent-btn i {
        font-size: 16px;
    }

    .talent-footer {
        position: fixed;
        left: 0;
        right: 0;
        bottom: 0;
         
        padding: 12px 24px;
        display: flex;
        justify-content: flex-end;
        z-index: 50;
         
    }
</style>

@php
    $activeCount = $stats['approved'] ?? ($talents->where('verification_status', 'approved')->count());
    $totalTalents = $stats['total'] ?? $talents->count();
    $fallbackImg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" width="300" height="360"><rect width="300" height="360" rx="18" fill="#e5e7eb"/><path d="M150 170c28 0 50-22 50-50s-22-50-50-50-50 22-50 50 22 50 50 50Zm0 20c-42 0-80 19-92 56-2 6 2 12 8 12h168c6 0 10-6 8-12-12-37-50-56-92-56Z" fill="#cbd5e1"/></svg>');
    $storageDisk = \Illuminate\Support\Facades\Storage::disk(config('filesystems.default', 'public'));
    $toUrl = function($path) use ($storageDisk) {
        if (!$path) return null;
        if (is_array($path)) {
            $path = $path['url'] ?? ($path['path'] ?? ($path[0] ?? null));
        }
        if (!$path) return null;
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://', 'data:'])) {
            return $path;
        }
        $clean = ltrim($path, '/');
        try {
            return $storageDisk->url($clean);
        } catch (\Exception $e) {
            return asset('storage/' . $clean);
        }
    };
@endphp

<div class="talents-shell">
    <div class="talents-head">
        <div>
            <h5>Talents</h5>
            <div class="meta">
                <strong>{{ $activeCount }} active talents</strong>
                <span>•</span>
                <span>Manage and verify profiles</span>
            </div>
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
                    $flagCode = $talent->nationality ?? $talent->country_code ?? $talent->country ?? null;
                    $flagUrl = $flagCode && strlen($flagCode) === 2 ? 'https://flagcdn.com/w40/' . strtolower($flagCode) . '.png' : null;
                    $avatarCandidate = $talent->headshot_center_path ?? ($talent->headshot_left_path ?? $talent->headshot_right_path);
                    $avatar = $toUrl($avatarCandidate) ?: $fallbackImg;

                    // Collect all images for hover effect
                    $headshotImages = [];
                    $fullBodyImages = [];

                    // Helper function to normalize image path
                    $normalizeImage = $toUrl;

                    // Collect headshot images
                    if ($talent->headshot_left_path) {
                        $img = $normalizeImage($talent->headshot_left_path);
                        if ($img) $headshotImages[] = $img;
                    }
                    if ($talent->headshot_center_path) {
                        $img = $normalizeImage($talent->headshot_center_path);
                        if ($img) $headshotImages[] = $img;
                    }
                    if ($talent->headshot_right_path) {
                        $img = $normalizeImage($talent->headshot_right_path);
                        if ($img) $headshotImages[] = $img;
                    }

                    // Collect full-body images
                    if ($talent->full_body_front_path) {
                        $img = $normalizeImage($talent->full_body_front_path);
                        if ($img) $fullBodyImages[] = $img;
                    }
                    if ($talent->full_body_right_path) {
                        $img = $normalizeImage($talent->full_body_right_path);
                        if ($img) $fullBodyImages[] = $img;
                    }
                    if ($talent->full_body_back_path) {
                        $img = $normalizeImage($talent->full_body_back_path);
                        if ($img) $fullBodyImages[] = $img;
                    }

                    // Combine all images (headshots first, then full-body)
                    $allImages = array_merge($headshotImages, $fullBodyImages);
                    if (empty($allImages)) {
                        $allImages = [$avatar];
                    }
                @endphp
                <div class="talent-card" data-gender="{{ $gender }}" data-status="{{ $status }}" data-name="{{ Str::lower($displayName) }}" data-url="{{ route('admin.talent-profiles.show', $talent->id) }}" data-images='@json($allImages)'>
                    <div class="talent-img-container">
                        @foreach($allImages as $index => $imgSrc)
                            <img class="talent-img {{ $index === 0 ? 'active' : '' }}" src="{{ $imgSrc }}" alt="{{ $displayName }} - Image {{ $index + 1 }}" data-index="{{ $index }}">
                        @endforeach
                    </div>
                    <span class="badge-active">{{ $isVerified ? 'Active' : 'Pending' }}</span>
                    <div class="card-ellipsis actions-dropdown-container">
                        <span class="dropdown-toggle-btn"><i class="fas fa-ellipsis-v"></i></span>
                        <div class="actions-dropdown-menu">
                            <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="actions-dropdown-item">
                                <i class="far fa-eye"></i> View Profile
                            </a>
                            <form action="{{ route('admin.talent-profiles.destroy', $talent->id) }}" method="POST" class="delete-talent-form" data-swal-confirm="Are you sure? All the data will be deleted." style="margin:0;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="actions-dropdown-item text-danger">
                                    <i class="far fa-trash-alt"></i> Delete
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-overlay">
                        <div class="overlay-top">
                            <div class="overlay-flag">
                                @if($flagCode && strlen($flagCode) === 2)
                                    <span class="fi fi-{{ strtolower($flagCode) }}" title="{{ $flagCode }}"></span>
                                @else
                                    <div style="background:#444; color:#fff; font-size:8px; width:50%; height:50%; display:grid; place-items:center;">{{ strtoupper(substr($flagCode ?? '??',0,2)) }}</div>
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
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<div class="talent-footer">
    <a href="{{ route('admin.talent-profiles.create') }}" class="add-talent-btn">
        <i class="fas fa-plus"></i> Add Talent
    </a>
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

        // Make cards clickable
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't navigate if clicking on the ellipsis menu
                if (e.target.closest('.card-ellipsis')) {
                    return;
                }
                const url = this.dataset.url;
                if (url) {
                    window.location.href = url;
                }
            });
        });

        // Image rotation on hover
        cards.forEach(card => {
            const images = card.querySelectorAll('.talent-img');
            if (images.length <= 1) return; // No rotation needed if only one image

            let rotationInterval = null;
            let currentIndex = 0;

            card.addEventListener('mouseenter', function() {
                rotationInterval = setInterval(() => {
                    images[currentIndex].classList.remove('active');
                    currentIndex = (currentIndex + 1) % images.length;
                    images[currentIndex].classList.add('active');
                }, 400);
            });

            card.addEventListener('mouseleave', function() {
                if (rotationInterval) {
                    clearInterval(rotationInterval);
                    rotationInterval = null;
                }
                images.forEach((img, idx) => {
                    img.classList.toggle('active', idx === 0);
                });
                currentIndex = 0;
            });
        });

        // Ellipsis dropdown toggle
        const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');
        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const menu = this.nextElementSibling;

                // Close other open menus
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });

                menu.classList.toggle('active');
            });
        });

        // Close menu when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        });

        // Direct SweetAlert handler for delete forms (backup for global listener)
        document.querySelectorAll('.delete-talent-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                if (form.dataset.swalConfirmed === 'true') return;

                e.preventDefault();
                e.stopPropagation();

                const message = form.dataset.swalConfirm || 'Are you sure?';

                Swal.fire({
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#000000',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.dataset.swalConfirmed = 'true';
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection
