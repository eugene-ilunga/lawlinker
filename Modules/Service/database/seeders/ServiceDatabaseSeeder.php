<?php

namespace Modules\Service\database\seeders;

use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Modules\Service\app\Models\Service;
use Modules\Service\app\Models\ServiceFaq;
use Modules\Service\app\Models\ServiceImage;
use Modules\Service\app\Models\ServiceVideo;
use Modules\Service\app\Models\ServiceTranslation;
use Modules\Service\app\Models\ServiceFaqTranslation;

class ServiceDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {

        $services = [
            [
                'icon'         => 'fab fa-fort-awesome',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Corporate Law',
                        'sort_description' => 'Legal support for corporate governance, & compliance',
                        'description'      => '<p>We provide expert legal services for corporations including governance structure, shareholder agreements, compliance, and mergers & acquisitions.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'القانون التجاري',
                        'sort_description' => 'الدعم القانوني لحوكمة الشركات والاندماجات والامتثال',
                        'description'      => '<p>نقدم خدمات قانونية متخصصة للشركات، تشمل هيكلة الحوكمة، واتفاقيات المساهمين، والامتثال، والاندماجات والاستحواذات.</p>',
                    ],
                ],
            ],
            [
                'icon'         => 'fas fa-balance-scale',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Family Law',
                        'sort_description' => 'Legal services for marriage, divorce, child custody, and more',
                        'description'      => '<p>Our family law experts assist in resolving family disputes including marriage, divorce, custody, and inheritance issues.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'قانون الأسرة',
                        'sort_description' => 'خدمات قانونية للزواج، والطلاق، وحضانة الأطفال، وغيرها',
                        'description'      => '<p>يقدم خبراؤنا في قانون الأسرة المساعدة في حل النزاعات الأسرية مثل الزواج والطلاق والحضانة وقضايا الميراث.</p>',
                    ],
                ],
            ],
            [
                'icon'         => 'fas fa-anchor',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Criminal Law',
                        'sort_description' => 'Defense against criminal charges and legal representation in court',
                        'description'      => '<p>We provide strong defense representation in criminal matters to protect your rights at every stage of the legal process.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'القانون الجنائي',
                        'sort_description' => 'الدفاع ضد التهم الجنائية والتمثيل القانوني في المحكمة',
                        'description'      => '<p>نقدم تمثيلًا قانونيًا قويًا في القضايا الجنائية لحماية حقوقك في جميع مراحل الإجراءات القانونية.</p>',
                    ],
                ],
            ],
            [
                'icon'         => 'fas fa-gavel',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Business Law',
                        'sort_description' => 'Legal guidance for business formation & contracts',
                        'description'      => '<p>We help businesses navigate legal challenges with expert advice on contracts, compliance, mergers, and more.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'القانون التجاري',
                        'sort_description' => 'إرشاد قانوني لتأسيس الشركات والعقود والامتثال',
                        'description'      => '<p>نساعد الشركات على التغلب على التحديات القانونية من خلال تقديم المشورة المتخصصة بشأن العقود والامتثال والاندماجات والمزيد.</p>',
                    ],
                ],
            ],
            [
                'icon'         => 'far fa-snowflake',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Insurance Law',
                        'sort_description' => 'Assistance with insurance claims, disputes, and legal coverage',
                        'description'      => '<p>Our attorneys support clients in navigating insurance issues, including claim denials and policy disputes.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'قانون التأمين',
                        'sort_description' => 'المساعدة في مطالبات التأمين والنزاعات والتغطية القانونية',
                        'description'      => '<p>يدعم محامونا العملاء في التعامل مع قضايا التأمين، بما في ذلك رفض المطالبات والنزاعات المتعلقة بالسياسات.</p>',
                    ],
                ],
            ],
            [
                'icon'         => 'fab fa-envira',
                'translations' => [
                    [
                        'lang_code'        => 'en',
                        'title'            => 'Environmental Law',
                        'sort_description' => 'Legal solutions for environmental regulations and compliance',
                        'description'      => '<p>We advise on environmental laws, compliance, and litigation for businesses and individuals impacting the environment.</p>',
                    ],
                    [
                        'lang_code'        => 'ar',
                        'title'            => 'القانون البيئي',
                        'sort_description' => 'حلول قانونية للتشريعات البيئية والامتثال',
                        'description'      => '<p>نقدم المشورة بشأن القوانين البيئية والامتثال والتقاضي للشركات والأفراد الذين يتأثرون أو يؤثرون على البيئة.</p>',
                    ],
                ],
            ],
        ];

        $videos = [
            [
                'link' => 'https://www.youtube.com/watch?v=6_aWI8JgRCs',
                'code' => '6_aWI8JgRCs',
            ],
            [
                'link' => 'https://www.youtube.com/watch?v=SzXbRCVy4r0',
                'code' => 'SzXbRCVy4r0',
            ]
        ];
        $faqs = [
            [
                'translations' => [
                    [
                        'lang_code' => 'en',
                        'question'  => 'Lorem ipsum dolor sit amet per mollis?',
                        'answer'    => 'Lorem ipsum dolor sit amet, per mollis aeterno nostrud in, nam timeam fastidii eu. Commodo nonumes vim eu. Quo indoctum voluptatibus delicatissimi no. Eu cum dico melius. Cum impetus scribentur ad.',
                    ],
                    [
                        'lang_code' => 'ar',
                        'question'  => 'لوريم إيبسوم دولور الجلوس أميت لكل موليس؟',
                        'answer'    => 'لوريم إيبسوم دولور الجلوس أميت لكل موليس؟لوريم إيبسوم دولور الجلوس أميت لكل موليس؟لوريم إيبسوم دولور الجلوس أميت لكل موليس؟',
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code' => 'en',
                        'question'  => 'Ut alterum dissentiunt eam nobis audire?',
                        'answer'    => 'Ut alterum dissentiunt eam, nobis audire verterem ut vel. Vidisse persius mea no. Melius imperdiet his at. Ex has zril convenire, cu eos eros dolor, omittam adversarium suscipiantur mea ea.',
                    ],
                    [
                        'lang_code' => 'ar',
                        'question'  => 'هل من الممكن أن أختلف مع أي شخص آخر؟',
                        'answer'    => 'هل من الممكن أن أختلف مع أي شخص آخرهل من الممكن أن أختلف مع أي شخص آخرهل من الممكن أن أختلف مع أي شخص آخر',
                    ],
                ],

            ],
        ];

        
        $images = [
            [
                'small_image' => 'uploads/website-images/dummy/department-small-1.webp',
                'large_image' => 'uploads/website-images/dummy/department-large-1.webp',
            ],
            [
                'small_image' => 'uploads/website-images/dummy/department-small-2.webp',
                'large_image' => 'uploads/website-images/dummy/department-large-2.webp',
            ],
            [
                'small_image' => 'uploads/website-images/dummy/department-small-3.webp',
                'large_image' => 'uploads/website-images/dummy/department-large-3.webp',
            ],
            [
                'small_image' => 'uploads/website-images/dummy/department-small-4.webp',
                'large_image' => 'uploads/website-images/dummy/department-large-4.webp',
            ],
        ];

        foreach ($services as $item) {
            $service = Service::create([
                'slug' => Str::slug($item['translations'][0]['title']),
                'icon' => $item['icon'],
            ]);

            foreach ($item['translations'] as $translation) {
                ServiceTranslation::create([
                    'service_id'       => $service->id,
                    'lang_code'        => $translation['lang_code'],
                    'title'            => $translation['title'],
                    'description'      => $translation['description'],
                    'sort_description' => $translation['sort_description'],
                    'seo_title'        => $translation['title'],
                    'seo_description'  => $translation['sort_description'],
                ]);
            }
            foreach ($videos as $video) {
                ServiceVideo::create([
                    'service_id' => $service->id,
                    'link'       => $video['link'],
                    'code'       => $video['code'],
                ]);
            }

            foreach ($images as $image) {
                ServiceImage::create([
                    'service_id'  => $service->id,
                    'small_image' => $image['small_image'],
                    'large_image' => $image['large_image'],
                ]);
            }
            foreach ($faqs as $faq) {
                $serviceFaq = ServiceFaq::create(['service_id' => $service->id]);
                foreach ($faq['translations'] as $value) {
                    ServiceFaqTranslation::create([
                        'service_faq_id' => $serviceFaq->id,
                        'lang_code'      => $value['lang_code'],
                        'question'       => $value['question'],
                        'answer'         => $value['answer'],
                    ]);
                }
            }
        }
    }
}
