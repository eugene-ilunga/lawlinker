<?php

namespace Modules\HomeSection\app\Services;

use App\Traits\RedirectHelperTrait;
use Modules\HomeSection\app\Models\SectionControl;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class SectionUpdatingService {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function createSection($request): ?SectionControl {
        $section_control = new SectionControl();
        $section_control->save();
        $this->generateTranslations(TranslationModels::SectionControl, $section_control, 'section_control_id', $request);

        return $section_control;
    }

    public function updateSectionStatusOnly($request, $section_control): void {
        $section_control->update($request->all());
    }

    public function updateSection($request, $section_control): void {
        $section_control->update($request->all());
        $this->updateTranslations($section_control, $request, $request->all());
    }

}
