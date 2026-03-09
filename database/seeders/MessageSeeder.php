<?php

namespace Database\Seeders;

use App\Models\Message;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $messages = [
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'Hello Sir', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'I want to get treatment from you soon.', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'Can you please provide me your information so that I can contact on your chambers.', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 1, 'user_id' => 1000, 'message' => 'Hello', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'Yes. You can contact me. My official phone is: 222-2323-1222', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 2, 'send_user' => 0],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'Thank you very much, sir', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'You are welcome', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 2, 'send_user' => 0],
            ['lawyer_id' => 1, 'user_id' => 1000, 'message' => 'Are you there?', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 1, 'user_id' => 1000, 'message' => 'yes there', 'lawyer_view' => 0, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'lorem ipsum', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 2, 'send_user' => 0],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'Yes. You can contact me. My official phone is: 222-2323-1222', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 2, 'send_user' => 0],
            ['lawyer_id' => 1, 'user_id' => 1000, 'message' => 'hi', 'lawyer_view' => 0, 'user_view' => 1, 'send_lawyer' => 0, 'send_user' => 1],
            ['lawyer_id' => 2, 'user_id' => 1000, 'message' => 'hi', 'lawyer_view' => 1, 'user_view' => 1, 'send_lawyer' => 2, 'send_user' => 0],
        ];

        foreach ($messages as $message) {
            Message::create($message);
        }
    }
}
