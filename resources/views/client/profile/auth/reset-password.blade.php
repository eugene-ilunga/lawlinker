@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Reset Password') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Reset Password') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Reset Password') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Register Start-->
    <div class="register-area pt_70 pb_70">
        <div class="container wow fadeIn">
            <div class="row">
                @if (session()->get('text_direction') == 'rtl')
                    <div class="col-lg-3"></div>
                @endif
                <div class="col-md-8 col-lg-6 col-xl-5 col-xxl-4 m-auto">
                    <div class="regiser-form login-form login_area_bg">
                        <form action="{{ route('reset-password-store', $token) }}" method="POST">
                            @csrf
                            <div class="form-row row">
                                <div class="col-12">
                                    <label for="email">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ $user->email }}">
                                </div>

                                <div class="col-12">
                                    <label for="password">{{ __('Password') }}</label>
                                    <input type="password" name="password" class="form-control" id="password">
                                </div>
                                <div class="col-12">
                                    <label for="password-confirmation">{{ __('Confirm Password') }}</label>
                                    <input type="password" name="password_confirmation" class="form-control"
                                        id="password-confirmation">
                                </div>
                                @if ($setting->recaptcha_status == 'active')
                                    <div class="col-12">
                                        <div class="g-recaptcha" data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                    </div>
                                @endif

                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">{{ __('Reset Password') }}</button>
                                </div>
                            </div>
                        </form>
                        <p>{{ __('Go to login page') }} <a href="{{ route('login') }}" class="link">{{ __('Login') }}</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Register End-->
@endsection
@push('js')
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
@endpush
