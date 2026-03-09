@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Order List') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Order List') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Order List') }}</span></li>
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
                        <h2 class="d-headline">{{ __('Order List') }}</h2>
                        <div class="table_border">
                            <div class="table-responsive">
                                <table class="coustom-dashboard dashboard-table display table-striped" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th width="5%">{{ __('Serial') }}</th>
                                            <th width="5%">{{ __('Order ID') }}</th>
                                            <th width="20%">{{ __('Fee') }}</th>
                                            <th width="5%">{{ __('Total Appointment') }}</th>
                                            <th width="15%">{{ __('Date') }}</th>
                                            <th width="15%">{{ __('Payment') }}</th>
                                            <th width="15%">{{ __('Action') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $index => $item)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $item?->order_id }}</td>
                                                <td>{{ specific_currency_with_icon($item?->payable_currency, $item?->total_payment) }}
                                                </td>
                                                <td>{{ $item?->appointment_qty }}</td>
                                                <td>{{ formattedDate($item?->created_at) }}</td>
                                                <td>
                                                    @if ($item?->payment_status == 0)
                                                        <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ __('Success') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a data-bs-toggle="modal"
                                                        data-bs-target="#orderDetails-{{ $item?->id }}"
                                                        class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    @if (is_null($item?->payment_transaction_id))
                                                        <a target="_blank" href="{{route('payment',['order_id'=>$item?->order_id])}}" class="btn btn-info btn-sm"><i class="fas fa-credit-card"></i></a>
                                                    @endif
                                                    
                                                </td>
                                            </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $orders?->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->

    @foreach ($orders as $item)
        <!-- Modal -->
        <div class="order_modal">
            <div class="modal fade" id="orderDetails-{{ $item?->id }}" tabindex="-1" role="dialog"
                aria-labelledby="modelTitleId" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <h4>{{ __('Order Information') }}:</h4>

                                <div class="table_border">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Serial') }}</th>
                                                    <th>{{ __('Fee') }}</th>
                                                    <th>{{ __('Payment Method') }}</th>
                                                    <th>{{ __('Transaction ID') }}</th>
                                                    <th>{{ __('Payment') }}</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>{{ $item?->order_id }}</td>
                                                    <td>{{ specific_currency_with_icon($item?->payable_currency, $item?->total_payment) }}</td>
                                                <td>{{ $item?->payment_method }}</td>
                                                    <td>{{ $item?->payment_transaction_id }}</td>
                                                    <td>
                                                        @if ($item?->payment_status == 0)
                                                            <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge bg-success">{{ __('Success') }}</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <h4 class="mt-4">{{ __('Appointment History') }}:</h4>
                                <div class="table_border">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Serial') }}</th>
                                                    <th>{{ __('Lawyer') }}</th>
                                                    <th>{{ __('Phone') }}</th>
                                                    <th>{{ __('Schedule') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Fee') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($item?->appointments as $index => $item)
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ ucfirst($item?->lawyer?->name) }}</td>
                                                        <td>{{ $item?->lawyer?->phone }}</td>
                                                        <td>{{ strtoupper($item?->schedule?->start_time) . '-' . strtoupper($item?->schedule?->end_time) }}
                                                        </td>
                                                        <td>{{ formattedDate($item?->date) }}</td>
                                                        <td>{{ specific_currency_with_icon($item?->payable_currency, $item->appointment_fee) }}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-danger btn-sm mt-3"
                                    data-bs-dismiss="modal">{{ __('Close') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endsection
