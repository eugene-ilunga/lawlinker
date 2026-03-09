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
