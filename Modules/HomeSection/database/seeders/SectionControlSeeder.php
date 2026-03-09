<?php

namespace Modules\HomeSection\database\seeders;

use Illuminate\Database\Seeder;
use Modules\HomeSection\app\Models\SectionControl;
use Modules\HomeSection\app\Models\SectionControlTranslation;

class SectionControlSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run() {
        $translations = [
            [
                'lang_code'                 => 'en',
                'work_first_heading'        => 'How',
                'work_second_heading'       => 'We Work',
                'work_description'          => 'We deliver exceptional results through collaboration, expertise, and continuous improvement. Our process ensures quality and efficiency, with open communication and a commitment to excellence for optimal client satisfaction.',
                'service_first_heading'     => 'Our',
                'service_second_heading'    => 'Service',
                'service_description'       => 'We offer comprehensive, high-quality solutions tailored to meet your unique needs, ensuring satisfaction through expert execution, personalized attention, and continuous support.',
                'department_first_heading'  => 'Our',
                'department_second_heading' => 'Departments',
                'department_description'    => 'We provide specialized legal services across various practice areas, ensuring comprehensive counsel, expert representation, and personalized solutions for all your legal needs',
                'client_first_heading'     => 'Our',
                'client_second_heading'    => 'Clients',
                'client_description'       => 'We prioritize our clients’ well-being by delivering dedicated legal support, personalized strategies, and ongoing guidance to ensure their rights are protected and their legal needs are met',
                'lawyer_first_heading'      => 'Our',
                'lawyer_second_heading'     => 'Lawyers',
                'lawyer_description'        => 'Our lawyers are highly skilled professionals dedicated to delivering exceptional legal counsel, accurate case assessments, and personalized strategies to achieve the best outcomes for our clients.',
                'blog_first_heading'        => 'Our',
                'blog_second_heading'       => 'Blog',
                'blog_description'          => 'Stay informed with the latest legal insights, case studies, and expert advice. Our blog is dedicated to helping you understand your rights and stay updated on important legal developments.',
            ],
            [
                'lang_code'                 => 'ar',
                'work_first_heading'        => 'كيفية',
                'work_second_heading'       => 'نحن نعمل',
                'work_description'          => 'نحن نقدم نتائج استثنائية من خلال التعاون والخبرة والتحسين المستمر. تضمن عمليتنا الجودة والكفاءة، مع التواصل المفتوح والالتزام بالتميز لتحقيق الرضا الأمثل للعملاء.',
                'service_first_heading'     => 'خدمتنا',
                'service_second_heading'    => 'خدمات',
                'service_description'       => 'نحن نقدم حلولاً شاملة وعالية الجودة مصممة خصيصًا لتلبية احتياجاتك الفريدة، مع ضمان الرضا من خلال تنفيذ الخبراء والاهتمام الشخصي والدعم المستمر.',
                'department_first_heading'  => 'أقسامنا',
                'department_second_heading' => 'الأقسام',
                'department_description'    => 'نحن نقدم خدمات قانونية متخصصة في مختلف مجالات الممارسة، ونضمن لك المشورة الشاملة والتمثيل الخبير والحلول الشخصية لجميع احتياجاتك القانونية',
                'client_first_heading'     => 'مرضانا',
                'client_second_heading'    => 'العملاء',
                'client_description'       => 'نحن نعطي الأولوية لرفاهية عملائنا من خلال تقديم الدعم القانوني المخصص والاستراتيجيات الشخصية والتوجيه المستمر لضمان حماية حقوقهم وتلبية احتياجاتهم القانونية',
                'lawyer_first_heading'      => 'أطباؤنا',
                'lawyer_second_heading'     => 'المحامون',
                'lawyer_description'        => 'إن محامينا هم من المحترفين ذوي المهارات العالية والمكرسين لتقديم المشورة القانونية الاستثنائية وتقييمات القضايا الدقيقة والاستراتيجيات الشخصية لتحقيق أفضل النتائج لعملائنا.',
                'blog_first_heading'        => 'مدونتنا',
                'blog_second_heading'       => 'المدونة',
                'blog_description'          => 'ابقَ على اطلاع بأحدث الرؤى القانونية ودراسات الحالة ونصائح الخبراء. مدونتنا مُخصصة لمساعدتك على فهم حقوقك والبقاء على اطلاع دائم بالتطورات القانونية المهمة.',
            ],
        ];

        $sectionControl = SectionControl::create();
        // Insert section control translations
        foreach ($translations as $translation) {
            SectionControlTranslation::create([
                'section_control_id'        => $sectionControl->id,
                'lang_code'                 => $translation['lang_code'],
                'work_first_heading'        => $translation['work_first_heading'],
                'work_second_heading'       => $translation['work_second_heading'],
                'work_description'          => $translation['work_description'],
                'service_first_heading'     => $translation['service_first_heading'],
                'service_second_heading'    => $translation['service_second_heading'],
                'service_description'       => $translation['service_description'],
                'department_first_heading'  => $translation['department_first_heading'],
                'department_second_heading' => $translation['department_second_heading'],
                'department_description'    => $translation['department_description'],
                'client_first_heading'     => $translation['client_first_heading'],
                'client_second_heading'    => $translation['client_second_heading'],
                'client_description'       => $translation['client_description'],
                'lawyer_first_heading'      => $translation['lawyer_first_heading'],
                'lawyer_second_heading'     => $translation['lawyer_second_heading'],
                'lawyer_description'        => $translation['lawyer_description'],
                'blog_first_heading'        => $translation['blog_first_heading'],
                'blog_second_heading'       => $translation['blog_second_heading'],
                'blog_description'          => $translation['blog_description'],
            ]);
        }
    }
}
