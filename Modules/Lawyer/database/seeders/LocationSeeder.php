<?php

namespace Modules\Lawyer\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Lawyer\app\Models\Location;
use Modules\Lawyer\app\Models\LocationTranslation;

class LocationSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $locations = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'New York'],
                    ['lang_code' => 'ar', 'name' => 'نيويورك'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Chicago'],
                    ['lang_code' => 'ar', 'name' => 'شيكاغو'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Boston'],
                    ['lang_code' => 'ar', 'name' => 'بوسطن'],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'name' => 'Los Angeles'],
                    ['lang_code' => 'ar', 'name' => 'لوس أنجلوس'],
                ],
            ],
        ];

        foreach ($locations as $locationData) {
            $location = Location::create();

            foreach ($locationData['translations'] as $translation) {
                LocationTranslation::create([
                    'location_id' => $location->id,
                    'lang_code'   => $translation['lang_code'],
                    'name'        => $translation['name'],
                ]);
            }
        }
    }
}
