@extends('admin.master_layout')
@section('title')
    <title>{{ __('Invoice Contact') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Invoice Contact') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Invoice Contact') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.update-prescription-contact') }}" method="post">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <x-admin.form-input data-translate="true" id="prescription_email"
                                            name="prescription_email" label="{{ __('Email') }}"
                                            placeholder="{{ __('Enter Email') }}"
                                            value="{{ old('prescription_email', $setting?->prescription_email) }}"
                                            required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-input data-translate="true" id="prescription_phone"
                                            name="prescription_phone" label="{{ __('Phone') }}"
                                            placeholder="{{ __('Enter Phone') }}"
                                            value="{{ old('prescription_phone', $setting?->prescription_phone) }}"
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
