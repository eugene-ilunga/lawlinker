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
                <div class="row">
                    <!-- Today Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Current Balance') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ specific_currency_with_icon('USD',$current_balance ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- New Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Withdraw') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ specific_currency_with_icon('USD',$total_withdraw ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- New Appointment -->
                    <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>{{ __('Total Earning') }}</h4>
                                </div>
                                <div class="card-body">
                                    {{ specific_currency_with_icon('USD',$total_earning ?? 0) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.add-button :href="route('lawyer.withdraw.create')" :text="__('Create Withdraw')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                <th>{{ __('Charge') }}</th>
                                                <th>{{ __('Total Amount') }}</th>
                                                <th>{{ __('Withdraw Amount') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th>{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($withdraws as $index => $withdraw)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $withdraw?->withdraw_method?->name }}</td>
                                                    <td>{{ currency($withdraw?->total_amount - $withdraw?->withdraw_amount) }}
                                                    </td>
                                                    <td>{{ currency($withdraw?->total_amount) }}</td>
                                                    <td> {{ currency($withdraw?->withdraw_amount) }}</td>
                                                    <td>
                                                        @if ($withdraw->status == 'approved')
                                                            <span class="badge bg-success">{{ __('Success') }}</span>
                                                        @elseif ($withdraw->status == 'rejected')
                                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('Pending') }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('lawyer.withdraw.show', $withdraw?->id) }}"
                                                            class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                                aria-hidden="true"></i></a>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="7"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $withdraws->onEachSide(0)->links() }}
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
