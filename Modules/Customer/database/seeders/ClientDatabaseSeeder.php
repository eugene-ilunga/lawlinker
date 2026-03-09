<?php

namespace Modules\Customer\database\seeders;

use App\Models\User;
use App\Models\UserDetails;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data = [
            [
                'user'         => [
                    'id'                    => 1000,
                    'client_id'            => '2107141535',
                    'name'                  => 'Harold Lujan',
                    'email'                 => 'client@gmail.com',
                    'password'              => Hash::make(1234),
                    'ready_for_appointment' => 1,
                    'email_verified_at'     => '2024-07-24 05:03:27',
                ],
                'user_details' => [
                    'phone'          => '111-222-3398',
                    'address'        => '3130 Bungalow Road Omaha, NE 68114',
                    'city'           => 'Omaha',
                    'country'        => 'USA',
                    'guardian_name'  => 'Robert Santiago',
                    'guardian_phone' => '111-222-3433',
                    'occupation'     => 'Student',
                    'age'            => '20',
                    'date_of_birth'  => '2023-10-27',
                    'gender'         => 'male',
                ],
            ],
            [
                'user'         => [
                    'id'                    => 1001,
                    'client_id'            => '2110265002',
                    'name'                  => 'Oliver Ilva',
                    'email'                 => 'client2@gmail.com',
                    'password'              => Hash::make(1234),
                    'ready_for_appointment' => 1,
                    'email_verified_at'     => '2024-07-29 23:17:15',
                ],
                'user_details' => [
                    'phone'          => '125-985-4587',
                    'address'        => 'Hempstead',
                    'city'           => 'New York',
                    'country'        => 'USA',
                    'guardian_name'  => 'Benjamin Amelia',
                    'guardian_phone' => '125-985-4587',
                    'occupation'     => 'Teacher',
                    'age'            => '32',
                    'date_of_birth'  => '1992-06-27',
                    'gender'         => 'female',
                ],
            ],
        ];

        foreach ($data as $entry) {
            $user = User::create($entry['user']);
            UserDetails::create(array_merge(['user_id' => $user->id], $entry['user_details']));
        }
    }
}
