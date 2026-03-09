<?php

namespace Modules\Lawyer\database\seeders;

use Faker\Factory as Faker;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use App\Models\LawyerSocialMedia;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Lawyer\app\Models\LawyerTranslation;

class LawyerInfoSeeder extends Seeder {
    public function run() {
        $faker = Faker::create();
        $lawyers = [
            [
                'department_id'     => 1,
                'location_id'       => 1,
                'name'              => 'Tonmoy Shank',
                'email'             => 'lawyer@gmail.com',
                'about' => 'Tommy Shank is a highly skilled criminal defense attorney with a deep understanding of criminal law and procedure. He specializes in defending clients against a wide range of criminal charges, offering personalized representation to each client.',
                'address' => '456 Legal Street, New York, NY, 10002, USA',
                'educations' => '
                    <ul>
                        <li>JD - New York University School of Law (2006)</li>
                        <li>LLM (Criminal Law) - Columbia Law School (2011)</li>
                        <li>Bar Admission - New York State Bar Association (2007)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Associate Attorney - Manhattan Legal Defenders (2006-2011)</li>
                        <li>Senior Defense Attorney - Shank & Associates, New York (2011-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Criminal Law Specialist</li>
                        <li>Member of the National Association of Criminal Defense Lawyers</li>
                        <li>Board Certified Trial Lawyer</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Judicial Science', 'seo_title' => 'Tommy Shank', 'seo_description' => 'Tommy Shank'],
                    ['lang_code' => 'ar', 'designations' => 'Judicial Science', 'seo_title' => 'تومي شانك', 'seo_description' => 'تومي شانك'],
                ],
                'wallet_balance' => 10,
            ],
            [
                'department_id'     => 2,
                'location_id'       => 1,
                'name'              => 'Aaron Bemis',
                'email'             => 'aaron@gmail.com',
                'about' => 'Aaron Bemis is a renowned corporate lawyer with extensive experience in business law and corporate transactions. He specializes in managing mergers and acquisitions, conducting due diligence, and providing personalized legal strategies for his clients to ensure business compliance and growth.',
                'address' => '123 Corporate Lane, New York, NY, 10001, USA',
                'educations' => '
                    <ul>
                        <li>JD - New York University School of Law (2004)</li>
                        <li>LLM (Corporate Law) - Harvard Law School (2009)</li>
                        <li>Bar Admission - New York State Bar Association (2005)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Corporate Associate - White & Case LLP (2004-2009)</li>
                        <li>Partner - Bemis Corporate Law Group (2009-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Corporate Law Specialist</li>
                        <li>Member of the American Bar Association - Business Law Section</li>
                        <li>Board Certified in Business Bankruptcy Law</li>
                    </ul>',

                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Master of Laws', 'seo_title' => 'Aaron Bemis', 'seo_description' => 'Aaron Bemis'],
                    ['lang_code' => 'ar', 'designations' => 'Master of Laws', 'seo_title' => 'آرون بيميس', 'seo_description' => 'آرون بيميس'],
                ],
                'wallet_balance' => 420,
            ],
            [
                'department_id'     => 3,
                'location_id'       => 1,
                'name'              => 'Jesse Moran',
                'email'             => 'moran@gmail.com',
                'about' => 'Jesse Moran is an experienced intellectual property attorney specializing in patent, trademark, and copyright protection. With years of practice, Moran is committed to protecting clients\' creative works and innovative ideas through comprehensive legal strategies.',
                'address' => '456 IP Street, New York, NY, 10001, USA',
                'educations' => '
                    <ul>
                        <li>JD - New York University School of Law (2005)</li>
                        <li>LLM (Intellectual Property) - Columbia Law School (2010)</li>
                        <li>Bar Admission - New York State Bar Association (2006)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>IP Associate - Fish & Richardson P.C. (2005-2010)</li>
                        <li>Senior IP Counsel - Moran IP Law Firm (2010-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Registered Patent Attorney - USPTO</li>
                        <li>Member of the International Trademark Association</li>
                        <li>Board Certified IP Specialist</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'JD, LLM', 'seo_title' => 'Jesse Moran', 'seo_description' => 'Jesse Moran'],
                    ['lang_code' => 'ar', 'designations' => 'JD, LLM', 'seo_title' => 'جيسي موران', 'seo_description' => 'جيسي موران'],
                ],
                'wallet_balance' => 10,
            ],
            [
                'department_id'     => 4,
                'location_id'       => 1,
                'name'              => 'Miguel Silva',
                'email'             => 'silva@gmail.com',
                'about' => 'Miguel Silva is a compassionate family law attorney with a special interest in child custody, divorce, and adoption cases. He is skilled in negotiating and litigating a wide range of family law matters, with a focus on amicable resolutions and child welfare.',
                'address' => '123 Family Law Lane, New York, NY, 10001, USA',
                'educations' => '
                    <ul>
                        <li>JD - New York University School of Law (2007)</li>
                        <li>LLM (Family Law) - Fordham University School of Law (2012)</li>
                        <li>Bar Admission - New York State Bar Association (2008)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Family Law Associate - Greenberg Traurig (2007-2012)</li>
                        <li>Partner - Silva Family Law Practice (2012-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Family Law Specialist</li>
                        <li>Member of the American Academy of Matrimonial Lawyers</li>
                        <li>Court-Approved Family Mediator</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Business Law', 'seo_title' => 'Miguel Silva', 'seo_description' => 'Miguel Silva'],
                    ['lang_code' => 'ar', 'designations' => 'Business Law', 'seo_title' => 'ميغيل سيلفا', 'seo_description' => 'ميغيل سيلفا'],
                ],
            ],
            [
                'department_id'     => 1,
                'location_id'       => 2,
                'name'              => 'John M Brown',
                'email'             => 'john@gmail.com',
                'about' => 'John M Brown is a distinguished personal injury attorney with expertise in handling cases related to auto accidents, workplace injuries, and medical malpractice. His extensive experience in the field allows him to offer personalized legal representation to his clients seeking compensation.',
                'address' => '101 Injury Claims Drive, Chicago, IL, 60601, USA',
                'educations' => '
                    <ul>
                        <li>JD - University of Chicago Law School (2004)</li>
                        <li>LLM (Trial Advocacy) - Northwestern University School of Law (2009)</li>
                        <li>Bar Admission - Illinois State Bar Association (2005)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Associate Attorney - Morgan & Morgan (2004-2009)</li>
                        <li>Senior Partner - Brown Injury Law Group (2009-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Civil Trial Specialist</li>
                        <li>Member of the American Association for Justice</li>
                        <li>Board Certified in Personal Injury Law</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Bachelor of Laws', 'seo_title' => 'John M Brown', 'seo_description' => 'John M Brown'],
                    ['lang_code' => 'ar', 'designations' => 'Bachelor of Laws', 'seo_title' => 'جون إم براون', 'seo_description' => 'جون إم براون'],
                ],
            ],
            [
                'department_id'     => 6,
                'location_id'       => 2,
                'name'              => 'Nicholas Fox',
                'email'             => 'nicholas@gmail.com',
                'about' => 'Nicholas Fox is a highly skilled real estate attorney with years of experience in property transactions, leasing, and land use regulations. He specializes in commercial real estate deals, property disputes, and title issues, providing comprehensive legal services to his clients.',
                'address' => '456 Real Estate Street, Chicago, IL, 60611, USA',
                'educations' => '
                    <ul>
                        <li>JD - University of Chicago Law School (2005)</li>
                        <li>LLM (Real Estate Law) - John Marshall Law School (2010)</li>
                        <li>Bar Admission - Illinois State Bar Association (2006)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Real Estate Associate - Kirkland & Ellis LLP (2005-2010)</li>
                        <li>Partner - Fox Real Estate Law Group (2010-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Real Estate Law Specialist</li>
                        <li>Member of the American Land Title Association</li>
                        <li>Licensed Real Estate Broker</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'JD, LLM', 'seo_title' => 'Nicholas Fox', 'seo_description' => 'Nicholas Fox'],
                    ['lang_code' => 'ar', 'designations' => 'JD, LLM', 'seo_title' => 'نيكولاس فوكس', 'seo_description' => 'نيكولاس فوكس'],
                ],
            ],
            [
                'department_id'     => 4,
                'location_id'       => 3,
                'name'              => 'Sarah Adams',
                'email'             => 'sarah@gmail.com',
                'about' => 'Sarah Adams is a dedicated family law attorney specializing in divorce, child custody, and adoption cases. She focuses on collaborative divorce solutions, child support arrangements, and ensuring the best interests of children, providing personalized and comprehensive legal support to families.',
                'address' => '789 Family Court Avenue, Boston, MA, 02115, USA',
                'educations' => '
                    <ul>
                        <li>JD - Harvard Law School (2008)</li>
                        <li>LLM (Family Law) - Boston University School of Law (2013)</li>
                        <li>Bar Admission - Massachusetts State Bar Association (2009)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Family Law Associate - Mintz Levin (2008-2013)</li>
                        <li>Partner - Adams Family Law (2013-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Family Law Specialist</li>
                        <li>Member of the American Academy of Matrimonial Lawyers</li>
                        <li>Certified Divorce Mediator</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'QC / KC', 'seo_title' => 'Sarah Adams', 'seo_description' => 'Sarah Adams'],
                    ['lang_code' => 'ar', 'designations' => 'QC / KC', 'seo_title' => 'سارة آدامز', 'seo_description' => 'سارة آدامز'],
                ],
            ],
            [
                'department_id'     => 3,
                'location_id'       => 3,
                'name'              => 'Emily Johnson',
                'email'             => 'emily@gmail.com',
                'about' => 'Emily Johnson is a renowned intellectual property attorney with a focus on patent litigation and trademark protection. Her dedication to safeguarding creative works and innovations has made her a leader in the IP law field.',
                'address' => '321 Innovation Avenue, Boston, MA, 02108, USA',
                'educations' => '
                    <ul>
                        <li>JD - Harvard Law School (2003)</li>
                        <li>LLM (Intellectual Property) - Boston College Law School (2008)</li>
                        <li>Bar Admission - Massachusetts State Bar Association (2004)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>IP Associate - Fish & Richardson P.C. (2003-2008)</li>
                        <li>Senior IP Partner - Johnson IP Law Group (2008-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Registered Patent Attorney - USPTO</li>
                        <li>Member of the International Trademark Association</li>
                        <li>Certified Copyright Specialist</li>
                    </ul>',

                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Master of Laws', 'seo_title' => 'Emily Johnson', 'seo_description' => 'Emily Johnson'],
                    ['lang_code' => 'ar', 'designations' => 'Master of Laws', 'seo_title' => 'إميلي جونسون', 'seo_description' => 'إميلي جونسون'],
                ],
            ],
            [
                'department_id'     => 2,
                'location_id'       => 4,
                'name'              => 'William Green',
                'email'             => 'william@gmail.com',
                'about' => 'William Green is a leading corporate attorney with a passion for business law. He specializes in mergers and acquisitions, securities regulations, and corporate governance, offering comprehensive legal strategies to ensure long-term business success for his clients.',
                'address' => '789 Corporate Avenue, Los Angeles, CA, 90001, USA',
                'educations' => '
                    <ul>
                        <li>JD - UCLA School of Law (2007)</li>
                        <li>LLM (Business Law) - USC Gould School of Law (2012)</li>
                        <li>Bar Admission - California State Bar Association (2008)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Corporate Associate - Gibson Dunn (2007-2012)</li>
                        <li>Partner - Green Corporate Law Group (2012-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Business Law Specialist</li>
                        <li>Member of the American Bar Association - Business Law Section</li>
                        <li>Licensed Securities Attorney</li>
                    </ul>',

                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'Business Law', 'seo_title' => 'William Green', 'seo_description' => 'William Green'],
                    ['lang_code' => 'ar', 'designations' => 'Business Law', 'seo_title' => 'ويليام غرين', 'seo_description' => 'ويليام غرين'],
                ],
            ],
            [
                'department_id'     => 5,
                'location_id'       => 4,
                'name'              => 'Jessica Roberts',
                'email'             => 'jessica@gmail.com',
                'about' => 'Jessica Roberts is a skilled immigration attorney specializing in visa applications, deportation defense, and citizenship processes. She has a wealth of experience in navigating complex immigration laws to assist clients in achieving their immigration goals and securing their future in the United States.',
                'address' => '789 Immigration Blvd, Los Angeles, CA, 90001, USA',
                'educations' => '
                    <ul>
                        <li>JD - UCLA School of Law (2006)</li>
                        <li>LLM (Immigration Law) - Loyola Law School (2011)</li>
                        <li>Bar Admission - California State Bar Association (2007)</li>
                    </ul>',
                'experience' => '
                    <ul>
                        <li>Immigration Associate - Fragomen (2006-2011)</li>
                        <li>Partner - Roberts Immigration Law Firm (2011-Present)</li>
                    </ul>',
                'qualifications' => '
                    <ul>
                        <li>Certified Immigration Law Specialist</li>
                        <li>Member of the American Immigration Lawyers Association</li>
                        <li>Board Certified in Immigration and Nationality Law</li>
                    </ul>',
                'translations'      => [
                    ['lang_code' => 'en', 'designations' => 'JD, LLM', 'seo_title' => 'Jessica Roberts', 'seo_description' => 'Jessica Roberts'],
                    ['lang_code' => 'ar', 'designations' => 'JD, LLM', 'seo_title' => 'جيسيكا روبرتس', 'seo_description' => 'جيسيكا روبرتس'],
                ],
            ],
        ];

        foreach ($lawyers as $key => $lawyer) {
            $index = ++$key;
            $now = now();
            $lawyerData = [
                'id'                  => $index,
                'department_id'       => $lawyer['department_id'],
                'location_id'         => $lawyer['location_id'],
                'name'                => $lawyer['name'],
                'slug'                => Str::slug($lawyer['name']),
                'email'               => $lawyer['email'],
                'password'            => bcrypt(1234),
                'phone'               => $faker->phoneNumber,
                'fee'                 => $faker->numberBetween(15, 200),
                'years_of_experience' => $faker->numberBetween(1, 5),
                'image'               => "uploads/website-images/dummy/lawyer-{$index}.webp",
                'status'              => 1,
                'show_homepage'       => 1,
                'wallet_balance'       => $lawyer['wallet_balance'] ?? 0.00,
                'email_verified_at'   => $now,
                'created_at'          => $now,
                'updated_at'          => $now,
            ];

            Lawyer::updateOrCreate(['id' => $index], $lawyerData);

            foreach ($lawyer['translations'] as $translation) {
                LawyerTranslation::updateOrCreate(
                    [
                        'lawyer_id' => $index,
                        'lang_code' => $translation['lang_code'],
                    ],
                    [
                        ...$translation,
                        'about' => $lawyer['about'],
                        'address' => $lawyer['address'],
                        'educations' => $lawyer['educations'],
                        'experience' => $lawyer['experience'],
                        'qualifications' => $lawyer['qualifications'],
                    ]
                );
            }

            LawyerSocialMedia::insert([
                [
                    'lawyer_id' => $index,
                    'icon'      => 'fab fa-facebook-f',
                    'link'      => 'https://www.facebook.com',
                ],
                [
                    'lawyer_id' => $index,
                    'icon'      => 'fab fa-twitter',
                    'link'      => 'https://www.twitter.com',
                ],
                [
                    'lawyer_id' => $index,
                    'icon'      => 'fab fa-linkedin-in',
                    'link'      => 'https://www.linkedin.com',
                ],
                [
                    'lawyer_id' => $index,
                    'icon'      => 'fab fa-instagram',
                    'link'      => 'https://www.instagram.com',
                ],
                [
                    'lawyer_id' => $index,
                    'icon'      => 'fas fa-globe',
                    'link'      => 'https://www.yourwebsite.com',
                ]
            ]);
        }
    }
}
