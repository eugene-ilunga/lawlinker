<div class="tab-pane fade" id="vision_tab" role="tabpanel">
    <form action="{{ route('admin.pages.about-us.update', ['code' => $code]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="tab" value="vision_section">
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-image-preview recommended="625X535" div_id="vision_image_preview" label_id="vision_image_label"
                input_id="vision_image_upload" :image="$aboutus?->vision_image" name="vision_img" label="{{ __('Existing Image') }}"
                button_label="{{ __('Update Image') }}"  required="0"/>
        </div>
        <div class="form-group">
            <x-admin.form-editor data-translate="true" id="vision_description" name="vision_description" label="{{ __('Description') }}"
                value="{!! $aboutus?->getTranslation($code)->vision_description !!}" required="true" />
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="vision_status" label="{{ __('Status') }}" :checked="$aboutus?->vision_status == '1'" />
        </div>
        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </form>
</div>
