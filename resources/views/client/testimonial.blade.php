@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Testimonial')->first()?->seo_title ?? 'Testimonial | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Testimonial')->first()?->seo_description ?? 'Testimonial | LawMent' }}">
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Testimonials') }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Testimonials') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Testimonial Start-->
    <div class="testimonial-page pt_70 pb_100">
        <div class="container">
            <div class="row">
                @foreach ($testimonials as $testimonial)
                    <div class="col-lg-4 col-md-6 col-12">
                        <div class="testimonial-item mt_30">
                            <p>
                                {{ $testimonial?->comment }}
                            </p>
                            <div class="testi-info">
                                <img src="{{ url($testimonial?->image) }}" alt="{{ $testimonial?->name }}" loading="lazy">
                                <h4 class="title">{{ $testimonial?->name }}</h4>
                                <span>{{ $testimonial?->designation }}</span>
                            </div>
                            <div class="testi-link">
                                <a href="javascript:void;"></a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
            @if ($testimonials->hasPages())
                {{ $testimonials->links('client.paginator') }}
            @endif
        </div>
    </div>
    <!--Testimonial End-->
@endsection
