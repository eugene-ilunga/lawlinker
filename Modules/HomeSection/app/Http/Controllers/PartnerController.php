<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Http\RedirectResponse;
use Modules\HomeSection\app\Models\Partner;
use Modules\HomeSection\app\Http\Requests\PartnerRequest;

class PartnerController extends Controller {
    use RedirectHelperTrait;
    public function index() {
        checkAdminHasPermissionAndThrowException('partner.view');

        $partners = Partner::latest()->paginate(10)->withQueryString();

        return view('homesection::partner.index', compact('partners'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(PartnerRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('partner.store');

        $item = Partner::create($request->except('image'));

        if ($item && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                resize: [255,113]
            );
            $item->image = $file_name;
            $item->save();
        }
        cache()->forget('getPartners');
        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PartnerRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('partner.update');
        $item = Partner::findOrFail($id);

        if ($item && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $item->image,
                resize: [270,140]
            );
            $item->image = $file_name;
            $item->save();
        }
        $item->update($request->except('image'));
        cache()->forget('getPartners');

        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('partner.delete');

        $partner = Partner::findOrFail($id);

        if ($partner->image) {
            if (File::exists(public_path($partner->image))) {
                unlink(public_path($partner->image));
            }
        }
        $partner->delete();
        cache()->forget('getPartners');

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('partner.update');

        $item = Partner::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');
        cache()->forget('getPartners');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
