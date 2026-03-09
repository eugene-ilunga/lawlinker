<div class="tab-pane fade" id="instamojo_tab" role="tabpanel">
    <form action="{{ route('admin.instamojo-update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input id="instamojo_charge" name="instamojo_charge" label="{{ __('Gateway charge') }}(%)"
                    value="{{ $basic_payment->instamojo_charge }}" />
            </div>
            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="instamojo_api_key" name="instamojo_api_key" label="{{ __('API key') }}"
                        value="instamojo-test-348949439-api-key" required="true" />
                @else
                    <x-admin.form-input id="instamojo_api_key" name="instamojo_api_key" label="{{ __('API key') }}"
                        value="{{ $basic_payment->instamojo_api_key }}" required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                @if (env('APP_MODE') == 'DEMO')
                    <x-admin.form-input id="instamojo_auth_token" name="instamojo_auth_token"
                        label="{{ __('Auth token') }}" value="instamojo-test-348949439-auth-token" required="true" />
                @else
                    <x-admin.form-input id="instamojo_auth_token" name="instamojo_auth_token"
                        label="{{ __('Auth token') }}" value="{{ $basic_payment->instamojo_auth_token }}"
                        required="true" />
                @endif
            </div>

            <div class="form-group col-md-6">
                <x-admin.form-select id="instamojo_account_mode" name="instamojo_account_mode"
                    label="{{ __('Account Mode') }}" class="form-select" required="true">
                    <x-admin.select-option :selected="strtolower($basic_payment->instamojo_account_mode) == 'live'" value="live" text="{{ __('Live') }}" />
                    <x-admin.select-option :selected="strtolower($basic_payment->instamojo_account_mode) == 'sandbox'" value="sandbox" text="{{ __('Sandbox') }}" />
                </x-admin.form-select>
            </div>

        </div>

        <div class="form-group">
            <x-admin.form-image-preview recommended="200X110" div_id="instamojo_image_preview" label_id="instamojo_image_label"
                input_id="instamojo_image_upload" :image="$basic_payment->instamojo_image" name="instamojo_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="instamojo_status" label="{{ __('Status') }}" active_value="active"
                inactive_value="inactive" :checked="$basic_payment->instamojo_status == 'active'" />
        </div>

        <x-admin.update-button :text="__('Update')" />
    </form>
</div>