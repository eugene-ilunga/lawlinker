<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Models\ZoomCredential;
use Illuminate\Http\Request;

class ZoomCredentialController extends Controller {
    public function index() {
        $credential = ZoomCredential::where('lawyer_id', lawyerAuth()?->id)->first();
        return view('lawyer.zoom.setting.index', compact('credential'));
    }

    public function store(Request $request) {
        $rules = [
            'zoom_account_id'    => 'required',
            'zoom_api_key'    => 'required',
            'zoom_api_secret' => 'required',
        ];
        $customMessages = [
            'zoom_account_id.required'    => __('Zoom Account ID is required'),
            'zoom_api_key.required'    => __('Zoom API key is required.'),
            'zoom_api_secret.required' => __('Zoom API Secret key is required.'),
        ];
        $this->validate($request, $rules, $customMessages);

        ZoomCredential::updateOrCreate(
            ['lawyer_id' => lawyerAuth()?->id],
            [
                'zoom_account_id'    => $request->zoom_account_id,
                'zoom_api_key'    => $request->zoom_api_key,
                'zoom_api_secret' => $request->zoom_api_secret,
            ],
        );
        $notification = ['message' => __('Updated successfully'), 'alert-type' => 'success'];
        return back()->with($notification);
    }
}
