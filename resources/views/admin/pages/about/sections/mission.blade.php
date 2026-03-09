<div class="tab-pane fade" id="mission_tab" role="tabpanel">
    <form action="{{ route('admin.pages.about-us.update', ['code' => $code]) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="tab" value="mission_section">
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-image-preview recommended="625X535" data-translate="true" div_id="mission_image_preview" label_id="mission_image_label"
                input_id="mission_image_upload" :image="$aboutus?->mission_image" name="mission_img"
                label="{{ __('Existing Image') }}" button_label="{{ __('Update Image') }}" required="0"/>
        </div>
        <div class="form-group">
            <x-admin.form-editor data-translate="true" id="mission_description" name="mission_description" label="{{ __('Description') }}"
                value="{!! $aboutus?->getTranslation($code)->mission_description !!}" required="true" />
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="mission_status" label="{{ __('Status') }}" :checked="$aboutus?->mission_status == '1'" />
        </div>
        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </form>
</div>
