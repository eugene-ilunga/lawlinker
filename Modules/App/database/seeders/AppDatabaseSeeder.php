<?php

namespace Modules\App\database\seeders;

use Illuminate\Database\Seeder;
use Modules\App\app\Models\OnBoardingScreen;

class AppDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            [
                'title'     => 'Welcome to LawMent',
                'sub_title' => 'Your trusted partner for effortless healthcare management',
                'image'     => 'uploads/website-images/app/screen_1.png',
                'order'     => 1,
            ],
            [
                'title'     => 'Easy Appointment Booking',
                'sub_title' => 'Find lawyers and book appointments with just a few taps.',
                'image'     => 'uploads/website-images/app/screen_2.png',
                'order'     => 2,
            ],
            [
                'title'     => 'title',
                'sub_title' => 'sub title',
                'image'     => 'uploads/website-images/app/screen_3.png',
                'order'     => 3,
            ],

        ];
        foreach ($data as $item) {
            OnBoardingScreen::create([
                'title'            => $item['title'],
                'sort_description' => $item['sub_title'],
                'image'            => $item['image'],
                'order'            => $item['order'],
            ]);
        }
    }
}
