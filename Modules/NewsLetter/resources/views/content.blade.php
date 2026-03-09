@extends('admin.master_layout')
@section('title')
    <title>{{ __('Subscriber Content') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Subscriber Content') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Subscriber Content') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                                @if ($code !== $languages->first()->code)
                                    <x-admin.button onclick="translateAll()" id="translate-btn" text="{{ __('Translate') }}" />
                                @endif
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.subscriber-content', ['code' => $language->code]) }}"><i
                                                    class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                                {{ $language->name }}</a></li>
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
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.subscriber-content.update', ['code' => $code]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('put')

                                    <div class="row">
                                        <div class="form-group">
                                            <x-admin.form-input data-translate="true" id="title" name="title" label="{{ __('Title') }}"
                                                placeholder="{{ __('Enter Title') }}"
                                                value="{{ $subscriber_content?->getTranslation($code)?->title }}"
                                                required="true" />
                                        </div>

                                        <div class="form-group">
                                            <x-admin.form-textarea data-translate="true" id="description" name="description" label="{{ __('Description') }}" required="true" value="{{ $subscriber_content?->getTranslation($code)?->description }}" maxlength="1000" placeholder="{{ __('Enter Description') }}"/>
                                        </div>

                                        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
                                            <x-admin.form-image-preview recommended="1900X470" label="{{ __('Banner Image') }}" :image="$subscriber_content?->image"
                                                required="0" />
                                        </div>
                                    </div>
                                    <div class="row">
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

        var isTranslatingInputs = true;

        function translateOneByOne(inputs, index = 0) {
            if (index >= inputs.length) {
                if (isTranslatingInputs) {
                    isTranslatingInputs = false;
                    translateAllTextarea();
                }
                $('#translate-btn').prop('disabled', false);
                $('#update-btn').prop('disabled', false);
                return;
            }

            var $input = $(inputs[index]);
            var inputValue = $input.val();

            if (inputValue) {
                $.ajax({
                    url: "{{ route('admin.languages.update.single') }}",
                    type: "POST",
                    data: {
                        lang: '{{ $code }}',
                        text: inputValue,
                        _token: '{{ csrf_token() }}'
                    },
                    dataType: 'json',
                    beforeSend: function() {
                        $input.prop('disabled', true);
                        iziToast.show({
                            timeout: false,
                            close: true,
                            theme: 'dark',
                            icon: 'loader',
                            iconUrl: 'https://hub.izmirnic.com/Files/Images/loading.gif',
                            title: "{{ __('Translation Processing, please wait...') }}",
                            position: 'center',
                        });
                    },
                    success: function(response) {
                        $input.val(response);
                        $input.prop('disabled', false);
                        iziToast.destroy();
                        toastr.success("{{ __('Translated Successfully!') }}");
                        translateOneByOne(inputs, index + 1);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error(textStatus, errorThrown);
                        iziToast.destroy();
                        toastr.error('Error', 'Error');
                    }
                });
            } else {
                translateOneByOne(inputs, index + 1);
            }
        }

        function translateAll() {
            iziToast.question({
                timeout: 20000,
                close: false,
                overlay: true,
                displayMode: 'once',
                id: 'question',
                zindex: 999,
                title: "{{ __('This will take a while!') }}",
                message: "{{ __('Are you sure?') }}",
                position: 'center',
                buttons: [
                    ["<button><b>{{ __('YES') }}</b></button>", function(instance, toast) {
                        var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}";

                        if (isDemo == 'DEMO') {
                            instance.hide({
                                transitionOut: 'fadeOut'
                            }, toast, 'button');
                            toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                            return;
                        }

                        $('#translate-btn').prop('disabled', true);
                        $('#update-btn').prop('disabled', true);

                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');

                        var inputs = $('input[data-translate="true"]').toArray();
                        translateOneByOne(inputs);

                    }, true],
                    ["<button>{{ __('NO') }}</button>", function(instance, toast) {

                        instance.hide({
                            transitionOut: 'fadeOut'
                        }, toast, 'button');

                    }],
                ],
                onClosing: function(instance, toast, closedBy) {},
                onClosed: function(instance, toast, closedBy) {}
            });
        };

        function translateAllTextarea() {
            var inputs = $('textarea[data-translate="true"]').toArray();
            if (inputs.length === 0) {
                return;
            }
            translateOneByOne(inputs);
        }

        $(document).ready(function() {
            var selectedTranslation = $('#selected-language').text();
            var btnText = "{{ __('Translate to') }} " + selectedTranslation;
            $('#translate-btn').text(btnText);
        });
    </script>
@endpush
