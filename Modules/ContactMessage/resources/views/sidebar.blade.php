<li
    class="nav-item dropdown {{ isRoute(['admin.prescription-contact','admin.contact-message','admin.contact-messages','admin.contact-info']) ? 'active' : '' }}">
    <a href="javascript:void()" class="nav-link has-dropdown"><i
            class="fas fa-envelope"></i><span>{{ __('Contact') }}</span></a>

    <ul class="dropdown-menu">
        @adminCan('contact.info.view')
        <li class="{{ isRoute('admin.prescription-contact') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.prescription-contact') }}">
                {{ __('Invoice Contact') }}
            </a>
        </li>
        @endadminCan
        @adminCan('contact.message.view')
        <li class="{{ isRoute('admin.contact-messages') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.contact-messages') }}">
                {{ __('Contact Messages') }}
            </a>
        </li>
        @endadminCan
        @adminCan('contact.info.view')
        <li class="{{ isRoute('admin.contact-info') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.contact-info', ['code' => getSessionLanguage()]) }}">
                {{ __('Contact Info') }}
            </a>
        </li>
        @endadminCan
    </ul>
</li>

