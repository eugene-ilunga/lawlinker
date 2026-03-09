@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Lawyer') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Lawyer') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Lawyer List') => route('admin.lawyer.index'),
                __('Edit Lawyer') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header gap-3 justify-content-between align-items-center">
                            <h5 class="m-0 service_card">{{ __('Available Translations') }}</h5>
                            @adminCan('lawyer.translate')
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
                                                href="{{ route('admin.lawyer.edit', ['lawyer' => $lawyer->id, 'code' => $language->code]) }}"><i
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
                                    @adminCan('lawyer.update')
                                        @if (!$lawyer?->email_verified_at)
                                            <x-admin.button variant="success" class="me-2" data-bs-toggle="modal"
                                                data-bs-target="#verifyModal" :text="__('Send Verify Link to Mail')" />
                                        @endif
                                    @endadminCan
                                    <x-admin.back-button :href="route('admin.lawyer.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form
                                    action="{{ route('admin.lawyer.update', [
                                        'lawyer' => $lawyer->id,
                                        'code' => $code,
                                    ]) }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row">
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="name" name="name" data-translate="true"
                                                label="{{ __('Name') }}" placeholder="{{ __('Enter Name') }}"
                                                value="{{ $lawyer->name }}" required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="slug" name="slug" label="{{ __('Slug') }}"
                                                placeholder="{{ __('Enter Slug') }}" value="{{ $lawyer->slug }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="phone" name="phone" label="{{ __('Phone') }}"
                                                placeholder="{{ __('Enter Phone') }}" value="{{ $lawyer->phone }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input type="email" id="email" name="email"
                                                label="{{ __('Email') }}" placeholder="{{ __('Enter Email') }}"
                                                value="{{ $lawyer->email }}" required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input type="password" id="password" name="password"
                                                label="{{ __('Password') }}" placeholder="{{ __('Enter Password') }}" />
                                        </div>
                                        <div class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="years_of_experience" name="years_of_experience" label="{{ __('Years of experience') }}"
                                                placeholder="{{ __('Enter Years of experience') }}" value="{{ $lawyer->years_of_experience }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-input id="fee" name="fee" label="{{ __('Fee') }}"
                                                placeholder="{{ __('Enter Fee') }}" value="{{ $lawyer->fee }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group @if ($code == $languages->first()->code) col-lg-4 col-md-6 @else col-12 @endif">
                                            <x-admin.form-input id="designations" name="designations" data-translate="true"
                                                label="{{ __('Designations') }}"
                                                placeholder="{{ __('Enter Designations') }}"
                                                value="{{ $lawyer->getTranslation($code)->designations }}"
                                                required="true" />
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-select name="department_id" id="department_id" class="select2"
                                                label="{{ __('Department') }}" required="true">
                                                <x-admin.select-option value=""
                                                    text="{{ __('Select Department') }}" />
                                                @foreach ($departments as $department)
                                                    <x-admin.select-option :selected="$department->id == $lawyer->department_id" value="{{ $department->id }}"
                                                        text="{{ $department->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div
                                            class="form-group col-lg-4  col-md-6 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-select name="location_id" id="location_id" class="select2"
                                                label="{{ __('Location') }}" required="true">
                                                <x-admin.select-option value=""
                                                    text="{{ __('Select Location') }}" />
                                                @foreach ($locations as $location)
                                                    <x-admin.select-option :selected="$location->id == $lawyer->location_id" value="{{ $location->id }}"
                                                        text="{{ $location->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="about" name="about"
                                                label="{{ __('About') }}"
                                                placeholder="{{ __('Enter About Information') }}" data-translate="true"
                                                value="{{ $lawyer->getTranslation($code)->about }}" maxlength="2000"
                                                required="true" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="address" name="address" data-translate="true"
                                                label="{{ __('Address') }}" value="{!! $lawyer->getTranslation($code)->address !!}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="educations" name="educations" data-translate="true"
                                                label="{{ __('Educations') }}" value="{!! $lawyer->getTranslation($code)->educations !!}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="experience" name="experience" data-translate="true"
                                                label="{{ __('Experience') }}" value="{!! $lawyer->getTranslation($code)->experience !!}"
                                                required="true" />
                                        </div>
                                        <div class="form-group col-md-12">
                                            <x-admin.form-editor id="qualifications" name="qualifications"
                                                data-translate="true" label="{{ __('Qualifications') }}"
                                                value="{!! $lawyer->getTranslation($code)->qualifications !!}" required="true" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="seo_title" name="seo_title"
                                                label="{{ __('SEO Title') }}" placeholder="{{ __('Enter SEO Title') }}"
                                                data-translate="true"
                                                value="{{ $lawyer->getTranslation($code)->seo_title }}" />
                                        </div>

                                        <div class="form-group col-md-12">
                                            <x-admin.form-textarea id="seo_description" name="seo_description"
                                                label="{{ __('SEO Description') }}"
                                                placeholder="{{ __('Enter SEO Description') }}" data-translate="true"
                                                value="{{ $lawyer->getTranslation($code)->seo_description }}"
                                                maxlength="2000" />
                                        </div>
                                        <div
                                            class="form-group col-md-12 {{ $code == $languages->first()->code ? '' : 'd-none' }}">
                                            <x-admin.form-image-preview recommended="300X270" name="lawyer_image" :image="$lawyer->image" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        @if ($code == $languages->first()->code)
                                            <div class="form-group col-md-12">
                                                <x-admin.form-switch name="show_homepage"
                                                    label="{{ __('Show on homepage') }}" :checked="$lawyer->show_homepage == 1" />
                                            </div>

                                            <div class="form-group col-md-12">
                                                <x-admin.form-switch name="status" label="{{ __('Status') }}"
                                                    :checked="$lawyer->status ==
                                                        Modules\Lawyer\app\Enums\LawyerStatus::ACTIVE->value" />
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
    @adminCan('lawyer.update')
        @if (!$lawyer?->email_verified_at)
            <!-- Start Verify modal -->
            <div class="modal fade" id="verifyModal" tabindex="-1" role="dialog" aria-labelledby="modelTitleId"
                aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Send verify link to Lawyer mail') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <p>{{ __('Are you sure want to send verify link to lawyer mail?') }}</p>

                                <form action="{{ route('admin.lawyer-send-verify-mail', $lawyer->id) }}" method="POST">
                                    @csrf

                            </div>
                        </div>
                        <div class="modal-footer">
                            <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                            <x-admin.button type="submit" text="{{ __('Send Mail') }}" />
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Verify modal -->
        @endif
    @endadminCan
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
                    $("#name").on("keyup", function(e) {
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
