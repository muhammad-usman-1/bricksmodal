@extends('layouts.admin')
@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
            <div>
                <h5 class="mb-1">Casting Requirement List</h5>
                <p class="text-muted mb-0">Manage all shoots from here.</p>
            </div>
            <div class="d-flex flex-column flex-md-row gap-2 mt-3 mt-lg-0">
                @can('casting_requirement_create')
                    <a class="btn btn-primary" href="{{ route('admin.casting-requirements.create') }}">
                        + Add New Shoot
                    </a>
                @endcan
            </div>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 datatable datatable-CastingRequirement align-middle">
                <thead class="bg-dark">
                    <tr>
                        <th>Shoot Name</th>
                        <th>{{ trans('cruds.castingRequirement.fields.location') }}</th>
                        <th>{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</th>
                        <th>{{ trans('cruds.castingRequirement.fields.status') }}</th>
                        <th>{{ trans('global.applicants') }}</th>
                        <th class="text-right">{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($castingRequirements as $castingRequirement)
                        <tr data-entry-id="{{ $castingRequirement->id }}">
                            <td class="font-weight-semibold">{{ $castingRequirement->project_name ?? '' }}</td>
                            <td>{{ $castingRequirement->location ?? trans('global.not_set') }}</td>
                            <td>{{ $castingRequirement->shoot_date_time ?? trans('global.not_set') }}</td>
                            <td>
                                <span class="badge badge-pill badge-{{ $castingRequirement->status === 'advertised' ? 'info' : ($castingRequirement->status === 'completed' ? 'success' : 'warning') }}">
                                    {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? trans('global.not_set') }}
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.casting-requirements.applicants', $castingRequirement->id) }}">
                                    {{ trans('global.view_applicants') }}
                                </a>
                            </td>
                            <td class="text-right">
                                <div class="action-pills d-inline-flex flex-wrap gap-2">
                                    @can('casting_requirement_show')
                                        <a class="btn btn-sm btn-light text-primary" href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}">
                                            <i class="fas fa-eye mr-1"></i> {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('casting_requirement_edit')
                                        <a class="btn btn-sm btn-light text-secondary" href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}">
                                            <i class="fas fa-edit mr-1"></i> {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('casting_requirement_delete')
                                        <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" data-swal-confirm="{{ trans('global.areYouSure') }}" class="d-inline">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <button type="submit" class="btn btn-sm btn-light text-danger">
                                                <i class="fas fa-trash mr-1"></i> {{ trans('global.delete') }}
                                            </button>
                                        </form>
                                    @endcan
                                </div>
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
@can('casting_requirement_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.casting-requirements.massDestroy') }}",
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
    order: [[ 0, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-CastingRequirement:not(.ajaxTable)').DataTable({
      buttons: dtButtons,
      select: false,
      columnDefs: [],
      dom: 'Brtip'
  })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
