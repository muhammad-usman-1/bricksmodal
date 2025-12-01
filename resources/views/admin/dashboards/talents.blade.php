@extends('layouts.admin')
@section('content')
<div class="content">
    <div class="row">
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="text-value">{{ $stats['total'] }}</div>
                    <div>{{ trans('global.talents_total') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="text-value">{{ $stats['approved'] }}</div>
                    <div>{{ trans('global.talents_approved') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="text-value">{{ $stats['pending'] }}</div>
                    <div>{{ trans('global.talents_pending') }}</div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6 mb-3">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <div class="text-value">{{ $stats['rejected'] }}</div>
                    <div>{{ trans('global.talents_rejected') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-none">
        <div class="card-header border-0 bg-transparent pr-0 pl-3 d-flex flex-column flex-md-row justify-content-between align-items-md-center">
            <div>
                <span class="text-uppercase text-muted small">{{ trans('global.recent_talents') }}</span>
            </div>
        </div>
        <div class="card-body px-0 px-md-4">
            @php
                $fallbackSvg = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><rect width="120" height="120" rx="20" fill="#f3f1f5"/><circle cx="60" cy="48" r="26" fill="#d9d3de"/><rect x="26" y="80" width="68" height="20" rx="10" fill="#e3dde8"/></svg>');
                $statusColor = [
                    'approved' => 'success',
                    'pending' => 'warning',
                    'rejected' => 'danger',
                    'under_review' => 'info',
                ];
            @endphp
            @if($talents->isEmpty())
                <div class="text-center text-muted py-4">{{ trans('global.no_talents_found') }}</div>
            @else
                <style>
                    .verified-badge {
                        display: inline-flex;
                        width: 20px;
                        height: 18px;
                        margin-left: 6px;
                    }
                    .verified-badge svg {
                        width: 100%;
                        height: 100%;
                        display: block;
                    }
                    .talent-card {
                        border: 1px solid #ece9f3;
                        border-radius: 24px;
                        padding: .25rem;
                        box-shadow: 0 20px 35px rgba(15, 23, 42, 0.07);
                        display: flex;
                        flex-direction: column;
                        height: 100%;
                        transition: transform .2s ease, box-shadow .2s ease;
                    }
                    .talent-card:hover {
                        transform: translateY(-4px);
                        box-shadow: 0 25px 45px rgba(15, 23, 42, 0.12);
                    }
                    .talent-avatar {
                        width: 100%;
                        aspect-ratio: 3 / 4;
                        border-radius: 20px;
                        overflow: hidden;
                        margin-bottom: 1rem;
                        background: linear-gradient(135deg, #f5f0ff, #edf7ff);
                    }
                    .talent-avatar img {
                        width: 100%;
                        height: 100%;
                        object-fit: cover;
                    }
                    .talent-name {
                        font-size: 1rem;
                        font-weight: 600;
                        color: #0f172a;
                        margin-bottom: .2rem;
                        display: inline-flex;
                        align-items: center;
                        gap: .35rem;
                    }
                    .talent-meta {
                        font-size: .85rem;
                        color: #6b7280;
                        margin-bottom: .35rem;
                    }
                    .talent-meta span::after {
                        content: '•';
                        margin: 0 .35rem;
                        color: #d1d5db;
                    }
                    .talent-meta span:last-child::after {
                        content: '';
                        margin: 0;
                    }
                    .talent-chips {
                        display: flex;
                        flex-wrap: wrap;
                        gap: .45rem;
                        margin-bottom: .75rem;
                    }
                    .talent-chip {
                        font-size: .78rem;
                        border-radius: 999px;
                        padding: .25rem .65rem;
                        background: #f4f6fb;
                        color: #4b5563;
                    }
                    .talent-actions {
                        margin-top: auto;
                        display: flex;
                        flex-wrap: wrap;
                        gap: .4rem;
                    }
                </style>
                @foreach($talents->chunk(4) as $talentChunk)
                    <div class="row">
                    @foreach($talentChunk as $talent)
                        @php
                            $displayName = $talent->display_name ?? $talent->legal_name ?? trans('global.not_set');
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
                            $avatar = $avatar ?: $fallbackSvg;

                            $gender = $talent->gender ? ucfirst($talent->gender) : trans('global.not_set');
                            $dob = optional($talent->date_of_birth)->format('M d, Y') ?? trans('global.not_set');
                            $onboard = optional($talent->onboarding_completed_at)->format('M d, Y') ?? trans('global.not_set');
                            $status = $talent->verification_status ?? 'pending';
                            $statusLabel = App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$status] ?? ucfirst($status);
                            $statusClass = $statusColor[$status] ?? 'secondary';
                        @endphp
                        <div class="col-xl-3 col-lg-4 col-md-6 mb-4 d-flex">
                            <div class="talent-card w-100">
                                <div class="talent-avatar">
                                    <img src="{{ $avatar }}" alt="{{ $displayName }}">
                                </div>
                                <a href="{{ route('admin.talent-profiles.show', $talent->id) }}" class="talent-name">
                                    {{ $displayName }}
                                    @if($status === 'approved')
                                        <span class="verified-badge" aria-label="{{ trans('global.verified') }}">
                                            <svg viewBox="0 0 60 60" role="img" aria-hidden="true">
                                                <path fill="#1DA1F2" d="M32 2c-2 0-4 .7-5.6 2l-4.3 3.5-5.5-1.3A9 9 0 0 0 6.1 12l-1.1 5.6-5.2 2.9A9 9 0 0 0 0 30.6l2.2 5.2L0 41a9 9 0 0 0 1.9 10.1l5.2 2.9 1.1 5.6a9 9 0 0 0 10.5 6.9l5.5-1.3 4.3 3.5a9 9 0 0 0 11.2 0l4.3-3.5 5.5 1.3a9 9 0 0 0 10.5-6.9l1.1-5.6 5.2-2.9A9 9 0 0 0 64 41l-2.2-5.2L64 30.6a9 9 0 0 0-1.9-10l-5.2-3-1.1-5.6a9 9 0 0 0-10.5-6.9l-5.5 1.3-4.3-3.5A9 9 0 0 0 32 2Z"/>
                                                <polyline fill="none" stroke="#FFFFFF" stroke-width="4.5" stroke-linecap="round" stroke-linejoin="round" points="20 34 29.5 43.5 46 23"/>
                                            </svg>
                                        </span>
                                    @endif
                                </a>
                                <div class="talent-meta">
                                    <span>{{ $gender }}</span>
                                   DOB: <span>{{ $dob }}</span>
                                </div>
                                <div class="talent-chips">
                                    <span class="talent-chip">
                                        Onboarding  Completed:
                                        <strong>{{ $onboard }}</strong>
                                    </span>
                                    <span class="badge badge-pill badge-{{ $statusClass }} px-3 py-1">
                                        {{ $statusLabel }}
                                    </span>
                                </div>
                                <div class="talent-actions">
                                    @can('talent_profile_show')
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.talent-profiles.show', $talent->id) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('talent_profile_edit')
                                        @if($talent->verification_status !== 'approved')
                                            <button class="btn btn-sm btn-success"
                                                data-toggle="modal"
                                                data-target="#talentActionModal"
                                                data-action="approve"
                                                data-route="{{ route('admin.talent-profiles.approve', $talent) }}"
                                                data-name="{{ $displayName }}"
                                                data-whatsapp="{{ $talent->whatsapp_number }}">
                                                {{ trans('global.approve') }}
                                            </button>
                                        @endif
                                        @if($talent->verification_status !== 'rejected')
                                            <button class="btn btn-sm btn-outline-warning"
                                                data-toggle="modal"
                                                data-target="#talentActionModal"
                                                data-action="reject"
                                                data-route="{{ route('admin.talent-profiles.reject', $talent) }}"
                                                data-name="{{ $displayName }}"
                                                data-whatsapp="{{ $talent->whatsapp_number }}">
                                                {{ trans('global.reject') }}
                                            </button>
                                        @endif
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
    const whatsappTemplates = {
        approve: @json(trans('notifications.whatsapp_template_approved', ['name' => ':name'])),
        reject: @json(trans('notifications.whatsapp_template_rejected', ['name' => ':name'])),
        reactivate: @json(trans('notifications.whatsapp_template_reactivated', ['name' => ':name'])),
    };

    $(document).on('show.bs.modal', '#talentActionModal', function (event) {
        var button = $(event.relatedTarget)
        var action = button.data('action')
        var route = button.data('route')
        var name = button.data('name') || ''
        var whatsapp = button.data('whatsapp') || ''
        var modal = $(this)
        var notesField = modal.find('textarea[name="notes"]')
        var notesGroup = modal.find('.notes-group')
        var whatsappField = modal.find('#whatsapp_message')
        var whatsappButton = modal.find('#sendWhatsAppAction')

        modal.find('form').attr('action', route)
        notesField.val('')
        modal.find('.modal-title').text(action.charAt(0).toUpperCase() + action.slice(1) + ' {{ trans('cruds.talentProfile.title_singular') }}')

        if (action === 'approve') {
            notesGroup.hide()
            notesField.prop('required', false)
        } else {
            notesGroup.show()
            notesField.prop('required', true)
        }

        var template = whatsappTemplates[action] || ''
        var defaultMessage = template.replace(':name', name)
        whatsappField.val(defaultMessage)

        if (whatsapp) {
            whatsappButton.prop('disabled', false)
                .data('phone', whatsapp)
                .data('name', name)
                .data('action', action)
                .removeClass('disabled')
                .attr('title', '')
        } else {
            whatsappButton.prop('disabled', true)
                .data('phone', '')
                .addClass('disabled')
                .attr('title', '{{ trans('notifications.whatsapp_number_missing') }}')
        }
    })

    $(document).on('click', '#sendWhatsAppAction', function () {
        var phone = $(this).data('phone')
        if (!phone) {
            alert('{{ trans('notifications.whatsapp_number_missing') }}')
            return
        }

        var message = $('#whatsapp_message').val() || ''
        if (!message.trim()) {
            alert('{{ trans('notifications.whatsapp_message_required') }}')
            return
        }

        var url = 'https://wa.me/' + phone + '?text=' + encodeURIComponent(message)
        window.open(url, '_blank')
    })
</script>
@include('admin.talentProfiles.partials.action-modal')
@endsection
