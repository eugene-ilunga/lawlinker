<div class="tab-pane fade {{$code == $languages->first()->code ? '': 'active show'}}" id="work_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="work_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="work_first_heading" name="work_first_heading" label="{{ __('First Heading') }}"
                value="{{ $section_control?->getTranslation($code)?->work_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="work_second_heading" name="work_second_heading" label="{{ __('Second Heading') }}"
                value="{{ $section_control?->getTranslation($code)?->work_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="work_description" name="work_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->work_description }}" maxlength="1000" />
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="work_how_many" name="work_how_many" label="{{ __('How Many') }}"
                value="{{ $section_control?->work_how_many ?? 3 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="work_status" label="{{ __('Status') }}" :checked="$section_control?->work_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
