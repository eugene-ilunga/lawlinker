<?php

namespace Modules\Day\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Day\app\Models\Day;
use Modules\Day\app\Models\DayTranslation;

class DayDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $days = [
            'Friday',
            'Saturday',
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
        ];

        foreach ($days as $day) {
            $create = Day::create([
                'slug' => strtolower($day),
                'status' => true,
            ]);

            foreach (allLanguages() as $language) {
                DayTranslation::create([
                    'day_id' => $create->id,
                    'lang_code' => $language->code,
                    'title' => $day,
                ]);
            }
        }
    }
}
