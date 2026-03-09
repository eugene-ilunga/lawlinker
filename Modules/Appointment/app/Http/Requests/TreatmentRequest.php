<?php

namespace Modules\Appointment\app\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TreatmentRequest extends FormRequest {
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        $rules = [
            'subject'     => 'required|string|max:255',
            'description' => 'required',
            'files'       => 'nullable|array',
            'files.*'     => 'file|mimes:pdf,doc,docx,xls,xlsx,txt,jpg,jpeg,png,webp|max:5240', // max 5MB per file
        ];
        return $rules;
    }

    public function messages(): array {
        return [

            'subject.required'     => __('The subject is required.'),
            'subject.string'       => __('The subject must be a string.'),
            'subject.max'          => __('The subject may not be greater than 255 characters.'),

            'description.required' => __('Description is required.'),

            'files.array'          => __('The files must be an array.'),
            'files.*.file'         => __('Each attachment must be a valid file.'),
            'files.*.mimes'        => __('Each file must be one of the following types: pdf, doc, docx, xls, xlsx, txt, jpg, jpeg, png, webp.'),
            'files.*.max'          => __('Each file may not be greater than 5MB.'),
        ];
    }
}
