@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Blog')->first()?->seo_title ?? 'Blog | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Blog')->first()?->seo_description ?? 'Blog | LawMent' }}">
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Blogs') }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Blogs') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Blog Start-->
    <div class="blog-page pt_70 pb_100">
        <div class="container">
            <div class="row">
                @foreach ($blogs as $blog)
                    <div class="col-lg-4 col-sm-6">
                        <div class="blog-item">
                            <div class="blog-image">
                                <a aria-label="{{ $blog?->title }}" href="{{ route('website.blog.details', $blog?->slug) }}"><img src="{{ url($blog?->thumbnail_image) }}" alt="{{ $blog?->title }}" loading="lazy"></a>
                            </div>
                            <div class="blog-author">
                                <span><i class="fas fa-user"></i> {{ $blog?->admin?->name ?? __('Admin') }}</span>
                                <span><i class="far fa-calendar-alt"></i> {{ formattedDate($blog?->created_at) }}</span>
                            </div>
                            <div class="blog-text">
                                <h3 class="title"><a aria-label="{{ $blog?->title }}" href="{{ route('website.blog.details', $blog?->slug) }}">{{ Str::limit($blog?->title,40,'...') }}</a>
                                </h3>
                                <p>
                                    {{ $blog?->sort_description }}

                                </p>
                                <a aria-label="{{ $blog?->title }}" class="sm_btn"
                                    href="{{ route('website.blog.details', $blog?->slug) }}">{{ __('Details') }} →</a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            <!--Pagination Start-->
            @if ($blogs->hasPages())
                {{ $blogs->links('client.paginator') }}
            @endif
            <!--Pagination End-->
        </div>
    </div>
    <!--Blog End-->
@endsection
