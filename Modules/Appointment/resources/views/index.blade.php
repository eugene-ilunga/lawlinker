@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <form action="{{ url()->current() }}" method="GET" class="card-body"
                                    id="search_filter_form">
                                    <div class="row">
                                        <div class="col-md-{{ isRoute('admin.appointment.new') ? '6' : '4' }} form-group mb-3 mb-md-0">
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
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="client" id="client" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Client') }}" />
                                                @foreach ($clients as $client)
                                                    <x-admin.select-option :selected="$client->id == request('client')" value="{{ $client?->id }}"
                                                        text="{{ $client?->name }} - ({{ $client?->email }})" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        @if (isRoute(['admin.appointment.index', 'admin.appointment.pending']))
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                    name="treated" id="treated" class="form-select">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Consulted') }}" />
                                                    <x-admin.select-option :selected="request('treated') == '1'" value="1"
                                                        text="{{ __('Yes') }}" />
                                                    <x-admin.select-option :selected="request('treated') == '0'" value="0"
                                                        text="{{ __('No') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        @if (isRoute('admin.appointment.index'))
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                    name="payment_status" id="payment_status" class="form-select">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Payment Status') }}" />
                                                    <x-admin.select-option :selected="request('payment_status') == '1'" value="1"
                                                        text="{{ __('Yes') }}" />
                                                    <x-admin.select-option :selected="request('payment_status') == '0'" value="0"
                                                        text="{{ __('No') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-{{ isRoute('admin.appointment.index') ? '1' : '2' }} form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="order_by" id="order_by" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '1'" value="1"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '0'" value="0"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-{{ isRoute('admin.appointment.index') ? '1' : '2' }} form-group mb-3 mb-md-0">
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

                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Client') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Schedule') }}</th>
                                            <th>{{ __('Payment') }}</th>
                                            <th>{{ __('Consulted') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>

                                        @forelse ($appointments as $index => $appointment)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a
                                                        href="{{ route('admin.customer-show', $appointment->user_id) }}">{{ $appointment?->user?->name }}</a>
                                                </td>
                                                <td>{{ $appointment?->user?->email }}</td>
                                                <td>{{ $appointment?->user?->details?->phone }}</td>
                                                <td>{{ formattedDate($appointment?->date) }}</td>
                                                <td>{{ $appointment?->schedule?->start_time . ' - ' . $appointment?->schedule?->end_time }}
                                                </td>

                                                <td>
                                                    @if ($appointment?->order->payment_status == 1)
                                                        <div class="badge bg-success">{{ __('Success') }}</div>
                                                    @else
                                                        <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($appointment->already_treated == 0)
                                                        <span class="badge bg-danger">{{ __('No') }}</span>
                                                    @else
                                                        <span class="badge bg-success">{{ __('Yes') }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ route('admin.appointment.show', $appointment->order_id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>

                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Appointment')" route="" create="no"
                                                :message="__('No data found!')" colspan="8" />
                                        @endforelse
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
