<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Modules\Language\app\Models\Language;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\App\database\seeders\AppDatabaseSeeder;
use Modules\Day\database\seeders\DayDatabaseSeeder;
use Modules\Faq\database\seeders\FaqDatabaseSeeder;
use Modules\Blog\database\seeders\BlogDatabaseSeeder;
use Modules\Currency\database\seeders\CurrencySeeder;
use Modules\Language\database\seeders\LanguageSeeder;
use Modules\Leave\database\seeders\LeaveDatabaseSeeder;
use Modules\CustomMenu\database\seeders\FreshMenuSeeder;
use Modules\Lawyer\database\seeders\LawyerDatabaseSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\Service\database\seeders\ServiceDatabaseSeeder;
use Modules\Customer\database\seeders\ClientDatabaseSeeder;
use Modules\Schedule\database\seeders\ScheduleDatabaseSeeder;
use Modules\ContactMessage\database\seeders\ContactInfoSeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\HomeSection\database\seeders\SectionDatabaseSeeder;
use Modules\Installer\database\seeders\InstallerDatabaseSeeder;
use Modules\BasicPayment\database\seeders\BasicPaymentInfoSeeder;
use Modules\CustomMenu\database\seeders\CustomMenuDatabaseSeeder;
use Modules\NewsLetter\database\seeders\NewsLetterDatabaseSeeder;
use Modules\SocialLink\database\seeders\SocialLinkDatabaseSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\Appointment\database\seeders\AppointmentDatabaseSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\PageBuilder\database\seeders\PageBuilderDatabaseSeeder;
use Modules\Testimonial\database\seeders\TestimonialDatabaseSeeder;
use Modules\PaymentWithdraw\database\seeders\PaymentWithdrawDatabaseSeeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        if (Cache::has('fresh_install') && Cache::get('fresh_install')) {
            $language = new Language();
            $language->name = 'English';
            $language->code = 'en';
            $language->is_default = true;
            $language->save();

            $currency = new MultiCurrency();
            $currency->currency_name = '$-USD';
            $currency->country_code = 'US';
            $currency->currency_code = 'USD';
            $currency->currency_icon = '$';
            $currency->is_default = 'yes';
            $currency->currency_rate = 1;
            $currency->currency_position = 'before_price';
            $currency->status = 'active';
            $currency->save();

            $this->call([
                InstallerDatabaseSeeder::class,
                GlobalSettingInfoSeeder::class,
                BasicPaymentInfoSeeder::class,
                CustomPaginationSeeder::class,
                EmailTemplateSeeder::class,
                SeoInfoSeeder::class,
                RolePermissionSeeder::class,
                AdminInfoSeeder::class,
                PageBuilderDatabaseSeeder::class,
                FreshMenuSeeder::class,
                AppDatabaseSeeder::class,
            ]);
        } else {
            $this->call([
                InstallerDatabaseSeeder::class,
                LanguageSeeder::class,
                CurrencySeeder::class,
                GlobalSettingInfoSeeder::class,
                BasicPaymentInfoSeeder::class,
                CustomPaginationSeeder::class,
                EmailTemplateSeeder::class,
                SeoInfoSeeder::class,
                RolePermissionSeeder::class,
                AdminInfoSeeder::class,
                ClientDatabaseSeeder::class,
                PageBuilderDatabaseSeeder::class,
                CustomMenuDatabaseSeeder::class,
                AboutPageSeeder::class,
                FaqPageSeeder::class,
                BlogDatabaseSeeder::class,
                ContactInfoSeeder::class,
                SocialLinkDatabaseSeeder::class,
                SectionDatabaseSeeder::class,
                DayDatabaseSeeder::class,
                FaqDatabaseSeeder::class,
                ServiceDatabaseSeeder::class,
                LawyerDatabaseSeeder::class,
                ScheduleDatabaseSeeder::class,
                LeaveDatabaseSeeder::class,
                MessageSeeder::class,
                PaymentWithdrawDatabaseSeeder::class,
                ZoomCredentialSeeder::class,
                TestimonialDatabaseSeeder::class,
                NewsLetterDatabaseSeeder::class,
                AppointmentDatabaseSeeder::class,
                AppDatabaseSeeder::class,
            ]);
        }
    }
}
