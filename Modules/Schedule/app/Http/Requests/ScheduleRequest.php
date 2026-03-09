<?php

namespace Modules\Schedule\app\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class ScheduleRequest extends FormRequest {
    public function authorize(): bool {
        return Auth::guard('admin')->check() ? true : false;
    }

    public function rules(): array {
        $rules = [
            'day_id'     => 'required|exists:days,id',
            'lawyer_id'  => 'required|exists:lawyers,id',
            'start_time' => [
                'required',
                'date_format:H:i',
                function ($attribute, $value, $fail) {
                    if ($value >= $this->input('end_time')) {
                        $fail(__('The start time must be less than the end time.'));
                    }
                }
            ],
            'end_time' => 'required|date_format:H:i',
            'quantity'   => 'required|numeric',
            'status'     => 'nullable',
        ];

        return $rules;
    }

    public function messages(): array {
        return [
            'day_id.required'     => __('The day is required.'),
            'day_id.exists'       => __('The selected day is invalid.'),
            'lawyer_id.required'  => __('The lawyer is required.'),
            'lawyer_id.exists'    => __('The selected lawyer is invalid.'),
            'start_time.required' => __('Start time is required.'),
            'end_time.required'   => __('End time is required.'),
            'quantity.required'   => __('Quantity is must be numeric and required.'),
            'quantity.numeric'    => __('Quantity is must be numeric and required.'),
        ];
    }
}
