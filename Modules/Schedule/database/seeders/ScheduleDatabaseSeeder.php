<?php

namespace Modules\Schedule\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Schedule\app\Models\Schedule;

class ScheduleDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        Schedule::insert([
            ['id' => 1, 'day_id' => 1, 'lawyer_id' => 1, 'start_time' => '9:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:08:37', 'updated_at' => '2021-07-13 18:08:37'],
            ['id' => 2, 'day_id' => 2, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:08:53', 'updated_at' => '2021-10-24 04:11:14'],
            ['id' => 3, 'day_id' => 3, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:03', 'updated_at' => '2021-10-21 09:57:02'],
            ['id' => 4, 'day_id' => 4, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:15', 'updated_at' => '2021-07-13 18:09:15'],
            ['id' => 5, 'day_id' => 5, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:24', 'updated_at' => '2021-07-13 18:09:24'],
            ['id' => 6, 'day_id' => 6, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:36', 'updated_at' => '2021-07-13 18:09:36'],
            ['id' => 7, 'day_id' => 7, 'lawyer_id' => 1, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:08', 'updated_at' => '2021-07-13 18:11:08'],
            ['id' => 8, 'day_id' => 1, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:18', 'updated_at' => '2021-07-13 18:11:18'],
            ['id' => 9, 'day_id' => 2, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:26', 'updated_at' => '2021-07-13 18:11:26'],
            ['id' => 10, 'day_id' => 3, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:33', 'updated_at' => '2021-07-13 18:11:33'],
            ['id' => 11, 'day_id' => 4, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:43', 'updated_at' => '2021-07-13 18:11:43'],
            ['id' => 12, 'day_id' => 5, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:50', 'updated_at' => '2021-07-13 18:11:50'],
            ['id' => 13, 'day_id' => 6, 'lawyer_id' => 2, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 20, 'status' => 1, 'created_at' => '2021-07-14 15:57:59', 'updated_at' => '2021-07-14 15:57:59'],
            ['id' => 14, 'day_id' => 7, 'lawyer_id' => 2, 'start_time' => '5:00 PM', 'end_time' => '9:00 PM', 'quantity' => 30, 'status' => 1, 'created_at' => '2021-07-14 15:58:26', 'updated_at' => '2021-07-14 15:58:26'],
            ['id' => 16, 'day_id' => 1, 'lawyer_id' => 3, 'start_time' => '10:00 AM', 'end_time' => '2:00 PM', 'quantity' => 123, 'status' => 1, 'created_at' => '2021-10-23 06:05:05', 'updated_at' => '2021-10-23 06:05:05'],
            ['id' => 17, 'day_id' => 2, 'lawyer_id' => 3, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2023-11-08 03:48:49', 'updated_at' => '2023-11-08 03:48:49'],
            ['id' => 19, 'day_id' => 3, 'lawyer_id' => 3, 'start_time' => '11:00 AM', 'end_time' => '1:00 PM', 'quantity' => 20, 'status' => 1, 'created_at' => '2024-07-24 07:07:26', 'updated_at' => '2024-07-24 07:07:26'],
            ['id' => 20, 'day_id' => 5, 'lawyer_id' => 3, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:26', 'updated_at' => '2021-07-13 18:11:26'],
            ['id' => 21, 'day_id' => 2, 'lawyer_id' => 4, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:33', 'updated_at' => '2021-07-13 18:11:33'],
            ['id' => 22, 'day_id' => 4, 'lawyer_id' => 4, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:43', 'updated_at' => '2021-07-13 18:11:43'],
            ['id' => 23, 'day_id' => 2, 'lawyer_id' => 5, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:50', 'updated_at' => '2021-07-13 18:11:50'],
            ['id' => 24, 'day_id' => 1, 'lawyer_id' => 6, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 20, 'status' => 1, 'created_at' => '2021-07-14 15:57:59', 'updated_at' => '2021-07-14 15:57:59'],
            ['id' => 25, 'day_id' => 3, 'lawyer_id' => 6, 'start_time' => '9:00 AM', 'end_time' => '10:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2024-07-24 07:08:10', 'updated_at' => '2024-07-24 07:08:10'],
            ['id' => 26, 'day_id' => 2, 'lawyer_id' => 7, 'start_time' => '9:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:08:37', 'updated_at' => '2021-07-13 18:08:37'],
            ['id' => 27, 'day_id' => 3, 'lawyer_id' => 7, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:08:53', 'updated_at' => '2021-10-24 04:11:14'],
            ['id' => 28, 'day_id' => 5, 'lawyer_id' => 7, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:03', 'updated_at' => '2021-10-21 09:57:02'],
            ['id' => 29, 'day_id' => 6, 'lawyer_id' => 7, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:09:15', 'updated_at' => '2021-07-13 18:09:15'],
            ['id' => 30, 'day_id' => 2, 'lawyer_id' => 8, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 20, 'status' => 1, 'created_at' => '2021-07-14 15:57:59', 'updated_at' => '2021-07-14 15:57:59'],
            ['id' => 31, 'day_id' => 3, 'lawyer_id' => 8, 'start_time' => '9:00 AM', 'end_time' => '10:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2024-07-24 07:08:10', 'updated_at' => '2024-07-24 07:08:10'],
            ['id' => 32, 'day_id' => 1, 'lawyer_id' => 9, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:33', 'updated_at' => '2021-07-13 18:11:33'],
            ['id' => 33, 'day_id' => 3, 'lawyer_id' => 9, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:43', 'updated_at' => '2021-07-13 18:11:43'],
            ['id' => 34, 'day_id' => 3, 'lawyer_id' => 10, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:33', 'updated_at' => '2021-07-13 18:11:33'],
            ['id' => 35, 'day_id' => 5, 'lawyer_id' => 10, 'start_time' => '10:00 AM', 'end_time' => '11:00 AM', 'quantity' => 10, 'status' => 1, 'created_at' => '2021-07-13 18:11:43', 'updated_at' => '2021-07-13 18:11:43'],
        ]);
    }
}
