@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Zoom Setting') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Zoom Setting') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Zoom Setting') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('lawyer.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('lawyer.zoom-credential.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <x-admin.form-input id="zoom_account_id" name="zoom_account_id" label="{{ __('Account ID') }}"
                                            placeholder="{{ __('Enter Account ID') }}" value="{{ $credential?->zoom_account_id }}"
                                            required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-input id="zoom_api_key" name="zoom_api_key" label="{{ __('Client ID') }}"
                                            placeholder="{{ __('Enter Client ID') }}" value="{{ $credential?->zoom_api_key }}"
                                            required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-input id="zoom_api_secret" name="zoom_api_secret" label="{{ __('Client Secret') }}"
                                            placeholder="{{ __('Enter Client Secret') }}" value="{{ $credential?->zoom_api_secret }}"
                                            required="true" />
                                    </div>
                                    <x-admin.update-button :text="__('Update')" />
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection