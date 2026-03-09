@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Terms Condition')->first()->seo_title ?? $setting->app_name }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Terms Condition')->first()->seo_description ?? $setting->app_name }}">
@endsection
@section('client-content')

<!--Banner Start-->
<div class="banner-area flex" style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="banner-text">
                    <h1>{{ $customPage?->title }}</h1>
                    <ul>
                        <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li><span>{{ $customPage?->title }}</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Banner End-->


<div class="about-style1 pt_50 pb_50">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {!! $customPage?->description !!}
            </div>
        </div>
    </div>
</div>


@endsection
