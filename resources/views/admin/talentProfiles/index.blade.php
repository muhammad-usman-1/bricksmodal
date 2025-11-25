@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('cruds.talentProfile.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-TalentProfile">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.talentProfile.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.talentProfile.fields.legal_name') }}
                        </th>


                        <th>
                            {{ trans('cruds.talentProfile.fields.verification_status') }}
                        </th>


                        <th>
                            {{ trans('cruds.talentProfile.fields.daily_rate') }}
                        </th>
                        <th>
                            {{ trans('cruds.talentProfile.fields.hourly_rate') }}
                        </th>


                        <th>
                            {{ trans('cruds.talentProfile.fields.user') }}
                        </th>
                        <th>
                           Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($talentProfiles as $key => $talentProfile)
                        @if($talentProfile->verification_status === 'approved')
                        <tr data-entry-id="{{ $talentProfile->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $talentProfile->id ?? '' }}
                            </td>
                            <td>
                                {{ $talentProfile->legal_name ?? '' }}
                            </td>

                            <td>
                                {{ App\Models\TalentProfile::VERIFICATION_STATUS_SELECT[$talentProfile->verification_status] ?? '' }}
                            </td>
                            <!-- #endregion -->

                            <td>
                                {{ $talentProfile->daily_rate ?? '' }}
                            </td>
                            <td>
                                {{ $talentProfile->hourly_rate ?? '' }}
                            </td>

                            <td>
                                {{ $talentProfile->user->name ?? '' }}
                            </td>
                            <td>
                                @can('talent_profile_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.talent-profiles.show', $talentProfile->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('talent_profile_edit')
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($talentProfile->verification_status !== 'approved')
                                            <button class="btn btn-success" type="button"
                                                data-toggle="modal"
                                                data-target="#talentActionModal"
                                                data-action="approve"
                                                data-route="{{ route('admin.talent-profiles.approve', $talentProfile) }}"
                                                data-name="{{ $talentProfile->display_name ?? $talentProfile->legal_name }}"
                                                data-whatsapp="{{ $talentProfile->whatsapp_number }}">
                                                {{ trans('global.approve') }}
                                            </button>
                                        @endif
                                        @if($talentProfile->verification_status !== 'rejected')
                                            <button class="btn btn-warning" type="button"
                                                data-toggle="modal"
                                                data-target="#talentActionModal"
                                                data-action="reject"
                                                data-route="{{ route('admin.talent-profiles.reject', $talentProfile) }}"
                                                data-name="{{ $talentProfile->display_name ?? $talentProfile->legal_name }}"
                                                data-whatsapp="{{ $talentProfile->whatsapp_number }}">
                                                {{ trans('global.reject') }}
                                            </button>
                                        @endif
                                        @if($talentProfile->verification_status === 'rejected')
                                            <button class="btn btn-info" type="button"
                                                data-toggle="modal"
                                                data-target="#talentActionModal"
                                                data-action="reactivate"
                                                data-route="{{ route('admin.talent-profiles.reactivate', $talentProfile) }}"
                                                data-name="{{ $talentProfile->display_name ?? $talentProfile->legal_name }}"
                                                data-whatsapp="{{ $talentProfile->whatsapp_number }}">
                                                {{ trans('global.reactivate') }}
                                            </button>
                                        @endif
                                    </div>
                                @endcan

                                @can('talent_profile_delete')
                                    <form action="{{ route('admin.talent-profiles.destroy', $talentProfile->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
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

    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('talent_profile_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.talent-profiles.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    orderCellsTop: true,
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-TalentProfile:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

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
