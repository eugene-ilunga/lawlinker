@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Screen') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Screen') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('On Boarding Screens') => route('admin.app.screen.index'),
                __('Edit Screen') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.app.screen.update', ['on_boarding_screen' => $screen->id]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div
                                            class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input id="title" data-translate="true" name="title"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                value="{{ $screen?->title }}" required="true" />
                                        </div>
                                        <div class="form-group col-md-12 col-lg-6">
                                            <x-admin.form-input type="number" id="order"  name="order" label="{{ __('Order number') }}" placeholder="{{ __('Order number') }}" value="{{ $screen?->order }}" required="true"/>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea data-translate="true" id="sort_description"
                                                name="sort_description" label="{{ __('Short Description') }}"
                                                placeholder="{{ __('Enter Short Description') }}"
                                                value="{{ $screen?->sort_description }}" maxlength="500" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-image-preview image="{{ $screen->image }}" required="0" recommended="375X812" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                :checked="$screen->status == 1" />
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.update-button :text="__('Update')" />
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
