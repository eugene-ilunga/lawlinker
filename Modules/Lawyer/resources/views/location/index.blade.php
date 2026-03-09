@extends('admin.master_layout')
@section('title')
    <title>{{ __('Location') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Location') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Location') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.location.index', ['code' => $language->code]) }}"><i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}</a></li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-2 alert alert-danger" role="alert">
                                @php
                                    $current_language = $languages->where('code', request()->get('code'))->first();
                                @endphp
                                <p>{{ __('Your editing mode') }}:<b> {{ $current_language?->name }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                @adminCan('location.store')
                                    <div>
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                            class="btn btn-primary"><i class="fa fa-plus"></i> {{ __('Add New') }}</a>
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="10%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Name') }}</th>
                                                @adminCan('location.update')
                                                    <th width="20%">{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('location.update') || checkAdminHasPermission('location.delete'))
                                                    <th width="20%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($locations as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item?->getTranslation($code)?->name }}</td>
                                                    @adminCan('location.update')
                                                        <td>
                                                            <input onchange="changeStatus({{ $item->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('location.update') || checkAdminHasPermission('location.delete'))
                                                        <td>
                                                            @adminCan('location.update')
                                                                <a href="javascript:;"
                                                                    onclick="editItemData({{ $item->id }}, '{{ $item?->getTranslation($code)?->name }}')"
                                                                    class="btn btn-warning btn-sm"><i class="fa fa-edit"
                                                                        aria-hidden="true"></i></a>
                                                            @endadminCan
                                                            @if (checkAdminHasPermission('location.delete') && $item?->lawyers->count() == 0)
                                                                <x-admin.delete-button :id="$item->id"
                                                                    onclick="deleteData" />
                                                            @endif
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Item')" route="admin.location.index" create="no"
                                                    :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $locations->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('location.store') || checkAdminHasPermission('location.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.location.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Add New Item') }}</h5>
                        <button type="button" class="btn-close itemModalClose" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-admin.form-input type="hidden" name="code" value="{{ $code }}" />
                        <x-admin.form-input id="itemTitle" name="name" label="{{ __('Name') }}"
                            placeholder="{{ __('Enter Name') }}" required="true" />
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" class="itemModalClose"
                            text="{{ __('Close') }}" />
                        <x-admin.button class="submit-btn" type="submit" text="{{ __('Save') }}" />
                    </div>
                </form>
            </div>
        </div>
    @endif
    @adminCan('location.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection
@push('js')
    <script>
        @adminCan('location.delete')
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/location/') }}' + "/" + id);
        }
        @endadminCan

        "use strict"
        $('.itemModalClose').on('click', function() {
            $("#ItemForm").attr("action", "{{ url('/admin/location') }}");
            $("#itemModal .modal-title").text("{{ __('Add New Item') }}");
            $(".submit-btn").text("{{ __('Save') }}");
            $("#itemTitle").val('');
            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, name) {
            console.log('Hello')
            $("#ItemForm").attr("action", "{{ url('/admin/location') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Item') }}");
            $(".submit-btn").text("{{ __('Update') }}");
            $("#itemTitle").val(name);
            $('#itemModal').modal('show');
        }

        @adminCan('location.update')
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
                url: "{{ url('/admin/location/status-update') }}" + "/" + id,
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
        @endadminCan
    </script>
@endpush
