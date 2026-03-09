<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\Slider;

class SliderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $sliders = ['uploads/website-images/dummy/slider-1.webp', 'uploads/website-images/dummy/slider-2.webp', 'uploads/website-images/dummy/slider-3.webp'];
        foreach ($sliders as $index => $slider) {
            Slider::create(['image' => $slider, 'title' => 'Slider-' . ++$index]);
        }
    }
}
