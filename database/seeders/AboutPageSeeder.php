<?php

namespace Database\Seeders;

use App\Models\AboutUsPage;
use App\Models\AboutUsPageTranslation;
use Illuminate\Database\Seeder;

class AboutPageSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        // Data for 'about_us_pages' table
        $aboutUsPageData = [
            'about_image'      => 'uploads/website-images/dummy/about_image.webp',
            'background_image' => 'uploads/website-images/dummy/background_image.webp',
            'mission_image'    => 'uploads/website-images/dummy/mission_image.webp',
            'vision_image'     => 'uploads/website-images/dummy/vision_image.webp',
        ];

        $aboutUsPage = AboutUsPage::create($aboutUsPageData);

        $translations = [
            [
                'lang_code'           => 'en',
                'about_description'   => "<h1>Experienced Lawyers Committed to Justice</h1><p>We provide expert legal services with integrity, professionalism, and a client-first approach. Our team of skilled attorneys is dedicated to helping individuals and businesses navigate complex legal challenges effectively.</p><p>Whether you need assistance in civil litigation, corporate law, family matters, or criminal defense, we offer personalized legal strategies tailored to your unique situation. With a reputation for excellence and a focus on results, we are your trusted partner in all legal matters.</p>",
                'mission_description' => "<h1><strong>Our Mission</strong></h1><p>To uphold justice and provide reliable legal solutions that protect the rights and interests of our clients.</p><p>We are committed to serving our clients with integrity, transparency, and diligence. Our mission is to simplify the legal process, ensuring that every client receives clear advice and strong representation. We aim to build long-term relationships based on trust and positive outcomes.</p>",
                'vision_description'  => "<h1>Our Vision</h1><p>To be a leading law firm recognized for legal excellence and client satisfaction.</p><p>We aspire to set the standard for innovative and effective legal representation. By combining legal expertise with a deep understanding of our clients’ needs, we strive to make the law accessible and empowering. Our vision is to be the first choice for individuals and organizations seeking dependable legal guidance.</p>",
            ],
            [
                'lang_code'           => 'ar',
                'about_description'   => "<h1>محامون ذو خبرة ملتزمون بتحقيق العدالة</h1><p>نحن نقدم خدمات قانونية احترافية تعتمد على النزاهة والمهنية ونهج يركز على العميل. يكرس فريقنا من المحامين المهرة جهوده لمساعدة الأفراد والشركات على تجاوز التحديات القانونية المعقدة بفعالية.</p><p>سواء كنت بحاجة إلى المساعدة في قضايا مدنية، أو قانون الشركات، أو شؤون الأسرة، أو الدفاع الجنائي، فإننا نقدم استراتيجيات قانونية مخصصة تناسب وضعك الفريد. بفضل سمعتنا في التميز وتركيزنا على النتائج، نحن شريكك الموثوق في جميع الأمور القانونية.</p>",
                'mission_description' => "<h1><strong>مهمتنا</strong></h1><p>الدفاع عن العدالة وتقديم حلول قانونية موثوقة تحمي حقوق ومصالح عملائنا.</p><p>نحن ملتزمون بخدمة عملائنا بنزاهة وشفافية واجتهاد. مهمتنا هي تبسيط الإجراءات القانونية لضمان حصول كل عميل على نصائح واضحة وتمثيل قوي. نسعى لبناء علاقات طويلة الأمد تقوم على الثقة والنتائج الإيجابية.</p>",
                'vision_description'  => "<h1>رؤيتنا</h1><p>أن نكون من بين أبرز مكاتب المحاماة التي يُعترف بها بالتميز القانوني ورضا العملاء.</p><p>نطمح إلى وضع معايير جديدة في التمثيل القانوني المبتكر والفعال. من خلال الجمع بين الخبرة القانونية وفهم عميق لاحتياجات عملائنا، نسعى لجعل القانون سهل الوصول وذو تأثير إيجابي. رؤيتنا أن نكون الخيار الأول للأفراد والمؤسسات الباحثين عن إرشاد قانوني موثوق.</p>",
            ],
        ];

        foreach ($translations as $translation) {
            AboutUsPageTranslation::create([
                'about_us_page_id'    => $aboutUsPage->id,
                'lang_code'           => $translation['lang_code'],
                'about_description'   => $translation['about_description'],
                'mission_description' => $translation['mission_description'],
                'vision_description'  => $translation['vision_description'],
            ]);
        }
    }
}
