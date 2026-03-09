@extends('admin.master_layout')
@section('title')
    <title>{{ __('Appointment Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Appointment Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Appointment Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Client Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table table-striped">

                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td><a
                                                    href="{{ route('admin.customer-show', $appointment->user_id) }}">{{ $appointment?->user?->name }}</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $appointment?->user?->email }}</td>
                                        </tr>
                                        @if ($appointment?->user?->details?->phone)
                                            <tr>
                                                <td>{{ __('Phone') }}</td>
                                                <td>{{ $appointment?->user?->details?->phone }}</td>
                                            </tr>
                                        @endif


                                        <tr>
                                            <td>{{ __('Age') }}</td>
                                            <td>{{ $appointment?->user?->details?->age }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Gender') }}</td>
                                            <td>{{ $appointment?->user?->details?->gender }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Appointment Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Lawyer Name') }}</td>
                                            <td>{{ $appointment?->lawyer?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Designation') }}</td>
                                            <td>{{ $appointment?->lawyer?->designations }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Date') }}</td>
                                            <td>{{ formattedDate($appointment->date) }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Schedule') }}</td>
                                            <td>{{ strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Already Consulted') }}</td>
                                            <td>
                                                @if ($appointment->already_treated == 0)
                                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                                @else
                                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Billing Information')" />
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>{{ __('Order ID') }}</th>
                                            <th>{{ __('Fee') }}</th>
                                            <th>{{ __('Payment Method') }}</th>
                                            <th>{{ __('Transaction') }}</th>
                                            <th>{{ __('Payment Status') }}</th>
                                        </tr>
                                        <tr>
                                            <td>{{ $appointment?->order->order_id }}</td>
                                            <td>{{ specific_currency_with_icon($appointment?->order?->payable_currency, $appointment?->order->total_payment) }}
                                            </td>
                                            <td>{{ $appointment?->order->payment_method }}</td>
                                            <td>{{ $appointment?->order->payment_transaction_id }}</td>
                                            <td>
                                                @if ($appointment?->order->payment_status == 1)
                                                    <div class="badge bg-success">{{ __('Success') }}</div>
                                                @elseif ($appointment?->order->payment_status == 'rejected')
                                                    <div class="badge bg-danger">{{ __('Rejected') }}</div>
                                                @else
                                                    <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
