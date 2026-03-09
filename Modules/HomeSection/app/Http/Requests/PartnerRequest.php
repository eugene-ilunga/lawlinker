<?php

namespace Modules\HomeSection\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PartnerRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'status' => 'nullable',
            'image' => 'required|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048',
            'link'   => 'nullable|string',
        ];
        if ($this->isMethod('post') && !empty($this->route('id'))) {
            $rules['image'] = 'nullable|image|mimes:jpeg,jpg,png,gif,webp,svg|max:2048';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'image.required' => __('Image is required'),
            'image.image'    => __('The image must be an image.'),
            'image.max'      => __('The image may not be greater than 2048 kilobytes.'),
        ];
    }
}
