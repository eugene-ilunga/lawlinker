<div class="section-body row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-center">
                <a href="{{ route('admin.department.edit', [
                    'department' => $department->id,
                    'code' => allLanguages()->first()->code,
                ]) }}"
                    class="m-1 btn {{ Route::is('admin.department.edit') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Department') }}</a>
                <a href="{{ route('admin.department.gallery', $department->id) }}"
                    class="m-1 btn {{ Route::is('admin.department.gallery') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Gallery') }}</a>
                <a href="{{ route('admin.department.videos', $department->id) }}"
                    class="m-1 btn {{ Route::is('admin.department.videos') ? 'btn-success' : 'btn-info' }}">{{ __('Manage Videos') }}</a>
                <a href="{{ route('admin.faq.by.department', ['slug' => $department->slug, 'code' => getSessionLanguage()]) }}"
                    class="m-1 btn {{ Route::is('admin.faq.by.department') ? 'btn-success' : 'btn-info' }}">{{ __('Manage FAQs') }}</a>
            </div>
        </div>
    </div>
</div>
