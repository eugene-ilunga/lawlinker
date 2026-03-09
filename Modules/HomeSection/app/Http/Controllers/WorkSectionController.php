<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Modules\HomeSection\app\Models\WorkSection;
use Modules\HomeSection\app\Models\WorkSectionFaq;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class WorkSectionController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index() {
        checkAdminHasPermissionAndThrowException('work.section.view');

        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }

        $workSection = WorkSection::with('translation')->first();
        $faqs = WorkSectionFaq::where('work_section_id', $workSection?->id)->latest()->paginate(10)->withQueryString();
        $languages = allLanguages();

        return view('homesection::work_section.index', compact('workSection', 'faqs', 'code', 'languages'));
    }

    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('work.section.update');
        $request->validate(
            [
                'code'        => 'required|string|exists:languages,code',
                'image'       => 'nullable|image|max:2048',
                'title'       => 'required|string|max:255',
                'video'       => 'required|string',
            ],
            [
                'image.image'          => __('The image must be an image.'),
                'image.max'            => __('The image may not be greater than 2048 kilobytes.'),

                'title.required'       => __('The title is required.'),
                'title.string'         => __('The title must be a string.'),
                'title.max'            => __('The title may not be greater than 255 characters.'),
            ]
        );

        if (!$request->filled('code')) {
            $notification = __('Language not found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return $this->redirectWithMessage(RedirectType::ERROR->value, 'admin.work-section.index', ['code' => $request->code], $notification);
        }

        $workSection = WorkSection::first();

        if (!$workSection) {
            $workSection = new WorkSection();
            $workSection->save();
            $this->generateTranslations(TranslationModels::WorkSection, $workSection, 'work_section_id', $request);
        }
        if ($workSection && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $workSection->image,
                resize: [645, 645]
            );
            $workSection->image = $file_name;
            $workSection->save();
        }
        $workSection->update($request->except('image'));

        $this->updateTranslations($workSection, $request, $request->except('image'));

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.work-section.index', ['code' => $request->code]);
    }
}
