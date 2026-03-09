@extends('layouts.client.layout')
@section('title')
    <title>{{ $service?->seo_title ?? $service?->title  }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $service?->seo_description }}">
    <meta property="og:title" content="{{ $service?->seo_title }}" />
    <meta property="og:description" content="{{ $service?->seo_description }}" />
    <meta property="og:image" content="{{ asset($service?->icon) }}" />
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
                        <h1>{{ $service?->title }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ $service?->title }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Service Detail Start-->
    <div class="service-detail-area pt_40">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="service-detail-text pt_30">

                        <div class="row mb_30">
                            <div class="col-md-12">
                                <!-- Swiper -->
                                <div class="swiper-container pro-detail-top">
                                    <div class="swiper-wrapper">
                                        @foreach ($service?->images as $item)
                                            <div class="swiper-slide">
                                                <div class="catagory-item">
                                                    <div class="catagory-img-holder">
                                                        <img src="{{ url($item?->large_image) }}" alt="{{__('Service')}}" loading="lazy">
                                                        <div class="catagory-text">
                                                            <div class="catagory-text-table">
                                                                <div class="catagory-text-cell">
                                                                    <ul class="catagory-hover">
                                                                        <li><a aria-label="{{ __('Search') }}" href="{{ url($item?->large_image) }}"
                                                                                class="magnific"><i
                                                                                    class="fas fa-search"></i></a></li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                    <!-- Add Arrows -->
                                    <div class="swiper-button-next swiper-button-white"></div>
                                    <div class="swiper-button-prev swiper-button-white"></div>
                                </div>
                                <div class="swiper-container pro-detail-thumbs">
                                    <div class="swiper-wrapper">
                                        @foreach ($service?->images as $item)
                                            <div class="swiper-slide"><img src="{{ url($item?->small_image) }}" alt="{{__('Service')}}" loading="lazy"></div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! $service?->description !!}
                    </div>
                    @if ($service?->service_faq->count() != 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="faq-service feature-section-text mt_50">
                                    <h2>{{ __('Frequently Asked Questions') }}</h2>
                                    <div class="feature-accordion" id="accordion">
                                        @foreach ($service?->service_faq as $faq)
                                            <div class="faq-item card">
                                                <div class="faq-header" id="heading-{{ $faq?->id }}">
                                                    <button class="faq-button collapsed" data-bs-toggle="collapse"
                                                        data-bs-target="#collapse-{{ $faq?->id }}" aria-expanded="true"
                                                        aria-controls="collapse-{{ $faq?->id }}">{{ $faq?->question }}</button>
                                                </div>

                                                <div id="collapse-{{ $faq?->id }}" class="collapse"
                                                    aria-labelledby="heading-{{ $faq?->id }}" data-parent="#accordion">
                                                    <div class="faq-body">
                                                        {!! $faq?->answer !!}
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach


                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if ($service?->videos->count() != 0)
                        <div class="row mt_50">
                            <div class="col-12">
                                <div class="video-headline">
                                    <h3>{{ __('Related Video') }}</h3>
                                </div>
                            </div>

                            @foreach ($service?->videos as $video)
                                <div class="col-md-6">
                                    <div class="video-item mt_30">
                                        <div class="video-img">
                                            @php
                                                $video_id = explode('=', $video?->link);
                                            @endphp
                                            <img src="https://img.youtube.com/vi/{{ $video_id[1] }}/0.jpg">
                                            <div class="video-section">
                                                <a aria-label="{{ __('Video') }}" class="video-button mgVideo"
                                                    href="{{ $video?->link }}"><span></span></a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                </div>
                <div class="col-md-4">
                    <div class="service-sidebar pt_30">
                        <div class="service-widget">
                            <ul>
                                @foreach ($services as $item)
                                    <li class="{{ $item?->id == $service?->id ? 'active' : '' }}"><a
                                            href="{{ route('website.service.details', $item?->slug) }}"><i
                                                class="fas fa-chevron-right"></i> {{ $item?->title }}</a></li>
                                @endforeach


                            </ul>
                        </div>
                        <div class="service-widget-contact mt_45">
                            <h2>{{ $contactInfo?->header }}</h2>
                            <p>{{ $contactInfo?->description }}</p>
                            <ul>
                                <li><i class="fas fa-phone"></i> {!! nl2br(e($contactInfo?->email)) !!}</li>
                                <li><i class="far fa-envelope"></i> {!! nl2br(e($contactInfo?->phone)) !!}</li>
                                <li><i class="fas fa-map-marker-alt"></i>{!! nl2br(e($contactInfo?->address)) !!}</li>
                            </ul>
                        </div>
                        <div class="service-qucikcontact event-form mt_30">
                            <h3>{{ __('Quick Contact') }}</h3>
                            <form action="{{ route('send-contact-message') }}" method="POST">
                                @csrf
                                <div class="form-row row">
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" id="name"
                                            placeholder="{{ __('Name') }}" name="name">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('Phone') }}"
                                            name="phone">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="email" class="form-control" placeholder="{{ __('Email') }}"
                                            name="email">
                                    </div>
                                    <div class="form-group col-md-12">
                                        <input type="text" class="form-control" placeholder="{{ __('Subject') }}"
                                            name="subject">
                                    </div>

                                    <div class="form-group col-md-12">
                                        <textarea name="message" class="form-control" placeholder="{{ __('Message') }}"></textarea>
                                    </div>
                                    @if ($setting->recaptcha_status == 'active')
                                        <div class="form-group col-12">
                                            <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}">
                                            </div>
                                        </div>
                                    @endif

                                    <div class="form-group col-md-12">
                                        <button type="submit" class="btn">{{ __('Send Message') }}</button>
                                    </div>

                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>
    <!--Service Detail End-->
@endsection
@push('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
