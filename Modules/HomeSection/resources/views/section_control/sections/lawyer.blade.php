<div class="tab-pane fade" id="lawyer_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="lawyer_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="lawyer_first_heading" name="lawyer_first_heading" label="{{ __('First Heading') }}" value="{{ $section_control?->getTranslation($code)?->lawyer_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="lawyer_second_heading" name="lawyer_second_heading" label="{{ __('Second Heading') }}" value="{{ $section_control?->getTranslation($code)?->lawyer_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="lawyer_description" name="lawyer_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->lawyer_description }}" maxlength="1000" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="lawyer_how_many" name="lawyer_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->lawyer_how_many ?? 4 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="lawyer_status" label="{{ __('Status') }}" :checked="$section_control?->lawyer_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>