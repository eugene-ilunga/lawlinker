<div class="section-body row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <a href="{{ route('admin.service.edit', [
                    'service' => $service->id,
                    'code' => allLanguages()->first()->code,
                ]) }}"
                    class="m-1 btn {{ Route::is('admin.service.edit') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Service') }}</a>
                <a href="{{ route('admin.service.gallery', $service->id) }}"
                    class="m-1 btn {{ Route::is('admin.service.gallery') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Gallery') }}</a>
                <a href="{{ route('admin.service.videos', $service->id) }}"
                    class="m-1 btn {{ Route::is('admin.service.videos') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Videos') }}</a>
                <a href="{{ route('admin.faq.by.service', ['slug'=> $service->slug, 'code' => getSessionLanguage()]) }}"
                    class="m-1 btn {{ Route::is('admin.faq.by.service') ? 'btn-success' : 'btn-info' }}">{{ __('Manage FAQs') }}</a>
            </div>
        </div>
    </div>
</div>
