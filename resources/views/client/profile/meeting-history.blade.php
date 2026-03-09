@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Meeting History') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Meeting History') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Meeting History') }}</span></li>
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
                        <h2 class="d-headline">{{ __('Meeting History') }}</h2>
                        <div class="table_border">
                            <div class="table-responsive">
                                <table class="coustom-dashboard dashboard-table display table-striped" width="100%"
                                    cellspacing="0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Lawyer') }}</th>
                                            <th>{{ __('Time') }}</th>
                                            <th>{{ __('Duration') }}</th>
                                            <th>{{ __('Meeting ID') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $index => $meeting)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $meeting->lawyer->name }}</td>
                                                <td>{{ staticFormattedDateTime($meeting->meeting_time) }}</td>
                                                <td>{{ $meeting->duration }} {{ __('Minutes') }}</td>
                                                <td>{{ $meeting->meeting->meeting_id }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        {{ $histories->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->
@endsection
