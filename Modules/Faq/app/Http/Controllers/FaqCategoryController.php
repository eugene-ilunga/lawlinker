<?php

namespace Modules\Faq\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\Faq\app\Models\FaqCategory;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Faq\app\Http\Requests\FaqCategoryRequest;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class FaqCategoryController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index() {
        checkAdminHasPermissionAndThrowException('faq.category.view');

        $categories = FaqCategory::paginate(15);
        $code = request('code') ?? getSessionLanguage();

        return view('faq::Category.index', ['categories' => $categories, 'code'=> $code]);
    }

    public function create() {
        checkAdminHasPermissionAndThrowException('faq.category.create');

        return view('faq::Category.create');
    }

    public function store(FaqCategoryRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('faq.category.store');
        $category = FaqCategory::create($request->validated());

        $languages = Language::all();

        $this->generateTranslations(
            TranslationModels::FaqCategory,
            $category,
            'faq_category_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.faq-category.edit', ['faq_category' => $category->id, 'code' => $languages->first()->code]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        checkAdminHasPermissionAndThrowException('faq.category.edit');
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $category = FaqCategory::findOrFail($id);
        $languages = allLanguages();

        return view('faq::Category.edit', compact('category', 'code', 'languages'));
    }

    public function update(FaqCategoryRequest $request, FaqCategory $faq_category) {
        checkAdminHasPermissionAndThrowException('faq.category.update');
        $validatedData = $request->validated();

        $faq_category->update($validatedData);

        $this->updateTranslations(
            $faq_category,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.faq-category.edit', ['faq_category' => $faq_category->id, 'code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FaqCategory $faqCategory) {
        checkAdminHasPermissionAndThrowException('faq.category.delete');
        if ($faqCategory->faq_list()->count() > 0) {
            $notification = [
                'message' => __('You can\'t delete this category because it has associated FAQs'),
                'alert-type' => 'error',
            ];
            return redirect()->back()->with($notification);
        }
        $faqCategory->translations()->each(function ($translation) {
            $translation->category()->dissociate();
            $translation->delete();
        });

        $faqCategory->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.faq-category.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('faq.category.update');
        $faqCategory = FaqCategory::find($id);
        $status = $faqCategory->status == 1 ? 0 : 1;
        $faqCategory->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
