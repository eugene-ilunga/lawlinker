<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class RdcPhoneNumber implements ValidationRule
{
    /**
     * Accepts RDC mobile numbers in 9 digits (899730021 / 999730021)
     * or local 10 digits prefixed with 0 (0899730021 / 0999730021).
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! is_string($value)) {
            $fail(__('Please provide a valid RDC phone number.'));
            return;
        }

        $normalized = preg_replace('/\D+/', '', trim($value));

        if (! preg_match('/^0?[89]\d{8}$/', $normalized)) {
            $fail(__('Please provide a valid RDC phone number.'));
        }
    }
}
