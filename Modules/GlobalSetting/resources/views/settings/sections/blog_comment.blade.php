<div class="tab-pane fade" id="comment_tab" role="tabpanel">
    <form action="{{ route('admin.update-blog-comment') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <x-admin.form-select id="comment_type" name="comment_type" label="{{ __('Comment Type') }}" class="form-select select2" required="true">
                <x-admin.select-option :selected="$setting->comment_type == 1" value="1" text="{{ __('Custom Comment') }}" />
                <x-admin.select-option :selected="$setting->comment_type == 0" value="0" text="{{ __('Facebook Comment') }}" />
            </x-admin.form-select>
        </div>
        <div class="form-group facebook-comment-box @if($setting->comment_type == 1) d-none @endif">
            <x-admin.form-input id="facebook_comment_script" name="facebook_comment_script" label="{{ __('Facebook App ID') }}"
                value="{{ $setting->facebook_comment_script }}" required="true"/>
        </div>

        <x-admin.update-button :text="__('Update')" />

    </form>
</div>
