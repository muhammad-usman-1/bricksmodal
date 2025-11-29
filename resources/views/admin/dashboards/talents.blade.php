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
        <div class="card-header border-0 bg-transparent px-0">
            <span>{{ trans('global.recent_talents') }}</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-sm mb-0 align-middle">
                    <thead>
                        <tr class="bg-dark text-white">
                            <th class="py-3">{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                            <th class="py-3">{{ trans('cruds.talentProfile.fields.verification_status') }}</th>
                            <th class="py-3">{{ trans('global.gender') }}</th>
                            <th class="py-3">{{ trans('global.date_of_birth') }}</th>
                            <th class="py-3">Onboarding Completed at</th>
                            <th class="py-3 text-right pr-4">{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($talents as $talent)
                            <tr class="border-top">
                                <td class="py-3">
                                    <a href="{{ route('admin.talent-profiles.show', $talent->id) }}">
                                        {{ $talent->display_name ?? $talent->legal_name }}
                                    </a>
                                </td>
                                <td class="py-3">{{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talent->verification_status] ?? trans('global.not_set') }}</td>
                                <td class="py-3 text-capitalize">{{ $talent->gender ?? trans('global.not_set') }}</td>
                                <td class="py-3">{{ optional($talent->date_of_birth)->format('M d, Y') ?? trans('global.not_set') }}</td>
                                <td class="py-3">{{ optional($talent->created_at)->format('M d, Y') ?? trans('global.not_set') }}</td>
                                <td class="py-3 text-right text-nowrap pr-4">
                                    @can('talent_profile_edit')
                                        <div class="d-inline-flex align-items-center gap-2">
                                            @if($talent->verification_status !== 'approved')
                                                <button class="btn btn-sm btn-success mr-1" type="button"
                                                    data-toggle="modal"
                                                    data-target="#talentActionModal"
                                                    data-action="approve"
                                                    data-route="{{ route('admin.talent-profiles.approve', $talent) }}"
                                                    data-name="{{ $talent->display_name ?? $talent->legal_name }}"
                                                    data-whatsapp="{{ $talent->whatsapp_number }}">
                                                    {{ trans('global.approve') }}
                                                </button>
                                            @endif
                                            @if($talent->verification_status !== 'rejected')
                                                <button class="btn btn-sm btn-warning" type="button"
                                                    data-toggle="modal"
                                                    data-target="#talentActionModal"
                                                    data-action="reject"
                                                    data-route="{{ route('admin.talent-profiles.reject', $talent) }}"
                                                    data-name="{{ $talent->display_name ?? $talent->legal_name }}"
                                                    data-whatsapp="{{ $talent->whatsapp_number }}">
                                                    {{ trans('global.reject') }}
                                                </button>
                                            @endif
                                        </div>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ trans('global.no_talents_found') }}
                                </td>
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
