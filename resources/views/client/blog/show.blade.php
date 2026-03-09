@extends('layouts.client.layout')
@section('title')
    <title>{{ @$blog?->seo_title ?? $blog?->title }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ @$blog?->seo_description }}">
    <meta property="og:title" content="{{ @$blog?->seo_title }}" />
    <meta property="og:description" content="{{ @$blog?->seo_description }}" />
    <meta property="og:image" content="{{ asset($blog?->image) }}" />
    <meta property="og:URL" content="{{ url()->current() }}" />
    <meta property="og:type" content="website" />
@endsection
@section('client-content')


    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ $blog?->title }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><a aria-label="{{ __('Blogs') }}" href="{{ route('website.blogs') }}">{{ __('Blogs') }}</a></li>
                            <li><span>{{ $blog?->title }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Blog Start-->
    <div class="blog-page single-blog pt_40 pb_90">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="blog-item">
                        <div class="single-blog-image">
                            <img src="{{ url($blog?->image) }}" alt="{{ $blog?->title }}" loading="lazy">
                            <div class="blog-author">
                                <span><i class="fas fa-user"></i> {{ $blog?->admin?->name ?? __('Admin') }}</span>
                                <span><i class="far fa-calendar-alt"></i> {{ formattedDate($blog?->created_at) }}</span>
                                <span><i class="fas fa-tag" aria-hidden="true"></i> {{ $blog?->category?->title }}</span>
                            </div>
                        </div>
                        <div class="blog-text pt_40">
                            <p>
                                {!! $blog?->sort_description !!}
                            </p>

                            {!! $blog?->description !!}
                        </div>
                    </div>
                    @if ($setting?->comment_type == 0)
                        <div class="comment-list mt_30">
                            <h4>{{ __('Comments') }}</h4>
                        </div>
                        <div class="fb-comments" data-href="{{ Request::url() }}" data-width="" data-numposts="10"></div>
                    @else
                        <div class="comment-list mt_30">
                            @if ($blog?->comments->count() != 0)
                                <h4>{{ __('Comments') }} <span class="c-number">({{ $blog?->comments->count() }})</span>
                                </h4>
                            @endif

                            <ul>
                                @foreach ($blog?->comments as $comment)
                                    <li>
                                        <div class="comment-item">
                                            <div class="thumb">
                                                @php
                                                    $gravatar_link =
                                                        'http://www.gravatar.com/avatar/' .
                                                        md5($comment?->email) .
                                                        '?s=32';
                                                    header('content-type: image/jpeg');
                                                @endphp
                                                <img src="{{ $gravatar_link }}" alt="{{ $comment?->name }}" loading="lazy">
                                            </div>
                                            <div class="com-text">
                                                <h5>{{ ucwords($comment?->name) }}</h5>
                                                <span class="date"><i
                                                        class="fas fa-calendar"></i> {{ formattedDateTime($comment?->created_at) }}</span>
                                                <p>
                                                    {{ $comment?->comment }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                        <div class="comment-form mt_30">
                            <h4>{{ __('Submit A Comment') }}</h4>
                            <form method="POST" action="{{ route('website.comment.store', $blog?->slug) }}">
                                @csrf
                                <div class="form-row row">
                                    @auth('web')
                                        <div class="form-group col-12">
                                            <input type="hidden" class="form-control" name="name" value="{{ userAuth()->name }}">
                                            <input type="hidden" class="form-control" name="email" value="{{ userAuth()->email }}">
                                            <input type="hidden" class="form-control" name="phone" value="{{ userAuth()?->details?->phone }}">
                                        </div>
                                    @else
                                        <div class="form-group col-12">
                                            <input type="text" class="form-control" name="name"
                                                value="{{ old('name') }}" placeholder="{{ __('Name') }}">
                                        </div>
                                        <div class="form-group col-12">
                                            <input type="email" class="form-control" name="email"
                                                value="{{ old('email') }}" placeholder="{{ __('Email') }}">
                                        </div>
                                        <div class="form-group col-12">
                                            <input type="text" class="form-control" name="phone"
                                                value="{{ old('phone') }}" placeholder="{{ __('Phone') }}">
                                        </div>
                                    @endauth
                                    <div class="form-group col-12">
                                        <textarea class="form-control" name="comment" placeholder="{{ __('Comment') }}">{{ old('comment') }}</textarea>
                                    </div>
                                    @if ($setting->recaptcha_status == 'active')
                                        <div class="form-group col-12">
                                            <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                            </div>
                                        </div>
                                    @endif
                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn">{{ __('Submit') }}</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif

                </div>
                <div class="col-lg-4">
                    <div class="sidebar">
                        <div class="sidebar-item">
                            <h3>{{ __('Blog Category') }}</h3>
                            <ul>
                                @foreach ($blogCategories as $category)
                                    <li class="{{ $blog?->blog_category_id == $category?->id ? 'active' : '' }}"><a aria-label="{{ $category?->title }}"
                                            href="{{ route('website.blog.category', $category?->slug) }}"><i
                                                class="fas fa-chevron-right"></i>{{ $category?->title }}</a></li>
                                @endforeach


                            </ul>
                        </div>
                        <div class="sidebar-item">
                            <h3>{{ __('Recent Posts') }}</h3>
                            @foreach ($latestBlog as $item)
                                <div class="blog-recent-item">
                                    <div class="blog-recent-photo">
                                        <a aria-label="{{ $item?->title }}" href="{{ route('website.blog.details', $item?->slug) }}"><img
                                                src="{{ url($item?->thumbnail_image) }}" alt="{{ $item?->title }}" loading="lazy"></a>
                                    </div>
                                    <div class="blog-recent-text">
                                        <a aria-label="{{ $item?->title }}"
                                            href="{{ route('website.blog.details', $item?->slug) }}">{{ $item?->title }}</a>
                                        <div class="blog-post-date">{{ formattedDate($item?->created_at) }}</div>
                                    </div>
                                </div>
                            @endforeach


                        </div>
                    </div>
                    <!--Sidebar End-->
                </div>
            </div>
        </div>
    </div>
    @if ($setting?->comment_type == 0)
        <div id="fb-root"></div>
    @endif
@endsection
@push('js')
    @if ($setting?->comment_type == 0)
        <script async defer crossorigin="anonymous"
            src="https://connect.facebook.net/en_US/sdk.js#xfbml=1&version=v11.0&appId={{ $setting?->facebook_comment_script }}&autoLogAppEvents=1"
            nonce="MoLwqHe5"></script>
    @endif
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
