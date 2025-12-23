@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Outfit Management</h5>
        <a class="btn btn-success" href="{{ route('admin.outfits.create') }}">
            Add New Outfit
        </a>
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Outfit">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Sub Category</th>
                        <th>Image</th>
                        <th>Status</th>
                        <th>Sort Order</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($outfits as $outfit)
                        <tr data-entry-id="{{ $outfit->id }}">
                            <td>{{ $outfit->id }}</td>
                            <td>{{ $outfit->name }}</td>
                            <td>{{ ucfirst($outfit->category) }}</td>
                            <td>{{ $outfit->sub_category }}</td>
                            <td>
                                @if($outfit->image)
                                    <img src="{{ $outfit->image }}" alt="{{ $outfit->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-{{ $outfit->is_active ? 'success' : 'danger' }}">
                                    {{ $outfit->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td>{{ $outfit->sort_order }}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.outfits.edit', $outfit->id) }}">
                                    Edit
                                </a>
                                <form action="{{ route('admin.outfits.destroy', $outfit->id) }}" method="POST" onsubmit="return confirm('Are you sure?');" style="display: inline-block;">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                    <input type="submit" class="btn btn-xs btn-danger" value="Delete">
                                </form>
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
  
  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 2, 'asc' ], [ 6, 'asc' ]],
    pageLength: 100,
  });
  $('.datatable-Outfit:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})
</script>
@endsection
