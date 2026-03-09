@php
    $lawyer = lawyerAuth();
    $un_seen_message = App\Models\Message::where([
        'lawyer_id' => $lawyer?->id,
        'lawyer_view' => 0,
    ])->count();
    $not_treated = $lawyer->appointments()->paymentSuccess()->notTreated()->count();

    $now = now();
    $tenMinutesLater = now()->addMinutes(10);
    $upcoming_meeting = $lawyer->meeting_history()->where('meeting_time', '>', $now)->where('meeting_time', '<=', $tenMinutesLater)->count();
@endphp
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{ route('lawyer.dashboard') }}"><img class="w-75" src="{{ asset($setting->logo) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{ route('lawyer.dashboard') }}"><img src="{{ asset($setting->favicon) ?? '' }}"
                    alt="{{ $setting->app_name ?? '' }}"></a>
        </div>

        <ul class="sidebar-menu">
            <li class="{{ isroute('lawyer.dashboard', 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.dashboard') }}"><i class="fas fa-home"></i>
                    <span>{{ __('Dashboard') }}</span>
                </a>
            </li>
            <li
                class="{{ isRoute(['lawyer.today.appointment', 'lawyer.treatment', 'lawyer.treatment.edit'], 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.today.appointment') }}"><i class="fas fa-calendar-day"></i>
                    <span>{{ __('Today Appointment') }}</span>
                </a>
            </li>
            <li
                class="nav-item dropdown {{ isRoute(['lawyer.new.appointment', 'lawyer.not.treated.appointment', 'lawyer.all.appointment', 'lawyer.old.appointment'], 'active') }}">
                <a href="javascript:;" class="nav-link has-dropdown">
                    <i class="fas fa-calendar"></i>
                    <span class="{{ $not_treated > 0 ? 'beep' : '' }}">{{ __('Manage Appointment') }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ isroute('lawyer.new.appointment', 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.new.appointment') }}">
                            {{ __('New Appointment') }}
                        </a>
                    </li>
                    <li class="{{ isroute('lawyer.not.treated.appointment', 'active') }}">
                        <a class="nav-link text-capitalize" href="{{ route('lawyer.not.treated.appointment') }}">
                            {{ __('Not Consulted') }}  
                            @if ($not_treated)
                                <small class="ms-2 badge bg-danger">{{ $not_treated }}</small>
                            @endif
                        </a>
                    </li>
                    <li class="{{ isroute('lawyer.all.appointment', 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.all.appointment') }}">
                            {{ __('Appointment History') }}
                        </a>
                    </li>
                </ul>
            </li>
            <li
                class="nav-item dropdown {{ isRoute(['lawyer.zoom-credential', 'lawyer.zoom-meetings', 'lawyer.create-zoom-meeting', 'lawyer.upcomming-meeting', 'lawyer.meeting-history'], 'active') }}">
                <a href="javascript:;" class="nav-link has-dropdown">
                    <i class="fas fa-video"></i>
                    <span class="{{ $upcoming_meeting > 0 ? 'beep' : '' }}">{{ __('Live Consultation') }}</span>
                </a>
                <ul class="dropdown-menu">
                    <li class="{{ isRoute(['lawyer.zoom-meetings', 'lawyer.create-zoom-meeting'], 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.zoom-meetings') }}">
                            {{ __('Zoom Meeting') }}
                        </a>
                    </li>
                    <li class="{{ isroute('lawyer.upcomming-meeting', 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.upcomming-meeting') }}">
                            {{ __('Upcoming Meeting') }}
                            @if ($upcoming_meeting)
                                <small class="ms-2 badge bg-danger">{{ $upcoming_meeting }}</small>
                            @endif
                        </a>
                    </li>
                    <li class="{{ isroute('lawyer.meeting-history', 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.meeting-history') }}">
                            {{ __('Meeting History') }}
                        </a>
                    </li>
                    <li class="{{ isroute('lawyer.zoom-credential', 'active') }}">
                        <a class="nav-link" href="{{ route('lawyer.zoom-credential') }}">
                            {{ __('Setting') }}
                        </a>
                    </li>
                </ul>
            </li>
            @if (Module::isEnabled('Leave'))
                @include('leave::sidebar')
            @endif
            <li class="{{ isroute('lawyer.payment.history', 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.payment.history') }}"><i class="fas fa-credit-card"></i>
                    <span>{{ __('Payment History') }}</span>
                </a>
            </li>
            <li class="{{ isroute('lawyer.withdraw.*', 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.withdraw.index') }}"><i class="fas fa-money-bill-wave"></i>
                    <span>{{ __('My Withdraw') }}</span>
                </a>
            </li>
            <li class="{{ isroute('lawyer.schedule', 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.schedule') }}"><i class="fas fa-clipboard-list"></i>
                    <span>{{ __('My Schedule') }}</span>
                </a>
            </li>
            <li class="{{ isroute('lawyer.message.index', 'active') }}">
                <a class="nav-link" href="{{ route('lawyer.message.index') }}"><i class="fas fa-envelope"></i>
                    <span class="{{ $un_seen_message > 0 ? 'beep' : '' }}">{{ __('Message') }}</span>
                </a>
            </li>
            @if ($setting->lawyer_can_add_social_links == 'active')
                <li class="{{ isroute('lawyer.social-link.*', 'active') }}">
                    <a class="nav-link" href="{{ route('lawyer.social-link.index') }}">
                        <i class="fas fa-hashtag"></i> <span>{{ __('Social Links') }}</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>
