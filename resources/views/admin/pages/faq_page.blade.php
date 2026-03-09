@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQ Page') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('FAQ Page') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQ Page') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.pages.faq.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <x-admin.form-image-preview recommended="520X770" :image="$faq_page?->image" name="image" label="{{ __('Existing Image') }}"
                                                button_label="{{ __('Update Image') }}" />
                                        </div>
                                    </div>
                                    <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
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
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
@endpush
