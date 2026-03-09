@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Forget Password') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Forget Password') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Forget Password') }}</span></li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Login Start-->
    <div class="login-area pt_70 pb_70">
        <div class="container">
            <div class="row">
                @if (session()->get('text_direction') == 'rtl')
                    <div class="col-lg-4"></div>
                @endif
                <div class="col-md-8 col-lg-6 col-xl-5 col-xxl-4 m-auto">
                    <div class="login_area_bg">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(request('type') != 'lawyer') active @endif" id="pills-home-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-home" type="button" role="tab" aria-controls="pills-home"
                                    aria-selected="true">{{ __('Client') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if(request('type') == 'lawyer') active @endif" id="pills-profile-tab" data-bs-toggle="pill"
                                    data-bs-target="#pills-profile" type="button" role="tab"
                                    aria-controls="pills-profile" aria-selected="false">{{ __('Lawyer') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade @if(request('type') != 'lawyer') show active @endif" id="pills-home" role="tabpanel"
                                aria-labelledby="pills-home-tab" tabindex="0">
                                <div class="login-form">
                                    <form action="{{ route('forget-password') }}" method="POST">
                                        @csrf
                                        <div class="form-row row">
                                            <div class="col-12">
                                                <label for="email">{{ __('Email') }}</label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ old('email') }}">
                                            </div>
                                            <div class="col-12"></div>
                                            @if ($setting->recaptcha_status == 'active')
                                                <div class="col-12">
                                                    <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">{{ __('Forgot Password') }}</button>
                                            </div>
            
                                        </div>
                                    </form>
            
                                    <div class="col-12">
                                        <p>{{ __('Go to login page') }} <a href="{{ route('login') }}" class="link">{{ __('Login') }}</a></p>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade @if(request('type') == 'lawyer') show active @endif" id="pills-profile" role="tabpanel"
                                aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="login-form">
                                    <form action="{{ route('lawyer.forget-password') }}" method="POST">
                                        @csrf
                                        <div class="form-row row">
                                            <div class="col-12">
                                                <label for="lawyer_email">{{ __('Email') }}</label>
                                                <input type="email" name="email" id="lawyer_email" class="form-control"
                                                    value="{{ old('email') }}">
                                            </div>
                                            <div class="col-12"></div>
                                            @if ($setting->recaptcha_status == 'active')
                                                <div class="col-12">
                                                    <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">{{ __('Forgot Password') }}</button>
                                            </div>
            
                                        </div>
                                    </form>
            
                                    <div class="col-12">
                                        <p>{{ __('Go to login page') }} <a href="{{ route('login',['type'=>'lawyer']) }}" class="link">{{ __('Login') }}</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Login End-->
@endsection
@push('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
