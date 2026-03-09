@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Change Password') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Change Password') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Change Password') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card ">
                            <div class="card-body">
                                <form action="{{ route('lawyer.update-password') }}" enctype="multipart/form-data"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="current_password"
                                                name="current_password" label="{{ __('Current Password') }}"
                                                required="true" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="password" name="password"
                                                label="{{ __('New Password') }}" required="true" />
                                        </div>

                                        <div class="form-group col-12">
                                            <x-admin.form-input type="password" id="password_confirmation"
                                                name="password_confirmation" label="{{ __('Confirm Password') }}"
                                                required="true" />
                                        </div>

                                    </div>
                                    <div class="row">
                                        <div class="col-12">
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
