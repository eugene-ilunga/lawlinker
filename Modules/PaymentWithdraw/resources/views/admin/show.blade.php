@extends('admin.master_layout')
@section('title')
    <title>{{ __('Withdraw Details') }}</title>
@endsection
@section('admin-content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Withdraw Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Withdraw Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.withdraw-list')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered table-striped table-hover">
                                    <tr>
                                        <td width="50%">{{ __('Lawyer') }}</td>
                                        <td width="50%">
                                            {{ $withdraw?->lawyer?->name }}</a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Method') }}</td>
                                        <td width="50%">{{ $withdraw->withdraw_method?->name }}</td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Current Balance') }}</td>
                                        <td width="50%">
                                            {{ $withdraw?->status == 'approved' ? currency($withdraw?->current_balance) : currency($withdraw?->lawyer?->wallet_balance) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge') }}</td>
                                        <td width="50%">{{ $withdraw->withdraw_charge }}%</td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Withdraw Charge Amount') }}</td>
                                        <td width="50%">
                                            {{ currency($withdraw->total_amount - $withdraw->withdraw_amount) }}
                                        </td>
                                    </tr>

                                    <tr>
                                        <td width="50%">{{ __('Total Amount') }}</td>
                                        <td width="50%">
                                            {{ currency($withdraw->total_amount) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td width="50%">{{ __('Withdraw Amount') }}</td>
                                        <td width="50%">
                                            {{ currency($withdraw->withdraw_amount) }}
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
                                        <td width="50%">
                                            {!! nl2br($withdraw->account_info) !!}
                                        </td>
                                    </tr>

                                </table>

                                @if ($withdraw->status == 'pending')
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#withdrawApproved"
                                        class="btn btn-primary">{{ __('Approve withdraw') }}</i></a>
                                @endif

                                @if ($withdraw->status != 'approved')
                                    <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                        class="btn btn-danger"
                                        onclick="deleteData({{ $withdraw->id }})">{{ __('Delete withdraw request') }}</a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>


    <div class="modal fade" tabindex="-1" role="dialog" id="withdrawApproved">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Withdraw Approved Confirmation') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Are You sure approved this withdraw request ?') }}</p>
                </div>
                <div class="modal-footer bg-whitesmoke br">
                    <form action="{{ route('admin.approved-withdraw', $withdraw?->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <x-admin.button variant="danger" data-bs-dismiss="modal" text="{{ __('Close') }}" />
                        <x-admin.button type="submit" text="{{ __('Yes, Approve') }}" />
                    </form>
                </div>
            </div>
        </div>
    </div>


    <x-admin.delete-modal />
    <script>
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('admin/delete-withdraw/') }}' + "/" + id)
        }
    </script>
@endsection
