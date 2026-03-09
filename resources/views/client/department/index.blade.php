@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Department')->first()?->seo_title ?? 'Department | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Department')->first()?->seo_description ?? 'Department | LawMent' }}">
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Departments') }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Departments') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <div class="case-study-home-page case-study-area pt_50">
        <div class="container">
            <div class="row">
                @foreach ($departments as $department)
                    <div class="col-lg-4 col-md-6 mt_15">
                        <div class="case-item">
                            <div class="case-box">
                                <div class="case-image">
                                    <img src="{{ $department?->thumbnail_image }}" alt="" loading="lazy">
                                    <div class="overlay"><a aria-label="{{ $department?->name }}"
                                            href="{{ route('website.department.details', $department?->slug) }}"
                                            class="btn-case">{{ __('See Details') }}</a>
                                    </div>
                                </div>
                                <div class="case-content">
                                    <h4 class="title"><a aria-label="{{ $department?->name }}"
                                            href="{{ route('website.department.details', $department?->slug) }}">{{ $department?->name }}</a>
                                    </h4>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach



            </div>
            <div class="mb-5">
                @if ($departments->hasPages())
                    {{ $departments->links('client.paginator') }}
                @endif
            </div>

        </div>
    </div>
@endsection
