<?php

namespace Modules\Lawyer\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DepartmentRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'seo_title'       => 'nullable|string|max:1000',
            'seo_description' => 'nullable|string|max:1000',
            'show_homepage'   => 'nullable',
            'status'          => 'nullable',
            'description'     => 'required',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['name'] = 'required|string|max:255';
            $rules['slug'] = 'sometimes|string|max:255|unique:departments,slug,' . $this->department;
            $rules['image'] = 'sometimes|image|max:2048';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|max:2048';
            $rules['slug'] = 'required|string|max:255|unique:departments,slug';
            $rules['name'] = 'required|string|max:255|unique:department_translations,name';
        }

        return $rules;
    }

    public function messages(): array {
        return [

            'code.required'           => __('Language is required and must be a string.'),
            'code.exists'             => __('The selected language is invalid.'),

            'seo_title.max'           => __('SEO title may not be greater than 1000 characters.'),
            'seo_title.string'        => __('SEO title must be a string.'),
            'seo_description.max'     => __('SEO description may not be greater than 2000 characters.'),
            'seo_description.string'  => __('SEO description must be a string.'),

            'image.required'   => __('The image is required.'),
            'image.image'      => __('The image must be an image.'),
            'image.max'        => __('The image may not be greater than 2048 kilobytes.'),

            'slug.required'             => __('Slug is required.'),
            'slug.max'                  => __('The slug may not be greater than 255 characters.'),
            'slug.unique'               => __('Slug must be unique.'),

            'name.required'           => __('Name is required'),
            'name.max'                => __('The name may not be greater than 255 characters.'),
            'name.string'             => __('The name must be a string.'),
            'name.unique'             => __('Name must be unique.'),

            'description.required'    => __('Description is required.'),

            'sort_description.max'      => __('Short description must be a string.'),
            'sort_description.string'   => __('Short description may not be greater than 500 characters.'),
        ];
    }
}
