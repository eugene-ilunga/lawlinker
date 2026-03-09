<?php

namespace Modules\HomeSection\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SliderRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'status' => 'nullable',
            'image'  => 'sometimes|image|max:2048',
            'title'  => 'required|string|max:255',
        ];

        return $rules;
    }

    public function messages(): array {
        return [
            'image.required' => __('Image is required'),
            'image.image'    => __('The image must be an image.'),
            'image.max'      => __('The image may not be greater than 2048 kilobytes.'),
            'title.required' => __('The title is required.'),
            'title.string'   => __('The title must be a string.'),
            'title.max'      => __('The title may not be greater than 255 characters.'),
        ];
    }
}
