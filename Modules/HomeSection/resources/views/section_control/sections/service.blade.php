<div class="tab-pane fade" id="service_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="service_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="service_first_heading" name="service_first_heading" label="{{ __('First Heading') }}" value="{{ $section_control?->getTranslation($code)?->service_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="service_second_heading" name="service_second_heading" label="{{ __('Second Heading') }}" value="{{ $section_control?->getTranslation($code)?->service_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="service_description" name="service_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->service_description }}" maxlength="1000" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="service_how_many" name="service_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->service_how_many ?? 6 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="service_status" label="{{ __('Status') }}" :checked="$section_control?->service_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>