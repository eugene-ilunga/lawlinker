<li class="{{ isRoute('admin.schedule.index', 'active') }}">
    <a class="nav-link" href="{{ route('admin.schedule.index', ['code' => getSessionLanguage()]) }}">
        {{ __('Schedules') }}
    </a>
</li>
