<?php

namespace Modules\Appointment\database\seeders;

use App\Models\MeetingHistory;
use Carbon\Carbon;
use App\Models\ZoomMeeting;
use Illuminate\Database\Seeder;
use Modules\Order\app\Models\Order;
use Modules\Appointment\app\Models\Appointment;
use Modules\PaymentWithdraw\app\Models\WithdrawRequest;

class AppointmentDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $data_list = [
            [
                'user_id'                => 1000,
                'order_id'               => '46606080',
                'appointment_qty'        => 1,
                'amount_usd'             => 420,
                'payment_method'         => 'Paypal',
                'total_payment'          => 420,
                'payment_transaction_id' => 'HUR3FNQ2XCB2U',
                'payment_description'    => json_encode([
                    'payments_captures_id' => '185193442H1060322',
                    'amount'               => '420.00',
                    'currency'             => 'USD',
                    'paid'                 => '420.00',
                    'paypal_fee'           => '15.15',
                    'net_amount'           => '404.85',
                    'status'               => 'COMPLETED',
                ]),
                'payment_status'         => 1,
                'order_status'           => 1,
                'approved_date'         => now(),
                'show_notification'      => 0,
                'gateway_charge'         => '0',
                'payable_with_charge'    => '420.00',
                'payable_currency'       => 'USD',
                'paid_amount'            => '420.00',
            ],
            [
                'user_id'                => 1000,
                'order_id'               => '1167789662',
                'appointment_qty'        => 1,
                'amount_usd'             => 12,
                'payment_method'         => 'Bank',
                'total_payment'          => 960,
                'payment_transaction_id' => 'tran_2408082735',
                'payment_description'    => json_encode([
                    'transaction_id' => 'tran_2408082735',
                    'amount'         => '960.00',
                    'currency'       => 'BDT',
                    'payment_status' => 'VALID',
                    'created'        => '2024-08-08 09:27:36',
                ]),
                'payment_status'         => 1,
                'order_status'           => 1,
                'approved_date'         => now(),
                'show_notification'      => 0,
                'gateway_charge'         => '0',
                'payable_with_charge'    => '960.00',
                'payable_currency'       => 'BDT',
                'paid_amount'            => '960.00',
            ],
            [
                'user_id'                => 1000,
                'order_id'               => '1031435644',
                'appointment_qty'        => 1,
                'amount_usd'             => 4173.5,
                'payment_method'         => 'Stripe',
                'total_payment'          => 4173.5,
                'payment_transaction_id' => 'pi_3PlN3xF56Pb8BOOX0GW1ZBzZ',
                'payment_description'    => json_encode([
                    'transaction_id' => 'pi_3PlN3xF56Pb8BOOX0GW1ZBzZ',
                    'amount'         => 417350,
                    'currency'       => 'NGN',
                    'payment_status' => 'paid',
                    'created'        => 1723087824,
                ]),
                'payment_status'         => 1,
                'order_status'           => 1,
                'approved_date'         => now(),
                'show_notification'      => 0,
                'gateway_charge'         => '0',
                'payable_with_charge'    => '417350',
                'payable_currency'       => 'NGN',
                'paid_amount'            => '4173.5',
            ],
            [
                'user_id'                => 1000,
                'order_id'               => '762593755',
                'appointment_qty'        => 1,
                'amount_usd'             => 10,
                'payment_method'         => 'Direct Bank',
                'total_payment'          => 10,
                'payment_transaction_id' => 'GB29NWBK60161331926819',
                'payment_description'    => json_encode([
                    'bank_name'      => 'Brac Bank',
                    'account_number' => '1234567890',
                    'routing_number' => '021000021',
                    'branch'         => 'Dhaka',
                    'transaction'    => 'GB29NWBK60161331926819',
                ]),
                'payment_status'         => 0,
                'order_status'           => 0,
                'approved_date'         => null,
                'show_notification'      => 0,
                'gateway_charge'         => '0',
                'payable_with_charge'    => '10.00',
                'payable_currency'       => 'USD',
                'paid_amount'            => '10.00',
            ],
        ];

        foreach ($data_list as $data) {
            Order::create($data);
        }
        $data_list = [
            [
                'user_id'                => 1000,
                'order_id'               => 1,
                'day_id'                 => 5,
                'schedule_id'            => 12,
                'lawyer_id'              => 2,
                'already_treated'        => 1,
                'date'                   => now(),
                'appointment_fee_usd'    => 420,
                'appointment_fee'        => 420,
                'payable_currency'       => 'USD',
                'payment_status'         => 1,
                'payment_transaction_id' => 'HUR3FNQ2XCB2U',
                'payment_method'         => 'Paypal',
                'payment_description'    => json_encode([
                    'payments_captures_id' => '185193442H1060322',
                    'amount'               => '420.00',
                    'currency'             => 'USD',
                    'paid'                 => '420.00',
                    'paypal_fee'           => '15.15',
                    'net_amount'           => '404.85',
                    'status'               => 'COMPLETED',
                ]),
                'subject'    => 'Lorem ipsum dolor sit amet',
                'description'    => "<p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p><p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p>",
                'status'                 => 0,
            ],
            [
                'user_id'                => 1000,
                'order_id'               => 2,
                'day_id'                 => 1,
                'schedule_id'            => 16,
                'lawyer_id'              => 3,
                'already_treated'        => 1,
                'date'                   => now(),
                'appointment_fee_usd'    => 12,
                'appointment_fee'        => 960,
                'payable_currency'       => 'BDT',
                'payment_status'         => 1,
                'payment_transaction_id' => 'tran_2408082735',
                'payment_method'         => 'Direct Bank',
                'payment_description'    => json_encode([
                    'transaction_id' => 'tran_2408082735',
                    'amount'         => '960.00',
                    'currency'       => 'BDT',
                    'payment_status' => 'VALID',
                    'created'        => '2024-08-08 09:27:36',
                ]),
                'subject'    => 'Lorem ipsum dolor sit amet',
                'description'    => "<p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p><p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p>",
                'status'                 => 0,
            ],
            [
                'user_id'                => 1000,
                'order_id'               => 3,
                'day_id'                 => 2,
                'schedule_id'            => 2,
                'lawyer_id'              => 1,
                'already_treated'        => 1,
                'date'                   => now(),
                'appointment_fee_usd'    => 10,
                'appointment_fee'        => 4173.5,
                'payable_currency'       => 'NGN',
                'payment_status'         => 1,
                'payment_transaction_id' => 'pi_3PlN3xF56Pb8BOOX0GW1ZBzZ',
                'payment_method'         => 'Stripe',
                'payment_description'    => json_encode([
                    'transaction_id' => 'pi_3PlN3xF56Pb8BOOX0GW1ZBzZ',
                    'amount'         => 417350,
                    'currency'       => 'ngn',
                    'payment_status' => 'paid',
                    'created'        => 1723087824,
                ]),
                'subject'    => 'Lorem ipsum dolor sit amet',
                'description'    => "<p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p><p>Lorem ipsum dolor sit amet, qui assum oblique praesent te. Quo ei erant essent scaevola, est ut clita dolorem, ei est mazim fuisset scribentur. Mel ut decore salutandi intellegam. Labitur epicurei vis cu, in mei rationibus consequuntur. Duo eu modus periculis, inermis detracto expetendis ius eu. Mel ludus viderer noluisse cu, te virtute constituam vix, et eos justo mucius salutatus. Nam illum dicant laudem no</p>",
                'status'                 => 0,
            ],
            [
                'user_id'                => 1000,
                'order_id'               => 4,
                'day_id'                 => 2,
                'schedule_id'            => 2,
                'lawyer_id'              => 1,
                'already_treated'        => 0,
                'date'                   => now(),
                'appointment_fee_usd'    => 10,
                'appointment_fee'        => 10,
                'payable_currency'       => 'USD',
                'payment_status'         => 0,
                'payment_transaction_id' => 'GB29NWBK60161331926819',
                'payment_method'         => 'Direct Bank',
                'payment_description'    => json_encode([
                    'bank_name'      => 'Brac Bank',
                    'account_number' => '1234567890',
                    'routing_number' => '021000021',
                    'branch'         => 'Dhaka',
                    'transaction'    => 'GB29NWBK60161331926819',
                ]),
                'subject'    => null,
                'description'    => null,
                'status'                 => 0,
            ],
        ];
        foreach ($data_list as $data) {
            Appointment::create($data);
        }
        $documents = [['path'=> 'document_1-20250513_112638.txt'],['path'=> 'document_2-20250513_112638.txt']];
        $appointments = Appointment::treated()->get();
        foreach ($appointments as $appointment) {
            $appointment->documents()->createMany($documents);
        }


        $data_list = [
            [
                'lawyer_id'          => 2,
                'withdraw_method_id' => 2,
                'method'             => '',
                'total_amount'       => 50.00,
                'withdraw_amount'    => 47.50,
                'withdraw_charge'    => 5.00,
                'account_info'       => '<p>Brac bank<br>1542234<br>120314<br>Dhaka</p>',
                'status'             => 'approved',
                'approved_date'      => Carbon::create(2024, 8, 8),
            ],
            [
                'lawyer_id'          => 2,
                'withdraw_method_id' => 1,
                'method'             => '',
                'total_amount'       => 100.00,
                'withdraw_amount'    => 90.00,
                'withdraw_charge'    => 10.00,
                'account_info'       => '<p>Meghna bank<br>24835475255<br>245869<br>Dhanmondi</p>',
                'status'             => 'pending',
                'approved_date'      => null,
            ],
        ];
        foreach ($data_list as $data) {
            WithdrawRequest::create($data);
        }

        $data_list = [
            [
                'lawyer_id'  => 1,
                'admin_id'   => 0,
                'topic'      => 'Test',
                'start_time' => Carbon::create(2024, 8, 30, 3, 0, 12),
                'duration'   => 60,
                'meeting_id' => '84537088774',
                'password'   => '1644795877',
                'join_url'   => 'https://us05web.zoom.us/j/84537088774?pwd=6eq0JsNwObdioZa6MwcTyyjLIT1fKl.1',
            ],
            [
                'lawyer_id'  => 1,
                'admin_id'   => 0,
                'topic'      => 'Test',
                'start_time' => Carbon::create(2024, 8, 8, 10, 16, 32),
                'duration'   => 15,
                'meeting_id' => '88508347157',
                'password'   => '1234413906',
                'join_url'   => 'https://us05web.zoom.us/j/88508347157?pwd=Qp5ImbEfB3beaH91H63s5MuDNbYUfo.1',
            ],
        ];
        foreach ($data_list as $data) {
            ZoomMeeting::create($data);
        }

        $data_list = [
            [
                'lawyer_id'    => 1,
                'user_id'      => 1000,
                'meeting_id'   => '84537088774',
                'meeting_time' => Carbon::create(2024, 8, 30, 3, 0, 12),
                'duration'     => 60,
                'created_at'   => Carbon::create(2024, 8, 7, 22, 16, 13),
                'updated_at'   => Carbon::create(2024, 8, 7, 22, 16, 13),
            ],
            [
                'lawyer_id'    => 1,
                'user_id'      => 1000,
                'meeting_id'   => '88508347157',
                'meeting_time' => Carbon::create(2024, 8, 8, 10, 16, 32),
                'duration'     => 15,
                'created_at'   => Carbon::create(2024, 8, 7, 22, 17, 0),
                'updated_at'   => Carbon::create(2024, 8, 7, 22, 17, 0),
            ],
        ];
        foreach ($data_list as $data) {
            MeetingHistory::create($data);
        }
    }
}
