<?php

namespace Modules\GlobalSetting\app\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\User;
use Database\Seeders\FreshDatabaseSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Modules\GlobalSetting\app\Enums\WebsiteSettingEnum;
use Modules\GlobalSetting\app\Models\CustomCode;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\Setting;
use ZipArchive;

class GlobalSettingController extends Controller {
    protected $cachedSetting;

    public function __construct() {
        $this->cachedSetting = Cache::get('setting');
    }

    public function general_setting() {
        checkAdminHasPermissionAndThrowException('setting.view');

        $custom_paginations = CustomPagination::all();
        $all_timezones = WebsiteSettingEnum::allTimeZones();
        $all_time_format = WebsiteSettingEnum::allTimeFormat();
        $all_date_format = WebsiteSettingEnum::allDateFormat();

        return view('globalsetting::settings.index', compact('custom_paginations', 'all_timezones', 'all_time_format', 'all_date_format'));
    }

    public function update_general_setting(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'app_name'                      => 'sometimes',
            'timezone'                      => 'sometimes',
            'contact_message_receiver_mail' => 'sometimes|email',
            'is_queable'                    => 'sometimes|in:active,inactive',
            'comments_auto_approved'        => 'sometimes|in:active,inactive',
            'client_can_register'           => 'sometimes|in:1,0',
            'lawyer_can_register'           => 'sometimes|in:1,0',
            'lawyer_can_add_social_links'   => 'sometimes|in:active,inactive',
            'lawyer_social_links_limit'     => 'sometimes',
            'prenotification_hour'          => 'sometimes',
            'save_contact_message'          => 'sometimes|in:1,0',
        ], [
            'is_queable.required'                 => __('Queue is required'),
            'contact_message_receiver_mail.email' => __('The contact message receiver mail must be a valid email address.'),
            'is_queable.in'                       => __('Queue is invalid'),
            'comments_auto_approved.in'           => __('Review auto approved is invalid'),
            'client_can_register.in'              => __('Client can register is invalid'),
            'lawyer_can_register.in'              => __('Lawyer can register is invalid'),
            'save_contact_message.in'             => __('Save contact message is invalid'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }
        Cache::forget('setting');
        Cache::forget('corn_working');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_blog_comment(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'comment_type'            => 'required|in:1,0',
            'facebook_comment_script' => [
                'sometimes',
                function ($attribute, $value, $fail) use ($request) {
                    if (!$request->input('comment_type') && empty($value)) {
                        $fail(__('Facebook App ID is required.'));
                    }
                },
            ],
        ], [
            'comment_type.required' => __('Comment type is required'),
            'comment_type.in'       => __('Comment type is invalid'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_color_setting(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'theme_one' => 'required',
            'theme_two' => 'required',
        ], [
            'theme_one.required' => __('Primary Color is required'),
            'theme_two.required' => __('Secondary Color is required'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_preloader_setting(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'preloader' => 'required|in:1,0',
        ], [
            'preloader.required' => __('Comment type is required'),
            'preloader.in'       => __('Comment type is invalid'),
        ]);

        Setting::where('key', 'preloader')->update(['value' => $request->preloader]);
        if ($request->hasFile('preloader_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->preloader_image,
                oldFile: $this->cachedSetting?->preloader_image
            );
            Setting::where('key', 'preloader_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_prescription_contact_setting(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'prescription_email' => 'required',
            'prescription_phone' => 'required',
        ], [
            'prescription_email.required' => __('Email is required'),
            'prescription_phone.required' => __('Phone is required'),
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_logo_favicon(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->hasFile('logo')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->logo,
                oldFile: $this->cachedSetting?->logo
            );
            Setting::where('key', 'logo')->update(['value' => $file_name]);
        }
        if ($request->hasFile('favicon')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->favicon,
                oldFile: $this->cachedSetting?->favicon
            );
            Setting::where('key', 'favicon')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_cookie_consent(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'cookie_status'    => 'required',
            'border'           => 'required',
            'corners'          => 'required',
            'background_color' => 'required',
            'text_color'       => 'required',
            'border_color'     => 'required',
            'btn_bg_color'     => 'required',
            'btn_text_color'   => 'required',
            'link_text'        => 'required',
            'btn_text'         => 'required',
            'message'          => 'required',
        ], [
            'cookie_status.required'    => __('Status is required'),
            'border.required'           => __('Border is required'),
            'corners.required'          => __('Corner is required'),
            'background_color.required' => __('Background color is required'),
            'text_color.required'       => __('Text color is required'),
            'border_color.required'     => __('Border Color is required'),
            'btn_bg_color.required'     => __('Button color is required'),
            'btn_text_color.required'   => __('Button text color is required'),
            'link_text.required'        => __('Link text is required'),
            'btn_text.required'         => __('Button text is required'),
            'message.required'          => __('Message is required'),
        ]);

        Setting::where('key', 'cookie_status')->update(['value' => $request->cookie_status]);
        Setting::where('key', 'border')->update(['value' => $request->border]);
        Setting::where('key', 'corners')->update(['value' => $request->corners]);
        Setting::where('key', 'background_color')->update(['value' => $request->background_color]);
        Setting::where('key', 'text_color')->update(['value' => $request->text_color]);
        Setting::where('key', 'border_color')->update(['value' => $request->border_color]);
        Setting::where('key', 'btn_bg_color')->update(['value' => $request->btn_bg_color]);
        Setting::where('key', 'btn_text_color')->update(['value' => $request->btn_text_color]);
        Setting::where('key', 'link_text')->update(['value' => $request->link_text]);
        Setting::where('key', 'btn_text')->update(['value' => $request->btn_text]);
        Setting::where('key', 'message')->update(['value' => $request->message]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_custom_pagination(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        foreach ($request->quantities as $index => $quantity) {
            if ($request->quantities[$index] == '') {
                $notification = [
                    'message'    => __('Every field are required'),
                    'alert-type' => 'error',
                ];

                return redirect()->back()->with($notification);
            }

            $custom_pagination = CustomPagination::find($request->ids[$index]);
            $custom_pagination->item_qty = $request->quantities[$index];
            $custom_pagination->save();
        }

        // Cache update
        $custom_pagination = CustomPagination::all();
        $pagination = [];
        foreach ($custom_pagination as $item) {
            $pagination[str_replace(' ', '_', strtolower($item->section_name))] = $item->item_qty;
        }
        $pagination = (object) $pagination;
        Cache::put('CustomPagination', $pagination);

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function update_default_avatar(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'default_avatar' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'default_avatar.required' => __('The image is required.'),
            'default_avatar.image'    => __('The image must be an image.'),
            'default_avatar.max'      => __('The image may not be greater than 2048 kilobytes.'),
        ]);

        if ($request->hasFile('default_avatar')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->default_avatar,
                oldFile: $this->cachedSetting?->default_avatar
            );
            Setting::where('key', 'default_avatar')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function update_breadcrumb(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'breadcrumb_image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'breadcrumb_image.required' => __('The image is required.'),
            'breadcrumb_image.image'    => __('The image must be an image.'),
            'breadcrumb_image.max'      => __('The image may not be greater than 2048 kilobytes.'),
        ]);

        if ($request->hasFile('breadcrumb_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->breadcrumb_image,
                oldFile: $this->cachedSetting?->breadcrumb_image
            );
            Setting::where('key', 'breadcrumb_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function crediential_setting() {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::credientials.index');
    }

    public function update_google_captcha(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'recaptcha_site_key'   => 'required',
            'recaptcha_secret_key' => 'required',
            'recaptcha_status'     => 'required',
        ], [
            'recaptcha_site_key.required'   => __('Site key is required'),
            'recaptcha_secret_key.required' => __('Secret key is required'),
            'recaptcha_status.required'     => __('Status is required'),
        ]);

        Setting::where('key', 'recaptcha_site_key')->update(['value' => $request->recaptcha_site_key]);
        Setting::where('key', 'recaptcha_secret_key')->update(['value' => $request->recaptcha_secret_key]);
        Setting::where('key', 'recaptcha_status')->update(['value' => $request->recaptcha_status]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_google_tag(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'googel_tag_status' => 'required',
            'googel_tag_id'     => 'required',
        ], [
            'googel_tag_status.required' => __('Status is required'),
            'googel_tag_id.required'     => __('Google Tag ID is required'),
        ]);

        Setting::where('key', 'googel_tag_status')->update(['value' => $request->googel_tag_status]);
        Setting::where('key', 'googel_tag_id')->update(['value' => $request->googel_tag_id]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_tawk_chat(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'tawk_status'    => 'required',
            'tawk_chat_link' => 'required',
        ], [
            'tawk_status.required'    => __('Status is required'),
            'tawk_chat_link.required' => __('Chat link is required'),
        ]);

        Setting::where('key', 'tawk_status')->update(['value' => $request->tawk_status]);
        Setting::where('key', 'tawk_chat_link')->update(['value' => $request->tawk_chat_link]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_google_analytic(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'google_analytic_status' => 'required',
            'google_analytic_id'     => 'required',
        ], [
            'google_analytic_status.required' => __('Status is required'),
            'google_analytic_id.required'     => __('Analytic id is required'),
        ]);

        Setting::where('key', 'google_analytic_status')->update(['value' => $request->google_analytic_status]);
        Setting::where('key', 'google_analytic_id')->update(['value' => $request->google_analytic_id]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_facebook_pixel(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pixel_status' => 'required',
            'pixel_app_id' => 'required',
        ], [
            'pixel_status.required' => __('Status is required'),
            'pixel_app_id.required' => __('App id is required'),
        ]);

        Setting::where('key', 'pixel_status')->update(['value' => $request->pixel_status]);
        Setting::where('key', 'pixel_app_id')->update(['value' => $request->pixel_app_id]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_social_login(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'google_login_status' => 'required',
            'gmail_client_id'     => 'required',
            'gmail_secret_id'     => 'required',
        ];
        $customMessages = [
            'google_login_status.required' => __('Google is required'),
            'gmail_client_id.required'     => __('Google client is required'),
            'gmail_secret_id.required'     => __('Google secret is required'),
        ];
        $request->validate($rules, $customMessages);

        Setting::where('key', 'facebook_login_status')->update(['value' => $request->facebook_login_status]);
        Setting::where('key', 'facebook_app_id')->update(['value' => $request->facebook_app_id]);
        Setting::where('key', 'facebook_app_secret')->update(['value' => $request->facebook_app_secret]);
        Setting::where('key', 'facebook_redirect_url')->update(['value' => $request->facebook_redirect_url]);
        Setting::where('key', 'google_login_status')->update(['value' => $request->google_login_status]);
        Setting::where('key', 'gmail_client_id')->update(['value' => $request->gmail_client_id]);
        Setting::where('key', 'gmail_secret_id')->update(['value' => $request->gmail_secret_id]);
        Setting::where('key', 'gmail_redirect_url')->update(['value' => $request->gmail_redirect_url]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function update_pusher(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $request->validate([
            'pusher_status'      => 'required',
            'pusher_app_id'      => 'required',
            'pusher_app_key'     => 'required',
            'pusher_app_secret'  => 'required',
            'pusher_app_cluster' => 'required',
        ], [
            'pusher_status.required'      => __('Status is required'),
            'pusher_app_id.required'      => __('Pusher App ID is required'),
            'pusher_app_key.required'     => __('Pusher App Key is required'),
            'pusher_app_secret.required'  => __('Pusher App Secret is required'),
            'pusher_app_cluster.required' => __('Pusher App Cluster is required'),
        ]);

        Setting::where('key', 'pusher_status')->update(['value' => $request->pusher_status]);
        Setting::where('key', 'pusher_app_id')->update(['value' => $request->pusher_app_id]);
        Setting::where('key', 'pusher_app_key')->update(['value' => $request->pusher_app_key]);
        Setting::where('key', 'pusher_app_secret')->update(['value' => $request->pusher_app_secret]);
        Setting::where('key', 'pusher_app_cluster')->update(['value' => $request->pusher_app_cluster]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function seo_setting() {
        checkAdminHasPermissionAndThrowException('setting.view');
        $pages = SeoSetting::all();

        return view('globalsetting::seo_setting', compact('pages'));
    }

    public function update_seo_setting(Request $request, $id) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $rules = [
            'seo_title'       => 'required',
            'seo_description' => 'required',
        ];
        $customMessages = [
            'seo_title.required'       => __('SEO title may not be greater than 1000 characters.'),
            'seo_description.required' => __('SEO description is required'),
        ];
        $request->validate($rules, $customMessages);

        $page = SeoSetting::find($id);
        $page->seo_title = $request->seo_title;
        $page->seo_description = $request->seo_description;
        $page->save();

        Cache::forget('seoSetting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);

    }

    public function cache_clear() {
        checkAdminHasPermissionAndThrowException('setting.update');

        return view('globalsetting::cache_clear');
    }

    public function cache_clear_confirm() {
        checkAdminHasPermissionAndThrowException('setting.update');
        Artisan::call('optimize:clear');

        $notification = __('Cache cleared successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function database_clear() {
        checkAdminHasPermissionAndThrowException('setting.view');

        return view('globalsetting::database_clear');
    }

    public function database_clear_success(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate(['password' => 'required'], ['password.required' => __('Password is required')]);

        if (Hash::check($request->password, auth('admin')->user()->password)) {
            Artisan::call('migrate:fresh --force');
            Artisan::call('db:seed', [
                '--class' => FreshDatabaseSeeder::class,
                '--force' => true,
            ]);
            Artisan::call('optimize:clear');

            if (app()->isProduction()) {
                $this->removeDummyFiles();

                // Get all .json files in the lang directory
                $langDirectory = dirname(app_path()) . "/lang";
                $langFiles = File::files($langDirectory);
                foreach ($langFiles as $file) {
                    if ($file->getFilename() !== 'en.json') {
                        File::delete($file->getPathname());
                    }
                }
            }

            $notification = __('Database Cleared Successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

        } else {
            $notification = __('Passwords do not match.');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
        }

        return redirect()->back()->with($notification);

    }
    private function removeDummyFiles() {
        $dummyFolders = [
            public_path('uploads/website-images/dummy'),
            public_path('uploads/custom-images'),
        ];

        foreach ($dummyFolders as $folderPath) {
            $this->deleteFolderAndFiles($folderPath);
        }
    }

    public function customCode($type) {
        checkAdminHasPermissionAndThrowException('setting.view');
        $customCode = CustomCode::first();
        if (!$customCode) {
            $customCode = new CustomCode();
            $customCode->css = '//write your css code here without the style tag';
            $customCode->header_javascript = '//write your javascript here without the script tag';
            $customCode->body_javascript = '//write your javascript here without the script tag';
            $customCode->footer_javascript = '//write your javascript here without the script tag';
            $customCode->save();
        }
        return view('globalsetting::custom_code_' . $type, compact('customCode'));
    }

    public function customCodeUpdate(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');
        $validatedData = $request->validate([
            'css'               => 'sometimes',
            'header_javascript' => 'sometimes',
            'body_javascript'   => 'sometimes',
            'footer_javascript' => 'sometimes',
        ]);

        $customCode = CustomCode::firstOrCreate();
        foreach ($request->except('_token') as $key => $value) {
            $customCode->$key = $value;
        }
        $customCode->save();

        Cache::forget('customCode');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function update_maintenance_mode_status() {
        checkAdminHasPermissionAndThrowException('setting.update');
        $status = $this->cachedSetting?->maintenance_mode == 1 ? 0 : 1;

        Setting::where('key', 'maintenance_mode')->update(['value' => $status]);

        Cache::forget('setting');

        return response()->json([
            'success' => true,
            'message' => __('Updated successfully'),
        ]);
    }

    public function update_maintenance_mode(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');

        $request->validate([
            'maintenance_image'       => 'sometimes',
            'maintenance_title'       => 'required',
            'maintenance_description' => 'required',
        ], [
            'maintenance_title'       => __('Maintenance Mode Title is required'),
            'maintenance_description' => __('Maintenance Mode Description is required'),
        ]);

        if ($request->hasFile('maintenance_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->maintenance_image,
                oldFile: $this->cachedSetting?->maintenance_image
            );
            Setting::where('key', 'maintenance_image')->update(['value' => $file_name]);
        }

        Setting::where('key', 'maintenance_title')->update(['value' => $request->maintenance_title]);
        Setting::where('key', 'maintenance_description')->update(['value' => $request->maintenance_description]);

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function update_error_page_setting(Request $request) {
        checkAdminHasPermissionAndThrowException('setting.update');

        if ($request->hasFile('error_page_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->error_page_image,
                oldFile: $this->cachedSetting?->error_page_image
            );
            Setting::where('key', 'error_page_image')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }

    public function systemUpdate() {
        $zipLoaded = extension_loaded('zip');

        $updateFileDetails = false;
        $files = false;
        $uploadFileSize = false;

        $zipFilePath = public_path('upload/update.zip');
        if ($updateFileDetails = File::exists($zipFilePath)) {
            $uploadFileSize = File::size($zipFilePath);

            $files = $this->getFilesFromZip($zipFilePath);
        }

        return view('globalsetting::auto-update', compact('updateFileDetails', 'uploadFileSize', 'files', 'zipLoaded'));
    }

    public function systemUpdateStore(Request $request) {
        $request->validate([
            'zip_file' => 'required|mimes:zip',
        ]);

        $zipFilePath = public_path('upload/update.zip');

        if (File::exists($zipFilePath)) {
            File::delete($zipFilePath);
        }

        // Store the uploaded file
        $zipFile = $request->file('zip_file');
        $zipFilePath = $zipFile->move(public_path('upload'), 'update.zip');

        if (!$this->isFirstDirUpload($zipFilePath)) {
            File::delete($zipFilePath);
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }

        return back();
    }

    public function systemUpdateRedirect() {
        $zipFilePath = public_path('upload/update.zip');

        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) !== true) {
            File::delete($zipFilePath);
            $notification = __('Corrupted Zip File');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        if (!$this->isFirstDirUpload($zipFilePath)) {
            $notification = __('Invalid Update File Structure');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            $zip->close();
            return redirect()->back()->with($notification);
        }

        $zip->close();

        $this->deleteFolderAndFiles(base_path('update'));

        if ($zip->open($zipFilePath) === true) {
            $zip->extractTo(base_path());
            $zip->close();
        }

        return redirect(url('/update'));
    }

    public function systemUpdateDelete() {
        $zipFilePath = public_path('upload/update.zip');
        File::delete($zipFilePath);

        $this->deleteFolderAndFiles(base_path('update'));

        $notification = __('Deleted Successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];
        return back()->with($notification);
    }

    private function getFilesFromZip($zipFilePath) {
        $files = [];
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $files[] = $fileInfo['name'];
            }
        }
        $zip->close();
        return $files;
    }

    private function deleteFolderAndFiles($dir) {
        if (!is_dir($dir)) {
            return;
        }

        $files = array_diff(scandir($dir), ['.', '..']);

        foreach ($files as $file) {
            $path = $dir . '/' . $file;

            if (is_dir($path)) {
                $this->deleteFolderAndFiles($path);
            } else {
                unlink($path);
            }
        }

        rmdir($dir);
    }

    private function isFirstDirUpload($zipFilePath) {
        $zip = new ZipArchive;
        if ($zip->open($zipFilePath) === TRUE) {
            $firstDir = null;

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $fileInfo = $zip->statIndex($i);
                $filePathParts = explode('/', $fileInfo['name']);

                if (count($filePathParts) > 1) {
                    $firstDir = $filePathParts[0];
                    break;
                }
            }

            $zip->close();
            return $firstDir === "update";
        }

        $zip->close();
        return false;
    }
}
