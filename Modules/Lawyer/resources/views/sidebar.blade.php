    <li
        class="nav-item dropdown {{ isRoute(['admin.lawyer.*', 'admin.department.*', 'admin.department.gallery', 'admin.department.videos', 'admin.faq.by.department', 'admin.location.index', 'admin.leave.*'], 'active') }}">
        <a href="javascript:void()" class="nav-link has-dropdown"><i
                class="fas fa-gavel"></i><span>{{ __('Lawyer') }}</span></a>

        <ul class="dropdown-menu">
            @if (checkAdminHasPermission('department.view'))
                <li
                    class="{{ isRoute(['admin.department.*', 'admin.department.gallery', 'admin.department.videos', 'admin.faq.by.department'], 'active') }}">
                    <a class="nav-link" href="{{ route('admin.department.index') }}">
                        {{ __('Department') }}
                    </a>
                </li>
            @endif
            @if (checkAdminHasPermission('location.view'))
                <li class="{{ isRoute('admin.location.index', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.location.index', ['code' => getSessionLanguage()]) }}">
                        {{ __('Location') }}
                    </a>
                </li>
            @endif
            @if (checkAdminHasPermission('lawyer.view'))
                <li class="{{ isRoute('admin.lawyer.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.lawyer.index') }}">
                        {{ __('Lawyer') }}
                    </a>
                </li>
            @endif
            @if (checkAdminHasPermission('leave.management'))
                <li class="{{ isRoute('admin.leave.*', 'active') }}">
                    <a class="nav-link" href="{{ route('admin.leave.index') }}">
                        {{ __('Leave') }}
                    </a>
                </li>
            @endif
        </ul>
    </li>
