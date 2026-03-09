<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\LawyerSocialMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LawyerSocialMediaController extends Controller {
    public function index() {
        $setting = cache()->get('setting');
        $lawyer = auth()->guard('lawyer_api')->user();
        $social_links = $lawyer->socialMedia()->select('id', 'icon', 'link')->active()->oldest()->take($setting?->lawyer_social_links_limit)->get();
        if ($social_links) {
            return response()->json(['status' => 'success', 'data' => $social_links], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $setting = cache()->get('setting');
        if ($lawyer->socialMedia()->count() >= $setting?->lawyer_social_links_limit) {
            return response()->json(['status' => 'failed', 'message' => [
                'limit_reached' => __('You have reached the limit. You can only add social links') . ' ' . $setting?->lawyer_social_links_limit,
            ]], 422);

        }

        $validator = Validator::make($request->all(), [
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
             'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }
        $item = new LawyerSocialMedia();
        $item->lawyer_id = $lawyer->id;
        $item->link = $request->link;
        $item->icon = $request->icon;
        $item->save();
        return response()->json(['status' => 'success', 'message' => __('Created Successfully')], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $item = $lawyer->socialMedia()->where('id', $id)->first();
        if (!$item) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        $validator = Validator::make($request->all(), [
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
             'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        $item->link = $request->link;
        $item->icon = $request->icon;
        $item->save();

        return response()->json(['status' => 'success', 'message' => __('Updated successfully')], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $item = $lawyer->socialMedia()->where('id', $id)->first();
        if ($item) {
            $item->delete();
            return response()->json(['status' => 'success', 'message' => __('Deleted Successfully')], 200);
        }
        return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
    }

    public function statusUpdate($id): JsonResponse {
        $lawyer = auth()->guard('lawyer_api')->user();
        $item = $lawyer->socialMedia()->where('id', $id)->first();
        if (!$item) {
            return response()->json(['status' => 'error', 'message' => __('Not Found!')], 404);
        }
        $status = $item->status == 1 ? 0 : 1;
        $item->update(['status' => $status]);
        return response()->json(['status' => 'success', 'message' => __('Updated successfully')]);
    }
}
