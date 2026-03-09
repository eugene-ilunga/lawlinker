<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\WorkSection;
use Modules\HomeSection\app\Models\WorkSectionFaq;
use Modules\HomeSection\app\Models\WorkSectionFaqTranslation;
use Modules\HomeSection\app\Models\WorkSectionTranslation;

class WorkSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Seed work sections
        $dummyWorkSections = [
            [
                'image' => 'uploads/website-images/dummy/work-background.webp',
                'video' => 'https://www.youtube.com/watch?v=G07V0aOmWTI',
                'translations' => [
                    [
                        'lang_code' => 'en',
                        'title' => 'Ensure Justice with Our Trusted Legal Support',
                    ],
                    [
                        'lang_code' => 'ar',
                        'title' => 'ضمان العدالة مع دعمنا القانوني الموثوق',
                    ],
                ],
            ],
        ];

        foreach ($dummyWorkSections as $item) {
            // Insert work section
            $workSection = WorkSection::create([
                'image' => $item['image'],
                'video' => $item['video'],
            ]);

            // Insert work section translations
            foreach ($item['translations'] as $translation) {
                WorkSectionTranslation::create([
                    'work_section_id' => $workSection->id,
                    'lang_code' => $translation['lang_code'],
                    'title' => $translation['title'],
                ]);
            }
        }

        // Seed work section FAQs
        $dummyWorkSectionFaqs = [
    [
        'status' => 1,
        'faqs' => [
            [
                'lang_code' => 'en',
                'question' => 'Who Are Our Clients?',
                'answer' => 'Our clients include individuals, businesses, and organizations seeking expert legal advice and representation.',
            ],
            [
                'lang_code' => 'ar',
                'question' => 'من هم عملاؤنا؟',
                'answer' => 'عملاؤنا هم أفراد وشركات ومنظمات يبحثون عن استشارات قانونية وخدمات تمثيل قانوني موثوقة.',
            ],
        ],
    ],
    [
        'status' => 1,
        'faqs' => [
            [
                'lang_code' => 'en',
                'question' => 'When Is A Lawyer Available?',
                'answer' => 'Our lawyers are available Monday to Friday, from 9:00 AM to 5:00 PM. Appointments can be scheduled in advance.',
            ],
            [
                'lang_code' => 'ar',
                'question' => 'متى يتوفر المحامي؟',
                'answer' => 'يتوفر محامونا من الاثنين إلى الجمعة، من الساعة 9:00 صباحًا حتى 5:00 مساءً. يمكن تحديد المواعيد مسبقًا.',
            ],
        ],
    ],
    [
        'status' => 1,
        'faqs' => [
            [
                'lang_code' => 'en',
                'question' => 'How Do I Register In This System?',
                'answer' => 'You can register by filling out the online registration form on our website and verifying your email address.',
            ],
            [
                'lang_code' => 'ar',
                'question' => 'كيف يمكنني التسجيل في هذا النظام؟',
                'answer' => 'يمكنك التسجيل من خلال تعبئة نموذج التسجيل عبر الإنترنت على موقعنا الإلكتروني وتأكيد عنوان بريدك الإلكتروني.',
            ],
        ],
    ],
];


        foreach ($dummyWorkSectionFaqs as $item) {
            $workSectionFaqs = WorkSectionFaq::create([
                'work_section_id' => WorkSection::first()->id,
                'status' => $item['status'],
            ]);

            // Insert work section FAQ translations
            foreach ($item['faqs'] as $faq) {
                WorkSectionFaqTranslation::create([
                    'work_section_faq_id' => $workSectionFaqs->id,
                    'lang_code' => $faq['lang_code'],
                    'question' => $faq['question'],
                    'answer' => $faq['answer'],
                ]);
            }
        }
    }
}
