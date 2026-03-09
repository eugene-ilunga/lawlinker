<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\Feature;
use Modules\HomeSection\app\Models\FeatureTranslation;

class FeatureSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $dummyFeatures = [
            [
                'image'        => 'uploads/website-images/dummy/featur-1.webp',
                'icon'         => 'fab fa-quinscape',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'Quick Response',
                        'description' => 'We respond to your needs promptly and efficiently.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'استجابة سريعة',
                        'description' => 'نستجيب لاحتياجاتك بسرعة وكفاءة.',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/website-images/dummy/featur-2.webp',
                'icon'         => 'fas fa-smile',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => '100% Satisfaction',
                        'description' => 'We ensure complete satisfaction with our services.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'رضا بنسبة 100%',
                        'description' => 'نضمن رضاك التام عن خدماتنا.',
                    ],
                ],
            ],
            [
                'image'        => 'uploads/website-images/dummy/featur-3.webp',
                'icon'         => 'fas fa-chess-queen',
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'title'       => 'Quality Service',
                        'description' => 'We offer high-quality service tailored to your needs.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'title'       => 'خدمة عالية الجودة',
                        'description' => 'نقدم خدمة عالية الجودة تلبي احتياجاتك.',
                    ],
                ],
            ],
        ];

        foreach ($dummyFeatures as $item) {
            // Insert feature
            $feature = Feature::create([
                'image' => $item['image'],
                'icon'  => $item['icon'],
            ]);

            // Insert feature translations
            foreach ($item['translations'] as $translation) {
                FeatureTranslation::create([
                    'feature_id'  => $feature->id,
                    'lang_code'   => $translation['lang_code'],
                    'title'       => $translation['title'],
                    'description' => $translation['description'],
                ]);
            }
        }
    }
}
