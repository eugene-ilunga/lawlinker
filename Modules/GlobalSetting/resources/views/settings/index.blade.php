@extends('admin.master_layout') @section('title')
    <title>{{ __('General Setting') }}</title>
    @endsection @section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('General Setting') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('General Setting') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('globalsetting::settings.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent2">
                                    @include('globalsetting::settings.sections.general')
                                    @include('globalsetting::settings.sections.blog_comment')
                                    @include('globalsetting::settings.sections.website')
                                    @include('globalsetting::settings.sections.preloader')
                                    @include('globalsetting::settings.sections.logo-favicon')
                                    @include('globalsetting::settings.sections.color')
                                    @include('globalsetting::settings.sections.cookie')
                                    @include('globalsetting::settings.sections.custom-paginate')
                                    @include('globalsetting::settings.sections.default-avatar')
                                    @include('globalsetting::settings.sections.error_page')
                                    @include('globalsetting::settings.sections.breadcrump')
                                    @include('globalsetting::settings.sections.maintenance-mode')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
<script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
         $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#favicon-upload",
            preview_box: "#favicon-preview",
            label_field: "#favicon-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#preloader-image-upload",
            preview_box: "#preloader-image-preview",
            label_field: "#preloader-image-label",
            label_default: "{{ __('Choose GIF') }}",
            label_selected: "{{ __('Update GIF') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#breadcrumb_image_upload",
            preview_box: "#breadcrumb_image_preview",
            label_field: "#breadcrumb_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#default_avatar_upload",
            preview_box: "#default_avatar_preview",
            label_field: "#default_avatar_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#error_image_upload",
            preview_box: "#error_image_preview",
            label_field: "#error_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#maintenance_image_upload",
            preview_box: "#maintenance_image_preview",
            label_field: "#maintenance_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });


        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab')
        });

        //Maintenance mode toggler
        function changeMaintenanceModeStatus() {
            var isDemo = "{{ strtoupper(config('app.app_mode')) ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/update-maintenance-mode-status') }}",
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
            })
        }
    </script>

    <script>
        function copyText() {
            var textToCopy = document.getElementById("copyCronText");
            var range = document.createRange();
            range.selectNode(textToCopy);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");

            toastr.success("{{ __('Copied to clipboard') }}");
        }
    </script>
    <script>
        "use strict";
        $(document).ready(function() {
            $('#comment_type').change(function() {
                $('.facebook-comment-box').toggleClass('d-none');
            });
            $('input[name="preloader"]').change(function() {
                $('.preloader-image-box').toggleClass('d-none');
            });
            $('input[name="lawyer_can_add_social_links"]').change(function() {
                $('.limit-box').toggleClass('d-none');
            });
        });
    </script>
@endpush
