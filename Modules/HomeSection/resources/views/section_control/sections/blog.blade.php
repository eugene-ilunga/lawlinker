<div class="tab-pane fade" id="blog_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="blog_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="blog_first_heading" name="blog_first_heading" label="{{ __('First Heading') }}" value="{{ $section_control?->getTranslation($code)?->blog_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="blog_second_heading" name="blog_second_heading" label="{{ __('Second Heading') }}" value="{{ $section_control?->getTranslation($code)?->blog_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="blog_description" name="blog_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->blog_description }}" maxlength="1000" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="blog_how_many" name="blog_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->blog_how_many ?? 4 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="blog_status" label="{{ __('Status') }}" :checked="$section_control?->blog_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>