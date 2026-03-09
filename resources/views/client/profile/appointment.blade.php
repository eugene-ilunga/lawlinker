@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Appointment List') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Appointment List') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Appointment List') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Dashboard Start-->
    <div class="dashboard-area pt_70 pb_70">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    @include('client.profile.sidebar')
                </div>
                <div class="col-lg-9">
                    <div class="detail-dashboard">
                        <h2 class="d-headline">{{ __('Appointment List') }}</h2>
                        <div class="table_border">
                            <div class="table-responsive">
                                <table class="coustom-dashboard dashboard-table display table-striped" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="3%">{{ __('Serial') }}</th>
                                            <th width="15%">{{ __('Lawyer') }}</th>
                                            <th width="15%">{{ __('Date') }}</th>
                                            <th width="20%">{{ __('Fee') }}</th>
                                            <th width="20%">{{ __('Schedule') }}</th>
                                            <th width="10%">{{ __('Payment') }}</th>
                                            <th width="5%">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($appointments as $index => $appointment)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ ucfirst($appointment?->lawyer?->name) }}</td>
                                                <td>{{ formattedDate($appointment?->date) }}</td>
                                                <td>{{ specific_currency_with_icon($appointment?->payable_currency, $appointment->appointment_fee) }}
                                                </td>
                                                <td>{{ strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time) }}
                                                </td>
                                                <td>
                                                    @if ($appointment->order->payment_status == 0)
                                                        <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ __('Success') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($appointment?->already_treated == 1)
                                                        <a class="db-bt-ed"
                                                            href="{{ route('client.show.appointment', $appointment->id) }}"><i
                                                                class="fas fa-eye    "></i></a>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $appointments?->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->
@endsection
