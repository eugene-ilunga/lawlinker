@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'Lawyers')->first()?->seo_title ?? 'Lawyers | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'Lawyers')->first()?->seo_description ?? 'Lawyers | LawMent' }}">
@endsection
@section('client-content')

    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('Lawyers ') }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('Lawyers ') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->


    <div class="lawyer-search">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <div class="s-container">
                        <form action="{{ route('website.search.lawyer') }}">

                            <div class="s-box">
                                <select name="location" class="form-control select2">
                                    <option value="">{{ __('Select Location') }}</option>
                                    @foreach ($locations as $location)
                                        <option {{ @$location_id == $location?->id ? 'selected' : '' }}
                                            value="{{ $location?->id }}">{{ ucwords($location?->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="s-box">
                                <select name="department" class="form-control select2">
                                    <option value="">{{ __('Select Department') }}</option>
                                    @foreach ($departments as $department)
                                        <option {{ @$department_id == $department?->id ? 'selected' : '' }}
                                            value="{{ $department?->id }}">{{ ucwords($department?->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="s-box">
                                <select name="lawyer" class="form-control select2">
                                    <option value="">{{ __('Select Lawyer') }}</option>
                                    @foreach ($lawyersForSearch as $lawyer)
                                        <option {{ @$lawyer_id == $lawyer->id ? 'selected' : '' }}
                                            value="{{ $lawyer?->id }}">
                                            {{ ucwords($lawyer?->name) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="s-button">
                                <button type="submit">{{ __('Search') }}</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>




    <!--Service Start-->
    <div class="team-page pb_70">
        <div class="container">
            <div class="row">

                @if ($lawyers->count() != 0)
                    @foreach ($lawyers as $lawyer)
                        <div class="col-lg-3 col-md-4 col-sm-6 mt_30">
                            <div class="team-item">
                                <div class="team-photo">
                                    <img src="{{ url($lawyer?->image ? $lawyer?->image : $setting?->default_avatar) }}"
                                        alt="{{$lawyer?->name}}" loading="lazy">
                                </div>
                                <div class="team-text">
                                    <a aria-label="{{ $lawyer?->name }}"
                                        href="{{ route('website.lawyer.details', $lawyer?->slug) }}">{{ ucfirst($lawyer?->name) }}</a>
                                    <p>{{ ucfirst($lawyer?->department?->name) }}</p>
                                    <p><span><i class="fas fa-graduation-cap"></i> {{ $lawyer?->designations }}</span></p>
                                    <p><span><b><i class="fas fa-street-view"></i>
                                                {{ ucfirst($lawyer?->location?->name) }}</b></span></p>
                                </div>
                                <div class="team-social">
                                    <ul>
                                        @foreach ($lawyer?->socialMedia as $socialMedia)
                                            <li><a target="_blank" aria-label="{{ $socialMedia?->link}}"
                                                    href="{{ $socialMedia?->link }}">
                                                    <i class="{{$socialMedia?->icon}}"></i>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <h3 class="text-danger text-center mt-5">{{ __('Lawyer Not Found') }}</h3>
                @endif


            </div>
            @if ($lawyers->hasPages())
                {{ $lawyers->links('client.paginator') }}
            @endif
        </div>
    </div>
    <!--Service End-->






@endsection
