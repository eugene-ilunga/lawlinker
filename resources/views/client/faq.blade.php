@extends('layouts.client.layout')
@section('title')
    <title>{{ seoSetting()->where('page_name', 'FaQ')->first()?->seo_title ?? 'FaQ | LawMent' }}</title>
@endsection
@section('meta')
    <meta name="description"
        content="{{ seoSetting()->where('page_name', 'FaQ')->first()?->seo_description ?? 'FaQ | LawMent' }}">
@endsection
@section('client-content')
    <!--Banner Start-->
    <div class="banner-area flex"
        style="background-image:url({{ $setting?->breadcrumb_image ? url($setting?->breadcrumb_image) : '' }});">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="banner-text">
                        <h1>{{ __('FAQ') }}</h1>
                        <ul>
                            <li><a aria-label="{{ __('Home') }}" href="{{ url('/') }}">{{ __('Home') }}</a></li>
                            <li><span>{{ __('FAQ') }}</span></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Banner End-->

    <!--Faq Start-->
    <div class="faq-area pt_100 pb_100">
        <div class="container">
            <div class="row">
                <div class="col-lg-7">
                    <div class="faq_page_item">
                        <h2>{{__('Frequently asked question')}}</h2>
                        <div class="faq-service feature-section-text">
                            <div class="feature-accordion" id="accordion">
                                @foreach ($faqCategories as $index => $category)
                                    @if ($category->faq_list->count() != 0)
                                        <div class="faq-single-item">
                                            <h2>{{ $category?->title }}</h2>
                                            @foreach ($category->faq_list as $faq)
                                                <div class="faq-item card">
                                                    <div class="faq-header" id="heading->{{ $faq?->id }}">
                                                        <button class="faq-button collapsed" data-bs-toggle="collapse"
                                                            data-bs-target="#collapse-{{ $faq?->id }}"
                                                            aria-expanded="true"
                                                            aria-controls="collapse-{{ $faq?->id }}">{{ $faq?->question }}</button>
                                                    </div>
                                                    <div id="collapse-{{ $faq?->id }}" class="collapse"
                                                        aria-labelledby="heading->{{ $faq?->id }}"
                                                        data-bs-parent="#accordion">
                                                        <div class="faq-body">
                                                            {!! $faq?->answer !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                @endforeach

                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="faq_img">
                        <img src="{{asset($faq_page?->image)}}" alt="Faq" class="img-fluid w-100">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--Faq End-->
@endsection
