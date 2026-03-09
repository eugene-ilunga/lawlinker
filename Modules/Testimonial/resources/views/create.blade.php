@extends('admin.master_layout')
@section('title')
    <title>{{ __('Testimonials') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Testimonial') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Testimonials') => route('admin.testimonial.index'),
                __('Create Testimonial') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.testimonial.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.testimonial.store') }}" enctype="multipart/form-data"
                                    method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="name" name="name"
                                                    label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}"
                                                    value="{{ old('name') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="designation" name="designation"
                                                    label="{{ __('Designation') }}"
                                                    placeholder="{{ __('Enter Designation') }}"
                                                    value="{{ old('designation') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-textarea id="comment" name="comment"
                                                    label="{{ __('Comment') }}" placeholder="{{ __('Enter Comment') }}"
                                                    value="{{ old('comment') }}" maxlength="5000" required="true"/>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input type="number" id="rating" name="rating" min="1" max="5"
                                                    label="{{ __('Rating') }}" placeholder="{{ __('Enter Rating') }}"
                                                    value="{{ old('rating') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-image-preview recommended="80X80" name="user_img" label="Image" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-switch name="show_homepage"
                                                    label="{{ __('Show on homepage') }}" :checked="old('show_homepage') == 1" />
                                            </div>
                                        </div>
                                        @adminCan('testimonial.store')
                                        <div class="col-md-12">
                                            <x-admin.save-button :text="__('Save')" />
                                        </div>
                                        @endadminCan
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
@endpush
