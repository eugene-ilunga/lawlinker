<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AboutUsPage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Blog\app\Models\Blog;
use Modules\Blog\app\Models\BlogCategory;
use Modules\Day\app\Models\Day;
use Modules\Lawyer\app\Models\Department;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Faq\app\Models\FaqCategory;
use Modules\GlobalSetting\app\Models\CustomPagination;
use Modules\HomeSection\app\Models\Counter;
use Modules\HomeSection\app\Models\Feature;
use Modules\HomeSection\app\Models\SectionControl;
use Modules\HomeSection\app\Models\WorkSection;
use Modules\HomeSection\app\Models\WorkSectionFaq;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\Schedule\app\Models\Schedule;
use Modules\Service\app\Models\Service;
use Modules\Testimonial\app\Models\Testimonial;

class AllPagesController extends Controller {
    public function index(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));

        $home_sections = SectionControl::with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code);
            },
        ])->first();

        $features = Feature::select('id', 'image', 'icon')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('feature_id', 'title', 'description');
        }])->active()->latest()->take($home_sections?->feature_how_many)->get();

        $work = WorkSection::select('id', 'image', 'video')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('work_section_id', 'title');
        }])->first();

        $workFaqs = WorkSectionFaq::select('id')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('work_section_faq_id', 'question', 'answer');
        }])->where('work_section_id', $work?->id)->active()->latest()->take($home_sections?->work_how_many)->get();

        $services = Service::select('id', 'icon', 'slug')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('service_id', 'title', 'sort_description');
        }])->active()->homepage()->latest()->take($home_sections?->service_how_many)->get();

        $overviews = Counter::select('id', 'icon', 'qty')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('counter_id', 'title');
        }])->active()->latest()->get();

        $departments = Department::select('id', 'slug', 'thumbnail_image')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('department_id', 'name');
        }])->active()->homepage()->latest()->take($home_sections?->department_how_many)->get();

        $testimonials = Testimonial::select('id', 'image')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('testimonial_id', 'name', 'designation', 'comment');
        }])->homepage()->active()->latest()->take($home_sections?->client_how_many)->get();

        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translations'            => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('lawyer_id', 'designations');
            },
            'department'              => function ($query) {
                $query->select('id');
            },
            'department.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name');
            },
            'location'                => function ($query) {
                $query->select('id');
            },
            'location.translations'   => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->homepage()->active()->verify()->latest()->take($home_sections?->lawyer_how_many)->get();

        $feature_blog = Blog::select('id', 'slug', 'image', 'created_at')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
        }])->whereHas('category', function ($query) {
            $query->active();
        })->feature()->active()->orderBy('id', 'desc')->first();

        $blogs = Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
        }])->whereHas('category', function ($query) {
            $query->active();
        })->homepage()->active()->orderBy('id', 'desc')->take($home_sections?->blog_how_many)->get();

        $data = [
            'features'     => [
                'show' => $home_sections?->feature_status,
                'data' => $features,
            ],
            'work'         => [
                'show'           => $home_sections?->work_status,
                'first_heading'  => $home_sections?->translations[0]?->work_first_heading,
                'second_heading' => $home_sections?->translations[0]?->work_second_heading,
                'description'    => $home_sections?->translations[0]?->work_description,
                'data'           => $work,
                'faq_list'       => $workFaqs,
            ],
            'services'     => [
                'show'           => $home_sections?->service_status,
                'first_heading'  => $home_sections?->translations[0]?->service_first_heading,
                'second_heading' => $home_sections?->translations[0]?->service_second_heading,
                'description'    => $home_sections?->translations[0]?->service_description,
                'data'           => $services,
            ],
            'overviews'    => $overviews,
            'departments'  => [
                'show'           => $home_sections?->department_status,
                'first_heading'  => $home_sections?->translations[0]?->department_first_heading,
                'second_heading' => $home_sections?->translations[0]?->department_second_heading,
                'description'    => $home_sections?->translations[0]?->department_description,
                'data'           => $departments,
            ],
            'testimonials' => [
                'show'           => $home_sections?->client_status,
                'first_heading'  => $home_sections?->translations[0]?->client_first_heading,
                'second_heading' => $home_sections?->translations[0]?->client_second_heading,
                'description'    => $home_sections?->translations[0]?->client_description,
                'data'           => $testimonials,
            ],
            'lawyers'      => [
                'show'           => $home_sections?->lawyer_status,
                'first_heading'  => $home_sections?->translations[0]?->lawyer_first_heading,
                'second_heading' => $home_sections?->translations[0]?->lawyer_second_heading,
                'description'    => $home_sections?->translations[0]?->lawyer_description,
                'data'           => $lawyers,
            ],
            'blogs'        => [
                'show'           => $home_sections?->blog_status,
                'first_heading'  => $home_sections?->translations[0]?->blog_first_heading,
                'second_heading' => $home_sections?->translations[0]?->blog_second_heading,
                'description'    => $home_sections?->translations[0]?->blog_description,
                'feature_blog'   => $feature_blog,
                'data'           => $blogs,
            ],
        ];
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function aboutUs(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $about = AboutUsPage::select('id', 'status', 'about_image', 'background_image', 'mission_image', 'mission_status', 'vision_image', 'vision_status')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('about_us_page_id', 'about_description', 'mission_description', 'vision_description');
            },
        ])->first();
        $overviews = Counter::select('id', 'icon', 'qty')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('counter_id', 'title');
        }])->active()->latest()->get();

        $home_sections = SectionControl::select('id', 'work_status')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('section_control_id', 'work_first_heading', 'work_second_heading', 'work_description');
            },
        ])->first();

        $work = WorkSection::select('id', 'image', 'video')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('work_section_id', 'title');
        }])->first();

        $workFaqs = WorkSectionFaq::select('id')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('work_section_faq_id', 'question', 'answer');
        }])->where('work_section_id', $work?->id)->active()->latest()->get();

        $data = [
            'about'     => $about,
            'overviews' => $overviews,
            'work'      => [
                'show'           => $home_sections?->work_status,
                'first_heading'  => $home_sections?->translations[0]?->work_first_heading,
                'second_heading' => $home_sections?->translations[0]?->work_second_heading,
                'description'    => $home_sections?->translations[0]?->work_description,
                'data'           => $work,
                'faq_list'       => $workFaqs,
            ],
        ];
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function service(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $pagination_qty = CustomPagination::where('section_name', 'Service')->select('item_qty')->first()?->item_qty ?? 10;

        $services = Service::select('id', 'icon', 'slug')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('service_id', 'title', 'sort_description');
            },
        ])->active()->paginate($pagination_qty);
        if ($services->count()) {
            return response()->json(['status' => 'success', 'data' => $services], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function serviceDetails($slug): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $service = Service::select('id', 'icon', 'slug')->with([
            'translations'             => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('service_id', 'title', 'description', 'seo_title', 'seo_description');
            },
            'images'                   => function ($query) {
                $query->select('service_id', 'small_image', 'large_image');
            },
            'service_faq'              => function ($query) {
                $query->select('id', 'service_id')->active();
            },
            'service_faq.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('service_faq_id', 'question', 'answer');
            },
            'videos'                   => function ($query) {
                $query->select('service_id', 'link');
            },
        ])->whereSlug($slug)->active()->first();

        if ($service) {
            return response()->json(['status' => 'success', 'data' => $service], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function department(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $pagination_qty = CustomPagination::where('section_name', 'Department')->select('item_qty')->first()?->item_qty ?? 10;
        $departments = Department::select('id', 'slug', 'thumbnail_image')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name');
            },
        ])->active()->paginate($pagination_qty);
        if ($departments->count()) {
            return response()->json(['status' => 'success', 'data' => $departments], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function departmentDetails($slug): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $department = Department::select('id', 'slug', 'thumbnail_image')->with([
            'translations'                => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name', 'description', 'seo_title', 'seo_description');
            },
            'images'                      => function ($query) {
                $query->select('department_id', 'small_image', 'large_image');
            },
            'department_faq'              => function ($query) {
                $query->select('id', 'department_id')->active();
            },
            'department_faq.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_faq_id', 'question', 'answer');
            },
            'videos'                      => function ($query) {
                $query->select('department_id', 'link');
            },
        ])->whereSlug($slug)->active()->first();

        if (!$department) {
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        }

        $home_sections = SectionControl::select('id')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('section_control_id', 'department_description');
            },
        ])->first();
        $description = $home_sections?->translations[0]?->department_description;
        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translations'            => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('lawyer_id', 'designations');
            },
            'department'              => function ($query) {
                $query->select('id');
            },
            'department.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name');
            },
            'location'                => function ($query) {
                $query->select('id');
            },
            'location.translations'   => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->where('department_id', $department->id)->active()->verify()->get();

        $data = [
            'data'                       => $department,
            'lawyer_section_description' => $description,
            'department_lawyers'         => $lawyers,
        ];

        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function lawyers(Request $request): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $location_id = $request->query('location');
        $lawyer_id = $request->query('lawyer');
        $department_id = $request->query('department');

        $pagination_qty = CustomPagination::where('section_name', 'Lawyer')->select('item_qty')->first()?->item_qty ?? 10;

        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translations'            => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('lawyer_id', 'designations');
            },
            'department'              => function ($query) {
                $query->select('id');
            },
            'department.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name');
            },
            'location'                => function ($query) {
                $query->select('id');
            },
            'location.translations'   => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->orderBy('name', 'asc')->active()->verify();

        if ($location_id) {
            $lawyers = $lawyers->where('location_id', $location_id);
        }

        if ($department_id) {
            $lawyers = $lawyers->where('department_id', $department_id);
        }

        if ($lawyer_id) {
            $lawyers = $lawyers->where('id', $lawyer_id);
        }

        // Paginate and append query parameters
        $lawyers = $lawyers->paginate($pagination_qty);

        if ($lawyers->count()) {
            return response()->json(['status' => 'success', 'data' => $lawyers], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function lawyerDetails($slug): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $lawyer = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'fee', 'image','years_of_experience')->with([
            'translations'            => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('lawyer_id', 'seo_title', 'seo_description', 'designations', 'about', 'address', 'educations', 'experience', 'qualifications',);
            },
            'department'              => function ($query) {
                $query->select('id');
            },
            'department.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('department_id', 'name');
            }, 'location', 'location.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->verify()->active()->whereSlug($slug)->first();

        if (!$lawyer) {
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        };

        $days = Day::with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('day_id', 'title');
        }])->select('id')->active()->get();

        $schedules = Schedule::select('day_id', 'start_time', 'end_time')
            ->where('lawyer_id', $lawyer?->id)
            ->active()
            ->get()
            ->groupBy('day_id');

        $scheduleData = $days->map(function ($day) use ($schedules) {
            $times = $schedules->get($day->id);
            if ($times) {
                return [
                    'day'  => $day->translations->first()?->title,
                    'time' => $times->map(function ($time) {
                        return strtoupper($time->start_time) . ' - ' . strtoupper($time->end_time);
                    })->toArray(),
                ];
            }
            return null;
        })->filter()->values();
        $currency_code = strtoupper(request()->query('currency', 'USD'));
        $lawyer->fee = apiCurrency($currency_code, $lawyer->fee);
        $data = [
            'lawyer'   => $lawyer,
            'schedule' => $scheduleData,
        ];
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function testimonial(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $pagination_qty = CustomPagination::where('section_name', 'Testimonial')->select('item_qty')->first()?->item_qty ?? 10;
        $testimonials = Testimonial::select('id', 'image')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('testimonial_id', 'name', 'designation', 'comment');
            },
        ])->active()->paginate($pagination_qty);
        if ($testimonials->count()) {
            return response()->json(['status' => 'success', 'data' => $testimonials], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function faq(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $faqCategories = FaqCategory::select('id')->with([
            'translations'          => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('faq_category_id', 'title');
            },
            'faq_list'              => function ($query) {
                $query->select('id', 'faq_category_id')->active();
            },
            'faq_list.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('faq_id', 'question', 'answer');
            },
        ])->active()->get();
        if ($faqCategories->count()) {
            return response()->json(['status' => 'success', 'data' => $faqCategories], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function blogs(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $pagination_qty = CustomPagination::where('section_name', 'Blog')->select('item_qty')->first()?->item_qty ?? 10;
        $blogs = Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->orderBy('id', 'desc')->active()->paginate($pagination_qty);

        if ($blogs->count()) {
            return response()->json(['status' => 'success', 'data' => ['author' => 'Admin', 'blogs' => $blogs]], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function blogDetails($slug): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $blog = Blog::select('id', 'blog_category_id', 'slug', 'image', 'created_at')->with([
            'translations'          => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_id', 'title', 'sort_description', 'description', 'seo_title', 'seo_description');
            },
            'category'              => function ($query) {
                $query->select('id');
            },
            'category.translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_category_id', 'title');
            },
            'comments'              => function ($query) {
                $query->select('blog_id', 'name', 'email', 'comment', 'created_at')->active();
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->whereSlug($slug)->active()->first();
        if (!$blog) {
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        };
        $blogCategories = BlogCategory::select('id', 'slug')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_category_id', 'title');
            },
        ])->active()->orderBy('slug', 'asc')->get();
        $latestBlog = Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->where('id', '!=', $blog->id)->active()->orderby('id', 'desc')->get()->take(5);
        $data = [
            'author'     => 'Admin',
            'blog'       => $blog,
            'categories' => $blogCategories,
            'latestBlog' => $latestBlog,
        ];
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }
    public function blogCategory($slug): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));
        $pagination_qty = CustomPagination::where('section_name', 'Blog')->select('item_qty')?->first()->item_qty ?? 10;
        $category = BlogCategory::select('id')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_category_id', 'title');
            },
        ])->whereSlug($slug)->active()->first();
        if (!$category) {
            return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
        }

        $blogs = Blog::select('id', 'slug', 'thumbnail_image', 'created_at')->with([
            'translations' => function ($query) use ($code) {
                $query->where('lang_code', $code)->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->active()->where('blog_category_id', $category->id)->paginate($pagination_qty);

        $data = [
            'author'   => 'Admin',
            'category' => $category,
            'blogs'    => $blogs,
        ];
        return response()->json(['status' => 'success', 'data' => $data], 200);
    }

    public function privacyPolicy() {
        $code = strtolower(request()->query('language', 'en'));
        $customPage = CustomizeablePage::select('id', 'slug')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('customizeable_page_id', 'title', 'description');
        }])->whereSlug('privacy-policy')->whereStatus(true)->first();
        if ($customPage) {
            return response()->json(['status' => 'success', 'data' => $customPage], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function termsCondition() {
        $code = strtolower(request()->query('language', 'en'));
        $customPage = CustomizeablePage::select('id', 'slug')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('customizeable_page_id', 'title', 'description');
        }])->whereSlug('terms-contidions')->whereStatus(true)->first();
        if ($customPage) {
            return response()->json(['status' => 'success', 'data' => $customPage], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
    public function customPages(): JsonResponse {
        $code = strtolower(request()->query('language', 'en'));

        $customPages = CustomizeablePage::select('id', 'slug')->with(['translations' => function ($q) use ($code) {
            $q->where('lang_code', $code)->select('customizeable_page_id', 'title', 'description');
        }])->whereNot('slug', 'privacy-policy')->whereNot('slug', 'terms-contidions')->where('status', 1)->get();
        if ($customPages) {
            return response()->json(['status' => 'success', 'data' => $customPages], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }
}
