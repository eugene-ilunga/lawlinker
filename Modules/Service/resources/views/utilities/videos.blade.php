@extends('admin.master_layout')
@section('title')
    <title>{{ __('Manage Videos') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            <x-admin.breadcrumb title="{{ __('Manage Videos') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Service List') => route('admin.service.index'),
                __('Edit Service') => route('admin.service.edit', [
                    'service' => $service->id,
                    'code' => allLanguages()->first()->code,
                ]),
                __('Manage Videos') => '#',
            ]" />

            @include('service::utilities.navbar')

            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form method="post" action="{{ route('admin.service.videos.update', request('id')) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="row">
                                        <div class="form-group col-md-12">
                                            <x-admin.form-input id="link" name="link" label="{{ __('Link') }}"
                                                placeholder="{{ __('Enter Link') }}" value="{{ old('link') }}"
                                                required="true" />
                                        </div>
                                        <div class="col-12">
                                            <x-admin.save-button :text="__('Add Video')" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-header">
                                <x-admin.form-title :text="__('Videos')" />
                            </div>
                            <div class="card-body">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>{{ __('SN') }}</th>
                                            <th>{{ __('Link') }}</th>
                                            <th>{{ __('Video') }}</th>
                                            <th class="text-center">{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($videos as $item)
                                            <tr>
                                                <td>{{ $loop->index + 1 }}</td>
                                                <td>
                                                    {{ $item->link ?? '' }}
                                                </td>
                                                <td>
                                                    <iframe width="300" height="150"
                                                        src="https://www.youtube.com/embed/{{ $item->code }}"
                                                        title="YouTube video player" frameborder="0"
                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                        allowfullscreen></iframe>

                                                </td>
                                                <td class="text-center">
                                                    <div>
                                                        <x-admin.delete-button :id="$item->id" onclick="deleteData" />
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <x-empty-table :name="__('Videos')" route="admin.service.index" create="no"
                                                :message="__('No data found!')" colspan="5" />
                                        @endforelse
                                    </tbody>
                                </table>
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
    <script type="text/javascript">
        "use Strict"
        function deleteData(id) {
            $("#deleteForm").attr("action", "{{ url('/admin/service-videos/') }}" + "/" + id);
        }
    </script>
@endpush
