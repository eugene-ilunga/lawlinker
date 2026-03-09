<?php

namespace Modules\CustomMenu\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Enums\AllMenus;
use Modules\CustomMenu\app\Models\MenuItem;
use Modules\CustomMenu\app\Models\MenuTranslation;
use Modules\CustomMenu\app\Models\MenuItemTranslation;

class CustomMenuDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        function processMenuItems($menuItems, $menuId, $parentId = 0) {
            foreach ($menuItems as $item) {
                $menuItem = new MenuItem();
                $menuItem->label = $item['translations'][0]['label'];
                $menuItem->link = $item['link'];
                $menuItem->menu_id = $menuId;
                $menuItem->parent_id = $parentId;
                $menuItem->sort = $item['sort'];

                if ($menuItem->save()) {
                    foreach ($item['translations'] as $translate_item) {
                        MenuItemTranslation::create([
                            'menu_item_id' => $menuItem->id,
                            'lang_code'    => $translate_item['lang_code'],
                            'label'        => $translate_item['label'],
                        ]);
                    }

                    if (isset($item['menu_items']) && is_array($item['menu_items'])) {
                        processMenuItems($item['menu_items'], $menuId, $menuItem->id);
                    }
                }
            }
        }
        // Menu list
        $menu_list = [
            [
                'slug'         => AllMenus::MAIN_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Main Menu'],
                    ['lang_code' => 'ar', 'name' => 'القائمة الرئيسية'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Home'],
                            ['lang_code' => 'ar', 'label' => 'بيت'],
                        ],
                    ],
                    [
                        'link'         => '/lawyers',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Lawyers'],
                            ['lang_code' => 'ar', 'label' => 'المحامون'],
                        ],
                    ],
                    [
                        'link'         => '/blog',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Blog'],
                            ['lang_code' => 'ar', 'label' => 'مدونة'],
                        ],
                    ],
                    [
                        'link'         => '/about-us',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'About Us'],
                            ['lang_code' => 'ar', 'label' => 'معلومات عنا'],
                        ],
                    ],
                    [
                        'link'         => '#',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Pages'],
                            ['lang_code' => 'ar', 'label' => 'الصفحات'],
                        ],
                        'menu_items'   => array_merge(
                            [
                                [
                                    'link'         => '/department',
                                    'sort'         => 1,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Department'],
                                        ['lang_code' => 'ar', 'label' => 'قسم'],
                                    ],
                                ],
                                [
                                    'link'         => '/service',
                                    'sort'         => 2,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Service'],
                                        ['lang_code' => 'ar', 'label' => 'خدمة'],
                                    ],
                                ],
                                [
                                    'link'         => '/testimonial',
                                    'sort'         => 3,
                                    'translations' => [
                                        ['lang_code' => 'en', 'label' => 'Testimonial'],
                                        ['lang_code' => 'ar', 'label' => 'شهادة'],
                                    ],
                                ],
                            ],
                        )
                    ],
                    [
                        'link'         => '/contact-us',
                        'sort'         => 6,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Contact Us'],
                            ['lang_code' => 'ar', 'label' => 'اتصل بنا'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => AllMenus::FOOTER_ONE_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Menu One'],
                    ['lang_code' => 'ar', 'name' => 'قائمة التذييل 1'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Home'],
                            ['lang_code' => 'ar', 'label' => 'بيت'],
                        ],
                    ],
                    [
                        'link'         => '/about-us',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'About Us'],
                            ['lang_code' => 'ar', 'label' => 'معلومات عنا'],
                        ],
                    ],
                    [
                        'link'         => '/department',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Department'],
                            ['lang_code' => 'ar', 'label' => 'قسم'],
                        ],
                    ],
                    [
                        'link'         => '/lawyers',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Lawyers'],
                            ['lang_code' => 'ar', 'label' => 'المحامون'],
                        ],
                    ],
                    [
                        'link'         => '/service',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Service'],
                            ['lang_code' => 'ar', 'label' => 'خدمة'],
                        ],
                    ],
                ],
            ],
            [
                'slug'         => AllMenus::FOOTER_TWO_MENU,
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Footer Menu two'],
                    ['lang_code' => 'ar', 'name' => 'قائمة التذييل 2'],
                ],
                'menu_items'   => [
                    [
                        'link'         => '/blog',
                        'sort'         => 1,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Blog'],
                            ['lang_code' => 'ar', 'label' => 'مدونة'],
                        ],
                    ],
                    [
                        'link'         => '/faq',
                        'sort'         => 2,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Faq'],
                            ['lang_code' => 'ar', 'label' => 'التعليمات'],
                        ],
                    ],
                    [
                        'link'         => '/contact-us',
                        'sort'         => 3,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Contact Us'],
                            ['lang_code' => 'ar', 'label' => 'اتصل بنا'],
                        ],
                    ],
                    [
                        'link'         => '/privacy-policy',
                        'sort'         => 4,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Privacy Policy'],
                            ['lang_code' => 'ar', 'label' => 'سياسة الخصوصية'],
                        ],
                    ],
                    [
                        'link'         => '/terms-condition',
                        'sort'         => 5,
                        'translations' => [
                            ['lang_code' => 'en', 'label' => 'Terms and Condition'],
                            ['lang_code' => 'ar', 'label' => 'أحكام وشروط'],
                        ],
                    ],
                ],
            ],
        ];

        foreach ($menu_list as $menu) {
            $data = new Menu();
            $data->name = $menu['translations'][0]['name'];
            $data->slug = $menu['slug'];

            if ($data->save()) {
                foreach ($menu['translations'] as $translate) {
                    MenuTranslation::create([
                        'menu_id'   => $data->id,
                        'lang_code' => $translate['lang_code'],
                        'name'      => $translate['name'],
                    ]);
                }

                if (isset($menu['menu_items']) && is_array($menu['menu_items'])) {
                    processMenuItems($menu['menu_items'], $data->id, 0);
                }
            }
        }
    }
}
