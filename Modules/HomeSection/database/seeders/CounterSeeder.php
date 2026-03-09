<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\Counter;
use Modules\HomeSection\app\Models\CounterTranslation;

class CounterSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $counters = [
            [
                'icon'         => 'fas fa-smile',
                'qty'          => '500',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Happy Clients'],
                    ['lang_code' => 'ar', 'title' => 'عملاء سعداء'],
                ],
            ],
            [
                'icon'         => 'fas fa-hospital-user',
                'qty'          => '16',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Departments'],
                    ['lang_code' => 'ar', 'title' => 'الأقسام'],
                ],
            ],
            [
                'icon'         => 'fas fa-balance-scale',
                'qty'          => '50',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Expert Lawyers'],
                    ['lang_code' => 'ar', 'title' => 'محامون خبراء'],
                ],
            ],
            [
                'icon'         => 'fas fa-award',
                'qty'          => '300',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Total Awards'],
                    ['lang_code' => 'ar', 'title' => 'إجمالي الجوائز'],
                ],
            ],
        ];

        foreach ($counters as $counterData) {
            // Create the counter
            $counter = Counter::create([
                'icon' => $counterData['icon'],
                'qty'  => $counterData['qty'],
            ]);

            // Create translations
            foreach ($counterData['translations'] as $translation) {
                CounterTranslation::create([
                    'counter_id' => $counter->id,
                    'lang_code'  => $translation['lang_code'],
                    'title'      => $translation['title'],
                ]);
            }
        }
    }
}
