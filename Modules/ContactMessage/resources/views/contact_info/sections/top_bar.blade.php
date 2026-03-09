<div class="tab-pane fade" id="top_bar" role="tabpanel">
    <form action="{{ checkAdminHasPermission('contact.info.update') ? route('admin.contact-info.update', ['code' => $code]) : '' }}" method="POST">
        @csrf
        @method('PUT')
        <x-admin.form-input type="hidden" name="tab" value="top_bar_section" />
        <div class="form-group">
            <x-admin.form-input type="email" id="top_bar_email" name="top_bar_email" label="{{ __('Email') }}" placeholder="{{ __('Enter email') }}" value="{{ $contact_info?->top_bar_email}}" required="true" />
        </div>
        <div class="form-group">
            <x-admin.form-input id="top_bar_phone" name="top_bar_phone" label="{{ __('Phone') }}" placeholder="{{ __('Enter phone') }}" value="{{ $contact_info?->top_bar_phone}}" required="true" />
        </div>
        @adminCan('contact.info.update')
            <x-admin.update-button :text="__('Update')" />
        @endadminCan
    </form>
</div>
