<?php

namespace Database\Seeders;

use App\Models\FaqPage;
use Illuminate\Database\Seeder;

class FaqPageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        FaqPage::create(['image' => 'uploads/website-images/faq_page.png']);
    }
}
