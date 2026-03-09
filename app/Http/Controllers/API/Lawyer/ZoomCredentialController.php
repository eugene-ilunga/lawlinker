<?php

namespace App\Http\Controllers\API\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ZoomCredential;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ZoomCredentialController extends Controller {
    public function index(): JsonResponse {
        $lawyer_id = auth()->guard('lawyer_api')->user()?->id;
        $credential = ZoomCredential::where('lawyer_id', $lawyer_id)->first();
        if ($credential) {
            return response()->json(['status' => 'success', 'data' => $credential], 200);
        }
        return response()->json(['status' => 'error', 'message' => 'Not Found!'], 404);
    }

    public function store(Request $request): JsonResponse {
        $validator = Validator::make($request->all(), [
            'zoom_account_id'    => 'required',
            'zoom_api_key'    => 'required',
            'zoom_api_secret' => 'required',
        ], [
            'zoom_account_id.required'    => __('Zoom Account ID is required'),
            'zoom_api_key.required'    => __('Zoom API key is required.'),
            'zoom_api_secret.required' => __('Zoom API Secret key is required.'),

        ]);
        if ($validator->fails()) {
            return response()->json(['status' => 'failed', 'message' => $validator->errors()], 422);
        }

        ZoomCredential::updateOrCreate(
            ['lawyer_id' => auth()->guard('lawyer_api')->user()?->id],
            [
                'zoom_account_id'    => $request->zoom_account_id,
                'zoom_api_key'    => $request->zoom_api_key,
                'zoom_api_secret' => $request->zoom_api_secret,
            ],
        );
        return response()->json(['status' => 'success', 'message' => __('Updated successfully')], 200);
    }
}
