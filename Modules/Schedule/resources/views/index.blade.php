@extends('admin.master_layout')
@section('title')
    <title>{{ __('Schedules') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Schedules') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Schedules') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <form action="{{ route('admin.schedule.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="day" id="day" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Day') }}" />
                                                @foreach ($days as $day)
                                                    <x-admin.select-option :selected="$day->id == request('day')" value="{{ $day?->id }}"
                                                        text="{{ $day?->title }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="lawyer" id="lawyer" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Lawyer') }}" />
                                                @foreach ($lawyers as $lawyer)
                                                    <x-admin.select-option :selected="$lawyer->id == request('lawyer')" value="{{ $lawyer?->id }}"
                                                        text="{{ $lawyer?->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="status" id="status" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option :selected="request('status') == '1'" value="1"
                                                    text="{{ __('Active') }}" />
                                                <x-admin.select-option :selected="request('status') == '0'" value="0"
                                                    text="{{ __('In-Active') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="par-page" id="par-page" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Per Page') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '5'" value="5"
                                                    text="{{ __('5') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '10'" value="10"
                                                    text="{{ __('10') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '25'" value="25"
                                                    text="{{ __('25') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '50'" value="50"
                                                    text="{{ __('50') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '100'" value="100"
                                                    text="{{ __('100') }}" />
                                                <x-admin.select-option :selected="request('par-page') == 'all'" value="all"
                                                    text="{{ __('All') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <h4>{{ __('Schedules') }}</h4>
                                @if (checkAdminHasPermission('schedule.store'))
                                    <div>
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                            class="btn btn-primary itemModalClose"><i class="fa fa-plus"></i>
                                            {{ __('Add New') }}</a>
                                    </div>
                                @endif
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="15%">{{ __('Lawyer Name') }}</th>
                                                <th width="10%">{{ __('Day') }}</th>
                                                <th width="15%">{{ __('Time') }}</th>
                                                <th width="15%">{{ __('Seat Quantity') }}</th>
                                                <th width="15%">{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($schedules as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item?->lawyer?->name }}
                                                    <td>{{ $item?->day?->title }}
                                                    <td>{{ $item?->start_time . ' - ' . $item?->end_time }}</td>
                                                    <td>{{ $item?->quantity }}</td>
                                                    <td>
                                                        <input onchange="changeStatus({{ $item->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        @if (checkAdminHasPermission('schedule.update'))
                                                            <x-admin.edit-button
                                                                onclick="editItemData({{ $item->id }}, '{{ $item?->day_id }}', '{{ $item?->lawyer_id }}', '{{ \Carbon\Carbon::parse($item?->start_time)->format('H:i') }}', '{{ \Carbon\Carbon::parse($item?->end_time)->format('H:i') }}', '{{ $item?->quantity }}')" />
                                                        @endif
                                                        @if (checkAdminHasPermission('schedule.delete'))
                                                            <x-admin.delete-button :id="$item->id"
                                                                onclick="deleteData" />
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Item')" route="admin.schedule.index"
                                                    create="no" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $schedules->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('schedule.delete'))
        <x-admin.delete-modal />
    @endif

    @if (checkAdminHasPermission('schedule.store') || checkAdminHasPermission('schedule.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.schedule.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Schedule') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <x-admin.form-select name="day_id" id="day_id" class="form-select"
                                label="{{ __('Day') }}" required="true">
                                <x-admin.select-option value="" text="{{ __('Select Day') }}" />
                                @foreach ($days as $day)
                                    <x-admin.select-option value="{{ $day->id }}" text="{{ $day->title }}" />
                                @endforeach
                            </x-admin.form-select>
                        </div>
                        <div class="form-group">
                            <x-admin.form-select name="lawyer_id" id="lawyer_id" class="itemSelect2"
                                label="{{ __('Lawyer') }}" required="true">
                                <x-admin.select-option value="" text="{{ __('Select Lawyer') }}" />
                                @foreach ($lawyers as $lawyer)
                                    <x-admin.select-option value="{{ $lawyer->id }}" text="{{ $lawyer->name }}" />
                                @endforeach
                            </x-admin.form-select>
                        </div>
                        <div class="form-group">
                            <x-admin.form-input type="time" id="start_time" name="start_time"
                                label="{{ __('Start time') }}" placeholder="{{ __('Start time') }}" required="true" />
                        </div>
                        <div class="form-group">
                            <x-admin.form-input type="time" id="end_time" name="end_time"
                                label="{{ __('End time') }}" placeholder="{{ __('End time') }}" required="true" />
                        </div>
                        <div class="form-group">
                            <x-admin.form-input type="number" id="quantity" name="quantity"
                                label="{{ __('Quantity') }}" placeholder="{{ __('Enter Quantity') }}"
                                required="true" />
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <x-admin.button variant="danger" class="itemModalClose" data-bs-dismiss="modal"
                            text="{{ __('Close') }}" />
                        <x-admin.button id="submitBtn" type="submit" text="{{ __('Save') }}" />
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/schedule/') }}' + "/" + id);
        }
        "use strict"
        $(document).ready(function() {
            $('.itemSelect2').select2({
                dropdownParent: $('#itemModal')
            });
        });

        $('.itemModalClose').on('click', function() {
            $("#ItemForm").attr("action", "{{ url('/admin/schedule') }}");
            $("#itemModal .modal-title").text("{{ __('Create Schedule') }}");
            $("#submitBtn").text("{{ __('Save') }}");
            $("#day_id").val("").change();
            $("#lawyer_id").val("").change();
            $("#start_time").val('');
            $("#end_time").val('');
            $("#quantity").val('');
            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, day_id, lawyer_id, start_time, end_time, quantity) {
            $("#ItemForm").attr("action", "{{ url('/admin/schedule') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Schedule') }}");
            $("#submitBtn").text("{{ __('Update') }}");
            $("#day_id").val(day_id).change();
            $("#lawyer_id").val(lawyer_id).change();
            $("#start_time").val(start_time);
            $("#end_time").val(end_time);
            $("#quantity").val(quantity);
            $('#itemModal').modal('show');
        }
        "use strict"

        function changeStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/schedule/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
@endpush
