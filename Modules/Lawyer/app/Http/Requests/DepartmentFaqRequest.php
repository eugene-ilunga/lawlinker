<?php

namespace Modules\Lawyer\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class DepartmentFaqRequest extends FormRequest {
    public function authorize(): bool {
        return (Auth::guard('admin')->check() && checkAdminHasPermission('department.update')) ? true : false;
    }

    public function rules(): array {
        $rules = [
            'department_id' => 'sometimes|exists:departments,id',
            'question'      => 'required|string|max:255',
            'answer'        => 'required|string|max:10000',
        ];

        return $rules;
    }

    public function messages(): array {
        return [
            'question.required' => __('The question field is required.'),
            'question.string'   => __('The question must be a string.'),
            'question.max'      => __('The question may not be greater than 255 characters.'),
            'answer.required'   => __('The answer field is required.'),
            'answer.string'     => __('The answer must be a string.'),
            'answer.max'        => __('The answer may not be greater than 10000 characters.'),
        ];
    }
}
