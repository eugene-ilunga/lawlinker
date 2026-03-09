@extends('admin.master_layout')
@section('title')
    <title>{{ __('Testimonials') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Testimonials') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Testimonials') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    @adminCan('testimonial.create')
                                        <x-admin.add-button :href="route('admin.testimonial.create')" />
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
                                                <th>{{ __('Designation') }}</th>
                                                <th>{{ __('Image') }}</th>
                                                <th>{{ __('Show Homepage') }}</th>
                                                @adminCan('testimonial.update')
                                                    <th>{{ __('Status') }}</th>
                                                @endadminCan
                                                @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                                                    <th>{{ __('Action') }}</th>
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($testimonials as $testimonial)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>{{ $testimonial?->name }}</td>
                                                    <td>{{ $testimonial?->designation }}</td>
                                                    <td><img src="{{ asset($testimonial?->image ?? $setting?->default_avatar) }}"
                                                            alt="{{ $testimonial?->name }}" class="rounded-circle my-2">
                                                    </td>
                                                    <td>
                                                        @if ($testimonial?->show_homepage == 1)
                                                            <span class="badge bg-success">{{ __('Yes') }}</span>
                                                        @else
                                                            <span class="badge bg-danger">{{ __('No') }}</span>
                                                        @endif
                                                    </td>
                                                    @adminCan('testimonial.update')
                                                        <td>
                                                            <input onchange="changeStatus({{ $testimonial?->id }})"
                                                                id="status_toggle" type="checkbox"
                                                                {{ $testimonial?->status ? 'checked' : '' }}
                                                                data-toggle="toggle" data-onlabel="{{ __('Active') }}"
                                                                data-offlabel="{{ __('Inactive') }}" data-onstyle="success"
                                                                data-offstyle="danger">
                                                        </td>
                                                    @endadminCan

                                                    @if (checkAdminHasPermission('testimonial.edit') || checkAdminHasPermission('testimonial.delete'))
                                                        <td>
                                                            @adminCan('testimonial.edit')
                                                                <x-admin.edit-button :href="route('admin.testimonial.edit', [
                                                                    'testimonial' => $testimonial?->id,
                                                                    'code' => getSessionLanguage(),
                                                                ])" />
                                                            @endadminCan
                                                            @adminCan('testimonial.delete')
                                                                <x-admin.delete-button :id="$testimonial?->id" onclick="deleteData" />
                                                            @endadminCan

                                                        </td>
                                                    @endif
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Testimonial')" route="admin.testimonial.create"
                                                    create="yes" :message="__('No data found!')" colspan="7" />
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $testimonials->onEachSide(3)->onEachSide(3)->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @adminCan('testimonial.delete')
        <x-admin.delete-modal />
    @endadminCan
@endsection

@push('js')
    <script>
        "use strict";
        @adminCan('testimonial.delete')
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/testimonial/') }}" + "/" + id)
        }
        @endadminCan

        @adminCan('testimonial.update')
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
                url: "{{ url('/admin/testimonial/status-update') }}" + "/" + id,
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
