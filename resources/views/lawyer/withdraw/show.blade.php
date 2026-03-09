@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('My Withdraw') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('My Withdraw') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('My Withdraw') => '#',
            ]" />
            <div class="section-body">

                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('lawyer.withdraw.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Method') }}</td>
                                        <td width="50%">{{ $withdraw->withdraw_method?->name }}</td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge') }}</td>
                                        <td width="50%">{{ $withdraw->withdraw_charge }}%</td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge Amount') }}</td>
                                        <td width="50%">
                                            {{ specific_currency_with_icon('USD',$withdraw->total_amount - $withdraw->withdraw_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Total Amount') }}</td>
                                        <td width="50%">
                                            {{ specific_currency_with_icon('USD',$withdraw->total_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Amount') }}</td>
                                        <td width="50%">
                                            {{ specific_currency_with_icon('USD',$withdraw->withdraw_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Status') }}</td>
                                        <td width="50%">
                                            @if ($withdraw->status == 'approved')
                                                <span class="badge bg-success">{{ __('Success') }}</span>
                                            @elseif ($withdraw->status == 'rejected')
                                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Requested Date') }}</td>
                                        <td width="50%">{{ formattedDate($withdraw->created_at) }}</td>
                                    </tr>
                                    @if ($withdraw->status == 'approved')
                                        <tr>
                                            <td width="50%">{{ __('Approved Date') }}</td>
                                            <td width="50%">{{ formattedDate($withdraw->approved_date) }}</td>
                                        </tr>
                                    @endif

                                    <tr>
                                        <td width="50%">{{ __('Account Information') }}</td>
                                        <td width="50%"> {!! nl2br($withdraw->account_info) !!}</td>
                                    </tr>

                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
