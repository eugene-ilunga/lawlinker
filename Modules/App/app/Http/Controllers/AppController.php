<?php

namespace Modules\App\app\Http\Controllers;

use App\Enums\RedirectType;
use App\Http\Controllers\Controller;
use App\Traits\RedirectHelperTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Modules\App\app\Http\Requests\OnBoardingRequest;
use Modules\App\app\Models\OnBoardingScreen;
use Modules\GlobalSetting\app\Models\Setting;

class AppController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('app.management');
        $screens = OnBoardingScreen::orderBy('order')->paginate(10);
        return view('app::index', compact('screens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        checkAdminHasPermissionAndThrowException('app.management');
        return view('app::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(OnBoardingRequest $request) {
        checkAdminHasPermissionAndThrowException('app.management');
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'image.image' => __('The image must be an image.'),
            'image.max'   => __('The image may not be greater than 2048 kilobytes.'),
        ]);

        $screen = new OnBoardingScreen();
        $screen->title = $request->title;
        $screen->sort_description = $request->sort_description;
        $screen->order = $request->order;
        $screen->status = $request->status;

        if ($request->hasFile('image')) {
            $file_name = file_upload($request->image);
            $screen->image = $file_name;
        }
        $screen->save();

        return $this->redirectWithMessage(RedirectType::CREATE->value, 'admin.app.screen.index');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {
        $screen = OnBoardingScreen::find($id);
        checkAdminHasPermissionAndThrowException('app.management');
        return view('app::edit', compact('screen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(OnBoardingRequest $request, $id) {
        checkAdminHasPermissionAndThrowException('app.management');
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'image.image' => __('The image must be an image.'),
            'image.max'   => __('The image may not be greater than 2048 kilobytes.'),
        ]);
        $screen = OnBoardingScreen::find($id);
        $screen->title = $request->title;
        $screen->sort_description = $request->sort_description;
        $screen->order = $request->order;
        $screen->status = $request->status;

        if ($screen && !empty($request->image)) {
            $file_name = file_upload($request->image, oldFile: $screen->image);
            $screen->image = $file_name;
        }
        $screen->save();
        return $this->redirectWithMessage(RedirectType::UPDATE->value, 'admin.app.screen.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('app.management');
        $screen = OnBoardingScreen::find($id);
        if ($screen->image) {
            if (File::exists(public_path($screen->image))) {
                unlink(public_path($screen->image));
            }
        }
        $screen->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value, 'admin.app.screen.index');
    }

    public function statusUpdate($id) {
        checkAdminHasPermissionAndThrowException('app.management');
        $screen = OnBoardingScreen::find($id);
        $status = $screen->status == 1 ? 0 : 1;
        $screen->update(['status' => $status]);
        $notification = __('Updated successfully');
        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }

    public function app_banner(Request $request) {
        checkAdminHasPermissionAndThrowException('app.management');
        $request->validate([
            'image'                   => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'app_login_img'           => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'app_forgot_password_img' => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'image.image'                   => __('The image must be an image.'),
            'image.max'                     => __('The image may not be greater than 2048 kilobytes.'),
            'app_login_img.image'           => __('The image must be an image.'),
            'app_login_img.max'             => __('The image may not be greater than 2048 kilobytes.'),
            'app_forgot_password_img.image' => __('The image must be an image.'),
            'app_forgot_password_img.max'   => __('The image may not be greater than 2048 kilobytes.'),
        ]);
        $cachedSetting = Cache::get('setting');

        if ($request->hasFile('image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->image,
                oldFile: $cachedSetting?->app_banner
            );
            Setting::where('key', 'app_banner')->update(['value' => $file_name]);
        }
        if ($request->hasFile('app_login_img')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->app_login_img,
                oldFile: $cachedSetting?->app_login_img
            );
            Setting::where('key', 'app_login_img')->update(['value' => $file_name]);
        }
        if ($request->hasFile('app_forgot_password_img')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->app_forgot_password_img,
                oldFile: $cachedSetting?->app_forgot_password_img
            );
            Setting::where('key', 'app_forgot_password_img')->update(['value' => $file_name]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
    public function app_store(Request $request) {
        checkAdminHasPermissionAndThrowException('app.management');
        $request->validate([
            'google_app_store_status' => 'required|in:1,0',
            'google_app_store_link'   => 'required_if:google_app_store_status,1|nullable',
            'google_app_store_img'    => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'apple_app_store_status'  => 'required|in:1,0',
            'apple_app_store_link'    => 'required_if:apple_app_store_status,1|nullable',
            'apple_app_store_img'     => 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
        ], [
            'google_app_store_status.required' => __('Status is required'),
            'google_app_store_status.in'       => __('Status is invalid'),
            'google_app_store_link.required_if'   => __('Link is required'),
            'google_app_store_img.image'       => __('The image must be an image.'),
            'google_app_store_img.max'         => __('The image may not be greater than 2048 kilobytes.'),
            'apple_app_store_status.required'  => __('Status is required'),
            'apple_app_store_status.in'        => __('Status is invalid'),
            'apple_app_store_link.required_if'    => __('Link is required'),
            'apple_app_store_img.image'        => __('The image must be an image.'),
            'apple_app_store_img.max'          => __('The image may not be greater than 2048 kilobytes.'),
        ]);
        $cachedSetting = Cache::get('setting');

        if ($request->hasFile('google_app_store_img')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->google_app_store_img,
                oldFile: $cachedSetting?->google_app_store_img
            );
            Setting::where('key', 'google_app_store_img')->update(['value' => $file_name]);
        }
        if ($request->hasFile('apple_app_store_img')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->apple_app_store_img,
                oldFile: $cachedSetting?->apple_app_store_img
            );
            Setting::where('key', 'apple_app_store_img')->update(['value' => $file_name]);
        }

        foreach ($request->except(['_token','google_app_store_img','apple_app_store_img']) as $key => $value) {
            Setting::where('key', $key)->update(['value' => $value]);
        }

        Cache::forget('setting');

        $notification = __('Updated successfully');
        $notification = ['message' => $notification, 'alert-type' => 'success'];

        return redirect()->back()->with($notification);
    }
}
