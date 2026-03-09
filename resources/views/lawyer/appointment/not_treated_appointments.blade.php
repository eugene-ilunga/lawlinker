@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Not Consulted') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Not Consulted') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Not Consulted') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <form action="{{ route('lawyer.all.appointment') }}" method="GET" class="card-body"
                                    id="search_filter_form">
                                    <div class="row">
                                        <div class="col-md-5 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input class="datepicker-two" name="from_date"
                                                    placeholder="{{ __('From Date') }}" value="{{ request('from_date') }}"
                                                    autocomplete="off" />
                                                <x-admin.form-input class="datepicker-two" name="to_date"
                                                    placeholder="{{ __('To Date') }}" value="{{ request('to_date') }}"
                                                    autocomplete="off" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="client" id="client" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Client') }}" />
                                                @foreach ($clients as $client)
                                                    <x-admin.select-option :selected="$client->id == request('client')" value="{{ $client?->id }}"
                                                        text="{{ $client?->name }} - ({{ $client?->email }})" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="order_by" id="order_by" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '1'" value="1"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '0'" value="0"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="par-page" id="par-page" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Per Page') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '5'" value="5"
                                                    text="{{ __('5') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '10'" value="10"
                                                    text="{{ __('10') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '25'" value="25"
                                                    text="{{ __('25') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '50'" value="50"
                                                    text="{{ __('50') }}" />
                                                <x-admin.select-option :selected="request('par-page') == '100'" value="100"
                                                    text="{{ __('100') }}" />
                                                <x-admin.select-option :selected="request('par-page') == 'all'" value="all"
                                                    text="{{ __('All') }}" />
                                            </x-admin.form-select>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

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
                                                <th>{{ __('Email') }}</th>
                                                <th>{{ __('Phone') }}</th>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Schedule') }}</th>
                                                <th>{{ __('Payment') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($appointments as $index => $appointment)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $appointment?->user?->name }}</td>
                                                    <td>{{ $appointment?->user?->details?->phone }}</td>
                                                    <td>{{ formattedDate($appointment->date) }}</td>
                                                    <td>{{ strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time) }}
                                                    </td>
                                                    <td>
                                                        @if ($appointment?->order?->payment_status == 1)
                                                            <div class="badge bg-success">{{ __('Success') }}</div>
                                                        @else
                                                            <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($appointment->payment_status == 1)
                                                            <a href="{{ route('lawyer.treatment', $appointment->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fas fa-eye"></i></a>

                                                            <a href="{{ route('lawyer.create-zoom-meeting', ['user' => $appointment?->user?->id]) }}"
                                                                class="btn btn-primary btn-sm" data-toggle="tooltip"
                                                                data-placement="top" title="{{ __('Create Meeting') }}"
                                                                target="_blank"><i class="fas fa-video"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Today Appointment')" route="lawyer.today.appointment"
                                                    create="no" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $appointments->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
@push('js')
    <script>
        $(document).ready(function() {
            $(".datepicker-two").datepicker({
                format: "yyyy-mm-dd",
                startDate: "2000-01-01"
            });
        });
    </script>
@endpush
