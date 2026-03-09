@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Schedule List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Schedule List') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Schedule List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('lawyer.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Week Day') }}</th>
                                                <th>{{ __('Schedule') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($days as $index => $day)
                                                @php
                                                    $times = $schedules->where('day_id', $day?->id);
                                                @endphp
                                                @if ($times->isNotEmpty())
                                                    <tr>
                                                        <td>{{ ++$index }}</td>
                                                        <td>{{ $day?->title }}</td>
                                                        <td>
                                                            @foreach ($times as $time)
                                                                {{ strtoupper($time?->start_time) }} -
                                                                {{ strtoupper($time?->end_time) }},
                                                                <br>
                                                            @endforeach
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
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
