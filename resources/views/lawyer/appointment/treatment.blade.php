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
                <div class="mt-4 row">
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Client Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td>{{ ucwords($appointment?->user?->name) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $appointment?->user?->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Age') }}</td>
                                            <td>{{ $appointment?->user?->details?->age }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Gender') }}</td>
                                            <td>{{ $appointment?->user?->details?->gender }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Old Appointment History') }}</td>
                                            <td><a
                                                    href="{{ route('lawyer.old.appointment', $appointment?->user_id) }}">{{ __('History Here') }}</a>
                                            </td>
                                        </tr>

                                        @if ($appointment?->user?->disabilities)
                                            <tr>
                                                <td>{{ __('Disabilities') }}</td>
                                                <td>{{ $appointment?->user?->disabilities }}</td>
                                            </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <x-admin.form-title :text="__('Appointment Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Date') }}</td>
                                            <td>{{ formattedDate($appointment?->date) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Schedule') }}</td>
                                            <td>{{ strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Fee') }}</td>
                                            <td>{{ specific_currency_with_icon($appointment?->payable_currency, $appointment->appointment_fee) }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Consulted') }}</td>
                                            <td>
                                                @if ($appointment?->already_treated == 1)
                                                    <span class="badge bg-success">{{ __('Yes') }}</span>
                                                @else
                                                    <span class="badge bg-danger">{{ __('No') }}</span>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <form action="{{ route('lawyer.treatment.store', $appointment->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between">
                                    <x-admin.form-title :text="__('Consultation Summary')" />
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <x-admin.form-input id="subject" name="subject" label="{{ __('Subject') }}"
                                                placeholder="{{ __('Enter Subject') }}" value="{{ old('subject') }}"
                                                required="true" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <x-admin.form-editor id="description" name="description"
                                                label="{{ __('Description') }}" value="{!! old('description') !!}"
                                                required="true" />
                                        </div>
                                        <div class="col-md-6">
                                            <x-admin.form-input label="{{ __('Documents') }}" id="files" type="file"
                                                name="files[]" multiple="multiple" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <x-admin.save-button :text="__('Save')" />
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
