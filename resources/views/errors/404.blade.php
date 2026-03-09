@extends('layouts.client.layout')
@section('title')
    <title>{{ __('Page Not Found') . ' | ' . $setting->app_name }}</title>
@endsection
@section('client-content')
    <!--Banner End-->

    <div class="about-style1 pt_50 pb_50">
        <div class="container">
            <div class="row">
                <div class="col-xxl-8 col-md-10 col-lg-9 col-xl-8 mx-auto my-3">
                    <div class="img">
                        <img src="{{ asset($setting?->error_page_image) }}" alt="{{ __('Page Not Found') }}"
                            class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
