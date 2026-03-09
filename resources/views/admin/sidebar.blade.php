<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('admin.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('admin.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            @adminCan('dashboard.view')
                <li class="{{ isRoute('admin.dashboard', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-home"></i>
                        <span>{{ __('Dashboard') }}</span>
                    </a>
                </li>
            @endadminCan

            @if (Module::isEnabled('Order') && checkAdminHasPermission('order.management'))
                @include('order::sidebar')
            @endif
            @if (checkAdminHasPermission('appointment.view') ||
                    checkAdminHasPermission('payment.view') ||
                    checkAdminHasPermission('schedule.view') ||
                    checkAdminHasPermission('day.view'))
                @if (Module::isEnabled('Appointment'))
                    @include('appointment::sidebar')
                @endif
            @endif

            @if (Module::isEnabled('Customer') && checkAdminHasPermission('client.view'))
                @include('customer::sidebar')
            @endif

            @if (checkAdminHasPermission('lawyer.view') || checkAdminHasPermission('leave.management') ||
                    checkAdminHasPermission('department.view') ||
                    checkAdminHasPermission('location.view') ||
                    checkAdminHasPermission('service.view') ||
                    checkAdminHasPermission('blog.category.view') ||
                    checkAdminHasPermission('blog.view') || checkAdminHasPermission('blog.comment.view'))
                <li class="menu-header">{{ __('Manage Contents') }}</li>

                @if (Module::isEnabled('Lawyer') && (checkAdminHasPermission('lawyer.view') || checkAdminHasPermission('leave.management') || checkAdminHasPermission('location.view') || checkAdminHasPermission('department.view')))
                    @include('lawyer::sidebar')
                @endif

                @if (Module::isEnabled('Service') && checkAdminHasPermission('service.view'))
                    @include('service::sidebar')
                @endif

                @if (Module::isEnabled('Blog') && (checkAdminHasPermission('blog.category.view') || checkAdminHasPermission('blog.view') || checkAdminHasPermission('blog.comment.view')))
                    @include('blog::sidebar')
                @endif

            @endif

            @if (checkAdminHasPermission('menu.view') ||
                    checkAdminHasPermission('slider.view') ||
                    checkAdminHasPermission('feature.view') ||
                    checkAdminHasPermission('work.section.view') ||
                    checkAdminHasPermission('counter.view') ||
                    checkAdminHasPermission('partner.view') ||
                    checkAdminHasPermission('page.aboutus.view') ||
                    checkAdminHasPermission('page.faq.view') ||
                    checkAdminHasPermission('faq.view') ||
                    checkAdminHasPermission('social.link.management') || checkAdminHasPermission('faq.category.view') ||
                    checkAdminHasPermission('page.view') ||
                    checkAdminHasPermission('section.view'))

                <li class="menu-header">{{ __('Manage Website') }}</li>

                @if (Module::isEnabled('CustomMenu') && checkAdminHasPermission('menu.view'))
                    @include('custommenu::sidebar')
                @endif

                @if (Module::isEnabled('HomeSection') &&
                        (checkAdminHasPermission('slider.view') ||
                            checkAdminHasPermission('feature.view') ||
                            checkAdminHasPermission('work.section.view') ||
                            checkAdminHasPermission('counter.view') ||
                            checkAdminHasPermission('partner.view') ||
                            checkAdminHasPermission('section.view')))
                    @include('homesection::sidebar')
                @endif

                @if (checkAdminHasPermission('page.aboutus.view') || checkAdminHasPermission('page.faq.view') || checkAdminHasPermission('page.view'))
                    <li
                        class="nav-item dropdown {{ isRoute(['admin.pages.about-us.index', 'admin.pages.contact-page.index', 'admin.pages.faq-page.index', 'admin.custom-pages.*', 'admin.pages.utility-page.index', 'admin.social.media.*','admin.pages.faq.index'], 'active') }}">
                        <a href="javascript:;" class="nav-link has-dropdown">
                            <i class="fas fa-th"></i><span>{{ __('Pages') }}</span>
                        </a>
                        <ul class="dropdown-menu">
                            @if (checkAdminHasPermission('page.aboutus.view'))
                                <li class="{{ isRoute('admin.pages.about-us.index', 'active') }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.pages.about-us.index', ['code' => getSessionLanguage()]) }}">
                                        {{ __('About Us') }}
                                    </a>
                                </li>
                            @endif
                            @if (checkAdminHasPermission('page.faq.view'))
                                <li class="{{ isRoute('admin.pages.faq.index', 'active') }}">
                                    <a class="nav-link"
                                        href="{{ route('admin.pages.faq.index') }}">
                                        {{ __('FAQ Page') }}
                                    </a>
                                </li>
                            @endif
                            @if (Module::isEnabled('PageBuilder') && checkAdminHasPermission('page.view'))
                                @include('pagebuilder::sidebar')
                            @endif
                        </ul>
                    </li>
                @endif

                @if (Module::isEnabled('Faq') && checkAdminHasPermission('faq.category.view'))
                    @include('faq::sidebar')
                @endif

                @if (Module::isEnabled('SocialLink') && checkAdminHasPermission('social.link.management'))
                    @include('sociallink::sidebar')
                @endif

            @endif
            @if (checkAdminHasPermission('setting.view') ||
                    checkAdminHasPermission('basic.payment.view') ||
                    checkAdminHasPermission('currency.view') ||
                    checkAdminHasPermission('language.view') ||
                    checkAdminHasPermission('role.view') || 
                    checkAdminHasPermission('addon.view') ||
                    checkAdminHasPermission('admin.view'))

                <li class="menu-header">{{ __('Others') }}</li>

                @if (Module::isEnabled('GlobalSetting'))
                    <li class="{{ isRoute('admin.settings', 'active') }}">
                        <a class="nav-link" href="{{ route('admin.settings') }}"><i class="fas fa-cog"></i>
                            <span>{{ __('Settings') }}</span>
                        </a>
                    </li>
                @endif
            @endif

            @if (Module::isEnabled('App') && checkAdminHasPermission('app.management'))
                @include('app::sidebar')
            @endif

            @if (Module::isEnabled('PaymentWithdraw') && checkAdminHasPermission('withdraw.management'))
                @include('paymentwithdraw::admin.sidebar')
            @endif

            @if (Module::isEnabled('NewsLetter') && (checkAdminHasPermission('newsletter.view') || checkAdminHasPermission('newsletter.mail') || checkAdminHasPermission('newsletter.content.view')))
                @include('newsletter::sidebar')
            @endif

            @if (Module::isEnabled('Testimonial') && checkAdminHasPermission('testimonial.view'))
                @include('testimonial::sidebar')
            @endif

            @if (Module::isEnabled('ContactMessage') && (checkAdminHasPermission('contact.message.view') || checkAdminHasPermission('contact.info.view')))
                @include('contactmessage::sidebar')
            @endif
            <li class="nav-item dropdown {{ isRoute('admin.addon.*') ? 'active' : '' }}" id="addon_sidemenu">
                <a class="nav-link has-dropdown" data-toggle="dropdown" href="#"><i class="fas fa-gem"></i>
                    <span>{{ __('Manage Addons') }} </span>
                </a>
                <ul class="dropdown-menu addon_menu">
                    @includeIf('admin.addons')
                </ul>
            </li>
        </ul>
        <div class="py-3 text-center">
            <div class="btn-sm-group-vertical version_button" role="group" aria-label="Responsive button group">
                <button class="btn btn-primary logout_btn mt-2 text-capitalize" disabled>{{ __('version') }}
                    {{ $setting->version ?? '1.0.0' }}</button>
                <button class="btn btn-danger mt-2"
                    onclick="event.preventDefault(); $('#admin-logout-form').trigger('submit');"><i
                        class="fas fa-sign-out-alt"></i></button>
            </div>
        </div>
    </aside>
</div>
