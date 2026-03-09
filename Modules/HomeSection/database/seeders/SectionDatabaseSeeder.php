<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\database\seeders\WorkSectionSeeder;

class SectionDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $this->call([
            FeatureSeeder::class,
            PartnerSeeder::class,
            SliderSeeder::class,
            WorkSectionSeeder::class,
            CounterSeeder::class,
            SectionControlSeeder::class,
        ]);
    }
}
