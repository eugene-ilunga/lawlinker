<div class="tab-pane fade" id="paystack_tab" role="tabpanel">
    <form action="{{ route('admin.paystack-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input id="paystack_charge" name="paystack_charge" label="{{ __('Gateway charge') }}(%)"
                    value="{{ $basic_payment->paystack_charge }}" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="paystack_public_key" name="paystack_public_key" label="{{ __('Public key') }}"
                        value="paystack-test-348949439-public-key" required="true" />
                @else
                    <x-admin.form-input id="paystack_public_key" name="paystack_public_key"
                        label="{{ __('Public key') }}" value="{{ $basic_payment->paystack_public_key }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-12">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="paystack_secret_key" name="paystack_secret_key"
                        label="{{ __('Secret key') }}" value="paystack-test-8384934-key-secret" required="true" />
                @else
                    <x-admin.form-input id="paystack_secret_key" name="paystack_secret_key"
                        label="{{ __('Secret key') }}" value="{{ $basic_payment->paystack_secret_key }}"
                        required="true" />
                @endif
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview recommended="200X110" div_id="paystack_image_preview" label_id="paystack_image_label"
                input_id="paystack_image_upload" :image="$basic_payment->paystack_image" name="paystack_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="paystack_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->paystack_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>