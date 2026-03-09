<?php

namespace Modules\NewsLetter\database\seeders;

use Illuminate\Database\Seeder;
use Modules\NewsLetter\app\Models\SubscriberContent;
use Modules\NewsLetter\app\Models\SubscriberContentTranslation;

class NewsLetterDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subscriberContent = SubscriberContent::create(['image' => 'uploads/website-images/dummy/subscribe-us-banner.webp']);

        $translations = [
            [
                'lang_code'   => 'en',
                'title'       => 'Subscribe Us',
                'description' => 'Stay updated with our latest news and offers by subscribing to our newsletter. We promise to keep you informed about new services, special promotions, and important updates. Join our community and never miss out on exciting updates and offers.',
            ],
            [
                'lang_code'   => 'ar',
                'title'       => 'اشترك معنا',
                'description' => 'ابقَ على اطلاع بأحدث أخبارنا وعروضنا من خلال الاشتراك في نشرتنا الإخبارية. نعدك بإبقائك على علم بالخدمات الجديدة، والعروض الخاصة، والتحديثات المهمة. انضم إلى مجتمعنا ولا تفوتك التحديثات المثيرة والعروض الرائعة.',
            ],
        ];

        foreach ($translations as $translation) {
            SubscriberContentTranslation::create([
                'subscriber_content_id' => $subscriberContent->id,
                'lang_code'             => $translation['lang_code'],
                'title'                 => $translation['title'],
                'description'           => $translation['description'],
            ]);
        }
    }
}
