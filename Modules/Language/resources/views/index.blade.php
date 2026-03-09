@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Language') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Language') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Settings') => route('admin.settings'),
                __('Manage Language') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    @adminCan('language.create')
                                        <x-admin.add-button :href="route('admin.languages.create')" />
                                    @endadminCan
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                <th>{{ __('Code') }}</th>
                                                <th>{{ __('Direction') }}</th>
                                                <th>{{ __('Default') }}</th>
                                                <th>{{ __('Translations') }}</th>
                                                <th>{{ __('Status') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($languages as $language)
                                                <tr>
                                                    <td>
                                                        {{ $loop->index + 1 }}</td>
                                                    <td>
                                                        {{ $language->name }}
                                                    </td>
                                                    <td>
                                                        {{ $language->code }}
                                                    </td>
                                                    <td>
                                                        {{ $language->direction == 'ltr' ? __('Left to right') : __('Right to left') }}
                                                    </td>
                                                    <td>
                                                        <input class="self-default-{{ $language->id }} default-status" onchange="changeStatus({{ $language->id }}, 'is_default')"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $language->is_default ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Yes') }}"
                                                            data-offlabel="{{ __('No') }}" data-onstyle="success"
                                                            data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        <div class="dropdown d-inline">
                                                            <a class="btn btn-primary"
                                                                href="{{ route('admin.languages.edit-static-languages', $language->code) }}"
                                                                title="{{ __('Edit Language') }}">
                                                                <i class="fas fa-language"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <a href="javascript:;"
                                                            onclick="changeStatus({{ $language->id }}, 'status')">
                                                            <input id="status_toggle" type="checkbox"
                                                                {{ $language->status ? 'checked' : '' }}
                                                                data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </a>
                                                    </td>
                                                    <td class="text-center">
                                                        <div>
                                                            @adminCan('language.edit')
                                                                <x-admin.edit-button :href="route('admin.languages.edit', $language->id)" />
                                                            @endadminCan
                                                            <x-admin.delete-button :id="$language->id" onclick="deleteData" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Language')" route="admin.languages.create"
                                                    create="yes" :message="__('No data found!')" colspan="8"/>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $languages->links() }}
                                </div>
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
        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/languages/') }}' + "/" + id)
        }

        function changeStatus(id, type) {

            var isDemo = "{{ env('APP_MODE') ?? 'LIVE' }}"
            if (isDemo == 'DEMO') {
                toastr.error("{{ __('This Is Demo Version. You Can Not Change Anything') }}");
                return;
            }
            $.ajax({
                type: "put",
                data: {
                    _token: '{{ csrf_token() }}',
                    column: type
                },
                url: "{{ url('/admin/languages/update-status') }}" + "/" + id,
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        if (type == 'is_default') {
                            window.location.reload();
                        }
                    } else {
                        toastr.warning(response.message);
                        if (!response.status) {
                            window.location.reload();
                        }
                    }
                },
                error: function(err) {}
            })
        }
    </script>
@endpush

@push('css')
    <style>
        .dd-custom-css {
            position: absolute;
            will-change: transform;
            top: 0px;
            left: 0px;
            transform: translate3d(0px, -131px, 0px);
        }

        .max-h-400 {
            min-height: 400px;
        }
    </style>
@endpush
