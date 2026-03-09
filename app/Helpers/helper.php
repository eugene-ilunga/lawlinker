<?php

use Illuminate\Http\UploadedFile;
use Modules\Blog\app\Models\Blog;
use Illuminate\Support\Facades\Log;
use Nwidart\Modules\Facades\Module;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;
use Modules\Lawyer\app\Models\Department;
use Modules\Language\app\Models\Language;
use Modules\HomeSection\app\Models\Partner;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\SocialLink\app\Models\SocialLink;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\BasicPayment\app\Models\BasicPayment;
use Modules\ContactMessage\app\Models\ContactInfo;
use App\Exceptions\AccessPermissionDeniedException;
use Modules\NewsLetter\app\Models\SubscriberContent;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Spatie\LaravelImageOptimizer\Facades\ImageOptimizer;

function file_upload(UploadedFile $file, string $path = 'uploads/custom-images/', string | null $oldFile = '', bool $optimize = false) {
    $extention = $file->getClientOriginalExtension();
    $file_name = 'wsus-img' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extention;
    $file_name = $path . $file_name;
    $file->move(public_path($path), $file_name);

    try {
        if ($oldFile && !str($oldFile)->contains('uploads/website-images') && File::exists(public_path($oldFile))) {
            unlink(public_path($oldFile));
        }

        if ($optimize) {
            ImageOptimizer::optimize(public_path($file_name));
        }
    } catch (Exception $e) {
        Log::info($e->getMessage());
    }

    return $file_name;
}
/**
 * Uploads a file to the specified storage folder and optionally optimizes it.
 *
 * @param  UploadedFile  $file
 * @param  string  $path
 * @param  string|null  $oldFile
 * @return string
 */
function file_upload_storage_folder(UploadedFile $file, string $path = 'uploads/', string $oldFile = ''): string {
    try {
        $path = rtrim($path, '/') . '/';

        $extension = $file->getClientOriginalExtension();
        $timestamp = now()->format('Ymd_His'); 

        $nameWithoutExt = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = "{$nameWithoutExt}_{$timestamp}.{$extension}";


        Storage::putFileAs("public/$path", $file, $fileName);

        if (!empty($oldFile) && Storage::exists($oldFile)) {
            Storage::delete($oldFile);
        }
        return $fileName;
    } catch (\Exception $e) {
        Log::error('File upload error: ' . $e->getMessage());
        return '';
    }
}
/**
 * Uploads an image, optionally resizes it, and deletes old file if provided.
 *
 * @param UploadedFile $file The uploaded file instance.
 * @param string $path The directory path where the image should be saved.
 * @param int[] $resize An array containing width and height dimensions for resizing.
 * @param int[] $crop An array containing crop width and height dimensions. Default is an empty array.
 * @param string|null $oldFile Optional. The path to the old file that needs deletion if not within 'uploads/website-images'.
 * @return string The path to the saved image.
 */
function uploadAndOptimizeImage(UploadedFile $file, ?string $oldFile = '', array $resize = [], array $crop = [], string $path = 'uploads/custom-images/') {
    if (!File::exists(public_path($path))) {
        File::makeDirectory(public_path($path), 0755, true);
    }
    $extension = $file->getClientOriginalExtension();
    $file_name = 'wsus-img' . date('-Y-m-d-h-i-s-') . rand(999, 9999) . '.' . $extension;
    $file_name = $path . $file_name;

    // create image manager with desired driver
    $manager = new ImageManager(new Driver());

    // read image from file system
    $image = $manager->read($file);

    if (!empty($resize)) {
        $image->resize($resize[0], $resize[1]);
    }
    if (!empty($crop)) {
        $image->crop($crop[0], $crop[1]);
    }
    $image->save(public_path($file_name));

    try {
        if ($oldFile && !str($oldFile)->contains('uploads/website-images') && File::exists(public_path($oldFile))) {
            unlink(public_path($oldFile));
        }
    } catch (Exception $e) {
        Log::info($e->getMessage());
    }

    return $file_name;
}
// file upload method
if (!function_exists('allLanguages')) {
    function allLanguages() {
        $allLanguages = Cache::rememberForever('allLanguages', function () {
            return Language::select('code', 'name', 'direction', 'status')->get();
        });

        if (!$allLanguages) {
            $allLanguages = Language::select('code', 'name', 'direction', 'status')->get();
        }

        return $allLanguages;
    }
}
if (!function_exists('customPages')) {
    function customPages() {
        return Cache::rememberForever('customPages', function () {
            return CustomizeablePage::with('translation')->whereNot('slug', 'privacy-policy')->whereNot('slug', 'terms-contidions')->where('status', 1)->get();
        });
    }
}

if (!function_exists('getSessionLanguage')) {
    function getSessionLanguage(): string {
        if (!session()->has('lang')) {
            session()->put('lang', config('app.locale'));
            session()->forget('text_direction');
            session()->put('text_direction', 'ltr');
        }

        $lang = Session::get('lang');

        return $lang;
    }
}
if (!function_exists('setLanguage')) {
    function setLanguage($code) {
        $lang = Language::whereCode($code)->first();

        if (session()->has('lang')) {
            sessionForgetLangChang();
        }
        if ($lang) {
            session()->put('lang', $lang->code);
            session()->put('text_direction', $lang->direction);
            return true;
        }
        session()->put('lang', config('app.locale'));
        return false;
    }
}
if (!function_exists('sessionForgetLangChang')) {
    function sessionForgetLangChang() {
        session()->forget('lang');
        session()->forget('text_direction');
        cache()->forget('contactInfo');
    }
}

if (!function_exists('allCurrencies')) {
    function allCurrencies() {
        $allCurrencies = Cache::rememberForever('allCurrencies', function () {
            return MultiCurrency::all();
        });

        if (!$allCurrencies) {
            $allCurrencies = MultiCurrency::all();
        }

        return $allCurrencies;
    }
}

if (!function_exists('getSessionCurrency')) {
    function getSessionCurrency(): string {
        if (!session()->has('currency_code') || !session()->has('currency_rate') || !session()->has('currency_position')) {
            $currency = allCurrencies()->where('is_default', 'yes')->first();
            session()->put('currency_code', $currency->currency_code);
            session()->forget('currency_position');
            session()->put('currency_position', $currency->currency_position);
            session()->forget('currency_icon');
            session()->put('currency_icon', $currency->currency_icon);
            session()->forget('currency_rate');
            session()->put('currency_rate', $currency->currency_rate);
        }

        return Session::get('currency_code');
    }
}

function admin_lang() {
    return Session::get('admin_lang');
}

// calculate currency
if (!function_exists('currency')) {
    function currency($price, $icon = true) {
        getSessionCurrency();
        $currency_icon = Session::get('currency_icon');
        $currency_rate = Session::has('currency_rate') ? Session::get('currency_rate') : 1;
        $currency_position = Session::get('currency_position');

        $price = $price * $currency_rate;

        if ($icon) {
            $price = number_format($price, 2, '.', ',');

            switch ($currency_position) {
            case 'before_price':
                $price = $currency_icon . $price;
                break;
            case 'before_price_with_space':
                $price = $currency_icon . ' ' . $price;
                break;
            case 'after_price':
                $price = $price . $currency_icon;
                break;
            case 'after_price_with_space':
                $price = $price . ' ' . $currency_icon;
                break;
            default:
                $price = $currency_icon . $price;
                break;
            }
        } else {
            $price = number_format($price, 2, '.', '');
        }

        return $price;
    }
}
// specific currency
if (!function_exists('specific_currency_with_icon')) {
    /**
     * Format a given price with the specified currency.
     *
     * @param string $currency_code The currency code (e.g., USD, EUR).
     * @param float $price The amount to be formatted.
     * @return string The formatted price with the currency symbol.
     */
    function specific_currency_with_icon($currency_code, $price) {
        return "{$price} ({$currency_code})";
    }
}
// calculate currency
if (!function_exists('apiCurrency')) {
    function apiCurrency($code, $price, $icon = true) {
        $currency = allCurrencies()->where('currency_code', $code)->first();
        if (!$currency) {
            $currency = allCurrencies()->where('currency_code', 'USD')->first();
        }
        $currency_icon = $currency->currency_icon;
        $currency_rate = $currency->currency_rate ?? 1;
        $currency_position = $currency->currency_position;

        $price = $price * $currency_rate;

        if ($icon) {
            $price = number_format($price, 2, '.', ',');

            switch ($currency_position) {
            case 'before_price':
                $price = $currency_icon . $price;
                break;
            case 'before_price_with_space':
                $price = $currency_icon . ' ' . $price;
                break;
            case 'after_price':
                $price = $price . $currency_icon;
                break;
            case 'after_price_with_space':
                $price = $price . ' ' . $currency_icon;
                break;
            default:
                $price = $currency_icon . $price;
                break;
            }
        } else {
            $price = number_format($price, 2, '.', '');
        }

        return $price;
    }
}

// custom decode and encode input value
function html_decode($text) {
    $after_decode = htmlspecialchars_decode($text, ENT_QUOTES);

    return $after_decode;
}
if (!function_exists('currectUrlWithQuery')) {
    function currectUrlWithQuery($code) {
        $currentUrlWithQuery = request()->fullUrl();

        // Parse the query string
        $parsedQuery = parse_url($currentUrlWithQuery, PHP_URL_QUERY);

        // Check if the 'code' parameter already exists
        $codeExists = false;
        if ($parsedQuery) {
            parse_str($parsedQuery, $queryArray);
            $codeExists = isset($queryArray['code']);
        }

        if ($codeExists) {
            $updatedUrlWithQuery = preg_replace('/(\?|&)code=[^&]*/', '$1code=' . $code, $currentUrlWithQuery);
        } else {
            $updatedUrlWithQuery = $currentUrlWithQuery . ($parsedQuery ? '&' : '?') . http_build_query(['code' => $code]);
        }
        return $updatedUrlWithQuery;
    }
}

if (!function_exists('checkAdminHasPermission')) {
    function checkAdminHasPermission($permission): bool {
        return Auth::guard('admin')->user()->can($permission) ? true : false;
    }
}

if (!function_exists('checkAdminHasPermissionAndThrowException')) {
    function checkAdminHasPermissionAndThrowException($permission) {
        if (!checkAdminHasPermission($permission)) {
            throw new AccessPermissionDeniedException();
        }
    }
}

if (!function_exists('getSettingStatus')) {
    function getSettingStatus($key) {
        if (Cache::has('setting')) {
            $setting = Cache::get('setting');
            if (!is_null($key)) {
                return $setting->$key == 'active' ? true : false;
            }
        } else {
            try {
                return Setting::where('key', $key)->first()?->value == 'active' ? true : false;
            } catch (Exception $e) {
                Log::info($e->getMessage());
                return false;
            }
        }

        return false;
    }
}
if (!function_exists('checkCredentials')) {
    function checkCredentials() {
        $checkCredentials = [];
        if (Cache::has('setting') && $settings = Cache::get('setting')) {

            if ($settings->mail_host == 'mail_host' || $settings->mail_username == 'mail_username' || $settings->mail_password == 'mail_password' || $settings->mail_host == '' || $settings->mail_port == '' || $settings->mail_username == '' || $settings->mail_password == '') {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Mail credentials not found'),
                    'description' => __('This may create a problem while sending email. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.email-configuration',
                ];
            }

            if ($settings->recaptcha_status !== 'inactive' && ($settings->recaptcha_site_key == 'recaptcha_site_key' || $settings->recaptcha_secret_key == 'recaptcha_secret_key' || $settings->recaptcha_site_key == '' || $settings->recaptcha_secret_key == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Google Recaptcha credentials not found'),
                    'description' => __('This may create a problem while submitting any form submission from website. Please fill up the credential from google account.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }
            if ($settings->googel_tag_status !== 'inactive' && ($settings->googel_tag_id == 'googel_tag_id' || $settings->googel_tag_id == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Google Tag credentials not found'),
                    'description' => __('This may create a problem with analyzing your website through Google Tag Manager. Please fill in the credentials to avoid any issues.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->pixel_status !== 'inactive' && ($settings->pixel_app_id == 'pixel_app_id' || $settings->pixel_app_id == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Facebook Pixel credentials not found'),
                    'description' => __('This may create a problem to analyze your website. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->google_login_status !== 'inactive' && ($settings->gmail_client_id == 'google_client_id' || $settings->gmail_secret_id == 'google_secret_id' || $settings->gmail_client_id == '' || $settings->gmail_secret_id == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Google login credentials not found'),
                    'description' => __('This may create a problem while logging in using google. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->google_analytic_status !== 'inactive' && ($settings->google_analytic_id == 'google_analytic_id' || $settings->google_analytic_id == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Google Analytic credentials not found'),
                    'description' => __('This may create a problem to analyze your website. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->tawk_status !== 'inactive' && ($settings->tawk_chat_link == 'tawk_chat_link' || $settings->tawk_chat_link == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Tawk Chat Link credentials not found'),
                    'description' => __('This may create a problem to analyze your website. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }

            if ($settings->pusher_status !== 'inactive' && ($settings->pusher_app_id == 'pusher_app_id' || $settings->pusher_app_key == 'pusher_app_key' || $settings->pusher_app_secret == 'pusher_app_secret' || $settings->pusher_app_cluster == 'pusher_app_cluster' || $settings->pusher_app_id == '' || $settings->pusher_app_key == '' || $settings->pusher_app_secret == '' || $settings->pusher_app_cluster == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Pusher credentials not found'),
                    'description' => __('This may create a problem while logging in using google. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.crediential-setting',
                ];
            }
        }

        if (!Cache::has('basic_payment') && Module::isEnabled('BasicPayment')) {
            Cache::rememberForever('basic_payment', function () {
                $payment_info = BasicPayment::get();
                $basic_payment = [];
                foreach ($payment_info as $payment_item) {
                    $basic_payment[$payment_item->key] = $payment_item->value;
                }

                return (object) $basic_payment;
            });
        }

        if (Cache::has('basic_payment') && $basicPayment = Cache::get('basic_payment')) {
            if ($basicPayment->stripe_status !== 'inactive' && ($basicPayment->stripe_secret == 'stripe_secret' || $basicPayment->stripe_secret == '')) {

                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Stripe credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->paypal_status !== 'inactive' && ($basicPayment->paypal_client_id == 'paypal_client_id' || $basicPayment->paypal_secret_key == 'paypal_secret_key' || $basicPayment->paypal_client_id == '' || $basicPayment->paypal_secret_key == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Paypal credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->razorpay_status !== 'inactive' && ($basicPayment->razorpay_key == 'razorpay_key' || $basicPayment->razorpay_secret == 'razorpay_secret' || $basicPayment->razorpay_key == '' || $basicPayment->razorpay_secret == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Razorpay credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->flutterwave_status !== 'inactive' && ($basicPayment->flutterwave_public_key == 'flutterwave_public_key' || $basicPayment->flutterwave_secret_key == 'flutterwave_secret_key' || $basicPayment->flutterwave_public_key == '' || $basicPayment->flutterwave_secret_key == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Flutterwave credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->paystack_status !== 'inactive' && ($basicPayment->paystack_public_key == 'paystack_public_key' || $basicPayment->paystack_secret_key == 'paystack_secret_key' || $basicPayment->paystack_public_key == '' || $basicPayment->paystack_secret_key == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Paystack credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }

            if ($basicPayment->mollie_status !== 'inactive' && ($basicPayment->mollie_key == 'mollie_key' || $basicPayment->mollie_key == '')) {
                $checkCredentials[] = (object) [
                    'status'      => true,
                    'message'     => __('Mollie credentials not found'),
                    'description' => __('This may create a problem while making payment. Please fill up the credential to avoid any problem.'),
                    'route'       => 'admin.basicpayment',
                ];
            }
        }

        return (object) $checkCredentials;
    }
}

if (!function_exists('isRoute')) {
    function isRoute(string | array $route, string $returnValue = null) {
        if (is_array($route)) {
            foreach ($route as $value) {
                if (Route::is($value)) {
                    return is_null($returnValue) ? true : $returnValue;
                }
            }
            return false;
        }

        if (Route::is($route)) {
            return is_null($returnValue) ? true : $returnValue;
        }

        return false;
    }
}
if (!function_exists('seoSetting')) {
    function seoSetting() {
        try {
            if (!Cache::has('seoSetting')) {
                $seoSetting = Cache::rememberForever('seoSetting', function () {
                    return SeoSetting::all();
                });
            } else {
                $seoSetting = Cache::get('seoSetting');
            }
        } catch (\Exception $e) {
            info($e);
            $seoSetting = SeoSetting::all();
        }

        return $seoSetting;
    }
}
if (!function_exists('contactInfo')) {
    function contactInfo() {
        return Cache::rememberForever('contactInfo', function () {
            return ContactInfo::first();
        });
    }
}
if (!function_exists('subscriberContent')) {
    function subscriberContent() {
        return SubscriberContent::select('id', 'image')->with(['translation' => function ($q) {
            $q->select('subscriber_content_id', 'title', 'description');
        }])->first();
    }
}
if (!function_exists('getPartners')) {
    function getPartners() {
        return Cache::rememberForever('getPartners', function () {
            return Partner::select('image', 'link')->active()->get();
        });
    }
}
if (!function_exists('footerLatestNews')) {
    function footerLatestNews() {
        return Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with([
            'translation' => function ($query) {
                $query->select('blog_id', 'title');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->active()->homepage()->latest()->take(2)->get();
    }
}
if (!function_exists('getSocialLinks')) {
    function getSocialLinks() {
        return Cache::rememberForever('getSocialLinks', function () {
            return SocialLink::select('icon', 'link')->get();
        });
    }
}
if (!function_exists('getDepartments')) {
    function getDepartments() {
        return Department::select('id')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->get();
    }
}
if (!function_exists('userAuth')) {
    function userAuth() {
        return Auth::guard('web')->user();
    }
}
if (!function_exists('lawyerAuth')) {
    function lawyerAuth() {
        return Auth::guard('lawyer')->user();
    }
}
if (!function_exists('customCode')) {
    function customCode() {
        return Cache::rememberForever('customCode', function () {
            return CustomCode::select('css', 'header_javascript', 'body_javascript', 'footer_javascript')->first();
        });
    }
}