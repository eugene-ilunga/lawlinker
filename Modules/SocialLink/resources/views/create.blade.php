@extends('admin.master_layout')
@section('title')
    <title>{{ __('Add Social Link') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Add Social Link') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Social Links') => route('admin.social-link.index'),
                __('Add Social Link') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.social-link.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('admin.social-link.store') }}" method="post">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12">
                                            <x-admin.form-input class="icon custom-icon-picker" id="icon"
                                                name="icon" autocomplete="off" label="{{ __('Icon') }}"
                                                placeholder="{{ __('Enter Icon') }}" required="true"
                                                value="{{ old('icon') }}" />
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <x-admin.form-input id="link" name="link"
                                                    label="{{ __('Link') }}" placeholder="{{ __('Enter link') }}"
                                                    value="{{ old('link') }}" required="true" />
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <x-admin.save-button :text="__('Save')" />
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