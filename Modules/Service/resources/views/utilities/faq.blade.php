@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQS') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('FAQS') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => route('admin.service.index'),
                __('Edit Service') => route('admin.service.edit', [
                    'service' => $service->id,
                    'code' => allLanguages()->first()->code,
                ]),
                __('FAQS') => '#',
            ]" />

            <div class="section-body row">
                <div class="card">
                    <div class="card-body">
                        <div class="lang_list_top">
                            <ul class="lang_list">
                                @foreach ($languages as $language)
                                    <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                            href="{{ route('admin.faq.by.service', ['slug' => $service->slug, 'code' => $language->code]) }}"><i
                                                class="fas {{ request('code') == $language->code ? 'fa-eye' : 'fa-edit' }}"></i>
                                            {{ $language->name }}</a></li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="mt-2 alert alert-danger" role="alert">
                            @php
                                $current_language = $languages->where('code', request()->get('code'))->first();
                            @endphp
                            <p>{{ __('Your editing mode') }} :
                                <b>{{ $current_language?->name }}</b>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            @include('service::utilities.navbar')
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#create_faq"
                                        class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add New') }}</a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($faqs as $faq)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $faq->getTranslation($code)->question }}</td>
                                                    <td>{{ $faq->getTranslation($code)->answer }}</td>
                                                    <td>
                                                        <input onchange="changeStatus({{ $faq->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $faq->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;" data-bs-toggle="modal"
                                                            data-bs-target="#edit_faq_id_{{ $faq->id }}"
                                                            class="btn btn-warning btn-sm"><i class="fa fa-edit"
                                                                aria-hidden="true"></i></a>

                                                        <x-admin.delete-button :id="$faq->id" onclick="deleteData" />
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('FAQ')" route="admin.faq.create" create="no"
                                                    :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $faqs->onEachSide(3)->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <x-admin.delete-modal />
    <!-- Modal -->
    <div class="modal fade" id="create_faq" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Create FAQ') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="container-fluid">
                        <form action="{{ route('admin.service-faq.store') }}" method="POST">
                            @csrf
                            <x-admin.form-input type="hidden" id="service_id" name="service_id"
                                value="{{ $service->id }}" />
                            <div class="form-group">
                                <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                    placeholder="{{ __('Enter Question') }}" value="{{ old('question') }}"
                                    required="true" />
                            </div>

                            <div class="form-group">
                                <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                    placeholder="{{ __('Enter Answer') }}" value="{{ old('answer') }}" maxlength="1000" />
                            </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                    <x-admin.button type="submit" text="{{ __('Save') }}" />
                </div>
                </form>
            </div>
        </div>
    </div>
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
                            <form action="{{ route('admin.service-faq.update', ['service_faq' => $faq->id, 'code' => $code]) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <x-admin.form-input type="hidden" id="service_id" name="service_id"
                                    value="{{ $faq->service_id }}" />
                                <div class="form-group">
                                    <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                        placeholder="{{ __('Enter Question') }}"
                                        value="{{ $faq->getTranslation($code)->question }}" required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                        placeholder="{{ __('Enter Answer') }}"
                                        value="{{ $faq->getTranslation($code)->answer }}" maxlength="1000" />
                                </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Update') }}" />
                    </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endsection

@push('js')
    <script>
        "use strict";

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/service-faq/') }}" + "/" + id)
        }

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
                url: "{{ url('/admin/service-faq/status-update') }}" + "/" + id,
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
            })
        }
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
