@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Old Appointment') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Old Appointment') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Old Appointment') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('lawyer.old.appointment',$user_id) }}" method="GET" class="card-body"
                                    id="search_filter_form">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input class="datepicker-two" name="from_date"
                                                    placeholder="{{ __('From Date') }}"
                                                    value="{{ request('from_date') }}" autocomplete="off" />
                                                <x-admin.form-input class="datepicker-two" name="to_date"
                                                    placeholder="{{ __('To Date') }}" value="{{ request('to_date') }}" autocomplete="off" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="order_by" id="order_by" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '1'" value="1"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '0'" value="0"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-3 form-group mb-3 mb-md-0">
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
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Lawyer') }}</th>
                                                <th>{{ __('Department') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($appointments as $index => $appointment)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $appointment?->user?->email }}</td>
                                                    <td>{{ formattedDate($appointment->date) }}</td>
                                                    <td>{{ ucwords($appointment?->lawyer?->name) }}</td>
                                                    <td>{{ ucwords($appointment?->lawyer?->department?->name) }}</td>
                                                    <td>
                                                        @if ($appointment->payment_status == 1)
                                                            <a href="{{ route('lawyer.treatment', $appointment->id) }}"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fas fa-eye"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Today Appointment')" route="lawyer.today.appointment"
                                                    create="no" :message="__('No data found!')" colspan="6" />
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
