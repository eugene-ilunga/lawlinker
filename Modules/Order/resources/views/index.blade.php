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
                                <form action="{{ url()->current() }}" method="GET" onchange="$(this).trigger('submit')"
                                    class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 form-group mb-3 mb-md-0">
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
                                        @if (isRoute('admin.orders'))
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select name="payment_status" id="payment_status" class="form-select">
                                                    <x-admin.select-option value="" text="{{ __('Payment Status') }}" />
                                                    <x-admin.select-option :selected="request('payment_status') == '1'" value="1"
                                                        text="{{ __('Success') }}" />
                                                    <x-admin.select-option :selected="request('payment_status') == '0'" value="0"
                                                        text="{{ __('Pending') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif
                                        <div class="col-md-{{ isRoute('admin.orders') ? '2' : '3' }} form-group mb-3 mb-md-0">
                                            <x-admin.form-select onchange="$('#search_filter_form').trigger('submit');"
                                                name="order_by" id="order_by" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '1'" value="1"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '0'" value="0"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-{{ isRoute('admin.orders') ? '2' : '3' }} form-group mb-3 mb-md-0">
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
                                            <th>{{ __('Order Id') }}</th>
                                            <th>{{ __('Price') }}</th>
                                            <th>{{ __('Payment') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Action') }}</th>
                                        </tr>

                                        @forelse ($orders as $index => $order)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td><a
                                                        href="{{ route('admin.customer-show', $order?->user_id) }}">{{ $order?->user?->name }}</a>
                                                </td>
                                                <td>#{{ $order?->order_id }}</td>
                                                <td>{{ specific_currency_with_icon($order?->payable_currency, $order?->total_payment) }}
                                                </td>

                                                <td>
                                                    @if ($order?->payment_status == 1)
                                                        <div class="badge bg-success">{{ __('Success') }}</div>
                                                    @elseif ($order?->payment_status == 'rejected')
                                                        <div class="badge bg-danger">{{ __('Rejected') }}</div>
                                                    @else
                                                        <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                    @endif
                                                </td>


                                                <td>{{ formattedDate($order?->created_at) }}</td>
                                                <td>
                                                    <a href="{{ route('admin.order', $order?->order_id) }}"
                                                        class="btn btn-primary btn-sm"><i class="fa fa-eye"></i></a>

                                                    @if ($order?->payment_status == 0)
                                                        <x-admin.delete-button :id="$order?->id" onclick="deleteData" />
                                                    @endif

                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Customer')" route="" create="no"
                                                :message="__('No data found!')" colspan="7"></x-empty-table>
                                        @endforelse
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $orders->onEachSide(0)->links() }}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <x-admin.delete-modal />
@endsection
@push('js')
    <script>
        $(function() {
            'use strict'

            $('.delete').on('click', function(e) {
                e.preventDefault();
                const modal = $('#delete');
                modal.find('form').attr('action', $(this).data('url'));
                modal.modal('show');
            })
        })

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/order-delete/') }}' + "/" + id)
        }
    </script>
@endpush
