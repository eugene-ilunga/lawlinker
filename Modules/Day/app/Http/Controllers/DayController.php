<?php

namespace Modules\Day\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Modules\Day\app\Http\Requests\DayRequest;
use Modules\Day\app\Models\Day;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class DayController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function index() {
        checkAdminHasPermissionAndThrowException('day.view');
        $days = Day::paginate(15)->withQueryString();
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $languages = allLanguages();

        return view('day::index', compact('days','languages','code'));
    }

    public function update(DayRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('day.update');
        $validatedData = $request->validated();
        $day = Day::findOrFail($id);
        $this->updateTranslations($day, $request, $validatedData);
        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('day.update');
        $item = Day::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
