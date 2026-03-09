<?php

namespace Modules\ContactMessage\app\Services;

use App\Traits\RedirectHelperTrait;
use Modules\ContactMessage\app\Models\ContactInfo;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ContactInfoUpdatingService {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function createInfo($request): ?ContactInfo {
        $contact_info = new ContactInfo();
        $contact_info->save();
        $this->generateTranslations(TranslationModels::ContactInfo, $contact_info, 'contact_info_id', $request);

        return $contact_info;
    }

    public function updateStaticData($request, $contact_info): void {
        $contact_info->update($request->all());
    }

    public function updateContactInfo($request, $contact_info): void {
        $contact_info->update($request->all());
        $this->updateTranslations($contact_info, $request, $request->all());
    }

}
