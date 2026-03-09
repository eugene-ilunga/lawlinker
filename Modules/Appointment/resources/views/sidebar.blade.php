<li
    class="nav-item dropdown {{ isRoute(['admin.day.index', 'admin.schedule.index', 'admin.appointment.*', 'admin.prescribe*', 'admin.payment.history'], 'active') }}">
    <a href="javascript:void()" class="nav-link has-dropdown"><i
            class="fas fa-calendar"></i><span>{{ __('Appointment') }}</span></a>
    <ul class="dropdown-menu">
        @adminCan('appointment.view')
        <li class="{{ isRoute('admin.appointment.index', 'active') }}">
            <a class="nav-link" href="{{ route('admin.appointment.index') }}">
                {{ __('All Appointment') }}
            </a>
        </li>
        @endadminCan
        @adminCan('appointment.view')
        <li class="{{ isRoute('admin.appointment.new', 'active') }}">
            <a class="nav-link" href="{{ route('admin.appointment.new') }}">
                {{ __('New Appointments') }}
            </a>
        </li>
        @endadminCan
        @adminCan('appointment.view')
        <li class="{{ isRoute('admin.appointment.pending', 'active') }}">
            <a class="nav-link" href="{{ route('admin.appointment.pending') }}">
                {{ __('Pending Appointments') }}
            </a>
        </li>
        @endadminCan
        @adminCan('appointment.view')
        <li class="{{ isRoute('admin.prescribe*', 'active') }}">
            <a class="nav-link" href="{{ route('admin.prescribe') }}">
                {{ __('Consultation Notes')  }}
            </a>
        </li>
        @endadminCan
        @adminCan('payment.view')
        <li class="{{ isRoute('admin.payment.history', 'active') }}">
            <a class="nav-link" href="{{ route('admin.payment.history') }}">
                {{ __('Payment History') }}
            </a>
        </li>
        @endadminCan
        @if (Module::isEnabled('Schedule') && checkAdminHasPermission('schedule.view'))
            @include('schedule::sidebar')
        @endif
        @if (Module::isEnabled('Day') && checkAdminHasPermission('day.view'))
            @include('day::sidebar')
        @endif
    </ul>
</li>
@adminCan('zoom.meeting')
<li
    class="nav-item dropdown {{ isRoute(['admin.upcomming-meeting','admin.previous-meeting',], 'active') }}">
    <a href="javascript:void()" class="nav-link has-dropdown"><i
            class="fas fa-video"></i><span>{{ __('Zoom Meeting') }}</span></a>
    <ul class="dropdown-menu">
        <li class="{{ isRoute('admin.upcomming-meeting', 'active') }}">
            <a class="nav-link" href="{{ route('admin.upcomming-meeting') }}">
                {{ __('Upcomming Meeting') }}
            </a>
        </li>
        <li class="{{ isRoute('admin.previous-meeting', 'active') }}">
            <a class="nav-link" href="{{ route('admin.previous-meeting') }}">
                {{ __('Previous Meeting') }}
            </a>
        </li>
    </ul>
</li>
@endadminCan
