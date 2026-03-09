@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Create Withdraw') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Create Withdraw') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('My Withdraw') => route('lawyer.withdraw.index'),
                __('Create Withdraw') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <!-- Today Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Current Balance') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($current_balance ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- New Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Withdraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($total_withdraw ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- New Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ currency($total_earning ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 row">
                    <div class="col-md-6 order-2 order-md-1">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('lawyer.withdraw.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('lawyer.withdraw.store') }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="form-group">
                                            <x-admin.form-select name="withdraw_method_id" id="withdraw_method_id"
                                                class="select2" label="{{ __('Method') }}"  required="true">
                                                <x-admin.select-option value="" text="{{ __('Select Method') }}" />
                                                @foreach ($methods as $method)
                                                    <x-admin.select-option :selected="$method?->id == old('withdraw_method_id')" value="{{ $method?->id }}"
                                                        text="{{ $method?->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="form-group">
                                            <x-admin.form-input id="amount" name="amount" type="number"
                                                label="{{ __('Withdraw Amount') }}"
                                                placeholder="{{ __('Enter Withdraw Amount') }}" value="{{ old('amount') }}"
                                                required="true" autocomplete="off" />
                                        </div>
                                        <div class="form-group">
                                            <x-admin.form-editor id="account_info" name="account_info" label="{{ __('Account Info') }}" value="{!! old('account_info') !!}" required="true"/>
                                        </div>
                                        <div class="col-12">
                                            <x-admin.button id="submitBtn" type="submit" :text="__('Submit')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 d-none order-1 order-md-2" id="method_des_box">
                        <div class="card shadow mb-4">
                            <div class="card-body" id="method_des"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        (function($) {
    "use strict";
    $(document).ready(function() {
        $("#withdraw_method_id").on('change', function() {
            var methodId = $(this).val();
            var submitBtn = $('#submitBtn');
            submitBtn.attr('disabled', true);
            if (!methodId) {
                $("#method_des_box").addClass('d-none');
                submitBtn.attr('disabled', false);
                return;
            }
            $.ajax({
                type: "get",
                url: "{{ url('/lawyer/get-withdraw-account-info/') }}" + "/" + methodId,
                success: function(response) {
                    $("#method_des").html(response);
                    $("#method_des_box").removeClass('d-none');
                },
                error: function() {},
                complete: function() {submitBtn.attr('disabled', false)}
            });
        });
    });
})(jQuery);

    </script>
@endpush
