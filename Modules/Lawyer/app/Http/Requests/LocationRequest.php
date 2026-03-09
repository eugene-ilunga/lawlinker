<?php

namespace Modules\Lawyer\app\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class LocationRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'status' => 'nullable',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
            $rules['name'] = 'required|string|max:255';
        }
        if ($this->isMethod('post')) {
            $rules['name'] = 'required|string|max:255|unique:location_translations,name';
        }

        return $rules;
    }

    public function messages(): array {
        return [
            'code.required' => __('Language is required and must be a string.'),
            'code.exists'   => __('The selected language is invalid.'),

            'name.unique'   => __('Name must be unique.'),
            'name.required'           => __('Name is required'),
            'name.max'                => __('The name may not be greater than 255 characters.'),
            'name.string'             => __('The name must be a string.'),
        ];
    }
}
