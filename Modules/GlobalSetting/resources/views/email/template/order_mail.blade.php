@extends('admin.master_layout')
@section('title')
    <title>{{ __('Edit Template') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Edit Template') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Email Configuration') => route('admin.email-configuration'),
                __('Edit Template') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.email-configuration')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                        <th>{{ __('Variable') }}</th>
                                        <th>{{ __('Meaning') }}</th>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            @php
                                                $client_name = '{{ client_name }}';
                                            @endphp
                                            <td>{{ $client_name }}</td>
                                            <td>{{ __('Client Name') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $orderId = '{{ orderId }}';
                                            @endphp
                                            <td>{{ $orderId }}</td>
                                            <td>{{ __('Order ID') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $payment_method = '{{ payment_method }}';
                                            @endphp
                                            <td>{{ $payment_method }}</td>
                                            <td>{{ __('Payment Method') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $amount = '{{ amount }}';
                                            @endphp
                                            <td>{{ $amount }}</td>
                                            <td>{{ __('Total Payment') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $payment_status = '{{ payment_status }}';
                                            @endphp
                                            <td>{{ $payment_status }}</td>
                                            <td>{{ __('Payment Status') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $status = '{{ status }}';
                                            @endphp
                                            <td>{{ $status }}</td>
                                            <td>{{ __('Status') }}</td>
                                        </tr>
                                        <tr>
                                            @php
                                                $order_details = '{{ order_details }}';
                                            @endphp
                                            <td>{{ $order_details }}</td>
                                            <td>{{ __('Order Details') }}</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('admin.update-email-template', $template->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <x-admin.form-input id="subject" name="subject" label="{{ __('Subject') }}"
                                            value="{{ $template->subject }}" required="true" />
                                    </div>
                                    <div class="form-group">
                                        <x-admin.form-editor id="message" name="message" label="{{ __('Message') }}"
                                            value="{!! $template->message !!}" required="true" />
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
