<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\EmailTemplate;

class EmailTemplateSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $templates = [
            [
                'name'    => 'password_reset',
                'subject' => 'Password Reset',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Do you want to reset your password? Please Click the following link and Reset Your Password.</p>',
            ],
            [
                'name'    => 'contact_mail',
                'subject' => 'Contact Email',
                'message' => '<p>Hello there,</p>
                <p>&nbsp;Mr. {{name}} has sent a new message. you can see the message details below.&nbsp;</p>
                <p>Email: {{email}}</p>
                <p>Phone: {{phone}}</p>
                <p>Subject: {{subject}}</p>
                <p>Message: {{message}}</p>',
            ],
            [
                'name'    => 'lawyer_login',
                'subject' => 'Lawyer Login',
                'message' => '<h4>Hi, <b>{{lawyer_name}}</b></h4>
                <p>Your Account has been created successfully. Your login info here</p>
                <p>Email: <b>{{email}}</b></p>
                <p>Password: <b>{{password}}</b></p>
                <p>You can log in to your account at <a href="{{login_url}}">{{login_url}}</a></p>',
            ],
            [
                'name' => 'order_mail',
                'subject' => 'Order Confirmation Mail',
                'message' => '<h4>Dear <b>{{client_name}}</b>,</h4><p> Thanks for your new order. Your order id is <b>{{orderId}}</b>.</p>
                <p>Payment Method :<b> {{payment_method}}</b></p>
                <p>Total amount:<b> {{amount}}</b></p>
                <p>Payment Status:<b> {{payment_status}}</b></p>
                <p>Status:<b> {{status}}</b></p>
                <p><b>{{order_details}}</b></p><p><b><br></b></p>',
            ],
            [
                'name' => 'approve_payment',
                'subject' => 'Approve Payment',
                'message' => '<h4>Dear <b>{{client_name}}</b>,</h4><p>Your payment for the order <b>{{orderId}}</b> has been successfully approved. Thank you for choosing our service.</p>',
            ],
            [
                'name'    => 'subscribe_notification',
                'subject' => 'Subscribe Notification',
                'message' => '<p>Hi there, Congratulations! Your Subscription has been created successfully. Please Click the following link and Verified Your Subscription. If you will not approve this link, you can not get any newsletter from us.</p>',
            ],
            [
                'name'    => 'social_login',
                'subject' => 'Social Login',
                'message' => '<p>Hello {{user_name}},</p>
                <p>Welcome to {{app_name}}! Your account has been created successfully.</p>
                <p>Your email: {{email}}</p>
                <p>Your password: {{password}}</p>
                <p>You can log in to your account at <a href="{{login_url}}">{{login_url}}</a></p>
                <p>Thank you for joining us.</p>',
            ],

            [
                'name'    => 'blog_comment',
                'subject' => 'Blog Comment',
                'message' => '<p>Hello, {{admin_name}},</p>
                <p>A new pending comment has been added by {{user_name}}</p>',
            ],
            [
                'name'    => 'user_verification',
                'subject' => 'User Verification',
                'message' => '<p>Dear {{user_name}},</p>
                <p>Congratulations! Your account has been created successfully. Please click the following link to activate your account.</p>',
            ],

            [
                'name'    => 'approved_withdraw',
                'subject' => 'Withdraw Request Approval',
                'message' => '<p>Dear {{user_name}},</p>
                <p>We are happy to say that, we have send a withdraw amount to your provided bank information.</p>
                <p>Thanks &amp; Regards</p>
                <p>LawMent</p>',
            ],
            [
                'name'    => 'zoom_meeting',
                'subject' => 'Zoom Meeting',
                'message' => '<p>Hi {{client_name}},</p><p>{{lawyer_name}} has created a zoom meeting. if you want to join the meeting, please click here</p><p>Meeting Schedule: {{meeting_schedule}}</p>',
            ],
            [
                'name'    => 'pre_notification',
                'subject' => 'Pre Notification for Appointment',
                'message' => '<p>Hi {{client_name}},</p><p>Your schedule time is&nbsp; {{schedule}}</p><p>Date:&nbsp;{{date}}</p><p>Lawyer: {{lawyer_name}}</p>',
            ],

        ];

        foreach ($templates as $index => $template) {
            $new_template = new EmailTemplate();
            $new_template->name = $template['name'];
            $new_template->subject = $template['subject'];
            $new_template->message = $template['message'];
            $new_template->save();
        }
    }
}
