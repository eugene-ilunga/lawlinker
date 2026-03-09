<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RedirectType;
use App\Models\AboutUsPage;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Modules\Language\app\Models\Language;
use App\Services\AboutpageUpdatingService;
use App\Services\AboutpageValidatingService;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class AboutUsPageController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;
    public function __construct(
        protected AboutpageUpdatingService $aboutpageUpdatingService,
        protected AboutpageValidatingService $aboutpageValidatingService
    ) {
    }

    public function index()
    {
        checkAdminHasPermissionAndThrowException('page.aboutus.view');
        $code = request('code') ?? getSessionLanguage();

        if (! Language::where('code', $code)->exists()) {
            abort(404);
        }

        $aboutus = AboutUsPage::first();
        $languages = allLanguages();

        return view('admin.pages.about.index', compact('aboutus', 'code','languages'));
    }

    public function update(Request $request)
    {
        checkAdminHasPermissionAndThrowException('page.aboutus.manage');
        if (!$request->filled('code')) {
            $notification = __('Language not found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
        $aboutpage = AboutUsPage::first();

        if (!$aboutpage) {
            $aboutpage = $this->aboutpageUpdatingService->createAboutpage($request);
        }

        if ($request->tab == 'about_section') {
            [$rules, $messages] = $this->aboutpageValidatingService->aboutSection($request);
            $this->validate($request, $rules, $messages);
            $this->aboutpageUpdatingService->aboutSection(request: $request, aboutpage: $aboutpage);
        }
        if ($request->tab == 'mission_section') {
            [$rules, $messages] = $this->aboutpageValidatingService->missionSection($request);
            $this->validate($request, $rules, $messages);
            $this->aboutpageUpdatingService->missionSection(request: $request, aboutpage: $aboutpage);
        }
        if ($request->tab == 'vision_section') {
            [$rules, $messages] = $this->aboutpageValidatingService->visionSection($request);
            $this->validate($request, $rules, $messages);
            $this->aboutpageUpdatingService->visionSection(request: $request, aboutpage: $aboutpage);
        }

        $aboutUs = AboutUsPage::first();

        if (! $aboutUs) {
            $aboutUs = new AboutUsPage();
            $aboutUs->save();
            $this->generateTranslations(TranslationModels::AboutusPage, $aboutUs, 'about_us_page_id', $request);
        }
        $aboutUs->update($request->all());

        $this->updateTranslations($aboutUs, $request, $request->only('code', 'title', 'description'));

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
