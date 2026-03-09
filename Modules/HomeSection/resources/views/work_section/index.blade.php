@extends('admin.master_layout')
@section('title')
    <title>{{ __('Work Section') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Work Section') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Work Section') => '#',
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
                                                href="{{ route('admin.work-section.index', ['code' => $language->code]) }}"><i
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
                    <div class="col-md-3">
                        <div class="card">
                            <div class="card-body">
                                <ul class="nav nav-pills flex-column" id="generalTab" role="tablist">
                                    @include('homesection::work_section.tabs.navbar')
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <div class="tab-content" id="myTabContent2">
                            @include('homesection::work_section.sections.work')
                            @if (checkAdminHasPermission('work.section.faq.view'))
                                @include('homesection::work_section.sections.faq')
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @if (checkAdminHasPermission('work.section.faq.delete'))
        <x-admin.delete-modal />
    @endif
    @if (checkAdminHasPermission('work.section.faq.store'))
        <div tabindex="-1" role="dialog" id="create_faq" class="modal fade">
            <div class="modal-dialog modal-lg" role="document">
                <form class="modal-content" id="ItemForm" action="{{ route('admin.work-section-faq.store') }}"
                    method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create FAQ') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <x-admin.form-input type="hidden" id="work_section_id" name="work_section_id"
                            value="{{ $workSection?->id }}" />
                        <input type="hidden" name="code" value="{{ request('code') ?? getSessionLanguage() }}">
                        <div class="form-group">
                            <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                placeholder="{{ __('Enter Question') }}" value="{{ old('question') }}" required="true" />
                        </div>

                        <div class="form-group">
                            <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                placeholder="{{ __('Enter Answer') }}" value="{{ old('answer') }}" maxlength="1000" required="true"/>
                        </div>
                    </div>
                    <div class="modal-footer bg-whitesmoke br">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.save-button :text="__('Save')" />
                    </div>
                </form>
            </div>
        </div>
    @endif
    @if (checkAdminHasPermission('work.section.faq.update'))
        @foreach ($faqs as $index => $faq)
            <div class="modal fade" id="edit_faq_id_{{ $faq->id }}" tabindex="-1" role="dialog"
                aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Edit Faq') }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container-fluid">
                                <form
                                    action="{{ route('admin.work-section-faq.update', ['work_section_faq' => $faq->id, 'code' => $code]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <x-admin.form-input type="hidden" id="work_section_id" name="work_section_id"
                                        value="{{ $faq->work_section_id }}" />
                                    <div class="form-group">
                                        <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                            placeholder="{{ __('Enter Question') }}"
                                            value="{{ $faq->getTranslation($code)->question }}" required="true" />
                                    </div>

                                    <div class="form-group">
                                        <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                            placeholder="{{ __('Enter Answer') }}"
                                            value="{{ $faq->getTranslation($code)->answer }}" maxlength="1000" required="true"/>
                                    </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                            <x-admin.update-button :text="__('Update')" />
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    @endif
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
        
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/work-section-faq/') }}' + "/" + id);
        }
        "use strict"

        function changeStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/work-section-faq/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
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
