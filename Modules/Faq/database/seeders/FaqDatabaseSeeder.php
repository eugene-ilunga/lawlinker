<?php

namespace Modules\Faq\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Models\FaqCategory;
use Modules\Faq\app\Models\FaqTranslation;
use Modules\Faq\app\Models\FaqCategoryTranslation;

class FaqDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $faqCategories = [
            [
                'slug'         => 'general-questions',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'General Questions'],
                    ['lang_code' => 'ar', 'title' => 'أسئلة عامة'],
                ],
            ],
            [
                'slug'         => 'payment-related-questions',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Payment Related Questions'],
                    ['lang_code' => 'ar', 'title' => 'أسئلة متعلقة بالدفع'],
                ],
            ],
            [
                'slug'         => 'appointment-related-questions',
                'translations' => [
                    ['lang_code' => 'en', 'title' => 'Appointment Related Questions'],
                    ['lang_code' => 'ar', 'title' => 'أسئلة متعلقة بالمواعيد'],
                ],
            ],
        ];

        foreach ($faqCategories as $categoryData) {
            // Create the FAQ category
            $category = FaqCategory::create([
                'slug'   => $categoryData['slug'],
            ]);

            // Create translations
            foreach ($categoryData['translations'] as $translation) {
                FaqCategoryTranslation::create([
                    'faq_category_id' => $category->id,
                    'lang_code'       => $translation['lang_code'],
                    'title'           => $translation['title'],
                ]);
            }
        }

        $faqs = [
            [
                'faq_category_id' => 1,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'What is your refund policy?', 'answer' => 'Our refund policy allows for returns within 30 days of purchase. Please ensure the product is in its original condition.'],
                    ['lang_code' => 'ar', 'question' => 'ما هي سياسة الاسترداد الخاصة بكم؟', 'answer' => 'تسمح سياسة الاسترداد لدينا بإرجاع المنتجات خلال 30 يومًا من الشراء. يرجى التأكد من أن المنتج في حالته الأصلية.'],
                ],
            ],
            [
                'faq_category_id' => 1,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'How can I track my order?', 'answer' => 'You can track your order using the tracking number provided in your shipping confirmation email.'],
                    ['lang_code' => 'ar', 'question' => 'كيف يمكنني تتبع طلبي؟', 'answer' => 'يمكنك تتبع طلبك باستخدام رقم التتبع المقدم في بريد التأكيد على الشحن.'],
                ],
            ],
            [
                'faq_category_id' => 2,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'What payment methods do you accept?', 'answer' => 'We accept Visa, MasterCard, PayPal, and bank transfers.'],
                    ['lang_code' => 'ar', 'question' => 'ما هي طرق الدفع التي تقبلونها؟', 'answer' => 'نقبل فيزا، ماستركارد، باي بال، والتحويلات البنكية.'],
                ],
            ],
            [
                'faq_category_id' => 2,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'Is it safe to use my credit card on your website?', 'answer' => 'Yes, we use industry-standard encryption to protect your information.'],
                    ['lang_code' => 'ar', 'question' => 'هل من الآمن استخدام بطاقتي الائتمانية على موقعكم؟', 'answer' => 'نعم، نستخدم تشفيراً وفق المعايير الصناعية لحماية معلوماتك.'],
                ],
            ],
            [
                'faq_category_id' => 3,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'How do I schedule an appointment?', 'answer' => 'You can schedule an appointment through our website or by calling our customer service.'],
                    ['lang_code' => 'ar', 'question' => 'كيف يمكنني تحديد موعد؟', 'answer' => 'يمكنك تحديد موعد من خلال موقعنا الإلكتروني أو بالاتصال بخدمة العملاء.'],
                ],
            ],
            [
                'faq_category_id' => 3,
                'translations'    => [
                    ['lang_code' => 'en', 'question' => 'Can I reschedule my appointment?', 'answer' => 'Yes, you can reschedule your appointment by contacting our customer service at least 24 hours in advance.'],
                    ['lang_code' => 'ar', 'question' => 'هل يمكنني إعادة جدولة موعدي؟', 'answer' => 'نعم، يمكنك إعادة جدولة موعدك عن طريق الاتصال بخدمة العملاء قبل 24 ساعة على الأقل.'],
                ],
            ],
        ];

        foreach ($faqs as $faqData) {
            // Create the FAQ
            $faq = Faq::create([
                'faq_category_id' => $faqData['faq_category_id'],
            ]);

            // Create translations
            foreach ($faqData['translations'] as $translation) {
                FaqTranslation::create([
                    'faq_id'    => $faq->id,
                    'lang_code' => $translation['lang_code'],
                    'question'  => $translation['question'],
                    'answer'    => $translation['answer'],
                ]);
            }
        }
    }
}
