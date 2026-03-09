<?php

namespace Database\Seeders;

use App\Models\ZoomCredential;
use Illuminate\Database\Seeder;

class ZoomCredentialSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $zoomCredentials = [
            [
                'id'              => 1,
                'lawyer_id'       => 1,
                'zoom_account_id'    => 'zoom_account_id',
                'zoom_api_key'    => 'zoom_api_key',
                'zoom_api_secret' => 'zoom_api_secret',
            ],
        ];

        foreach ($zoomCredentials as $credential) {
            ZoomCredential::create($credential);
        }
    }
}
