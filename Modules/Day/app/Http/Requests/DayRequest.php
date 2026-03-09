<?php

namespace Modules\Day\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DayRequest extends FormRequest {
    public function authorize(): bool {
        return (Auth::guard('admin')->check() && checkAdminHasPermission('day.update')) ? true : false;
    }

    public function rules(): array {
        $rules = [
            'title' => 'required|string|max:190',
        ];

        return $rules;
    }

    public function messages(): array {
        return [
            'title.required' => __('The title is required.'),
            'title.string'   => __('Title must be string with a maximum length of 255 characters.'),
            'title.max'      => __('Title must be string with a maximum length of 255 characters.'),
        ];
    }
}
