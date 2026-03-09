<?php

namespace App\Http\Controllers\Admin;

use App\Models\FaqPage;
use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;

class FaqPageController extends Controller {
    use RedirectHelperTrait;
    public function index() {
        checkAdminHasPermissionAndThrowException('page.faq.view');
        $faq_page = FaqPage::first();
        return view('admin.pages.faq_page', compact('faq_page'));
    }
    public function update(Request $request) {
        checkAdminHasPermissionAndThrowException('page.faq.manage');
        $request->validate([
            'image'  => 'required|image|mimes:webp,png,jpg,jpeg|max:2048',
        ],[
            'image.required'                  => __('Image is required'),
            'image.image'                  => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'image.max'                    => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
        ]);
        $faq_page = FaqPage::first();
        if(!$faq_page){
            $faq_page = new FaqPage();
            $faq_page->save();
        }
        if ( $faq_page && !empty( $request->image ) ) {
            $image_path = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $faq_page->image,
                resize: [520,770],
            );

            $faq_page->image = $image_path;
            $faq_page->save();
        }
        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }
}
