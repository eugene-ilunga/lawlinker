@extends('admin.master_layout')
@section('title')
    <title>{{ __('Customizable Page List') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Customizable Page List') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Customizable Page List') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.add-button :href="route('admin.custom-pages.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="30%">{{ __('Title') }}</th>
                                                <th width="15%">{{ __('Slug') }}</th>
                                                <th width="15%">{{ __('Items') }}</th>
                                                <th width="15%">{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($pages as $page)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><a target="_blank" href="">{{ $page->title }}</a>
                                                    </td>
                                                    <td>{{ $page->slug }}</td>
                                                    <td>{{ $page->items_count }}</td>
                                                    <td>
                                                        <input onchange="changeStatus({{ $page->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $page->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}" data-offlabel="{{ __('Inactive') }}"
                                                            data-onstyle="success" data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        <x-admin.edit-button :href="route('admin.custom-pages.edit', ['page' => $page->id, 'code' => getSessionLanguage()])" />
                                                        @if (!in_array($page->slug,['terms-contidions', 'privacy-policy']))
                                                            <x-admin.delete-button :id="$page->id" onclick="deleteData" />
                                                        @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Customizable Page')" route="admin.custom-pages.index"
                                                    create="no" :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $pages->onEachSide(0)->links() }}
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
            $("#deleteForm").attr("action", '{{ url('/admin/custom-pages/') }}' + "/" + id)
        }

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
                url: "{{ url('/admin/custom-pages/status-update') }}" + "/" + id,
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
