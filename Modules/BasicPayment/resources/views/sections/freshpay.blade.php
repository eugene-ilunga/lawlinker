<div class="tab-pane fade" id="freshpay_tab" role="tabpanel">
    <form action="{{ route('admin.freshpay-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="freshpay_api_url" name="freshpay_api_url" label="{{ __('FreshPay API URL') }}"
                value="{{ $basic_payment->freshpay_api_url ?? 'https://paydrc.gofreshbakery.net/api/v5/' }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="freshpay_merchant_id" name="freshpay_merchant_id" label="{{ __('Merchant ID') }}"
                value="{{ $basic_payment->freshpay_merchant_id ?? '' }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="freshpay_merchant_secret" name="freshpay_merchant_secret" label="{{ __('Merchant Secret') }}"
                value="{{ $basic_payment->freshpay_merchant_secret ?? '' }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="freshpay_firstname" name="freshpay_firstname" label="{{ __('Firstname') }}"
                value="{{ $basic_payment->freshpay_firstname ?? '' }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-input id="freshpay_lastname" name="freshpay_lastname" label="{{ __('Lastname') }}"
                value="{{ $basic_payment->freshpay_lastname ?? '' }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-input type="email" id="freshpay_email" name="freshpay_email" label="{{ __('Email') }}"
                value="{{ $basic_payment->freshpay_email ?? '' }}" required="true" />
        </div>

        <div class="form-group">
            <label for="freshpay_callback_signature_secret">{{ __('Signature Secret') }}</label>
            <div class="input-group">
                <x-admin.form-input id="freshpay_callback_signature_secret" name="freshpay_callback_signature_secret"
                    value="{{ $basic_payment->freshpay_callback_signature_secret ?? '' }}" />
                <button type="button" class="btn btn-outline-primary freshpay-generate-key" data-target="freshpay_callback_signature_secret" data-length="48">{{ __('Generate') }}</button>
            </div>
        </div>

        <div class="form-group">
            <label for="freshpay_callback_aes_key">{{ __('AES Key') }}</label>
            <div class="input-group">
                <x-admin.form-input id="freshpay_callback_aes_key" name="freshpay_callback_aes_key"
                    value="{{ $basic_payment->freshpay_callback_aes_key ?? '' }}" />
                <button type="button" class="btn btn-outline-primary freshpay-generate-key" data-target="freshpay_callback_aes_key" data-length="32">{{ __('Generate') }}</button>
            </div>
        </div>

        <div class="form-group">
            <label for="freshpay_callback_aes_iv">{{ __('AES IV') }}</label>
            <div class="input-group">
                <x-admin.form-input id="freshpay_callback_aes_iv" name="freshpay_callback_aes_iv"
                    value="{{ $basic_payment->freshpay_callback_aes_iv ?? '' }}" />
                <button type="button" class="btn btn-outline-primary freshpay-generate-key" data-target="freshpay_callback_aes_iv" data-length="16">{{ __('Generate') }}</button>
            </div>
        </div>

        <div class="form-group">
            <x-admin.form-input id="freshpay_charge" name="freshpay_charge" label="{{ __('Gateway charge') }}(%)"
                value="{{ $basic_payment->freshpay_charge ?? 0 }}" required="true" />
        </div>

        <div class="form-group">
            <x-admin.form-image-preview recommended="200X110" div_id="image-preview-freshpay" label_id="image-label-freshpay"
                input_id="image-upload-freshpay" :image="$basic_payment->freshpay_image ?? null" name="freshpay_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0"/>
        </div>

        <div class="form-group">
            <x-admin.form-switch name="freshpay_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="($basic_payment->freshpay_status ?? 'inactive') == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
