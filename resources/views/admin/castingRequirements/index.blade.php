@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('cruds.castingRequirement.title_singular') }} {{ trans('global.list') }}</span>
        @can('casting_requirement_create')
            <a class="btn btn-primary" href="{{ route('admin.casting-requirements.create') }}">
                {{ trans('global.create_new_project') }}
            </a>
        @endcan
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-CastingRequirement">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.castingRequirement.fields.project_name') }}</th>
                        <th>{{ trans('cruds.castingRequirement.fields.location') }}</th>
                        <th>{{ trans('cruds.castingRequirement.fields.shoot_date_time') }}</th>
                        <th>{{ trans('cruds.castingRequirement.fields.status') }}</th>
                        <th>{{ trans('global.applicants') }}</th>
                        <th>{{ trans('global.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($castingRequirements as $key => $castingRequirement)
                        <tr data-entry-id="{{ $castingRequirement->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $castingRequirement->project_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->location ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->shoot_date_time ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? '' }}
                            </td>
                            <td>
                                <a class="btn btn-xs btn-outline-primary" href="{{ route('admin.casting-requirements.applicants', $castingRequirement->id) }}">
                                    {{ trans('global.view_applicants') }}
                                </a>
                            </td>
                            <td>
                                @can('casting_requirement_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('casting_requirement_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('casting_requirement_delete')
                                    <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <button type="submit" class="btn btn-xs btn-danger">{{ trans('global.delete') }}</button>
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
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  let table = $('.datatable-CastingRequirement:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });

})

</script>
@endsection
