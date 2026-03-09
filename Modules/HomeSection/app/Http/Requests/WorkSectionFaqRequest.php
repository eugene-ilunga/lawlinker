<?php

namespace Modules\HomeSection\app\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class WorkSectionFaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array
    {
        $rules = [
            'status' => 'nullable',
            'work_section_id' => 'required',
            'question'   => 'required|string|max:255',
            'answer'     => 'required|string|max:10000',
        ];

        if ($this->isMethod('put')) {
            $rules['code'] = 'required|exists:languages,code';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'work_section_id.required' => __('Work Section Id is required'),

            'code.required' => __('Language is required and must be a string.'),
            'code.exists' => __('The selected language is invalid.'),

            'question.required' => __('The question field is required.'),
            'question.string'   => __('The question must be a string.'),
            'question.max'      => __('The question may not be greater than 255 characters.'),
            'answer.required'   => __('The answer field is required.'),
            'answer.string'     => __('The answer must be a string.'),
            'answer.max'        => __('The answer may not be greater than 10000 characters.'),
        ];
    }
}
