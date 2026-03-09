<?php

namespace Modules\ContactMessage\app\Services;

class ContactInfoValidatingService {
    public function validateTopBarInfo($request): array {
        $rules = [
            'top_bar_email' => 'required|email',
            'top_bar_phone' => 'required',
        ];

        $messages = [
            'top_bar_email.required' => __('Email is required'),
            'top_bar_email.unique'   => __('Email already exist'),
            'top_bar_phone.required' => __('Phone is required'),
        ];

        return [$rules, $messages];
    }

    public function validateContactInfo($request): array {
        $firstLanguage = allLanguages()?->first();
        $rules = [
            'code'           => 'required|string|exists:languages,code',
            'email'          => 'sometimes|email',
            'phone'          => 'sometimes',
            'address'        => 'sometimes',
            'map_embed_code' => 'sometimes',
            'header'         => 'required',
            'description'    => 'required',
            'about'          => 'required',
            'copyright'      => 'required',
        ];

        if ($request->code == $firstLanguage?->code) {
            $rules['phone'] = 'required';
            $rules['email'] = 'required';
            $rules['address'] = 'required';
            $rules['map_embed_code'] = 'required';
        }

        $messages = [
            'code.required'           => __('The language code is required.'),
            'code.string'             => __('The language code must be a string.'),
            'code.exists'             => __('The selected language code is invalid.'),

            'phone.required'          => __('Phone number is required.'),
            'email.required'          => __('Email is required'),
            'email.unique'            => __('Email already exist'),
            'phone.sometimes'         => __('Phone is required'),
            'address.required'        => __('Address is required'),
            'map_embed_code.required' => __('Map embed code is required'),
            'header.required'         => __('Header is required'),
            'description.required'    => __('Description is required'),
            'about.required'          => __('Footer about is required'),
            'copyright.required'      => __('Copyright is required'),
        ];

        return [$rules, $messages];
    }
}
