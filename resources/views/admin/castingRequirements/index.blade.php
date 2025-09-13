@extends('layouts.admin')
@section('content')
@can('casting_requirement_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.casting-requirements.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.castingRequirement.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.castingRequirement.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-CastingRequirement">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.project_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.client_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.location') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.shoot_date_time') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.hair_color') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.age_range') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.gender') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.outfit') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.reference') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.count') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.notes') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.user') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.rate_per_model') }}
                        </th>
                        <th>
                            {{ trans('cruds.castingRequirement.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($castingRequirements as $key => $castingRequirement)
                        <tr data-entry-id="{{ $castingRequirement->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $castingRequirement->id ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->project_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->client_name ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->location ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->shoot_date_time ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->hair_color ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->age_range ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CastingRequirement::GENDER_SELECT[$castingRequirement->gender] ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->outfit ?? '' }}
                            </td>
                            <td>
                                @foreach($castingRequirement->reference as $key => $media)
                                    <a href="{{ $media->getUrl() }}" target="_blank">
                                        {{ trans('global.view_file') }}
                                    </a>
                                @endforeach
                            </td>
                            <td>
                                {{ $castingRequirement->count ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->notes ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->user->name ?? '' }}
                            </td>
                            <td>
                                {{ $castingRequirement->rate_per_model ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\CastingRequirement::STATUS_SELECT[$castingRequirement->status] ?? '' }}
                            </td>
                            <td>
                                @can('casting_requirement_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.casting-requirements.show', $castingRequirement->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('casting_requirement_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.casting-requirements.edit', $castingRequirement->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('casting_requirement_delete')
                                    <form action="{{ route('admin.casting-requirements.destroy', $castingRequirement->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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