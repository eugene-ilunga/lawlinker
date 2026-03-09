<?php

namespace Modules\Leave\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Leave\app\Models\Leave;

class LeaveDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $leaves = [
            ['id' => 1, 'lawyer_id' => 1, 'date' => '2021-07-21', 'reason' => 'Vacation', 'status' => 1],
            ['id' => 2, 'lawyer_id' => 2, 'date' => '2021-10-29', 'reason' => 'Personal Leave', 'status' => 1],
            ['id' => 3, 'lawyer_id' => 1, 'date' => '2023-11-07', 'reason' => 'Medical Appointment', 'status' => 1],
        ];

        foreach ($leaves as $leave) {
            Leave::create($leave);
        }
    }
}
