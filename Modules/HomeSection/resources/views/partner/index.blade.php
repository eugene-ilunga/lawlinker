@extends('admin.master_layout')
@section('title')
    <title>{{ __('Partners') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Partners') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Partners') => '#',
            ]" />
            <div class="section-body">
                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h4>{{ __('Partners') }}</h4>
                        @if (checkAdminHasPermission('partner.store'))
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
                                        <th width="30%">{{ __('Image') }}</th>
                                        <th width="20%">{{ __('Link') }}</th>
                                        <th width="15%">{{ __('Status') }}</th>
                                        <th width="20%">{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($partners as $item)
                                        <tr>
                                            <td>{{ $loop->index + 1 }}</td>
                                            <td><img height="60" src="{{ asset($item?->image) }}"
                                                    class="rounded my-2 w-auto"></td>
                                            <td>{{ $item?->link }}
                                            </td>
                                            <td>
                                                <input onchange="changeStatus({{ $item->id }})" id="status_toggle"
                                                    type="checkbox" {{ $item->status ? 'checked' : '' }}
                                                    data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                    data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                    data-offstyle="danger">
                                            </td>
                                            <td>
                                                @if (checkAdminHasPermission('partner.update'))
                                                    <x-admin.edit-button
                                                        onclick="editItemData({{ $item->id }}, '{{ $item?->link }}', '{{ asset($item->image) }}')" />
                                                @endif
                                                @if (checkAdminHasPermission('partner.delete'))
                                                    <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <x-empty-table :name="__('Item')" route="admin.partner.index" create="no"
                                            :message="__('No data found!')" colspan="7" />
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="float-right">
                            {{ $partners->onEachSide(0)->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('partner.delete'))
        <x-admin.delete-modal />
    @endif

    @if (checkAdminHasPermission('partner.store') || checkAdminHasPermission('partner.update'))
        <div tabindex="-1" role="dialog" id="itemModal" class="modal fade">
            <div class="modal-dialog" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.partner.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create Partner') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <x-admin.form-image-preview recommended="270X140" label="{{ __('Image') }}" />
                        </div>
                        <div class="form-group">
                            <x-admin.form-input type="link" id="itemLink" name="link"
                                label="{{ __('Profile Link') }}" placeholder="{{ __('Enter Profile Link') }}" />
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
        .image-preview {
            width: 255px;
            height: 113px;
            background-repeat: no-repeat;
        }

        .image-preview label {
            width: 90px;
            height: 36px;
            line-height: 36px;
            font-size: 10px;
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
            $("#deleteForm").attr("action", '{{ url('/admin/partner/') }}' + "/" + id);
        }
        "use strict"
        $('.itemModalClose').on('click', function() {
            $("#ItemForm").attr("action", "{{ url('/admin/partner') }}");
            $("#itemModal .modal-title").text("{{ __('Create Partner') }}");
            $("#submitBtn").text("{{ __('Save') }}");
            $("#itemLink").val('');
            $("label span").removeClass('d-none');
            $("#image-preview").css('background-image', 'none');
            $('#itemModal').modal('hide');
        });

        "use strict"

        function editItemData(id, link, image) {
            $("#ItemForm").attr("action", "{{ url('/admin/partner') }}" + '/' + id);
            $("#itemModal .modal-title").text("{{ __('Edit Partner') }}");
            $("#submitBtn").text("{{ __('Update') }}");
            $("#itemLink").val(link);
            $("label span").addClass('d-none');
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
                url: "{{ url('/admin/partner/status-update') }}" + "/" + id,
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
