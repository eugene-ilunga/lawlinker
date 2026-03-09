<div class="tab-pane fade active show pt-0" id="work_tab" role="tabpanel">
    <div class="card">
        <div class="card-header d-flex justify-content-between">
            <div>
                <x-admin.back-button :href="route('admin.dashboard')" />
            </div>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.work-section.update', ['code' => $code]) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="row">
                    <div class="form-group">
                        <x-admin.form-input data-translate="true" id="title" name="title" label="{{ __('Title') }}"
                            placeholder="{{ __('Enter Title') }}"
                            value="{{ $workSection?->getTranslation($code)?->title }}" required="true" />
                    </div>
                    <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
                        <x-admin.form-input type="link" id="video" name="video" label="{{ __('Video Link') }}"
                            placeholder="{{ __('Enter Video Link') }}" value="{{ $workSection?->video }}"
                            required="true" />
                    </div>

                    <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
                        <x-admin.form-image-preview recommended="645X645" :image="$workSection?->image" required="0" />
                    </div>
                    <div class="form-group {{$code == $languages->first()->code ? '': 'd-none'}}">
                        <x-admin.form-switch name="status" label="{{ __('Status') }}" :checked="$workSection?->status == 1" />
                    </div>

                </div>
                <div class="row">
                    <div class="col-md-12">
                        <x-admin.update-button :text="__('Update')" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
