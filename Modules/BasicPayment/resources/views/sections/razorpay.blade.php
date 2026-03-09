<div class="tab-pane fade" id="razorpay_tab" role="tabpanel">
    <form action="{{ route('admin.razorpay-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input id="razorpay_charge" name="razorpay_charge" label="{{ __('Gateway charge') }}(%)"
                    value="{{ $basic_payment->razorpay_charge }}" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="razorpay_key" name="razorpay_key" value="demo-razorpay-39394343-test-key"
                        label="{{ __('Razorpay key') }}" required="true" />
                @else
                    <x-admin.form-input id="razorpay_key" name="razorpay_key"
                        value="{{ $basic_payment->razorpay_key }}" label="{{ __('Razorpay key') }}" required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="razorpay_secret" name="razorpay_secret"
                        value="demo-razorpay-8384934-test-secret" label="{{ __('Razorpay secret') }}" required="true" />
                @else
                    <x-admin.form-input id="razorpay_secret" name="razorpay_secret"
                        value="{{ $basic_payment->razorpay_secret }}" label="{{ __('Razorpay secret') }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="razorpay_name" name="razorpay_name"
                    value="{{ $basic_payment->razorpay_name }}" label="{{ __('Razorpay App Name') }}"
                    required="true" />
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="razorpay_description" name="razorpay_description"
                    value="{{ $basic_payment->razorpay_description }}" label="{{ __('Razorpay Description') }}"
                    required="true" />
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-input id="razorpay_theme_color" name="razorpay_theme_color" type="color"
                    value="{{ $basic_payment->razorpay_theme_color }}" label="{{ __('Theme color') }}"
                    required="true" />
            </div>
        </div>

        <div class="form-group">
            <x-admin.form-image-preview recommended="200X110" name="razorpay_image" div_id="razorpay_image_preview"
                label_id="razorpay_image_label" input_id="razorpay_image_upload" :image="$basic_payment->razorpay_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0" />
        </div>

        <div class="form-group">
            <x-admin.form-switch name="razorpay_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->razorpay_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
