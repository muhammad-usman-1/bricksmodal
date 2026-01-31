@extends('layouts.admin')

@section('content')

<div class="card border-0 shadow-sm mt-4">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
        <h4 class="mb-0 text-dark font-weight-bold">Arabic Labels</h4>
    </div>

    <style>
        .custom-control-input:checked ~ .custom-control-label::before {
            background-color: #000 !important;
            border-color: #000 !important;
        }
        .custom-control-label::before, 
        .custom-control-label::after {
            transition: none !important;
        }
    </style>

    <div class="card-body bg-white">


        <form method="POST" action="{{ route('admin.onboarding-labels.update') }}">
            @csrf

            <!-- Hidden input to ensure 'settings' array is present even if checkboxes are unchecked -->
            <input type="hidden" name="settings" value="1">

            <div class="d-flex justify-content-between align-items-center mb-3 pl-4 pr-4">
                <h5 class="text-dark font-weight-bold mb-0">Onboarding Labels</h5>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="toggleOnboarding" name="settings[onboarding]" value="1" {{ $settings['onboarding'] ? 'checked' : '' }}>
                    <label class="custom-control-label" for="toggleOnboarding">Show Arabic Labels</label>
                </div>
            </div>

            <table class="table table-borderless mb-5" style="width: 100%;">
                <thead class="border-bottom">
                    <tr>
                        <th style="width: 50%;" class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 pl-4">
                            English (Reference)
                        </th>
                        <th style="width: 50%; text-align: right;" class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 pr-4">
                            Arabic (Translation)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($onboardingLabels as $key => $values)
                        <tr class="border-bottom">
                            <td class="align-middle pl-4">
                                <span class="text-dark font-weight-bold d-block">{{ $values['en'] }}</span>
                                <small class="text-muted">{{ $key }}</small>
                            </td>
                            <td class="align-middle pr-4">
                                <input class="form-control border-dark" type="text" name="onboarding[{{ $key }}]" value="{{ $values['ar'] }}" style="text-align: right; direction: rtl; background-color: #fff; border-radius: 4px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="d-flex justify-content-between align-items-center mb-3 pl-4 pr-4 border-top pt-4">
                <h5 class="text-dark font-weight-bold mb-0">Casting Requirement Labels</h5>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="toggleCasting" name="settings[casting]" value="1" {{ $settings['casting'] ? 'checked' : '' }}>
                    <label class="custom-control-label" for="toggleCasting">Show Arabic Labels</label>
                </div>
            </div>

            <table class="table table-borderless" style="width: 100%;">
                <thead class="border-bottom">
                    <tr>
                        <th style="width: 50%;" class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 pl-4">
                            English (Reference)
                        </th>
                        <th style="width: 50%; text-align: right;" class="text-uppercase text-secondary text-xs font-weight-bolder opacity-7 pr-4">
                            Arabic (Translation)
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($castingLabels as $key => $values)
                        <tr class="border-bottom">
                            <td class="align-middle pl-4">
                                <span class="text-dark font-weight-bold d-block">{{ $values['en'] }}</span>
                                <small class="text-muted">{{ $key }}</small>
                            </td>
                            <td class="align-middle pr-4">
                                <input class="form-control border-dark" type="text" name="casting[{{ $key }}]" value="{{ $values['ar'] }}" style="text-align: right; direction: rtl; background-color: #fff; border-radius: 4px;">
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="form-group mt-4 text-right pr-4">
                <button class="btn btn-dark px-5 py-2" type="submit" style="background-color: #000; border-color: #000;">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

@endsection

@section('scripts')
@parent
<script>
    $(function () {
        // Disable DataTables default behavior if it interferes with the form submission or paging
        // For now, we keep it simple without complex datatables to ensure all inputs submit on one page
        // But if we want search/filter, we might need to handle form submission carefully if paginated.
        // Given the number of labels is small (~50), we can probably show all on one page or disable pagination.
        
        $('.datatable-Label').DataTable({
            "paging": false,
            "info": false
        });

        // Toggle Alert Listener
        $('#toggleOnboarding, #toggleCasting').on('change', function() {
            let isChecked = $(this).is(':checked');
            let label = $(this).attr('id') === 'toggleOnboarding' ? 'Onboarding Labels' : 'Casting Requirement Labels';
            let state = isChecked ? 'Enabled' : 'Disabled';
            
            Swal.fire({
                icon: 'info',
                title: label + ' ' + state,
                text: 'Don\'t forget to save your changes!',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        });
    });
</script>
@endsection
