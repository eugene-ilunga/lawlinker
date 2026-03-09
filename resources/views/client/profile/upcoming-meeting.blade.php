@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Upcoming Meeting') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Upcoming Meeting') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Upcoming Meeting') }}</span>
                            </li>
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
                        <h2 class="d-headline">
                            {{ __('Upcoming Meeting') }}</h2>
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
                                            <th>{{ __('Join Link') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($histories as $index => $meeting)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $meeting->lawyer->name }}</td>
                                                <td>{{ staticFormattedDateTime($meeting->meeting_time) }}</td>
                                                <td>{{ $meeting->duration }} {{ __('Minutes') }}</td>
                                                <td>{{ $meeting?->meeting?->meeting_id }}</td>
                                                <td>
                                                    @if (strtolower(config('app.app_mode')) == 'demo')
                                                        <a id="zoom_demo_mode" href="javascript:;"
                                                            class="btn btn-success btn-sm"><i class="fas fa-video"></i></a>
                                                    @else
                                                        <a target="_blank" href="{{ $meeting?->meeting->join_url }}"
                                                            class="btn btn-primary btn-sm"><i class="fas fa-video"></i></a>
                                                    @endif
                                                </td>
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
