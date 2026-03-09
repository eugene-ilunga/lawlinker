@extends('admin.master_layout')
@section('title')
    <title>{{ __('Sliders') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Sliders') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Sliders') => '#',
            ]" />
            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('Sliders') }}</h4>
                        @if (checkAdminHasPermission('slider.store'))
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
                                        <th width="15%">{{ __('Title') }}</th>
                                        <th width="15%">{{ __('Image') }}</th>
                                        <th width="15%">{{ __('Status') }}</th>
                                        <th width="15%">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($sliders as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td><img src="{{ asset($item?->image) }}" class="img-thumbnail my-2"></td>
                                            <td>
                                                <input onchange="changeStatus({{ $item->id }})" id="status_toggle"
                                                    type="checkbox" {{ $item->status ? 'checked' : '' }}
                                                    data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                    data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </td>
                                            <td>
                                                @if (checkAdminHasPermission('slider.update'))
                                                    <x-admin.edit-button
                                                        onclick="editItemData({{ $item->id }}, '{{ $item->title }}', '{{ asset($item->image) }}')" />
                                                @endif
                                                @if (checkAdminHasPermission('slider.delete'))
                                                    <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <x-empty-table :name="__('Item')" route="admin.slider.index" create="no"
                                            :message="__('No data found!')" colspan="7" />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $sliders->onEachSide(0)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('slider.delete'))
        <x-admin.delete-modal />
    @endif

    @if (checkAdminHasPermission('slider.store') || checkAdminHasPermission('slider.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.slider.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Slider') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <x-admin.form-input id="title" name="title" label="{{ __('Title') }}"
                                placeholder="{{ __('Enter Title') }}" required="true" />
                        </div>
                        <div class="row">
                            <div class="form-group col-md-6 image-field">
                                <x-admin.form-image-preview recommended="1000X690" recommended_class="d-block" label="{{ __('Image') }}" />
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
@push('css')
    <style>
        .image-preview{
            width: auto;
        }
    </style>
@endpush
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
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/slider/') }}' + "/" + id);
        }
        "use strict"
        $('.itemModalClose').on('click', function() {
            $("#ItemForm").attr("action", "{{ url('/admin/slider') }}");
            $("#itemModal .modal-title").text("{{ __('Create Slider') }}");
            $("#title").val('');
            $("#submitBtn").text("{{ __('Save') }}");
            $("#image-preview").css('background-image', 'none');
            $(".image-field label span").removeClass('d-none');
            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, title, image) {
            $("#ItemForm").attr("action", "{{ url('/admin/slider') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Slider') }}");
            $("#submitBtn").text("{{ __('Update') }}");
            $("#title").val(title);
            $(".image-field label span").addClass('d-none');
            $("#image-preview").css({
                'background-image': 'url(' + image + ')',
                'background-size': 'contain',
                'background-position': 'center center'
            });
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
                url: "{{ url('/admin/slider/status-update') }}" + "/" + id,
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
