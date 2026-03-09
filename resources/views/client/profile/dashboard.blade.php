@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Dashboard') }}</title>
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Dashboard') }}</h1>
                        <ul>
                            <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Dashboard') }}</span></li>
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
                    <div class="detail-dashboard pb-0 pt-4 px-4">
                        <div class="row">
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="dash-item db-yellow flex">
                                    <i class="fas fa-handshake"></i>
                                    <h2>{{ $orders->count() }}</h2>
                                    <h4>{{ __('Total Order') }}</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="dash-item db-red flex">
                                    <i class="fas fa-hourglass-start"></i>
                                    <h2>{{ $appointments->where('payment_status', 0)->count() }}</h2>
                                    <h4>{{ __('Pending Appointment') }}</h4>

                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="dash-item db-blue flex">
                                    <i class="fas fa-check-circle"></i>
                                    <h2>{{ $appointments->count() }}</h2>
                                    <h4>{{ __('Total Appointment') }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profile_info_area">

                        <div class="wsus__profile_info profile_info detail-dashboard">
                            <div class="wsus__profile_info_top">
                                <h2 class="d-headline">{{ __('Personal Information') }}</h2>
                                <a href="javascript:;" class="edit_btn edit_profile">{{ __('Edit info') }}</a>
                            </div>

                            <ul class="">
                                <li><span>{{ __('Name') }}:</span>{{ $user?->name }}</li>
                                <li><span>{{ __('Phone ') }}:</span>{{ $user?->details?->phone }}</li>
                                <li class="text-lowercase"><span
                                        class="text-capitalize">{{ __('Email') }}:</span>{{ $user?->email }}</li>
                                <li><span>{{ __('Gender ') }}:</span>{{ $user?->details?->phone }}</li>
                                <li><span>{{ __('Date Of Birth ') }}:</span>{{ $user?->details?->date_of_birth }}</li>
                                <li><span>{{ __('Occupation ') }}:</span>{{ $user?->details?->occupation }}</li>
                                <li><span>{{ __('Age ') }}:</span>{{ $user?->details?->age }}</li>
                                <li><span>{{ __('Country ') }}:</span>{{ $user?->details?->country }}</li>
                                <li><span>{{ __('City ') }}:</span>{{ $user?->details?->city }}</li>
                                <li><span>{{ __('Address') }}:</span>{{ $user?->details?->address }}</li>
                            </ul>
                        </div>

                        <div class="detail-dashboard add-form profile_edit_area mt_25">
                            <div class="wsus__profile_info_top">
                                <h2 class="d-headline">{{ __('My Profile') }}</h2>
                                <a href="javascript:;" class="edit_btn del_btn">{{ __('Cancel') }}</a>
                            </div>
                            <form action="{{ route('client.update.profile') }}" method="post"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="form-row row">
                                    <div class="form-group col-md-6">
                                        <label for="name">{{ __('Name') }} <span>*</span></label>
                                        <input type="text" class="form-control" id="name" name="name"
                                            value="{{ $user?->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="email">{{ __('Email') }} <span>*</span></label>
                                        <input type="text" class="form-control" id="email" name="email"
                                            value="{{ $user?->email }}" readonly>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Phone ') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="phone"
                                            value="{{ $user?->details?->phone }}">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Age') }}<span>*</span></label>
                                        <input type="number" class="form-control" name="age"
                                            value="{{ $user?->details?->age }}">

                                    </div>
                                    <div class="form-group col-md-6 option-item">
                                        <label for="">{{ __('Date Of Birth') }} </label>
                                        <input type="text" class="form-control datepicker2" name="date_of_birth"
                                            value="{{ $user?->details?->date_of_birth }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Occupation') }}<span>*</span></label>
                                        <input type="text" class="form-control" name="occupation"
                                            value="{{ $user?->details?->occupation }}">

                                    </div>
                                    <div class="form-group col-md-6 option-item">
                                        <label for="">{{ __('Gender') }} <span>*</span></label>
                                        <select class="form-control" name="gender">
                                            <option value="">{{ __('Select gender') }}</option>
                                            <option {{ $user?->details?->gender == 'male' ? 'selected' : '' }}
                                                value="male">
                                                {{ __('Male') }}</option>
                                            <option {{ $user?->details?->gender == 'female' ? 'selected' : '' }}
                                                value="female">
                                                {{ __('Female') }}</option>
                                            <option {{ $user?->details?->gender == 'others' ? 'selected' : '' }}
                                                value="others">
                                                {{ __('Others') }}</option>
                                        </select>

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Guardian Name') }}</label>
                                        <input type="text" class="form-control" name="guardian_name"
                                            value="{{ $user?->details?->guardian_name }}">

                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Guardian Phone') }}</label>
                                        <input type="text" class="form-control" name="guardian_phone"
                                            value="{{ $user?->details?->guardian_phone }}">

                                    </div>
                                    <div class="form-group col-md-6 option-item">
                                        <label for="">{{ __('Country') }} <span>*</span></label>
                                        <input type="text" name="country" class="form-control"
                                            value="{{ $user?->details?->country }}">

                                    </div>
                                    <div class="form-group col-md-6 option-item">
                                        <label for="">{{ __('City') }} <span>*</span></label>
                                        <input type="text" name="city" placeholder="City" class="form-control"
                                            value="{{ $user?->details?->city }}">

                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="">{{ __('Photo') }} <code>({{ __('Recommended') }}: 400X400 PX)</code></label>
                                        <input type="file" class="form-control" name="image">
                                        <input type="hidden" name="old_image" value="{{ $user?->image }}">

                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="">{{ __('Address') }} <span>*</span></label>
                                        <input type="text" class="form-control" name="address"
                                            value="{{ $user?->details?->address }}">

                                    </div>

                                    <div class="form-group col-md-12 mb-0">
                                        <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
    <!--Dashboard End-->
@endsection
