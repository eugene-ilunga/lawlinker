@extends('layouts.client.layout')
@section('title')
<title>{{ __('Change Password') }}</title>
@endsection
@section('client-content')


<!--Banner Start-->
<div class="banner-area flex" style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1>{{ __('Change Password') }}</h1>
                    <ul>
                        <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li><span>{{ __('Change Password') }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->

<!--Dashboard Start-->
<div class="dashboard-area pt_70 pb_70">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                @include('client.profile.sidebar')
            </div>
            <div class="col-lg-9">
                <div class="detail-dashboard add-form">
                    <h2 class="d-headline">{{ __('Change Password') }}</h2>
                    <form action="{{ route('client.update.password') }}" method="post">
                        @csrf
                        <div class="form-row row">
                            <div class="form-group col-lg-4 col-md-12">
                                <label for="">{{ __('Old Password') }} <span>*</span></label>
                                <input type="password" class="form-control" name="current_password">
                            </div>
                            <div class="form-group col-lg-4 col-md-6">
                                <label for="">{{ __('New Password') }} <span>*</span></label>
                                <input type="password" class="form-control" name="password">
                            </div>
                            <div class="form-group col-lg-4 col-md-6">
                                <label for="">{{ __('Confirmed Password') }} <span>*</span></label>
                                <input type="password" class="form-control" name="password_confirmation">
                            </div>
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            </div>
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!--Dashboard End-->

@endsection
