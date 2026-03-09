<?php

namespace Modules\HomeSection\app\Services;

class SectionValidatingService {
    public function validateSectionStatusOnly($request, $columnName): array {
        $rules = [
            'code'      => 'required|string|exists:languages,code',
            $columnName => 'required',
        ];

        $messages = [
            'code.required' => __('The language code is required.'),
            'code.string'   => __('The language code must be a string.'),
            'code.exists'   => __('The selected language code is invalid.'),
            "$columnName.required"   => __('How many field is required'),
        ];

        return [$rules, $messages];
    }

    public function validateSection($request, $howMany, $status, $firstHeading, $secondHeading, $description): array {
        $rules = [
            'code'         => 'required|string|exists:languages,code',
            $howMany       => 'nullable',
            $status        => 'nullable',
            $firstHeading  => 'required|string|max:100',
            $secondHeading => 'required|string|max:100',
            $description   => 'required|string',
        ];
        if ($request->code == allLanguages()?->first()?->code) {
            $rules[$howMany] = 'required';
        }

        $messages = [
            'code.required'           => __('The language code is required.'),
            'code.string'             => __('The language code must be a string.'),
            'code.exists'             => __('The selected language code is invalid.'),

            "$firstHeading.required"  => __('First heading is required.'),
            "$firstHeading.string"    => __('First heading must be a string.'),
            "$firstHeading.max"       => __('First heading must not exceed 100 characters.'),

            "$secondHeading.required" => __('Second subheading is required.'),
            "$secondHeading.string"   => __('Second subheading must be a string.'),
            "$secondHeading.max"      => __('Second subheading must not exceed 100 characters.'),
            "$description.required"   => __('Description is required.'),
            "$howMany.required"   => __('How many field is required'),
        ];

        return [$rules, $messages];
    }
}
