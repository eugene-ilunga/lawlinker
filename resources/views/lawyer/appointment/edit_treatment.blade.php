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
                <form action="{{ route('lawyer.treatment.update', $appointment->id) }}" method="POST"
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
                                                placeholder="{{ __('Enter Subject') }}"
                                                value="{{ old('subject', $appointment?->subject) }}" required="true" />
                                        </div>
                                        <div class="col-md-12 mb-3">
                                            <x-admin.form-editor id="description" name="description"
                                                label="{{ __('Description') }}" value="{!! old('description', $appointment?->description) !!}"
                                                required="true" />
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <x-admin.form-input label="{{ __('Documents') }}" id="files" type="file"
                                                name="files[]" multiple="multiple" />
                                        </div>

                                        @if (count($appointment->documents))
                                            <div class="col-md-12">
                                                <h6>{{ __('Existing Documents') }}:</h6>
                                                @foreach ($appointment->documents as $document)
                                                    <div class="form-group col-md-6 delete-row">
                                                        <div class="input-group">
                                                            <x-admin.form-input value="{{ $document->path }}" disabled />
                                                            <div class="input-group-append">
                                                                <button type="button"
                                                                    class="input-group-text wsus-toggle-medicine-row bg-danger text-white removeDocument"
                                                                    data-id="{{ $document->id }}"
                                                                    data-appointment="{{ $appointment->id }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <x-admin.update-button :text="__('Update')" />
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        (function($) {
            "use strict";
            $(document).ready(function() {
                $(document).on('click', '.removeDocument', function() {
                    var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
                    if (isDemo == 'DEMO') {
                        toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                        return;
                    }
                    const button = $(this);
                    var id = button.data('id');
                    var appointment_id = button.data('appointment');
                    var buttonText = button.html();

                    $.ajax({
                        type: 'DELETE',
                        url: "{{ url('lawyer/delete-document/') }}" + "/" + appointment_id +
                            "/" + id,
                        beforeSend: function() {
                            button.html(`<i class="fas fa-spinner fa-spin"></i>`);
                        },
                        success: function(response) {
                            if (response.success) {
                                button.closest('.delete-row').remove();
                                toastr.success(response.message);
                            } else {
                                toastr.warning(response.message);
                            }
                        },
                        error: function(err) {
                            button.html(buttonText);
                            console.log(err);
                        }
                    });


                });
            });
        })(jQuery);
    </script>
@endpush
