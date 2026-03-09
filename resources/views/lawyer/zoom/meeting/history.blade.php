@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Meeting History') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Meeting History') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Meeting History') => '#',
            ]" />
            <div class="section-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.add-button :href="route('lawyer.create-zoom-meeting')" :text="__('Create Meeting')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Client') }}</th>
                                                <th>{{ __('Time') }}</th>
                                                <th>{{ __('Duration') }}</th>
                                                <th>{{ __('Meeting ID') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($histories as $index => $history)
                                                <tr>
                                                    <td>{{ ++$index }}</td>
                                                    <td>{{ $history?->user?->name }}</td>
                                                    <td>{{ staticFormattedDateTime($history?->meeting_time) }}</td>
                                                    <td>{{ $history->duration }} {{ __('Minutes') }}</td>
                                                    <td>{{ $history?->meeting_id }}</td>
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
