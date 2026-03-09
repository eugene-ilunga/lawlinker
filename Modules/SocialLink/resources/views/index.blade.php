@extends('admin.master_layout')
@section('title')
    <title>{{ __('Social Links') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Social Links') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Social Links') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.add-button :href="route('admin.social-link.create')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>{{ __('SN') }}</th>
                                                <th>{{ __('Icon') }}</th>
                                                <th>{{ __('Link') }}</th>
                                                <th class="text-center">{{ __('Actions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($socialLinks as $link)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td><i class="{{ $link->icon }} feature-icon"></i></td>
                                                    <td>{{ $link->link }}</td>
                                                    <td class="text-center">
                                                        <div>
                                                            <x-admin.edit-button :href="route('admin.social-link.edit', $link->id)" />
                                                            <x-admin.delete-button :id="$link->id" onclick="deleteData" />
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Social Links')" route="admin.social-link.create"
                                                    create="yes" :message="__('No data found!')" colspan="6"/>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                                <div class="float-right">
                                    {{ $socialLinks->links() }}
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
            $("#deleteForm").attr("action", "{{ url('/admin/social-link/') }}" + "/" + id)
        }
    </script>
@endpush