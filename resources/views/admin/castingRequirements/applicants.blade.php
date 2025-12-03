@extends('layouts.admin')
@section('content')

<div class="card shadow-sm border-0">
    <div class="card-header bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div>
            <p class="text-uppercase text-muted mb-1 small">{{ trans('global.applicants') }}</p>
            <h4 class="mb-0">{{ $castingRequirement->project_name }}</h4>
        </div>
        <a href="{{ route('admin.casting-requirements.index') }}" class="btn btn-outline-secondary btn-sm mt-3 mt-md-0">
            {{ trans('global.back_to_list') }}
        </a>
    </div>

    @php
        $avatarFallback = 'data:image/svg+xml;utf8,' . rawurlencode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 120 120"><rect width="120" height="120" rx="18" fill="#f3f1f5"/><circle cx="60" cy="50" r="26" fill="#d9d3de"/><rect x="24" y="82" width="72" height="22" rx="11" fill="#e3dde8"/></svg>');
        $formatMetric = function ($value, $unit) {
            if ($value === null || $value === '') {
                return null;
            }
            if (is_numeric($value)) {
                $formatted = rtrim(rtrim(number_format((float) $value, 1, '.', ''), '0'), '.');
            } else {
                $formatted = $value;
            }
            return trim($formatted . ' ' . $unit);
        };
        $statusBadgeMap = [
            'selected'    => 'success',
            'shortlisted' => 'primary',
            'pending'     => 'warning',
            'under_review'=> 'info',
            'rejected'    => 'danger',
        ];
        $paymentBadgeMap = [
            'requested' => 'info',
            'approved'  => 'primary',
            'released'  => 'warning',
            'received'  => 'success',
            'rejected'  => 'danger',
        ];
        $paymentStatusLabel = trans('cruds.castingApplication.fields.payment_status');
        if ($paymentStatusLabel === 'cruds.castingApplication.fields.payment_status') {
            $paymentStatusLabel = trans('global.payment_status');
        }
        if ($paymentStatusLabel === 'global.payment_status') {
            $paymentStatusLabel = trans('global.payment');
        }
        if ($paymentStatusLabel === 'global.payment') {
            $paymentStatusLabel = __('Payment Status');
        }
    @endphp

    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if($applications->isEmpty())
            <div class="alert alert-info mb-0">{{ trans('global.no_applicants_found') }}</div>
        @else
            <style>
                .applicant-grid {
                    display: grid;
                    grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
                    gap: 1.5rem;
                }

                .applicant-card {
                    border: 1px solid #e9e7ef;
                    border-radius: 20px;
                    padding: .25rem;
                    display: flex;
                    flex-direction: column;
                    height: 100%;
                    box-shadow: 0 10px 30px rgba(15, 23, 42, 0.05);
                    transition: transform 0.2s ease, box-shadow 0.2s ease;
                }

                .applicant-card:hover {
                    transform: translateY(-4px);
                    box-shadow: 0 18px 40px rgba(15, 23, 42, 0.08);
                }

                .applicant-photo {
                    width: 100%;
                    aspect-ratio: 3 / 4;
                    border-radius: 18px;
                    overflow: hidden;
                    margin-bottom: 1rem;
                    background: linear-gradient(135deg, #f4f0ff, #e5f2ff);
                }

                .applicant-photo img {
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                }

                .applicant-name {
                    font-size: 1.05rem;
                    font-weight: 600;
                    color: #111827;
                }

                .applicant-meta {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                    font-size: 0.85rem;
                    color: #6b7280;
                }

                .applicant-meta span::before {
                    content: '•';
                    margin-right: 0.35rem;
                    color: #c0c4d6;
                }

                .applicant-meta span:first-child::before {
                    content: '';
                    margin: 0;
                }

                .applicant-chips {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                    margin-top: 0.85rem;
                }

                .applicant-chip {
                    background: #f5f7fa;
                    border-radius: 999px;
                    padding: 0.35rem 0.75rem;
                    font-size: 0.78rem;
                    color: #4b5563;
                }

                .applicant-note {
                    font-size: 0.85rem;
                    color: #4b5563;
                    margin-top: 0.85rem;
                }

                .applicant-note small {
                    color: #9ca3af;
                    text-transform: uppercase;
                    letter-spacing: 0.04em;
                    font-weight: 600;
                }

                .applicant-actions {
                    margin-top: auto;
                    display: flex;
                    flex-wrap: wrap;
                    gap: 0.5rem;
                }

                .applicant-actions .btn {
                    font-size: 0.75rem;
                }

                .star-rating {
                    display: inline-flex;
                    flex-direction: row-reverse;
                    gap: 6px;
                }

                .star-rating input {
                    display: none;
                }

                .star-rating label {
                    font-size: 1.6rem;
                    color: #d9d3de;
                    cursor: pointer;
                    transition: color .15s ease;
                }

                .star-rating label.active {
                    color: #f5a623;
                }
            </style>

            <div class="applicant-grid">
                @foreach($applications as $application)
                    @php
                        $profile = $application->talent_profile;
                        $displayName = $profile?->display_name ?? $profile?->legal_name ?? trans('global.not_set');
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

                        $statusLabel = App\Models\CastingApplication::STATUS_SELECT[$application->status] ?? ($application->status ? ucfirst($application->status) : trans('global.not_set'));
                        $statusClass = $statusBadgeMap[$application->status] ?? 'secondary';

                        $paymentLabel = App\Models\CastingApplication::PAYMENT_STATUS_SELECT[$application->payment_status] ?? ($application->payment_status ? ucfirst($application->payment_status) : trans('global.not_set'));
                        $paymentClass = $paymentBadgeMap[$application->payment_status] ?? 'secondary';

                        $metaChips = array_values(array_filter([
                            $profile?->gender ? ucfirst($profile->gender) : null,
                            $formatMetric($profile?->height, 'cm'),
                            $formatMetric($profile?->weight, 'kg'),
                        ]));

                        $ratingValue = is_numeric($application->rating) ? (int) $application->rating : 5;
                        if ($ratingValue < 1 || $ratingValue > 5) {
                            $ratingValue = 5;
                        }
                        $reviewValue = $application->reviews;
                        if (is_array($reviewValue)) {
                            $reviewValue = implode(', ', array_filter($reviewValue, fn($item) => is_scalar($item)));
                        }
                    @endphp
                    <div class="applicant-card">
                        <div class="applicant-photo">
                            <img src="{{ $avatarSrc }}" alt="{{ $displayName }}">
                        </div>
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="applicant-name mb-1">{{ $displayName }}</p>
                                @if(count($metaChips))
                                    <div class="applicant-meta">
                                        @foreach($metaChips as $chip)
                                            <span>{{ $chip }}</span>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                            <span class="badge badge-{{ $statusClass }}">{{ $statusLabel }}</span>
                        </div>

                        <div class="applicant-chips">
                            <span class="applicant-chip">
                                {{ trans('cruds.castingApplication.fields.rate') }}:
                                <strong>{{ $application->rate ?? trans('global.not_set') }}</strong>
                            </span>
                            <span class="applicant-chip text-{{ $paymentClass }}">
                                {{ $paymentStatusLabel }}:
                                <strong>{{ $paymentLabel }}</strong>
                            </span>
                        </div>

                        @if($application->talent_notes)
                            <p class="applicant-note mb-0">
                                <small>{{ trans('cruds.castingApplication.fields.talent_notes') }}</small><br>
                                “{{ \Illuminate\Support\Str::limit($application->talent_notes, 120) }}”
                            </p>
                        @endif

                        @if($application->admin_notes)
                            <p class="applicant-note mb-0">
                                <small>{{ trans('cruds.castingApplication.fields.admin_notes') }}</small><br>
                                {{ \Illuminate\Support\Str::limit($application->admin_notes, 120) }}
                            </p>
                        @endif

                        <div class="applicant-actions">
                            @can('talent_profile_show')
                                @if($profile)
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.talent-profiles.show', $profile->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endif
                            @endcan

                            @if($application->status !== 'selected')
                                <button class="btn btn-sm btn-success"
                                    data-toggle="modal"
                                    data-target="#approveApplicationModal"
                                    data-route="{{ route('admin.casting-applications.approve', $application) }}"
                                    data-name="{{ $displayName }}">
                                    {{ trans('global.approve') }}
                                </button>
                            @endif

                            @if($application->status !== 'rejected')
                                <button class="btn btn-sm btn-outline-danger"
                                    data-toggle="modal"
                                    data-target="#rejectApplicationModal"
                                    data-route="{{ route('admin.casting-applications.reject', $application) }}"
                                    data-name="{{ $displayName }}">
                                    {{ trans('global.reject') }}
                                </button>
                            @endif

                            @if($application->status === 'selected' && in_array($application->payment_status, ['pending', 'rejected', 'requested']))
                                @php
                                    $requestingAdmin = $application->requestedByAdmin;
                                    $requestMessage = $requestingAdmin
                                        ? __('Payment requested by :name', ['name' => $requestingAdmin->name])
                                        : null;
                                @endphp
                                @if(auth('admin')->user()->isSuperAdmin())
                                    @if($application->payment_status === 'requested' && $requestMessage)
                                        <span class="badge badge-info align-self-center">{{ $requestMessage }}</span>
                                    @endif
                                @else
                                    @if($application->payment_status === 'requested' && $requestMessage)
                                        <span class="badge badge-info align-self-center">{{ $requestMessage }}</span>
                                    @else
                                        <button
                                            class="btn btn-sm btn-outline-primary request-payment-btn"
                                            data-toggle="modal"
                                            data-target="#requestPaymentModal"
                                            data-route="{{ route('admin.casting-applications.request-payment', $application) }}"
                                            data-name="{{ $displayName }}"
                                            data-rating="{{ $ratingValue }}"
                                            data-review="{{ $reviewValue ?? '' }}"
                                        >
                                           Request for Payment.
                                        </button>
                                    @endif
                                @endif
                            @elseif($application->payment_status === 'requested')
                                <span class="badge badge-info align-self-center">Payment Requested</span>
                            @elseif($application->payment_status === 'approved')
                                <span class="badge badge-primary align-self-center">Payment Approved</span>
                            @elseif($application->payment_status === 'released')
                                <span class="badge badge-warning align-self-center">Payment Released</span>
                            @elseif($application->payment_status === 'received')
                                <span class="badge badge-success align-self-center">Payment Received</span>
                            @endif

                            @if(optional($application->talent_profile)->whatsapp_number)
                                <button class="btn btn-sm btn-outline-success application-whatsapp-button"
                                    data-toggle="modal"
                                    data-target="#applicationWhatsAppModal"
                                    data-number="{{ $application->talent_profile->whatsapp_number }}"
                                    data-name="{{ $displayName }}"
                                    data-project="{{ optional($application->casting_requirement)->project_name }}"
                                    data-status="{{ $application->status }}">
                                    {{ trans('notifications.send_whatsapp') }}
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

@include('admin.castingRequirements.partials.application-modals')
@endsection

@section('scripts')
    @parent
    <script>
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
            if (!message.trim()) {
                alert('{{ trans('notifications.whatsapp_message_required') }}')
                return
            }

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
            const review = button.data('review') || ''
            const modal = $(this)
            const starContainer = modal.find('[data-star-rating]')

            modal.find('form').attr('action', route)
            modal.find('textarea[name="reviews"]').val(review)
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
    </script>
@endsection
