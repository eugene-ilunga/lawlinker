<?php

namespace Modules\Service\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;
use Modules\Service\app\Http\Requests\ServiceFaqRequest;
use Modules\Service\app\Models\Service;
use Modules\Service\app\Models\ServiceFaq;

class ServiceFaqController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index($slug) {
        checkAdminHasPermissionAndThrowException('service.view');

        $service = Service::whereSlug($slug)->first();
        if (!$service) {
            abort(404);
        }

        $faqs = ServiceFaq::where('service_id', $service->id)->latest()->paginate(15);
        $code = request('code') ?? getSessionLanguage();
        $languages = allLanguages();

        return view('service::utilities.faq', compact('faqs', 'service', 'code', 'languages'));
    }

    public function store(ServiceFaqRequest $request) {
        checkAdminHasPermissionAndThrowException('service.store');

        $faq = ServiceFaq::create($request->validated());

        $this->generateTranslations(
            TranslationModels::ServiceFaq,
            $faq,
            'service_faq_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    public function update(ServiceFaqRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('service.update');

        $faq = ServiceFaq::findOrFail($id);

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
        checkAdminHasPermissionAndThrowException('service.delete');

        $faq = ServiceFaq::findOrFail($id);

        $faq->translations()->each(function ($translation) {
            $translation->service_faq()->dissociate();
            $translation->delete();
        });

        $faq->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('service.update');

        $faq = ServiceFaq::find($id);
        $status = $faq->status == 1 ? 0 : 1;
        $faq->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
