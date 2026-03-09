<div class="tab-pane fade" id="error_page_img_tab" role="tabpanel">
    <form action="{{ route('admin.update-error-page') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-image-preview recommended="1000X610" div_id="error_image_preview" label_id="error_image_label"
                input_id="error_image_upload" :image="$setting->error_page_image" name="error_page_image"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
