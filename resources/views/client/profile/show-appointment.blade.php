@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Appointment History') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Appointment History') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Appointment History') }}</span></li>
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
                        <div class="prescription">
                            <div class="top">
                                <div class="table-responsive">
                                    <table class="table table-no-border">
                                        <tbody>
                                            <tr>
                                                <td class="w-50">
                                                    <div class="prescription-logo"><img src="{{ url($setting?->logo) }}"
                                                            alt="{{ $setting?->app_name }}"></div>
                                                    <div class="address">
                                                        <i class="fas fa-map-marker-alt"></i> {{ $contactInfo?->address }}
                                                    </div>
                                                    <div class="phone">
                                                        <i class="fas fa-phone"></i> {{ $setting?->prescription_phone }}
                                                    </div>
                                                    <div class="email">
                                                        <i class="far fa-envelope"></i> {{ $setting?->prescription_email }}
                                                    </div>
                                                </td>
                                                <td class="w-50">
                                                    <div class="right">
                                                        <h2>{{ $appointment?->lawyer?->name }}</h2>
                                                        <p>
                                                            {{ $appointment?->lawyer?->designations }}
                                                        </p>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="client-info">
                                <div class="table-responsive">
                                    <table class="table table-no-border">
                                        <tbody>
                                            <tr>
                                                <td>{{ __('Client Name') }}: {{ $appointment?->user?->name }}</td>
                                                <td>{{ __('Age') }}: {{ $appointment?->user?->details?->age }}
                                                    {{ __('Years') }}</td>
                                                <td>{{ __('Date') }}: {{ formattedDate($appointment?->date) }}</td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="main-section">
                                <div class="table-responsive">
                                    <table class="table table-no-border">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="problem">
                                                        <h2>{{ __('Subject') }}: {{ $appointment?->subject }}</h2>
                                                        <p>
                                                            {!! clean($appointment?->description) !!}
                                                        </p>

                                                        @if (count($appointment?->documents))
                                                            <h2>{{ __('Important Documents') }}</h2>
                                                            <ol>
                                                                @foreach ($appointment?->documents as $document)
                                                                    <li><a class="text-primary"
                                                                            href="{{ route('client.download.document', ['id' => $appointment->id, 'path' => $document?->path]) }}">{{ $document?->path }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ol>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                            <div class="footer">
                                <h2>{{ __('Signature') }}</h2>
                                <p>
                                    {{ $appointment?->lawyer->name }}<br> {{ $appointment?->lawyer?->designations }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <a target="_blank" class="btn btn-primary mt-3 print-btn"
                        href="{{ route('client.print.prescription', $appointment->id) }}"><i class="fas fa-print"
                            aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->
@endsection
@push('css')
    <style>
        table {
            margin-bottom: 0 !important;
        }

        td,
        th {
            padding: 0 !important;
            height: auto !important;
        }

        table tr td {
            border: none !important;
            text-align: start !important;
        }
    </style>
@endpush
