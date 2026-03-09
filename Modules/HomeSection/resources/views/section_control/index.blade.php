@extends('admin.master_layout')
@section('title')
    <title>{{ __('Section Control') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Section Control') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Section Control') => '#',
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
                                    @include('homesection::section_control.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="card">
                            <div class="card-body">
                                <div class="tab-content" id="myTabContent2">
                                    @if ($code == $languages->first()->code)
                                        @include('homesection::section_control.sections.feature')
                                    @endif
                                    @include('homesection::section_control.sections.work')
                                    @include('homesection::section_control.sections.service')
                                    @include('homesection::section_control.sections.department')
                                    @include('homesection::section_control.sections.client')
                                    @include('homesection::section_control.sections.lawyer')
                                    @include('homesection::section_control.sections.blog')
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
    <script>
        //Tab active setup locally
        $(document).ready(function() {
            activeTabSetupLocally('generalTab')
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
