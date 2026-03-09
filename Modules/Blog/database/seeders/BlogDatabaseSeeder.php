<?php

namespace Modules\Blog\database\seeders;

use App\Models\Admin;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Blog\app\Models\BlogCategoryTranslation;
use Modules\Blog\app\Models\BlogComment;
use Modules\Blog\app\Models\BlogTranslation;

class BlogDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $faker = Faker::create();

        $dummyCategories = [
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => "Corporate Law"],
                    ['lang_code' => 'ar', 'title' => "القانون التجاري"],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => "Family Law"],
                    ['lang_code' => 'ar', 'title' => "قانون الأسرة"],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => "Criminal Defense"],
                    ['lang_code' => 'ar', 'title' => "الدفاع الجنائي"],
                ],
            ],
            [
                'translations' => [
                    ['lang_code' => 'en', 'title' => "Intellectual Property"],
                    ['lang_code' => 'ar', 'title' => "الملكية الفكرية"],
                ],
            ],
        ];
        foreach ($dummyCategories as $item) {
            $category = new BlogCategory();
            $category->slug = Str::slug($item['translations'][0]['title']);
            $category->status = true;
            $category->save();

            foreach ($item['translations'] as $translation) {
                $categoryTranslation = new BlogCategoryTranslation();
                $categoryTranslation->blog_category_id = $category->id;
                $categoryTranslation->lang_code = $translation['lang_code'];
                $categoryTranslation->title = $translation['title'];
                $categoryTranslation->save();
            }
        }

        //Blogs
        $dummyBlogs = [
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Knowing and Understanding Your Legal Rights",
                        'short_description' => "Learn the fundamentals of your legal rights and how to protect them.",
                        'description'       => "<p>Knowing your legal rights is essential when dealing with law enforcement, contracts, or disputes. This article covers:<br><br>1. What are your basic rights?<br>2. How to assert your rights respectfully.<br>3. When to contact a lawyer.<br>4. Rights in the workplace.<br>5. Legal protections for consumers.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "فهم حقوقك القانونية",
                        'short_description' => "تعرف على أساسيات حقوقك القانونية وكيفية حمايتها.",
                        'description'       => "<p>معرفة حقوقك القانونية أمر ضروري عند التعامل مع الجهات القانونية أو العقود أو النزاعات. في هذا المقال نناقش:<br><br>1. ما هي حقوقك الأساسية؟<br>2. كيفية المطالبة بحقوقك باحترام.<br>3. متى يجب التواصل مع محامٍ.<br>4. الحقوق في مكان العمل.<br>5. الحماية القانونية للمستهلكين.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "The Importance of Legal Consultation",
                        'short_description' => "Discover why seeking legal advice early can save you time and money.",
                        'description'       => "<p>Legal consultation can prevent costly mistakes. Here’s why you should always consult a lawyer:<br><br>1. Clarifying legal obligations.<br>2. Reviewing contracts before signing.<br>3. Avoiding litigation.<br>4. Understanding potential legal risks.<br>5. Gaining peace of mind.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "أهمية الاستشارة القانونية",
                        'short_description' => "اكتشف لماذا يمكن أن توفر الاستشارة القانونية الوقت والمال.",
                        'description'       => "<p>الاستشارة القانونية يمكن أن تمنع أخطاء مكلفة. إليك أسباب التواصل مع محامٍ:<br><br>1. توضيح الالتزامات القانونية.<br>2. مراجعة العقود قبل التوقيع.<br>3. تجنب الدعاوى القضائية.<br>4. فهم المخاطر القانونية المحتملة.<br>5. الشعور بالاطمئنان.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Steps to Take After a Car Accident",
                        'short_description' => "Ensure your legal and personal safety after an accident with these steps.",
                        'description'       => "<p>In the aftermath of a car accident, taking the right steps is crucial. Here's what you should do:<br><br>1. Call emergency services.<br>2. Document the scene.<br>3. Avoid admitting fault.<br>4. Contact your insurance provider.<br>5. Speak with a personal injury lawyer.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "الخطوات التي يجب اتخاذها بعد حادث سيارة",
                        'short_description' => "احمِ سلامتك القانونية والشخصية بعد الحادث من خلال هذه الخطوات.",
                        'description'       => "<p>بعد وقوع حادث سيارة، من الضروري اتخاذ الخطوات الصحيحة. إليك ما يجب فعله:<br><br>1. الاتصال بخدمات الطوارئ.<br>2. توثيق الحادث.<br>3. تجنب الاعتراف بالذنب.<br>4. الاتصال بشركة التأمين الخاصة بك.<br>5. التواصل مع محامٍ متخصص في إصابات الحوادث.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "What to Know Before Signing a Contract",
                        'short_description' => "Understand the key elements of a legally binding contract.",
                        'description'       => "<p>Signing a contract without understanding it can lead to legal trouble. Here are tips to protect yourself:<br><br>1. Read every clause.<br>2. Look out for penalties and obligations.<br>3. Check for exit clauses.<br>4. Confirm all terms are accurate.<br>5. Seek legal review if unsure.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "ما يجب معرفته قبل توقيع العقد",
                        'short_description' => "افهم العناصر الأساسية للعقد القانوني الملزم.",
                        'description'       => "<p>توقيع عقد دون فهمه قد يؤدي إلى مشاكل قانونية. إليك بعض النصائح:<br><br>1. قراءة كل بند بعناية.<br>2. الانتباه للعقوبات والالتزامات.<br>3. التحقق من شروط الإنهاء.<br>4. التأكد من دقة جميع البنود.<br>5. استشارة محامٍ إذا كنت غير متأكد.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Family Law: Navigating Divorce and Custody",
                        'short_description' => "Get familiar with the legal process of divorce and child custody.",
                        'description'       => "<p>Family legal matters are emotional and complex. Here's what to know:<br><br>1. Understanding divorce proceedings.<br>2. Legal grounds for divorce.<br>3. Child custody arrangements.<br>4. Financial and property division.<br>5. Mediation and court alternatives.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "قانون الأسرة: التعامل مع الطلاق والحضانة",
                        'short_description' => "تعرف على الإجراءات القانونية المتعلقة بالطلاق وحضانة الأطفال.",
                        'description'       => "<p>قضايا الأسرة عاطفية ومعقدة. إليك ما يجب معرفته:<br><br>1. فهم إجراءات الطلاق.<br>2. الأسباب القانونية للطلاق.<br>3. ترتيبات حضانة الأطفال.<br>4. تقسيم المال والممتلكات.<br>5. الوساطة كبديل للمحكمة.<br></p>",
                    ],
                ],
            ],

            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Starting a Business: Legal Requirements You Must Know",
                        'short_description' => "Learn the legal steps and documents required to start your business.",
                        'description'       => "<p>Before launching a business, it's essential to comply with legal obligations:<br><br>1. Choosing the right business structure (LLC, sole proprietorship, etc.).<br>2. Registering your business name.<br>3. Getting the necessary licenses and permits.<br>4. Drafting legal agreements.<br>5. Understanding tax obligations.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "بدء عمل تجاري: المتطلبات القانونية التي يجب معرفتها",
                        'short_description' => "تعرف على الخطوات القانونية والمستندات المطلوبة لبدء عملك.",
                        'description'       => "<p>قبل بدء مشروعك، من الضروري الامتثال للمتطلبات القانونية:<br><br>1. اختيار الهيكل القانوني المناسب (شركة ذات مسؤولية محدودة، ملكية فردية، إلخ).<br>2. تسجيل اسم النشاط التجاري.<br>3. الحصول على التراخيص والتصاريح اللازمة.<br>4. إعداد الاتفاقيات القانونية.<br>5. فهم الالتزامات الضريبية.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Cyber Law: Protecting Your Online Business",
                        'short_description' => "Understand your legal responsibilities and rights in the digital world.",
                        'description'       => "<p>Online businesses must follow specific cyber laws to avoid penalties:<br><br>1. Data protection regulations (GDPR, etc.).<br>2. Terms and privacy policy compliance.<br>3. Intellectual property rights online.<br>4. Cybersecurity obligations.<br>5. Handling customer data legally.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "القانون الإلكتروني: حماية نشاطك التجاري عبر الإنترنت",
                        'short_description' => "افهم مسؤولياتك وحقوقك القانونية في العالم الرقمي.",
                        'description'       => "<p>يجب أن تمتثل الأنشطة التجارية عبر الإنترنت لقوانين معينة:<br><br>1. قوانين حماية البيانات (مثل GDPR).<br>2. الالتزام بسياسات الخصوصية والشروط.<br>3. حماية حقوق الملكية الفكرية على الإنترنت.<br>4. الالتزامات المتعلقة بالأمن السيبراني.<br>5. التعامل القانوني مع بيانات العملاء.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Tenant Rights: What Every Renter Should Know",
                        'short_description' => "A guide to tenant protections, lease agreements, and dispute resolution.",
                        'description'       => "<p>If you're renting a property, understanding your rights is vital:<br><br>1. Lease agreement basics.<br>2. Security deposit laws.<br>3. Right to a habitable home.<br>4. What to do during landlord disputes.<br>5. When to involve a lawyer.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "حقوق المستأجر: ما يجب أن يعرفه كل مستأجر",
                        'short_description' => "دليل لحماية المستأجر، واتفاقيات الإيجار، وتسوية النزاعات.",
                        'description'       => "<p>إذا كنت تستأجر عقارًا، فمعرفة حقوقك أمر ضروري:<br><br>1. أساسيات اتفاق الإيجار.<br>2. قوانين التأمين المالي.<br>3. الحق في السكن المناسب.<br>4. كيفية التعامل مع نزاعات المالك.<br>5. متى يجب استشارة محامٍ.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Learn how to legally respond to workplace harassment",
                        'short_description' => "Learn how to legally respond to workplace harassment or discrimination.",
                        'description'       => "<p>Workplace rights include protection from harassment and discrimination:<br><br>1. What counts as workplace harassment?<br>2. Steps to document and report.<br>3. Legal protections under labor laws.<br>4. Filing a formal complaint.<br>5. Seeking compensation or legal action.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "التحرش في مكان العمل: خياراتك القانونية",
                        'short_description' => "تعرف على كيفية الرد قانونيًا على التحرش أو التمييز في العمل.",
                        'description'       => "<p>تشمل حقوق العمل الحماية من التحرش والتمييز:<br><br>1. ما الذي يُعتبر تحرشًا في العمل؟<br>2. خطوات التوثيق والإبلاغ.<br>3. الحماية القانونية بموجب قوانين العمل.<br>4. تقديم شكوى رسمية.<br>5. المطالبة بالتعويض أو اتخاذ إجراء قانوني.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "When to Hire a Criminal Defense Lawyer",
                        'short_description' => "Understand the situations where legal defense is crucial to protect your freedom.",
                        'description'       => "<p>If you're accused of a crime, hiring a defense attorney is critical:<br><br>1. What does a criminal defense lawyer do?<br>2. Your rights during arrest and questioning.<br>3. Preparing your defense.<br>4. Negotiating plea deals.<br>5. Representing you in court.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "متى يجب تعيين محامٍ للدفاع الجنائي؟",
                        'short_description' => "افهم الحالات التي يكون فيها الدفاع القانوني ضروريًا لحماية حريتك.",
                        'description'       => "<p>إذا تم اتهامك بجريمة، فإن تعيين محامٍ أمر بالغ الأهمية:<br><br>1. ما الذي يفعله محامي الدفاع الجنائي؟<br>2. حقوقك أثناء التوقيف والاستجواب.<br>3. إعداد الدفاع الخاص بك.<br>4. التفاوض على اتفاقيات الاعتراف بالذنب.<br>5. تمثيلك أمام المحكمة.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "Understanding Power of Attorney: What You Need to Know",
                        'short_description' => "Learn what a power of attorney is, when to use it, and the types available.",
                        'description'       => "<p>A Power of Attorney (POA) is a legal document that allows someone to act on your behalf:<br><br>1. Types of POA: General, Special, Medical.<br>2. When is POA useful? (e.g. travel, illness, business).<br>3. Legal requirements to draft a valid POA.<br>4. Responsibilities and limits of an attorney-in-fact.<br>5. How to revoke or change a POA.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "فهم التوكيل الرسمي: ما تحتاج إلى معرفته",
                        'short_description' => "تعرف على ما هو التوكيل الرسمي، ومتى يتم استخدامه، وأنواعه المختلفة.",
                        'description'       => "<p>التوكيل الرسمي هو مستند قانوني يسمح لشخص بالتصرف نيابة عنك:<br><br>1. أنواع التوكيل: عام، خاص، طبي.<br>2. متى يكون التوكيل مفيدًا؟ (مثل السفر، المرض، الأعمال).<br>3. المتطلبات القانونية لإنشاء توكيل رسمي صحيح.<br>4. مسؤوليات وحدود الممثل القانوني.<br>5. كيفية إلغاء أو تعديل التوكيل.<br></p>",
                    ],
                ],
            ],
            [
                'translations' => [
                    [
                        'lang_code'         => "en",
                        'title'             => "A guide to Legal Steps After a Car Accident",
                        'short_description' => "A guide to what to do legally after being involved in a traffic accident.",
                        'description'       => "<p>Being involved in a car accident can be stressful, but knowing your legal options helps:<br><br>1. Call the authorities and get a police report.<br>2. Collect evidence (photos, witness contacts).<br>3. Notify your insurance provider.<br>4. Know when to contact a lawyer.<br>5. Filing claims for damages or injuries.<br></p>",
                    ],
                    [
                        'lang_code'         => "ar",
                        'title'             => "الخطوات القانونية بعد وقوع حادث سيارة",
                        'short_description' => "دليل لما يجب فعله قانونيًا بعد التعرض لحادث مروري.",
                        'description'       => "<p>التعرض لحادث سيارة قد يكون مرهقًا، ولكن معرفتك بحقوقك القانونية أمر مهم:<br><br>1. الاتصال بالشرطة والحصول على تقرير رسمي.<br>2. جمع الأدلة (صور، شهود).<br>3. إبلاغ شركة التأمين.<br>4. معرفة متى يجب استشارة محامٍ.<br>5. تقديم مطالبات التعويض عن الأضرار أو الإصابات.<br></p>",
                    ],
                ],
            ],

        ];
        
        $counter = 1;
        foreach ($dummyBlogs as $value) {
            $blog = new Blog();
            $blog->admin_id = Admin::first()->id;
            $blog->blog_category_id = $faker->numberBetween(1, 4);
            $blog->slug = Str::slug($value['translations'][0]['title']);
            $blog->image = "uploads/website-images/dummy/blog-{$counter}.webp";
            $blog->thumbnail_image = "uploads/website-images/dummy/blog-thumbnail-{$counter}.webp";
            $blog->show_homepage = $faker->boolean;
            $blog->is_feature = $faker->boolean;
            $blog->status = true;

            $blog->save();

            foreach ($value['translations'] as $data) {
                $blogTranslation = new BlogTranslation();
                $blogTranslation->blog_id = $blog->id;
                $blogTranslation->lang_code = $data['lang_code'];
                $blogTranslation->title = $data['title'];
                $blogTranslation->sort_description = $data['short_description'];
                $blogTranslation->description = $data['description'];
                $blogTranslation->seo_title = $data['title'];
                $blogTranslation->seo_description = $data['short_description'];
                $blogTranslation->save();
            }

            for ($j = 0; $j < 3; $j++) {
                $comment = new BlogComment();
                $comment->blog_id = $blog->id;
                $comment->name = $faker->name;
                $comment->email = $faker->email;
                $comment->phone = $faker->phoneNumber;
                $comment->comment = $faker->paragraph;
                $comment->status = 1;
                $comment->save();
            }

            $counter++;
        }
    }
}
