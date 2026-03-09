@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Register') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Register') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Register') }}</span></li>
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
                    <div class="login_area_bg">
                        <ul class="nav nav-pills" id="pills-tab" role="tablist">
                            @if ($setting->client_can_register)
                                @if (!$setting->lawyer_can_register)
                                    <h4 class="text-center">{{__('Register Now for Expert Legal Advice')}}</h4>
                                @else
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if (request('type') != 'lawyer') active @endif"
                                            id="pills-contact-tab" data-bs-toggle="pill" data-bs-target="#pills-contact"
                                            type="button" role="tab" aria-controls="pills-contact"
                                            aria-selected="false">{{ __('Client') }}</button>
                                    </li>
                                @endif
                            @endif
                            @if ($setting->lawyer_can_register)
                                @if (!$setting->client_can_register)
                                    <h4 class="text-center">{{__('Register Today to Join Our Medical Team!')}}</h4>
                                @else
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link @if (request('type') == 'lawyer') active @endif"
                                            id="pills-disabled-tab" data-bs-toggle="pill" data-bs-target="#pills-disabled"
                                            type="button" role="tab" aria-controls="pills-disabled"
                                            aria-selected="false">{{ __('Lawyer') }}</button>
                                    </li>
                                @endif
                            @endif
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @if ($setting->client_can_register)
                                <div class="tab-pane fade @if (request('type') != 'lawyer' || !$setting->lawyer_can_register) show active @endif"
                                    id="pills-contact" role="tabpanel" aria-labelledby="pills-contact-tab" tabindex="0">
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
                                    <div class="regiser-form login-form">
                                        <form action="{{ route('register') }}" method="POST">
                                            @csrf
                                            <div class="form-row row">
                                                <div class="col-12">
                                                    <label for="name">{{ __('Name') }}</label>
                                                    <input name="name" type="text" id="name" class="form-control"
                                                        value="{{ old('name') }}">
                                                </div>

                                                <div class="col-12">
                                                    <label for="email">{{ __('Email') }}</label>
                                                    <input type="email" name="email" id="email" class="form-control"
                                                        value="{{ old('email') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="password">{{ __('Password') }}</label>
                                                    <input type="password" name="password" class="form-control"
                                                        id="password">
                                                </div>
                                                <div class="col-12">
                                                    <label for="password_confirmation">{{ __('Confirm Password') }}</label>
                                                    <input type="password" name="password_confirmation" class="form-control"
                                                        id="password_confirmation">
                                                </div>
                                                @if ($setting->recaptcha_status == 'active')
                                                    <div class="col-12">
                                                        <div class="g-recaptcha"
                                                            data-sitekey="{{ $setting->recaptcha_site_key }}">
                                                        </div>
                                                    </div>
                                                @endif
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ __('Register') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <p>{{ __('Already have an account?') }} <a href="{{ route('login') }}"
                                                class="link">{{ __('Login') }}</a></p>
                                    </div>
                                </div>
                            @endif
                            @if ($setting->lawyer_can_register)
                                <div class="tab-pane fade @if (request('type') == 'lawyer' || !$setting->client_can_register) show active @endif"
                                    id="pills-disabled" role="tabpanel" aria-labelledby="pills-disabled-tab" tabindex="0">
                                    <div class="regiser-form login-form">
                                        <form action="{{ route('lawyer.register') }}" method="POST">
                                            @csrf
                                            <div class="form-row row">
                                                <div class="col-12">
                                                    <label for="lawyer_name">{{ __('Name') }}</label>
                                                    <input name="name" type="text" id="lawyer_name"
                                                        class="form-control" value="{{ old('name') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="designations">{{ __('Designations') }}</label>
                                                    <input name="designations" type="text" id="designations"
                                                        class="form-control" value="{{ old('designations') }}">
                                                </div>

                                                <div class="col-12">
                                                    <label for="lawyer_email">{{ __('Email') }}</label>
                                                    <input type="email" name="email" id="lawyer_email"
                                                        class="form-control" value="{{ old('email') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="lawyer_phone">{{ __('Phone') }}</label>
                                                    <input name="phone" type="text" id="lawyer_phone"
                                                        class="form-control" value="{{ old('phone') }}">
                                                </div>
                                                <div class="col-12">
                                                    <label for="lawyer_password">{{ __('Password') }}</label>
                                                    <input type="password" name="password" class="form-control"
                                                        id="lawyer_password">
                                                </div>
                                                <div class="col-12">
                                                    <label
                                                        for="lawyer_password_confirmation">{{ __('Confirm Password') }}</label>
                                                    <input type="password" name="password_confirmation"
                                                        class="form-control" id="lawyer_password_confirmation">
                                                </div>
                                                <div class="col-12">
                                                    <label for="department_id">{{ __('Select Department') }}</label>
                                                    <select class="form-select select2" name="department_id">
                                                        <option value="">{{ __('Select Department') }}</option>
                                                        @foreach ($departments as $department)
                                                            <option value="{{ $department->id }}"
                                                                @selected($department->id == old('department_id'))>{{ $department->name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-12">
                                                    <label for="location_id">{{ __('Select Location') }}</label>
                                                    <select class="form-select select2" name="location_id">
                                                        <option value="">{{ __('Select Location') }}</option>
                                                        @foreach ($locations as $location)
                                                            <option value="{{ $location->id }}"
                                                                @selected($location->id == old('location_id'))>
                                                                {{ $location->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                @if ($setting->recaptcha_status == 'active')
                                                    <div class="col-12">
                                                        <div class="g-recaptcha"
                                                            data-sitekey="{{ $setting->recaptcha_site_key }}"></div>
                                                    </div>
                                                @endif
                                                <div class="col-12">
                                                    <button type="submit"
                                                        class="btn btn-primary">{{ __('Register') }}</button>
                                                </div>
                                            </div>
                                        </form>
                                        <p>{{ __('Already have an account?') }} <a
                                                href="{{ route('login', ['type' => 'lawyer']) }}"
                                                class="link">{{ __('Login') }}</a></p>
                                    </div>
                                </div>
                            @endif
                        </div>
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
