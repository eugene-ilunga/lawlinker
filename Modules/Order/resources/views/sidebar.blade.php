<li
    class="nav-item dropdown {{ isRoute(['admin.orders', 'admin.order', 'admin.active-orders', 'admin.pending-orders'], 'active') }}">
    <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-shopping-bag"></i>
        <span>{{ __('Manage Order') }} </span>

    </a>
    <ul class="dropdown-menu">
        <li class="{{ isRoute(['admin.orders','admin.order'], 'active') }}"><a class="nav-link"
                href="{{ route('admin.orders') }}">{{ __('Order History') }}</a></li>

        <li class="{{ isRoute('admin.active-orders', 'active') }}"><a class="nav-link"
                href="{{ route('admin.active-orders') }}">{{ __('Successful Order') }}</a></li>
        <li class="{{ isRoute('admin.pending-orders', 'active') }}"><a class="nav-link"
                href="{{ route('admin.pending-orders') }}">{{ __('Pending Order') }}</a></li>
    </ul>
</li>
