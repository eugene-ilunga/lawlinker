<?php

namespace Modules\GlobalSetting\database\seeders;

use Illuminate\Database\Seeder;
use Modules\GlobalSetting\app\Models\Setting;

class GlobalSettingInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $setting_data = [
            'app_name'                      => 'LawMent',
            'version'                       => '4.0.0',
            'logo'                          => 'uploads/website-images/logo.webp',
            'timezone'                      => 'Asia/Dhaka',
            'date_format'                   => 'Y-m-d',
            'time_format'                   => 'h:i A',
            'favicon'                       => 'uploads/website-images/favicon.png',
            'cookie_status'                 => 'active',
            'border'                        => 'normal',
            'corners'                       => 'thin',
            'background_color'              => '#c8b47e',
            'text_color'                    => '#ffffff',
            'border_color'                  => '#c8b47e',
            'btn_bg_color'                  => '#ffffff',
            'btn_text_color'                => '#222758',
            'link_text'                     => 'Policy',
            'btn_text'                      => 'Yes',
            'message'                       => 'This website uses essential cookies to ensure its proper operation and tracking cookies to understand how you interact with it. The latter will be set only upon approval.',
            'recaptcha_site_key'            => '6Le-PAYqAAAAAIHVR0VOcWt6eB3VUhcvji-wOaBd',
            'recaptcha_secret_key'          => '6Le-PAYqAAAAAKsiisewXG6ysu2bxOp820eB8Sub',
            'recaptcha_status'              => 'inactive',
            'tawk_status'                   => 'inactive',
            'tawk_chat_link'                => 'https://embed.tawk.to/6682660beaf3bd8d4d16bb9f/1i1mlt82l',
            'googel_tag_status'             => 'inactive',
            'googel_tag_id'                 => 'googel_tag_id',
            'google_analytic_status'        => 'inactive',
            'google_analytic_id'            => 'google_analytic_id',
            'pixel_status'                  => 'inactive',
            'pixel_app_id'                  => 'pixel_app_id',
            'google_login_status'           => 'inactive',
            'gmail_client_id'               => 'google_client_id',
            'gmail_secret_id'               => 'google_secret_id',
            'default_avatar'                => 'uploads/website-images/default-avatar.png',
            'breadcrumb_image'              => 'uploads/website-images/breadcrumb-image.webp',
            'error_page_image'              => 'uploads/website-images/error_img.png',
            'mail_host'                     => 'sandbox.smtp.mailtrap.io',
            'mail_sender_email'             => 'sender@gmail.com',
            'mail_username'                 => 'mail_username',
            'mail_password'                 => 'mail_password',
            'mail_port'                     => 2525,
            'mail_encryption'               => 'ssl',
            'mail_sender_name'              => 'LawMent',
            'contact_message_receiver_mail' => 'receiver@gmail.com',
            'pusher_app_id'                 => 'pusher_app_id',
            'pusher_app_key'                => 'pusher_app_key',
            'pusher_app_secret'             => 'pusher_app_secret',
            'pusher_app_cluster'            => 'pusher_app_cluster',
            'pusher_status'                 => 'inactive',
            'club_point_rate'               => 1,
            'club_point_status'             => 'active',
            'maintenance_mode'              => 0,
            'maintenance_image'             => 'uploads/website-images/maintenance.jpg',
            'maintenance_title'             => 'Website Under maintenance',
            'maintenance_description'       => '<p>We are currently performing maintenance on our website to<br>improve your experience. Please check back later.</p>
            <p><a title="Websolutions" href="https://websolutionus.com/">Websolutions</a></p>',
            'last_update_date'              => date('Y-m-d H:i:s'),
            'is_queable'                    => 'inactive',
            'comments_auto_approved'        => 'active',
            'client_can_register'          => 1,
            'lawyer_can_register'           => 1,
            'lawyer_can_add_social_links'   => 'active',
            'lawyer_social_links_limit'     => 5,
            'prenotification_hour'          => 3,
            'comment_type'                  => 1,
            'facebook_comment_script'       => 882238482112522,
            'theme_one'                     => '#c8b47e',
            'theme_two'                     => '#f1634c',
            'preloader'                     => 0,
            'preloader_image'               => 'uploads/website-images/preloader_image.gif',
            'prescription_phone'            => 123 - 233 - 3455,
            'prescription_email'            => 'support@lawment.com',
            'save_contact_message'          => 0,
            'app_banner'                    => 'uploads/website-images/app_banner.webp',
            'app_login_img'                 => 'uploads/website-images/app/app_login.jpg',
            'app_forgot_password_img'       => 'uploads/website-images/app/app_forgot_password.jpg',
            'google_app_store_status'       => 1,
            'google_app_store_link'         => 'https://websolutionus.com',
            'google_app_store_img'          => 'uploads/website-images/google-play.svg',
            'apple_app_store_status'        => 1,
            'apple_app_store_link'          => 'https://websolutionus.com',
            'apple_app_store_img'           => 'uploads/website-images/apple-store.svg',
        ];

        foreach ($setting_data as $index => $setting_item) {
            $new_item = new Setting();
            $new_item->key = $index;
            $new_item->value = $setting_item;
            $new_item->save();
        }
    }
}
