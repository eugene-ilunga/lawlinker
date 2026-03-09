<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\HomeSection\app\Models\SectionControl;
use Modules\HomeSection\app\Services\SectionUpdatingService;
use Modules\HomeSection\app\Services\SectionValidatingService;
use Modules\Language\app\Models\Language;

class SectionControlController extends Controller {
    use RedirectHelperTrait;

    public function __construct(
        protected SectionUpdatingService $sectionUpdatingService,
        protected SectionValidatingService $sectionValidatingService
    ) {
    }

    public function index() {
        checkAdminHasPermissionAndThrowException('section.view');
        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();

        $section_control = SectionControl::first();

        return view('homesection::section_control.index', compact('section_control', 'code', 'languages'));
    }

    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('section.manage');
        if (!$request->filled('code')) {
            $notification = __('Language not found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $section_control = SectionControl::first();

        if (!$section_control) {
            $section_control = $this->sectionUpdatingService->createSection($request);
        }

        if ($request->tab == 'feature_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSectionStatusOnly($request, 'feature_how_many');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSectionStatusOnly(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'work_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'work_how_many', 'work_status', 'work_first_heading', 'work_second_heading','work_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'service_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'service_how_many', 'service_status', 'service_first_heading', 'service_second_heading','service_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'department_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'department_how_many', 'department_status', 'department_first_heading', 'department_second_heading','department_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'client_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'client_how_many', 'client_status', 'client_first_heading', 'client_second_heading','client_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'lawyer_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'lawyer_how_many', 'lawyer_status', 'lawyer_first_heading', 'lawyer_second_heading','lawyer_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }
        if ($request->tab == 'blog_section') {
            [$rules, $messages] = $this->sectionValidatingService->validateSection($request, 'blog_how_many', 'blog_status', 'blog_first_heading', 'blog_second_heading','blog_description');
            $this->validate($request, $rules, $messages);
            $this->sectionUpdatingService->updateSection(request: $request, section_control: $section_control);
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
