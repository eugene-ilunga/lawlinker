<?php

namespace Modules\HomeSection\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\File;
use Modules\HomeSection\app\Http\Requests\SliderRequest;
use Modules\HomeSection\app\Models\Slider;

class SliderController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('slider.view');
        $sliders = Slider::latest()->paginate(10)->withQueryString();
        return view('homesection::slider.index', compact('sliders'));
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request): RedirectResponse {
        checkAdminHasPermissionAndThrowException('slider.store');
        if ($request->hasFile('image')) {
            $item = new Slider();
            $item->title = $request->title;
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                resize: [1000, 690]
            );
            $item->image = $file_name;

            $item->save();

            return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.slider.index', ['code' => $request->code]);
        } else {
            $notification = __('Image is required');
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return back()->with($notification);
        }

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, $id): RedirectResponse {
        checkAdminHasPermissionAndThrowException('slider.update');
        $item = Slider::findOrFail($id);
        $item->title = $request->title;

        if ($item && $request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $item->image,
                resize: [1000, 690]
            );
            $item->image = $file_name;
        }

        $item->save();

        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.slider.index', ['code' => $request->code]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('slider.delete');

        $slider = Slider::findOrFail($id);

        if ($slider->image) {
            if (File::exists(public_path($slider->image))) {
                unlink(public_path($slider->image));
            }
        }
        $slider->delete();

        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('slider.update');

        $item = Slider::find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');

        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
