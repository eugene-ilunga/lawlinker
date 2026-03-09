@extends('layouts.client.layout')
@section('title')
    <title>{{ $lawyer?->seo_title ?? $lawyer?->name }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $lawyer?->seo_description }}">
    <meta property="og:title" content="{{ $lawyer?->seo_title }}" />
    <meta property="og:description" content="{{ $lawyer?->seo_description }}" />
    <meta property="og:image" content="{{ asset($lawyer?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ ucfirst($lawyer?->name) }} ({{ $lawyer?->designations }})</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a>
                            </li>
                            <li><span>{{ ucfirst($lawyer?->name) }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Team Detail Start-->
    <div class="team-detail-page pt_40 pb_70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="team-detail-photo">
                        <img src="{{ url($lawyer?->image ? $lawyer?->image : $setting?->default_avatar) }}"
                            alt="{{ $lawyer?->name }}" loading="lazy">
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="team-detail-text">
                        <h4>{{ $lawyer?->name }} </h4>
                        <span><b>{{ $lawyer?->department?->name }} ({{ $lawyer?->designations }})</b></span>
                        <p class="mt-0"><b>{{ __('Years of experience') }}: {{ $lawyer?->years_of_experience }}</b></p>
                        <h5 class="appointment-cost mb-0">{{ __('Fee') }}: {{ currency($lawyer?->fee) }}</h5>

                        {!! $lawyer?->about !!}
                        <ul>
                            @foreach ($lawyer?->socialMedia as $socialMedia)
                                <li><a target="_blank" aria-label="{{ $socialMedia?->link }}"
                                        href="{{ $socialMedia?->link }}"><i class="{{$socialMedia?->icon}}"></i></a>
                                </li>
                            @endforeach
                        </ul>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="team-exp-area bg-area pt_70 pb_70">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="team-headline">
                        <h2>{{ __('Lawyer Info') }}</h2>
                    </div>
                </div>
                <div class="col-md-12">
                    <!--Tab Start-->
                    <div class="event-detail-tab mt_20">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="active">
                                <a aria-label="{{ __('Working Hours') }}" aria-selected="true" data-bs-toggle="tab"
                                    class="active" href="#working_hour" data-bs-toggle="tab">{{ __('Working Hours') }}</a>
                            </li>
                            <li>
                                <a aria-label="{{ __('Address') }}" aria-selected="false" data-bs-toggle="tab"
                                    href="#address" data-bs-toggle="tab">{{ __('Address') }}</a>
                            </li>
                            <li>
                                <a aria-label="{{ __('Education') }}" aria-selected="false" data-bs-toggle="tab"
                                    href="#education" data-bs-toggle="tab">{{ __('Education') }}</a>
                            </li>
                            <li>
                                <a aria-label="{{ __('Experience') }}" aria-selected="false" data-bs-toggle="tab"
                                    href="#experience" data-bs-toggle="tab">{{ __('Experience') }}</a>
                            </li>
                            <li>
                                <a aria-label="{{ __('Qualification') }}" aria-selected="false" data-bs-toggle="tab"
                                    href="#qualification" data-bs-toggle="tab">{{ __('Qualification') }}</a>
                            </li>
                            <li>
                                <a aria-label="{{ __('Appointment') }}" aria-selected="false" data-bs-toggle="tab"
                                    href="#book_appointment" data-bs-toggle="tab">{{ __('Appointment') }}</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content event-detail-content">
                        <div id="working_hour" class="tab-pane fade show active">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="wh-table table-responsive">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('Week Day') }}</th>
                                                    <th>{{ __('Schedule') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($days as $index => $day)
                                                    @php
                                                        $times = $lawyer?->schedules->where('day_id', $day?->id);
                                                    @endphp

                                                    @if ($times->isNotEmpty())
                                                        <tr>
                                                            <td>{{ $day?->title }}</td>
                                                            <td>
                                                                @foreach ($times as $time)
                                                                    <div class="sch">
                                                                        {{ strtoupper($time?->start_time) }} -
                                                                        {{ strtoupper($time?->end_time) }}</div>
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
                        <div id="address" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $lawyer?->address ?? '<h3 class="text-danger">' . __('No data found!') . '</h3>' !!}
                                </div>
                            </div>
                        </div>
                        <div id="education" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $lawyer?->educations ?? '<h3 class="text-danger">' . __('No data found!') . '</h3>' !!}
                                </div>
                            </div>
                        </div>
                        <div id="experience" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $lawyer?->experience ?? '<h3 class="text-danger">' . __('No data found!') . '</h3>' !!}
                                </div>
                            </div>
                        </div>
                        <div id="qualification" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    {!! $lawyer?->qualifications ?? '<h3 class="text-danger">' . __('No data found!') . '</h3>' !!}
                                </div>
                            </div>
                        </div>
                        <div id="book_appointment" class="tab-pane fade">
                            <div class="row">
                                <div class="col-md-12">
                                    <h3>{{ __('Create Appointment') }}</h3>

                                    <div class="book-appointment">

                                        <form action="{{ route('website.create.appointment') }}" method="POST">
                                            @csrf
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for=""
                                                            class="form-label">{{ __('Select Date') }}</label>
                                                        <input type="text" name="date"
                                                            class="form-control datepicker" id="datepicker-value">
                                                        <input type="hidden" name="lawyer_id"
                                                            value="{{ $lawyer?->id }}" id="lawyer_id">
                                                        <input type="hidden" value="{{ $lawyer?->department_id }}"
                                                            name="department_id">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row d-none" id="schedule-box-outer">
                                                <div class="col-md-6">
                                                    <div class="mb-3">
                                                        <label for=""
                                                            class="form-label">{{ __('Select Schedule') }}</label>
                                                        <select name="schedule_id" class="form-control"
                                                            id="lawyer-available-schedule">

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 d-none" id="lawyer-schedule-error">

                                                </div>
                                            </div>



                                            <div class="">
                                                <button type="submit" class="submit_btn" id="sub"
                                                    disabled>{{ __('Submit') }}</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!--Tab End-->
                </div>

            </div>
        </div>
    </div>
    <!--Team Detail End-->
@endsection
