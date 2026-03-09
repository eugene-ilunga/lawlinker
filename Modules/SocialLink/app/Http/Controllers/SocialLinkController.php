<?php

namespace Modules\SocialLink\app\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\SocialLink\app\Models\SocialLink;

class SocialLinkController extends Controller {
    /**
     * Display a listing of the resource.
     */
    public function index() {
        checkAdminHasPermissionAndThrowException('social.link.management');
        $socialLinks = SocialLink::paginate(25);
        return view('sociallink::index', compact('socialLinks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        return view('sociallink::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        checkAdminHasPermissionAndThrowException('social.link.management');
        $request->validate([
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
            'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);

        $item = new SocialLink();
        $item->link = $request->link;
        $item->icon = $request->icon;
        $item->save();


        cache()->forget('getSocialLinks');

        return redirect()->route('admin.social-link.index')->with(['message' => __('Updated successfully'), 'alert-type' => 'success']);

    }
    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id) {

        checkAdminHasPermissionAndThrowException('social.link.management');
        $socialLink = SocialLink::findOrFail($id);
        return view('sociallink::edit', compact('socialLink'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {

        checkAdminHasPermissionAndThrowException('social.link.management');
        $request->validate([
            'icon'        => 'required|string|max:190',
            'link' => 'required',
        ], [
            'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),
            'link.required' => 'Link is required.',
        ]);

        $socialLink = SocialLink::findOrFail($id);
        $socialLink->link = $request->link;
        $socialLink->icon = $request->icon;
        $socialLink->save();

        cache()->forget('getSocialLinks');

        return redirect()->route('admin.social-link.index')->with(['message' => __('Updated successfully'), 'alert-type' => 'success']);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {
        checkAdminHasPermissionAndThrowException('social.link.management');
        $socialLink = SocialLink::findOrFail($id);
        $socialLink->delete();
        cache()->forget('getSocialLinks');
        return redirect()->route('admin.social-link.index')->with(['message' => __('Deleted Successfully'), 'alert-type' => 'success']);
    }
}
