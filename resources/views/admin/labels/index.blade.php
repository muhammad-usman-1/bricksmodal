@extends('layouts.admin')

@section('content')
@can('label_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.labels.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.label.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>{{ trans('cruds.label.title_singular') }} {{ trans('global.list') }}</span>
        <span class="text-muted small">{{ trans_choice('{0}No labels yet|{1}1 label|[2,*]:count labels', $labels->count(), ['count' => $labels->count()]) }}</span>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Label">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.label.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.label.fields.name') }}
                        </th>
                        <th>
                            {{ trans('global.created_at') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($labels as $label)
                        <tr data-entry-id="{{ $label->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $label->id }}
                            </td>
                            <td>
                                {{ $label->name }}
                            </td>
                            <td>
                                {{ $label->created_at?->format('d M Y H:i') }}
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    @can('label_access')
                                        <a class="btn btn-primary" href="{{ route('admin.labels.show', $label) }}">
                                            {{ trans('global.view') }}
                                        </a>
                                    @endcan
                                    @can('label_edit')
                                        <a class="btn btn-info" href="{{ route('admin.labels.edit', $label) }}">
                                            {{ trans('global.edit') }}
                                        </a>
                                    @endcan
                                    @can('label_delete')
                                        <form action="{{ route('admin.labels.destroy', $label) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display:inline-block;">
                                            @method('DELETE')
                                            @csrf
                                            <input type="submit" class="btn btn-danger" value="{{ trans('global.delete') }}">
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
@can('label_delete')
        let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
        let deleteButton = {
            text: deleteButtonTrans,
            url: "{{ route('admin.labels.massDestroy') }}",
            className: 'btn-danger',
            action: function (e, dt, node, config) {
                let ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                    return $(entry).data('entry-id')
                })

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
        let table = $('.datatable-Label:not(.ajaxTable)').DataTable({ buttons: dtButtons })
        $('a[data-toggle="tab"]').on('shown.bs.tab click', function(){
            $($.fn.dataTable.tables(true)).DataTable()
                .columns.adjust();
        });
    })
</script>
@endsection

