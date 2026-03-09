@extends('admin.master_layout')
@section('title')
    <title>{{ __('Post Comments') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('All Comments') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Post Comments') => route('admin.blog-comment.index'),
                __('All Comments') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between">
                                <div>
                                    <x-admin.back-button :href="route('admin.blog-comment.index')" />
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="card-body">
                                    <ul class="list-unstyled list-unstyled-border list-unstyled-noborder">
                                        @foreach ($comments as $comment)
                                            <li class="media">
                                                <img alt="image" class="me-3 rounded-circle" width="70" src="{{ asset($setting?->default_avatar) }}">
                                                <div class="media-body">
                                                    <div class="media-right">
                                                        @if ($comment->status == 1)
                                                            <div class="text-primary">{{ __('Approved') }}</div>
                                                        @else
                                                            <div class="text-warning">{{ __('Pending') }}</div>
                                                        @endif
                                                    </div>
                                                    <div class="mb-1 media-title">{{ $comment->name }}</div>
                                                    <div class="text-time">{{ formattedDateTime($comment?->created_at) }}
                                                    </div>
                                                    <div class="media-description text-muted">
                                                        {!! $comment->comment !!}
                                                    </div>
                                                    <div class="media-links">
                                                        <div class="bullet"></div>
                                                        <a href="javascript:;" data-bs-toggle="modal" data-bs-target="#deleteModal" onclick="deleteData({{ $comment->id }})" class="text-danger">{{ __('Trash') }}</a>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="float-right">
                                    {{ $comments->links() }}
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
            $("#deleteForm").attr("action", '{{ url("/admin/blog-comment/") }}' + "/" + id)
        }
    </script>
@endpush
