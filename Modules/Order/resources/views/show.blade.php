@extends('admin.master_layout')
@section('title')
    <title>{{ __('Order Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Order Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Order Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Client Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table table-striped">

                                        <tr>
                                            <td>{{ __('Name') }}</td>
                                            <td><a
                                                    href="{{ route('admin.customer-show', $order?->user_id) }}">{{ $order?->user?->name }}</a>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Email') }}</td>
                                            <td>{{ $order?->user?->email }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Phone') }}</td>
                                            <td>{{ $order?->user?->details?->phone }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Age') }}</td>
                                            <td>{{ $order?->user?->details?->age }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Gender') }}</td>
                                            <td>{{ $order?->user?->details?->gender }}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Billing Information')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Fee') }}</td>
                                            <td>{{ specific_currency_with_icon($order?->payable_currency, $order?->total_payment) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Payment Method') }}</td>
                                            <td>{{ $order?->payment_method }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Transaction') }}</td>
                                            <td>{{ $order?->payment_transaction_id }}  </td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Date') }}</td>
                                            <td>{{ formattedDate($order?->created_at) }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Payment Status') }}</td>
                                            <td>
                                                @if ($order?->payment_status == 1)
                                                    <div class="badge bg-success">{{ __('Approved') }} {{__('on')}} - {{ formattedDate($order?->approved_date) }}</div>
                                                @elseif ($order?->payment_status == 'rejected')
                                                    <div class="badge bg-danger">{{ __('Rejected') }}</div>
                                                @else
                                                    <div class="badge bg-danger">{{ __('Pending') }}</div>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>


                    <div class="col-12 col-md-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Appointment Information')" />
                            </div>
                            <div class="card-body text-center">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Lawyer') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Phone') }}</th>
                                            <th>{{ __('Schedule') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Fee') }}</th>
                                        </tr>

                                        @forelse ($order?->appointments as $index => $appointment)
                                            <tr>
                                                <td>{{ ++$index }}</td>
                                                <td>{{ $appointment?->lawyer?->name }}</td>
                                                <td>{{ $appointment?->lawyer?->email }}</td>
                                                <td>{{ $appointment?->lawyer?->phone }}</td>
                                                <td>{{ strtoupper($appointment?->schedule?->start_time) . '-' . strtoupper($appointment?->schedule?->end_time) }}
                                                </td>
                                                <td>{{ formattedDate($appointment->date) }}</td>
                                                <td>{{ specific_currency_with_icon($appointment?->payable_currency, $appointment->appointment_fee) }}</td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('')" route="" create="no" :message="__('No data found!')"
                                                colspan="7"></x-empty-table>
                                        @endforelse
                                    </table>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col">
                        @if ($order?->payment_status == 0)
                            <a href="javascript:;" class="btn btn-success" onclick="event.preventDefault(); $('#approvePayment').trigger('submit');">{{ __('Approve Payment') }}</a>
                            <form id="approvePayment" action="{{ route('admin.order-payment-approved', $order?->id) }}"
                                method="POST" class="d-none">
                                @csrf
                            </form>
                            <a href="" data-url="{{ route('admin.order-delete', $order?->id) }}"
                                    class="btn btn-danger delete">{{ __('Delete Order') }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" tabindex="-1" role="dialog" id="delete">
        <div class="modal-dialog" role="document">
            <form action="" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Delete Order') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-danger">{{ __('Are you sure to delete this order ?') }}</p>
                    </div>
                    <div class="modal-footer">
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Delete') }}" />
                    </div>
                </div>
            </form>
        </div>
    </div>
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
    </script>
@endpush
