<li class="nav-item dropdown {{ isRoute(['admin.counter.*', 'admin.feature.index', 'admin.work-section.index', 'admin.partner.index', 'admin.slider.index', 'admin.section-control.index'], 'active') }}">
    <a href="javascript:;" class="nav-link has-dropdown">
        <i class="fas fa-th-large"></i><span>{{ __('Home Sections') }}</span>
    </a>
    <ul class="dropdown-menu">
        @if (Route::has('admin.slider.index') && checkAdminHasPermission('slider.view'))
            <li class="{{ isRoute('admin.slider.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.slider.index') }}">
                    {{ __('Sliders') }}
                </a>
            </li>
        @endif
        @if (Route::has('admin.feature.index') && checkAdminHasPermission('feature.view'))
            <li class="{{ isRoute('admin.feature.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.feature.index', ['code' => getSessionLanguage()]) }}">
                    {{ __('Features') }}
                </a>
            </li>
        @endif
        @if (Route::has('admin.work-section.index') && checkAdminHasPermission('work.section.view'))
            <li class="{{ isRoute('admin.work-section.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.work-section.index', ['code' => getSessionLanguage()]) }}">
                    {{ __('Work Section') }}
                </a>
            </li>
        @endif
        @if (Route::has('admin.counter.index') && checkAdminHasPermission('counter.view'))
            <li class="{{ isRoute('admin.counter.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.counter.index', ['code' => getSessionLanguage()]) }}">
                    {{ __('Overview') }}
                </a>
            </li>
        @endif
        @if (Route::has('admin.partner.index') && checkAdminHasPermission('partner.view'))
            <li class="{{ isRoute('admin.partner.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.partner.index') }}">
                    {{ __('Partners') }}
                </a>
            </li>
        @endif
        @if (Route::has('admin.section-control.index') && checkAdminHasPermission('section.view'))
            <li class="{{ isRoute('admin.section-control.index') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('admin.section-control.index', ['code' => getSessionLanguage()]) }}">
                    {{ __('Section Controls') }}
                </a>
            </li>
        @endif
    </ul>
</li>
