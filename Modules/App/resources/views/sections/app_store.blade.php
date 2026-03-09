<div class="tab-pane fade pt-0" id="app_store" role="tabpanel">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app.store.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <x-admin.form-switch name="google_app_store_status" label="{{ __('Google Play Store') }}"
                        :checked="$setting->google_app_store_status == '1'" />
                    <div class="google-app-store-area @if ($setting->google_app_store_status == 0) d-none @endif">
                        <x-admin.form-input id="google_app_store_link" name="google_app_store_link"
                            label="{{ __('Link') }}" value="{{ $setting->google_app_store_link }}" />
                        <br>
                        <x-admin.form-image-preview recommended="160X50" div_id="image-preview-5" label_id="image-label-5"
                            input_id="image-upload-5" :image="$setting->google_app_store_img" name="google_app_store_img" required="0" />
                    </div>
                </div>


                <div class="form-group">
                    <x-admin.form-switch name="apple_app_store_status" label="{{ __('Apple Store') }}"
                        :checked="$setting->apple_app_store_status == '1'" />
                    <div class="apple-app-store-area @if ($setting->apple_app_store_status == 0) d-none @endif">
                        <x-admin.form-input id="apple_app_store_link" name="apple_app_store_link"
                            label="{{ __('Link') }}" value="{{ $setting->apple_app_store_link }}" />
                        <br>
                        <x-admin.form-image-preview recommended="160X50" div_id="image-preview-6" label_id="image-label-6"
                            input_id="image-upload-6" :image="$setting->apple_app_store_img" name="apple_app_store_img" required="0" />
                    </div>
                </div>

                <x-admin.update-button :text="__('Update')" />
            </form>
        </div>
    </div>
</div>
