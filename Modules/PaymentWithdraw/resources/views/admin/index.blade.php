@extends('admin.master_layout')
@section('title')
    <title>{{ $title }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ $title }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                $title => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ url()->current() }}" method="GET" onchange="$(this).trigger('submit')"
                                    class="card-body">
                                    <div class="row">
                                        <div
                                            class="{{ Route::is('admin.withdraw-list') ? 'col-md-4' : 'col-md-6' }} form-group mb-3 mb-md-0">
                                                <div class="input-group">
                                                    <x-admin.form-input name="keyword" placeholder="{{ __('Search') }}"
                                                        value="{{ request()->get('keyword') }}" />
                                                    <button class="btn btn-primary" type="submit"><i
                                                            class="fas fa-search"></i></button>
                                                </div>
                                        </div>
                                        @if (Route::is('admin.withdraw-list'))
                                            <div class="col-md-2 form-group mb-3 mb-md-0">
                                                <x-admin.form-select name="status" id="status" class="form-select">
                                                    <x-admin.select-option value=""
                                                        text="{{ __('Select Status') }}" />
                                                    <x-admin.select-option :selected="request('status') == 'pending'" value="pending"
                                                        text="{{ __('Pending') }}" />
                                                    <x-admin.select-option :selected="request('status') == 'approved'" value="approved"
                                                        text="{{ __('Approved') }}" />
                                                    <x-admin.select-option :selected="request('status') == 'rejected'" value="rejected"
                                                        text="{{ __('Rejected') }}" />
                                                </x-admin.form-select>
                                            </div>
                                        @endif

                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="lawyer" id="lawyer" class="select2">
                                                <x-admin.select-option value="" text="{{ __('Select Lawyer') }}" />
                                                @foreach ($lawyers as $lawyer)
                                                    <x-admin.select-option :selected="$lawyer->id == request('lawyer')" value="{{ $lawyer?->id }}"
                                                        text="{{ $lawyer?->name }}" />
                                                @endforeach
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="order_by" id="order_by" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Order By') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '1'" value="1"
                                                    text="{{ __('ASC') }}" />
                                                <x-admin.select-option :selected="request('order_by') == '0'" value="0"
                                                    text="{{ __('DESC') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="par-page" id="par-page" class="form-select">
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
                            <div class="card-body">
                                <div class="table-responsive table-invoice">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Lawyer') }}</th>
                                                <th>{{ __('Method') }}</th>
                                                 <th>{{ __('Current Balance') }}</th>
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
                                                    <td>{{ $withdraw?->lawyer?->name }}</td>

                                                    <td>{{ $withdraw->withdraw_method?->name }}</td>
                                                    <td>
                                                        {{ $withdraw?->status == 'approved' ? currency($withdraw?->current_balance) : currency($withdraw?->lawyer?->wallet_balance) }}
                                                    </td>
                                                    <td>
                                                        {{ currency($withdraw->total_amount - $withdraw->withdraw_amount) }}
                                                    </td>
                                                    <td>
                                                        {{ currency($withdraw->total_amount) }}
                                                    </td>
                                                    <td>
                                                        {{ currency($withdraw->withdraw_amount) }}
                                                    </td>
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

                                                        <a href="{{ route('admin.show-withdraw', $withdraw->id) }}"
                                                            class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                                aria-hidden="true"></i></a>

                                                        @if ($withdraw->status != 'approved')
                                                            <x-admin.delete-button :id="$withdraw->id" onclick="deleteData" />
                                                        @endif
                                                    </td>


                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="8"></x-empty-table>
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
        </section>
    </div>

    <x-admin.delete-modal />
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/delete-withdraw/') }}' + "/" + id)
        }
    </script>
@endsection
