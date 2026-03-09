@php
    $header_lawyer = Auth::guard('lawyer')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @yield('title')
    <link rel="icon" href="{{ asset($setting->favicon) }}">
    @include('backend_layouts.partials.styles')
    @stack('css')
</head>

<body>
    <div id="app" class="lawyer_dashboard_layout">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                <div class="me-auto form-inline">
                    <ul class="me-3 navbar-nav d-flex align-items-center">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>
                        {{-- language select --}}
                        @include('backend_layouts.partials.language_select')
                        {{-- currency select --}}
                        @include('backend_layouts.partials.currency_select')
                    </ul>
                </div>
                <ul class="navbar-nav lawyer_nav">
                    <li class="dropdown dropdown-list-toggle">
                        <a target="_blank" href="{{ route('home') }}" class="nav-link nav-link-lg">
                            <i class="fas fa-home"></i> {{ __('Visit Website') }}</i>
                        </a>
                    </li>

                    <li class="dropdown"><a href="javascript:;" data-bs-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            @if ($header_lawyer->image)
                                <img alt="image" src="{{ asset($header_lawyer->image) }}"
                                    class="me-1 rounded-circle">
                            @else
                                <img alt="image" src="{{ asset($setting->default_avatar) }}"
                                    class="me-1 rounded-circle">
                            @endif

                            <div class="d-sm-none d-lg-inline-block">{{ $header_lawyer->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="{{ route('lawyer.edit-profile', ['code' => getSessionLanguage()]) }}"
                                class="dropdown-item has-icon d-flex align-items-center {{ isroute('lawyer.edit-profile', 'text-primary') }}">
                                <i class="far fa-user"></i> {{ __('Profile') }}
                            </a>
                            <a href="{{ route('lawyer.change-password') }}"
                                class="dropdown-item has-icon d-flex align-items-center {{ isroute('lawyer.change-password', 'text-primary') }}">
                                <i class="fas fa-key"></i> {{ __('Change Password') }}
                            </a>
                            <a href="javascript:;" class="dropdown-item has-icon d-flex align-items-center"
                                onclick="event.preventDefault(); $('#lawyer-logout-form').trigger('submit');">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>


            @include('lawyer.sidebar')

            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-right">
                    <p>{{ $contactInfo?->copyright }}</p>
                </div>
            </footer>
        </div>
    </div>

    {{-- start admin logout form --}}
    <form id="lawyer-logout-form" action="{{ route('lawyer.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    {{-- end admin logout form --}}
    @include('backend_layouts.partials.javascripts')

    @stack('js')

</body>

</html>
