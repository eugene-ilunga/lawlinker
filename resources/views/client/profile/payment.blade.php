@extends('layouts.client.layout')
@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('title')
    <title>{{ __('Payment') }}</title>
@endsection
@section('client-content')


    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Payment') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Payment') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Check Out Start-->
    <div class="check-out pt_40 pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="checkout-table table-responsive">
                        <h4>{{ __('Appointment List') }}</h4>
                        <div class="table_border">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th><b>{{ __('Lawyer') }}</b></th>
                                        <th><b>{{ __('Department') }}</b></th>
                                        <th><b>{{ __('Location') }}</b></th>
                                        <th><b>{{ __('Date') }}</b></th>
                                        <th><b>{{ __('Schedule') }}</b></th>
                                        <th><b>{{ __('Fee') }} ({{ getSessionCurrency() }})</b></th>
                                        <th><b>{{ __('Action') }}</b></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($appointments as $i => $item)
                                        @php
                                            $department = Modules\Lawyer\app\Models\Department::select('id')
                                                ->with([
                                                    'translation' => function ($query) {
                                                        $query->select('department_id', 'name');
                                                    },
                                                ])
                                                ->where('id', $item?->options?->department_id)
                                                ->first();

                                            $location = Modules\Lawyer\app\Models\Location::select('id')
                                                ->with([
                                                    'translation' => function ($query) {
                                                        $query->select('location_id', 'name');
                                                    },
                                                ])
                                                ->where('id', $item?->options?->location_id)
                                                ->first();
                                        @endphp
                                        <tr>
                                            <td>{{ ucfirst($item?->name) }}</td>
                                            <td>{{ $department?->name }}</td>
                                            <td>{{ $location?->name }}</td>
                                            <td>{{ $item?->options?->date }}</td>
                                            <td>{{ strtoupper($item?->options?->time) }}</td>
                                            <td>{{ currency($item?->price) }}</td>
                                            <td><a href="{{ url('remove-appointment/' . $item?->rowId) }}"
                                                    class="btn btn-danger btn-sm"><i class="fas fa-times"></i></a></td>
                                        </tr>
                                    @endforeach


                                    <tr>
                                        <td class="text-right" colspan="5"><b>{{ __('Total') }}</b></td>
                                        <td class="" colspan="2"><b>{{ currency($payable_amount) }}</b></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @if ($appointments->count() != 0)
                <div class="preloader d-none" id="preloader-two">
                    <div class="status" style="background-image: url({{ url($setting->preloader_image) }})"></div>
                </div>
                <div class="row mt-5">
                    <div class="col-md-8">
                        <div id="show_currency_notifications">
                            <div class="alert alert-warning d-none"></div>
                        </div>
                        <div class="payment-select">
                            <h4>{{ __('Pay Now') }}</h4>
                            <div class=" d-flex flex-wrap gap-3">
                                @foreach ($activeGateways as $gatewayKey => $gatewayDetails)
                                    <a class="place-order-btn" data-method="{{ $gatewayKey }}">
                                        <img class="shadow p-2 payment-logo" src="{{ asset($gatewayDetails['logo']) }}"
                                            alt="{{ $gatewayDetails['name'] }}">
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @if ($payable_amount > 0)
                        <div class="col-md-4">
                            <div class="payment-select">
                                <h4>{{ __('Payable With Charge') }}</h4>
                                <div class="card shadow border-0">
                                    <div class="card-body">
                                        @php
                                            $currency = getSessionCurrency();
                                        @endphp

                                        @foreach ($activeGateways as $gatewayKey => $gatewayDetails)
                                            @if ($paymentService->isCurrencySupported($gatewayKey))
                                                @php
                                                    $payableDetails = $paymentService->getPayableAmount(
                                                        $gatewayKey,
                                                        $payable_amount,
                                                    );
                                                @endphp

                                                <p class="mb-1 d-flex justify-content-between">
                                                    <strong>{{ $gatewayDetails['name'] }}:</strong> 
                                                    <span>{{ $payableDetails->payable_with_charge }}
                                                        {{ $currency }}</span>
                                                </p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
        </div>
        @endif
    </div>
    </div>
    <!--Check Out End-->

@endsection
@push('js')
    <script>
        var base_url = "{{ url('/') }}";
        var basic_error_message = "{{ __('Something went wrong') }}";
    </script>
    <script src="{{ asset('client/js/place.order.js') }}"></script>
@endpush
