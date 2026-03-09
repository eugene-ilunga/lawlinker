<?php

namespace Modules\ContactMessage\app\Http\Controllers\Admin;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Modules\Language\app\Models\Language;
use Modules\ContactMessage\app\Models\ContactInfo;
use Modules\ContactMessage\app\Services\ContactInfoUpdatingService;
use Modules\ContactMessage\app\Services\ContactInfoValidatingService;

class ContactInfoController extends Controller {
    use RedirectHelperTrait;

    public function __construct(
        protected ContactInfoUpdatingService $contactInfoUpdatingService,
        protected ContactInfoValidatingService $contactInfoValidatingService
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('contact.info.view');
        $contact_info = ContactInfo::first();
        $code = request('code') ?? getSessionLanguage();

        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();
        return view('contactmessage::contact_info.index',compact('contact_info', 'code', 'languages'));
    }

    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('contact.info.update');
        if (!$request->filled('code')) {
            $notification = __('Language not found!');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }

        $contact_info = ContactInfo::first();

        if (!$contact_info) {
            $contact_info = $this->contactInfoUpdatingService->createInfo($request);
        }

        if ($request->tab == 'top_bar_section') {
            [$rules, $messages] = $this->contactInfoValidatingService->validateTopBarInfo($request);
            $this->validate($request, $rules, $messages);
            $this->contactInfoUpdatingService->updateStaticData(request: $request, contact_info: $contact_info);
        }
        if ($request->tab == 'contact_info_section') {
            [$rules, $messages] = $this->contactInfoValidatingService->validateContactInfo($request);
            $this->validate($request, $rules, $messages);
            $this->contactInfoUpdatingService->updateContactInfo(request: $request, contact_info: $contact_info);
        }

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
    public function prescription_contact() {
        checkAdminHasPermissionAndThrowException('contact.info.view');
        return view('contactmessage::prescription_contact');
    }
}
