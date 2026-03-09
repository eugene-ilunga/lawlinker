<?php

namespace App\Enums;

enum RouteList {
    public static function getAll():object {
        $route_list = [
            (object)[
                'name'       => __('Dashboard'),
                'route'      => route('admin.dashboard'),
                'permission' => 'dashboard.view',
            ],
            (object)[
                'name'       => __('Profile'),
                'route'      => route('admin.edit-profile'),
                'permission' => 'admin.profile.view',
            ],
            (object)[
                'name'       => __('Order History'),
                'route'      => route('admin.orders'),
                'permission' => 'order.management',
            ],
            (object)[
                'name'       => __('Pending Order'),
                'route'      => route('admin.pending-orders'),
                'permission' => 'order.management',
            ],
            (object)[
                'name'       => __('All Appointment'),
                'route'      => route('admin.appointment.index'),
                'permission' => 'appointment.view',
            ],
            (object)[
                'name'       => __('New Appointments'),
                'route'      => route('admin.appointment.new'),
                'permission' => 'appointment.view',
            ],
            (object)[
                'name'       => __('Pending Appointments'),
                'route'      => route('admin.appointment.pending'),
                'permission' => 'appointment.view',
            ],
            (object)[
                'name'       => __('Payment History'),
                'route'      => route('admin.payment.history'),
                'permission' => 'payment.view',
            ],
            (object)[
                'name'       => __('Schedules'),
                'route'      => route('admin.schedule.index', ['code' => getSessionLanguage()]),
                'permission' => 'schedule.view',
            ],
            (object)[
                'name'       => __('Days'),
                'route'      => route('admin.day.index', ['code' => getSessionLanguage()]),
                'permission' => 'day.view',
            ],
            (object)[
                'name'       => __('Upcomming Meeting'),
                'route'      => route('admin.upcomming-meeting'),
                'permission' => 'appointment.view',
            ],
            (object)[
                'name'       => __('Previous Meeting'),
                'route'      => route('admin.previous-meeting'),
                'permission' => 'appointment.view',
            ],
            (object)[
                'name'       => __('All Clients'),
                'route'      => route('admin.all-customers'),
                'permission' => 'client.view',
            ],
            (object)[
                'name'       => __('Active Client'),
                'route'      => route('admin.active-customers'),
                'permission' => 'client.view',
            ],
            (object)[
                'name'       => __('Non verified'),
                'route'      => route('admin.non-verified-customers'),
                'permission' => 'client.view'],
            (object)[
                'name'       => __('Banned Client'),
                'route'      => route('admin.banned-customers'),
                'permission' => 'client.view',
            ],
            (object)[
                'name'       => __('Client Send bulk mail'),
                'route'      => route('admin.send-bulk-mail'),
                'permission' => 'client.view',
            ],
            (object)[
                'name'       => __('Department'),
                'route'      => route('admin.department.index'),
                'permission' => 'department.view',
            ],
            (object)[
                'name'       => __('Location'),
                'route'      => route('admin.location.index', ['code' => getSessionLanguage()]),
                'permission' => 'location.view',
            ],
            (object)[
                'name'       => __('Lawyer'),
                'route'      => route('admin.lawyer.index'),
                'permission' => 'lawyer.view',
            ],
            (object)[
                'name'       => __('Services'),
                'route'      => route('admin.service.index'),
                'permission' => 'service.view',
            ],
            (object)[
                'name'       => __('Category List'),
                'route'      => route('admin.blog-category.index'),
                'permission' => 'blog.category.view',
            ],
            (object)[
                'name'       => __('Post List'),
                'route'      => route('admin.blogs.index'),
                'permission' => 'blog.view',
            ],
            (object)[
                'name'       => __('Post Comments'),
                'route'      => route('admin.blog-comment.index'),
                'permission' => 'blog.comment.view',
            ],
            (object)[
                'name'       => __('Menu Builder'),
                'route'      => route('admin.custom-menu.index'),
                'permission' => 'menu.view',
            ],
            (object)[
                'name'       => __('Sliders'),
                'route'      => route('admin.slider.index'),
                'permission' => 'slider.view',
            ],
            (object)[
                'name'       => __('Features'),
                'route'      => route('admin.feature.index'),
                'permission' => 'feature.view',
            ],
            (object)[
                'name'       => __('Work Section'),
                'route'      => route('admin.work-section.index', ['code' => getSessionLanguage()]),
                'permission' => 'work.section.view',
            ],
            (object)[
                'name'       => __('Overview'),
                'route'      => route('admin.counter.index'),
                'permission' => 'counter.view',
            ],
            (object)[
                'name'       => __('Partners'),
                'route'      => route('admin.partner.index'),
                'permission' => 'partner.view',
            ],
            (object)[
                'name'       => __('Section Controls'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'feature_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Feature Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'feature_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Work Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'work_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Service Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'service_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Department Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'department_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Testimonial Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'client_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Lawyer Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'lawyer_tab',
            ],
            (object)[
                'name'       => __('Section Controls') . ' > ' . __('Blog Section'),
                'route'      => route('admin.section-control.index', ['code' => getSessionLanguage()]),
                'permission' => 'section.view',
                'tab'        => 'blog_tab',
            ],
            (object)[
                'name'       => __('About Us'),
                'route'      => route('admin.pages.about-us.index', ['code' => getSessionLanguage()]),
                'permission' => 'page.aboutus.view',
            ],
            (object)[
                'name'       => __('Customizable Page'),
                'route'      => route('admin.custom-pages.index'),
                'permission' => 'page.view',
            ],
            (object)[
                'name'       => __('FAQS'),
                'route'      => route('admin.faq-category.index'),
                'permission' => 'faq.view',
            ],
            (object)[
                'name'       => __('Social Links'),
                'route'      => route('admin.social-link.index'),
                'permission' => 'social.link.management',
            ],
            (object)[
                'name'       => __('Withdraw Method'),
                'route'      => route('admin.withdraw-method.index'),
                'permission' => 'withdraw.management',
            ],
            (object)[
                'name'       => __('Withdraw List'),
                'route'      => route('admin.withdraw-list'),
                'permission' => 'withdraw.management',
            ],
            (object)[
                'name'       => __('Pending Withdraw'),
                'route'      => route('admin.pending-withdraw-list'),
                'permission' => 'withdraw.management',
            ],
            (object)[
                'name'       => __('Subscriber List'),
                'route'      => route('admin.subscriber-list'),
                'permission' => 'newsletter.view',
            ],
            (object)[
                'name'       => __('Subscriber Send bulk mail'),
                'route'      => route('admin.send-mail-to-newsletter'),
                'permission' => 'newsletter.mail',
            ],
            (object)[
                'name'       => __('Subscriber Content'),
                'route'      => route('admin.subscriber-content', ['code' => getSessionLanguage()]),
                'permission' => 'newsletter.content.view',
            ],
            (object)[
                'name'       => __('Testimonial'),
                'route'      => route('admin.testimonial.index'),
                'permission' => 'testimonial.view',
            ],
            (object)[
                'name'       => __('Invoice Contact'),
                'route'      => route('admin.prescription-contact'),
                'permission' => 'contact.info.view',
            ],
            (object)[
                'name'       => __('Contact Messages'),
                'route'      => route('admin.contact-messages'),
                'permission' => 'contact.message.view',
            ],
            (object)[
                'name'       => __('Contact Info'),
                'route'      => route('admin.contact-info', ['code' => getSessionLanguage()]),
                'permission' => 'contact.info.view',
            ],
            (object)[
                'name'       => __('App Settings') . ' > ' . __('On Boarding Screens'),
                'route'      => route('admin.app.screen.index'),
                'permission' => 'app.management',
                'tab'        => 'on_boarding_tab',
            ],
            (object)[
                'name'       => __('App Settings') . ' > ' . __('Banner Image'),
                'route'      => route('admin.app.screen.index'),
                'permission' => 'app.management',
                'tab'        => 'banner_tab',
            ],
            (object)[
                'name'       => __('General Settings'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'general_tab',
            ],
            (object)[
                'name'       => __('Blog Comment'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'comment_tab',
            ],
            (object)[
                'name'       => __('Time & Date Setting'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'website_tab',
            ],
            (object)[
                'name'       => __('Logo & Favicon'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'logo_favicon_tab',
            ],
            (object)[
                'name'       => __('Preloader'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'preloader_tab',
            ],
            (object)[
                'name'       => __('Theme Color'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'color_tab',
            ],
            (object)[
                'name'       => __('Cookie Consent'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'cookie_consent_tab',
            ],
            (object)[
                'name'       => __('Custom Pagination'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'custom_pagination_tab',
            ],
            (object)[
                'name'       => __('Default avatar'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'default_avatar_tab',
            ],
            (object)[
                'name'       => __('Breadcrumb image'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'breadcrump_img_tab',
            ],
            (object)[
                'name'       => __('404 Page'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'error_page_img_tab',
            ],
            (object)[
                'name'       => __('Maintenance Mode'),
                'route'      => route('admin.general-setting'),
                'permission' => 'setting.view',
                'tab'        => 'mmaintenance_mode_tab',
            ],
            (object)[
                'name'       => __('Credential Settings'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_recaptcha_tab',
            ],
            (object)[
                'name'       => __('Google reCaptcha'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_recaptcha_tab'],
            (object)[
                'name'       => __('Google Tag Manager'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'googel_tag_tab',
            ],
            (object)[
                'name'       => __('Google Analytic'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'google_analytic_tab',
            ],
            (object)[
                'name'       => __('Facebook Pixel'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'facebook_pixel_tab',
            ],
            (object)[
                'name'       => __('Social Login'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'social_login_tab',
            ],
            (object)[
                'name'       => __('Tawk Chat'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'tawk_chat_tab',
            ],
            (object)[
                'name'       => __('Pusher'),
                'route'      => route('admin.crediential-setting'),
                'permission' => 'setting.view',
                'tab'        => 'pusher_tab',
            ],
            (object)[
                'name'       => __('Email Configuration'),
                'route'      => route('admin.email-configuration'),
                'permission' => 'setting.view',
                'tab'        => 'setting_tab',
            ],
            (object)[
                'name'       => __('Email Template'),
                'route'      => route('admin.email-configuration'),
                'permission' => 'setting.view',
                'tab'        => 'email_template_tab',
            ],
            (object)[
                'name'       => __('SEO Setup'),
                'route'      => route('admin.seo-setting'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('Custom CSS'),
                'route'      => route('admin.custom-code','css'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('Custom JS'),
                'route'      => route('admin.custom-code','js'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('Clear cache'),
                'route'      => route('admin.cache-clear'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('Database Clear'),
                'route'      => route('admin.database-clear'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('System Update'),
                'route'      => route('admin.system-update.index'),
                'permission' => 'setting.view',
            ],
            (object) [
                'name' => __('Manage Addons'),
                'route' => route('admin.addons.view'),
                'permission' => 'setting.view',
            ],
            (object)[
                'name'       => __('Manage Language'),
                'route'      => route('admin.languages.index'),
                'permission' => 'language.view',
            ],
            (object)[
                'name'       => __('Payment Gateway'),
                'route'      => route('admin.basicpayment'),
                'permission' => 'basic.payment.view',
            ],
            (object)[
                'name'       => __('Multi Currency'),
                'route'      => route('admin.currency.index'),
                'permission' => 'currency.view',
            ],
            (object)[
                'name'       => __('Manage Admin'),
                'route'      => route('admin.admin.index'),
                'permission' => 'admin.view',
            ],
            (object)[
                'name'       => __('Role & Permissions'),
                'route'      => route('admin.role.index'),
                'permission' => 'role.view',
            ],
        ];
        return (object) $route_list;
    }
}