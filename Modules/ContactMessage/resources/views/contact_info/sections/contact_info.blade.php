<div class="tab-pane fade active show" id="contact_info" role="tabpanel">
    <form action="{{ checkAdminHasPermission('contact.info.update') ? route('admin.contact-info.update', ['code' => $code]) : '' }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="contact_info_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="header" name="header" label="{{ __('Contact Header') }}"
                value="{{ $contact_info?->getTranslation($code)?->header }}"
                placeholder="{{ __('Enter contact header') }}" required="true" />
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="description" name="description" label="{{ __('Contact description') }}"
                placeholder="{{ __('Enter contact description') }}"
                value="{{ $contact_info?->getTranslation($code)?->description }}" maxlength="500" required="true" />
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="about" name="about" label="{{ __('Footer about') }}"
                placeholder="{{ __('Enter footer about') }}" value="{{ $contact_info?->getTranslation($code)?->about }}"
                maxlength="500" required="true" />
        </div>
        @if ($code == $languages->first()->code)
            <div class="form-group">
                <x-admin.form-input id="phone" name="phone" label="{{ __('Phone') }}"
                    placeholder="{{ __('Enter phone') }}" value="{{ $contact_info?->phone }}" required="true" />
            </div>
            <div class="form-group">
                <x-admin.form-input type="email" id="email" name="email" label="{{ __('Email') }}"
                    placeholder="{{ __('Enter email') }}" value="{{ $contact_info?->email }}" required="true" />
            </div>
            <div class="form-group">
                <x-admin.form-textarea id="address" name="address" label="{{ __('Address') }}"
                    placeholder="{{ __('Enter Address') }}" value="{{ $contact_info?->address }}" maxlength="500"
                    required="true" />
            </div>
            <div class="form-group">
                <x-admin.form-textarea id="map_embed_code" name="map_embed_code"
                    label="{{ __('Google Map Embed Code') }}" placeholder="{{ __('Enter google map embed code') }}"
                    value="{!! $contact_info?->map_embed_code !!}" required="true" />
            </div>
        @endif
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="copyright" name="copyright" label="{{ __('Copyright') }}"
                placeholder="{{ __('Enter copyright') }}"
                value="{{ $contact_info?->getTranslation($code)?->copyright }}" required="true" />
        </div>

        @adminCan('contact.info.update')
            <x-admin.update-button :text="__('Update')" />
        @endadminCan
    </form>
</div>
