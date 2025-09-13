@extends('layouts.admin')
@section('content')
@can('bank_detail_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route('admin.bank-details.create') }}">
                {{ trans('global.add') }} {{ trans('cruds.bankDetail.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.bankDetail.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-BankDetail">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.talent_profile') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.bank_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.account_holder_name') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.account_number') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.iban') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.swift_code') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.branch') }}
                        </th>
                        <th>
                            {{ trans('cruds.bankDetail.fields.status') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bankDetails as $key => $bankDetail)
                        <tr data-entry-id="{{ $bankDetail->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $bankDetail->id ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->talent_profile->legal_name ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->bank_name ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->account_holder_name ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->account_number ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->iban ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->swift_code ?? '' }}
                            </td>
                            <td>
                                {{ $bankDetail->branch ?? '' }}
                            </td>
                            <td>
                                {{ App\Models\BankDetail::STATUS_SELECT[$bankDetail->status] ?? '' }}
                            </td>
                            <td>
                                @can('bank_detail_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.bank-details.show', $bankDetail->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('bank_detail_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.bank-details.edit', $bankDetail->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('bank_detail_delete')
                                    <form action="{{ route('admin.bank-details.destroy', $bankDetail->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
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
@can('bank_detail_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.bank-details.massDestroy') }}",
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
  let table = $('.datatable-BankDetail:not(.ajaxTable)').DataTable({ buttons: dtButtons })
  $('a[data-toggle="tab"]').on('shown.bs.tab click', function(e){
      $($.fn.dataTable.tables(true)).DataTable()
          .columns.adjust();
  });
  
})

</script>
@endsection