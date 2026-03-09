@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Leave') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Leave') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Leave') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                        class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Reason') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($leaves as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ formattedDate($item?->date) }}</td>
                                                    <td>{{ $item?->reason }}</td>
                                                    <td>
                                                        @if ($item?->status)
                                                            <div class="badge badge-success">{{ __('Approved') }}</div>
                                                        @else
                                                            <div class="badge badge-info">{{ __('Pending') }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if (!$item->status)
                                                            <a href="javascript:;"
                                                                onclick="editItemData({{ $item->id }}, '{{ $item?->date }}', '{{ $item?->reason }}')"
                                                                class="btn btn-warning btn-sm"><i
                                                                    class="fa fa-edit"></i></a>
                                                            <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Item')" route="lawyer.leave.index" create="no"
                                                    :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $leaves->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
        <div class="modal-dialog" role="document">
            <form class="modal-content" id="ItemForm" action="{{ route('lawyer.leave.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create Leave') }}</h5>
                    <button type="button" class="btn-close itemModalClose" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <x-admin.form-input class="datepicker" id="itemTitle" name="date" label="{{ __('Date') }}"
                            placeholder="{{ __('Enter Date') }}" autocomplete="off" required="true" />
                    </div>
                    <div class="form-group">
                        <x-admin.form-textarea id="itemReason" name="reason" label="{{ __('Reason') }}"
                            required="true" />
                    </div>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <x-admin.button variant="danger" data-bs-dismiss="modal" class="itemModalClose"
                        text="{{ __('Close') }}" />
                    <x-admin.button class="submit-btn" type="submit" text="{{ __('Save') }}" />
                </div>
            </form>
        </div>
    </div>
    <x-admin.delete-modal />
@endsection
@push('js')
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/lawyer/leave/') }}' + "/" + id);
        }
        "use strict"
        $('.itemModalClose').on('click', function() {
            $("#ItemForm").attr("action", "{{ url('/lawyer/leave') }}");
            $("#itemModal .modal-title").text("{{ __('Create Leave') }}");
            $(".submit-btn").text("{{ __('Save') }}");
            $("#itemTitle").val('');
            $("#itemReason").val('');
            $(".form-group span.text-danger").hide();
            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, date, reason) {
            $("#ItemForm").attr("action", "{{ url('/lawyer/leave') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Leave') }}");
            $(".submit-btn").text("{{ __('Update') }}");
            $("#itemTitle").val(date);
            $("#itemReason").val(reason);
            $(".form-group span.text-danger").hide();
            $('#itemModal').modal('show');
        }
    </script>
@endpush
