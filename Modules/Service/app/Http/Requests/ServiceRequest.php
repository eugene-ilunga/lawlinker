<?php

namespace Modules\Service\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'seo_title'        => 'nullable|string|max:1000',
            'seo_description'  => 'nullable|string|max:1000',
            'show_homepage'    => 'nullable',
            'status'           => 'nullable',
            'description'      => 'required',
            'sort_description' => 'nullable|string|max:500',
            'icon'        => 'required|string|max:190',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['title'] = 'required|string|max:255';
            $rules['slug'] = 'sometimes|string|max:255|unique:services,slug,' . $this->service;
        }
        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|max:255|unique:services,slug';
            $rules['title'] = 'required|string|max:255|unique:service_translations,title';
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

            'image.required'          => __('The image is required.'),
            'image.image'             => __('The image must be an image.'),
            'image.max'               => __('The image may not be greater than 2048 kilobytes.'),

            'title.required'          => __('The title is required.'),
            'title.string'            => __('The title must be a string.'),
            'title.max'               => __('The title may not be greater than 255 characters.'),

            'icon.required'        => __('Icon is required.'),
            'icon.string'         => __('Icon must be a string.'),
            'icon.max'            => __('Icon must not exceed 190 characters.'),

            'slug.required'           => __('Slug is required.'),
            'slug.max'                => __('The slug may not be greater than 255 characters.'),
            'slug.unique'             => __('Slug must be unique.'),

            'title.unique'            => __('Title must be unique.'),

            'description.required'    => __('Description is required.'),

            'sort_description.max'      => __('Short description must be a string.'),
            'sort_description.string'   => __('Short description may not be greater than 500 characters.'),
        ];
    }
}
