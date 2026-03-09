<?php

namespace Modules\PaymentWithdraw\database\seeders;

use Illuminate\Database\Seeder;
use Modules\PaymentWithdraw\app\Models\WithdrawMethod;

class PaymentWithdrawDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $withdrawMethods = [
            [
                'id'              => 1,
                'name'            => 'Bank Payment',
                'min_amount'      => 10.00,
                'max_amount'      => 100.00,
                'withdraw_charge' => 10.00,
                'description'     => '<p>Bank Name: Your bank name <br> Account Number:&nbsp; Your bank account number <br> Routing Number: Your bank routing number <br> Branch: Your bank branch name</p>',
            ],
            [
                'id'              => 2,
                'name'            => 'Paypal',
                'min_amount'      => 5.00,
                'max_amount'      => 50.00,
                'withdraw_charge' => 5.00,
                'description'     => '<p>Your Name <br> Your paypal email address <br> Your phone number</p>',
            ],
        ];

        foreach ($withdrawMethods as $method) {
            WithdrawMethod::create($method);
        }
    }
}
