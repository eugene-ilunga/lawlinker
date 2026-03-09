<?php

namespace Modules\Blog\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [];

        if ($this->isMethod('put')) {
            $id = $this->route('blog_category')->id;
            $rules['code'] = 'required|string';
            $rules['title'] = 'required|string|max:255|unique:blog_category_translations,title,' . $id . ',blog_category_id';

            $rules['slug'] = 'required|string|max:255|unique:blog_categories,slug,' . $id . ',id';
        }
        if ($this->isMethod('post')) {
            $rules['slug'] = 'required|string|max:255';
            $rules['title'] = 'required|string|max:255|unique:blog_category_translations,title';
            $rules['slug'] = 'required|string|max:255|unique:blog_categories,slug';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.unique' => __('Title must be unique.'),
            'title.required' => __('The title is required.'),
            'title.max' => __('Title must be string with a maximum length of 255 characters.'),
            'slug.unique' => __('Slug must be unique.'),
            'slug.required' => __('Slug is required.'),
            'slug.max' => __('Slug must be string with a maximum length of 255 characters.'),
        ];
    }
}
