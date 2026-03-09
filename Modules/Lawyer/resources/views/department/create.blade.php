@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Department') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Department') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Department List') => route('admin.department.index'),
                __('Create Department') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.department.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.department.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="name"  name="name" label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}" value="{{ old('name') }}" required="true"/>
                                        </div>

                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="slug"  name="slug" label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}" value="{{ old('slug') }}" required="true"/>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="description" name="description" label="{{ __('Description') }}" value="{!! old('description') !!}" required="true"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="seo_title" name="seo_title" label="{{ __('SEO Title') }}" placeholder="{{ __('Enter SEO Title') }}" value="{{ old('seo_title') }}"/>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="seo_description" name="seo_description" label="{{ __('SEO Description') }}" placeholder="{{ __('Enter SEO Description') }}" value="{{ old('seo_description') }}" maxlength="2000" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-image-preview recommended="730X480" label="{{ __('Thumbnail Image') }}" button_label="{{ __('Upload Image') }}" />
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <x-admin.form-switch name="show_homepage" label="{{ __('Show on homepage') }}" :checked="old('show_homepage') == 1"/>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-switch name="status" label="{{ __('Status') }}" :checked="old('status') == 1"/>
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.save-button :text="__('Save')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    {{-- Image preview --}}
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });

        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#name").on("keyup", function(e) {
                    $("#slug").val(convertToSlug($(this).val()));
                })
            });
        })(jQuery);

        function convertToSlug(Text) {
            return Text
                .toLowerCase()
                .replace(/[^\w ]+/g, '')
                .replace(/ +/g, '-');
        }
    </script>
@endpush
