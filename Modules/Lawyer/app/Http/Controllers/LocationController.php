<?php

namespace Modules\Lawyer\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Modules\Lawyer\app\Http\Requests\LocationRequest;
use Modules\Lawyer\app\Models\Location;
use Modules\Language\app\Enums\TranslationModels;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class LocationController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('location.view');
        $locations = Location::paginate(15);
        $code = request('code') ?? getSessionLanguage();
        return view('lawyer::location.index', compact('locations', 'code'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LocationRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('location.store');
        $item = Location::create($request->validated());

        $this->generateTranslations(
            TranslationModels::Location,
            $item,
            'location_id',
            $request,
        );

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LocationRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('location.update');
        $validatedData = $request->validated();

        $item = Location::findOrFail($id);
        $item->update($validatedData);

        $this->updateTranslations($item, $request, $validatedData);

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('location.delete');

        $item = Location::findOrFail($id);
        if ($item->lawyers()->count() > 0) {
            return $this->redirectWithMessage(RedirectType::ERROR->value);
        }

        $item->translations()->each(function ($translation) {
            $translation->location()->dissociate();
            $translation->delete();
        });

        $item->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('location.update');

        $item = Location::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
