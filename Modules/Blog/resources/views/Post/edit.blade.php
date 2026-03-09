@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Post') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Post') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Blog List') => route('admin.blogs.index'),
                __('Edit Post') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('blog.translate')
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
                                                href="{{ route('admin.blogs.edit', ['blog' => $blog->id, 'code' => $language->code]) }}"><i
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
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.blogs.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.blogs.update', [
                                        'blog' => $blog->id,
                                        'code' => $code,
                                    ]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="title" data-translate="true" name="title"
                                                label="{{ __('Title') }}" placeholder="{{ __('Enter Title') }}"
                                                value="{{ $blog->getTranslation($code)->title }}" required="true" />
                                        </div>
                                        @if ($code == $languages->first()->code)
                                            <div class="form-group col-md-8">
                                                <x-admin.form-input id="slug" name="slug"
                                                    label="{{ __('Slug') }}" placeholder="{{ __('Enter Slug') }}"
                                                    value="{{ $blog->slug }}" required="true" />
                                            </div>

                                            <div class="form-group col-md-4">
                                                <x-admin.form-select name="blog_category_id" id="blog_category_id"
                                                    class="select2" label="{{ __('Category') }}" required="true">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Category') }}" />
                                                    @foreach ($categories as $category)
                                                        <x-admin.select-option :selected="$category->id ==
                                                            old('blog_category_id', $blog->blog_category_id)"
                                                            value="{{ $category->id }}" text="{{ $category->title }}" />
                                                    @endforeach
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="sort_description" name="sort_description"
                                                label="{{ __('Short Description') }}"
                                                placeholder="{{ __('Enter Short Description') }}"
                                                value="{{ $blog->getTranslation($code)->sort_description }}"
                                                maxlength="2000" required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="description" name="description"
                                                label="{{ __('Description') }}" value="{!! $blog->getTranslation($code)->description !!}"
                                                required="true" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="seo_title" name="seo_title"
                                                label="{{ __('SEO Title') }}" placeholder="{{ __('Enter SEO Title') }}"
                                                data-translate="true"
                                                value="{{ $blog->getTranslation($code)->seo_title }}" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="seo_description" name="seo_description"
                                                label="{{ __('SEO Description') }}"
                                                placeholder="{{ __('Enter SEO Description') }}" data-translate="true"
                                                value="{{ $blog->getTranslation($code)->seo_description }}"
                                                maxlength="2000" />
                                        </div>
                                        @if ($code == $languages->first()->code)
                                            <div class="form-group col-md-12">
                                                <x-admin.form-image-preview recommended="730X410" name="blog_image" :image="$blog->image" required="0" />
                                            </div>
                                        @endif

                                    </div>
                                    <div class="row">
                                        @if ($code == $languages->first()->code)
                                            <div class="col-md-12  mb-3">
                                                <x-admin.form-switch name="show_homepage"
                                                    label="{{ __('Show on homepage') }}" :checked="$blog->show_homepage == 1" />
                                            </div>

                                            <div class="col-md-12  mb-3">
                                                <x-admin.form-switch name="is_feature" label="{{ __('Show on Feature') }}"
                                                    :checked="$blog->is_feature == 1" />
                                            </div>

                                            <div class="col-md-12  mb-3">
                                                <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                    :checked="$blog->status == 1" />
                                            </div>
                                        @endif
                                        @adminCan('blog.update')
                                            <div class="col-md-12">
                                                <x-admin.update-button :text="__('Update')" />
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
    @if ($code == $languages->first()->code)
        <script src="{{ asset('backend/js/jquery.uploadPreview.min.js') }}"></script>
        <script>
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
