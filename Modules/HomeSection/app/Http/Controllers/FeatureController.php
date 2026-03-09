<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Modules\Language\app\Models\Language;
use Modules\HomeSection\app\Models\Feature;
use Modules\Language\app\Enums\TranslationModels;
use Modules\HomeSection\app\Http\Requests\FeatureRequest;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class FeatureController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('feature.view');

        $features = Feature::latest()->paginate(10)->withQueryString();
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();

        return view('homesection::feature.index', compact('features', 'languages', 'code'));

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(FeatureRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('feature.store');
        

        $item = new Feature();
        $item->icon = $request->icon;

        if ($item && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                resize: [645, 645]
            );
            $item->image = $file_name;
        }
        $item->save();

        $this->generateTranslations(
            TranslationModels::Feature,
            $item,
            'feature_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.feature.index', ['code' => $request->code]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FeatureRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('feature.update');
        $validatedData = $request->validated();

        $item = Feature::findOrFail($id);
        $item->icon = $request->icon;

        if ($item && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $item->image,
                resize: [645, 645]
            );
            $item->image = $file_name;
        }
        $item->save();

        $this->updateTranslations($item,$request,$validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.feature.index', ['code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('feature.delete');

        $feature = Feature::findOrFail($id);

        $feature->translations()->each(function ($translation) {
            $translation->feature()->dissociate();
            $translation->delete();
        });
        if ($feature->image) {
            if (File::exists(public_path($feature->image))) {
                unlink(public_path($feature->image));
            }
        }
        $feature->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('feature.update');

        $item = Feature::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
