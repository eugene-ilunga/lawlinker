<div class="tab-pane fade pt-0" id="banner_tab" role="tabpanel">
    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.app.banner.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-4 form-group">
                        <x-admin.form-image-preview recommended="375X275" recommended_class="d-block" label="{{ __('Banner Image') }}" :image="$setting?->app_banner" required="0" />
                    </div>
                    <div class="col-md-4 form-group">
                        <x-admin.form-image-preview recommended="375X812" recommended_class="d-block" div_id="image-preview-4" label_id="image-label-4"
                            input_id="image-upload-4" label="{{ __('Login and register Page Background') }}" :image="$setting?->app_login_img"
                            name="app_login_img" required="0" />
                    </div>
                    <div class="col-md-4 form-group">
                        <x-admin.form-image-preview recommended="375X812" recommended_class="d-block" div_id="image-preview-2" label_id="image-label-2"
                            input_id="image-upload-2" label="{{ __('Forgot Password Page Background') }}" :image="$setting?->app_forgot_password_img"
                            name="app_forgot_password_img" required="0" />
                    </div>
                </div>
                <x-admin.update-button :text="__('Update')" />
            </form>
        </div>
    </div>
</div>
