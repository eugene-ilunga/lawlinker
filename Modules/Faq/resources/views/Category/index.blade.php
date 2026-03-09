@extends('admin.master_layout')
@section('title')
    <title>{{ __('FAQ Categories') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('FAQ Categories') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('FAQ Categories') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                @adminCan('faq.category.create')
                                    <div>
                                        <x-admin.add-button :href="route('admin.faq-category.create')" />
                                    </div>
                                @endadminCan
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Name') }}</th>
                                                @adminCan('faq.category.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('faq.category.update') ||
                                                        checkAdminHasPermission('faq.category.delete'))
                                                    <th class="text-center">{{ __('Actions') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($categories as $category)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $category->title }}</td>
                                                    @adminCan('faq.category.update')
                                                        <td>
                                                            <input onchange="changeStatus({{ $category->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $category->status ? 'checked' : '' }} data-toggle="toggle"
                                                                data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan
                                                    @if (checkAdminHasPermission('faq.category.update') || checkAdminHasPermission('faq.category.delete'))
                                                        <td class="text-center">
                                                            @adminCan('faq.category.edit')
                                                                <x-admin.edit-button :href="route('admin.faq-category.edit', [
                                                                    'faq_category' => $category->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('faq.category.delete')
                                                                <x-admin.delete-button :id="$category->id" onclick="deleteData" />
                                                            @endadminCan
                                                            @adminCan('faq.view')
                                                                <x-admin.add-button variant="success"
                                                                    text="{{ __('Manage FAQs') }}" :href="route('admin.faq.by.category', [
                                                                        'slug' => $category->slug,
                                                                        'code' => $code,
                                                                    ])" />
                                                            @endadminCan
                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Category')" route="admin.faq-category.create"
                                                    create="yes" :message="__('No data found!')" colspan="5" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $categories->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('faq.category.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        @adminCan('faq.category.delete')

        function deleteData(id) {
            $("#deleteForm").attr("action", '{{ url('/admin/faq-category/') }}' + "/" + id)
        }
        @endadminCan

        @adminCan('faq.category.update')

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
                url: "{{ url('/admin/faq-category/status-update') }}" + "/" + id,
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
            })
        }
        @endadminCan
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
