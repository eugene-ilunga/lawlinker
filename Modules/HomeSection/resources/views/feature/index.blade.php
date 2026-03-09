@extends('admin.master_layout')
@section('title')
    <title>{{ __('Features') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Features') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Features') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.feature.index', ['code' => $language->code]) }}"><i
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
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('Features') }}</h4>
                        @adminCan('feature.store')
                            <div>
                                <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#itemModal"
                                    class="btn btn-primary itemModalClose"><i class="fa fa-plus"></i>
                                    {{ __('Add New') }}</a>
                            </div>
                        @endadminCan
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th width="5%">{{ __('SN') }}</th>
                                        <th width="10%">{{ __('Icon') }}</th>
                                        <th width="15%">{{ __('Image') }}</th>
                                        <th width="15%">{{ __('Title') }}</th>
                                        <th width="15%">{{ __('Description') }}</th>
                                        @adminCan('feature.update')
                                            <th width="15%">{{ __('Status') }}</th>
                                        @endadminCan
                                        @if (checkAdminHasPermission('feature.update') || checkAdminHasPermission('feature.delete'))
                                            <th width="20%">{{ __('Action') }}</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($features as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><i class="{{ $item->icon }} feature-icon"></i></td>
                                            <td><img src="{{ asset($item?->image) }}" class="img-thumbnail my-2"></td>
                                            <td>{{ $item?->getTranslation($code)?->title }}</td>
                                            <td>{{ $item?->getTranslation($code)?->description }}</td>
                                            @adminCan('feature.update')
                                                <td>
                                                    <input onchange="changeStatus({{ $item->id }})" id="status_toggle"
                                                        type="checkbox" {{ $item->status ? 'checked' : '' }}
                                                        data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                        data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                        data-offstyle="danger">
                                                </td>
                                            @endadminCan
                                            @if (checkAdminHasPermission('feature.update') || checkAdminHasPermission('feature.delete'))
                                                <td>
                                                    @adminCan('feature.update')
                                                        <x-admin.edit-button
                                                            onclick="editItemData({{ $item->id }}, '{{ $item->icon }}', '{{ asset($item->image) }}', '{{ $item?->getTranslation($code)?->title }}', '{{ $item?->getTranslation($code)?->description }}')" />
                                                    @endadminCan
                                                    @adminCan('feature.delete')
                                                        <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                                    @endadminCan
                                                </td>
                                            @endif
                                        </tr>
                                    @empty
                                        <x-empty-table :name="__('Item')" route="admin.feature.index" create="no"
                                            :message="__('No data found!')" colspan="7" />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $features->onEachSide(0)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('feature.delete')
        <x-admin.delete-modal />
    @endadminCan

    @if (checkAdminHasPermission('feature.store') || checkAdminHasPermission('feature.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog modal-lg" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.feature.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Feature') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-admin.form-input type="hidden" name="code" value="{{ $code }}" />
                        <div class="row">
                            @if ($code == $languages->first()->code)
                                <div class="form-group col-md-6">
                                    <x-admin.form-image-preview recommended="645X645" recommended_class="d-block"
                                        label="{{ __('Image') }}" required="0" />
                            @endif
                        </div>
                        <div @class([
                            'form-group col-md-12',
                            'd-none' => $code != $languages->first()->code,
                        ])>
                            <x-admin.form-input class="icon custom-icon-picker" id="icon" name="icon" autocomplete="off"
                                label="{{ __('Icon') }}" placeholder="{{ __('Enter Icon') }}" required="true" />
                        </div>
                        <div class="form-group col-12">
                            <x-admin.form-input id="title" name="title" label="{{ __('Title') }}"
                                placeholder="{{ __('Enter Title') }}" required="true" />
                        </div>
                        <div class="form-group mb-0">
                            <x-admin.form-textarea id="description" name="description" label="{{ __('Description') }}"
                                placeholder="{{ __('Enter Description') }}" maxlength="900" required="true" />
                        </div>
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
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        "use strict";
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
    <script>
        @adminCan('feature.delete')
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/feature/') }}' + "/" + id);
        }
        @endadminCan

        "use strict"
        $('.itemModalClose').on('click', function() {
            var default_lang = "{{ $code == $languages->first()->code }}";
            $("#ItemForm").attr("action", "{{ url('/admin/feature') }}");
            $("#itemModal .modal-title").text("{{ __('Create Feature') }}");
            $("#submitBtn").text("{{ __('Save') }}");
            $("#title").val('');
            $("#icon").val('');
            $("#description").val('');
            if (default_lang) {
                $("#image-preview").css('background-image', 'none');
            }

            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, icon, image, title, description) {
            var default_lang = "{{ $code == $languages->first()->code }}";
            $("#ItemForm").attr("action", "{{ url('/admin/feature') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Feature') }}");
            $("#submitBtn").text("{{ __('Update') }}");
            $("#title").val(title);
            $("#icon").val(icon);
            $("#description").val(description);
            if (default_lang) {
                $("#image-preview").css({
                    'background-image': 'url(' + image + ')'
                });
            }
            $('#itemModal').modal('show');
        }

        @adminCan('feature.update')
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
                url: "{{ url('/admin/feature/status-update') }}" + "/" + id,
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
