<div class="dashboard-widget">
    <div class="dashboard-account-info">
            <img src="{{ !empty($user?->image) ? asset($user?->image) : asset($setting?->default_avatar)}}" alt="{{ $user?->name }}">
            <h3>{{ ucfirst($user?->name) }}</h3>
            <p>{{ __('Client ID') }}: {{ $user?->client_id }}</p>
    </div>
     <ul>
         <li class="{{ isRoute('dashboard') ?'active':'' }}"><a href="{{ route('dashboard') }}"><i class="fas fa-chevron-right"></i> {{ __('Dashboard') }}</a></li>
        @php
            $un_seen_message = App\Models\Message::where(['user_id' => userAuth()?->id, 'user_view' => 0])->count();
        @endphp
         <li class="{{ isroute('client.message') ?'active':'' }}"><a href="{{ route('client.message') }}"><i class="fas fa-chevron-right"></i> <span class="beep @if($un_seen_message) parent @endif">{{ __('Message') }}</span></a>
        </li>


         <li class="{{ isroute('client.meeting-history') ?'active':'' }}"><a href="{{ route('client.meeting-history') }}"><i class="fas fa-chevron-right"></i> {{ __('Meeting History') }}</a></li>

         <li class="{{ isroute('client.upcomming-meeting') ?'active':'' }}"><a href="{{ route('client.upcomming-meeting') }}"><i class="fas fa-chevron-right"></i> {{ __('Upcoming Meeting') }} </a></li>

         <li class="{{ isRoute(['client.appointment','client.show.appointment']) ?'active':'' }}"><a href="{{ route('client.appointment') }}"><i class="fas fa-chevron-right"></i> {{ __('Appointment List') }}</a></li>
         <li class="{{ isroute('client.order') ?'active':'' }}"><a href="{{ route('client.order') }}"><i class="fas fa-chevron-right"></i> {{ __('Order List') }}</a></li>
         <li class="{{ isroute('client.change.password') ?'active':'' }}"><a href="{{ route('client.change.password') }}"><i class="fas fa-chevron-right"></i> {{ __('Change Password') }}</a></li>


         <li><a href="javascript:;" onclick="event.preventDefault();$('#logout-form').trigger('submit');"><i class="fas fa-chevron-right"></i> {{ __('Logout') }}</a></li>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            {{ csrf_field() }}
        </form>
     </ul>
 </div>
