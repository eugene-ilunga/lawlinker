<div class="tab-pane fade" id="color_tab" role="tabpanel">
    <form action="{{ route('admin.update-color') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="form-group col-md-6">
                <x-admin.form-input type="color" id="theme_one" name="theme_one" label="{{ __('Primary Color') }}"
                    value="{{ $setting->theme_one }}" />
            </div>
            <div class="form-group col-md-6">
                <x-admin.form-input type="color" id="theme_two" name="theme_two" label="{{ __('Secondary Color') }}"
                    value="{{ $setting->theme_two }}" />
            </div>
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
