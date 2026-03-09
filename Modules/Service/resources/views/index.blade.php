@extends('admin.master_layout')
@section('title')
    <title>{{ __('Service List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Service List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    {{-- Search filter --}}
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body p-0">
                                <form action="{{ route('admin.service.index') }}" method="GET"
                                    onchange="$(this).trigger('submit')" class="card-body">
                                    <div class="row">
                                        <div class="col-md-4 form-group mb-3 mb-md-0">
                                            <div class="input-group">
                                                <x-admin.form-input name="keyword" placeholder="{{ __('Search') }}"
                                                    value="{{ request()->get('keyword') }}" />
                                                <button class="btn btn-primary" type="submit"><i
                                                        class="fas fa-search"></i></button>
                                            </div>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="show_homepage" id="show_homepage"
                                                class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Show Homepage') }}" />
                                                <x-admin.select-option :selected="request('show_homepage') == '1'" value="1"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option :selected="request('show_homepage') == '0'" value="0"
                                                    text="{{ __('No') }}" />
                                            </x-admin.form-select>
                                        </div>
                                        <div class="col-md-2 form-group mb-3 mb-md-0">
                                            <x-admin.form-select name="status" id="status" class="form-select">
                                                <x-admin.select-option value="" text="{{ __('Select Status') }}" />
                                                <x-admin.select-option :selected="request('status') == '1'" value="1"
                                                    text="{{ __('Yes') }}" />
                                                <x-admin.select-option :selected="request('status') == '0'" value="0"
                                                    text="{{ __('No') }}" />
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
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.add-button :href="route('admin.service.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="10%">{{ __('Show Homepage') }}</th>
                                                <th width="15%">{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($services as $service)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $service->title }}</td>

                                                    <td>
                                                        @if ($service->show_homepage == 1)
                                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('No') }}</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        <input onchange="changeStatus({{ $service->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $service->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}"
                                                            data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        <x-admin.edit-button :href="route('admin.service.edit', [
                                                            'service' => $service->id,
                                                            'code' => getSessionLanguage(),
                                                        ])" />
                                                        <x-admin.delete-button :id="$service->id" onclick="deleteData" />
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Service')" route="admin.service.create"
                                                    create="yes" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                @if (request()->get('par-page') !== 'all')
                                    <div class="float-right">
                                        {{ $services->onEachSide(0)->links() }}
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
        "use strict"

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/service/') }}' + "/" + id)
        }
        "use strict"

        function changeStatus(id) {
            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                },
                url: "{{ url('/admin/service/status-update') }}" + "/" + id,
                success: function(response) {
                    if (response.success) {
                        toastr.success(response.message);
                    } else {
                        toastr.warning(response.message);
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        }
    </script>
@endpush
