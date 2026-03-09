<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\Partner;

class PartnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $partners = ['uploads/website-images/dummy/partner-1.webp', 'image' => 'uploads/website-images/dummy/partner-2.webp', 'uploads/website-images/dummy/partner-3.webp', 'uploads/website-images/dummy/partner-4.webp'];

        foreach ($partners as $partner) {
            Partner::create(['image' => $partner, 'link' => 'https://websolutionus.com']);
        }
    }
}
