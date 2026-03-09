<div class="tab-pane fade" id="department_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="department_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="department_first_heading" name="department_first_heading" label="{{ __('First Heading') }}" value="{{ $section_control?->getTranslation($code)?->department_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="department_second_heading" name="department_second_heading" label="{{ __('Second Heading') }}" value="{{ $section_control?->getTranslation($code)?->department_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="department_description" name="department_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->department_description }}" maxlength="1000" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="department_how_many" name="department_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->department_how_many ?? 6 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="department_status" label="{{ __('Status') }}" :checked="$section_control?->department_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>