@extends('admin.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        @if (checkAdminHasPermission('dashboard.view'))
            {{-- Show Credentials Setup Alert --}}
            <div class="row position-relative">
                @if (Route::is('admin.dashboard') && ($checkCredentials = checkCredentials()))
                    @foreach ($checkCredentials as $checkCredential)
                        @if ($checkCredential->status)
                            <div
                                class="alert alert-danger alert-has-icon alert-dismissible position-absolute w-100 missingCrentialsAlert">
                                <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
                                <div class="alert-body">
                                    <div class="alert-title">{{ $checkCredential->message }}</div>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>

                                    {{ $checkCredential->description }} <b><a class="btn btn-sm btn-outline-warning"
                                            href="{{ !empty($checkCredential->route) ? route($checkCredential->route) : url($checkCredential->url) }}">{{ __('Update') }}</a></b>
                                </div>
                            </div>
                        @endif
                    @endforeach
                @endif
            </div>    

            @if ($setting->is_queable == 'active' && Cache::get('corn_working') !== 'working')
                <div class="alert alert-danger alert-has-icon alert-dismissible show fade">
                    <div class="alert-icon"><i class="fas fa-sync"></i></div>
                    <div class="alert-body">
                        <div class="alert-title"><a href="{{ route('admin.email-configuration') }}" target="_blank"
                                rel="noopener noreferrer">{{ __('Corn Job Is Not Running! Many features will be disabled and face errors') }}</a>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
            @endif
        @endif
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Dashboard') }}" />
            @if (checkAdminHasPermission('dashboard.view'))
                <div class="section-body">
                    <div class="row">
                        <!-- New Appointment -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-primary">
                                    <i class="fas fa-calendar"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('New Appointment') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $new_appointment_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Pending Orders -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-warning">
                                    <i class="fas fa-shopping-bag"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Pending Orders') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $pending_orders_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Success Appointment -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Successful Appointment') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $success_appointment_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Client -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-danger">
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Client') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $client_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Total Lawyer  -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-gavel"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Lawyer') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $lawyer_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings Monthly -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Earnings') }} ({{ __('Monthly') }})</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ currency($monthlyEarning) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings Total -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-success">
                                    <i class="fas fa-dollar-sign"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Earnings') }} ({{ __('Total') }})</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ currency($totalEarning) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Earnings Subscriber -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-dark">
                                    <i class="fas fa-bullhorn"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Total Subscriber') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ $subscriber_qty ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Pending Orders') }}</h4>
                                </div>
                                <div class="p-0 card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SN') }}</th>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Email') }}</th>
                                                    <th>{{ __('Phone') }}</th>
                                                    <th>{{ __('Date') }}</th>
                                                    <th>{{ __('Payment') }}</th>
                                                    <th>{{ __('Action') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($pending_orders as $index => $pending_order)
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $pending_order?->user?->name }}</td>
                                                        <td>{{ $pending_order?->user?->email }}</td>
                                                        <td>{{ $pending_order?->user?->details?->phone }}</td>
                                                        <td>{{ formattedDate($pending_order?->created_at) }}</td>
                                                        </td>
                                                        <td>
                                                            @if ($pending_order->payment_status == 0)
                                                                <span
                                                                    class="badge bg-danger">{{ __('Pending') }}</span>
                                                            @else
                                                                <span
                                                                    class="badge bg-success">{{ __('Success') }}</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.order', $pending_order?->order_id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fas fa-eye"></i></a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <x-empty-table :name="__('')" route="" create="no"
                                                        :message="__('No data found!')" colspan="7" />
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4>{{ __('Lawyer Payment In') }} {{ date('F, Y') }}</h4>
                                </div>
                                <div class="p-0 card-body">
                                    <div class="table-responsive">
                                        <table class="table mb-0 table-striped">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('SN') }}</th>
                                                    <th>{{ __('Name') }}</th>
                                                    <th>{{ __('Email') }}</th>
                                                    <th>{{ __('Phone') }}</th>
                                                    <th>{{ __('Amount') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse ($payment_histories as $index => $payment)
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $payment?->lawyer?->name }}</td>
                                                        <td>{{ $payment?->lawyer?->email }}</td>
                                                        <td>{{ $payment?->lawyer?->phone }}</td>
                                                        <td>{{ currency($payment?->total_payment_fee) }}</td>
                                                    </tr>
                                                @empty
                                                    <x-empty-table :name="__('')" route="" create="no"
                                                        :message="__('No data found!')" colspan="5" />
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                    @if (request()->get('par-page') !== 'all')
                                        <div class="float-right">
                                            {{ $payment_histories->onEachSide(0)->links() }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col">
                            <div class="mb-4 shadow card">
                                <!-- Card Header - Dropdown -->
                                <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"> {{ __('Earnings In') }}
                                        {{ request()->has('year') && request()->has('month') ? Carbon\Carbon::createFromFormat('Y-m', request('year') . '-' . request('month'))->format('F, Y') : date('F, Y') }}
                                    </h6>
                                    <div class="form-inline">
                                        <form method="get" onchange="$(this).trigger('submit');">
                                            <select name="year" id="year" class="form-control">
                                                @php
                                                    $currentYear = Carbon\Carbon::now()->year;
                                                    $currentMonth = Carbon\Carbon::now()->month;
                                                    $selectYear = request('year') ?? $currentYear;
                                                    $selectMonth = request('month') ?? $currentMonth;
                                                @endphp
                                                @for ($i = $oldestYear; $i <= $latestYear; $i++)
                                                    <option value="{{ $i }}" @selected($selectYear == $i)>
                                                        {{ $i }}</option>
                                                @endfor
                                            </select>
                                            <select name="month" id="month" class="form-control">
                                                @php
                                                    for ($month = 1; $month <= 12; $month++) {
                                                        $monthNumber = str_pad($month, 2, '0', STR_PAD_LEFT);
                                                        $monthName = Carbon\Carbon::createFromFormat(
                                                            'm',
                                                            $month,
                                                        )->format('M');
                                                        echo '<option value="' .
                                                            $monthNumber .
                                                            '" ' .
                                                            ($selectMonth == $monthNumber ? 'selected' : '') .
                                                            '>' .
                                                            $monthName .
                                                            '</option>';
                                                    }
                                                @endphp
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <!-- Card Body -->
                                <div class="card-body">
                                    <div class="chart-area">
                                        <canvas id="myAreaChart"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </section>
    </div>
@endsection

@push('js')
    @if (checkAdminHasPermission('dashboard.view'))
        <script src="{{ asset('backend/chart.js/chart.umd.min.js') }}"></script>
        <script>
            (function($) {
                "use strict";
                // Area Chart Example
                $(document).ready(function() {

                    var bData = @json($monthly_data);
                    var jData = JSON.parse(bData);

                    var ctx = document.getElementById("myAreaChart");
                    var myLineChart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12", "13",
                                "14", "15", "16", "17", "18", "19", "20", "21", "22", "23", "24", "25",
                                "26", "27", "28", "29", "30", "31"
                            ],
                            datasets: [{
                                label: "{{ __('Earnings') }}",
                                lineTension: 0.3,
                                backgroundColor: "rgba(78, 115, 223, 0.05)",
                                borderColor: "rgba(78, 115, 223, 1)",
                                pointRadius: 3,
                                pointBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointBorderColor: "rgba(78, 115, 223, 1)",
                                pointHoverRadius: 3,
                                pointHoverBackgroundColor: "rgba(78, 115, 223, 1)",
                                pointHoverBorderColor: "rgba(78, 115, 223, 1)",
                                pointHitRadius: 10,
                                pointBorderWidth: 2,
                                data: jData,
                            }],
                        },
                        options: {
                            maintainAspectRatio: false,
                            layout: {
                                padding: {
                                    left: 10,
                                    right: 25,
                                    top: 25,
                                    bottom: 0
                                }
                            },
                            scales: {
                                xAxes: [{
                                    time: {
                                        unit: 'date'
                                    },
                                    gridLines: {
                                        display: false,
                                        drawBorder: false
                                    },
                                    ticks: {
                                        maxTicksLimit: 7
                                    }
                                }],
                                yAxes: [{
                                    ticks: {
                                        maxTicksLimit: 5,
                                        padding: 10,
                                        // Include a dollar sign in the ticks
                                        callback: function(value, index, values) {
                                            return '{{ session()->get('currency_icon') }}' +
                                                number_format(value);
                                        }
                                    },
                                    gridLines: {
                                        color: "rgb(234, 236, 244)",
                                        zeroLineColor: "rgb(234, 236, 244)",
                                        drawBorder: false,
                                        borderDash: [2],
                                        zeroLineBorderDash: [2]
                                    }
                                }],
                            },
                            legend: {
                                display: false
                            },
                            tooltips: {
                                backgroundColor: "rgb(255,255,255)",
                                bodyFontColor: "#858796",
                                titleMarginBottom: 10,
                                titleFontColor: '#6e707e',
                                titleFontSize: 14,
                                borderColor: '#dddfeb',
                                borderWidth: 1,
                                xPadding: 15,
                                yPadding: 15,
                                displayColors: false,
                                intersect: false,
                                mode: 'index',
                                caretPadding: 10,
                                callbacks: {
                                    label: function(tooltipItem, chart) {
                                        var datasetLabel = chart.datasets[tooltipItem.datasetIndex]
                                            .label || '';
                                        return datasetLabel +
                                            ': {{ session()->get('currency_icon') }}' +
                                            number_format(tooltipItem.yLabel);
                                    }
                                }
                            }
                        }
                    });
                });
            })(jQuery);
        </script>
    @endif
@endpush
