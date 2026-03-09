@extends('admin.master_layout')
@section('title')
    <title>{{ __('About Us') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('About Us') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('About Us') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li>
                                            <a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ currectUrlWithQuery($language->code) }}">
                                                <i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                            <div class="mt-2 alert alert-danger" role="alert">
                                @php
                                    $current_language = $languages->where('code', request()->get('code'))->first();
                                @endphp
                                <p>{{ __('Your editing mode') }}:<b> {{ $current_language?->name }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="row">
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('admin.pages.about.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent2">
                                    @include('admin.pages.about.sections.about')
                                    @include('admin.pages.about.sections.mission')
                                    @include('admin.pages.about.sections.vision')
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
            input_field: "#background_image_upload",
            preview_box: "#background_image_preview",
            label_field: "#background_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#mission_image_upload",
            preview_box: "#mission_image_preview",
            label_field: "#mission_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        $.uploadPreview({
            input_field: "#vision_image_upload",
            preview_box: "#vision_image_preview",
            label_field: "#vision_image_label",
            label_default: "{{ __('Choose Image') }}",
            label_selected: "{{ __('Update Image') }}",
            no_label: false,
            success_callback: null
        });
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab')
        });
    </script>
@endpush
