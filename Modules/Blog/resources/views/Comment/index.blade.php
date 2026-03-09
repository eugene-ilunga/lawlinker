@extends('admin.master_layout')
@section('title')
    <title>{{ __('Post Comments') }}</title>
@endsection
@section('admin-content')
    <div class="main-content">
        <section class="section">
            {{-- Breadcrumb --}}
            <x-admin.breadcrumb title="{{ __('Post Comments') }}" :list="[
                __('Dashboard') => route('admin.dashboard'),
                __('Post Comments') => '#',
            ]" />
            <div class="section-body">
                <div class="mt-4 row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form onchange="$(this).trigger('submit')"
                                    action="{{ route('admin.update-general-setting') }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <label class="d-flex align-items-center mb-0">
                                        <input type="hidden" value="inactive" name="comments_auto_approved"
                                            class="custom-switch-input">
                                        <input {{ $setting->comments_auto_approved == 'active' ? 'checked' : '' }}
                                            type="checkbox" value="active" name="comments_auto_approved"
                                            class="custom-switch-input">
                                        <span class="custom-switch-indicator"></span>
                                        <span
                                            class="custom-switch-description">{{ __('Comments Auto Approved') }}</span>
                                    </label>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="table-responsive max-h-400">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th width="5%">{{ __('SN') }}</th>
                                                <th width="20%">{{ __('Comment') }}</th>
                                                <th width="15%">{{ __('Post') }}</th>
                                                <th width="10%">{{ __('Author') }}</th>
                                                <th width="10%">{{ __('Email') }}</th>
                                                <th width="10%">{{ __('phone') }}</th>
                                                <th width="15%">{{ __('Status') }}</th>
                                                <th width="15%">{{ __('Action') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($comments as $comment)
                                                <tr>
                                                    <td>{{ $loop->index + 1 }}</td>
                                                    <td>
                                                        {{ Str::limit($comment->comment, 30, '...') }}
                                                    </td>
                                                    <td><a target="_blank" href="{{route('website.blog.details',$comment?->post?->slug)}}">{{ $comment?->post?->title }}</a></td>

                                                    <td>
                                                        {{ $comment->name }}
                                                    </td>
                                                    <td>
                                                        {{ $comment->email }}
                                                    </td>
                                                    <td>
                                                        {{ $comment->phone }}
                                                    </td>

                                                    <td>
                                                        <input onchange="changeStatus({{ $comment->id }})"
                                                            id="status_toggle" type="checkbox"
                                                            {{ $comment->status ? 'checked' : '' }} data-toggle="toggle"
                                                            data-onlabel="{{ __('Active') }}" data-offlabel="{{ __('Inactive') }}"
                                                            data-onstyle="success" data-offstyle="danger">
                                                    </td>
                                                    <td>
                                                        <a href="{{ route('admin.blog-comment.show', $comment?->post?->id) }}"
                                                            class="btn btn-success btn-sm"><i class="fa fa-eye"
                                                                aria-hidden="true"></i></a>
                                                        <x-admin.delete-button :id="$comment->id"  onclick="deleteData" />
                                                </tr>
                                            @empty
                                                <x-empty-table :name="__('Post Comments')" route="admin.blog-comment.index"
                                                    create="no" :message="__('No data found!')" colspan="8"></x-empty-table>
                                            @endforelse
                                        </tbody>
                                    </table>
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
            $("#deleteForm").attr("action", '{{ url('/admin/blog-comment/') }}' + "/" + id)
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
                url: "{{ url('/admin/blog-comment/status-update') }}" + "/" + id,
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
