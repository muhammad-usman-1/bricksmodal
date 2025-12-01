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
                            <td>
                                {{ $castingRequirement->shoot_date_display ?? trans('global.not_set') }}
                                @if($castingRequirement->duration)
                                    <div class="text-muted small">Duration: {{ $castingRequirement->duration }}</div>
                                @endif
                            </td>
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
                                    <button class="btn btn-sm btn-light text-info share-project-btn"
                                        type="button"
                                        data-toggle="modal"
                                        data-target="#shareProjectModal"
                                        data-name="{{ $castingRequirement->project_name }}"
                                        data-location="{{ $castingRequirement->location ?? trans('global.not_set') }}"
                                        data-date="{{ $castingRequirement->shoot_date_display ?? trans('global.not_set') }}"
                                        data-url="{{ route('talent.projects.show', $castingRequirement) }}">
                                        <i class="fas fa-share-alt mr-1"></i> {{ trans('global.share') }}
                                    </button>
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

<div class="modal fade" id="shareProjectModal" tabindex="-1" role="dialog" aria-labelledby="shareProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title" id="shareProjectModalLabel">{{ trans('global.share') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ trans('global.close') }}">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label class="font-weight-bold mb-1">{{ trans('global.link') }}</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="shareProjectLink" readonly>
                        <div class="input-group-append">
                            <button class="btn btn-outline-secondary" type="button" id="copyShareLink">
                                {{ trans('global.copy_link') }}
                            </button>
                        </div>
                    </div>
                </div>
                <div class="form-group mb-0">
                    <label class="font-weight-bold mb-1">{{ trans('global.message') }}</label>
                    <textarea id="shareProjectMessage" rows="4" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">{{ trans('global.cancel') }}</button>
                <button type="button" class="btn btn-success" id="shareProjectWhatsApp">
                    <i class="fab fa-whatsapp mr-1"></i> {{ trans('notifications.send_whatsapp') }}
                </button>
            </div>
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

  const shareLinkInput = $('#shareProjectLink')
  const shareMessageInput = $('#shareProjectMessage')
  const shareWhatsappBtn = $('#shareProjectWhatsApp')

  $('.share-project-btn').on('click', function () {
      const button = $(this)
      const projectName = button.data('name') || '{{ trans('global.this_project') }}'
      const location = button.data('location') || '{{ trans('global.not_set') }}'
      const date = button.data('date') || '{{ trans('global.not_set') }}'
      const url = button.data('url') || ''

      shareLinkInput.val(url)
      const template = `Hi! Check out "${projectName}" happening on ${date} at ${location}. Apply here: ${url}`
      shareMessageInput.val(template)
  })

  $('#copyShareLink').on('click', function () {
      const text = shareLinkInput.val()
      if (!text) {
          return
      }

      const notifyCopied = () => {
          if (typeof Swal !== 'undefined') {
              Swal.fire({
                  icon: 'success',
                  title: '{{ trans('global.copied') }}',
                  text: '{{ trans('global.link_copied') }}',
                  timer: 1500,
                  showConfirmButton: false
              })
          }
      }

      if (navigator.clipboard && navigator.clipboard.writeText) {
          navigator.clipboard.writeText(text).then(notifyCopied).catch(() => {
              shareLinkInput.trigger('focus').select()
              document.execCommand('copy')
              notifyCopied()
          })
      } else {
          shareLinkInput.trigger('focus').select()
          document.execCommand('copy')
          notifyCopied()
      }
  })

  shareWhatsappBtn.on('click', function () {
      const message = shareMessageInput.val() || ''
      if (!message.trim()) {
          if (typeof Swal !== 'undefined') {
              Swal.fire({
                  icon: 'error',
                  title: '{{ trans('global.error') }}',
                  text: '{{ trans('notifications.whatsapp_message_required') }}'
              })
          }
          return
      }

      const link = 'https://wa.me/?text=' + encodeURIComponent(message)
      window.open(link, '_blank')
  })
})

</script>

<script>
(function () {
    var shootLocationSelector = '#location';
    var kuwaitRestriction = { country: ['kw'] };

    function isPlacesReady() {
        return window.google && google.maps && google.maps.places;
    }

    window.initShootLocationAutocomplete = function () {
        if (!isPlacesReady()) {
            console.warn('Google Places library is not available yet.');
            return;
        }

        var input = document.querySelector(shootLocationSelector);
        if (!input || input.dataset.autocompleteBound === 'true') {
            return;
        }

        input.dataset.autocompleteBound = 'true';

        var autocomplete = new google.maps.places.Autocomplete(input, {
            componentRestrictions: kuwaitRestriction,
            fields: ['formatted_address', 'name', 'geometry'],
            types: ['geocode']
        });

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (place && place.formatted_address) {
                input.value = place.formatted_address;
            } else if (place && place.name) {
                input.value = place.name;
            }
        });
    };

    document.addEventListener('DOMContentLoaded', function () {
        if (isPlacesReady()) {
            window.initShootLocationAutocomplete();
        }
    });

    document.addEventListener('shown.bs.modal', function () {
        if (isPlacesReady()) {
            window.initShootLocationAutocomplete();
        }
    });

    document.addEventListener('shootWizardLoaded', function () {
        if (isPlacesReady()) {
            window.initShootLocationAutocomplete();
        }
    });
})();
</script>
@php
    $googlePlacesKey = config('services.google.places_api_key');
@endphp
@if ($googlePlacesKey)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ $googlePlacesKey }}&libraries=places&callback=initShootLocationAutocomplete" async defer></script>
@else
    <script>
        console.warn('Google Places API key is not configured. Location autocomplete will be disabled.');
    </script>
@endif
@endsection
