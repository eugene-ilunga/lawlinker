<?php

namespace App\Http\Controllers;

use App\Models\FaqPage;
use App\Models\AboutUsPage;
use Illuminate\Http\Request;
use Modules\Day\app\Models\Day;
use Modules\Blog\app\Models\Blog;
use Modules\Lawyer\app\Models\Lawyer;
use Modules\Lawyer\app\Models\Location;
use Modules\Faq\app\Models\FaqCategory;
use Modules\Service\app\Models\Service;
use Modules\Lawyer\app\Models\Department;
use Modules\HomeSection\app\Models\Slider;
use Modules\HomeSection\app\Models\Counter;
use Modules\HomeSection\app\Models\Feature;
use Modules\HomeSection\app\Models\WorkSection;
use Modules\Testimonial\app\Models\Testimonial;
use Modules\HomeSection\app\Models\SectionControl;
use Modules\HomeSection\app\Models\WorkSectionFaq;
use Modules\PageBuilder\app\Models\CustomizeablePage;
use Modules\GlobalSetting\app\Models\CustomPagination;

class HomeController extends Controller {
    public function index() {
        $locations = Location::select('id')->with([
            'translation' => function ($query) {
                $query->select('location_id', 'name');
            },
        ])->orderBy('id', 'asc')->active()->get();
        $departmentsForSearch = Department::select('id')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->latest()->get();
        $lawyersForSearch = Lawyer::select('id', 'name')->orderBy('name', 'asc')->active()->verify()->get();
        $sliders = Slider::select('image','title')->active()->get();
        $home_sections = SectionControl::first();
        $features = Feature::select('id', 'image', 'icon')->with([
            'translation' => function ($query) {
                $query->select('feature_id', 'title', 'description');
            },
        ])->active()->latest()->get();
        $work = WorkSection::select('id', 'image', 'video')->with([
            'translation' => function ($query) {
                $query->select('work_section_id', 'title');
            },
        ])->first();
        $workFaqs = WorkSectionFaq::select('id')->with([
            'translation' => function ($query) {
                $query->select('work_section_faq_id', 'question', 'answer');
            },
        ])->where('work_section_id', $work?->id)->active()->get();

        $services = Service::select('id', 'icon', 'slug')->with([
            'translation' => function ($query) {
                $query->select('service_id', 'title', 'sort_description');
            },
        ])->active()->latest()->homepage()->get();
        $overviews = Counter::select('id', 'icon', 'qty')->with([
            'translation' => function ($query) {
                $query->select('counter_id', 'title');
            },
        ])->active()->latest()->take(4)->get();
        $departments = Department::select('id', 'slug', 'thumbnail_image')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->latest()->homepage()->get();
        $testimonials = Testimonial::select('id', 'image')->with([
            'translation' => function ($query) {
                $query->select('testimonial_id', 'name', 'designation', 'comment');
            },
        ])->homepage()->latest()->active()->get();

        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translation'            => function ($query) {
                $query->select('lawyer_id', 'designations');
            },
            'department'             => function ($query) {
                $query->select('id');
            },
            'department.translation' => function ($query) {
                $query->select('department_id', 'name');
            },
            'location'               => function ($query) {
                $query->select('id');
            },
            'location.translation'   => function ($query) {
                $query->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->homepage()->active()->verify()->get();

        $feature_blog = Blog::select('id','admin_id', 'slug', 'image', 'created_at')->with([
            'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'translation' => function ($query) {
                $query->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->feature()->active()->orderBy('id', 'desc')->first();
        $blogs = Blog::select('id','admin_id', 'slug', 'thumbnail_image', 'created_at')->with([
            'admin' => function ($query) {
                $query->select('id', 'name');
            },
            'translation' => function ($query) {
                $query->select('blog_id', 'title', 'sort_description');
            },
        ])->whereHas('category', function ($query) {
            $query->active();
        })->homepage()->active()->latest()->get();
        return view('client.index', compact('locations', 'departmentsForSearch', 'lawyersForSearch', 'sliders', 'home_sections', 'features', 'work', 'workFaqs', 'services', 'overviews', 'departments', 'testimonials', 'lawyers', 'feature_blog', 'blogs'));
    }
    public function aboutUs(){
        $about=AboutUsPage::select('id', 'status','about_image','background_image','mission_image','mission_status','vision_image','vision_status')->with([
            'translation' => function ($query) {
                $query->select('about_us_page_id','about_description','mission_description','vision_description');
            },
        ])->first();

        $home_sections = SectionControl::select('id','work_status')->with([
            'translation' => function ($query) {
                $query->select('section_control_id', 'work_first_heading', 'work_second_heading', 'work_description');
            },
        ])->first();

        $work = WorkSection::select('id', 'image', 'video')->with([
            'translation' => function ($query) {
                $query->select('work_section_id', 'title');
            },
        ])->first();
        $workFaqs = WorkSectionFaq::select('id')->with([
            'translation' => function ($query) {
                $query->select('work_section_faq_id', 'question', 'answer');
            },
        ])->where('work_section_id', $work?->id)->active()->get();
        $overviews = Counter::select('id', 'icon', 'qty')->with([
            'translation' => function ($query) {
                $query->select('counter_id', 'title');
            },
        ])->active()->get();

        return view('client.about',compact('about','home_sections','work','workFaqs','overviews'));
    }

    public function service() {
        if (cache()->has('CustomPagination')) {
            $pagination_qty = cache()->get('CustomPagination')->service;
        } else {
            $pagination_qty = CustomPagination::where('section_name', 'Service')->select('item_qty')->first()->item_qty;
        }
        $services = Service::select('id', 'icon', 'slug')->with([
            'translation' => function ($query) {
                $query->select('service_id', 'title', 'sort_description');
            },
        ])->active()->paginate($pagination_qty);
        return view('client.service.index', compact('services'));
    }

    public function serviceDetails($slug) {
        $service = Service::select('id', 'icon', 'slug')->with([
            'translation'             => function ($query) {
                $query->select('service_id', 'title', 'description', 'seo_title', 'seo_description');
            },
            'images'                  => function ($query) {
                $query->select('service_id', 'small_image', 'large_image');
            },
            'service_faq'             => function ($query) {
                $query->select('id', 'service_id')->active();
            },
            'service_faq.translation' => function ($query) {
                $query->select('service_faq_id', 'question', 'answer');
            },
            'videos'                  => function ($query) {
                $query->select('service_id', 'link');
            },
        ])->whereSlug($slug)->active()->first();
        if (!$service) {
            abort(404);
        }

        $services = Service::select('id', 'slug')->with([
            'translation' => function ($query) {
                $query->select('service_id', 'title');
            },
        ])->active()->get();

        return view('client.service.show', compact('service', 'services'));
    }
    public function department() {
        if (cache()->has('CustomPagination')) {
            $pagination_qty = cache()->get('CustomPagination')->department;
        } else {
            $pagination_qty = CustomPagination::where('section_name', 'Department')->select('item_qty')->first()->item_qty;
        }
        $departments = Department::select('id', 'slug', 'thumbnail_image')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->paginate($pagination_qty);
        return view('client.department.index', compact('departments'));
    }

    public function departmentDetails($slug) {
        $department = Department::select('id', 'slug','thumbnail_image')->with([
            'translation'                => function ($query) {
                $query->select('department_id', 'name', 'description', 'seo_title', 'seo_description');
            },
            'images'                     => function ($query) {
                $query->select('department_id', 'small_image', 'large_image');
            },
            'department_faq'             => function ($query) {
                $query->select('id', 'department_id')->active();
            },
            'department_faq.translation' => function ($query) {
                $query->select('department_faq_id', 'question', 'answer');
            },
            'videos'                     => function ($query) {
                $query->select('department_id', 'link');
            },
        ])->whereSlug($slug)->active()->first();
        if (!$department) {
            abort(404);
        }
        $departments = Department::select('id', 'slug')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->get();

        $home_sections = SectionControl::select('id')->with([
            'translation' => function ($query) {
                $query->select('section_control_id', 'department_description');
            },
        ])->first();
        $description = $home_sections?->department_description;
        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translation'            => function ($query) {
                $query->select('lawyer_id', 'designations');
            },
            'department'             => function ($query) {
                $query->select('id');
            },
            'department.translation' => function ($query) {
                $query->select('department_id', 'name');
            },
            'location'               => function ($query) {
                $query->select('id');
            },
            'location.translation'   => function ($query) {
                $query->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->where('department_id', $department->id)->active()->verify()->get();

        return view('client.department.show', compact('department', 'departments', 'lawyers', 'description'));
    }
    public function lawyers() {
        if (cache()->has('CustomPagination')) {
            $pagination_qty = cache()->get('CustomPagination')->lawyer;
        } else {
            $pagination_qty = CustomPagination::where('section_name', 'Lawyer')->select('item_qty')->first()->item_qty;
        }
        $locations = Location::select('id')->with([
            'translation' => function ($query) {
                $query->select('location_id', 'name');
            },
        ])->orderBy('id', 'asc')->active()->get();
        $departments = Department::select('id')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->orderBy('slug', 'asc')->get();
        $lawyersForSearch = Lawyer::select('id', 'name')->orderBy('name', 'asc')->active()->verify()->get();

        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translation'            => function ($query) {
                $query->select('lawyer_id', 'designations');
            },
            'department'             => function ($query) {
                $query->select('id');
            },
            'department.translation' => function ($query) {
                $query->select('department_id', 'name');
            },
            'location'               => function ($query) {
                $query->select('id');
            },
            'location.translation'   => function ($query) {
                $query->select('location_id', 'name');
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->orderBy('name', 'asc')->active()->verify()->paginate($pagination_qty);

        return view('client.lawyer.index', compact('lawyers', 'departments', 'locations', 'lawyersForSearch'));
    }

    public function searchLawyer(Request $request) {
        $location_id = $request->location;
        $lawyer_id = $request->lawyer;
        $department_id = $request->department;

        if (cache()->has('CustomPagination')) {
            $pagination_qty = cache()->get('CustomPagination')->lawyer;
        } else {
            $pagination_qty = CustomPagination::where('section_name', 'Lawyer')->select('item_qty')->first()->item_qty;
        }

        $lawyers = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'image')->with([
            'translation'            => function ($query) {
                $query->select('lawyer_id', 'designations');
            },
            'department'             => function ($query) {
                $query->select('id');
            },
            'department.translation' => function ($query) {
                $query->select('department_id', 'name');
            },
            'location'               => function ($query) {
                $query->select('id');
            },
            'location.translation'   => function ($query) {
                $query->select('location_id', 'name');
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

        $lawyers = $lawyers->paginate($pagination_qty);
        $lawyers = $lawyers->appends($request->all());

        $locations = Location::select('id')->with([
            'translation' => function ($query) {
                $query->select('location_id', 'name');
            },
        ])->orderBy('id', 'asc')->active()->get();
        $departments = Department::select('id')->with([
            'translation' => function ($query) {
                $query->select('department_id', 'name');
            },
        ])->active()->orderBy('slug', 'asc')->get();
        $lawyersForSearch = Lawyer::select('id', 'name')->orderBy('name', 'asc')->active()->verify()->get();

        return view('client.lawyer.index', compact('lawyers', 'departments', 'locations', 'lawyersForSearch', 'location_id', 'lawyer_id', 'department_id'));
    }

    public function lawyerDetails($slug) {
        $lawyer = Lawyer::select('id', 'department_id', 'location_id', 'slug', 'name', 'fee','years_of_experience', 'image')->with([
            'translation'            => function ($query) {
                $query->select('lawyer_id', 'seo_title', 'seo_description', 'designations', 'about', 'address', 'educations', 'experience', 'qualifications');
            },
            'department'             => function ($query) {
                $query->select('id');
            },
            'department.translation' => function ($query) {
                $query->select('department_id', 'name');
            },
            'location', 'schedules'=> function ($query) {
                $query->select('lawyer_id','day_id', 'start_time', 'end_time')->active();
            },
            'socialMedia'            => function ($query) {
                $query->select('lawyer_id', 'link', 'icon')->active();
            },
        ])->verify()->active()->whereSlug($slug)->first();
        if (!$lawyer) {
            abort(404);
        }
        $days = Day::select('id')->with([
            'translation' => function ($query) {
                $query->select('day_id', 'title');
            },
        ])->active()->get();

        return view('client.lawyer.show', compact('lawyer','days'));
    }
    public function testimonial() {
        if (cache()->has('CustomPagination')) {
            $pagination_qty = cache()->get('CustomPagination')->testimonial;
        } else {
            $pagination_qty = CustomPagination::where('section_name', 'Testimonial')->select('item_qty')->first()->item_qty;
        }
        $testimonials = Testimonial::select('id', 'image')->with([
            'translation' => function ($query) {
                $query->select('testimonial_id', 'name', 'designation', 'comment');
            },
        ])->active()->paginate($pagination_qty);
        return view('client.testimonial', compact('testimonials'));
    }
    public function faq() {
        $faq_page = FaqPage::first();
        $faqCategories = FaqCategory::select('id')->with([
            'translation' => function ($query) {
                $query->select('faq_category_id', 'title');
            },
            'faq_list'             => function ($query) {
                $query->select('id','faq_category_id')->active();
            },
            'faq_list.translation' => function ($query) {
                $query->select('faq_id', 'question', 'answer');
            },
        ])->active()->get();
        return view('client.faq', compact('faq_page','faqCategories'));
    }

    public function privacyPolicy() {
        $customPage = CustomizeablePage::with('translation')->whereSlug('privacy-policy')->whereStatus(true)->first();
        if ($customPage) {
            return view('client.privacy-policy', compact('customPage'));
        }
        abort(404);
    }

    public function termsCondition() {
        $customPage = CustomizeablePage::with('translation')->whereSlug('terms-contidions')->whereStatus(true)->first();
        if ($customPage) {
            return view('client.terms-condition', compact('customPage'));
        }
        abort(404);
    }
    public function customPage($slug) {
        $customPage = CustomizeablePage::with('translation')->whereStatus(true)->whereSlug($slug)->first();
        if ($customPage) {
            return view('client.custom-page', compact('customPage'));
        }
        abort(404);
    }
}