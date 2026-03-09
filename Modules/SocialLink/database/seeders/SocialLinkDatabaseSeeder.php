<?php

namespace Modules\SocialLink\database\seeders;

use Illuminate\Database\Seeder;
use Modules\SocialLink\app\Models\SocialLink;

class SocialLinkDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        SocialLink::create([
            'icon' => 'fab fa-instagram',
            'link' => 'https://www.instagram.com',
        ]);
        SocialLink::create([
            'icon' => 'fab fa-facebook-f',
            'link' => 'https://www.facebook.com',
        ]);

        SocialLink::create([
            'icon' => 'fab fa-linkedin-in',
            'link' => 'https://www.linkedin.com',
        ]);
        SocialLink::create([
            'icon' => 'fab fa-twitter',
            'link' => 'https://www.twitter.com',
        ]);
    }
}