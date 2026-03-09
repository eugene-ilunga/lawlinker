<?php

namespace App\Http\Controllers\Lawyer;

use App\Enums\RedirectType;
use Illuminate\Http\Request;
use App\Traits\RedirectHelperTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\Lawyer\app\Models\Location;
use Modules\Lawyer\app\Models\Department;
use Modules\Language\app\Models\Language;
use Modules\Language\app\Traits\GenerateTranslationTrait;

class ProfileController extends Controller {
    use GenerateTranslationTrait, RedirectHelperTrait;

    public function __construct() {
        $this->middleware('auth:lawyer');
    }

    public function edit_profile() {
        $code = request('code') ?? getSessionLanguage();
        if (!Language::where('code', $code)->exists()) {
            abort(404);
        }
        $lawyer = Auth::guard('lawyer')->user();
        $departments = Department::with('translation')->get();
        $locations = Location::with('translation')->get();
        $languages = allLanguages();

        return view('lawyer.profile.edit_profile', compact('lawyer' ,'code', 'departments', 'locations', 'languages'));
    }

    public function profile_update(Request $request) {
        $lawyer = Auth::guard('lawyer')->user();
        $rules = [
            'code' => 'required|exists:languages,code',
            'name'  => 'required|string|max:255',
            'email' => 'required|unique:lawyers,email,' . $lawyer->id,
            'phone'   => 'required',
            'fee'     => 'required|numeric',
            'years_of_experience' => 'required',

            'department_id'   => 'required|exists:departments,id',
            'location_id'     => 'required|exists:locations,id',
            
            'designations'    => 'required',
            'about'           => 'required',
            'address'         => 'required',
            'educations'      => 'required',
            'experience'      => 'required',
            'qualifications'  => 'required',
            'lawyer_image'   => 'sometimes|image|max:2048',
            'seo_title'       => 'nullable|string|max:1000',
            'seo_description' => 'nullable|string|max:2000',

        ];
        $customMessages = [
            'department_id.required'  => __('The department is required.'),
            'department_id.exists'    => __('The selected department is invalid.'),

            'location_id.required'    => __('The location is required.'),
            'location_id.exists'      => __('The selected location is invalid.'),

            'fee.required'            => __('Fee is required.'),
            'fee.numeric'             => __('Fee must be a numeric.'),
            'years_of_experience.required' => __('Years of experience is required.'),

            'code.required'           => __('Language is required and must be a string.'),
            'code.exists'             => __('The selected language is invalid.'),

            'seo_title.max'           => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'        => __('SEO title must be a string.'),
            
            'seo_description.max'     => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'  => __('SEO description must be a string.'),

            'lawyer_image.required'   => __('The image is required.'),
            'lawyer_image.image'      => __('The image must be an image.'),
            'lawyer_image.max'        => __('The image may not be greater than 2048 kilobytes.'),

            'name.required'           => __('Name is required'),
            'name.max'                => __('The name may not be greater than 255 characters.'),
            'name.string'             => __('The name must be a string.'),

            'phone.required'          => __('Phone number is required.'),
            'email.required'          => __('Email is required.'),
            'email.unique'            => __('Email already exist'),
            'designations.required'   => __('Designations is required.'),
            'about.required'          => __('About information is required.'),
            'address.required'        => __('Address is required.'),
            'educations.required'     => __('Educations is required.'),
            'experience.required'     => __('Experience is required.'),
            'qualifications.required' => __('Qualifications is required.'),
        ];
        $validatedData = $this->validate($request, $rules, $customMessages);

        $lawyer->update($validatedData);


        if ($lawyer && $request->hasFile('lawyer_image')) {
            $file_name = uploadAndOptimizeImage(
                file: $request->lawyer_image,
                oldFile: $lawyer->image,
            );
            $lawyer->image = $file_name;
        }
        $lawyer->save();

        $this->updateTranslations(
            $lawyer,
            $request,
            $validatedData,
        );

        return $this->redirectWithMessage(
            RedirectType::UPDATE->value,
            'lawyer.edit-profile',
            ['lawyer' => $lawyer->id, 'code' => $request->code]
        );
    }

    public function change_password() {
        return view('lawyer.profile.change_password');
    }

    public function update_password(Request $request) {
        $lawyer = Auth::guard('lawyer')->user();
        $rules = [
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:4',
        ];
        $customMessages = [
            'current_password.required' => __('Current password is required'),
            'password.required'         => __('Password is required'),
            'password.confirmed'        => __('Confirm password does not match'),
            'password.min'              => __('Password must be at leat 4 characters'),
        ];
        $this->validate($request, $rules, $customMessages);

        if (Hash::check($request->current_password, $lawyer->password)) {
            $lawyer->password = Hash::make($request->password);
            $lawyer->save();

            $notification = __('Password Updated successfully');
            $notification = ['message' => $notification, 'alert-type' => 'success'];

            return $this->redirectWithMessage(RedirectType::UPDATE->value, '', [], $notification);

        } else {
            $notification = __('Current password does not match');
            $notification = ['message' => $notification, 'alert-type' => 'error'];

            return redirect()->back()->with($notification);
        }
    }
}
