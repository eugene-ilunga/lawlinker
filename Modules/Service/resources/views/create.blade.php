@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Service') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Service') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => route('admin.service.index'),
                __('Create Service') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.service.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.service.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="title"  name="title" label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}" value="{{ old('title') }}" required="true"/>
                                        </div>

                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="slug"  name="slug" label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}" value="{{ old('slug') }}" required="true"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input class="icon custom-icon-picker" id="icon"
                                                name="icon" autocomplete="off" label="{{ __('Icon') }}"
                                                placeholder="{{ __('Enter Icon') }}" required="true"
                                                value="{{ old('icon') }}" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="sort_description" name="sort_description" label="{{ __('Short Description') }}" placeholder="{{ __('Enter Short Description') }}" value="{{ old('sort_description') }}" maxlength="500" />
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
    <script>

        (function($) {
            "use strict";
            $(document).ready(function() {
                $("#title").on("keyup", function(e) {
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
