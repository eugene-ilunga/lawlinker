<?php

namespace App\Traits;

use ReflectionClass;

trait PermissionsTrait {
    public static array $dashboardPermissions = [
        'group_name'  => 'dashboard',
        'permissions' => [
            'dashboard.view',
        ],
    ];

    public static array $adminProfilePermissions = [
        'group_name'  => 'admin profile',
        'permissions' => [
            'admin.profile.view',
            'admin.profile.update',
        ],
    ];
    public static array $sectionControlPermissions = [
        'group_name'  => 'section control',
        'permissions' => [
            'section.view',
            'section.manage',
        ],
    ];

    public static array $adminPermissions = [
        'group_name'  => 'admin',
        'permissions' => [
            'admin.view',
            'admin.create',
            'admin.store',
            'admin.edit',
            'admin.update',
            'admin.delete',
        ],
    ];
    public static array $orderPermissions = [
        'group_name'  => 'order management',
        'permissions' => [
            'order.management',
        ],
    ];
    public static array $leavePermissions = [
        'group_name'  => 'leave management',
        'permissions' => [
            'leave.management',
        ],
    ];
    public static array $zoomMeetingPermissions = [
        'group_name'  => 'zoom meeting',
        'permissions' => [
            'zoom.meeting',
        ],
    ];
    public static array $withdrawPermissions = [
        'group_name'  => 'withdraw management',
        'permissions' => [
            'withdraw.management',
        ],
    ];

    public static array $blogCatgoryPermissions = [
        'group_name'  => 'blog category',
        'permissions' => [
            'blog.category.view',
            'blog.category.create',
            'blog.category.translate',
            'blog.category.store',
            'blog.category.edit',
            'blog.category.update',
            'blog.category.delete',
        ],
    ];

    public static array $blogPermissions = [
        'group_name'  => 'blog',
        'permissions' => [
            'blog.view',
            'blog.create',
            'blog.translate',
            'blog.store',
            'blog.edit',
            'blog.update',
            'blog.delete',
        ],
    ];

    public static array $blogCommentPermissions = [
        'group_name'  => 'blog comment',
        'permissions' => [
            'blog.comment.view',
            'blog.comment.update',
            'blog.comment.delete',
        ],
    ];
    public static array $departmentPermissions = [
        'group_name'  => 'department',
        'permissions' => [
            'department.view',
            'department.create',
            'department.translate',
            'department.store',
            'department.edit',
            'department.update',
            'department.delete',
        ],
    ];
    public static array $lawyerPermissions = [
        'group_name'  => 'lawyer',
        'permissions' => [
            'lawyer.view',
            'lawyer.create',
            'lawyer.translate',
            'lawyer.store',
            'lawyer.edit',
            'lawyer.update',
            'lawyer.delete',
        ],
    ];
    public static array $locationPermissions = [
        'group_name'  => 'location',
        'permissions' => [
            'location.view',
            'location.store',
            'location.update',
            'location.delete',
        ],
    ];

    public static array $rolePermissions = [
        'group_name'  => 'role',
        'permissions' => [
            'role.view',
            'role.create',
            'role.store',
            'role.assign',
            'role.edit',
            'role.update',
            'role.delete',
        ],
    ];
    public static array $servicePermissions = [
        'group_name'  => 'service',
        'permissions' => [
            'service.view',
            'service.create',
            'service.translate',
            'service.store',
            'service.edit',
            'service.update',
            'service.delete',
        ],
    ];

    public static array $settingPermissions = [
        'group_name'  => 'setting',
        'permissions' => [
            'setting.view',
            'setting.update',
        ],
    ];

    public static array $basicPaymentPermissions = [
        'group_name'  => 'basic payment',
        'permissions' => [
            'basic.payment.view',
            'basic.payment.update',
        ],
    ];

    public static array $contectMessagePermissions = [
        'group_name'  => 'contact message',
        'permissions' => [
            'contact.message.view',
            'contact.message.delete',
            'contact.info.view',
            'contact.info.update',
        ],
    ];

    public static array $currencyPermissions = [
        'group_name'  => 'currency',
        'permissions' => [
            'currency.view',
            'currency.create',
            'currency.store',
            'currency.edit',
            'currency.update',
            'currency.delete',
        ],
    ];

    public static array $customerPermissions = [
        'group_name'  => 'customer',
        'permissions' => [
            'client.view',
            'client.bulk.mail',
            'client.update',
            'client.delete',
        ],
    ];

    public static array $languagePermissions = [
        'group_name'  => 'language',
        'permissions' => [
            'language.view',
            'language.create',
            'language.store',
            'language.edit',
            'language.update',
            'language.delete',
            'language.translate',
            'language.single.translate',
        ],
    ];

    public static array $menuPermissions = [
        'group_name'  => 'menu builder',
        'permissions' => [
            'menu.view',
            'menu.create',
            'menu.update',
            'menu.delete',
        ],
    ];

    public static array $pagePermissions = [
        'group_name'  => 'page builder',
        'permissions' => [
            'page.view',
            'page.create',
            'page.store',
            'page.edit',
            'page.update',
            'page.delete',
            'page.aboutus.view',
            'page.aboutus.manage',
            'page.faq.view',
            'page.faq.manage',
        ],
    ];

    public static array $paymentPermissions = [
        'group_name'  => 'payment',
        'permissions' => [
            'payment.view',
            'payment.update',
        ],
    ];
    public static array $socialPermission = [
        'group_name'  => 'social link management',
        'permissions' => [
            'social.link.management',
        ],
    ];

    public static array $newsletterPermissions = [
        'group_name'  => 'newsletter',
        'permissions' => [
            'newsletter.view',
            'newsletter.mail',
            'newsletter.delete',
            'newsletter.content.view',
            'newsletter.content.update',
        ],
    ];

    public static array $testimonialPermissions = [
        'group_name'  => 'testimonial',
        'permissions' => [
            'testimonial.view',
            'testimonial.create',
            'testimonial.translate',
            'testimonial.store',
            'testimonial.edit',
            'testimonial.update',
            'testimonial.delete',
        ],
    ];

    public static array $faqPermissions = [
        'group_name'  => 'faq',
        'permissions' => [
            'faq.view',
            'faq.store',
            'faq.update',
            'faq.delete',
        ],
    ];
    public static array $faqCatgoryPermissions = [
        'group_name'  => 'faq category',
        'permissions' => [
            'faq.category.view',
            'faq.category.create',
            'faq.category.translate',
            'faq.category.store',
            'faq.category.edit',
            'faq.category.update',
            'faq.category.delete',
        ],
    ];
    public static array $workSectionPermissions = [
        'group_name'  => 'work section',
        'permissions' => [
            'work.section.view',
            'work.section.update',
            'work.section.faq.view',
            'work.section.faq.store',
            'work.section.faq.update',
            'work.section.faq.delete',
        ],
    ];
    public static array $counterPermissions = [
        'group_name'  => 'counter',
        'permissions' => [
            'counter.view',
            'counter.store',
            'counter.update',
            'counter.delete',
        ],
    ];
    public static array $partnerPermissions = [
        'group_name'  => 'partner',
        'permissions' => [
            'partner.view',
            'partner.store',
            'partner.update',
            'partner.delete',
        ],
    ];
    public static array $schedulePermissions = [
        'group_name'  => 'schedule',
        'permissions' => [
            'schedule.view',
            'schedule.store',
            'schedule.update',
            'schedule.delete',
        ],
    ];
    public static array $appointmentPermissions = [
        'group_name'  => 'appointment',
        'permissions' => [
            'appointment.view',
        ],
    ];
    public static array $appPermissions = [
        'group_name'  => 'app management',
        'permissions' => [
            'app.management',
        ],
    ];
    public static array $sliderPermissions = [
        'group_name'  => 'slider',
        'permissions' => [
            'slider.view',
            'slider.store',
            'slider.update',
            'slider.delete',
        ],
    ];
    public static array $dayPermissions = [
        'group_name'  => 'day',
        'permissions' => [
            'day.view',
            'day.update',
        ],
    ];
    public static array $featurePermissions = [
        'group_name'  => 'feature',
        'permissions' => [
            'feature.view',
            'feature.store',
            'feature.update',
            'feature.delete',
        ],
    ];
    public static array $addonsPermissions = [
        'group_name' => 'Addons',
        'permissions' => [
            'addon.view',
            'addon.install',
            'addon.update',
            'addon.status.change',
            'addon.remove',
        ],
    ];
    private static function getSuperAdminPermissions(): array {
        $reflection = new ReflectionClass(__TRAIT__);
        $properties = $reflection->getStaticProperties();

        $permissions = [];
        foreach ($properties as $value) {
            if (is_array($value)) {
                $permissions[] = [
                    'group_name'  => $value['group_name'],
                    'permissions' => (array) $value['permissions'],
                ];
            }
        }

        return $permissions;
    }
}
