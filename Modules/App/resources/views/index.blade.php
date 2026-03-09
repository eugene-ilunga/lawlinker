@extends('admin.master_layout')
@section('title')
    <title>{{ __('App Settings') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('App Settings') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('App Settings') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('app::tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="myTabContent2">
                            @include('app::sections.on_boarding')
                            @include('app::sections.banner')
                            @include('app::sections.app_store')
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
@endsection
@push('js')
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        "use strict"
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#image-upload-2",
            preview_box: "#image-preview-2",
            label_field: "#image-label-2",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#image-upload-4",
            preview_box: "#image-preview-4",
            label_field: "#image-label-4",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#image-upload-5",
            preview_box: "#image-preview-5",
            label_field: "#image-label-5",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#image-upload-6",
            preview_box: "#image-preview-6",
            label_field: "#image-label-6",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab');

            $('input[name="google_app_store_status"]').change(function() {
                $('.google-app-store-area').toggleClass('d-none');
            });
            $('input[name="apple_app_store_status"]').change(function() {
                $('.apple-app-store-area').toggleClass('d-none');
            });
        });
        
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/app/on-boarding-screen/') }}' + "/" + id)
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
                url: "{{ url('/admin/app/on-boarding-screen/status-update') }}" + "/" + id,
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

