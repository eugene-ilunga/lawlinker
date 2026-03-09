<?php

namespace Modules\Faq\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Faq\app\Http\Requests\FaqRequest;
use Modules\Faq\app\Models\Faq;
use Modules\Faq\app\Models\FaqCategory;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class FaqController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index($slug) {
        checkAdminHasPermissionAndThrowException('faq.view');

        $category = FaqCategory::whereSlug($slug)->first();
        if(! $category) abort( 404 );
        
        $faqs = Faq::where('faq_category_id', $category->id)->latest()->paginate(15);
        $code = request('code') ?? getSessionLanguage();
        $languages = allLanguages();

        return view('faq::index', compact('faqs', 'category', 'code', 'languages'));
    }

    public function store(FaqRequest $request) {
        checkAdminHasPermissionAndThrowException('faq.store');

        $faq = Faq::create($request->validated());

        $this->generateTranslations(
            TranslationModels::Faq,
            $faq,
            'faq_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    public function update(FaqRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('faq.update');

        $faq = Faq::findOrFail($id);

        $validatedData = $request->validated();

        $faq->update($validatedData);

        $this->updateTranslations(
            $faq,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('faq.delete');

        $faq = Faq::findOrFail($id);

        $faq->translations()->each(function ($translation) {
            $translation->faq()->dissociate();
            $translation->delete();
        });

        $faq->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('faq.update');

        $faq = Faq::find($id);
        $status = $faq->status == 1 ? 0 : 1;
        $faq->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
