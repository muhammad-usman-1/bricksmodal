@extends('layouts.admin')
@section('content')
@can('casting_application_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.casting-applications.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.castingApplication.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.castingApplication.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-CastingApplication">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.castingApplication.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingApplication.fields.casting_requirement') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingApplication.fields.talent_profile') }}
                        </th>
                        <th>
                            {{ trans('cruds.talentProfile.fields.display_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingApplication.fields.rate') }}
                        </th>

                        <th>
                            {{ trans('cruds.castingApplication.fields.status') }}
                        </th>

                        <th>
                           Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($castingApplications as $key => $castingApplication)
                        <tr data-entry-id="{{ $castingApplication->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $castingApplication->id ?? '' }}
                            </td>
                            <td>
                                {{ $castingApplication->casting_requirement->project_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingApplication->talent_profile->legal_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingApplication->talent_profile->display_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingApplication->rate ?? '' }}
                            </td>

                            <td>
                                {{ App\Models\CastingApplication::STATUS_SELECT[$castingApplication->status] ?? '' }}
                            </td>

                            <td>
                                @can('casting_application_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.casting-applications.show', $castingApplication->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('casting_application_manage')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.casting-applications.edit', $castingApplication->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                    @if($castingApplication->status !== 'selected')
                                        <button class="btn btn-xs btn-success" type="button"
                                            data-toggle="modal"
                                            data-target="#approveApplicationModal"
                                            data-route="{{ route('admin.casting-applications.approve', $castingApplication) }}"
                                            data-name="{{ $castingApplication->talent_profile->display_name ?? $castingApplication->talent_profile->legal_name }}">
                                            {{ trans('global.approve') }}
                                        </button>
                                    @endif
                                @endcan

                                @can('casting_application_delete')
                                    <form action="{{ route('admin.casting-applications.destroy', $castingApplication->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

                            </td>

                        </tr>
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
    $(function () {
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('casting_application_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.casting-applications.massDestroy') }}",
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
  let table = $('.datatable-CastingApplication:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

$('#approveApplicationModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget)
    var route = button.data('route')
    var name = button.data('name')
    var modal = $(this)

    modal.find('form').attr('action', route)
    modal.find('textarea[name="admin_notes"]').val('')
    modal.find('.modal-title').text(name ? name + ' {{ trans('notifications.approval_modal_title') }}' : '{{ trans('notifications.approval_modal_default_title') }}')
})

</script>
<div class="modal fade" id="approveApplicationModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ trans('notifications.approval_modal_default_title') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="POST" action="#">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="admin_notes">{{ trans('cruds.castingApplication.fields.admin_notes') }}</label>
                        <textarea name="admin_notes" id="admin_notes" class="form-control" rows="4"></textarea>
                        <small class="form-text text-muted">{{ trans('notifications.approval_modal_note_help') }}</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                    <button type="submit" class="btn btn-success">{{ trans('global.approve') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
