<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ trans('panel.site_title') }}</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.2.0/css/all.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://unpkg.com/@coreui/coreui@3.2/dist/css/coreui.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/css/perfect-scrollbar.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <style>
        .admin-header {
            background-color: #ffffff;
            border-bottom: 1px solid #edf0f2;
            box-shadow: none;
        }

        .admin-topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
            width: 100%;
        }

        .admin-search {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f7f9fb;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            padding: 8px 12px;
            width: 100%;
            max-width: 520px;
        }

        .admin-search i {
            color: #9ca3af;
            font-size: 14px;
        }

        .admin-search input {
            border: none;
            outline: none;
            background: transparent;
            width: 100%;
            font-size: 13px;
            color: #6b7280;
        }

        .admin-header .admin-icons {
            display: flex;
            align-items: center;
            gap: 10px !important;
        }

        .icon-btn {
            border: none !important;
            background: transparent !important;
            color: #6b7280;
            font-size: 16px;
            padding: 0;
            position: relative;
            box-shadow: none;
            border-radius: 0;
        }

        .icon-btn:focus {
            outline: none;
        }

        .icon-badge {
            position: absolute;
            top: -3px;
            right: -4px;
            width: 8px;
            height: 8px;
            background: #e74c3c;
            border-radius: 50%;
        }

        @media (max-width: 767px) {
            .admin-topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 10px;
            }

            .admin-icons {
                justify-content: flex-end;
            }
        }
    </style>
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.menu')
    <div class="c-wrapper">
        <header class="c-header c-header-fixed px-3 admin-header">
            <div class="admin-topbar">
                <div class="admin-search">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search talents, shoots, or campaigns..." aria-label="Search" />
                </div>
                <div class="admin-icons">
                    <button class="icon-btn" type="button" aria-label="Add">
                        <i class="fas fa-plus"></i>
                    </button>
                    <a class="icon-btn" href="{{ route('admin.settings.index') }}" aria-label="Settings">
                        <i class="fas fa-cog"></i>
                    </a>
                    <div class="dropdown">
                        <a class="icon-btn dropdown-toggle p-0" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false" aria-label="Notifications">
                            <i class="fas fa-bell"></i>
                            <span class="icon-badge {{ auth()->user()->unreadNotifications->count() > 0 ? '' : 'd-none' }}"></span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right pt-0" style="max-height: 400px; overflow-y: auto;">
                            <div class="dropdown-header bg-light py-2">
                                <strong>Notifications</strong>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <a href="{{ route('admin.notifications.mark-all-read') }}" class="float-right text-muted" style="font-size: 0.8em;">
                                        Mark all as read
                                    </a>
                                @endif
                            </div>
                            @forelse(auth()->user()->notifications()->latest()->limit(10)->get() as $notification)
                                <a class="dropdown-item {{ $notification->read_at ? 'text-muted' : 'font-weight-bold' }}"
                                   href="{{ route('admin.notifications.show', $notification->id) }}">
                                    @if($notification->data['type'] === 'talent_profile')
                                        <i class="fas fa-user text-info"></i>
                                    @else
                                        <i class="fas fa-video text-warning"></i>
                                    @endif
                                    {{ $notification->data['title'] }}
                                    <div class="small text-muted">{{ $notification->data['message'] }}</div>
                                    <div class="small text-muted">{{ $notification->created_at->diffForHumans() }}</div>
                                </a>
                            @empty
                                <div class="dropdown-item text-center text-muted">
                                    No notifications
                                </div>
                            @endforelse
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item text-center" href="{{ route('admin.notifications.index') }}">
                                View all notifications
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="c-body">
            <main class="c-main"  style="background: #F9FAFB;">


                <div class="container-fluid">
                    @php
                        $impersonatingId = session('impersonate.original_admin_id');
                        $originalAdmin = $impersonatingId ? \App\Models\User::find($impersonatingId) : null;
                    @endphp
                    @if($impersonatingId && $originalAdmin)
                        <div class="alert alert-warning d-flex justify-content-between align-items-center" role="alert">
                            <div>
                                <strong>{{ trans('global.impersonating_notice', ['name' => auth('admin')->user()->name]) }}</strong>
                                <span class="d-block small">{{ trans('global.impersonating_original', ['name' => $originalAdmin->name]) }}</span>
                            </div>
                            <a href="{{ route('admin.impersonate.stop') }}" class="btn btn-sm btn-dark">
                                {{ trans('global.stop_impersonating') }}
                            </a>
                        </div>
                    @endif
                    @if(session('message'))
                        <div class="row mb-2">
                            <div class="col-lg-12">
                                <div class="alert alert-success" role="alert">{{ session('message') }}</div>
                            </div>
                        </div>
                    @endif
                    @if($errors->count() > 0)
                        <div class="alert alert-danger">
                            <ul class="list-unstyled">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    @yield('content')

                </div>


            </main>
            <form id="logoutform" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.5.0/perfect-scrollbar.min.js"></script>
    <script src="https://unpkg.com/@coreui/coreui@3.2/dist/js/coreui.min.js"></script>
    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
    <script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
    <script src="https://cdn.ckeditor.com/ckeditor5/16.0.0/classic/ckeditor.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('js/main.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const attachSwal = function () {
                document.querySelectorAll('form[data-swal-confirm]').forEach(function (form) {
                    if (form.dataset.swalBound === 'true') {
                        return;
                    }
                    form.dataset.swalBound = 'true';
                    form.addEventListener('submit', function (e) {
                        if (form.dataset.swalConfirmed === 'true') {
                            return;
                        }
                        e.preventDefault();
                        const message = form.dataset.swalConfirm || '{{ trans('global.areYouSure') }}';
                        Swal.fire({
                            text: message,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: '{{ trans('global.yes') }}'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.dataset.swalConfirmed = 'true';
                                form.submit();
                            }
                        });
                    });
                });
            };

            attachSwal();

            document.addEventListener('turbolinks:load', attachSwal);
            document.addEventListener('ajaxComplete', attachSwal);
        });

        $(function() {
  let copyButtonTrans = '{{ trans('global.datatables.copy') }}'
  let csvButtonTrans = '{{ trans('global.datatables.csv') }}'
  let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
  let pdfButtonTrans = '{{ trans('global.datatables.pdf') }}'
  let printButtonTrans = '{{ trans('global.datatables.print') }}'
  let colvisButtonTrans = '{{ trans('global.datatables.colvis') }}'
  let selectAllButtonTrans = '{{ trans('global.select_all') }}'
  let selectNoneButtonTrans = '{{ trans('global.deselect_all') }}'

  let languages = {
    'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json'
  };

  $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' })
  $.extend(true, $.fn.dataTable.defaults, {
    language: {
      url: languages['{{ app()->getLocale() }}']
    },
    columnDefs: [{
        orderable: false,
        className: 'select-checkbox',
        targets: 0
    }, {
        orderable: false,
        searchable: false,
        targets: -1
    }],
    select: {
      style:    'multi+shift',
      selector: 'td:first-child'
    },
    order: [],
    scrollX: true,
    pageLength: 100,
    dom: 'lBfrtip<"actions">',
    buttons: [
      {
        extend: 'selectAll',
        className: 'btn-primary',
        text: selectAllButtonTrans,
        exportOptions: {
          columns: ':visible'
        },
        action: function(e, dt) {
          e.preventDefault()
          dt.rows().deselect();
          dt.rows({ search: 'applied' }).select();
        }
      },
      {
        extend: 'selectNone',
        className: 'btn-primary',
        text: selectNoneButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'copy',
        className: 'btn-default',
        text: copyButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'csv',
        className: 'btn-default',
        text: csvButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'excel',
        className: 'btn-default',
        text: excelButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'pdf',
        className: 'btn-default',
        text: pdfButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'print',
        className: 'btn-default',
        text: printButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      },
      {
        extend: 'colvis',
        className: 'btn-default',
        text: colvisButtonTrans,
        exportOptions: {
          columns: ':visible'
        }
      }
    ]
  });

  $.fn.dataTable.ext.classes.sPageButton = '';
});

    </script>
    @yield('scripts')
</body>

</html>
