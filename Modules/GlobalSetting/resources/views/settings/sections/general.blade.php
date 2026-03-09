<div class="tab-pane fade active show" id="general_tab" role="tabpanel">
    <form action="{{ route('admin.update-general-setting') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-input id="app_name" name="app_name" label="{{ __('App Name') }}"
                value="{{ $setting->app_name }}" />
        </div>
        <div class="form-group">
            <x-admin.form-input id="prenotification_hour" name="prenotification_hour"
                label="{{ __('Appointment Pre Notification Hour') }}" value="{{ $setting->prenotification_hour }}" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="client_can_register" label="{{ __('Client can register') }}"
                :checked="$setting->client_can_register == '1'" />
        </div>
        <div class="form-group">
            <x-admin.form-switch name="lawyer_can_register" label="{{ __('Lawyer can register') }}" :checked="$setting->lawyer_can_register == '1'" />
        </div>
        <div class="form-group">
            <div class="input-group">
                <x-admin.form-switch name="lawyer_can_add_social_links" label="{{ __('Lawyer can add social links') }}"
                    active_value="active" inactive_value="inactive" :checked="$setting->lawyer_can_add_social_links == 'active'" />

                <div class="ms-2 limit-box @if ($setting->lawyer_can_add_social_links == 'inactive') d-none @endif">
                    <x-admin.form-input type="number" id="lawyer_social_links_limit" name="lawyer_social_links_limit" value="{{ $setting->lawyer_social_links_limit }}" />
                </div>
            </div>

        </div>
        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
