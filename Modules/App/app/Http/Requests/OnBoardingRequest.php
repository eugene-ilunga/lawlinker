<?php

namespace Modules\App\app\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class OnBoardingRequest extends FormRequest
{
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'title' => 'required|string|max:255',
            'sort_description' => 'required|string|max:500',
            'status'           => 'nullable',
        ];

        if ($this->isMethod('put')) {
            $rules['order'] = 'required|unique:on_boarding_screens,order,' . $this->on_boarding_screen;
            $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
        }
        if ($this->isMethod('post')) {
            $rules['image'] = 'required|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
            $rules['order'] = 'required|unique:on_boarding_screens,order';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'image.required'          => __('The image is required.'),
            'image.image'             => __('The image must be an image.'),
            'image.max'               => __('The image may not be greater than 2048 kilobytes.'),

            'title.required'          => __('The title is required.'),
            'title.string'            => __('The title must be a string.'),
            'title.max'               => __('The title may not be greater than 255 characters.'),
            'title.unique'            => __('Title must be unique.'),

            'order.required'          => __('Order number is required.'),
            'order.unique'            => __('Order number already taken'),

            'sort_description.max'      => __('Short description must be a string.'),
            'sort_description.string'   => __('Short description may not be greater than 500 characters.'),
        ];
    }
}
