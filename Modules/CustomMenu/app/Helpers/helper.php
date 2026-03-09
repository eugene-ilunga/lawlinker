<?php

use Illuminate\Support\Facades\Cache;
use Modules\CustomMenu\app\Enums\AllMenus;
use Modules\CustomMenu\app\Models\Menu;
use Modules\CustomMenu\app\Models\MenuItem;

if (!function_exists('mainMenu')) {
    function mainMenu() {
        return menuGetBySlug(AllMenus::MAIN_MENU);
    }
}
if (!function_exists('footerFirstMenu')) {
    function footerFirstMenu() {
        return menuGetBySlug(AllMenus::FOOTER_ONE_MENU);
    }
}
if (!function_exists('footerSecondMenu')) {
    function footerSecondMenu() {
        return menuGetBySlug(AllMenus::FOOTER_TWO_MENU);
    }
}

if (!function_exists('menuGetBySlug')) {
    function menuGetBySlug($slug) {
        $menu = Menu::bySlug($slug);
        return is_null($menu) ? [] : menuGetById($menu->id);
    }
}

if (!function_exists('menuGetById')) {
    function menuGetById($menu_id) {
        $menuItem = new MenuItem;
        $menu_list = $menuItem->getAll($menu_id);

        $roots = $menu_list->where('menu_id', (integer) $menu_id)->where('parent_id', 0);

        $items = menuTree($roots, $menu_list);
        return $items;
    }
}

if (!function_exists('menuTree')) {
    function menuTree($items, $all_items) {
        $data_arr = array();
        $i = 0;
        foreach ($items as $item) {
            $data_arr[$i] = $item->toArray();
            $find = $all_items->where('parent_id', $item->id);

            $data_arr[$i]['child'] = array();

            if ($find->count()) {
                $data_arr[$i]['child'] = menuTree($find, $all_items);
            }

            $i++;
        }

        return $data_arr;
    }
}

//for api
if (!function_exists('menuGetBySlugAndLang')) {
    function menuGetBySlugAndLang($slug, $code) {
        $menu = Menu::bySlug($slug);
        return is_null($menu) ? [] : menuGetByIdAndLang($menu->id,$code);
    }
}
if (!function_exists('menuGetByIdAndLang')) {
    function menuGetByIdAndLang($menu_id,$code) {
        $menuItem = new MenuItem;
        $menu_list = $menuItem->getAllAndLang($menu_id,$code);

        $roots = $menu_list->where('menu_id', (integer) $menu_id)->where('parent_id', 0);

        $items = menuTree($roots, $menu_list);
        return $items;
    }
}