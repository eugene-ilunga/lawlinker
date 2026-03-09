<?php

namespace Modules\CustomMenu\app\Enums;

use Illuminate\Support\Collection;

enum DefaultMenusEnum: string {
    public static function getAll(): Collection {

        $all_default_menus = [
            (object) ['name' => __('About Us'), 'url' => '/about-us'],
            (object) ['name' => __('Blog'), 'url' => '/blog'],
            (object) ['name' => __('Contact'), 'url' => '/contact-us'],
            (object) ['name' => __('Lawyer'), 'url' => '/lawyers'],
            (object) ['name' => __('Department'), 'url' => '/department'],
            (object) ['name' => __('Service'), 'url' => '/service'],
            (object) ['name' => __('Testimonial'), 'url' => '/testimonial'],
            (object) ['name' => __('Faq'), 'url' => '/faq'],
            (object) ['name' => __('Home'), 'url' => '/'],
            (object) ['name' => __('Privacy Policy'), 'url' => '/privacy-policy'],
            (object) ['name' => __('Terms and Conditions'), 'url' => '/terms-condition'],
        ];

        foreach (customPages() as $page) {
            $all_default_menus[] = (object) ['name' => $page->title, 'url' => "/page/$page->slug"];
        }

        // Sort the array by the 'name' property
        usort($all_default_menus, function ($a, $b) {
            return strcmp($a->name, $b->name);
        });

        // Convert the sorted array to a collection
        return collect($all_default_menus);
    }
}
