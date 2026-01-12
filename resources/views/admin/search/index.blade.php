@extends('layouts.admin')
@section('content')
<style>
    .search-shell { padding: 12px 0 18px; }
    .search-head { display:flex; justify-content: space-between; align-items: flex-end; gap: 12px; margin-bottom: 14px; }
    .search-title { margin: 0; font-size: 20px; font-weight: 700; color: #111827; }
    .search-sub { margin: 4px 0 0; font-size: 13px; color: #6b7280; }
    .search-card { background: #fff; border: 1px solid #e5e7eb; border-radius: 12px; box-shadow: 0 10px 24px rgba(15,23,42,0.06); overflow:hidden; margin-bottom: 14px; }
    .search-card-head { padding: 12px 14px; border-bottom: 1px solid #eef0f3; display:flex; justify-content: space-between; align-items:center; }
    .search-card-head h4 { margin: 0; font-size: 13px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: #374151; }
    .search-count { font-size: 12px; color: #6b7280; }
    .search-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .search-table th { text-align:left; padding: 10px 14px; font-size: 11px; text-transform: uppercase; letter-spacing: .06em; color:#6b7280; background:#f9fafb; border-bottom: 1px solid #eef0f3; }
    .search-table td { padding: 12px 14px; border-bottom: 1px solid #f0f2f5; color: #374151; }
    .search-table tr:last-child td { border-bottom: none; }
    .search-link { color:#111827; font-weight: 700; text-decoration:none; }
    .search-link:hover,
    .search-link:focus,
    .search-link:active,
    .search-link:visited {
        color: #111827;
        text-decoration: none;
    }
    .pill { display:inline-flex; align-items:center; padding: 4px 10px; border-radius: 999px; font-size: 11px; font-weight: 800; background: #f3f4f6; color: #374151; }
    .empty { padding: 18px 14px; color:#6b7280; font-size: 13px; }
</style>

<div class="search-shell">
    <div class="search-head">
        <div>
            <h3 class="search-title">Search</h3>
            <p class="search-sub">
                @if($q !== '')
                    Results for <strong>{{ $q }}</strong>
                @else
                    Type a query in the header search box and press Enter.
                @endif
            </p>
        </div>
    </div>

    @if($q !== '' && !$canSeeTalents && !$canSeeShoots)
        <div class="search-card">
            <div class="empty">You don’t have access to search talents or shoots.</div>
        </div>
    @endif

    @if($canSeeTalents)
        <div class="search-card">
            <div class="search-card-head">
                <h4>Talents</h4>
                <div class="search-count">{{ $talents->count() }} shown</div>
            </div>
            @if($q === '')
                <div class="empty">Enter a search query to find talents.</div>
            @elseif($talents->isEmpty())
                <div class="empty">No talents matched.</div>
            @else
                <div style="overflow-x:auto;">
                    <table class="search-table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($talents as $talent)
                            <tr>
                                <td>
                                    <a class="search-link" href="{{ route('admin.talent-profiles.show', $talent->id) }}">
                                        {{ $talent->display_name ?: trim(($talent->first_name ?? '') . ' ' . ($talent->last_name ?? '')) ?: ('Talent #' . $talent->id) }}
                                    </a>
                                </td>
                                <td>{{ $talent->user->email ?? '-' }}</td>
                                <td><span class="pill">{{ ucfirst($talent->verification_status ?? 'n/a') }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    @if($canSeeShoots)
        <div class="search-card">
            <div class="search-card-head">
                <h4>Shoots</h4>
                <div class="search-count">{{ $shoots->count() }} shown</div>
            </div>
            @if($q === '')
                <div class="empty">Enter a search query to find shoots.</div>
            @elseif($shoots->isEmpty())
                <div class="empty">No shoots matched.</div>
            @else
                <div style="overflow-x:auto;">
                    <table class="search-table">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Client</th>
                            <th>Location</th>
                            <th>Status</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($shoots as $shoot)
                            <tr>
                                <td>
                                    <a class="search-link" href="{{ route('admin.casting-requirements.show', $shoot->id) }}">
                                        {{ $shoot->project_name ?: ('Shoot #' . $shoot->id) }}
                                    </a>
                                </td>
                                <td>{{ $shoot->client_name ?? '-' }}</td>
                                <td>{{ $shoot->location ?? '-' }}</td>
                                <td><span class="pill">{{ ucfirst($shoot->status ?? 'n/a') }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif
</div>
@endsection


