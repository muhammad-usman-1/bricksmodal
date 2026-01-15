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

    /* Override admin layout background if needed, or just style the container */
    
    .talents-head {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        padding: 10px 0 12px;
        margin-bottom: 20px;
    }
    .talents-head h5 { 
        color: #101828;
        font-size: 24px;
        font-weight: 400;
        line-height: 36px;
        margin: 0;
    }
    .talents-head .meta { margin: 4px 0; color: var(--ink-500); font-size: 13px; display: flex; gap: 10px; align-items: center; flex-wrap: wrap; }
    
    .talent-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
    .talent-card { 
        position: relative; 
        background: #f0f1f3; 
        border-radius: 10px; 
        overflow: visible; /* Changed to visible for dropdown to work if it hangs out, but card design usually expects hidden. 
                             If dropdown is inside relative container, we need to be careful. 
                             talents.blade.php uses overflow: hidden on talent-card but dropdown is absolute? 
                             Wait, in talents.blade.php: .talent-card { overflow: hidden; } 
                             But .actions-dropdown-menu has z-index: 100.
                             If container has overflow:hidden, absolute child CANNOT go outside.
                             Let's check talents.blade.php again. 
                             It has .card-ellipsis inside .talent-card.
                             If .talent-card has overflow:hidden, the menu will be cut off.
                             Maybe the user's reference implementation in talents.blade.php actually cuts it off?
                             Or maybe the menu is small enough to fit inside?
                             Or maybe they use Popper? No, custom script.
                             I will set overflow: visible just in case, or manage border radius on the image/overlay.
                          */
        overflow: visible; 
        height: 400px; 
        box-shadow: 0 4px 20px rgba(0,0,0,0.06); 
        border: 1px solid var(--border); 
        transition: transform 0.2s ease; 
        /* To clip image with border radius while keeping overflow visible for dropdowns */
    }
    .talent-card-inner {
        position: absolute; top:0; left:0; right:0; bottom:0;
        border-radius: 10px;
        overflow: hidden; /* Clip image and overlay */
        z-index: 0;
    }

    .talent-card:hover { transform: translateY(-4px); z-index: 5; } /* z-index bump on hover */

    .talent-img { position: absolute; top: 0; left: 0; width: 100%; height: 100%; object-fit: cover; z-index: 1; }
    
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
    .badge-active.status-pending { background: #fffbeb; color: #f59e0b; }
    .badge-active.status-rejected { background: #fef2f2; color: #ef4444; }
    
    .badge-active::before {
        content: ''; width: 6px; height: 6px; background: currentColor; border-radius: 50%;
    }

    .actions-dropdown-container { position: relative; display: inline-block; }
    
    .card-ellipsis { 
        position: absolute !important; 
        top: 12px; 
        right: 15px; 
        z-index: 30; 
    }

    .dropdown-toggle-btn { 
        color: #111; 
        font-size: 16px; 
        cursor: pointer; 
        width: 32px;
        height: 32px;
        background: rgba(255,255,255,0.8); /* Slight background for visibility */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .dropdown-toggle-btn:hover { background: #fff; }

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
        min-width: 180px;
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
    .actions-dropdown-item.text-success { color: #10b981; }
    .actions-dropdown-item.text-success:hover { background: #ecfdf5; }

    .card-overlay {
        position: absolute; left: 0; right: 0; bottom: 0;
        height: 50%;
        padding: 20px 18px 15px;
        background: rgba(0, 0, 0, 0.5); /* Plain light black as requested */
        color: #fff;
        display: flex; flex-direction: column;
        justify-content: flex-end;
        z-index: 10;
        pointer-events: none;
    }

    .overlay-top { position: relative; width: 100%; display: flex; flex-direction: column; align-items: center; margin-bottom: 4px; }
    .overlay-flag { position: absolute; left: 0; top: 0; width: auto; height: 14px; display: block; }
    
    .overlay-meta-info { font-size: 11px; text-transform: uppercase; letter-spacing: 0.06em; color: rgba(255,255,255,0.9); font-weight: 500; }

    .talent-name { font-weight: 600; font-size: 16px; margin: 4px 0 12px; text-align: center; }

    .card-divider { width: 100%; height: 1px; background: rgba(255,255,255,0.3); margin-bottom: 12px; }

    .overlay-bottom { display: flex; justify-content: start; align-items: flex-end; }
    .joined-info { display: flex; flex-direction: column; gap: 2px; }
    .joined-label { font-size: 9px; text-transform: uppercase; letter-spacing: 0.08em; color: rgba(255,255,255,0.7); font-weight: 700; }
    .joined-date { font-size: 12px; font-weight: 500; color: #fff; }

</style>

<div class="talents-shell">
    <div class="talents-head">
        <div>
            <h5>{{ $castingRequirement->project_name }} - Applicants</h5>
            <div class="meta">
                <strong>{{ $applications->count() }} total applicants</strong>
                <span>•</span>
                <span>Manage and review casting applications</span>
            </div>
        </div>
        <a href="{{ route('admin.casting-requirements.index') }}" class="btn btn-outline-secondary btn-sm">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    @if(session('message'))
        <div class="alert alert-success mb-4">{{ session('message') }}</div>
    @endif

    @if($applications->isEmpty())
        <div class="alert alert-info">{{ trans('global.no_applicants_found') }}</div>
    @else
        <div class="talent-grid">
            @foreach($applications as $application)
                @php
                    $profile = $application->talent_profile;
                    $displayName = $profile?->display_name ?? $profile?->legal_name ?? trans('global.not_set');
                    $avatarFallback = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><rect width="120" height="120" rx="18" fill="#f3f1f5"/><circle cx="60" cy="50" r="26" fill="#d9d3de"/><rect x="24" y="82" width="72" height="22" rx="11" fill="#e3dde8"/></svg>');
                    
                    $avatarRaw = $profile?->headshot_center_path ?? ($profile?->headshot_left_path ?? $profile?->headshot_right_path);
                    if (is_array($avatarRaw)) {
                        $avatarCandidate = $avatarRaw['url'] ?? ($avatarRaw['path'] ?? ($avatarRaw[0] ?? null));
                    } else {
                        $avatarCandidate = $avatarRaw;
                    }
                    $avatarSrc = null;
                    if ($avatarCandidate) {
                        if (\Illuminate\Support\Str::startsWith($avatarCandidate, ['http://', 'https://', 'data:'])) {
                            $avatarSrc = $avatarCandidate;
                        } else {
                            $normalized = ltrim($avatarCandidate, '/');
                            $storageRelative = \Illuminate\Support\Str::startsWith($normalized, 'storage/')
                                ? substr($normalized, strlen('storage/'))
                                : $normalized;
                            $storagePath = 'storage/' . $storageRelative;
                            if (file_exists(public_path($storagePath))) {
                                $avatarSrc = asset($storagePath);
                            } elseif (file_exists(public_path($normalized))) {
                                $avatarSrc = asset($normalized);
                            } else {
                                $avatarSrc = asset($storagePath);
                            }
                        }
                    }
                    $avatarSrc = $avatarSrc ?: $avatarFallback;

                    // Status Logic
                    $statusKey = $application->status;
                    $statusLabel = App\Models\CastingApplication::STATUS_SELECT[$statusKey] ?? ucfirst($statusKey);
                    
                    // Override 'did_not_show' to display as 'Pending'
                    if ($statusKey === 'did_not_show') {
                        $statusLabel = 'Pending'; // Or use trans('global.pending') if available, but hardcoding for exact compliance
                    }

                    // Map status to classes for the badge
                    $badgeClass = 'status-pending';
                    if ($statusKey === 'selected') $badgeClass = 'status-selected';
                    if ($statusKey === 'rejected') $badgeClass = 'status-rejected';

                    $gender = $profile?->gender ? strtoupper($profile->gender) : 'N/A';
                    $age = $profile?->date_of_birth ? $profile->date_of_birth->age . ' YEARS' : '';
                    $joinedDate = $application->created_at ? $application->created_at->format('d M Y') : 'N/A';
                    
                    $flagCode = $profile?->country_code ?? null;
                    $flagUrl = $flagCode ? "https://flagcdn.com/24x18/" . strtolower($flagCode) . ".png" : null;
                @endphp

                <div class="talent-card" data-url="{{ route('admin.talent-profiles.show', $profile->id) }}">
                    <div class="talent-card-inner">
                        <img src="{{ $avatarSrc }}" alt="{{ $displayName }}" class="talent-img">
                        <span class="badge-active {{ $badgeClass }}">{{ $statusLabel }}</span>
                        
                        <div class="card-overlay">
                            <div class="overlay-top">
                                @if($flagUrl)
                                    <img src="{{ $flagUrl }}" alt="Flag" class="overlay-flag">
                                @endif
                                <span class="overlay-meta-info">{{ $gender }} @if($age) • {{ $age }} @endif</span>
                            </div>
                            <p class="talent-name">{{ $displayName }}</p>
                            <div class="card-divider"></div>
                            <div class="overlay-bottom">
                                <div class="joined-info">
                                    <span class="joined-label">Joined</span>
                                    <span class="joined-date">{{ $joinedDate }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-ellipsis actions-dropdown-container">
                        <div class="dropdown-toggle-btn"><i class="fas fa-ellipsis-v"></i></div>
                        <div class="actions-dropdown-menu">
                            @if($application->status !== 'selected')
                                <button class="actions-dropdown-item text-success"
                                    data-toggle="modal"
                                    data-target="#approveApplicationModal"
                                    data-route="{{ route('admin.casting-applications.approve', $application) }}"
                                    data-name="{{ $displayName }}">
                                    <i class="fas fa-check"></i> {{ trans('global.approve') }}
                                </button>
                            @endif

                            @if($application->status !== 'shortlisted' && $application->status !== 'selected')
                                <form action="{{ route('admin.casting-applications.shortlist', $application) }}" method="POST" style="margin:0;">
                                    @csrf
                                    <button type="submit" class="actions-dropdown-item text-primary" style="width: 100%; text-align: left;">
                                        <i class="fas fa-list"></i> Shortlist
                                    </button>
                                </form>
                            @endif

                            @if($application->status !== 'rejected')
                                <button class="actions-dropdown-item text-danger"
                                    data-toggle="modal"
                                    data-target="#rejectApplicationModal"
                                    data-route="{{ route('admin.casting-applications.reject', $application) }}"
                                    data-name="{{ $displayName }}">
                                    <i class="fas fa-times"></i> {{ trans('global.reject') }}
                                </button>
                            @endif

                            @if($application->status === 'selected')
                                @php
                                    $paymentPending = in_array($application->payment_status, ['pending', 'rejected', 'requested']);
                                    $isRequest = $application->payment_status === 'requested';
                                    $byAdmin = isset($application->requestedByAdmin);
                                    $isSuper = auth('admin')->user()->isSuperAdmin();
                                    $showPayment = $paymentPending && !($isRequest && $byAdmin && !$isSuper);
                                @endphp
                                @if($showPayment)
                                    <button class="actions-dropdown-item"
                                        data-toggle="modal"
                                        data-target="#requestPaymentModal"
                                        data-route="{{ route('admin.casting-applications.request-payment', $application) }}"
                                        data-name="{{ $displayName }}"
                                        data-rating="{{ is_numeric($application->rating) ? (int) $application->rating : 5 }}">
                                        <i class="fas fa-dollar-sign"></i> Request Payment
                                    </button>
                                @endif
                            @endif

                            @if(optional($profile)->whatsapp_number)
                                <button class="actions-dropdown-item text-success"
                                    data-toggle="modal"
                                    data-target="#applicationWhatsAppModal"
                                    data-number="{{ $profile->whatsapp_number }}"
                                    data-name="{{ $displayName }}"
                                    data-project="{{ optional($application->casting_requirement)->project_name }}"
                                    data-status="{{ $application->status }}">
                                    <i class="fab fa-whatsapp"></i> WhatsApp
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@include('admin.castingRequirements.partials.application-modals')
@endsection

@section('scripts')
@parent
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Card Click Navigation
        const cards = document.querySelectorAll('.talent-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Ignore if clicking on dropdown or modals
                if (e.target.closest('.card-ellipsis') || e.target.closest('.modal')) {
                    return;
                }
                const url = this.getAttribute('data-url');
                if (url) {
                    window.location.href = url;
                }
            });
        });
        
        // Add cursor pointer style
        cards.forEach(card => card.style.cursor = 'pointer');

        // Dropdown toggle logic
        const dropdownBtns = document.querySelectorAll('.dropdown-toggle-btn');
        dropdownBtns.forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const menu = this.nextElementSibling;
                
                // Close other menus
                document.querySelectorAll('.actions-dropdown-menu').forEach(m => {
                    if (m !== menu) m.classList.remove('active');
                });
                
                menu.classList.toggle('active');
            });
        });

        // Close when clicking outside
        document.addEventListener('click', function() {
            document.querySelectorAll('.actions-dropdown-menu').forEach(menu => {
                menu.classList.remove('active');
            });
        });

        // Modal Scripts
        const applicationWhatsappTemplates = {
            selected: @json(trans('notifications.whatsapp_template_application_selected', ['name' => ':name', 'project' => ':project'])),
            rejected: @json(trans('notifications.whatsapp_template_application_rejected', ['name' => ':name', 'project' => ':project'])),
        };

        $('#approveApplicationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var route = button.data('route')
            var name = button.data('name')
            var modal = $(this)
            modal.find('form').attr('action', route)
            modal.find('textarea[name="admin_notes"]').val('')
            modal.find('.modal-title').text(name ? name + ' {{ trans('notifications.approval_modal_title') }}' : '{{ trans('notifications.approval_modal_default_title') }}')
        })

        $('#rejectApplicationModal').on('show.bs.modal', function (event) {
            var button = $(event.relatedTarget)
            var route = button.data('route')
            var name = button.data('name')
            var modal = $(this)
            modal.find('form').attr('action', route)
            modal.find('textarea[name="admin_notes"]').val('')
            modal.find('.modal-title').text(name ? name + ' {{ trans('notifications.rejection_modal_title') }}' : '{{ trans('notifications.rejection_modal_default_title') }}')
        })

        $(document).on('show.bs.modal', '#applicationWhatsAppModal', function (event) {
            var button = $(event.relatedTarget)
            var number = button.data('number') || ''
            var name = button.data('name') || ''
            var project = button.data('project') || ''
            var status = button.data('status') || 'selected'
            var template = applicationWhatsappTemplates[status] || applicationWhatsappTemplates['selected']
            var message = template.replace(':name', name).replace(':project', project)
            $(this).find('#application_whatsapp_message').val(message)
            $('#sendApplicationWhatsApp').data('phone', number)
        })

        $(document).on('click', '#sendApplicationWhatsApp', function () {
            var phone = $(this).data('phone')
            if (!phone) {
                alert('{{ trans('notifications.whatsapp_number_missing') }}')
                return
            }
            var message = $('#application_whatsapp_message').val() || ''
            var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message)
            window.open(url, '_blank')
        })

        const updateStarClasses = function(container, value) {
            container.find('label').each(function () {
                $(this).toggleClass('active', $(this).data('value') <= value)
            })
        }

        $('#requestPaymentModal').on('show.bs.modal', function (event) {
            const button = $(event.relatedTarget)
            const route = button.data('route')
            const name = button.data('name') || ''
            const rating = button.data('rating') || 5
            const modal = $(this)
            const starContainer = modal.find('[data-star-rating]')
            modal.find('form').attr('action', route)
            starContainer.find('input').prop('checked', false)
            starContainer.find(`input[value="${rating}"]`).prop('checked', true)
            updateStarClasses(starContainer, rating)
            modal.find('.modal-title').text(name ? `{{ __('Request Payment') }} — ${name}` : '{{ __('Request Payment') }}')
        })

        $(document).on('click', '[data-star-rating] label', function () {
            const label = $(this)
            const container = label.closest('[data-star-rating]')
            const value = label.data('value')
            container.find(`input[value="${value}"]`).prop('checked', true).trigger('change')
            updateStarClasses(container, value)
        })
    });
</script>
@endsection
