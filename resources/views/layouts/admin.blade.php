<!DOCTYPE html>
@php
    $adminSettings = \App\Models\AdminSetting::singleton();
    $appearance = $adminSettings->appearance ?? 'light';
@endphp
<html data-theme-preference="{{ $appearance }}">

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

        /* ===== Dark Theme Styles ===== */
        html[data-theme="dark"],
        html[data-theme="dark"] body {
            color-scheme: dark;
        }


        html[data-theme="dark"] .c-main {
            background: #0f1114 !important;
        }

        html[data-theme="dark"] .c-body {
            background: #0f1114;
        }

        html[data-theme="dark"] .container-fluid {
            background: #0f1114;
        }

        html[data-theme="dark"] .card,
        html[data-theme="dark"] .card-block {
            background: #1a1d23;
            border-color: #2d3138;
            color: #e5e7eb;
        }

        html[data-theme="dark"] .card-title,
        html[data-theme="dark"] .settings-title {
            color: #f3f4f6;
        }

        html[data-theme="dark"] .card-sub,
        html[data-theme="dark"] .settings-sub,
        html[data-theme="dark"] .toggle-hint {
            color: #9ca3af;
        }

        html[data-theme="dark"] .toggle-label {
            color: #e5e7eb;
        }

        html[data-theme="dark"] .alert {
            background-color: #1a1d23;
            border-color: #2d3138;
            color: #e5e7eb;
        }

        html[data-theme="dark"] .alert-success {
            background-color: #064e3b;
            border-color: #065f46;
            color: #d1fae5;
        }

        html[data-theme="dark"] .alert-danger {
            background-color: #7f1d1d;
            border-color: #991b1b;
            color: #fecaca;
        }

        html[data-theme="dark"] .alert-warning {
            background-color: #78350f;
            border-color: #92400e;
            color: #fde68a;
        }

        html[data-theme="dark"] .dropdown-menu {
            background-color: #1a1d23;
            border-color: #2d3138;
        }

        html[data-theme="dark"] .dropdown-item {
            color: #e5e7eb;
        }

        html[data-theme="dark"] .dropdown-item:hover {
            background-color: #252932;
            color: #f3f4f6;
        }

        html[data-theme="dark"] .dropdown-header {
            background-color: #252932 !important;
            color: #9ca3af;
            border-bottom-color: #2d3138;
        }

        html[data-theme="dark"] .table {
            color: #e5e7eb;
        }

        html[data-theme="dark"] .table thead th {
            border-bottom-color: #2d3138;
            color: #f3f4f6;
        }

        html[data-theme="dark"] .table tbody td {
            border-top-color: #2d3138;
        }

        html[data-theme="dark"] .form-control,
        html[data-theme="dark"] .select-lite select {
            background-color: #252932;
            border-color: #2d3138;
            color: #e5e7eb;
        }

        html[data-theme="dark"] .form-control:focus,
        html[data-theme="dark"] .select-lite select:focus {
            background-color: #252932;
            border-color: #3b82f6;
            color: #e5e7eb;
        }

        html[data-theme="dark"] .btn-primary {
            background-color: #3b82f6;
            border-color: #3b82f6;
        }

        html[data-theme="dark"] .btn-primary:hover {
            background-color: #2563eb;
            border-color: #2563eb;
        }

        html[data-theme="dark"] .btn-dark {
            background-color: #374151;
            border-color: #374151;
        }

        html[data-theme="dark"] .text-muted {
            color: #9ca3af !important;
        }

        html[data-theme="dark"] .bg-light {
            background-color: #252932 !important;
        }
    </style>
    @stack('styles')
    @yield('styles')
</head>

<body class="c-app">
    @include('partials.menu')
    <div class="c-wrapper">
        @include('partials.header')

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
                    {{-- SweetAlert handles these notifications now --}}
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
        // Theme Management - Apply theme based on preference
        (function() {
            const html = document.documentElement;
            const themePreference = html.getAttribute('data-theme-preference') || 'light';

            function applyTheme(theme) {
                html.setAttribute('data-theme', theme);
            }

            function getSystemTheme() {
                if (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
                    return 'dark';
                }
                return 'light';
            }

            // Apply theme based on preference
            if (themePreference === 'system') {
                // Apply system theme
                applyTheme(getSystemTheme());

                // Listen for system theme changes
                if (window.matchMedia) {
                    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
                    mediaQuery.addEventListener('change', function(e) {
                        applyTheme(e.matches ? 'dark' : 'light');
                    });
                }
            } else {
                // Apply explicit theme (light or dark)
                applyTheme(themePreference);
            }
        })();

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
                            confirmButtonColor: '#000000',
                            cancelButtonColor: '#6c757d',
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Success Message
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: "{{ session('success') }}",
                    confirmButtonColor: '#000000',
                });
            @endif

            // Error Message
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: "{{ session('error') }}",
                    confirmButtonColor: '#000000',
                });
            @endif

            // Message (Generic)
            @if(session('message'))
                Swal.fire({
                    icon: 'info',
                    title: 'Notice',
                    text: "{{ session('message') }}",
                    confirmButtonColor: '#000000',
                });
            @endif

            // Validation Errors
            @if($errors->any())
                @php
                    $errorList = '<ul style="text-align: left;">';
                    foreach($errors->all() as $error) {
                        $errorList .= '<li>' . addslashes($error) . '</li>';
                    }
                    $errorList .= '</ul>';
                @endphp
                Swal.fire({
                    icon: 'error',
                    title: 'Validation Issue',
                    html: '{!! $errorList !!}',
                    confirmButtonColor: '#000000',
                });
            @endif
        });
    </script>
    @yield('scripts')
</body>

</html>
