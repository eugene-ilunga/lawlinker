@extends('admin.master_layout')
@section('title')
    <title>{{ __('Leave Details') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Leave Details') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Leave Details') => '#',
            ]" />

            <div class="section-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Leave Details')" />
                            </div>
                            <div class="card-body">
                                <div class="table-responsive text-center">
                                    <table class="table table-striped">
                                        <tr>
                                            <td>{{ __('Lawyer Name') }}</td>
                                            <td>{{ $leave?->lawyer?->name }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Email Address') }}</td>
                                            <td>{{ $leave?->lawyer?->email }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Date') }}</td>
                                            <td>{{ formattedDate($leave?->date) }}</td>
                                        </tr>
                                        <tr>
                                            <td>{{ __('Reason') }}</td>
                                            <td>{{ $leave?->reason }}</td>
                                        </tr>

                                        <tr>
                                            <td>{{ __('Status') }}</td>
                                            <td>
                                                @if ($leave?->status)
                                                    <div class="badge bg-success">{{ __('Approved') }}</div>
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
                </div>
            </div>
        </section>
    </div>
@endsection
