<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\SeoSetting;

class SeoInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item1 = new SeoSetting();
        $item1->page_name = 'Home';
        $item1->seo_title = 'Home || LawMent';
        $item1->seo_description = 'Home || LawMent';
        $item1->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'About';
        $item2->seo_title = 'About || LawMent';
        $item2->seo_description = 'About || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Contact';
        $item2->seo_title = 'Contact || LawMent';
        $item2->seo_description = 'Contact || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Blog';
        $item2->seo_title = 'Blog || LawMent';
        $item2->seo_description = 'Blog || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Lawyers';
        $item2->seo_title = 'Lawyers || LawMent';
        $item2->seo_description = 'Lawyers || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Department';
        $item2->seo_title = 'Department || LawMent';
        $item2->seo_description = 'Department || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Service';
        $item2->seo_title = 'Service || LawMent';
        $item2->seo_description = 'Service || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Testimonial';
        $item2->seo_title = 'Testimonial || LawMent';
        $item2->seo_description = 'Testimonial || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'FaQ';
        $item2->seo_title = 'FaQ || LawMent';
        $item2->seo_description = 'FaQ || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Privacy Policy';
        $item2->seo_title = 'Privacy Policy || LawMent';
        $item2->seo_description = 'Privacy Policy || LawMent';
        $item2->save();

        $item2 = new SeoSetting();
        $item2->page_name = 'Terms Condition';
        $item2->seo_title = 'Terms and Condition || LawMent';
        $item2->seo_description = 'Terms and Condition || LawMent';
        $item2->save();
    }
}
