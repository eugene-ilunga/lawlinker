<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\CustomPagination;

class CustomPaginationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $item1 = new CustomPagination();
        $item1->section_name = 'Blog';
        $item1->item_qty = 6;
        $item1->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Blog Comment';
        $item2->item_qty = 10;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Lawyer';
        $item2->item_qty = 8;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Department';
        $item2->item_qty = 6;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Service';
        $item2->item_qty = 6;
        $item2->save();

        $item2 = new CustomPagination();
        $item2->section_name = 'Testimonial';
        $item2->item_qty = 6;
        $item2->save();

        $item3 = new CustomPagination();
        $item3->section_name = 'Language List';
        $item3->item_qty = 200;
        $item3->save();
    }
}
