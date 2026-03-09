<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Modules\HomeSection\app\Models\WorkSectionFaq;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\HomeSection\app\Http\Requests\WorkSectionFaqRequest;

class WorkSectionFaqController extends Controller
{
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Store a newly created resource in storage.
     */
    public function store(WorkSectionFaqRequest $request): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('work.section.faq.store');
        $item = WorkSectionFaq::create($request->validated());

        $this->generateTranslations(
            TranslationModels::WorkSectionFaq,
            $item,
            'work_section_faq_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value,'admin.work-section.index',['code' => $request->code]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(WorkSectionFaqRequest $request, $id): RedirectResponse
    {
        checkAdminHasPermissionAndThrowException('work.section.faq.update');
        $validatedData = $request->validated();

        $item = WorkSectionFaq::findOrFail($id);
        $item->update($validatedData);

        $this->updateTranslations($item,$request,$validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value,'admin.work-section.index',['code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        checkAdminHasPermissionAndThrowException('work.section.faq.delete');

        $item = WorkSectionFaq::findOrFail($id);

        $item->translations()->each(function ($translation) {
            $translation->work_section_faq()->dissociate();
            $translation->delete();
        });

        $item->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id)
    {
        checkAdminHasPermissionAndThrowException('work.section.faq.update');

        $item = WorkSectionFaq::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
