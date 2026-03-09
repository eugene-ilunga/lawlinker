<div class="tab-pane fade" id="client_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="client_section" />
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="client_first_heading" name="client_first_heading" label="{{ __('First Heading') }}" value="{{ $section_control?->getTranslation($code)?->client_first_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-input data-translate="true" id="client_second_heading" name="client_second_heading" label="{{ __('Second Heading') }}" value="{{ $section_control?->getTranslation($code)?->client_second_heading }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-textarea data-translate="true" id="client_description" name="client_description" label="{{ __('Description') }}"
                placeholder="{{ __('Enter description') }}"
                value="{{ $section_control?->getTranslation($code)?->client_description }}" maxlength="1000" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-input type="number" id="client_how_many" name="client_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->client_how_many ?? 4 }}" required="true"/>
        </div>
        <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
            <x-admin.form-switch name="client_status" label="{{ __('Status') }}" :checked="$section_control?->client_status == 1"/>
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>