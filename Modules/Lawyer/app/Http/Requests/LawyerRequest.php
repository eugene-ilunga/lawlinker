<?php

namespace Modules\Lawyer\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LawyerRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'department_id'       => 'sometimes|exists:departments,id',
            'location_id'         => 'sometimes|exists:locations,id',
            'seo_title'           => 'nullable|string|max:1000',
            'seo_description'     => 'nullable|string|max:2000',
            'status'              => 'nullable',
            'show_homepage'       => 'nullable',

            'fee'                 => 'required|numeric',
            'years_of_experience' => 'required',
            'designations'        => 'required',
            'about'               => 'required',
            'address'             => 'required',
            'educations'          => 'required',
            'experience'          => 'required',
            'qualifications'      => 'required',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['name'] = 'sometimes|string|max:255';
            $rules['slug'] = 'sometimes|string|max:255|unique:lawyers,slug,' . $this->lawyer;
            $rules['email'] = 'required|max:255|unique:lawyers,email,' . $this->lawyer;
            $rules['phone'] = 'required';
            $rules['lawyer_image'] = 'sometimes|image|max:2048';
            $rules['password'] = 'nullable|min:4';
        }
        if ($this->isMethod('post')) {
            $rules['lawyer_image'] = 'required|image|max:2048';
            $rules['slug'] = 'required|string|max:255|unique:lawyers,slug';
            $rules['email'] = 'required|max:255|unique:lawyers,email';
            $rules['name'] = 'required|string|max:255';
            $rules['phone'] = 'required';
            $rules['password'] = 'required|min:4';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'department_id.required'       => __('The department is required.'),
            'department_id.exists'         => __('The selected department is invalid.'),

            'location_id.required'         => __('The location is required.'),
            'location_id.exists'           => __('The selected location is invalid.'),

            'fee.required'                 => __('Fee is required.'),
            'fee.numeric'                  => __('Fee must be a numeric.'),

            'years_of_experience.required' => __('Years of experience is required.'),

            'code.required'                => __('Language is required and must be a string.'),
            'code.exists'                  => __('The selected language is invalid.'),

            'seo_title.max'                => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'             => __('SEO title must be a string.'),

            'seo_description.max'          => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'       => __('SEO description must be a string.'),

            'lawyer_image.required'        => __('The image is required.'),
            'lawyer_image.image'           => __('The image must be an image.'),
            'lawyer_image.max'             => __('The image may not be greater than 2048 kilobytes.'),

            'name.required'                => __('Name is required'),
            'name.max'                     => __('The name may not be greater than 255 characters.'),
            'name.string'                  => __('The name must be a string.'),

            'slug.required'                => __('Slug is required.'),
            'slug.max'                     => __('The slug may not be greater than 255 characters.'),
            'slug.unique'                  => __('Slug must be unique.'),

            'phone.required'               => __('Phone number is required.'),
            'email.required'               => __('Email is required.'),
            'email.unique'                 => __('Email already exist'),
            'password.required'            => __('Password is required.'),
            'password.min'                 => __('You have to provide minimum 4 character password'),
            'designations.required'        => __('Designations is required.'),
            'about.required'               => __('About information is required.'),
            'address.required'             => __('Address is required.'),
            'educations.required'          => __('Educations is required.'),
            'experience.required'          => __('Experience is required.'),
            'qualifications.required'      => __('Qualifications is required.'),
        ];
    }
}
