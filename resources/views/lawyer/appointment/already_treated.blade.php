@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Consultation') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Consultation') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Consultation') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="prescribe">
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="prescription">
                                    <div class="top">
                                        <div class="table-responsive">
                                            <table class="table table-no-border">
                                                <tbody>
                                                    <tr>
                                                        <td class="w-50">
                                                            <div class="prescription-logo"><img width="200"
                                                                    height="auto" src="{{ asset($setting?->logo) }}"
                                                                    alt="{{ $setting?->app_name }}"></div>
                                                            <div class="address">
                                                                {{ $contactInfo?->address }}
                                                            </div>
                                                            <div class="phone">
                                                                {{ $setting?->prescription_phone }}
                                                            </div>
                                                            <div class="email">
                                                                {{ $setting?->prescription_email }}
                                                            </div>
                                                        </td>
                                                        <td class="w-50">
                                                            <div class="right">
                                                                <h2 class="text-end">{{ $appointment?->lawyer?->name }}</h2>
                                                                <p class="text-end">
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
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-no-border">
                                                    <tbody>
                                                        <tr>
                                                            <td>{{ __('Client Name') }}: {{ $appointment?->user?->name }}
                                                            </td>
                                                            <td>{{ __('Age') }}:
                                                                {{ $appointment?->user?->details?->age }}
                                                                {{ __('Years') }}</td>
                                                            <td>{{ __('Date') }}:
                                                                {{ formattedDate($appointment?->date) }}</td>
                                                        </tr>
                                                    </tbody>

                                                </table>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="main-section">
                                        <div class="row">
                                            <div class="table-responsive">
                                                <table class="table table-no-border">
                                                    <tbody>
                                                        <tr>
                                                            <td>
                                                                <div class="problem">
                                                                    <h2>{{ __('Subject') }}: {{ $appointment?->subject }}
                                                                    </h2>
                                                                    <p>
                                                                        {!! clean($appointment?->description) !!}
                                                                    </p>
                                                                    @if (count($appointment?->documents))
                                                                        <h2>{{ __('Important Documents') }}</h2>
                                                                        <ol>
                                                                            @foreach ($appointment?->documents as $document)
                                                                                <li><a
                                                                                        href="{{ route('lawyer.download.document', ['id' => $appointment->id, 'path' => $document?->path]) }}">{{ $document?->path }}</a>
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
                                    </div>

                                    <div class="footer mt-5">
                                        <h2>{{ __('Signature') }}</h2>
                                        <p>
                                            {{ $appointment?->lawyer?->name }}<br>
                                            {{ $appointment?->lawyer?->designations }}
                                        </p>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="row">
                    <div class="col">
                        <a target="_blank" class="btn btn-success me-3 print-btn"
                            href="{{ route('lawyer.print.prescription', $appointment->id) }}"><i class="fas fa-print"
                                aria-hidden="true"></i> {{ __('Print') }}</a>
                        @if (lawyerAuth()->id == $appointment?->lawyer_id)
                            <a href="{{ route('lawyer.treatment.edit', $appointment->id) }}"
                                class="btn btn-primary edit-btn">{{ __('Edit') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('css')
    <link rel="stylesheet" href="{{ asset('client/css/prescription.css') }}">
    <style>
        table {
            margin-bottom: 0 !important;
        }

        td,
        th {
            padding: 0 !important;
            height: auto !important;
        }
    </style>
@endpush
