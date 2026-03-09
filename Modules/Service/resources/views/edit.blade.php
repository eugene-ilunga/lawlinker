@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Service') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Service') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => route('admin.service.index'),
                __('Edit Service') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header justify-content-between">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('service.translate')
                                @if ($code !== $languages->first()->code)
                                    <x-admin.button onclick="translateAll()" id="translate-btn" text="{{ __('Translate') }}" />
                                @endif
                            @endadminCan
                        </div>
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages = allLanguages() as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.service.edit', ['service' => $service->id, 'code' => $language->code]) }}"><i
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
            @if ($code == $languages->first()->code)
                @include('service::utilities.navbar')
            @endif
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.service.update', [
                                        'service' => $service->id,
                                        'code' => $code,
                                    ]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div
                                            class="form-group col-md-12 {{ $code == $languages->first()->code ? 'col-lg-6' : '' }}">
                                            <x-admin.form-input id="title" data-translate="true" name="title"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                value="{{ $service?->getTranslation($code)?->title }}" required="true" />
                                        </div>

                                        @if ($code == $languages->first()->code)
                                            <div class="form-group col-md-12 col-lg-6">
                                                <x-admin.form-input id="slug" name="slug"
                                                    label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}"
                                                    value="{{ $service->slug }}" required="true" />
                                            </div>
                                        @endif
                                        <div @class([
                                            'form-group col-md-12',
                                            'd-none' => $code != $languages->first()->code,
                                        ])>
                                            <x-admin.form-input class="icon custom-icon-picker" id="icon"
                                                name="icon" autocomplete="off" label="{{ __('Icon') }}"
                                                placeholder="{{ __('Enter Icon') }}" required="true"
                                                value="{{ old('icon', $service?->icon) }}" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea data-translate="true" id="sort_description"
                                                name="sort_description" label="{{ __('Short Description') }}"
                                                placeholder="{{ __('Enter Short Description') }}"
                                                value="{{ $service?->getTranslation($code)?->sort_description }}"
                                                maxlength="500" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor data-translate="true" id="description" name="description"
                                                label="{{ __('Description') }}" value="{!! $service?->getTranslation($code)?->description !!}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input data-translate="true" id="seo_title" name="seo_title"
                                                label="{{ __('SEO Title') }}" placeholder="{{ __('Enter SEO Title') }}"
                                                value="{{ $service?->getTranslation($code)?->seo_title }}" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea data-translate="true" id="seo_description"
                                                name="seo_description" label="{{ __('SEO Description') }}"
                                                placeholder="{{ __('Enter SEO Description') }}"
                                                value="{{ $service?->getTranslation($code)?->seo_description }}"
                                                maxlength="2000" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        @if ($code == $languages->first()->code)
                                            <div class="form-group col-md-12">
                                                <x-admin.form-switch name="show_homepage"
                                                    label="{{ __('Show on homepage') }}" :checked="$service->show_homepage == 1" />
                                            </div>
                                            <div class="form-group col-md-12">
                                                <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                    :checked="$service->status == 1" />
                                            </div>
                                        @endif
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
    {{-- Image preview --}}
    @if ($code == $languages->first()->code)
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
    @else
        <script>
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
    @endif
@endpush
