@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQ List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('FAQ') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQS') => '#',
            ]" />
            <div class="section-body row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="lang_list_top">
                                <ul class="lang_list">
                                    @foreach ($languages as $language)
                                        <li><a id="{{ request('code') == $language->code ? 'selected-language' : '' }}"
                                                href="{{ route('admin.faq.by.category', ['slug' => $category->slug, 'code' => $language->code]) }}"><i
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
            </div>

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.back-button variant="info" :href="route('admin.faq-category.index')" text="{{ __('FAQ Categories') }}" />
                                @adminCan('faq.store')
                                    <div>
                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#create_faq"
                                            class="btn btn-primary"><i class="fas fa-plus"></i> {{ __('Add New') }}</a>
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Question') }}</th>
                                                <th>{{ __('Answer') }}</th>
                                                @adminCan('faq.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('faq.update') || checkAdminHasPermission('faq.delete'))
                                                    <th width="15%">{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($faqs as $faq)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $faq->getTranslation($code)->question }}</td>
                                                    <td>{{ $faq->getTranslation($code)->answer }}</td>
                                                    @adminCan('faq.update')
                                                        <td>
                                                            <input onchange="changeStatus({{ $faq->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $faq->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('faq.update') || checkAdminHasPermission('faq.delete'))
                                                        <td>
                                                            @adminCan('faq.update')
                                                                <a href="javascript:;" data-bs-toggle="modal"
                                                                    data-bs-target="#edit_faq_id_{{ $faq->id }}"
                                                                    class="btn btn-warning btn-sm"><i class="fa fa-edit"
                                                                        aria-hidden="true"></i></a>
                                                            @endadminCan
                                                            @adminCan('faq.delete')
                                                                <x-admin.delete-button :id="$faq->id" onclick="deleteData" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
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
    @adminCan('faq.delete')
        <x-admin.delete-modal />
    @endadminCan
    <!-- Modal -->
    @adminCan('faq.store')
        <div class="modal fade" id="create_faq" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Create FAQ') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="container-fluid">
                            <form action="{{ route('admin.faq.store') }}" method="POST">
                                @csrf
                                <x-admin.form-input type="hidden" id="faq_category_id" name="faq_category_id"
                                    value="{{ $category->id }}" />
                                <div class="form-group">
                                    <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                        placeholder="{{ __('Enter Question') }}" value="{{ old('question') }}"
                                        required="true" />
                                </div>

                                <div class="form-group">
                                    <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                        placeholder="{{ __('Enter Answer') }}" value="{{ old('answer') }}" maxlength="1000"
                                        required="true" />
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
    @endadminCan

    @adminCan('faq.update')
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
                                <form action="{{ route('admin.faq.update', ['faq' => $faq->id, 'code' => $code]) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <x-admin.form-input type="hidden" id="faq_category_id" name="faq_category_id"
                                        value="{{ $faq->faq_category_id }}" />
                                    <div class="form-group">
                                        <x-admin.form-input id="question" name="question" label="{{ __('Question') }}"
                                            placeholder="{{ __('Enter Question') }}"
                                            value="{{ $faq->getTranslation($code)->question }}" required="true" />
                                    </div>

                                    <div class="form-group">
                                        <x-admin.form-textarea id="answer" name="answer" label="{{ __('Answer') }}"
                                            placeholder="{{ __('Enter Answer') }}"
                                            value="{{ $faq->getTranslation($code)->answer }}" maxlength="1000"
                                            required="true" />
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
    @endadminCan
@endsection

@push('js')
    <script>
        "use strict";
        @adminCan('faq.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/faq/') }}" + "/" + id)
        }
        @endadminCan

        @adminCan('faq.update')

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
                url: "{{ url('/admin/faq/status-update') }}" + "/" + id,
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
        @endadminCan
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
