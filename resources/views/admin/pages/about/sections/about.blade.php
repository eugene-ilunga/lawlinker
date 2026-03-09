<div class="tab-pane fade active show" id="about_tab" role="tabpanel">
    <form action="{{ route('admin.pages.about-us.update', ['code' => $code]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="tab" value="about_section">
        <div class="row">
            <div class="form-group col-md-6 {{$code == $languages->first()->code ? '': 'd-none'}}">
                <x-admin.form-image-preview recommended="480X480" :image="$aboutus?->about_image" name="image" label="{{ __('Existing Image') }}"
                    button_label="{{ __('Update Image') }}" required="0"/>
            </div>
            <div class="form-group col-md-6 {{$code == $languages->first()->code ? '': 'd-none'}}">
                <x-admin.form-image-preview recommended="525X455" div_id="background_image_preview" label_id="background_image_label"
                    input_id="background_image_upload" :image="$aboutus?->background_image" name="about_background_image"
                    label="{{ __('Existing Background Image') }}" button_label="{{ __('Update Image') }}" required="0"/>
            </div>
        </div>
        <div class="form-group">
            <x-admin.form-editor data-translate="true" id="about_description" name="about_description" label="{{ __('Description') }}"
                value="{!! $aboutus?->getTranslation($code)->about_description !!}" required="true" />
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="status" label="{{ __('Status') }}" :checked="$aboutus?->status == '1'" />
        </div>

        <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
    </form>
</div>
