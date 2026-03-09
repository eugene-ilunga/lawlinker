<?php

namespace App\Services;

class AboutpageValidatingService {
    public function aboutSection($request): array {
        $rules = [
            'code'                   => 'required|string|exists:languages,code',
            'status'                 => 'nullable',
            'about_description'      => 'required',
            'image'                  => 'nullable|image|mimes:webp,png,jpg,jpeg|max:2048',
            'about_background_image' => 'nullable|image|mimes:webp,png,jpg,jpeg|max:2048',
        ];

        $messages = [
            'code.required'                => __('The language code is required.'),
            'code.string'                  => __('The language code must be a string.'),
            'code.exists'                  => __('The selected language code is invalid.'),
            'about_description.required'   => __('Description is required.'),
            'image.image'                  => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'image.max'                    => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'about_background_image.image' => __('The background image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'about_background_image.max'   => __('The background image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
        ];
        return [$rules, $messages];
    }
    public function missionSection($request): array {
        $rules = [
            'code'                => 'required|string|exists:languages,code',
            'mission_status'      => 'nullable',
            'mission_description' => 'required',
            'mission_img'         => 'nullable|image|mimes:webp,png,jpg,jpeg|max:2048',
        ];

        $messages = [
            'code.required'                => __('The language code is required.'),
            'code.string'                  => __('The language code must be a string.'),
            'code.exists'                  => __('The selected language code is invalid.'),
            'mission_description.required' => __('Description is required.'),
            'mission_img.image'            => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'mission_img.max'              => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
        ];
        return [$rules, $messages];
    }
    public function visionSection($request): array {
        $rules = [
            'code'               => 'required|string|exists:languages,code',
            'vision_status'      => 'nullable',
            'vision_description' => 'required',
            'vision_img'         => 'nullable|image|mimes:webp,png,jpg,jpeg|max:2048',
        ];

        $messages = [
            'code.required'               => __('The language code is required.'),
            'code.string'                 => __('The language code must be a string.'),
            'code.exists'                 => __('The selected language code is invalid.'),
            'vision_description.required' => __('Description is required.'),
            'vision_img.image'            => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
            'vision_img.max'              => __('The image must be an image file with a maximum size of 2048 kilobytes (2 MB).'),
        ];
        return [$rules, $messages];
    }
}
