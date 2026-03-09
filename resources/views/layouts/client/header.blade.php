<!DOCTYPE html>
@if (session()->get('text_direction') == 'rtl')
    <html class="no-js" lang="en" dir="rtl">
@else
    <html class="no-js" lang="en">
@endif

<head>
    <!-- Meta Tags -->
    <meta name="viewport" content="width=device-width,initial-scale=1.0" />
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $appName = $setting->app_name;
        try {
            $routeName = str(Route::currentRouteName())
                ->replace('_', ' - ')
                ->replace('-', ' - ')
                ->replace('.', ' - ')
                ->title();
        } catch (\Exception $e) {
            info($e);
            $routeName = '';
        }
        $SeoTitle = $routeName . ' || ' . $appName;
    @endphp

    <!-- Title -->
    @yield('title')
    @yield('meta')

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset($setting->favicon) ?? '' }}">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    @include('layouts.client.style')
    @stack('css')
    @if (customCode()?->css)
        <style>
            {!! customCode()->css !!}
        </style>
    @endif

    @if ($setting->googel_tag_status == 'active')
        <!-- Google Tag Manager -->
        <script>
            (function(w, d, s, l, i) {
                w[l] = w[l] || [];
                w[l].push({
                    'gtm.start': new Date().getTime(),
                    event: 'gtm.js'
                });
                var f = d.getElementsByTagName(s)[0],
                    j = d.createElement(s),
                    dl = l != 'dataLayer' ? '&l=' + l : '';
                j.async = true;
                j.src =
                    'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
                f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', '{{ $setting->googel_tag_id }}');
            // Initialize Data Layer
            window.dataLayer = window.dataLayer || [];
        </script>
        <!-- End Google Tag Manager -->
    @endif

    @if ($setting->google_analytic_status == 'active')
        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $setting->google_analytic_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $setting->google_analytic_id }}');
        </script>
    @endif

    @if ($setting->pixel_status == 'active')
        <!-- Meta Pixel Code -->
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $setting->pixel_app_id }}');
            fbq('track', 'PageView');
        </script>

        <noscript><img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $setting->pixel_app_id }}&ev=PageView&noscript=1" /></noscript>
        <!-- End Meta Pixel Code -->
    @endif
    @if (customCode()?->header_javascript)
        <script>
            "use strict";
            {!! customCode()->header_javascript !!}
        </script>
    @endif

</head>

<body>
    <!--============================
    Google Tag Manager
    ==============================-->
    @if ($setting->googel_tag_status == 'active')
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $setting->googel_tag_id }}" height="0"
                width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
    @endif

    <!--Preloader Start-->
    @if ($setting->preloader == 1)
        <div id="preloader" class="preloader">
            <div class="status" style="background-image: url({{ url($setting->preloader_image) }})"></div>
        </div>
    @endif
    <!--Preloader End-->
    @if (customCode()?->body_javascript)
        <script>
            "use strict";
            {!! customCode()->body_javascript !!}
        </script>
    @endif

    <!--Header-Area Start-->
    <div class="header-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xxl-2 col-lg-3 col-12">
                    <div class="header-social">
                        <ul>
                            <li>
                                <div class="social-bar">
                                    <ul>
                                        @foreach (getSocialLinks() as $social)
                                            <li><a target="_blank" aria-label="{{ $social?->link }}"
                                                    href="{{ $social?->link }}">
                                                    <i class="{{ $social?->icon }}"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-xxl-10 col-lg-9 col-12">
                    <div class="header-info">
                        <ul>
                            @if ($contactInfo?->top_bar_email)
                                <li>
                                    <i class="far fa-envelope"></i>
                                    <span>{{ $contactInfo?->top_bar_email }}</span>
                                </li>
                            @endif
                            @if ($contactInfo?->top_bar_phone)
                                <li>
                                    <i class="fas fa-phone"></i>
                                    <span>+{{ $contactInfo?->top_bar_phone }}</span>
                                </li>
                            @endif
                            @if (allLanguages()?->where('status', 1)->count() > 1)
                                <li>
                                    <form id="setLanguageHeader" action="{{ route('set-language') }}" method="get">
                                        <select class="select_js" name="code">
                                            @forelse (allLanguages()?->where('status', 1) as $language)
                                                <option value="{{ $language->code }}"
                                                    {{ getSessionLanguage() == $language->code ? 'selected' : '' }}>
                                                    {{ $language->name }}
                                                </option>
                                            @empty
                                                <option value="en"
                                                    {{ getSessionLanguage() == 'en' ? 'selected' : '' }}>
                                                    {{ __('English') }}
                                                </option>
                                            @endforelse
                                        </select>
                                    </form>
                                </li>
                            @endif
                            @if (allCurrencies()?->where('status', 'active')->count() > 1)
                                <li>
                                    <form id="setCurrencyHeader" action="{{ route('set-currency') }}" method="get">
                                        <select class="select_js" name="currency">
                                            @forelse (allCurrencies()?->where('status', 'active') as $currency)
                                                <option value="{{ $currency->currency_code }}"
                                                    {{ getSessionCurrency() == $currency->currency_code ? 'selected' : '' }}>
                                                    {{ $currency->currency_name }}
                                                </option>
                                            @empty
                                                <option value="USD"
                                                    {{ getSessionCurrency() == 'USD' ? 'selected' : '' }}>
                                                    {{ __('USD') }}
                                                </option>
                                            @endforelse
                                        </select>
                                    </form>
                                </li>
                            @endif

                            @guest
                                <li>
                                    <a aria-label="{{ __('Login') }}" href="{{ url('login') }}"><i
                                            class="fas fa-user"></i></a>
                                </li>
                            @else
                                <li>
                                    <a aria-label="{{ __('Dashboard') }}" href="{{ route('dashboard') }}"><i
                                            class="fas fa-user"></i></a>
                                </li>
                            @endguest
                            <li>
                                <a aria-label="{{ __('Appointment List') }}" href="{{ route('client.payment') }}"><i
                                        class="fas fa-shopping-cart position-relative"></i><span
                                        class="badge bg-danger position-absolute shopping-cart">{{ Cart::count() > 0 ? Cart::count() : '' }}</span></a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Header-Area End-->


    <!--Menu Start-->
    <div id="strickymenu" class="menu-area">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-6">
                    <div class="logo flex">
                        <a aria-label="{{ $setting?->app_name }}" href="{{ url('/') }}"><img
                                src="{{ asset($setting->logo) }}" alt="{{ $setting?->app_name }}" loading="lazy"></a>
                    </div>
                </div>
                <div class="col-md-9 col-6">
                    <div class="main-menu">
                        <ul class="nav-menu">
                            @if ($public_menu = mainMenu())
                                @foreach ($public_menu as $menu)
                                    @php
                                        $is_child =
                                            isset($menu['child']) &&
                                            is_array($menu['child']) &&
                                            count($menu['child']) > 0;
                                    @endphp
                                    <li class="@if ($is_child) menu-item-has-children @endif"><a
                                            @if ($menu['open_new_tab']) target="_blank" @endif
                                            href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">{{ $menu['label'] }}</a>
                                        @if ($is_child)
                                            <ul class="sub-menu">
                                                @foreach ($menu['child'] as $child)
                                                    <li><a @if ($child['open_new_tab']) target="_blank" @endif
                                                            href="{{ $child['link'] == '#' || empty($child['link']) ? 'javascript:;' : url($child['link']) }}">{{ $child['label'] }}</a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </li>
                                @endforeach
                                <li class="special-button"><a href="javascript:;" aria-label="{{ __('Appointment') }}"
                                        data-bs-toggle="modal"
                                        data-bs-target="#appointment_modal">{{ __('Appointment') }}</a></li>
                            @else
                                <li><a aria-label="{{ __('Home') }}"
                                        href="{{ route('home') }}">{{ __('Home') }}</a></li>
                            @endif
                        </ul>
                    </div>

                    <!--Mobile Menu Icon Start-->
                    <div class="mobile-menuicon">
                        <span class="menu-bar" onclick="openNav()"><i class="fas fa-bars" aria-hidden="true"></i></span>
                    </div>
                    <!--Mobile Menu Icon End-->
                </div>
            </div>
        </div>
    </div>
    <!--Menu End-->
    @php
        $getDepartments = getDepartments();
    @endphp

    <!--Mobile Menu Start-->
    <div class="mobile-menu">
        <div id="mySidenav" class="sidenav">
            <a aria-label="{{ $setting?->app_name }}" href="{{ url('/') }}"><img
                    src="{{ url($setting->logo) }}" alt="{{ $setting?->app_name }}" loading="lazy"></a>
            <span class="closebtn" onclick="closeNav()">&times;</span>

            @if ($public_menu = mainMenu())
                <ul>
                    @foreach ($public_menu as $menu)
                        @php
                            $is_child = isset($menu['child']) && is_array($menu['child']) && count($menu['child']) > 0;
                        @endphp
                        <li class="@if ($is_child) menu-child @endif">
                            @if ($is_child)
                                <span>{{ $menu['label'] }}</span>
                                <ul>
                                    @foreach ($menu['child'] as $child)
                                        <li><a
                                                href="{{ $child['link'] == '#' || empty($child['link']) ? 'javascript:;' : url($child['link']) }}">{{ $child['label'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <a
                                    href="{{ $menu['link'] == '#' || empty($menu['link']) ? 'javascript:;' : url($menu['link']) }}">{{ $menu['label'] }}</a>
                            @endif
                        </li>
                    @endforeach
                    <li class="special-button"><a aria-label="{{ __('Appointment') }}" href="javascript:;"
                            data-bs-toggle="modal" data-bs-target="#appointment_modal1">{{ __('Appointment') }}</a>
                    </li>

                    <!-- Modal -->
                    <div class="modal fade" id="appointment_modal1" role="dialog"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLabel">{{ __('Create Appointment') }}
                                    </h5>
                                    <button type="button" class="close" data-bs-dismiss="modal"
                                        aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body book-appointment">

                                    <form action="{{ url('create-appointment') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="">{{ __('Select Department') }}</label>
                                            <select name="department_id" onchange="loadMobileModallawyer()"
                                                class="modal-department-id select_js">
                                                <option value="">{{ __('Select Department') }}</option>
                                                @foreach ($getDepartments as $item)
                                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                @endforeach

                                            </select>
                                        </div>

                                        <div class="form-group d-none" id="mobile-modal-lawyer-box">
                                            <label for="">{{ __('Select Lawyer') }}</label>
                                            <select name="" class="form-select modal-lawyer-id mySelect2Item"
                                                onchange="loadModalDate()">
                                                <option value="">{{ __('Select Lawyer') }}</option>
                                            </select>
                                        </div>
                                        <div class="form-group d-none" id="mobile-modal-date-box">
                                            <label for="">{{ __('Select Date') }}</label>
                                            <input type="text" name="date" class="form-control datepicker"
                                                id="mobile-modal-datepicker-value">
                                            <input type="hidden" name="lawyer_id" value=""
                                                id="mobile_modal_lawyer_id">
                                        </div>

                                        <div class="form-group d-none" id="mobile-modal-schedule-box">
                                            <label for="">{{ __('Select Schedule') }}</label>
                                            <select name="schedule_id" class="form-select mySelect2Item"
                                                id="available-mobile-modal-schedule">

                                            </select>
                                        </div>
                                        <div id="mobile-modal-schedule-error" class="d-none"></div>
                                        <div class="form-group">
                                            <button type="button" class="btn btn-danger"
                                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                                            <input type="submit" value="{{ __('Submit') }}"
                                                class="btn btn-primary" id="mobile-modal-sub" disabled>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                </ul>
            @endif
        </div>
    </div>
    <!--Mobile Menu End-->

    <!-- Modal -->
    <div class="modal fade" id="appointment_modal" role="dialog" aria-labelledby="exampleModalLabelTwo"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabelTwo">{{ __('Create Appointment') }}</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body book-appointment">
                    <form action="{{ url('create-appointment') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="">{{ __('Select Department') }}</label>
                            <select name="department_id" onchange="loadlawyer()" class="department-id modal_select2">
                                <option value="">{{ __('Select Department') }}</option>
                                @foreach ($getDepartments as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach

                            </select>
                        </div>
                        <div class="form-group d-none" id="modal-lawyer-box">
                            <label for="">{{ __('Select Lawyer') }}</label>
                            <select name="" class="lawyer-id form-select" onchange="loadDate()">
                                <option value="">{{ __('Select Lawyer') }}</option>
                            </select>
                        </div>
                        <div class="form-group d-none" id="modal-date-box">
                            <label for="">{{ __('Select Date') }}</label>
                            <input type="text" name="date" class="form-control datepicker"
                                id="modal-datepicker-value">
                            <input type="hidden" name="lawyer_id" value="" id="modal_lawyer_id">
                        </div>
                        <div class="form-group d-none" id="modal-schedule-box">
                            <label for="">{{ __('Select Schedule') }}</label>
                            <select name="schedule_id" class="form-select modal_select2"
                                id="available-modal-schedule">

                            </select>
                        </div>
                        <div id="modal-schedule-error" class="d-none"></div>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger"
                                data-bs-dismiss="modal">{{ __('Close') }}</button>
                            <input type="submit" value="{{ __('Submit') }}" class="btn btn-primary"
                                id="modal-sub" disabled>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
    <!-- // Modal -->
