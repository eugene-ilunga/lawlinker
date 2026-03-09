<li class="{{ isRoute('admin.day.index', 'active') }}">
    <a class="nav-link" href="{{ route('admin.day.index', ['code' => getSessionLanguage()]) }}">
        {{ __('Days') }}
    </a>
</li>
