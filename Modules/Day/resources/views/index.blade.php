@extends('admin.master_layout')
@section('title')
    <title>{{ __('Days') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Days') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Days') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.day.index', ['code' => $language->code]) }}"><i
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
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="20%">{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($days as $item)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $item?->getTranslation($code)?->title }}</td>
                                                    <td>
                                                        <input onchange="changeStatus({{ $item->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $item->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        @if (checkAdminHasPermission('day.update'))
                                                            <x-admin.edit-button
                                                                onclick="editItemData({{ $item->id }}, '{{ $item?->getTranslation($code)?->title }}')" />
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Item')" route="admin.day.index" create="no"
                                                    :message="__('No data found!')" colspan="4" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $days->onEachSide(0)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('day.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Edit Day') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-admin.form-input type="hidden" name="code"
                            value="{{ request('code') ?? getSessionLanguage() }}" />
                        <div class="form-group col-12">
                            <x-admin.form-input id="itemTitle" name="title" label="{{ __('Title') }}"
                                placeholder="{{ __('Enter Title') }}" required="true" />
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <x-admin.button variant="danger" class="itemModalClose" data-bs-dismiss="modal"
                            text="{{ __('Close') }}" />
                        <x-admin.update-button :text="__('Update')" />
                    </div>
                </form>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script>
        "use strict"

        function editItemData(id, title) {
            $("#ItemForm").attr("action", "{{ url('/admin/day') }}" + '/' + id);
            $("#submitBtn").text("{{ __('Update') }}");
            $("#itemTitle").val(title);
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
                url: "{{ url('/admin/day/status-update') }}" + "/" + id,
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
