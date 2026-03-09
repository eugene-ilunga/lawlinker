<?php

namespace App\Providers;

use Exception;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\Setting;

class AppServiceProvider extends ServiceProvider {
    /**
     * Register any application services.
     */
    public function register(): void {

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        try {
            $setting = Cache::rememberForever('setting', function () {
                $setting_info = Setting::get();
                $setting = [];
                foreach ($setting_info as $setting_item) {
                    $setting[$setting_item->key] = $setting_item->value;
                }
                $setting = (object) $setting;

                return $setting;
            });

            // Setup mail configuration
            $mailConfig = [
                'transport'  => 'smtp',
                'host'       => $setting?->mail_host,
                'port'       => $setting?->mail_port,
                'encryption' => $setting?->mail_encryption,
                'username'   => $setting?->mail_username,
                'password'   => $setting?->mail_password,
                'timeout'    => null,
            ];

            config(['mail.mailers.smtp' => $mailConfig]);
            config(['mail.from.address' => $setting?->mail_sender_email]);
            config(['mail.from.name' => $setting?->mail_sender_name]);

            // setup timezone globally
            config(['app.timezone' => $setting?->timezone]);

            config(['broadcasting.connections.pusher.key' => $setting?->pusher_app_key]);
            config(['broadcasting.connections.pusher.secret' => $setting?->pusher_app_secret]);
            config(['broadcasting.connections.pusher.app_id' => $setting?->pusher_app_id]);
            config(['broadcasting.connections.pusher.options.cluster' => $setting?->pusher_app_cluster]);
            config(['broadcasting.connections.pusher.options.host' => 'api-' . $setting?->pusher_app_cluster . '.pusher.com']);

            Cache::rememberForever('CustomPagination', function () {
                $custom_pagination = CustomPagination::all();
                $pagination = [];
                foreach ($custom_pagination as $item) {
                    $pagination[str_replace(' ', '_', strtolower($item->section_name))] = $item->item_qty;
                }
                $pagination = (object) $pagination;

                return $pagination;
            });
        } catch (Exception $ex) {
            $setting = null;
            Log::error($ex->getMessage());
        }

        try {
            $contactInfo = contactInfo();
        } catch (Exception $e) {
            $setting = (object) ['timezone' => config('app.timezone')];
            $contactInfo = null;
        }
        // setup timezone globally
        date_default_timezone_set($setting?->timezone ?? config('app.timezone'));

        View::composer('*', function ($view) use ($contactInfo) {
            $setting = Cache::get('setting');
            $view->with('setting', $setting);
            $view->with('contactInfo', $contactInfo);
        });

        /**
         * Register custom blade directives
         * this can be used for permission or permissions check
         * this check will be perform on admin guard
         */
        $this->registerBladeDirectives();
        Paginator::useBootstrapFour();
    }

    protected function registerBladeDirectives() {
        Blade::directive('adminCan', function ($permission) {
            return "<?php if(auth()->guard('admin')->user()->can({$permission})): ?>";
        });

        Blade::directive('endadminCan', function () {
            return '<?php endif; ?>';
        });
    }
}
