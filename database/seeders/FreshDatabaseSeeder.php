<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\App\database\seeders\AppDatabaseSeeder;
use Modules\BasicPayment\database\seeders\BasicPaymentInfoSeeder;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\CustomMenu\database\seeders\FreshMenuSeeder;
use Modules\GlobalSetting\database\seeders\CustomPaginationSeeder;
use Modules\GlobalSetting\database\seeders\EmailTemplateSeeder;
use Modules\GlobalSetting\database\seeders\GlobalSettingInfoSeeder;
use Modules\GlobalSetting\database\seeders\SeoInfoSeeder;
use Modules\Installer\app\Models\Configuration;
use Modules\Language\app\Models\Language;
use Modules\PageBuilder\database\seeders\PageBuilderDatabaseSeeder;

class FreshDatabaseSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
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

        Configuration::create(['config' => 'setup_stage', 'value' => 5]);
        Configuration::create(['config' => 'setup_complete', 'value' => 1]);

        $this->call([
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
    }
}
