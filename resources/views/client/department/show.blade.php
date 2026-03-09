@extends('layouts.client.layout')
@section('title')
    <title>{{ $department?->seo_title ?? $department?->name }}</title>
@endsection
@section('meta')
    <meta name="description" content="{{ $department?->seo_description }}">
    <meta property="og:title" content="{{ $department?->seo_title }}" />
    <meta property="og:description" content="{{ $department?->seo_description }}" />
    <meta property="og:image" content="{{ asset($department?->thumbnail_image) }}" />
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
                        <h1>{{ $department?->name }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ $department?->name }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->



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
                                        @foreach ($department?->images as $item)
                                            <div class="swiper-slide">
                                                <div class="catagory-item">
                                                    <div class="catagory-img-holder">
                                                        <img src="{{ url($item?->large_image) }}"
                                                            alt="{{ $department?->name }}" loading="lazy">
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
                                        @foreach ($department?->images as $item)
                                            <div class="swiper-slide"><img src="{{ url($item?->small_image) }}"
                                                    alt="{{ $department?->name }}" loading="lazy"></div>
                                        @endforeach

                                    </div>
                                </div>
                            </div>
                        </div>
                        {!! $department?->description !!}
                    </div>
                    @if ($department->department_faq->count() != 0)
                        <div class="row">
                            <div class="col-md-12">
                                <div class="faq-service feature-section-text mt_50">
                                    <h2>{{ __('Frequently Asked Questions') }}</h2>
                                    <div class="feature-accordion" id="accordion">
                                        @foreach ($department?->department_faq as $faq)
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

                    @if ($department?->videos->count() != 0)
                        <div class="row mt_50">
                            <div class="col-12">
                                <div class="video-headline">
                                    <h3>{{ __('Related Video') }}</h3>
                                </div>
                            </div>

                            @foreach ($department?->videos as $video)
                                <div class="col-md-6">
                                    <div class="video-item mt_30">
                                        <div class="video-img">
                                            @php
                                                $video_id = explode('=', $video?->link);

                                            @endphp
                                            <img src="https://img.youtube.com/vi/{{ $video_id[1] }}/0.jpg" loading="lazy">
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
                                @foreach ($departments as $item)
                                    <li class="{{ $item->id == $department?->id ? 'active' : '' }}"><a aria-label="{{ $item?->name }}"
                                            href="{{ route('website.department.details', $item?->slug) }}"><i
                                                class="fas fa-chevron-right"></i> {{ $item?->name }}</a></li>
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


    @if ($lawyers->count() != 0)
        <div class="team-page service_details_team pt_40 pb_70 bg_f2f2f2">
            <div class="container">
                <div class="row">
                    <div class="col-md-11 col-lg-8 col-xl-7 m-auto wow fadeInDown">
                        <div class="main-headline">
                            <h2 class="title">{{ __('Department Lawyer') }}</h2>
                            <p>{{ $description }}</p>
                        </div>
                    </div>
                </div>


                <div class="row">

                    @if ($lawyers->count() != 0)
                        @foreach ($lawyers as $lawyer)
                            <div class="col-lg-3 col-md-6 mt_30">
                                <div class="team-item">
                                    <div class="team-photo">
                                        <img src="{{ url($lawyer?->image ? $lawyer?->image : $setting?->default_avatar) }}"
                                            alt="{{ $lawyer?->name }}" loading="lazy">
                                    </div>
                                    <div class="team-text">
                                        <a aria-label="{{ $lawyer?->name }}"
                                            href="{{ route('website.lawyer.details', $lawyer?->slug) }}">{{ ucfirst($lawyer?->name) }}</a>
                                        <p>{{ ucfirst($lawyer?->department?->name) }}</p>
                                        <p><span><i class="fas fa-graduation-cap"></i> {{ $lawyer?->designations }}</span>
                                        </p>
                                        <p><span><b><i class="fas fa-street-view"></i>
                                                    {{ ucfirst($lawyer?->location?->name) }}</b></span></p>
                                    </div>
                                    <div class="team-social">
                                        <ul>
                                            @foreach ($lawyer?->socialMedia as $socialMedia)
                                                <li><a target="_blank" aria-label="{{ $socialMedia?->link }}"
                                                        href="{{ $socialMedia?->link }}"><i class="{{$socialMedia?->icon}}"></i></a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <h3 class="text-danger text-center">{{ __('Lawyer Not Found') }}</h3>
                    @endif


                </div>
            </div>
        </div>
    @endif
@endsection
@push('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
