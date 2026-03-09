<?php

namespace Modules\Testimonial\database\seeders;

use Illuminate\Database\Seeder;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\Testimonial\app\Models\TestimonialTranslation;

class TestimonialDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $testimonials = [
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Hattie Peterman',
                        'designation' => 'CEO, ABC IT Limited',
                        'comment'     => 'The lawyers provided exceptional legal support and were extremely attentive throughout my entire case. Every aspect was handled with the utmost professionalism, ensuring all my concerns were addressed. I felt genuinely supported and highly recommend their services to anyone in need of reliable legal counsel.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'Hattie Peterman',
                        'designation' => 'الرئيس التنفيذي، ABC IT Limited',
                        'comment'     => 'قدّم المحامون دعماً قانونياً استثنائياً وكانوا منتبهين للغاية طوال قضيتي. تم التعامل مع كل جانب بأقصى درجات الاحتراف، مما ضمن معالجة جميع مخاوفي. شعرت بدعم حقيقي وأوصي بشدة بخدماتهم لأي شخص يحتاج إلى مشورة قانونية موثوقة.',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Paul Kelley',
                        'designation' => 'MD, Nice Multimedia',
                        'comment'     => 'My experience with the legal team was very positive. They showed great professionalism, and the process was handled efficiently. From start to finish, I felt confident and well-guided. I truly appreciate their commitment to delivering excellent legal services.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'Paul Kelley',
                        'designation' => 'المدير الطبي، Nice Multimedia',
                        'comment'     => 'كانت تجربتي مع الفريق القانوني إيجابية للغاية. أظهروا احترافية كبيرة، وتمت إدارة العملية بكفاءة. من البداية إلى النهاية، شعرت بالثقة والتوجيه الجيد. أقدر حقًا التزامهم بتقديم خدمات قانونية ممتازة.',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Thomas West',
                        'designation' => 'CTO, KMC Limited',
                        'comment'     => 'The legal services I received were outstanding. From the first consultation, the attention to detail and dedication were impressive. The lawyers addressed every aspect of my case thoroughly. I highly recommend their firm to anyone seeking expert legal representation.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'Thomas West',
                        'designation' => 'مدير التكنولوجيا، KMC Limited',
                        'comment'     => 'كانت الخدمات القانونية التي تلقيتها ممتازة. من أول استشارة، كان الانتباه للتفاصيل والتفاني مثيرًا للإعجاب. تعامل المحامون مع جميع جوانب قضيتي بشكل شامل. أوصي بشدة بمكتبهم لأي شخص يبحث عن تمثيل قانوني محترف.',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Sarah Lambert',
                        'designation' => 'Founder, LegalTech Hub',
                        'comment'     => 'From the initial consultation to the final resolution, the legal team exceeded my expectations. Their clarity, communication, and commitment made a stressful situation much easier to navigate. I am grateful for their expertise and would confidently recommend their services to others.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'Sarah Lambert',
                        'designation' => 'المؤسسة، LegalTech Hub',
                        'comment'     => 'من الاستشارة الأولى وحتى الحل النهائي، فاق الفريق القانوني توقعاتي. لقد ساعدني وضوحهم وتواصلهم والتزامهم على تخطي وضع مرهق بسهولة أكبر. أنا ممتنة لخبرتهم وأوصي بخدماتهم بكل ثقة.',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Mohammed Al-Farsi',
                        'designation' => 'General Manager, Gulf Trading Co.',
                        'comment'     => 'I had a complex legal issue involving contracts and business regulations. The firm handled it with precision and deep knowledge of the law. Their team was approachable, transparent, and responsive throughout the process. I would gladly work with them again if needed.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'محمد الفارسي',
                        'designation' => 'المدير العام، Gulf Trading Co.',
                        'comment'     => 'واجهت قضية قانونية معقدة تتعلق بالعقود والأنظمة التجارية. تعامل المكتب معها بدقة ومعرفة عميقة بالقانون. كان فريقهم متعاوناً وشفافاً وسريع الاستجابة طوال العملية. سأتعامل معهم مجددًا بكل سرور إذا دعت الحاجة.',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'   => 'en',
                        'name'        => 'Khalid Al-Zayani',
                        'designation' => 'CEO, Zayani Group',
                        'comment'     => 'From the initial consultation to the final resolution, the legal team demonstrated exceptional professionalism and clarity. Their dedication gave me peace of mind throughout the case. Truly a firm I can trust.',
                    ],
                    [
                        'lang_code'   => 'ar',
                        'name'        => 'خالد الزايني',
                        'designation' => 'الرئيس التنفيذي، Zayani Group',
                        'comment'     => 'من الاستشارة الأولى وحتى الحل النهائي، أظهر الفريق القانوني احترافية ووضوحاً استثنائيين. منحني التزامهم راحة البال طوال فترة القضية. بالفعل شركة يمكن الوثوق بها.',
                    ],
                ],
            ],
        ];

        $counter = 1;
        foreach ($testimonials as $testimonialData) {
            // Create the testimonial
            $testimonial = Testimonial::create([
                'image'  => "uploads/website-images/dummy/testimonial-{$counter}.webp",
                'rating' => 5,
            ]);

            // Create translations
            foreach ($testimonialData['translations'] as $translation) {
                TestimonialTranslation::create([
                    'testimonial_id' => $testimonial->id,
                    'lang_code'      => $translation['lang_code'],
                    'name'           => $translation['name'],
                    'designation'    => $translation['designation'],
                    'comment'        => $translation['comment'],
                ]);
            }

            $counter++;
        }
    }
}
