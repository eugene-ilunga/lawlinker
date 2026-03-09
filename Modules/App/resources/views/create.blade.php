@extends('admin.master_layout')
@section('title')
    <title>{{ __('Create Screen') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Screen') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('On Boarding Screens') => route('admin.app.screen.index'),
                __('Create Screen') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.app.screen.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.app.screen.store') }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="title"  name="title" label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}" value="{{ old('title') }}" required="true"/>
                                        </div>
                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input type="number" id="order"  name="order" label="{{ __('Order number') }}" placeholder="{{ __('Order number') }}" value="{{ old('order') }}" required="true"/>
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="sort_description" name="sort_description" label="{{ __('Short Description') }}" placeholder="{{ __('Enter Short Description') }}" value="{{ old('sort_description') }}" maxlength="500" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-image-preview recommended="375X812"/>
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
    <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
    <script>
        $.uploadPreview({
            input_field: "#image-upload",
            preview_box: "#image-preview",
            label_field: "#image-label",
            label_default: "{{ __('Choose Icon') }}",
            label_selected: "{{ __('Change Icon') }}",
            no_label: false,
            success_callback: null
        });
    </script>
@endpush
