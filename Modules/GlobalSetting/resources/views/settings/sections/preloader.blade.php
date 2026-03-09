<div class="tab-pane fade" id="preloader_tab" role="tabpanel">
    <form action="{{ route('admin.update-preloader') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="form-group">
            <x-admin.form-switch name="preloader" label="{{ __('Status') }}"
                :checked="$setting->preloader == '1'" />
        </div>
        <div class="form-group preloader-image-box @if ($setting->preloader == 0) d-none @endif">
            <x-admin.form-image-preview recommended="120X120" div_id="preloader-image-preview" label_id="preloader-image-label" input_id="preloader-image-upload" :image="$setting->preloader_image" name="preloader_image" label="{{ __('Existing GIF') }}" button_label="{{ __('Update GIF') }}" required="0" />
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
