<?php

namespace App\Services;

use App\Models\AboutUsPage;
use App\Traits\RedirectHelperTrait;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class AboutpageUpdatingService {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function createAboutpage( $request ): ?AboutUsPage {
        $aboutpage = new AboutUsPage();
        $aboutpage->save();
        $this->generateTranslations( TranslationModels::AboutusPage, $aboutpage, 'about_us_page_id', $request );

        return $aboutpage;
    }

    public function aboutSection( $request, $aboutpage ): void {
        $aboutpage->update();

        if ( $aboutpage && !empty( $request->image ) ) {
            $about_path = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $aboutpage->about_image,
                resize: [480,480],
            );

            $aboutpage->about_image = $about_path;
            $aboutpage->save();
        }
            
        if ( $aboutpage && !empty( $request->about_background_image ) ) {
            $file_path = uploadAndOptimizeImage(
                file: $request->about_background_image,
                oldFile: $aboutpage->background_image,
                resize: [525,455]
            );
            $aboutpage->background_image = $file_path;
            $aboutpage->save();
        }

        $this->updateTranslations( $aboutpage, $request, $request->only('about_description', 'code' ) );
    }
    public function missionSection( $request, $aboutpage ): void {
        $aboutpage->update();

        if ( $aboutpage && !empty( $request->mission_img ) ) {
            $file_path = uploadAndOptimizeImage(
                file: $request->mission_img,
                oldFile: $aboutpage->mission_image,
                resize: [625,535],
            );
            $aboutpage->mission_image = $file_path;
            $aboutpage->save();
        }

        $this->updateTranslations( $aboutpage, $request, $request->only( 'mission_description','code' ) );
    }
    public function visionSection( $request, $aboutpage ): void {
        $aboutpage->update();

        if ( $aboutpage && !empty( $request->vision_img ) ) {
            $file_path = uploadAndOptimizeImage(
                file: $request->vision_img,
                oldFile: $aboutpage->vision_image,
                resize: [625,535],
            );
            $aboutpage->vision_image = $file_path;
            $aboutpage->save();
        }

        $this->updateTranslations( $aboutpage, $request, $request->only( 'vision_description','code' ) );
    }
}
