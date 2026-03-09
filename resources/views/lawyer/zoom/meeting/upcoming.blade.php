@extends('lawyer.master_layout')
@section('title')
    <title>{{ __('Upcoming Meeting') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Upcoming Meeting') }}" :list="[
                __('Dashboard') => route('lawyer.dashboard'),
                __('Upcoming Meeting') => '#',
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
                                                <th>{{ __('Start Time') }}</th>
                                                <th>{{ __('Duration') }}</th>
                                                <th>{{ __('Meeting ID') }}</th>
                                                <th>{{ __('Password') }}</th>
                                                <th>{{ __('Action') }}</th>
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
                                                    <td>{{ $history?->meeting?->password }}</td>
                                                    <td>
                                                        @if (strtolower(config('app.app_mode')) == 'demo')
                                                            <a id="zoom_demo_mode" href="javascript:;"
                                                                class="btn btn-success btn-sm"><i
                                                                    class="fas fa-video"></i></a>
                                                        @else
                                                            <a target="_blank" data-toggle="tooltip" data-placement="top"
                                                            title="{{ __('Join via Zoom App') }}" href="{{ $history?->meeting?->join_url }}"
                                                                class="btn btn-primary btn-sm"><i
                                                                    class="fas fa-video"></i></a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('')" route="" create="no"
                                                    :message="__('No data found!')" colspan="7" />
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
