@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Login') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Login') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Login') }}</span></li>
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
                                <button class="nav-link @if (request('type') != 'lawyer') active @endif" id="pills-home-tab"
                                    data-bs-toggle="pill" data-bs-target="#pills-home" type="button" role="tab"
                                    aria-controls="pills-home" aria-selected="true">{{ __('Client') }}</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link @if (request('type') == 'lawyer') active @endif"
                                    id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                                    type="button" role="tab" aria-controls="pills-profile"
                                    aria-selected="false">{{ __('Lawyer') }}</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            <div class="tab-pane fade @if (request('type') != 'lawyer') show active @endif" id="pills-home"
                                role="tabpanel" aria-labelledby="pills-home-tab" tabindex="0">
                                @if (enum_exists('App\Enums\SocialiteDriverType'))
                                    @php
                                        $socialiteEnum = 'App\Enums\SocialiteDriverType';
                                    @endphp
                                    @if ($setting->google_login_status == 'active')
                                        <a href="{{ route('auth.social', $socialiteEnum::GOOGLE->value) }}"
                                            class="account__social-btn">
                                            <img src="{{ asset($socialiteEnum::GOOGLE_ICON->value) }}"
                                                alt="img">{{ __('Continue with google') }}
                                        </a>
                                        <div class="account__divider">
                                            <span>{{ __('or') }}</span>
                                        </div>
                                    @endif
                                @endif
                                <div class="login-form">
                                    <form action="{{ route('client-login') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="email">{{ __('Email') }}</label>
                                                @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        value="{{old('email','client@gmail.com')}}">
                                                @else
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        value="{{ old('email') }}">
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label for="password">{{ __('Password') }}</label>
                                                @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                                    <input type="password" name="password" class="form-control"
                                                        id="password" value="1234">
                                                @else
                                                    <input type="password" name="password" class="form-control"
                                                        id="password">
                                                @endif
                                            </div>
                                            @if ($setting->recaptcha_status == 'active')
                                                <div class="col-12">
                                                    <div class="g-recaptcha"
                                                        data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <div class="remember d-flex flex-wrap justify-content-between">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="remember" type="checkbox" {{ old('remember') ? 'checked' : '' }}
                                                            id="remember">
                                                        <label class="form-check-label" for="remember">
                                                            {{ __('Remember Me') }}
                                                        </label>
                                                    </div>
                                                    <a href="{{ route('password.request') }}"
                                                        class="link text-right">{{ __('Forgot password?') }}</a>
                                                </div>
                                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    @if ($setting->client_can_register == 1)
                                        <p>{{ __('Dont have an account?') }} <a href="{{ route('register') }}"
                                                class="link">{{ __('Sign up') }}</a></p>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane fade @if (request('type') == 'lawyer') show active @endif"
                                id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab" tabindex="0">
                                <div class="login-form">
                                    <form action="{{ route('lawyer.login') }}" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <label for="lawyer_email">{{ __('Email') }}</label>
                                                @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                                    <input type="email" name="email" id="lawyer_email"
                                                        class="form-control" value="lawyer@gmail.com">
                                                @else
                                                    <input type="email" name="email" id="lawyer_email"
                                                        class="form-control" value="{{ old('email') }}">
                                                @endif
                                            </div>
                                            <div class="col-12">
                                                <label for="lawyer_password">{{ __('Password') }}</label>
                                                @if (app()->isLocal() && app()->hasDebugModeEnabled())
                                                    <input type="password" name="password" class="form-control"
                                                        id="lawyer_password" value="1234">
                                                @else
                                                    <input type="password" name="password" class="form-control"
                                                        id="lawyer_password">
                                                @endif
                                            </div>
                                            @if ($setting->recaptcha_status == 'active')
                                                <div class="col-12">
                                                    <div class="g-recaptcha"
                                                        data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-12">
                                                <div class="remember d-flex flex-wrap justify-content-between">
                                                    <div class="form-check">
                                                        <input class="form-check-input" name="lawyer_remember" type="checkbox" {{ old('lawyer_remember') ? 'checked' : '' }}
                                                            id="lawyer_remember">
                                                        <label class="form-check-label" for="lawyer_remember">
                                                            {{ __('Remember Me') }}
                                                        </label>
                                                    </div>
                                                    <a href="{{ route('password.request', ['type' => 'lawyer']) }}"
                                                        class="link text-right">{{ __('Forget your password?') }}</a>
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Login') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                    @if ($setting->lawyer_can_register == 1)
                                        <p>{{ __('Dont have an account?') }} <a
                                                href="{{ route('register', ['type' => 'lawyer']) }}"
                                                class="link">{{ __('Sign up') }}</a></p>
                                    @endif
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
