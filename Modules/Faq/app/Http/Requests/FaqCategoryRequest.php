<?php

namespace Modules\Faq\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class FaqCategoryRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [];

        if ($this->isMethod('put')) {
            $id = $this->route('faq_category')->id;
            $rules['code'] = 'required|string';
            $rules['slug'] = 'required|string|max:255|unique:faq_categories,slug,' . $id . ',id';
            $rules['title'] = 'required|string|max:255|unique:faq_category_translations,title,' . $id . ',faq_category_id';
        }
        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|max:255|unique:faq_categories,slug';
            $rules['title'] = 'required|string|max:255|unique:faq_category_translations,title';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'title.required' => __('The title is required.'),
            'title.max'      => __('The title may not be greater than 255 characters.'),
            'title.string'   => __('The title must be a string.'),
            'title.unique'   => __('Title must be unique.'),
            'slug.required'  => __('Slug is required.'),
            'slug.string'    => __('The slug must be a string.'),
            'slug.unique'    => __('Slug must be unique.'),
            'slug.max'       => __('The slug may not be greater than 255 characters.'),
        ];
    }
}
