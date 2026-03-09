@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Contact')->first()->seo_title ?? 'Contact | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Contact')->first()->seo_description ?? 'Contact | LawMent' }}">
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ $contactInfo?->header }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ $contactInfo?->header }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Form Start-->
    <div class="contauct-style1  pt_50 pb_65">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="about1-text mt_30">
                        <h1>{{ $contactInfo?->header }}</h1>
                        <p class="mb_30">
                            {{ $contactInfo?->description }}
                        </p>
                    </div>
                    <form action="{{ route('send-contact-message') }}" method="POST">
                        @csrf
                        <div class="row contact-form">
                            <div class="col-md-6 form-group">
                                <label>{{ __('Name') }} *</label>
                                <input type="text" class="form-control" id="name" name="name">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Email') }} *</label>
                                <input type="email" id="email" class="form-control" name="email">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Phone') }}</label>
                                <input type="text" id="phone" name="phone" class="form-control">
                            </div>
                            <div class="col-md-6 form-group">
                                <label>{{ __('Subject') }} *</label>
                                <input type="text" id="subject" class="form-control" name="subject">
                            </div>
                            <div class="col-lg-12 form-group">
                                <label>{{ __('Message') }} *</label>
                                <textarea name="message" class="form-control" id="massege"></textarea>
                            </div>
                            @if ($setting->recaptcha_status == 'active')
                                <div class="form-group col-12">
                                    <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                </div>
                            @endif
                            <div class="col-md-12 form-group">
                                <button type="submit" class="btn">{{ __('Submit') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-lg-5 contact_margin">
                    <div class="contact-info-item bg1">
                        <div class="contact-info">
                            <span>
                                <i class="fas fa-phone"></i> {{ __('Phone') }}:
                            </span>
                            <div class="contact-text">
                                <a aria-label="{{ $contactInfo?->phone }}" href="tel:{!! nl2br($contactInfo?->phone) !!}">
                                    {!! nl2br($contactInfo?->phone) !!}</a>
                                <br>
                            </div>
                        </div>
                    </div>
                    <div class="contact-info-item bg2">
                        <div class="contact-info">
                            <span>
                                <i class="far fa-envelope"></i> {{ __('Email Address') }}:
                            </span>
                            <div class="contact-text">
                                <a aria-label="{{ $contactInfo?->email }}" href="mailto:{!! nl2br(e($contactInfo?->email)) !!}">{!! nl2br(e($contactInfo?->email)) !!}</a>

                            </div>
                        </div>
                    </div>
                    <div class="contact-info-item bg3">
                        <div class="contact-info">
                            <span>
                                <i class="fas fa-map-marker-alt"></i> {{ __('Address') }}:
                            </span>
                            <div class="contact-text">
                                <p>
                                    {!! nl2br($contactInfo?->address) !!}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Form End-->

    <!--Google map start-->
    <div class="map-area">
        {!! $contactInfo?->map_embed_code !!}
    </div>
    <!--Google map end-->
@endsection
@push('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
