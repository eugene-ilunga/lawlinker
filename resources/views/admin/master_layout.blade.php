@php
    $header_admin = Auth::guard('admin')->user();
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="" type="image/x-icon">
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">

    @yield('title')
    <link rel="icon" href="{{ asset($setting->favicon) }}">
    @include('backend_layouts.partials.styles')
    @stack('css')
</head>

<body>
    <div id="app">
        <div class="main-wrapper">
            <div class="navbar-bg"></div>
            <nav class="navbar navbar-expand-lg main-navbar px-3 py-2">
                <div class="form-inline">
                    <ul class="navbar-nav d-flex align-items-center">
                        <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i
                                    class="fas fa-bars"></i></a></li>

                        {{-- language select --}}
                        @include('backend_layouts.partials.language_select')
                        {{-- currency select --}}
                        @include('backend_layouts.partials.currency_select')
                    </ul>
                </div>
                <div class="me-auto search-box position-relative">
                    <x-admin.form-input id="search_menu" :placeholder="__('Search option')" />
                    <div id="admin_menu_list" class="position-absolute d-none rounded-2">
                        @foreach (App\Enums\RouteList::getAll() as $route_item)
                            @if (checkAdminHasPermission($route_item?->permission) || empty($route_item?->permission))
                                <a @isset($route_item->tab)
                                        data-active-tab="{{ $route_item->tab }}" class="border-bottom search-menu-item"
                                    @else
                                        class="border-bottom"
                                    @endisset
                                    href="{{ $route_item?->route }}">{{ $route_item?->name }}</a>
                            @endif
                        @endforeach
                        <a class="not-found-message d-none" href="javascript:;">{{ __('Not Found!') }}</a>
                    </div>
                </div>
                <ul class="navbar-nav">
                    <li class="dropdown dropdown-list-toggle">
                        <a target="_blank" href="{{ route('home') }}" class="nav-link nav-link-lg p-0">
                            <i class="fas fa-home"></i> <span
                                class="d-md-none d-lg-inline-block">{{ __('Visit Website') }}</span></i>
                        </a>
                    </li>

                    <li class="dropdown"><a href="javascript:;" data-bs-toggle="dropdown"
                            class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                            <img alt="image"
                                src="{{ !empty($header_admin->image) ? asset($header_admin->image) : asset($setting->default_avatar) }}"
                                class="me-1 my-1 rounded-circle">

                            <div class="d-sm-none d-lg-inline-block">{{ $header_admin->name }}</div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            @adminCan('admin.profile.view')
                                <a href="{{ route('admin.edit-profile') }}"
                                    class="dropdown-item has-icon d-flex align-items-center {{ isRoute('admin.edit-profile', 'text-primary') }}">
                                    <i class="far fa-user"></i> {{ __('Profile') }}
                                </a>
                            @endadminCan
                            @adminCan('setting.view')
                                <a href="{{ route('admin.settings') }}"
                                    class="dropdown-item has-icon d-flex align-items-center {{ isRoute('admin.settings', 'text-primary') }}">
                                    <i class="fas fa-cog"></i> {{ __('Setting') }}
                                </a>
                            @endadminCan
                            <a href="javascript:;" class="dropdown-item has-icon d-flex align-items-center"
                                onclick="event.preventDefault(); $('#admin-logout-form').trigger('submit');">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                            </a>
                        </div>
                    </li>

                </ul>
            </nav>

            @if (request()->routeIs(
                    'admin.general-setting',
                    'admin.crediential-setting',
                    'admin.email-configuration',
                    'admin.edit-email-template',
                    'admin.currency.*',
                    'admin.seo-setting',
                    'admin.custom-code',
                    'admin.cache-clear',
                    'admin.database-clear',
                    'admin.system-update.index',
                    'admin.addons.*',
                    'admin.admin.*',
                    'admin.languages.*',
                    'admin.basicpayment',
                    'admin.paymentgateway',
                    'admin.role.*'))
                @include('admin.settings.sidebar')
            @else
                @include('admin.sidebar')
            @endif
            @yield('admin-content')

            <footer class="main-footer">
                <div class="footer-right">
                    <span class="text-capitalize">{{ __('version') }}: {{ $setting->version ?? '' }}</span>
                </div>
            </footer>

        </div>
    </div>

    {{-- start admin logout form --}}
    <form id="admin-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
        @csrf
    </form>
    {{-- end admin logout form --}}
    @include('backend_layouts.partials.javascripts')

    @stack('js')

</body>

</html>
