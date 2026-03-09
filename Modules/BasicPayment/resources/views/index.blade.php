@extends('admin.master_layout')
@section('title')
    <title>{{ __('Payment Gateway') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Payment Gateway') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Payment Gateway') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="basicPaymentTab" role="tablist">
                                    @include('basicpayment::tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent4">
                                    @include('basicpayment::sections.stripe')
                                    @include('basicpayment::sections.paypal')
                                    @include('basicpayment::sections.direct-bank')
                                    @include('basicpayment::sections.razorpay')
                                    @include('basicpayment::sections.flutterwave')
                                    @include('basicpayment::sections.paystack')
                                    @include('basicpayment::sections.mollie')
                                    @include('basicpayment::sections.instamojo')
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
            input_field: "#stripe_image_upload",
            preview_box: "#stripe_image_preview",
            label_field: "#stripe_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#paypal_image_upload",
            preview_box: "#paypal_image_preview",
            label_field: "#paypal_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#bank_image_upload",
            preview_box: "#bank_image_preview",
            label_field: "#bank_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#image-upload-razorpay",
            preview_box: "#image-preview-razorpay",
            label_field: "#image-label-razorpay",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });

        $.uploadPreview({
            input_field: "#image-upload-flutterwave",
            preview_box: "#image-preview-flutterwave",
            label_field: "#image-label-flutterwave",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });

        $.uploadPreview({
            input_field: "#image-upload-paystack",
            preview_box: "#image-preview-paystack",
            label_field: "#image-label-paystack",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });

        $.uploadPreview({
            input_field: "#image-upload-mollie",
            preview_box: "#image-preview-mollie",
            label_field: "#image-label-mollie",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });

        $.uploadPreview({
            input_field: "#image-upload-instamojo",
            preview_box: "#image-preview-instamojo",
            label_field: "#image-label-instamojo",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Change Image') }}",
            no_label: false,
            success_callback: null
        });
    </script>
    <script>
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('basicPaymentTab')
        });
    </script>
@endpush
