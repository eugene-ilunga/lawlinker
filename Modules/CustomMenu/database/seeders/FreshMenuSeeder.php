<?php

namespace Modules\CustomMenu\database\seeders;

use Illuminate\Database\Seeder;
use Modules\CustomMenu\app\Enums\AllMenus;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Models\MenuTranslation;
use Modules\Language\app\Models\Language;

class FreshMenuSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Menu list
        $menu_list = [
            [
                'name' => "Main Menu",
                'slug' => AllMenus::MAIN_MENU,
            ],
            [
                'name' => "Footer Menu One",
                'slug' => AllMenus::FOOTER_ONE_MENU,
            ],
            [
                'name' => "Footer Menu two",
                'slug' => AllMenus::FOOTER_TWO_MENU,
            ],
        ];

        foreach ($menu_list as $menu) {
            $data = new Menu();
            $data->name = $menu['name'];
            $data->slug = $menu['slug'];
            $data->save();
            if ($data->save()) {
                MenuTranslation::create([
                    'menu_id'   => $data->id,
                    'lang_code' => Language::first()?->code ?? 'en',
                    'name'      => $menu['name'],
                ]);
            }
        }
    }
}
