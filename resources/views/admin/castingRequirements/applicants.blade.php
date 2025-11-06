@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <div>
            <h5 class="mb-0">{{ $castingRequirement->project_name }}</h5>
            <small class="text-muted">{{ trans('global.applicants') }}</small>
        </div>
        <a href="{{ route('admin.casting-requirements.index') }}" class="btn btn-sm btn-secondary">{{ trans('global.back_to_list') }}</a>
    </div>

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
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ trans('cruds.talentProfile.fields.display_name') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.rate') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.talent_notes') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.status') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.payment_processed') }}</th>
                            <th>{{ trans('cruds.castingApplication.fields.admin_notes') }}</th>
                            <th>{{ trans('global.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($applications as $application)
                            <tr>
                                <td>{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name }}</td>
                                <td>{{ $application->rate ?? trans('global.not_set') }}</td>
                                <td>{{ $application->talent_notes ?? trans('global.not_set') }}</td>
                                <td>{{ App\Models\CastingApplication::STATUS_SELECT[$application->status] ?? $application->status }}</td>
                                <td>{{ App\Models\CastingApplication::PAYMENT_PROCESSED_SELECT[$application->payment_processed] ?? $application->payment_processed }}</td>
                                <td>{{ $application->admin_notes ?? trans('global.not_set') }}</td>
                                <td class="d-flex flex-wrap gap-1" style="gap:4px;">
                                    @if($application->status !== 'selected')
                                        <button class="btn btn-xs btn-success" data-toggle="modal" data-target="#approveApplicationModal" data-route="{{ route('admin.casting-applications.approve', $application) }}" data-name="{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name }}">
                                            {{ trans('global.approve') }}
                                        </button>
                                    @endif
                                    @if($application->status !== 'rejected')
                                        <button class="btn btn-xs btn-danger" data-toggle="modal" data-target="#rejectApplicationModal" data-route="{{ route('admin.casting-applications.reject', $application) }}" data-name="{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name }}">
                                            {{ trans('global.reject') }}
                                        </button>
                                    @endif
                                    @if($application->status === 'selected' && $application->payment_processed !== 'paid')
                                        <form method="POST" action="{{ route('admin.casting-applications.pay', $application) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-xs btn-outline-primary">{{ trans('global.pay_now') }}</button>
                                        </form>
                                    @endif
                                    @if(optional($application->talent_profile)->whatsapp_number)
                                        <button class="btn btn-xs btn-outline-success application-whatsapp-button"
                                            data-toggle="modal"
                                            data-target="#applicationWhatsAppModal"
                                            data-number="{{ $application->talent_profile->whatsapp_number }}"
                                            data-name="{{ optional($application->talent_profile)->display_name ?? optional($application->talent_profile)->legal_name }}"
                                            data-project="{{ optional($application->casting_requirement)->project_name }}"
                                            data-status="{{ $application->status }}">
                                            {{ trans('notifications.send_whatsapp') }}
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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
    </script>
@endsection
