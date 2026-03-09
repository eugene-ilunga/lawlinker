@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Dashboard') }}" />

            <div class="section-body">
                <div class="row">
                        <!-- Today Appointment -->
                        <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                            <div class="card card-statistic-1">
                                <div class="card-icon bg-info">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="card-wrap">
                                    <div class="card-header">
                                        <h4>{{ __('Today Appointment') }}</h4>
                                    </div>
                                    <div class="card-body">
                                        {{ count($today_appointment)}}
                                    </div>
                                </div>
                            </div>
                        </div>
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
                                        {{ $new_appointment ?? 0 }}
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
                                        {{ $success_appointment ?? 0 }}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Earnings (Monthly) Card Example -->
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
                </div>
                @use('Carbon\Carbon', 'Carbon')
                    <div class="row">
                        <!-- Area Chart -->
                        <div class="col">
                            <div class="mb-4 shadow card">
                                <!-- Card Header - Dropdown -->
                                <div class="flex-row py-3 card-header d-flex align-items-center justify-content-between">
                                    <h6 class="m-0 font-weight-bold text-primary"> {{ __('Earnings In') }}
                                        {{ request()->has('year') && request()->has('month') ? Carbon::createFromFormat('Y-m', request('year') . '-' . request('month'))->format('F, Y') : date('F, Y') }}
                                    </h6>
                                    <div class="form-inline">
                                        <form method="get" onchange="$(this).trigger('submit');">
                                            <select name="year" id="year" class="form-control">
                                                @php
                                                    $currentYear = Carbon::now()->year;
                                                    $currentMonth = Carbon::now()->month;
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
                                                        $monthName = Carbon::createFromFormat('m', $month)->format('M');
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


                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>{{ __('Today Appointment') }}</h4>
                            </div>
                            <div class="p-0 card-body">
                                <div class="table-responsive">
                                    <table class="table mb-0 table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('Schedule') }}</th>
                                                <th>{{ __('Payment') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($today_appointment as $index => $appointment)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{$appointment?->user?->email}}</td>
                                                    <td>{{formattedDate($appointment?->date)}}</td>
                                                    <td>{{$appointment?->user?->details?->phone}}</td>
                                                    <td>{{strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time)}}</td>
                                                    <td>
                                                        @if ($appointment->payment_status == 0)
                                                            <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                        @else
                                                            <span class="badge bg-success">{{ __('Success') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a  href="{{ route('lawyer.treatment',$appointment->id) }}" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                            <x-empty-table :name="__('Dashboard')" route="lawyer.dashboard"
                                            create="no" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
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
@push('js')
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
@endpush
