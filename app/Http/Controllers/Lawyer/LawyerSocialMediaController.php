<?php

namespace App\Http\Controllers\Lawyer;

use Closure;
use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Models\LawyerSocialMedia;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;

class LawyerSocialMediaController extends Controller {
    use RedirectHelperTrait;
    /**
     * Display a listing of the resource.
     */
    public function index() {
        $setting = cache()->get('setting');
        if($setting->lawyer_can_add_social_links == 'inactive'){
            abort(404);
        }
        $social_links = lawyerAuth()->socialMedia()->oldest()->take($setting?->lawyer_social_links_limit)->get();
        return view('lawyer.social_media.index', compact('social_links'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse {
        $setting = cache()->get('setting');
        if(lawyerAuth()->socialMedia()->count() >= $setting?->lawyer_social_links_limit){
            $notification = __('You have reached the limit. You can only add social links').' ' . $setting?->lawyer_social_links_limit;
            $notification = ['message' => $notification, 'alert-type' => 'error'];
            return back()->with($notification);

        }
        $request->validate([
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
            'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);
        $item = new LawyerSocialMedia();
        $item->lawyer_id = lawyerAuth()->id;
        $item->link = $request->link;
        $item->icon = $request->icon;
        $item->save();

        return $this->redirectWithMessage(RedirectType::CREATE->value);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse {
        $request->validate([
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
            'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);

        $item = lawyerAuth()->socialMedia()->findOrFail($id);
        $item->link = $request->link;
        $item->icon = $request->icon;
        $item->save();



        return $this->redirectWithMessage(RedirectType::UPDATE->value);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        $item = lawyerAuth()->socialMedia()->findOrFail($id);
        $item->delete();
        return $this->redirectWithMessage(RedirectType::DELETE->value);
    }

    public function statusUpdate($id) {
        $item = lawyerAuth()->socialMedia()->find($id);
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);

        $notification = __('Updated successfully');
        return response()->json([
            'success' => true,
            'message' => $notification,
        ]);
    }
}
