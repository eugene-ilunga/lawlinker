<div class="tab-pane fade active show" id="feature_tab" role="tabpanel">
    <form action="{{ route('admin.section-control.update', ['code' => $code]) }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="feature_section" />
        <div class="form-group">
            <x-admin.form-input type="number" id="feature_how_many" name="feature_how_many" label="{{ __('How Many') }}" value="{{ $section_control?->feature_how_many ?? 3 }}" required="true"/>
        </div>
        <div class="form-group">
            <x-admin.form-switch name="feature_status" label="{{ __('Status') }}" :checked="$section_control?->feature_status == 1" />
        </div>
        <x-admin.update-button :text="__('Update')" />
    </form>
</div>
