<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Traits\GlobalMailTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\App\app\Models\OnBoardingScreen;
use Modules\Blog\app\Models\Blog;
use Modules\ContactMessage\app\Models\ContactInfo;
use Modules\ContactMessage\app\Models\ContactMessage;
use Modules\Currency\app\Models\MultiCurrency;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Lawyer\app\Models\Location;
use Modules\GlobalSetting\app\Models\SeoSetting;
use Modules\GlobalSetting\app\Models\Setting;
use Modules\HomeSection\app\Models\Partner;
use Modules\Language\app\Models\Language;
use Modules\NewsLetter\app\Models\NewsLetter;
use Modules\NewsLetter\app\Models\SubscriberContent;
use Modules\Service\app\Models\Service;
use Modules\SocialLink\app\Models\SocialLink;

class FrontendController extends Controller {
    use GlobalMailTrait;
    public function settings(): JsonResponse {
        $setting_list = ['app_name','app_banner', 'logo', 'favicon', 'default_avatar', 'breadcrumb_image', 'timezone', 'date_format', 'time_format', 'maintenance_mode', 'maintenance_image', 'maintenance_title', 'maintenance_description', 'client_can_register', 'lawyer_can_register', 'prescription_phone', 'prescription_email', 'pusher_app_key', 'pusher_app_cluster', 'pusher_status', 'app_login_img', 'app_forgot_password_img','lawyer_can_add_social_links','lawyer_social_links_limit'];

        $settings = Setting::whereIn('key', $setting_list)->pluck('value', 'key');
        if ($settings) {
            $settings['order_success_image'] = 'uploads/website-images/success.png';
            $settings['order_cancel_image'] = 'uploads/website-images/cancel.png';
            return response()->json(['status' => 'success', 'data' => $settings], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function departments(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $take = request()->query('take', -1);
        $departments = Department::select('id', 'slug')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('department_id', 'name');
        }])->active()
            ->whereHas('translations', function ($query) {
                $query->orderBy('name', 'asc');
            })->take($take)->get();
        if ($departments) {
            return response()->json(['status' => 'success', 'data' => $departments], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function locations(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $take = request()->query('take', -1);
        $locations = Location::select('id')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('location_id', 'name');
        }])->active()->whereHas('translations', function ($query) {
            $query->orderBy('name', 'asc');
        })->take($take)->get();
        if ($locations) {
            return response()->json(['status' => 'success', 'data' => $locations], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function lawyers(Request $request): JsonResponse {
        $take = request()->query('take', -1);
        $location_id = $request->query('location');
        $department_id = $request->query('department');

        $lawyers = Lawyer::select('id', 'slug', 'name')->orderBy('name', 'asc')->active()->verify();
        if ($location_id) {
            $lawyers = $lawyers->where('location_id', $location_id);
        }
        if ($department_id) {
            $lawyers = $lawyers->where('department_id', $department_id);
        }
        $lawyers = $lawyers->take($take)->get();

        if ($lawyers) {
            return response()->json(['status' => 'success', 'data' => $lawyers], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function services(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $take = request()->query('take', -1);
        $services = Service::select('id', 'slug')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('service_id', 'title');
            },
        ])->active()->take($take)->get();
        if ($services) {
            return response()->json(['status' => 'success', 'data' => $services], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function socialLinks(): JsonResponse {
        $socialLinks = SocialLink::select('icon', 'link')->get();
        if ($socialLinks) {
            return response()->json(['status' => 'success', 'data' => $socialLinks], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function contactInfo(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $contactInfo = ContactInfo::with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('contact_info_id', 'header', 'description', 'about', 'copyright')->first();
            },
        ])->first();


        preg_match('/<iframe\s+src="([^"]+)"/', $contactInfo?->map_embed_code, $matches);
        $data = [
            "top_bar_email"  => $contactInfo?->top_bar_email,
            "top_bar_phone"  => $contactInfo?->top_bar_phone,
            "email"          => $contactInfo?->email,
            "phone"          => $contactInfo?->phone,
            "address"        => $contactInfo?->address,
            "map_embed_code" => $matches[1],

            "header"         => $contactInfo?->translations[0]?->header,
            "description"    => $contactInfo?->translations[0]?->description,
            "about"          => $contactInfo?->translations[0]?->about,
            "copyright"      => $contactInfo?->translations[0]?->copyright,
        ];

        if ($contactInfo) {
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function allLanguages(): JsonResponse {
        $allLanguages = Language::select('code', 'name', 'direction', 'is_default', 'status')->get();
        if ($allLanguages) {
            return response()->json(['status' => 'success', 'data' => $allLanguages], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function allCurrency(): JsonResponse {
        $allCurrency = MultiCurrency::all();
        if ($allCurrency) {
            return response()->json(['status' => 'success', 'data' => $allCurrency], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function partners(): JsonResponse {
        $partners = Partner::select('image', 'link')->active()->get();
        if ($partners) {
            return response()->json(['status' => 'success', 'data' => $partners], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function seoSetting(): JsonResponse {
        $seoSetting = SeoSetting::all();
        if ($seoSetting) {
            return response()->json(['status' => 'success', 'data' => $seoSetting], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function subscriberContent(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));

        $subscriberContent = SubscriberContent::select('id', 'image')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('subscriber_content_id', 'title', 'description');
        }])->first();

        if ($subscriberContent) {
            return response()->json(['status' => 'success', 'data' => $subscriberContent], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function footerLatestNews(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $take = strtolower(request()->query('take', 3));

        $blogs = Blog::select('id', 'slug', 'thumbnail_image', 'image', 'created_at')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
        }])->whereHas('category', function ($query) {
            $query->active();
        })->active()->homepage()->latest()->take($take)->get();

        if ($blogs) {
            return response()->json(['status' => 'success', 'data' => $blogs], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function contactUs(Request $request) {
        $setting = Cache::get('setting');

        $validator = Validator::make($request->all(), [
            'name'    => 'required',
            'email'   => 'required|email',
            'phone'   => 'sometimes',
            'subject' => 'required',
            'message' => 'required',
        ], [
            'name.required'    => __('Name is required'),
            'email.required'   => __('Email is required'),
            'subject.required' => __('Subject is required'),
            'message.required' => __('Message is required'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        // Save contact message if the setting allows it
        if ($setting?->save_contact_message) {
            $new_message = new ContactMessage();
            $new_message->name = $request->name;
            $new_message->email = $request->email;
            $new_message->subject = $request->subject;
            $new_message->message = $request->message;
            $new_message->phone = $request->phone;
            $new_message->save();
        }

        // Prepare the email
        $str_replace = [
            'name'    => $request->name,
            'email'   => $request->email,
            'phone'   => $request->phone,
            'subject' => $request->subject,
            'message' => $request->message,
        ];

        // Send the email
        try {
            [$subject, $message] = $this->fetchEmailTemplate('contact_mail', $str_replace);
            $this->sendMail($setting->contact_message_receiver_mail, $subject, $message);
        } catch (\Exception $e) {
            info($e->getMessage());
            return $this->handleMailException($e, true);
        }
        return response()->json(['status' => 'success', 'message' => __('Message Sent Successfully')], 200);
    }
    public function newsletter_request(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:news_letters',
        ], [
            'email.required' => __('Email is required'),
            'email.unique'   => __('Email already exists'),
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        // Save the newsletter data
        $newsletter = new NewsLetter();
        $newsletter->email = $request->email;
        $newsletter->verify_token = Str::random(100);
        $newsletter->save();

        // Prepare email for sending
        [$subject, $message] = $this->fetchEmailTemplate('subscribe_notification');
        $link = [__('CONFIRM YOUR EMAIL') => route('newsletter-verification', $newsletter->verify_token)];

        // Send email
        try {
            $this->sendMail($newsletter->email, $subject, $message, $link);
        } catch (\Exception $e) {
            // Log the error and return a failure response
            info($e->getMessage());
            return response()->json(['status' => 'error', 'message' => __('Failed to send verification email')], 500);
        }

        // Return success response
        return response()->json(['status' => 'success', 'message' => __('A verification link has been sent to your email, please verify it to receive our newsletter')], 200);
    }

    public function getLanguageFile($code = 'en'): JsonResponse {
        $filePath = base_path('lang/' . $code . '.json');
        if (File::exists($filePath)) {
            $data = json_decode(File::get($filePath), true);
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!', 'code' => $code], 404);
    }
    public function boardingScreen(): JsonResponse {
        $data = OnBoardingScreen::active()->get();
        if ($data) {
            return response()->json(['status' => 'success', 'data' => $data], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

}
