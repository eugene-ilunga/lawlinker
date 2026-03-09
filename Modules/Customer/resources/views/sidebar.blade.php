<li
    class="nav-item dropdown {{ isRoute(['admin.all-customers', 'admin.active-customers', 'admin.non-verified-customers', 'admin.banned-customers', 'admin.customer-show', 'admin.send-bulk-mail'], 'active') }}">
    <a href="javascript:void()" class="nav-link has-dropdown">
        <i class="fas fa-user"></i><span>{{ __('Manage Clients') }}</span>
    </a>

    <ul class="dropdown-menu">
        <li class="{{ isRoute('admin.all-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.all-customers') }}">
                {{ __('All Clients') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.active-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.active-customers') }}">
                {{ __('Active Client') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.non-verified-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.non-verified-customers') }}">
                {{ __('Non verified') }}
            </a>
        </li>

        <li class="{{ isRoute('admin.banned-customers', 'active') }}">
            <a class="nav-link" href="{{ route('admin.banned-customers') }}">
                {{ __('Banned Client') }}
            </a>
        </li>
        @adminCan('client.bulk.mail')
            <li class="{{ isRoute('admin.send-bulk-mail', 'active') }}">
                <a class="nav-link" href="{{ route('admin.send-bulk-mail') }}">
                    {{ __('Send bulk mail') }}
                </a>
            </li>
        @endadminCan
    </ul>
</li>
