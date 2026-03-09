@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
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
                                                name="lawyer" id="lawyer" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Lawyer') }}" />
                                                @foreach ($lawyers as $lawyer)
                                                    <x-admin.select-option :selected="$lawyer->id == request('lawyer')" value="{{ $lawyer?->id }}"
                                                        text="{{ $lawyer?->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="client" id="client" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Client') }}" />
                                                @foreach ($clients as $client)
                                                    <x-admin.select-option :selected="$client->id == request('client')" value="{{ $client?->id }}"
                                                        text="{{ $client?->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
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

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.dashboard')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Lawyer') }}</th>
                                                <th>{{ __('Client') }}</th>
                                                <th>{{ __('Time') }}</th>
                                                <th>{{ __('Duration') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($histories as $index => $history)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $history?->lawyer?->name }}</td>
                                                    <td>{{ $history?->user?->name }}</td>
                                                    <td>{{ formattedDateTime($history?->meeting_time) }}</td>
                                                    <td>{{ $history->duration }} {{ __('Minutes') }}</td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $histories->onEachSide(0)->links() }}
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
